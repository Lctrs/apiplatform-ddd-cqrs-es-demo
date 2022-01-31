<?php

declare(strict_types=1);

namespace App\Core\Domain;

interface IdentifiesAggregate
{
    /**
     * @return static
     */
    public static function generate(): static;

    public static function fromString(string $string): static;

    public function toString(): string;
}
