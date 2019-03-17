<?php

declare(strict_types=1);

namespace App\Core\Domain;

interface IdentifiesAggregate
{
    public function __toString() : string;

    /**
     * @return static
     */
    public static function generate();

    /**
     * @return static
     */
    public static function fromString(string $string);

    public function equals(self $other) : bool;
}
