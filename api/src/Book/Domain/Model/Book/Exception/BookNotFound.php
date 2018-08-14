<?php

declare(strict_types=1);

namespace Book\Domain\Model\Book\Exception;

use Book\Domain\Model\Book\BookId;
use InvalidArgumentException;

final class BookNotFound extends InvalidArgumentException
{
    public static function withId(BookId $id): self
    {
        return new self(sprintf('Book with id "%s" cannot be found.', $id->toString()));
    }
}
