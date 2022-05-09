<?php

declare(strict_types=1);

namespace App\Sentry\Handler;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Sentry\Breadcrumb;
use Sentry\ClientInterface;
use Sentry\Event as SentryEvent;
use Sentry\ExceptionDataBag;
use Sentry\Severity;
use Sentry\State\HubInterface;
use Sentry\State\Scope;
use Throwable;

use function array_filter;
use function array_merge;
use function array_reduce;

final class MonologHandler extends AbstractProcessingHandler
{
    private array $breadcrumbsBuffer = [];

    public function __construct(
        private readonly HubInterface $hub,
        int $level = Logger::DEBUG,
        bool $bubble = true,
    ) {
        parent::__construct($level, $bubble);
    }

    public function handleBatch(array $records): void
    {
        $records = array_filter($records, fn($record) => $record['level'] >= $this->level);

        if ([] === $records) {
            return;
        }

        // the record with the highest severity is the "main" one
        $main = array_reduce(
            $records,
            static function ($highest, $record) {
                if (null === $highest || $record['level'] > $highest['level']) {
                    return $record;
                }

                return $highest;
            },
        );

        // the other ones are added as a context items
        foreach ($records as $record) {
            $record = $this->processRecord($record);
            $record['formatted'] = $this->getFormatter()->format($record);

            $this->breadcrumbsBuffer[] = $record;
        }

        $this->handle($main);

        $this->breadcrumbsBuffer = [];
    }

    protected function write(array $record): void
    {
        $sentryEvent = SentryEvent::createEvent();
        $sentryEvent->setLevel($sentryLevel = $this->getSeverityFromLevel((int) $record['level']));
        $sentryEvent->setMessage((new LineFormatter('%channel%.%level_name%: %message%'))->format($record));

        if (isset($record['context']['exception']) && $record['context']['exception'] instanceof Throwable) {
            $bag = new ExceptionDataBag($record['context']['exception']);
            $sentryEvent->setExceptions([$bag]);
        }

        $this->hub->withScope(function (Scope $scope) use ($record, $sentryEvent, $sentryLevel): void {
            $scope->setLevel($sentryLevel);
            $scope->setExtra('monolog.formatted', $record['formatted'] ?? '');

            foreach ($this->breadcrumbsBuffer as $breadcrumbRecord) {
                $context = array_merge($breadcrumbRecord['context'], $breadcrumbRecord['extra']);

                $breadcrumb = new Breadcrumb(
                    $this->getBreadcrumbLevelFromLevel((int) $breadcrumbRecord['level']),
                    $this->getBreadcrumbTypeFromLevel((int) $breadcrumbRecord['level']),
                    (string) $breadcrumbRecord['channel'] ?: 'N/A',
                    (string) $breadcrumbRecord['message'] ?: 'N/A',
                    $context,
                );

                $scope->addBreadcrumb($breadcrumb);
            }

            $this->hub->captureEvent($sentryEvent);
        });

        $client = $this->hub->getClient();

        if ($client instanceof ClientInterface) {
            $client->flush();
        }
    }

    private function getSeverityFromLevel(int $level): Severity
    {
        return match ($level) {
            Logger::DEBUG                => Severity::debug(),
            Logger::INFO, Logger::NOTICE => Severity::info(),
            Logger::WARNING              => Severity::warning(),
            Logger::ERROR                => Severity::error(),
            default                      => Severity::fatal(),
        };
    }

    private function getBreadcrumbLevelFromLevel(int $level): string
    {
        return match ($level) {
            Logger::DEBUG                => Breadcrumb::LEVEL_DEBUG,
            Logger::INFO, Logger::NOTICE => Breadcrumb::LEVEL_INFO,
            Logger::WARNING              => Breadcrumb::LEVEL_WARNING,
            Logger::ERROR                => Breadcrumb::LEVEL_ERROR,
            default                      => Breadcrumb::LEVEL_FATAL,
        };
    }

    private function getBreadcrumbTypeFromLevel(int $level): string
    {
        return $level >= Logger::ERROR ? Breadcrumb::TYPE_ERROR : Breadcrumb::TYPE_DEFAULT;
    }
}
