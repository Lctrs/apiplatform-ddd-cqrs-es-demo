<?php

declare(strict_types=1);

namespace App\Greeting\Domain\Model;

final class Name
{
    /** @var non-empty-string */
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function fromString(string $name): self
    {
        return new self($name);
    }

    /**
     * @return non-empty-string
     */
    public function toString(): string
    {
        return $this->value;
    }
}
