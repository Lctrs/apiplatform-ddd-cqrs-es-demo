<?php

declare(strict_types=1);

namespace App\Tests\Book\Domain\Model\Book;

use App\Book\Domain\Model\Book\Title;
use PHPUnit\Framework\TestCase;

final class TitleTest extends TestCase
{
    public function testItCreatesTitleFromString(): void
    {
        $title = Title::fromString('A title');
        self::assertSame('A title', $title->toString());
    }
}
