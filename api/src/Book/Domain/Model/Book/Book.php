<?php

declare(strict_types=1);

namespace App\Book\Domain\Model\Book;

use App\Book\Domain\Model\Book\Event\BookWasCreated;
use App\Book\Domain\Model\Book\Event\BookWasDeleted;
use App\Book\Domain\Model\Review\Author as ReviewAuthor;
use App\Book\Domain\Model\Review\Body;
use App\Book\Domain\Model\Review\Rating;
use App\Book\Domain\Model\Review\Review;
use App\Book\Domain\Model\Review\ReviewId;
use App\Core\Domain\AggregateRoot;
use App\Core\Domain\DomainEvent;
use App\Core\Domain\IdentifiesAggregate;
use RuntimeException;
use function get_class;
use function sprintf;

final class Book extends AggregateRoot
{
    /** @var BookId */
    private $id;
    /** @var Isbn|null */
    private $isbn;
    /** @var Title */
    private $title;
    /** @var Description */
    private $description;
    /** @var Author */
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
        switch (get_class($event)) {
            case BookWasCreated::class:
                $this->whenBookWasCreated($event);
                break;
            case BookWasDeleted::class:
                break;
            default:
                throw new RuntimeException(sprintf(
                    'Missing event "%s" handler method for aggregate root "%s".',
                    get_class($event),
                    static::class
                ));
        }
    }

    private function whenBookWasCreated(BookWasCreated $event): void
    {
        $this->id          = $event->aggregateId();
        $this->isbn        = $event->isbn();
        $this->title       = $event->title();
        $this->description = $event->description();
        $this->author      = $event->author();
    }
}
