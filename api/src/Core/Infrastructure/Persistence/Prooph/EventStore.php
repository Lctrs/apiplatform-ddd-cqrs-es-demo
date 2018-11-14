<?php

declare(strict_types=1);

namespace Core\Infrastructure\Persistence\Prooph;

use Core\Domain\EventStore as EventStoreInterface;
use Core\Domain\IdentifiesAggregate;
use Core\Infrastructure\Persistence\Prooph\Internal\CallableIterator;
use Core\Infrastructure\Persistence\Prooph\Internal\DomainEventToProophMessageTransformer;
use Prooph\EventStore\EventStore as ProophEventStore;
use Prooph\EventStore\Metadata\MetadataMatcher;
use Prooph\EventStore\Metadata\Operator;
use Prooph\EventStore\StreamName;

final class EventStore implements EventStoreInterface
{
    private $eventStore;
    private $transformer;

    public function __construct(ProophEventStore $eventStore, DomainEventToProophMessageTransformer $transformer)
    {
        $this->eventStore = $eventStore;
        $this->transformer = $transformer;
    }

    public function appendTo(string $streamName, iterable $streamEvents): void
    {
        $this->eventStore->appendTo(
            new StreamName($streamName),
            new CallableIterator(new \ArrayIterator($streamEvents), [$this->transformer, 'transform'])
        );
    }

    public function load(string $streamName, IdentifiesAggregate $aggregateId): iterable
    {
        $metadataMatcher = (new MetadataMatcher())
            ->withMetadataMatch(
                '_aggregate_id',
                Operator::EQUALS(),
                $aggregateId->__toString()
            );

        return new CallableIterator(
            $this->eventStore->load(new StreamName($streamName), 1, null, $metadataMatcher),
            [$this->transformer, 'reverseTransform']
        );
    }
}
