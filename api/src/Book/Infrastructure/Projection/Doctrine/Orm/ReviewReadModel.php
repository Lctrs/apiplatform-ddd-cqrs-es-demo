<?php

declare(strict_types=1);

namespace Book\Infrastructure\Projection\Doctrine\Orm;

use Book\Infrastructure\Projection\Doctrine\Orm\Entity\Review;
use Core\Infrastructure\Projection\Doctrine\Orm\AbstractDoctrineOrmReadModel;
use Doctrine\ORM\EntityManagerInterface;

final class ReviewReadModel extends AbstractDoctrineOrmReadModel
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Review::class);
    }

    protected function insert(array $data)
    {
        $this->entityManager->persist(new Review(
            $data['id'],
            $data['body'],
            $data['rating'],
            $data['author']
        ));
    }
}
