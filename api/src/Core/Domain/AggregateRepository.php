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
        $this->eventStore->appendTo($this->determineStreamName($aggregateRoot->aggregateId()), $aggregateRoot->popRecordedEvents());
    }

    public function getAggregateRoot(IdentifiesAggregate $id): AggregateRoot
    {
        return $this->aggregateType->aggregateRootClass()::reconstituteFromHistory($this->eventStore->load($this->determineStreamName($id), $id));
    }

    private function determineStreamName(IdentifiesAggregate $id): string
    {
        return $this->aggregateType->aggregateType().'-'.$id->__toString();
    }
}
