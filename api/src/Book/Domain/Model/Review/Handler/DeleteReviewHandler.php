<?php

declare(strict_types=1);

namespace Book\Domain\Model\Review\Handler;

use Book\Domain\Model\Review\Command\DeleteReview;
use Book\Domain\Model\Review\Exception\ReviewNotFound;
use Book\Domain\Model\Review\ReviewList;

final class DeleteReviewHandler
{
    private $reviewList;

    public function __construct(ReviewList $reviewList)
    {
        $this->reviewList = $reviewList;
    }

    public function __invoke(DeleteReview $command): void
    {
        $review = $this->reviewList->get($command->id());

        if (null === $review) {
            throw ReviewNotFound::withId($command->id());
        }

        $review->delete();

        $this->reviewList->save($review);
    }
}
