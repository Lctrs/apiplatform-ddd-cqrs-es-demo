<?php

declare(strict_types=1);

namespace App\Core\Domain;

abstract class AggregateRepository
{
    protected EventStore $eventStore;
    protected string $streamCategory;
    /** @var class-string<AggregateRoot> */
    protected string $aggregateRootClassName;

    /**
     * @psalm-param class-string<AggregateRoot> $aggregateRootClassName
     */
    public function __construct(
        EventStore $eventStore,
        string $streamCategory,
        string $aggregateRootClassName
    ) {
        $this->eventStore             = $eventStore;
        $this->streamCategory         = $streamCategory;
        $this->aggregateRootClassName = $aggregateRootClassName;
    }

    public function saveAggregateRoot(AggregateRoot $aggregateRoot): void
    {
        $this->eventStore->save($aggregateRoot, $this->streamCategory);
    }

    /**
     * Returns null if no stream events can be found for aggregate root otherwise the reconstituted aggregate root
     */
    public function getAggregateRoot(IdentifiesAggregate $aggregateId): ?AggregateRoot
    {
        $result = $this->eventStore->load($this->streamCategory, $aggregateId);
        if ($result === null) {
            return null;
        }

        $className     = $this->aggregateRootClassName;
        $aggregateRoot = $className::reconstituteFromHistory($result->domainEvents());

        $aggregateRoot->setExpectedVersion(
            $result->version()
        );

        return $aggregateRoot;
    }
}
