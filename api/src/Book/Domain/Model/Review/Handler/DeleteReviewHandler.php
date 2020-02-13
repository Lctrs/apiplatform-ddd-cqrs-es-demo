<?php

declare(strict_types=1);

namespace App\Book\Domain\Model\Review\Handler;

use App\Book\Domain\Model\Review\Command\DeleteReview;
use App\Book\Domain\Model\Review\Exception\ReviewNotFound;
use App\Book\Domain\Model\Review\ReviewList;

final class DeleteReviewHandler
{
    private ReviewList $reviewList;

    public function __construct(ReviewList $reviewList)
    {
        $this->reviewList = $reviewList;
    }

    public function __invoke(DeleteReview $command) : void
    {
        $review = $this->reviewList->get($command->reviewId());

        if ($review === null) {
            throw ReviewNotFound::withId($command->reviewId());
        }

        $review->delete();

        $this->reviewList->save($review);
    }
}
