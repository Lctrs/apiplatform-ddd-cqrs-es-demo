<?php

declare(strict_types=1);

namespace App\Book\Domain\Model\Review\Command;

use App\Book\Domain\Model\Book\BookId;
use App\Book\Domain\Model\Review\Author;
use App\Book\Domain\Model\Review\Body;
use App\Book\Domain\Model\Review\Rating;
use App\Book\Domain\Model\Review\ReviewId;
use App\Core\Domain\Command;

final class PostReview implements Command
{
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

    public function __construct(ReviewId $reviewId, BookId $bookId, ?Body $body, Rating $rating, ?Author $author)
    {
        $this->reviewId = $reviewId;
        $this->bookId   = $bookId;
        $this->body     = $body;
        $this->rating   = $rating;
        $this->author   = $author;
    }

    public function reviewId() : ReviewId
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
}
