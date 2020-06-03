<?php

declare(strict_types=1);

namespace App\Book\Domain\Model\Review;

final class Body
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function fromString(string $body): self
    {
        return new self($body);
    }

    public function toString(): string
    {
        return $this->value;
    }
}
