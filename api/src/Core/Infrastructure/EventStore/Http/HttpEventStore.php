<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\EventStore\Http;

use App\Core\Domain\AggregateRoot;
use App\Core\Domain\EventStore;
use App\Core\Domain\EventStoreLoadResult;
use App\Core\Domain\IdentifiesAggregate;
use App\Core\Infrastructure\Bridge\Prooph\DomainEventTransformer;
use Prooph\EventStore\EventStoreConnection;
use Prooph\EventStore\ExpectedVersion;
use Prooph\EventStore\Internal\Consts;
use Prooph\EventStore\SliceReadStatus;

final class HttpEventStore implements EventStore
{
    private EventStoreConnection $connection;
    private DomainEventTransformer $transformer;

    public function __construct(EventStoreConnection $connection, DomainEventTransformer $transformer)
    {
        $this->connection  = $connection;
        $this->transformer = $transformer;
    }

    public function save(AggregateRoot $aggregateRoot, string $streamCategory, bool $optimisticConcurrency): void
    {
        $domainEvents = $aggregateRoot->popRecordedEvents();

        if (empty($domainEvents)) {
            return;
        }

        $aggregateId = $aggregateRoot->aggregateId();
        $stream      = $streamCategory . '-' . $aggregateId->toString();

        $eventData = [];

        foreach ($domainEvents as $event) {
            $eventData[] = $this->transformer->toEventData($event);
        }

        $expectedVersion = $optimisticConcurrency
            ? $aggregateRoot->expectedVersion()
            : ExpectedVersion::ANY;

        $writeResult = $this->connection
            ->appendToStream(
                $stream,
                $expectedVersion,
                $eventData
            );

        $aggregateRoot->setExpectedVersion(
            $writeResult->nextExpectedVersion()
        );
    }

    public function load(string $streamCategory, IdentifiesAggregate $aggregateId): ?EventStoreLoadResult
    {
        $stream = $streamCategory . '-' . $aggregateId->toString();

        $start = 0;
        $count = Consts::MAX_READ_SIZE;

        do {
            $events = [];

            $streamEventsSlice = $this->connection
                ->readStreamEventsForward(
                    $stream,
                    $start,
                    $count,
                    true
                );

            if (! $streamEventsSlice->status()->equals(SliceReadStatus::success())) {
                return null;
            }

            $start = $streamEventsSlice->nextEventNumber();

            foreach ($streamEventsSlice->events() as $event) {
                $events[] = $this->transformer->toDomainEvent($event);
            }
        } while (! $streamEventsSlice->isEndOfStream());

        return new EventStoreLoadResult($events, $streamEventsSlice->lastEventNumber());
    }
}
