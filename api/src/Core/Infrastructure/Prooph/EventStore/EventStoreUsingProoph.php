<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Prooph\EventStore;

use App\Core\Domain\AggregateRoot;
use App\Core\Domain\EventStore;
use App\Core\Domain\EventStoreLoadResult;
use App\Core\Domain\IdentifiesAggregate;
use Prooph\EventStore\EventStore as ProophEventStore;
use Prooph\EventStore\StreamName;

final class EventStoreUsingProoph implements EventStore
{
    private ProophEventStore $eventStore;
    private DomainEventTransformer $domainEventTransformer;

    public function __construct(ProophEventStore $eventStore, DomainEventTransformer $domainEventTransformer)
    {
        $this->eventStore             = $eventStore;
        $this->domainEventTransformer = $domainEventTransformer;
    }

    public function save(AggregateRoot $aggregateRoot, string $streamCategory): void
    {
        $domainEvents = $aggregateRoot->popRecordedEvents();
        if ($domainEvents === []) {
            return;
        }

        $this->eventStore->appendTo(
            $this->buildStreamName($streamCategory),
            $domainEvents
        );
    }

    public function load(string $streamCategory, IdentifiesAggregate $aggregateId): ?EventStoreLoadResult
    {
    }

    private function buildStreamName(string $streamCategory): StreamName
    {
        return new StreamName($streamCategory);
    }
}
