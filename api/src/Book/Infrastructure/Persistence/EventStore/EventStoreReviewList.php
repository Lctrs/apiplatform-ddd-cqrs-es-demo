<?php

declare(strict_types=1);

namespace Book\Infrastructure\Persistence\EventStore;

use Book\Domain\Model\Review\Review;
use Book\Domain\Model\Review\ReviewId;
use Book\Domain\Model\Review\ReviewList;
use Core\Domain\AggregateRepository;
use Core\Domain\EventStore;

/**
 * @method null|Review getAggregateRoot(ReviewId $reviewId) : ?Review
 */
final class EventStoreReviewList extends AggregateRepository implements ReviewList
{
    public function __construct(EventStore $eventStore, string $streamName)
    {
        parent::__construct($eventStore, $streamName, Review::class);
    }

    public function save(Review $review): void
    {
        $this->saveAggregateRoot($review);
    }

    public function get(ReviewId $reviewId): ?Review
    {
        return $this->getAggregateRoot($reviewId);
    }
}
