<?php

declare(strict_types=1);

namespace Book\Infrastructure\Api\ApiPlatform\DataProvider\Assembler;

use Book\Infrastructure\Api\ApiPlatform\Resource\Book;
use Book\Infrastructure\Projection\Doctrine\Orm\Entity\Book as Entity;

final class BookAssembler
{
    public function assemble(Entity $entity): Book
    {
        $resource = new Book();
        $resource->id = $entity->getId();
        $resource->isbn = $entity->getIsbn();
        $resource->title = $entity->getTitle();
        $resource->description = $entity->getDescription();
        $resource->author = $entity->getAuthor();

        return $resource;
    }
}
