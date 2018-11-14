<?php

declare(strict_types=1);

namespace App\Book\Domain\Model\Review\Event;

use App\Book\Domain\Model\Review\ReviewId;
use App\Core\Domain\DomainEvent;
use App\Core\Domain\IdentifiesAggregate;

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
