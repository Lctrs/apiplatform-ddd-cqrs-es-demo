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

    public static function fromInt(int $rating) : self
    {
        return new self($rating);
    }

    public function toInt() : int
    {
        return $this->value;
    }
}
