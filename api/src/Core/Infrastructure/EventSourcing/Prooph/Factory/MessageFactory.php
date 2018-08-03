<?php

declare(strict_types=1);

namespace Core\Infrastructure\EventSourcing\Prooph\Factory;

use Book\App\Command\CreateBook;
use Book\App\Command\PostReview;
use Prooph\Common\Messaging\FQCNMessageFactory;
use Prooph\Common\Messaging\Message;

final class MessageFactory extends FQCNMessageFactory
{
    private const MAP = [
        CreateBook::MESSAGE_NAME => CreateBook::class,
        PostReview::MESSAGE_NAME => PostReview::class,
    ];

    public function createMessageFromArray(string $messageName, array $messageData): Message
    {
        return parent::createMessageFromArray(self::MAP[$messageName] ?? $messageName, $messageData);
    }
}
