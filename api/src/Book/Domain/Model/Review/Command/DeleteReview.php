<?php

declare(strict_types=1);

namespace Book\Domain\Model\Review\Command;

use Book\Domain\Model\Review\ReviewId;
use Core\Domain\Command;

final class DeleteReview implements Command
{
    private $reviewId;

    public function __construct(ReviewId $reviewId)
    {
        $this->reviewId = $reviewId;
    }

    public function reviewId(): ReviewId
    {
        return $this->reviewId;
    }
}
