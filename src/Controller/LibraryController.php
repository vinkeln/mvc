<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
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
    public function createBook(): Response
    {
        return $this->render('library/create.html.twig');
    }

    #[Route('/library/create', name: 'library_create_post', methods: ['POST'])]
    public function createBookPost(
        Request $request,
        ManagerRegistry $doctrine
    ): Response {
        $entityManager = $doctrine->getManager();

        $book = new Book();
        $book->setTitle($request->request->get('title'));
        $book->setIsbn($request->request->get('isbn'));
        $book->setAuthor($request->request->get('author'));
        $book->setImage($request->request->get('image'));

        $entityManager->persist($book);
        $entityManager->flush();

        return $this->redirectToRoute('library_show_all');
    }

    #[Route('/library/show', name: 'library_show_all')]
    public function showAllBooks(BookRepository $bookRepository): Response
    {
        $books = $bookRepository->findAll();

        return $this->render('library/show_all.html.twig', [
            'books' => $books
        ]);
    }

    #[Route('/library/show/{id}', name: 'library_show_one')]
    public function showOneBook(BookRepository $bookRepository, int $id): Response
    {
        $book = $bookRepository->find($id);

        if (!$book) {
            throw $this->createNotFoundException('Book not found');
        }

        return $this->render('library/show_one.html.twig', [
            'book' => $book
        ]);
    }

    #[Route('/library/update/{id}', name: 'library_update', methods: ['GET'])]
    public function updateBook(BookRepository $bookRepository, int $id): Response
    {
        $book = $bookRepository->find($id);

        if (!$book) {
            throw $this->createNotFoundException('Book not found');
        }

        return $this->render('library/update.html.twig', [
            'book' => $book
        ]);
    }

    #[Route('/library/update/{id}', name: 'library_update_post', methods: ['POST'])]
    public function updateBookPost(
        Request $request,
        ManagerRegistry $doctrine,
        int $id
    ): Response {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Book::class)->find($id);

        if (!$book) {
            throw $this->createNotFoundException('Book not found');
        }

        $book->setTitle($request->request->get('title'));
        $book->setIsbn($request->request->get('isbn'));
        $book->setAuthor($request->request->get('author'));
        $book->setImage($request->request->get('image'));

        $entityManager->flush();

        return $this->redirectToRoute('library_show_one', ['id' => $id]);
    }

    #[Route('/library/delete/{id}', name: 'library_delete', methods: ['POST'])]
    public function deleteBook(
        ManagerRegistry $doctrine,
        int $id
    ): Response {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Book::class)->find($id);

        if (!$book) {
            throw $this->createNotFoundException('Book not found');
        }

        $entityManager->remove($book);
        $entityManager->flush();

        return $this->redirectToRoute('library_show_all');
    }

    #[Route('/library/reset', name: 'library_reset')]
    public function resetLibrary(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        // Ta bort alla böcker
        $books = $entityManager->getRepository(Book::class)->findAll();
        foreach ($books as $book) {
            $entityManager->remove($book);
        }
        $entityManager->flush();

        // Lägger till böcker
        $booksData = [
            [
                'title' => 'Harry Potter and the Philosopher\'s Stone',
                'isbn' => '978-0439708180',
                'author' => 'J.K. Rowling',
                'image' => 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1598823299i/42844155.jpg'
            ],
            [
                'title' => 'Fourth Wing',
                'isbn' => '978-1649374042',
                'author' => 'Rebecca Yarros',
                'image' => 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1701980900i/61431922.jpg'
            ],
            [
                'title' => 'The Lord of the Rings',
                'isbn' => '978-0618640157',
                'author' => 'J.R.R. Tolkien',
                'image' => 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1566425108i/33.jpg'
            ]
        ];

        foreach ($booksData as $bookData) {
            $book = new Book();
            $book->setTitle($bookData['title']);
            $book->setIsbn($bookData['isbn']);
            $book->setAuthor($bookData['author']);
            $book->setImage($bookData['image']);
            $entityManager->persist($book);
        }

        $entityManager->flush();

        return $this->redirectToRoute('library_show_all');
    }
}
