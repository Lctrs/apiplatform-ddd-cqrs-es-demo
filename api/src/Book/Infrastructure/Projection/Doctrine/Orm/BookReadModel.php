<?php

declare(strict_types=1);

namespace App\Book\Infrastructure\Projection\Doctrine\Orm;

use App\Book\Infrastructure\Projection\Doctrine\Orm\Entity\Book;
use App\Core\Infrastructure\Projection\Doctrine\Orm\AbstractDoctrineOrmReadModel;
use Doctrine\ORM\EntityManagerInterface;

final class BookReadModel extends AbstractDoctrineOrmReadModel
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Book::class);
    }

    protected function insert(array $data): void
    {
        $book = new Book(
            $data['id'],
            $data['isbn'],
            $data['title'],
            $data['description'],
            $data['author']
        );

        $this->entityManager->persist($book);
    }

    protected function remove(string $id): void
    {
        $book = $this->entityManager->find($this->entityClass, $id);

        if (null === $book) {
            return;
        }

        $this->entityManager->remove($book);
    }
}
