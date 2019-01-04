<?php

declare(strict_types=1);

namespace App\Core\Domain;

abstract class AggregateRoot
{
    /** @var int */
    private $version = 0;
    /** @var DomainEvent[] */
    private $recordedEvents = [];

    protected function __construct()
    {
    }

    abstract public function aggregateId(): IdentifiesAggregate;

    /**
     * @return iterable|DomainEvent[]
     */
    public function popRecordedEvents(): iterable
    {
        $pendingEvents = $this->recordedEvents;

        $this->recordedEvents = [];

        return $pendingEvents;
    }

    /**
     * @param iterable|DomainEvent[] $historyEvents
     */
    public static function reconstituteFromHistory(iterable $historyEvents): self
    {
        $instance = new static();

        foreach ($historyEvents as $pastEvent) {
            $instance->version = $pastEvent->version();
            $instance->when($pastEvent);
        }

        return $instance;
    }

    protected function recordThat(DomainEvent $event): void
    {
        ++$this->version;
        $this->recordedEvents[] = $event->withVersion($this->version);

        $this->when($event);
    }

    abstract protected function when(DomainEvent $event): void;
}
