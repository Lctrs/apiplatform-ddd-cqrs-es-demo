<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Prooph\EventStore;

use App\Core\Domain\DomainEvent;
use Prooph\Common\Messaging\DomainMessage;
use RuntimeException;
use Symfony\Component\Uid\Uuid;

use function assert;
use function is_array;

final class DomainEventTransformer
{
    /** @var array<string, class-string<DomainEvent>> */
    private array $map;

    /**
     * @param array<string, class-string<DomainEvent>> $map
     */
    public function __construct(array $map)
    {
        $this->map = $map;
    }

    public function toDomainMessage(DomainEvent $event): DomainMessage
    {
        return EventData::fromArray([
            'uuid' => $event->eventId() ?? Uuid::v6()->toRfc4122(),
            'message_name' => $event->eventType(),
            'metadata' => [
                '_aggregate_id' => $event->aggregateId()->__toString(),
                '_aggregate_version' => $event->version(),
            ],
            'created_at' => $event->occurredOn(),
            'payload' => $event->toArray(),
        ]);
    }

    /**
     * @throws RuntimeException
     */
    public function toDomainEvent(ResolvedEvent $event): DomainEvent
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
