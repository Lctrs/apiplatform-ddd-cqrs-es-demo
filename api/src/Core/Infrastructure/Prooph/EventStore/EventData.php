<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Prooph\EventStore;

use Prooph\Common\Messaging\DomainEvent;

final class EventData extends DomainEvent
{
    private array $payload = [];

    protected function setPayload(array $payload): void
    {
        $this->payload = $payload;
    }

    public function payload(): array
    {
        return $this->payload;
    }
}
