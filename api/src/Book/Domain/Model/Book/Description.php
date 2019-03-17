<?php

declare(strict_types=1);

namespace App\Book\Domain\Model\Book;

final class Description
{
    /** @var string */
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function __toString() : string
    {
        return $this->value;
    }

    public function value() : string
    {
        return $this->value;
    }

    public static function fromString(string $description) : self
    {
        return new self($description);
    }

    public function toString() : string
    {
        return $this->value;
    }
}
