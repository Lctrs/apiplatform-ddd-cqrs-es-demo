<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Persistence\Prooph;

use App\Core\Domain\DomainEvent;
use Ramsey\Uuid\Uuid;
use UnexpectedValueException;
use function class_exists;
use function is_subclass_of;
use function sprintf;

final class DomainEventTransformer
{
    /** @var mixed[] */
    private $eventNameToEventFqcnMap;

    /**
     * @param mixed[] $eventNameToEventFqcnMap
     */
    public function __construct(array $eventNameToEventFqcnMap)
    {
        $this->eventNameToEventFqcnMap = $eventNameToEventFqcnMap;
    }

    public static function toEventData(DomainEvent $domainEvent) : EventData
    {
        return EventData::fromArray([
            'uuid' => Uuid::uuid4()->toString(),
            'message_name' => $domainEvent->name(),
            'metadata' => [
                '_aggregate_id' => $domainEvent->aggregateId()->__toString(),
                '_aggregate_version' => $domainEvent->version(),
            ],
            'created_at' => $domainEvent->occuredOn(),
            'payload' => $domainEvent->toArray(),
        ]);
    }

    public function toDomainEvent(EventData $message) : DomainEvent
    {
        $messageName = $message->messageName();

        if (! isset($this->eventNameToEventFqcnMap[$messageName])) {
            throw new UnexpectedValueException(sprintf('Unknown message name "%s".', $messageName));
        }

        $fqcn = $this->eventNameToEventFqcnMap[$messageName];

        if (! class_exists($fqcn)) {
            throw new UnexpectedValueException('Given message class is not a valid class: ' . $fqcn);
        }

        if (! is_subclass_of($fqcn, DomainEvent::class, true)) {
            throw new UnexpectedValueException(sprintf(
                'Message class %s is not a sub class of %s',
                $fqcn,
                DomainEvent::class
            ));
        }

        $data = $message->payload();

        $data['aggregateId'] = $message->aggregateId();
        $data['version']     = $message->version();
        $data['occuredOn']   = $message->createdAt();

        return $fqcn::fromArray($data);
    }
}
