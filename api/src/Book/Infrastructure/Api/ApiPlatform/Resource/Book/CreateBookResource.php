<?php

namespace App\Book\Infrastructure\Api\ApiPlatform\Resource\Book;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Book\Domain\Model\Book\Author;
use App\Book\Domain\Model\Book\BookId;
use App\Book\Domain\Model\Book\Command\CreateBook;
use App\Book\Domain\Model\Book\Description;
use App\Book\Domain\Model\Book\Isbn;
use App\Book\Domain\Model\Book\Title;
use App\Book\Infrastructure\Api\ApiPlatform\Resource\Resource;
use App\Core\Domain\Command;

/**
 * @ApiResource(
 *     collectionOperations={"post"},
 *     itemOperations={}
 * )
 */
final class CreateBookResource implements Resource
{
    /** @var string */
    public $isbn;
    /** @var string */
    public $title;
    /** @var string */
    public $description;
    /** @var string */
    public $author;

    public function toCommand(): Command
    {
        return new CreateBook(
            BookId::generate(),
            $this->isbn === null ? $this->isbn : Isbn::fromString($this->isbn),
            Title::fromString($this->title),
            Description::fromString($this->description),
            Author::fromString($this->author)
        );
    }
}
