<?php

declare(strict_types=1);

namespace App\Learning\Repository;

use App\Learning\Entity\Word;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Http\Discovery\Exception\NotFoundException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

use function is_int;

final class WordRepository extends ServiceEntityRepository
{
    public function __construct(private readonly PaginatorInterface $paginator, ManagerRegistry $registry)
    {
        parent::__construct($registry, Word::class);
    }

    public function save(Word $word, bool $isFlush = true): void
    {
        $this->_em->persist($word);

        if ($isFlush) {
            $this->_em->flush();
        }
    }

    public function remove(Word|int $word, bool $isFlush = true): void
    {
        if (is_int($word)) {
            $word = $this->find($word);
        }

        if ($word) {
            $this->_em->remove($word);
        }

        if ($isFlush) {
            $this->_em->flush();
        }
    }

    public function paginate(int $page, ?int $limit = null): PaginationInterface
    {
        $dql = 'SELECT word FROM Learning:Word word';
        $query = $this->_em->createQuery($dql);

        return $this->paginator->paginate($query, $page, $limit);
    }

    public function requireOne(string|int $id): Word
    {
        $word = $this->find($id);

        if (!$word instanceof Word) {
            throw new NotFoundException('Word not found');
        }

        return $word;
    }
}
