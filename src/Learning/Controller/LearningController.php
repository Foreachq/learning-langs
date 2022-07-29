<?php

declare(strict_types=1);

namespace App\Learning\Controller;

use App\Learning\Repository\WordProgressRepository;
use App\Learning\Repository\WordRepository;
use App\Learning\Service\LearningService;
use App\Security\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

use function count;
use function sprintf;

class LearningController extends AbstractController
{
    public function __construct(
        private readonly LearningService $learningService,
        private readonly WordProgressRepository $progressRepository,
        private readonly WordRepository $wordRepository,
    ) {
    }

    /**
     * @param User $user
     */
    #[Route(
        '/learning/',
        name: 'learning_learn',
        methods: [Request::METHOD_GET, Request::METHOD_POST],
    )]
    public function learn(#[CurrentUser] UserInterface $user, Request $request): Response
    {
        $profile = $user->getProfile();

        if ($request->isMethod(Request::METHOD_POST)) {
            $wordId = $request->query->get('word_id');
            $userAnswerId = $request->request->get('answer_id');
            $isWordInEnglish = $request->request->get('is_word_in_english');

            if (null === $wordId || null === $userAnswerId || null === $isWordInEnglish) {
                throw new BadRequestHttpException(
                    'Attributes "word_id", "answer_id", "is_word_in_english" are required.',
                );
            }

            $word = $this->wordRepository->requireOne($wordId);
            $progress = $this->progressRepository->findOneBy([
                'profile' => $profile,
                'word'    => $word,
            ]);

            if (!$progress) {
                throw $this->createNotFoundException(
                    sprintf('User #%s is not learning word #%s.', $profile->getId(), $word->getId()),
                );
            }

            $progress->addAttemptCount();

            if ($wordId === $userAnswerId) {
                $progress->addCorrectAnswersCount();
                $this->addFlash('success', 'Correct!');
            } else {
                $answer = $isWordInEnglish ? $word->getRussian() : $word->getEnglish();

                $this->addFlash('error', sprintf('Incorrect. Correct answer was: %s', $answer));
            }

            $this->progressRepository->save($progress);
        }

        if (!count($this->progressRepository->getActiveProgresses($profile))) {
            $this->addFlash('info', 'Your learning list is empty. Add words to learn.');

            return $this->redirectToRoute('learning_word_progress_new');
        }

        return $this->render(
            '@learning/user/learning/learn.html.twig',
            $this->learningService->prepareQuestionAndAnswers($profile),
        );
    }
}
