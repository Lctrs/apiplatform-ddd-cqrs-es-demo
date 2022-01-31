<?php

declare(strict_types=1);

namespace App\Greeting\Domain\Model\Event;

use App\Core\Domain\DomainEvent;
use App\Greeting\Domain\Model\GreetingId;
use App\Greeting\Domain\Model\Name;
use DateTimeImmutable;
use Prooph\EventStore\EventId;

final class SomeoneHasBeenGreeted implements DomainEvent
{
    public const MESSAGE_NAME = 'someone-has-been-greeted';

    private ?string $eventId = null;
    private DateTimeImmutable $occurredOn;
    private int $version;
    private GreetingId $greetingId;
    private Name $name;

    private function __construct(GreetingId $greetingId, Name $name)
    {
        $this->occurredOn = new DateTimeImmutable();
        $this->greetingId = $greetingId;
        $this->name       = $name;
    }

    public static function with(GreetingId $greetingId, Name $name): self
    {
        return new self($greetingId, $name);
    }

    public function greetingId(): GreetingId
    {
        return $this->greetingId;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function eventId(): ?string
    {
        return $this->eventId;
    }

    public function eventType(): string
    {
        return self::MESSAGE_NAME;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }

    public function withVersion(int $version): DomainEvent
    {
        $clone = clone $this;

        $clone->version = $version;

        return $clone;
    }

    public function version(): int
    {
        return $this->version;
    }

    public function toArray(): array
    {
        return [
            'greetingId' => $this->greetingId->toString(),
            'name' => $this->name->toString(),
        ];
    }

    public static function from(EventId $eventId, array $data): DomainEvent
    {
        // TODO: Implement from() method.
    }
}
