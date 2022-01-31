<?php

declare(strict_types=1);

namespace App\Core\Domain;


abstract class AggregateRoot
{
    private int $version = 0;

    /** @var list<DomainEvent> */
    private array $recordedEvents = [];

    final protected function __construct()
    {
    }

    /**
     * @return list<DomainEvent>
     */
    final public function popRecordedEvents(): array
    {
        try {
            return $this->recordedEvents;
        } finally {
            $this->recordedEvents = [];
        }
    }

    /**
     * @param iterable<DomainEvent> $historyEvents
     */
    final public static function reconstituteFromHistory(iterable $historyEvents): self
    {
        $instance = new static();
        $instance->replay($historyEvents);

        return $instance;
    }

    /**
     * @param iterable<DomainEvent> $historyEvents
     */
    final public function replay(iterable $historyEvents): void
    {
        foreach ($historyEvents as $pastEvent) {
            $this->apply($pastEvent);
        }
    }

    final protected function recordThat(DomainEvent $event): void
    {
        ++$this->version;

        $this->recordedEvents[] = $event->withVersion($this->version);

        $this->apply($event);
    }

    abstract public function aggregateId(): IdentifiesAggregate;

    abstract protected function apply(DomainEvent $event): void;
}
