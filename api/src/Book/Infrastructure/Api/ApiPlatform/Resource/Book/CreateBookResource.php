<?php

declare(strict_types=1);

namespace App\Book\Infrastructure\Api\ApiPlatform\Resource\Book;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Book\Domain\Model\Book\Author;
use App\Book\Domain\Model\Book\BookId;
use App\Book\Domain\Model\Book\Command\CreateBook;
use App\Book\Domain\Model\Book\Description;
use App\Book\Domain\Model\Book\Isbn;
use App\Book\Domain\Model\Book\Title;
use App\Core\Domain\Command;
use App\Core\Infrastructure\Api\Resource;

/**
 * @ApiResource(
 *     collectionOperations={
 *         "post"={"status"=202}
 *     },
 *     itemOperations={},
 *     outputClass=false
 * )
 */
final class CreateBookResource implements Resource
{
    /** @var string|null */
    public $isbn;
    /** @var string */
    public $title;
    /** @var string */
    public $description;
    /** @var string */
    public $author;
    /**
     * @var BookId|null
     * @ApiProperty(identifier=true)
     */
    private $id;

    public function toCommand(): Command
    {
        return new CreateBook(
            $this->id ?? $this->id = BookId::generate(),
            $this->isbn === null ? null : Isbn::fromString($this->isbn),
            Title::fromString($this->title),
            Description::fromString($this->description),
            Author::fromString($this->author)
        );
    }
}
