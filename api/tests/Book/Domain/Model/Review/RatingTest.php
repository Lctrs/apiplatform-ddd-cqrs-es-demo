<?php

declare(strict_types=1);

namespace App\Tests\Book\Domain\Model\Review;

use App\Book\Domain\Model\Review\Rating;
use PHPUnit\Framework\TestCase;

final class RatingTest extends TestCase
{
    public function testItCreatesRatingFromInt(): void
    {
        $rating = Rating::fromInt(1);
        self::assertSame(1, $rating->toInt());
    }
}
