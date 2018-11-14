<?php

declare(strict_types=1);

namespace App\Book\Domain\Model\Book\Event;

use App\Book\Domain\Model\Book\Author;
use App\Book\Domain\Model\Book\BookId;
use App\Book\Domain\Model\Book\Description;
use App\Book\Domain\Model\Book\Isbn;
use App\Book\Domain\Model\Book\Title;
use App\Core\Domain\DomainEvent;
use App\Core\Domain\IdentifiesAggregate;

final class BookWasCreated extends DomainEvent
{
    public const MESSAGE_NAME = 'book-was-created';

    private $bookId;
    private $isbn;
    private $title;
    private $description;
    private $author;

    protected function __construct(BookId $bookId, ?Isbn $isbn, Title $title, Description $description, Author $author)
    {
        parent::__construct();

        $this->bookId = $bookId;
        $this->isbn = $isbn;
        $this->title = $title;
        $this->description = $description;
        $this->author = $author;
    }

    public static function with(BookId $bookId, ?Isbn $isbn, Title $title, Description $description, Author $author): self
    {
        return new self($bookId, $isbn, $title, $description, $author);
    }

    public function name(): string
    {
        return self::MESSAGE_NAME;
    }

    public function toArray(): array
    {
        return [
            'isbn' => null === $this->isbn ? null : $this->isbn->toString(),
            'title' => $this->title->toString(),
            'description' => $this->description->toString(),
            'author' => $this->author->toString(),
        ];
    }

    public function aggregateId(): IdentifiesAggregate
    {
        return $this->bookId;
    }

    public function isbn(): ?Isbn
    {
        return $this->isbn;
    }

    public function title(): Title
    {
        return $this->title;
    }

    public function description(): Description
    {
        return $this->description;
    }

    public function author(): Author
    {
        return $this->author;
    }

    public static function fromArray(array $data): DomainEvent
    {
        /** @var self $message */
        $message = (new \ReflectionClass(self::class))->newInstanceWithoutConstructor();

        $message->bookId = BookId::fromString($data['aggregateId']);
        $message->isbn = null === $data['isbn'] ? $data['isbn'] : Isbn::fromString($data['isbn']);
        $message->title = Title::fromString($data['title']);
        $message->description = Description::fromString($data['description']);
        $message->author = Author::fromString($data['author']);
        $message->version = $data['version'];
        $message->occuredOn = $data['occuredOn'];

        return $message;
    }
}
