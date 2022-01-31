<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Prooph\EventStore;

use Prooph\Common\Messaging\Message;
use Prooph\Common\Messaging\MessageFactory;

final class EventDataFactory implements MessageFactory
{
    /**
     * @inheritDoc
     */
    public function createMessageFromArray(string $messageName, array $messageData): Message
    {
        if (! isset($messageData['message_name'])) {
            $messageData['message_name'] = $messageName;
        }

        return EventData::fromArray($messageData);
    }
}
