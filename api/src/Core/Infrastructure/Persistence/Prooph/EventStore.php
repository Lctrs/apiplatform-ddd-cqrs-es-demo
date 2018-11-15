<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Persistence\Prooph;

use App\Core\Domain\DomainEvent;
use App\Core\Domain\EventStore as EventStoreInterface;
use App\Core\Domain\IdentifiesAggregate;
use App\Core\Domain\MapperIterator;
use App\Core\Infrastructure\Persistence\Prooph\Internal\DomainEventTransformer;
use ArrayIterator;
use Prooph\Common\Messaging\Message;
use Prooph\EventStore\EventStore as ProophEventStore;
use Prooph\EventStore\Metadata\MetadataMatcher;
use Prooph\EventStore\Metadata\Operator;
use Prooph\EventStore\Stream;
use Prooph\EventStore\StreamName;
use Traversable;
use function reset;

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
    public function appendTo(string $streamName, iterable $streamEvents): void
    {
        $firstEvent = $this->getFirstEvent($streamEvents);

        if (null === $firstEvent) {
            return;
        }

        $createStream = false;
        if ($this->isFirstStreamEvent($firstEvent)) {
            $createStream = true;
        }

        if (!$streamEvents instanceof Traversable) {
            $streamEvents = new ArrayIterator($streamEvents);
        }

        if ($createStream) {
            $stream = new Stream(
                new StreamName($streamName),
                new MapperIterator($streamEvents, function (DomainEvent $event): Message {
                    return $this->transformer->transform($event);
                })
            );

            $this->eventStore->create($stream);

            return;
        }

        $this->eventStore->appendTo(
            new StreamName($streamName),
            new MapperIterator($streamEvents, function (DomainEvent $event): Message {
                return $this->transformer->transform($event);
            })
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

        return new MapperIterator(
            $this->eventStore->load(new StreamName($streamName), 1, null, $metadataMatcher),
            function (Message $message): DomainEvent {
                return $this->transformer->reverseTransform($message);
            }
        );
    }

    private function getFirstEvent(iterable $streamEvents): ?DomainEvent
    {
        if ($streamEvents instanceof Traversable) {
            foreach ($streamEvents as $streamEvent) {
                return $streamEvent;
            }

            return null;
        }

        if ($streamEvents === []) {
            return null;
        }

        return reset($streamEvents);
    }

    private function isFirstStreamEvent(DomainEvent $domainEvent): bool
    {
        return 1 === $domainEvent->version();
    }
}
