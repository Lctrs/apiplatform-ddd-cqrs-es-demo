<?php

declare(strict_types=1);

namespace App\Book\Domain\Model\Book\Event;

use App\Book\Domain\Model\Book\Author;
use App\Book\Domain\Model\Book\BookId;
use App\Book\Domain\Model\Book\Description;
use App\Book\Domain\Model\Book\Isbn;
use App\Book\Domain\Model\Book\PublicationDate;
use App\Book\Domain\Model\Book\Title;
use App\Core\Domain\DomainEvent;
use DateTimeImmutable;

final class BookWasCreated implements DomainEvent
{
    public const MESSAGE_NAME = 'book-was-created';

    private ?string $eventId = null;
    private DateTimeImmutable $occurredOn;
    private BookId $bookId;
    private ?Isbn $isbn;
    private Title $title;
    private Description $description;
    private Author $author;
    private PublicationDate $publicationDate;

    private function __construct(
        BookId $bookId,
        ?Isbn $isbn,
        Title $title,
        Description $description,
        Author $author,
        PublicationDate $publicationDate
    ) {
        $this->occurredOn      = new DateTimeImmutable();
        $this->bookId          = $bookId;
        $this->isbn            = $isbn;
        $this->title           = $title;
        $this->description     = $description;
        $this->author          = $author;
        $this->publicationDate = $publicationDate;
    }

    public static function with(
        BookId $bookId,
        ?Isbn $isbn,
        Title $title,
        Description $description,
        Author $author,
        PublicationDate $publicationDate
    ): self {
        return new self($bookId, $isbn, $title, $description, $author, $publicationDate);
    }

    public function aggregateId(): BookId
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

    public function publicationDate(): PublicationDate
    {
        return $this->publicationDate;
    }

    public function eventId(): ?string
    {
        return $this->eventId;
    }

    public function eventType(): string
    {
        return self::MESSAGE_NAME;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return [
            'bookId' => $this->bookId->toString(),
            'isbn' => $this->isbn === null ? null : $this->isbn->toString(),
            'title' => $this->title->toString(),
            'description' => $this->description->toString(),
            'author' => $this->author->toString(),
            'publicationDate' => $this->publicationDate->toString(),
        ];
    }

    /**
     * @param array{bookId: string, isbn: string|null, title: string, description: string, author: string, publicationDate: string} $data
     */
    public static function from(EventId $eventId, array $data): DomainEvent
    {
        $message = new self(
            BookId::fromString($data['bookId']),
            $data['isbn'] === null ? null : Isbn::fromString($data['isbn']),
            Title::fromString($data['title']),
            Description::fromString($data['description']),
            Author::fromString($data['author']),
            PublicationDate::fromString($data['publicationDate'])
        );

        $message->eventId = $eventId->toString();

        return $message;
    }
}
