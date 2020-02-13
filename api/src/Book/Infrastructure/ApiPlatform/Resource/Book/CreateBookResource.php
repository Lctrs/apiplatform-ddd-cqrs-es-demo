<?php

declare(strict_types=1);

namespace App\Book\Infrastructure\ApiPlatform\Resource\Book;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Book\Domain\Model\Book\Author;
use App\Book\Domain\Model\Book\BookId;
use App\Book\Domain\Model\Book\Command\CreateBook;
use App\Book\Domain\Model\Book\Description;
use App\Book\Domain\Model\Book\Isbn;
use App\Book\Domain\Model\Book\PublicationDate;
use App\Book\Domain\Model\Book\Title;
use App\Core\Domain\Command;
use App\Core\Infrastructure\ApiPlatform\Resource;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     collectionOperations={
 *         "post"={"status"=202}
 *     },
 *     itemOperations={},
 *     output=false
 * )
 */
final class CreateBookResource implements Resource
{
    /** @Assert\Isbn() */
    public ?string $isbn;
    /** @Assert\NotBlank() */
    public string $title;
    /** @Assert\NotBlank() */
    public string $description;
    /** @Assert\NotBlank() */
    public string $author;
    /**
     * @Assert\NotBlank()
     * @Assert\Date()
     */
    public string $publicationDate;
    /** @ApiProperty(identifier=true) */
    private ?BookId $id;

    public function toCommand() : Command
    {
        return new CreateBook(
            $this->id ?? $this->id = BookId::generate(),
            $this->isbn === null ? null : Isbn::fromString($this->isbn),
            Title::fromString($this->title),
            Description::fromString($this->description),
            Author::fromString($this->author),
            PublicationDate::fromString($this->publicationDate)
        );
    }
}
