<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Persistence\Prooph\Internal;

use App\Core\Domain\DomainEvent;
use Prooph\Common\Messaging\Message;
use Prooph\EventSourcing\AggregateChanged;
use Ramsey\Uuid\Uuid;

final class DomainEventTransformer
{
    private $eventNameToEventFqcnMap;

    public function __construct(array $eventNameToEventFqcnMap)
    {
        $this->eventNameToEventFqcnMap = $eventNameToEventFqcnMap;
    }

    public function transform(DomainEvent $domainEvent): Message
    {
        return AggregateChanged::fromArray([
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

    public function reverseTransform(Message $message): DomainEvent
    {
        $messageName = $message->messageName();

        if (!isset($this->eventNameToEventFqcnMap[$messageName])) {
            throw new \UnexpectedValueException(sprintf('Unknown message name "%s".', $messageName));
        }

        $fqcn = $this->eventNameToEventFqcnMap[$messageName];

        if (!\class_exists($fqcn)) {
            throw new \UnexpectedValueException('Given message class is not a valid class: '.$fqcn);
        }

        if (!\is_subclass_of($fqcn, DomainEvent::class, true)) {
            throw new \UnexpectedValueException(\sprintf(
                'Message class %s is not a sub class of %s',
                $fqcn,
                DomainEvent::class
            ));
        }

        $data = $message->payload();

        $metadata = $message->metadata();
        $data['aggregateId'] = $metadata['_aggregate_id'];
        $data['version'] = $metadata['_aggregate_version'];

        $data['occuredOn'] = $message->createdAt();

        return $fqcn::fromArray($data);
    }
}
