<?php

declare(strict_types=1);

namespace Book\Infrastructure\Projection\Doctrine\Orm;

use Book\Infrastructure\Projection\Doctrine\Orm\Entity\Review;
use Doctrine\ORM\EntityManagerInterface;

final class ReviewFinder
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findAll(): array
    {
        return $this->entityManager->createQuery(sprintf('SELECT r FROM %s r', Review::class))->getResult();
    }

    public function find(string $id): ?Review
    {
        return $this->entityManager->find(Review::class, $id);
    }
}
