<?php

namespace App\Tests\Book\Domain\Model\Book;

use App\Book\Domain\Model\Book\Author;
use PHPUnit\Framework\TestCase;

class AuthorTest extends TestCase
{
    public function testItCreatesAuthorFromString(): void
    {
        $author = Author::fromString('Homer');
        $this->assertSame('Homer', $author->toString());
    }
}
