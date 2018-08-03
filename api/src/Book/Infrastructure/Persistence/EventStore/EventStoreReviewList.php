<?php

declare(strict_types=1);

namespace Book\Infrastructure\Persistence\EventStore;

use Book\Domain\Model\Review\Review;
use Book\Domain\Model\Review\ReviewId;
use Book\Domain\Model\Review\ReviewList;
use Prooph\EventSourcing\Aggregate\AggregateRepository;

/**
 * @method null|Review getAggregateRoot(string $aggregateId)
 */
final class EventStoreReviewList extends AggregateRepository implements ReviewList
{
    public function save(Review $book): void
    {
        $this->saveAggregateRoot($book);
    }

    public function get(ReviewId $id): ?Review
    {
        return $this->getAggregateRoot($id->toString());
    }
}
