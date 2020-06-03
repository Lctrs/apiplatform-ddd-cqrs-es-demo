<?php

declare(strict_types=1);

namespace App\Book\Domain\Model\Book;

final class Author
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
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
