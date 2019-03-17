<?php

declare(strict_types=1);

namespace App\Book\Infrastructure\Persistence\EventStore;

use App\Book\Domain\Model\Book\Book;
use App\Book\Domain\Model\Book\BookId;
use App\Book\Domain\Model\Book\BookList;
use App\Core\Domain\AggregateRepository;
use App\Core\Domain\AggregateType;
use App\Core\Domain\EventStore;

/**
 * @method Book|null getAggregateRoot(BookId $id) : ?Book
 */
final class EventStoreBookList extends AggregateRepository implements BookList
{
    public function __construct(EventStore $eventStore)
    {
        parent::__construct($eventStore, new AggregateType('book', Book::class));
    }

    public function save(Book $book) : void
    {
        $this->saveAggregateRoot($book);
    }

    public function get(BookId $bookId) : ?Book
    {
        return $this->getAggregateRoot($bookId);
    }
}
