<?php

declare(strict_types=1);

namespace App\Core\Domain;

abstract class AggregateRepository
{
    private $eventStore;
    private $streamName;
    private $aggregateType;

    public function __construct(EventStore $eventStore, string $streamName, AggregateType $aggregateType)
    {
        $this->eventStore = $eventStore;
        $this->streamName = $streamName;
        $this->aggregateType = $aggregateType;
    }

    public function saveAggregateRoot(AggregateRoot $aggregateRoot): void
    {
        $this->eventStore->appendTo($this->streamName, $aggregateRoot->popRecordedEvents());
    }

    public function getAggregateRoot(IdentifiesAggregate $id): AggregateRoot
    {
        return $this->aggregateType->aggregateRootClass()::reconstituteFromHistory($this->eventStore->load($this->streamName, $id));
    }
}
