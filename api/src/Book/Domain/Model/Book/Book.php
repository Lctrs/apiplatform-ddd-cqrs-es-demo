<?php

declare(strict_types=1);

namespace Book\Domain\Model\Book;

use Book\Domain\Model\Book\Event\BookWasCreated;
use Book\Domain\Model\Book\Event\BookWasDeleted;
use Book\Domain\Model\Review\Author as ReviewAuthor;
use Book\Domain\Model\Review\Body;
use Book\Domain\Model\Review\Rating;
use Book\Domain\Model\Review\Review;
use Book\Domain\Model\Review\ReviewId;
use Core\Domain\AggregateRoot;
use Core\Domain\DomainEvent;
use Core\Domain\IdentifiesAggregate;

final class Book extends AggregateRoot
{
    private $id;
    private $isbn;
    private $title;
    private $description;
    private $author;

    public static function create(BookId $id, ?Isbn $isbn, Title $title, Description $description, Author $author): self
    {
        $book = new self();

        $book->recordThat(BookWasCreated::with($id, $isbn, $title, $description, $author));

        return $book;
    }

    public function postReview(ReviewId $reviewId, ?Body $body, Rating $rating, ?ReviewAuthor $author): Review
    {
        return Review::post($reviewId, $this->id, $body, $rating, $author);
    }

    public function aggregateId(): IdentifiesAggregate
    {
        return $this->id;
    }

    public function delete(): void
    {
        $this->recordThat(BookWasDeleted::with($this->id));
    }

    public function isbn(): ?Isbn
    {
        return $this->isbn;
    }

    public function title(): Title
    {
        return $this->title;
    }

    public function description(): Description
    {
        return $this->description;
    }

    public function author(): Author
    {
        return $this->author;
    }

    protected function when(DomainEvent $event): void
    {
        switch (\get_class($event)) {
            case BookWasCreated::class:
                $this->whenBookWasCreated($event);
                break;
            case BookWasDeleted::class:
                break;
            default:
                throw new \RuntimeException(sprintf(
                    'Missing event "%s" handler method for aggregate root "%s".',
                    \get_class($event),
                    \get_class($this)
                ));
        }
    }

    private function whenBookWasCreated(BookWasCreated $event): void
    {
        $this->id = $event->aggregateId();
        $this->isbn = $event->isbn();
        $this->title = $event->title();
        $this->description = $event->description();
        $this->author = $event->author();
    }
}
