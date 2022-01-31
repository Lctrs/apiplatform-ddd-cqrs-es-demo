<?php

declare(strict_types=1);

namespace App\Book\Domain\Model\Book\Event;

use App\Book\Domain\Model\Book\BookId;
use App\Core\Domain\DomainEvent;
use DateTimeImmutable;
use Prooph\EventStore\EventId;

final class BookWasDeleted implements DomainEvent
{
    public const MESSAGE_NAME = 'book-was-deleted';

    private ?string $eventId = null;
    private DateTimeImmutable $occurredOn;
    private BookId $bookId;

    private function __construct(BookId $bookId)
    {
        $this->occurredOn = new DateTimeImmutable();
        $this->bookId     = $bookId;
    }

    public static function with(BookId $bookId): self
    {
        return new self($bookId);
    }

    public function aggregateId(): BookId
    {
        return $this->bookId;
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
        ];
    }

    /**
     * @param array{bookId:string} $data
     */
    public static function from(EventId $eventId, array $data): DomainEvent
    {
        $message = new self(BookId::fromString($data['bookId']));

        $message->eventId = $eventId->toString();

        return $message;
    }
}
