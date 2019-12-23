<?php

declare(strict_types=1);

namespace App\Book\Infrastructure\Persistence\EventStore;

use App\Book\Domain\Model\Review\Review;
use App\Book\Domain\Model\Review\ReviewId;
use App\Book\Domain\Model\Review\ReviewList;
use App\Core\Domain\AggregateRepository;
use App\Core\Domain\DomainEventTransformer;
use Prooph\EventStore\EventStoreConnection;
use function assert;

final class EventStoreReviewList extends AggregateRepository implements ReviewList
{
    public function __construct(
        EventStoreConnection $eventStoreConnection,
        DomainEventTransformer $transformer
    ) {
        parent::__construct(
            $eventStoreConnection,
            $transformer,
            'review',
            Review::class,
            true
        );
    }

    public function save(Review $review) : void
    {
        $this->saveAggregateRoot($review);
    }

    public function get(ReviewId $reviewId) : ?Review
    {
        $review = $this->getAggregateRoot($reviewId);

        assert($review === null || $review instanceof Review);

        return $review;
    }
}
