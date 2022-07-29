<?php

declare(strict_types=1);

namespace App\Learning\Controller;

use App\Learning\Repository\WordProgressRepository;
use App\Learning\Repository\WordRepository;
use App\Learning\Service\WordProgressService;
use App\Profile\Entity\Profile;
use App\Security\Entity\User;
use App\Security\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

use function compact;

class WordProgressController extends AbstractController
{
    public function __construct(
        private readonly WordProgressService $progressService,
        UserRepository $userRepository,
        private readonly Security $security,
    ) {
    }

    #[Route(
        '/words_progress/',
        name: 'learning_word_progress_index',
        methods: [Request::METHOD_GET],
    )]
    public function index(Request $request, WordProgressRepository $progressRepository): Response
    {
        $progresses = $progressRepository->getActiveProgressesPaginated(
            $this->getProfile(),
            $request->query->getInt('page', 1),
        );

        return $this->render(
            '@learning/user/word_progress/index.html.twig',
            compact('progresses'),
        );
    }

    #[Route(
        '/words_progress/edit',
        name: 'learning_word_progress_edit',
        methods: [Request::METHOD_GET, Request::METHOD_PATCH],
    )]
    public function editList(Request $request, WordProgressRepository $progressRepository): Response
    {
        $wordsProgress = $this->getProfile()->getWordsProgress();

        if ($request->isMethod(Request::METHOD_PATCH)) {
            foreach ($wordsProgress as $progress) {
                $isSetActive = $request->request->has((string) $progress->getWord()->getId());
                $progress->setActive($isSetActive);

                $progressRepository->save($progress);
            }

            return $this->redirectToRoute('learning_word_progress_index');
        }

        return $this->render('@learning/user/word_progress/edit.html.twig', compact('wordsProgress'));
    }

    #[Route(
        '/words_progress/new',
        name: 'learning_word_progress_new',
        methods: [Request::METHOD_GET, Request::METHOD_POST],
    )]
    public function new(
        Request $request,
        WordRepository $wordRepository,
    ): Response {
        $profile = $this->getProfile();

        if ($request->isMethod(Request::METHOD_POST)) {
            $wordId = $request->query->get('id');

            if (!$wordId) {
                throw new BadRequestHttpException('Param "id" is required.');
            }

            $word = $wordRepository->requireOne($wordId);

            if (!$this->progressService->isWordBeingLearnt($word, $profile)) {
                $this->progressService->addWordToLearning($word, $profile);

                $this->addFlash('info', 'Added new word in your dictionary.');
            } else {
                $this->addFlash('info', 'You are learning this word already.');
            }

            return $this->redirectToRoute('learning_word_progress_new');
        }

        $words = $wordRepository->getNotLearningWordsByProfile(
            $profile,
            $request->query->getInt('page', 1),
        );

        return $this->render('@learning/user/word_progress/new.html.twig', compact('words'));
    }

    private function getProfile(): Profile
    {
        /** @var User $user */
        $user = $this->security->getUser();

        return $user->getProfile();
    }
}
