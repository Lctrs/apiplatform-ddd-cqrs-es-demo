<?php

declare(strict_types=1);

namespace App\Core\Domain;

use Prooph\EventStore\ExpectedVersion;

abstract class AggregateRoot
{
    private int $expectedVersion = ExpectedVersion::EMPTY_STREAM;
    /** @var DomainEvent[] */
    private array $recordedEvents = [];

    final protected function __construct()
    {
    }

    final public function expectedVersion(): int
    {
        return $this->expectedVersion;
    }

    final public function setExpectedVersion(int $version): void
    {
        $this->expectedVersion = $version;
    }

    /**
     * @return iterable|DomainEvent[]
     *
     * @psalm-return iterable<DomainEvent>
     */
    final public function popRecordedEvents(): iterable
    {
        $pendingEvents = $this->recordedEvents;

        $this->recordedEvents = [];

        return $pendingEvents;
    }

    /**
     * @param iterable|DomainEvent[] $historyEvents
     *
     * @psalm-param iterable<DomainEvent> $historyEvents
     */
    final public static function reconstituteFromHistory(iterable $historyEvents): self
    {
        $instance = new static();
        $instance->replay($historyEvents);

        return $instance;
    }

    /**
     * @param iterable|DomainEvent[] $historyEvents
     *
     * @psalm-param iterable<DomainEvent> $historyEvents
     */
    final public function replay(iterable $historyEvents): void
    {
        foreach ($historyEvents as $pastEvent) {
            $this->apply($pastEvent);
        }
    }

    final protected function recordThat(DomainEvent $event): void
    {
        $this->recordedEvents[] = $event;

        $this->apply($event);
    }

    abstract public function aggregateId(): IdentifiesAggregate;

    abstract protected function apply(DomainEvent $event): void;
}
