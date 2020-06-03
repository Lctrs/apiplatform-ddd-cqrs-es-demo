<?php

declare(strict_types=1);

namespace App\Book\Domain\Model\Review;

use App\Core\Domain\IdentifiesAggregate;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class ReviewId implements IdentifiesAggregate
{
    private UuidInterface $uuid;

    public static function generate(): self
    {
        return new self(Uuid::uuid4());
    }

    public static function fromString(string $string): self
    {
        return new self(Uuid::fromString($string));
    }

    private function __construct(UuidInterface $reviewId)
    {
        $this->uuid = $reviewId;
    }

    public function toString(): string
    {
        return $this->uuid->toString();
    }
}
