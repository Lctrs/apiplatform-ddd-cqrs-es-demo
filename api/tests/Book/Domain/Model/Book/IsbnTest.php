<?php

declare(strict_types=1);

namespace App\Tests\Book\Domain\Model\Book;

use App\Book\Domain\Model\Book\Isbn;
use PHPUnit\Framework\TestCase;

final class IsbnTest extends TestCase
{
    public function testItCreatesIsbnFromString(): void
    {
        $isbn = Isbn::fromString('978-2723442282');
        self::assertSame('978-2723442282', $isbn->toString());
    }
}
