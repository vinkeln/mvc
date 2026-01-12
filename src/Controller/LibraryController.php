<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use App\Service\LibraryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LibraryController extends AbstractController
{
    #[Route('/library', name: 'library')]
    public function index(): Response
    {
        return $this->render('library/index.html.twig');
    }

    #[Route('/library/create', name: 'library_create', methods: ['GET'])]
    public function create(): Response
    {
        return $this->render('library/create.html.twig');
    }

    #[Route('/library/create', name: 'library_create_post', methods: ['POST'])]
    public function createPost(
        Request $request,
        LibraryService $libraryService
    ): Response {
        $libraryService->createBook(
            $request->request->get('title'),
            $request->request->get('isbn'),
            $request->request->get('author'),
            $request->request->get('image')
        );

        return $this->redirectToRoute('library_show_all');
    }

    #[Route('/library/show', name: 'library_show_all')]
    public function showAll(BookRepository $bookRepository): Response
    {
        return $this->render('library/show_all.html.twig', [
            'books' => $bookRepository->findAll()
        ]);
    }

    #[Route('/library/show/{id}', name: 'library_show_one')]
    public function showOne(
        BookRepository $bookRepository,
        int $id
    ): Response {
        $book = $this->getBookOr404($bookRepository, $id);

        return $this->render('library/show_one.html.twig', [
            'book' => $book
        ]);
    }

    #[Route('/library/update/{id}', name: 'library_update', methods: ['GET'])]
    public function update(
        BookRepository $bookRepository,
        int $id
    ): Response {
        $book = $this->getBookOr404($bookRepository, $id);

        return $this->render('library/update.html.twig', [
            'book' => $book
        ]);
    }

    #[Route('/library/update/{id}', name: 'library_update_post', methods: ['POST'])]
    public function updatePost(
        Request $request,
        BookRepository $bookRepository,
        LibraryService $libraryService,
        int $id
    ): Response {
        $book = $this->getBookOr404($bookRepository, $id);

        $libraryService->updateBook(
            $book,
            $request->request->get('title'),
            $request->request->get('isbn'),
            $request->request->get('author'),
            $request->request->get('image')
        );

        return $this->redirectToRoute('library_show_one', ['id' => $id]);
    }

    #[Route('/library/delete/{id}', name: 'library_delete', methods: ['POST'])]
    public function delete(
        BookRepository $bookRepository,
        LibraryService $libraryService,
        int $id
    ): Response {
        $book = $this->getBookOr404($bookRepository, $id);

        $libraryService->deleteBook($book);

        return $this->redirectToRoute('library_show_all');
    }

    #[Route('/library/reset', name: 'library_reset')]
    public function reset(LibraryService $libraryService): Response
    {
        $libraryService->resetLibrary();

        return $this->redirectToRoute('library_show_all');
    }

    private function getBookOr404(
        BookRepository $bookRepository,
        int $id
    ): Book {
        $book = $bookRepository->find($id);

        if (!$book) {
            throw $this->createNotFoundException('Book not found');
        }

        return $book;
    }
}
