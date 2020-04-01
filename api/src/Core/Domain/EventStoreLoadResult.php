<?php

declare(strict_types=1);

namespace App\Core\Domain;

final class EventStoreLoadResult
{
    /** @var iterable<DomainEvent> */
    private iterable $domainEvents;

    private int $version;

    /**
     * @param iterable<DomainEvent> $domainEvents
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
