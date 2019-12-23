<?php

declare(strict_types=1);

namespace App\Tests\Book\Domain\Model\Book;

use App\Book\Domain\Model\Book\Author;
use PHPUnit\Framework\TestCase;

final class AuthorTest extends TestCase
{
    public function testItCreatesAuthorFromString() : void
    {
        $author = Author::fromString('Homer');
        self::assertSame('Homer', $author->toString());
    }
}
