<?php

declare(strict_types=1);

namespace App\Core\Domain;

interface EventStore
{
    public function appendTo(string $streamName, AggregateType $aggregateType, iterable $streamEvents): void;

    public function load(string $streamName, AggregateType $aggregateType, IdentifiesAggregate $aggregateId): iterable;
}
