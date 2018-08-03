<?php

declare(strict_types=1);

namespace Book\Infrastructure\Api\ApiPlatform\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use Book\Infrastructure\Api\ApiPlatform\Resource\Review;
use Book\Infrastructure\Projection\Doctrine\Orm\Entity\Review as Entity;
use Book\Infrastructure\Projection\Doctrine\Orm\ReviewFinder;

final class ReviewDataProvider implements CollectionDataProviderInterface, ItemDataProviderInterface, RestrictedDataProviderInterface
{
    private $finder;

    public function __construct(ReviewFinder $finder)
    {
        $this->finder = $finder;
    }

    /**
     * {@inheritdoc}
     */
    public function getCollection(string $resourceClass, string $operationName = null)
    {
        return array_map(function (Entity $review) {
            $item = new Review();
            $item->id = $review->getId();
            $item->body = $review->getBody();
            $item->rating = $review->getRating();
            $item->author = $review->getAuthor();

            return $item;
        }, $this->finder->findAll());
    }

    /**
     * {@inheritdoc}
     */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        $review = $this->finder->find($id);

        if (null === $review) {
            return null;
        }

        $item = new Review();
        $item->id = $review->getId();
        $item->body = $review->getBody();
        $item->rating = $review->getRating();
        $item->author = $review->getAuthor();

        return $item;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Review::class === $resourceClass;
    }
}
