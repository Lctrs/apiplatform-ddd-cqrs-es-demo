<?php

declare(strict_types=1);

namespace Core\Domain;

interface IdentifiesAggregate
{
    public function __toString(): string;

    public static function generate();

    public static function fromString(string $string);

    public function equals(self $other): bool;
}
