<?php

declare(strict_types=1);

namespace App\Core\Domain;

abstract class AggregateRoot
{
    private $version = 0;
    private $recordedEvents = [];

    protected function __construct()
    {
    }

    /**
     * @return iterable|DomainEvent[]
     */
    public function popRecordedEvents(): iterable
    {
        $pendingEvents = $this->recordedEvents;

        $this->recordedEvents = [];

        return $pendingEvents;
    }

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
