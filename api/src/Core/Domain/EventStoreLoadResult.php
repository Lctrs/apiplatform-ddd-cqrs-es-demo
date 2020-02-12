<?php

declare(strict_types=1);

namespace App\Core\Domain;

/**
 * @psalm-immutable
 */
final class EventStoreLoadResult
{
    /**
     * @var iterable|DomainEvent[]
     * @psalm-var iterable<DomainEvent>
     */
    private $domainEvents;

    /** @var int */
    private $version;

    /**
     * @param iterable|DomainEvent[] $domainEvents
     *
     * @psalm-param iterable<DomainEvent> $domainEvents
     */
    public function __construct(iterable $domainEvents, int $version)
    {
        $this->domainEvents = $domainEvents;
        $this->version      = $version;
    }

    /**
     * @return iterable|DomainEvent[]
     *
     * @psalm-return iterable<DomainEvent>
     */
    public function domainEvents() : iterable
    {
        return $this->domainEvents;
    }

    public function version() : int
    {
        return $this->version;
    }
}
