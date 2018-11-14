<?php

declare(strict_types=1);

namespace App\Core\Domain;

abstract class DomainEvent
{
    protected $version = 1;
    protected $occuredOn;

    protected function __construct()
    {
        $this->occuredOn = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
    }

    public function withVersion(int $version): self
    {
        $instance = clone $this;
        $instance->version = $version;

        return $instance;
    }

    public function version(): int
    {
        return $this->version;
    }

    public function occuredOn(): \DateTimeInterface
    {
        return $this->occuredOn;
    }

    abstract public function name(): string;

    abstract public static function fromArray(array $data): self;

    abstract public function toArray(): array;

    abstract public function aggregateId(): IdentifiesAggregate;
}
