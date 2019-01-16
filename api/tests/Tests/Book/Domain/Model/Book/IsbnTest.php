<?php

namespace App\Tests\Book\Domain\Model\Book;

use App\Book\Domain\Model\Book\Isbn;
use PHPUnit\Framework\TestCase;

class IsbnTest extends TestCase
{
    public function testItCreatesIsbnFromString(): void
    {
        $isbn = Isbn::fromString('978-2723442282');
        $this->assertSame('978-2723442282', $isbn->toString());
    }
}
