<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Bridge\Prooph;

use App\Core\Domain\DomainEvent;
use Prooph\EventStore\EventData;
use Prooph\EventStore\EventId;
use Prooph\EventStore\ResolvedEvent;
use Prooph\EventStore\Util\Json;
use RuntimeException;
use function assert;
use function is_array;

final class DomainEventTransformer
{
    /**
     * @var array<string, string>
     * @psalm-var array<string, class-string<DomainEvent>>
     */
    private $map;

    /**
     * @param array<string, string> $map
     *
     * @psalm-param array<string, class-string<DomainEvent>> $map
     */
    public function __construct(array $map)
    {
        $this->map = $map;
    }

    public function toEventData(DomainEvent $event) : EventData
    {
        $eventId = $event->eventId();
        if ($eventId === null) {
            $eventId = EventId::generate();
        } else {
            $eventId = EventId::fromString($eventId);
        }

        return new EventData(
            $eventId,
            $event->eventType(),
            true,
            Json::encode($event->toArray())
        );
    }

    /**
     * @throws RuntimeException
     */
    public function toDomainEvent(ResolvedEvent $event) : DomainEvent
    {
        $event = $event->event();

        if ($event === null) {
            throw new RuntimeException('Event is null.');
        }

        $type = $event->eventType();

        if (! isset($this->map[$type])) {
            throw new RuntimeException('No event class for type ' . $type . ' given.');
        }

        $payload = Json::decode($event->data());

        assert(is_array($payload));

        $class = $this->map[$type];

        return $class::from($event->eventId(), $payload);
    }
}
