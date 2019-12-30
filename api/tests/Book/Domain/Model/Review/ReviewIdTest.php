<?php

declare(strict_types=1);

namespace App\Tests\Book\Domain\Model\Review;

use App\Book\Domain\Model\Review\ReviewId;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class ReviewIdTest extends TestCase
{
    public function testItCreatesBookIdFromString() : void
    {
        $reviewId = ReviewId::fromString('4e27f920-0862-4da7-9da3-6fb812040e3a');
        self::assertSame('4e27f920-0862-4da7-9da3-6fb812040e3a', $reviewId->toString());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testItThrowsExceptionOnInvalidUuid() : void
    {
        $this->expectException(InvalidArgumentException::class);

        ReviewId::fromString('invalid');
    }
}
