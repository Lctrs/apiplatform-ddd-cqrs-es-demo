<?php

declare(strict_types=1);

namespace App\Book\Infrastructure\Persistence\EventStore;

use App\Book\Domain\Model\Review\Review;
use App\Book\Domain\Model\Review\ReviewId;
use App\Book\Domain\Model\Review\ReviewList;
use App\Core\Domain\AggregateRepository;
use App\Core\Domain\AggregateType;
use App\Core\Domain\EventStore;

/**
 * @method Review|null getAggregateRoot(ReviewId $reviewId) : ?Review
 */
final class EventStoreReviewList extends AggregateRepository implements ReviewList
{
    public function __construct(EventStore $eventStore)
    {
        parent::__construct($eventStore, new AggregateType('review', Review::class));
    }

    public function save(Review $review) : void
    {
        $this->saveAggregateRoot($review);
    }

    public function get(ReviewId $reviewId) : ?Review
    {
        return $this->getAggregateRoot($reviewId);
    }
}
