<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Persistence\Prooph;

use Prooph\Common\Messaging\DomainEvent;

final class EventData extends DomainEvent
{
    /** @var mixed[] */
    protected $payload = [];

    public function aggregateId() : string
    {
        return $this->metadata['_aggregate_id'];
    }

    public function version() : int
    {
        return $this->metadata['_aggregate_version'];
    }

    /**
     * @return mixed[]
     */
    public function payload() : array
    {
        return $this->payload;
    }

    /**
     * @param mixed[] $payload
     */
    protected function setPayload(array $payload) : void
    {
        $this->payload = $payload;
    }
}
