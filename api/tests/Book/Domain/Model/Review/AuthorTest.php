<?php

declare(strict_types=1);

namespace App\Tests\Book\Domain\Model\Review;

use App\Book\Domain\Model\Review\Author;
use PHPUnit\Framework\TestCase;

final class AuthorTest extends TestCase
{
    public function testItCreatesAuthorFromString(): void
    {
        $author = Author::fromString('Homer');
        self::assertSame('Homer', $author->toString());
    }
}
