<?php

declare(strict_types=1);

namespace App\Core\Domain;

abstract class AggregateRepository
{
    /** @var EventStore */
    private $eventStore;
    /** @var AggregateType */
    private $aggregateType;

    public function __construct(EventStore $eventStore, AggregateType $aggregateType)
    {
        $this->eventStore    = $eventStore;
        $this->aggregateType = $aggregateType;
    }

    public function saveAggregateRoot(AggregateRoot $aggregateRoot) : void
    {
        $this->eventStore->appendTo($this->aggregateType, $aggregateRoot->popRecordedEvents());
    }

    public function getAggregateRoot(IdentifiesAggregate $id) : AggregateRoot
    {
        return $this->aggregateType->aggregateRootClass()::reconstituteFromHistory(
            $this->eventStore->load(
                $this->aggregateType,
                $id
            )
        );
    }
}
