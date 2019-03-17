<?php

declare(strict_types=1);

namespace App\Core\Domain;

interface EventStore
{
    /**
     * @param iterable|DomainEvent[] $streamEvents
     */
    public function appendTo(AggregateType $aggregateType, iterable $streamEvents) : void;

    /**
     * @return iterable|DomainEvent[]
     */
    public function load(AggregateType $aggregateType, IdentifiesAggregate $aggregateId) : iterable;
}
