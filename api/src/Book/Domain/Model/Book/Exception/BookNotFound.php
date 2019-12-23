<?php

declare(strict_types=1);

namespace App\Book\Domain\Model\Book\Exception;

use App\Book\Domain\Model\Book\BookId;
use InvalidArgumentException;
use function sprintf;

final class BookNotFound extends InvalidArgumentException
{
    public static function withId(BookId $id) : self
    {
        return new self(sprintf('Book with id "%s" cannot be found.', $id->toString()));
    }
}
