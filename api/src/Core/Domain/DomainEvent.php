<?php

declare(strict_types=1);

namespace App\Core\Domain;

use Prooph\EventStore\EventId;

interface DomainEvent
{
    public function eventId() : ?string;

    public function eventType() : string;

    /**
     * @return array<string, (string|int|bool|float|null)>
     */
    public function toArray() : array;

    /**
     * @param array<string, (string|int|bool|float|null)> $data
     */
    public static function from(EventId $eventId, array $data) : self;
}
