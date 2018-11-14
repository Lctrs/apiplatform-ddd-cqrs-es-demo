<?php

declare(strict_types=1);

namespace Core\Domain;

interface EventStore
{
    public function appendTo(string $streamName, iterable $streamEvents): void;

    public function load(string $streamName, IdentifiesAggregate $aggregateId): iterable;
}
