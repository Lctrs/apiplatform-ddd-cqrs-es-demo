<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Persistence\Prooph;

use Prooph\Common\Messaging\Message;
use Prooph\Common\Messaging\MessageFactory;

final class ProophMessageFactory implements MessageFactory
{
    /**
     * @param mixed[] $messageData
     */
    public function createMessageFromArray(string $messageName, array $messageData): Message
    {
        if (!isset($messageData['message_name'])) {
            $messageData['message_name'] = $messageName;
        }

        return EventData::fromArray($messageData);
    }
}
