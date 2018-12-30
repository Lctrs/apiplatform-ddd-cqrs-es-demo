<?php

declare(strict_types=1);

namespace App\Book\Domain\Model\Review;

final class Rating
{
    /** @var int */
    private $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public function value(): int
    {
        return $this->value;
    }

    public static function fromScalar(int $rating): self
    {
        return new self($rating);
    }

    public function toScalar(): int
    {
        return $this->value;
    }
}
