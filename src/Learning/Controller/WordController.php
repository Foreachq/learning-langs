<?php

declare(strict_types=1);

namespace App\Learning\Controller;

use App\Learning\Form\WordType;
use App\Learning\Repository\WordRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use function compact;

class WordController extends AbstractController
{
    public function __construct(private readonly WordRepository $wordRepository)
    {
    }

    #[Route(
        '/words/',
        name: 'learning_word_index',
        methods: [Request::METHOD_GET],
    )]
    public function index(Request $request): Response
    {
        $words = $this->wordRepository->paginate(
            $request->query->getInt('page', 1),
        );

        return $this->render('@learning/word/index.html.twig', compact('words'));
    }

    #[Route(
        '/words/{id}',
        name: 'learning_word_show',
        requirements: ['id' => '\d+'],
        methods: [Request::METHOD_GET],
    )]
    public function show(int $id): Response
    {
        $word = $this->wordRepository->requireOne($id);

        return $this->render('@learning/word/show.html.twig', compact('word'));
    }

    #[Route(
        '/words/{id}/update',
        name: 'learning_word_update',
        requirements: ['id' => '\d+'],
        methods: [Request::METHOD_PUT, Request::METHOD_GET],
    )]
    public function update(Request $request, int $id): Response
    {
        $word = $this->wordRepository->requireOne($id);

        $form = $this->createForm(WordType::class, $word, [
            'action' => $this->generateUrl('learning_word_update', ['id' => $word->getId()]),
            'method' => 'PUT',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->wordRepository->save($word);

            return $this->redirectToRoute('learning_word_show', ['id' => $word->getId()]);
        }

        return $this->renderForm('@learning/word/update.html.twig', compact('form'));
    }

    #[Route(
        '/words/new',
        name: 'learning_word_new',
        methods: [Request::METHOD_POST, Request::METHOD_GET],
    )]
    public function new(Request $request): Response
    {
        $form = $this->createForm(WordType::class, null, [
            'action' => $this->generateUrl('learning_word_new'),
            'method' => 'POST',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->wordRepository->save($form->getData());

            return $this->redirectToRoute('learning_word_index');
        }

        return $this->renderForm('@learning/word/new.html.twig', compact('form'));
    }

    #[Route(
        '/words/{id}/delete',
        name: 'learning_word_delete',
        requirements: ['id' => '\d+'],
        methods: [Request::METHOD_DELETE],
    )]
    public function delete(int $id): Response
    {
        $this->wordRepository->remove($id);

        return $this->redirectToRoute('learning_word_index');
    }
}
