<?php

declare(strict_types=1);

namespace App\Core\Domain;

use Prooph\EventStore\ExpectedVersion;

abstract class AggregateRoot
{
    /** @var int */
    private $expectedVersion = ExpectedVersion::EMPTY_STREAM;
    /** @var DomainEvent[] */
    private $recordedEvents = [];

    final protected function __construct()
    {
    }

    public function expectedVersion() : int
    {
        return $this->expectedVersion;
    }

    public function setExpectedVersion(int $version) : void
    {
        $this->expectedVersion = $version;
    }

    /**
     * @return iterable|DomainEvent[]
     */
    public function popRecordedEvents() : iterable
    {
        $pendingEvents = $this->recordedEvents;

        $this->recordedEvents = [];

        return $pendingEvents;
    }

    /**
     * @param DomainEvent[] $historyEvents
     */
    public static function reconstituteFromHistory(iterable $historyEvents) : self
    {
        $instance = new static();
        $instance->replay($historyEvents);

        return $instance;
    }

    /**
     * @param DomainEvent[] $historyEvents
     */
    public function replay(iterable $historyEvents) : void
    {
        foreach ($historyEvents as $pastEvent) {
            $this->apply($pastEvent);
        }
    }

    protected function recordThat(DomainEvent $event) : void
    {
        $this->recordedEvents[] = $event;

        $this->apply($event);
    }

    abstract public function aggregateId() : IdentifiesAggregate;

    abstract protected function apply(DomainEvent $event) : void;
}
