<?php

declare(strict_types=1);

namespace App\Book\Domain\Model\Review;

interface ReviewList
{
    public function save(Review $review): void;

    public function get(ReviewId $reviewId): ?Review;
}
