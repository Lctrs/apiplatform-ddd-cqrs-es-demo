<?php

declare(strict_types=1);

namespace Book\Domain\Model\Review\Handler;

use Book\Domain\Model\Book\BookList;
use Book\Domain\Model\Book\Exception\BookNotFound;
use Book\Domain\Model\Review\Command\PostReview;
use Book\Domain\Model\Review\ReviewList;

final class PostReviewHandler
{
    private $bookList;
    private $reviewList;

    public function __construct(BookList $bookList, ReviewList $reviewList)
    {
        $this->bookList = $bookList;
        $this->reviewList = $reviewList;
    }

    public function __invoke(PostReview $command): void
    {
        $book = $this->bookList->get($command->bookId());

        if (null === $book) {
            throw BookNotFound::withId($command->bookId());
        }

        $review = $book->postReview($command->reviewId(), $command->body(), $command->rating(), $command->author());

        $this->reviewList->save($review);
    }
}
