<?php

declare(strict_types=1);

namespace Book\Domain\Model\Review\Handler;

use Book\Domain\Model\Review\Command\PostReview;
use Book\Domain\Model\Review\Review;
use Book\Domain\Model\Review\ReviewList;

final class PostReviewHandler
{
    private $reviewList;

    public function __construct(ReviewList $bookList)
    {
        $this->reviewList = $bookList;
    }

    public function __invoke(PostReview $command)
    {
        $this->reviewList->save(Review::post(
            $command->id(),
            $command->bookId(),
            $command->body(),
            $command->rating(),
            $command->author()
        ));
    }
}
