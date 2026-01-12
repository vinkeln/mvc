<?php

namespace App\Tests\Service;

use App\Entity\Book;
use App\Repository\BookRepository;
use App\Service\LibraryService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class LibraryServiceTest extends TestCase
{
    public function testCreateBookPersistsBook(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $bookRepository = $this->createMock(BookRepository::class);

        $entityManager
            ->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(Book::class));

        $entityManager
            ->expects($this->once())
            ->method('flush');

        $service = new LibraryService(
            $entityManager,
            $bookRepository
        );

        $service->createBook(
            'Test Book',
            '123',
            'Test Author',
            'image.jpg'
        );
    }
}
