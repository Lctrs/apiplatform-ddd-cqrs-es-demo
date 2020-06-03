<?php

declare(strict_types=1);

namespace App\Tests\Book\Domain\Model\Book;

use App\Book\Domain\Model\Book\Description;
use PHPUnit\Framework\TestCase;

final class DescriptionTest extends TestCase
{
    public function testItCreatesDescriptionFromString(): void
    {
        $description = Description::fromString('A description');
        self::assertSame('A description', $description->toString());
    }
}
