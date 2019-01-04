<?php

declare(strict_types=1);

namespace App\Book\Infrastructure\Projection\Doctrine\Orm;

use App\Book\Infrastructure\Projection\Doctrine\Data\InsertBook;
use App\Book\Infrastructure\Projection\Doctrine\Data\RemoveBook;
use App\Book\Infrastructure\Projection\Doctrine\Orm\Entity\Book;
use App\Core\Infrastructure\Projection\Doctrine\Orm\DoctrineOrmReadModel;
use Doctrine\ORM\EntityManagerInterface;

final class BookReadModel extends DoctrineOrmReadModel
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Book::class);
    }

    protected function insert(InsertBook $data): void
    {
        $book = new Book(
            $data->id(),
            $data->isbn(),
            $data->title(),
            $data->description(),
            $data->author()
        );

        $this->entityManager->persist($book);
    }

    protected function remove(RemoveBook $data): void
    {
        $book = $this->entityManager->getReference($this->entityClass, $data->id());

        if ($book === null) {
            return;
        }

        $this->entityManager->remove($book);
    }
}
