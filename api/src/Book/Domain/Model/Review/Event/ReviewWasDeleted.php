<?php

declare(strict_types=1);

namespace App\Book\Domain\Model\Review\Event;

use App\Book\Domain\Model\Review\ReviewId;
use App\Core\Domain\DomainEvent;
use Prooph\EventStore\EventId;

final class ReviewWasDeleted implements DomainEvent
{
    public const MESSAGE_NAME = 'review-was-deleted';

    private ?string $eventId = null;
    private ReviewId $reviewId;

    private function __construct(ReviewId $reviewId)
    {
        $this->reviewId = $reviewId;
    }

    public static function with(ReviewId $reviewId) : self
    {
        return new self($reviewId);
    }

    public function aggregateId() : ReviewId
    {
        return $this->reviewId;
    }

    public function eventId() : ?string
    {
        return $this->eventId;
    }

    public function eventType() : string
    {
        return self::MESSAGE_NAME;
    }

    /**
     * @inheritdoc
     */
    public function toArray() : array
    {
        return [
            'reviewId' => $this->reviewId->toString(),
        ];
    }

    /**
     * @param array{reviewId: string} $data
     */
    public static function from(EventId $eventId, array $data) : DomainEvent
    {
        $message = new self(ReviewId::fromString($data['reviewId']));

        $message->eventId = $eventId->toString();

        return $message;
    }
}
