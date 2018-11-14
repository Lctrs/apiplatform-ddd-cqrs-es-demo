<?php

declare(strict_types=1);

namespace App\Book\Infrastructure\Projection\Doctrine\Orm;

use App\Book\Infrastructure\Projection\Doctrine\Orm\Entity\Book;
use App\Book\Infrastructure\Projection\Doctrine\Orm\Entity\Review;
use App\Core\Infrastructure\Projection\Doctrine\Orm\AbstractDoctrineOrmReadModel;
use Doctrine\ORM\EntityManagerInterface;

final class ReviewReadModel extends AbstractDoctrineOrmReadModel
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Review::class);
    }

    protected function insert(array $data): void
    {
        $book = $this->entityManager->find(Book::class, $data['bookId']);

        if (null === $book) {
            return;
        }

        $review = new Review(
            $data['id'],
            $data['body'],
            $data['rating'],
            $data['author'],
            $book
        );

        $this->entityManager->persist($review);
    }

    protected function remove(string $id): void
    {
        $review = $this->entityManager->find($this->entityClass, $id);

        if (null === $review) {
            return;
        }

        $this->entityManager->remove($review);
    }
}
