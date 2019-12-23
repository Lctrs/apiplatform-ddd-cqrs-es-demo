<?php

declare(strict_types=1);

namespace App\Core\Domain;

use Prooph\EventStore\EventStoreConnection;
use Prooph\EventStore\ExpectedVersion;
use Prooph\EventStore\Internal\Consts;
use Prooph\EventStore\SliceReadStatus;
use Prooph\EventStore\UserCredentials;
use function assert;

abstract class AggregateRepository
{
    /** @var EventStoreConnection */
    protected $eventStoreConnection;
    /** @var DomainEventTransformer */
    protected $transformer;
    /** @var string */
    protected $streamCategory;
    /** @var string */
    protected $aggregateRootClassName;
    /** @var bool */
    protected $optimisticConcurrency;

    public function __construct(
        EventStoreConnection $eventStoreConnection,
        DomainEventTransformer $transformer,
        string $streamCategory,
        string $aggregateRootClassName,
        bool $useOptimisticConcurrencyByDefault = true
    ) {
        $this->eventStoreConnection   = $eventStoreConnection;
        $this->transformer            = $transformer;
        $this->streamCategory         = $streamCategory;
        $this->aggregateRootClassName = $aggregateRootClassName;
        $this->optimisticConcurrency  = $useOptimisticConcurrencyByDefault;
    }

    public function saveAggregateRoot(
        AggregateRoot $aggregateRoot,
        ?int $expectedVersion = null,
        ?UserCredentials $credentials = null
    ) : void {
        $domainEvents = $aggregateRoot->popRecordedEvents();

        if (empty($domainEvents)) {
            return;
        }

        $aggregateId = $aggregateRoot->aggregateId();
        $stream      = $this->streamCategory . '-' . $aggregateId->toString();

        $eventData = [];

        foreach ($domainEvents as $event) {
            $eventData[] = $this->transformer->toEventData($event);
        }

        if ($expectedVersion === null) {
            $expectedVersion = $this->optimisticConcurrency
                ? $aggregateRoot->expectedVersion()
                : ExpectedVersion::ANY;
        }

        $writeResult = $this->eventStoreConnection
            ->appendToStream(
                $stream,
                $expectedVersion,
                $eventData,
                $credentials
            );

        $aggregateRoot->setExpectedVersion(
            $writeResult->nextExpectedVersion()
        );
    }

    /**
     * Returns null if no stream events can be found for aggregate root otherwise the reconstituted aggregate root
     */
    public function getAggregateRoot(
        IdentifiesAggregate $aggregateId,
        ?UserCredentials $credentials = null
    ) : ?AggregateRoot {
        $stream = $this->streamCategory . '-' . $aggregateId->toString();

        $start = 0;
        $count = Consts::MAX_READ_SIZE;

        do {
            $events = [];

            $streamEventsSlice = $this->eventStoreConnection
                ->readStreamEventsForward(
                    $stream,
                    $start,
                    $count,
                    true,
                    $credentials
                );

            if (! $streamEventsSlice->status()->equals(SliceReadStatus::success())) {
                return null;
            }

            $start = $streamEventsSlice->nextEventNumber();

            foreach ($streamEventsSlice->events() as $event) {
                $events[] = $this->transformer->toDomainEvent($event);
            }

            if (isset($aggregateRoot)) {
                assert($aggregateRoot instanceof AggregateRoot);
                $aggregateRoot->replay($events);
            } else {
                $className     = $this->aggregateRootClassName;
                $aggregateRoot = $className::reconstituteFromHistory($events);
            }
        } while (! $streamEventsSlice->isEndOfStream());

        $aggregateRoot->setExpectedVersion(
            $streamEventsSlice->lastEventNumber()
        );

        return $aggregateRoot;
    }
}
