<?php

declare(strict_types=1);

namespace Book\Infrastructure\Persistence\EventStore;

use Book\Domain\Model\Book\Book;
use Book\Domain\Model\Book\BookId;
use Book\Domain\Model\Book\BookList;
use Prooph\EventSourcing\Aggregate\AggregateRepository;

/**
 * @method null|Book getAggregateRoot(string $aggregateId)
 */
final class EventStoreBookList extends AggregateRepository implements BookList
{
    public function save(Book $book): void
    {
        $this->saveAggregateRoot($book);
    }

    public function get(BookId $id): ?Book
    {
        return $this->getAggregateRoot($id->toString());
    }
}
