<?php

declare(strict_types=1);

namespace Book\Infrastructure\Projection\Doctrine\Orm;

use Book\Infrastructure\Projection\Doctrine\Orm\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;

final class BookFinder
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function all()
    {
        return $this->entityManager->createQuery(sprintf('SELECT b FROM %s b', Book::class))->getResult();
    }

    public function byId(string $id)
    {
        return $this->entityManager->find(Book::class, $id);
    }
}
