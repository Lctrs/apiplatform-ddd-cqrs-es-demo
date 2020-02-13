<?php

declare(strict_types=1);

namespace App\Book\Domain\Model\Review\Event;

use App\Book\Domain\Model\Book\BookId;
use App\Book\Domain\Model\Review\Author;
use App\Book\Domain\Model\Review\Body;
use App\Book\Domain\Model\Review\Rating;
use App\Book\Domain\Model\Review\ReviewId;
use App\Core\Domain\DomainEvent;
use Prooph\EventStore\EventId;

final class ReviewWasPosted implements DomainEvent
{
    public const MESSAGE_NAME = 'review-was-posted';

    private ?string $eventId = null;
    private ReviewId $reviewId;
    private BookId $bookId;
    private ?Body $body;
    private Rating $rating;
    private ?Author $author;

    private function __construct(ReviewId $reviewId, BookId $bookId, ?Body $body, Rating $rating, ?Author $author)
    {
        $this->reviewId = $reviewId;
        $this->bookId   = $bookId;
        $this->body     = $body;
        $this->rating   = $rating;
        $this->author   = $author;
    }

    public static function with(ReviewId $reviewId, BookId $bookId, ?Body $body, Rating $rating, ?Author $author) : self
    {
        return new self($reviewId, $bookId, $body, $rating, $author);
    }

    public function aggregateId() : ReviewId
    {
        return $this->reviewId;
    }

    public function bookId() : BookId
    {
        return $this->bookId;
    }

    public function body() : ?Body
    {
        return $this->body;
    }

    public function rating() : Rating
    {
        return $this->rating;
    }

    public function author() : ?Author
    {
        return $this->author;
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
            'bookId' => $this->bookId->toString(),
            'body' => $this->body === null ? null : $this->body->toString(),
            'rating' => $this->rating->toInt(),
            'author' => $this->author === null ? null : $this->author->toString(),
        ];
    }

    /**
     * @param array{reviewId: string, bookId: string, body: string|null, rating: int, author: string|null} $data
     */
    public static function from(EventId $eventId, array $data) : DomainEvent
    {
        $message = new self(
            ReviewId::fromString($data['reviewId']),
            BookId::fromString($data['bookId']),
            $data['body'] === null ? null : Body::fromString($data['body']),
            Rating::fromInt($data['rating']),
            $data['author'] === null ? null : Author::fromString($data['author'])
        );

        $message->eventId = $eventId->toString();

        return $message;
    }
}
