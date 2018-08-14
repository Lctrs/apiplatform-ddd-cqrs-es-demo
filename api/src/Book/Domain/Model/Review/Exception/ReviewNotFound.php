<?php

declare(strict_types=1);

namespace Book\Domain\Model\Review\Exception;

use Book\Domain\Model\Review\ReviewId;
use InvalidArgumentException;

final class ReviewNotFound extends InvalidArgumentException
{
    public static function withId(ReviewId $id): self
    {
        return new self(sprintf('Review with id "%s" cannot be found.', $id->toString()));
    }
}
