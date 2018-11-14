<?php

declare(strict_types=1);

namespace Core\Domain;

abstract class AggregateRepository
{
    private $eventStore;
    private $streamName;
    private $aggregateRootType;

    public function __construct(EventStore $eventStore, string $streamName, string $aggregateRootType)
    {
        if (!is_subclass_of($aggregateRootType, AggregateRoot::class, true)) {
            throw new \InvalidArgumentException(sprintf('"%s" is not a valid aggregate root type. It must extends "%s".', $aggregateRootType, AggregateRoot::class));
        }

        $this->eventStore = $eventStore;
        $this->streamName = $streamName;
        $this->aggregateRootType = $aggregateRootType;
    }

    public function saveAggregateRoot(AggregateRoot $aggregateRoot): void
    {
        $this->eventStore->appendTo($this->streamName, $aggregateRoot->popRecordedEvents());
    }

    public function getAggregateRoot(IdentifiesAggregate $id): AggregateRoot
    {
        return $this->aggregateRootType::reconstituteFromHistory($this->eventStore->load($this->streamName, $id));
    }
}
