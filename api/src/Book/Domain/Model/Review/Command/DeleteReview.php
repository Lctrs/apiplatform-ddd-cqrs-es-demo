<?php

declare(strict_types=1);

namespace App\Book\Domain\Model\Review\Command;

use App\Book\Domain\Model\Review\ReviewId;
use App\Core\Domain\Command;

final class DeleteReview implements Command
{
    private ReviewId $reviewId;

    public function __construct(ReviewId $reviewId)
    {
        $this->reviewId = $reviewId;
    }

    public function reviewId() : ReviewId
    {
        return $this->reviewId;
    }
}
