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

use function sprintf;

final class Book extends AggregateRoot
{
    private BookId $id;
    private ?Isbn $isbn;
    private Title $title;
    private Description $description;
    private Author $author;
    private PublicationDate $publicationDate;

    public static function create(
        BookId $id,
        ?Isbn $isbn,
        Title $title,
        Description $description,
        Author $author,
        PublicationDate $publicationDate
    ): self {
        $book = new self();

        $book->recordThat(BookWasCreated::with($id, $isbn, $title, $description, $author, $publicationDate));

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

    public function publicationDate(): PublicationDate
    {
        return $this->publicationDate;
    }

    protected function apply(DomainEvent $event): void
    {
        switch ($event::class) {
            case BookWasCreated::class:
                $this->whenBookWasCreated($event);
                break;
            case BookWasDeleted::class:
                break;
            default:
                throw new RuntimeException(sprintf(
                    'Missing event "%s" handler method for aggregate root "%s".',
                    $event::class,
                    static::class
                ));
        }
    }

    private function whenBookWasCreated(BookWasCreated $event): void
    {
        $this->id              = $event->aggregateId();
        $this->isbn            = $event->isbn();
        $this->title           = $event->title();
        $this->description     = $event->description();
        $this->author          = $event->author();
        $this->publicationDate = $event->publicationDate();
    }
}
