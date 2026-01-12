<?php

namespace App\Service;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;

class LibraryService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private BookRepository $bookRepository
    ) {
    }

    public function createBook(
        string $title,
        string $isbn,
        string $author,
        string $image
    ): void {
        $book = new Book();
        $book->setTitle($title);
        $book->setIsbn($isbn);
        $book->setAuthor($author);
        $book->setImage($image);

        $this->entityManager->persist($book);
        $this->entityManager->flush();
    }

    public function updateBook(
        Book $book,
        string $title,
        string $isbn,
        string $author,
        string $image
    ): void {
        $book->setTitle($title);
        $book->setIsbn($isbn);
        $book->setAuthor($author);
        $book->setImage($image);

        $this->entityManager->flush();
    }

    public function deleteBook(Book $book): void
    {
        $this->entityManager->remove($book);
        $this->entityManager->flush();
    }

    public function resetLibrary(): void
    {
        foreach ($this->bookRepository->findAll() as $book) {
            $this->entityManager->remove($book);
        }

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

        foreach ($booksData as $data) {
            $book = new Book();
            $book->setTitle($data['title']);
            $book->setIsbn($data['isbn']);
            $book->setAuthor($data['author']);
            $book->setImage($data['image']);

            $this->entityManager->persist($book);
        }

        $this->entityManager->flush();
    }
}
