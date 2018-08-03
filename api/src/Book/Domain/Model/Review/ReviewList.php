<?php

declare(strict_types=1);

namespace Book\Domain\Model\Review;

interface ReviewList
{
    public function save(Review $book): void;

    public function get(ReviewId $id): ?Review;
}
