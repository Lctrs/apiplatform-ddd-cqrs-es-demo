<?php

declare(strict_types=1);

namespace App\Book\Domain\Model\Review;

use App\Book\Domain\Model\Book\BookId;
use App\Book\Domain\Model\Review\Event\ReviewWasDeleted;
use App\Book\Domain\Model\Review\Event\ReviewWasPosted;
use App\Core\Domain\AggregateRoot;
use App\Core\Domain\DomainEvent;
use App\Core\Domain\IdentifiesAggregate;

final class Review extends AggregateRoot
{
    private $id;
    private $bookId;
    private $body;
    private $rating;
    private $author;

    public static function post(ReviewId $reviewId, BookId $bookId, ?Body $body, Rating $rating, ?Author $author): self
    {
        $self = new self();

        $self->recordThat(ReviewWasPosted::with($reviewId, $bookId, $body, $rating, $author));

        return $self;
    }

    public function delete(): void
    {
        $this->recordThat(ReviewWasDeleted::with($this->id));
    }

    public function aggregateId(): IdentifiesAggregate
    {
        return $this->id;
    }

    public function bookId(): BookId
    {
        return $this->bookId;
    }

    public function body(): ?Body
    {
        return $this->body;
    }

    public function rating(): Rating
    {
        return $this->rating;
    }

    public function author(): ?Author
    {
        return $this->author;
    }

    protected function when(DomainEvent $event): void
    {
        switch (\get_class($event)) {
            case ReviewWasPosted::class:
                $this->whenReviewWasPosted($event);
                break;
            case ReviewWasDeleted::class:
                break;
            default:
                throw new \RuntimeException(sprintf(
                    'Missing event "%s" handler method for aggregate root "%s".',
                    \get_class($event),
                    \get_class($this)
                ));
        }
    }

    private function whenReviewWasPosted(ReviewWasPosted $event): void
    {
        $this->id = $event->aggregateId();
        $this->bookId = $event->bookId();
        $this->body = $event->body();
        $this->rating = $event->rating();
        $this->author = $event->author();
    }
}
