<?php

declare(strict_types=1);

namespace App\Book\Domain\Model\Book;

final class Isbn
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function fromString(string $isbn) : self
    {
        return new self($isbn);
    }

    public function toString() : string
    {
        return $this->value;
    }
}
