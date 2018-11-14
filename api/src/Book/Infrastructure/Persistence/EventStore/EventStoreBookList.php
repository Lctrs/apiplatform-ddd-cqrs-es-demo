<?php

declare(strict_types=1);

namespace Book\Infrastructure\Persistence\EventStore;

use Book\Domain\Model\Book\Book;
use Book\Domain\Model\Book\BookId;
use Book\Domain\Model\Book\BookList;
use Core\Domain\AggregateRepository;
use Core\Domain\EventStore;

/**
 * @method null|Book getAggregateRoot(BookId $id) : ?Book
 */
final class EventStoreBookList extends AggregateRepository implements BookList
{
    public function __construct(EventStore $eventStore, string $streamName)
    {
        parent::__construct($eventStore, $streamName, Book::class);
    }

    public function save(Book $book): void
    {
        $this->saveAggregateRoot($book);
    }

    public function get(BookId $bookId): ?Book
    {
        return $this->getAggregateRoot($bookId);
    }
}
