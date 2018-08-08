<?php

declare(strict_types=1);

namespace Book\Infrastructure\Projection\Doctrine\Orm;

use Book\Infrastructure\Projection\Doctrine\Orm\Entity\Book;
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
        $review = new Review();
        $review->id = $data['id'];
        $review->setBook($this->entityManager->find(Book::class, $data['bookId']));
        $review->body = $data['body'];
        $review->rating = $data['rating'];
        $review->author = $data['author'];

        $this->entityManager->persist($review);
    }
}
