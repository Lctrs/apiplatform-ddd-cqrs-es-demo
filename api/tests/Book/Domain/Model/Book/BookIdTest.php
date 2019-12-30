<?php

declare(strict_types=1);

namespace App\Tests\Book\Domain\Model\Book;

use App\Book\Domain\Model\Book\BookId;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class BookIdTest extends TestCase
{
    public function testItCreatesBookIdFromString() : void
    {
        $bookId = BookId::fromString('4e27f920-0862-4da7-9da3-6fb812040e3a');
        self::assertSame('4e27f920-0862-4da7-9da3-6fb812040e3a', $bookId->toString());
    }

    public function testItThrowsExceptionOnInvalidUuid() : void
    {
        $this->expectException(InvalidArgumentException::class);

        BookId::fromString('invalid');
    }
}
