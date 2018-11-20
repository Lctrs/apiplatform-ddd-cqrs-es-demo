<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Persistence\Prooph;

use App\Core\Domain\AggregateType;
use App\Core\Domain\DomainEvent;
use App\Core\Domain\EventStore as EventStoreInterface;
use App\Core\Domain\IdentifiesAggregate;
use App\Core\Domain\MapperIterator;
use ArrayIterator;
use Prooph\Common\Messaging\Message;
use Prooph\EventStore\EventStore as ProophEventStore;
use Prooph\EventStore\Metadata\MetadataMatcher;
use Prooph\EventStore\Metadata\Operator;
use Prooph\EventStore\StreamName;
use Traversable;

final class EventStore implements EventStoreInterface
{
    private $eventStore;
    private $transformer;

    public function __construct(ProophEventStore $eventStore, DomainEventTransformer $transformer)
    {
        $this->eventStore = $eventStore;
        $this->transformer = $transformer;
    }

    /**
     * @param iterable|DomainEvent[] $streamEvents
     */
    public function appendTo(string $streamName, AggregateType $aggregateType, iterable $streamEvents): void
    {
        if (!$streamEvents instanceof Traversable) {
            $streamEvents = new ArrayIterator($streamEvents);
        }

        $this->eventStore->appendTo(
            new StreamName($streamName),
            new MapperIterator(
                new MapperIterator($streamEvents, function (DomainEvent $event): Message {
                    return DomainEventTransformer::toEventData($event);
                }),
                function (EventData $eventData) use ($aggregateType): Message {
                    return $eventData->withAddedMetadata('_aggregate_type', $aggregateType->aggregateType());
                })
        );
    }

    public function load(string $streamName, AggregateType $aggregateType, IdentifiesAggregate $aggregateId): iterable
    {
        $metadataMatcher = (new MetadataMatcher())
            ->withMetadataMatch(
                '_aggregate_type',
                Operator::EQUALS(),
                $aggregateType->aggregateType()
            )
            ->withMetadataMatch(
                '_aggregate_id',
                Operator::EQUALS(),
                $aggregateId->__toString()
            );

        return new MapperIterator(
            $this->eventStore->load(new StreamName($streamName), 1, null, $metadataMatcher),
            function (EventData $eventData): DomainEvent {
                return $this->transformer->toDomainEvent($eventData);
            }
        );
    }
}
