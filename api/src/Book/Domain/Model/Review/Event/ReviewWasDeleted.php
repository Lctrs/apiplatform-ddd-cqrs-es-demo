<?php

declare(strict_types=1);

namespace Book\Domain\Model\Review\Event;

use Book\Domain\Model\Review\ReviewId;
use Core\Domain\DomainEvent;
use Core\Domain\IdentifiesAggregate;

final class ReviewWasDeleted extends DomainEvent
{
    public const MESSAGE_NAME = 'review-was-deleted';

    private $reviewId;

    protected function __construct(ReviewId $reviewId)
    {
        parent::__construct();

        $this->reviewId = $reviewId;
    }

    public static function fromArray(array $data): DomainEvent
    {
        /** @var self $message */
        $message = (new \ReflectionClass(self::class))->newInstanceWithoutConstructor();

        $message->reviewId = ReviewId::fromString($data['aggregateId']);
        $message->version = $data['version'];
        $message->occuredOn = $data['occuredOn'];

        return $message;
    }

    public static function with(ReviewId $reviewId): self
    {
        return new self($reviewId);
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
        return $this->reviewId;
    }
}
