<?php

declare(strict_types=1);

namespace App\Book\Domain\Model\Review\Event;

use App\Book\Domain\Model\Book\BookId;
use App\Book\Domain\Model\Review\Author;
use App\Book\Domain\Model\Review\Body;
use App\Book\Domain\Model\Review\Rating;
use App\Book\Domain\Model\Review\ReviewId;
use App\Core\Domain\DomainEvent;
use ReflectionClass;

final class ReviewWasPosted extends DomainEvent
{
    public const MESSAGE_NAME = 'review-was-posted';

    /** @var ReviewId */
    private $reviewId;
    /** @var BookId */
    private $bookId;
    /** @var Body|null */
    private $body;
    /** @var Rating */
    private $rating;
    /** @var Author|null */
    private $author;

    protected function __construct(ReviewId $reviewId, BookId $bookId, ?Body $body, Rating $rating, ?Author $author)
    {
        parent::__construct();

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

    public function name() : string
    {
        return self::MESSAGE_NAME;
    }

    /**
     * @inheritdoc
     */
    public function toArray() : array
    {
        return [
            'bookId' => $this->bookId->__toString(),
            'body' => $this->body === null ? null : $this->body->toString(),
            'rating' => $this->rating->toScalar(),
            'author' => $this->author === null ? null : $this->author->toString(),
        ];
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

    /**
     * @inheritdoc
     */
    public static function fromArray(array $data) : DomainEvent
    {
        /** @var self $message */
        $message = (new ReflectionClass(self::class))->newInstanceWithoutConstructor();

        $message->reviewId  = ReviewId::fromString($data['aggregateId']);
        $message->bookId    = BookId::fromString($data['bookId']);
        $message->body      = $data['body'] === null ? $data['body'] : Body::fromString($data['body']);
        $message->rating    = Rating::fromScalar($data['rating']);
        $message->author    = $data['author'] === null ? $data['author'] : Author::fromString($data['author']);
        $message->version   = $data['version'];
        $message->occuredOn = $data['occuredOn'];

        return $message;
    }
}
