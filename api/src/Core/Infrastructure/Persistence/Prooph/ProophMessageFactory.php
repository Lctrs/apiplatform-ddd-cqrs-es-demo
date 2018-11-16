<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Persistence\Prooph;

use Prooph\Common\Messaging\Message;
use Prooph\Common\Messaging\MessageFactory;
use Prooph\EventSourcing\AggregateChanged;

final class ProophMessageFactory implements MessageFactory
{
    public function createMessageFromArray(string $messageName, array $messageData): Message
    {
        if (!isset($messageData['message_name'])) {
            $messageData['message_name'] = $messageName;
        }

        return AggregateChanged::fromArray($messageData);
    }
}
