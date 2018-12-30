<?php

declare(strict_types=1);

namespace App\Book\Infrastructure\Projection\Doctrine\Orm;

use App\Book\Infrastructure\Projection\Doctrine\Orm\Data\InsertReview;
use App\Book\Infrastructure\Projection\Doctrine\Orm\Entity\Review;
use App\Core\Infrastructure\Projection\Doctrine\Orm\DoctrineOrmReadModel;
use Doctrine\ORM\EntityManagerInterface;

final class ReviewReadModel extends DoctrineOrmReadModel
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Review::class);
    }

    protected function insert(InsertReview $data): void
    {
        $review = new Review(
            $data->id(),
            $data->bookId(),
            $data->body(),
            $data->rating(),
            $data->author()
        );

        $this->entityManager->persist($review);
    }

    protected function remove(string $id): void
    {
        $review = $this->entityManager->getReference($this->entityClass, $id);

        if ($review === null) {
            return;
        }

        $this->entityManager->remove($review);
    }
}
