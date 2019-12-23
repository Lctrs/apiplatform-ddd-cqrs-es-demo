<?php

declare(strict_types=1);

namespace App\Tests\Book\Domain\Model\Review;

use App\Book\Domain\Model\Review\Body;
use PHPUnit\Framework\TestCase;

final class BodyTest extends TestCase
{
    public function testItCreatesBodyFromString() : void
    {
        $body = Body::fromString('A body');
        self::assertSame('A body', $body->toString());
    }
}
