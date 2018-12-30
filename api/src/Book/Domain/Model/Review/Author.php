<?php

declare(strict_types=1);

namespace App\Book\Domain\Model\Review;

final class Author
{
    /** @var string */
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public static function fromString(string $author): self
    {
        return new self($author);
    }

    public function toString(): string
    {
        return $this->value;
    }
}
