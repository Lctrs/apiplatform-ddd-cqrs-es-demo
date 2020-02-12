<?php

declare(strict_types=1);

namespace App\Core\Domain;

interface EventStore
{
    public function save(AggregateRoot $aggregateRoot, string $streamCategory, bool $optimisticConcurrency) : void;

    public function load(string $streamCategory, IdentifiesAggregate $aggregateId) : ?EventStoreLoadResult;
}
