<?php

declare(strict_types=1);

namespace App\Book\Infrastructure\Projection\Doctrine\Data;

final class RemoveBook
{
    /** @var string */
    private $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function id(): string
    {
        return $this->id;
    }
}
