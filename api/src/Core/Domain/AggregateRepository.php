<?php

declare(strict_types=1);

namespace App\Core\Domain;

abstract class AggregateRepository
{
    private $eventStore;
    private $aggregateType;

    public function __construct(EventStore $eventStore, AggregateType $aggregateType)
    {
        $this->eventStore = $eventStore;
        $this->aggregateType = $aggregateType;
    }

    public function saveAggregateRoot(AggregateRoot $aggregateRoot): void
    {
        $this->eventStore->appendTo('event_stream', $this->aggregateType, $aggregateRoot->popRecordedEvents());
    }

    public function getAggregateRoot(IdentifiesAggregate $id): AggregateRoot
    {
        return $this->aggregateType->aggregateRootClass()::reconstituteFromHistory($this->eventStore->load('event_stream', $this->aggregateType, $id));
    }
}
