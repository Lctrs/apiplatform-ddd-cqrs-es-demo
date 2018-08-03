<?php

declare(strict_types=1);

namespace Book\Infrastructure\Api\ApiPlatform\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use Book\Infrastructure\Api\ApiPlatform\DataProvider\Assembler\BookAssembler;
use Book\Infrastructure\Api\ApiPlatform\Resource\Book;
use Book\Infrastructure\Projection\Doctrine\Orm\BookFinder;

final class BookDataProvider implements CollectionDataProviderInterface, ItemDataProviderInterface, RestrictedDataProviderInterface
{
    private $finder;
    private $assembler;

    public function __construct(BookFinder $finder, BookAssembler $assembler)
    {
        $this->finder = $finder;
        $this->assembler = $assembler;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Book::class === $resourceClass;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        $book = $this->finder->byId($id);

        if (null === $book) {
            return null;
        }

        return $this->assembler->assemble($book);
    }

    /**
     * {@inheritdoc}
     */
    public function getCollection(string $resourceClass, string $operationName = null)
    {
        return array_map([$this->assembler, 'assemble'], $this->finder->all());
    }
}
