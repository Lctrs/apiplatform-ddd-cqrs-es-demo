<?php

declare(strict_types=1);

namespace App\Book\Domain\Model\Review\Exception;

use App\Book\Domain\Model\Review\ReviewId;
use InvalidArgumentException;
use function sprintf;

final class ReviewNotFound extends InvalidArgumentException
{
    public static function withId(ReviewId $id): self
    {
        return new self(sprintf('Review with id "%s" cannot be found.', $id->__toString()));
    }
}
