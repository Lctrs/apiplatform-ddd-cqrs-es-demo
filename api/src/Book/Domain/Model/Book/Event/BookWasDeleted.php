<?php

declare(strict_types=1);

namespace Book\Domain\Model\Book\Event;

use Book\Domain\Model\Book\BookId;
use Core\Domain\DomainEvent;
use Core\Domain\IdentifiesAggregate;

final class BookWasDeleted extends DomainEvent
{
    public const MESSAGE_NAME = 'book-was-deleted';

    private $bookId;

    protected function __construct(BookId $bookId)
    {
        parent::__construct();

        $this->bookId = $bookId;
    }

    public static function with(BookId $bookId): self
    {
        return new self($bookId);
    }

    public function name(): string
    {
        return self::MESSAGE_NAME;
    }

    public function toArray(): array
    {
        return [];
    }

    public function aggregateId(): IdentifiesAggregate
    {
        return $this->bookId;
    }

    public static function fromArray(array $data): DomainEvent
    {
        /** @var self $message */
        $message = (new \ReflectionClass(self::class))->newInstanceWithoutConstructor();

        $message->bookId = BookId::fromString($data['aggregateId']);
        $message->version = $data['version'];
        $message->occuredOn = $data['occuredOn'];

        return $message;
    }
}
