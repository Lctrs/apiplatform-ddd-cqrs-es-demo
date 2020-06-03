<?php

declare(strict_types=1);

namespace App\Book\Domain\Model\Book;

use App\Core\Domain\IdentifiesAggregate;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class BookId implements IdentifiesAggregate
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

    private function __construct(UuidInterface $bookId)
    {
        $this->uuid = $bookId;
    }

    public function toString(): string
    {
        return $this->uuid->toString();
    }
}
