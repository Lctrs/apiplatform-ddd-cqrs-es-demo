<?php

declare(strict_types=1);

namespace Book\Infrastructure\Projection\Doctrine\Orm;

use Book\Infrastructure\Projection\Doctrine\Orm\Entity\Book;
use Core\Infrastructure\Projection\Doctrine\Orm\AbstractDoctrineOrmReadModel;
use Doctrine\ORM\EntityManagerInterface;

final class BookReadModel extends AbstractDoctrineOrmReadModel
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Book::class);
    }

    protected function insert(array $data)
    {
        $this->entityManager->persist(new Book(
            $data['id'],
            $data['isbn'],
            $data['title'],
            $data['description'],
            $data['author']
        ));
    }
}
