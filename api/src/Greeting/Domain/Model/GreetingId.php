<?php

declare(strict_types=1);

namespace App\Greeting\Domain\Model;

use App\Core\Domain\IdentifiesAggregate;
use Symfony\Component\Uid\Uuid;

final class GreetingId implements IdentifiesAggregate
{
    private Uuid $uuid;

    private function __construct(Uuid $uuid)
    {
        $this->uuid = $uuid;
    }

    public static function generate(): static
    {
        return new self(Uuid::v6());
    }

    public static function fromString(string $string): static
    {
        return new self(Uuid::fromRfc4122($string));
    }

    public function toString(): string
    {
        return $this->uuid->toRfc4122();
    }
}
