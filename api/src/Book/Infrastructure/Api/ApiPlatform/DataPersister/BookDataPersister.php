<?php

declare(strict_types=1);

namespace Book\Infrastructure\Api\ApiPlatform\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Book\Domain\Model\Book\BookId;
use Book\Domain\Model\Book\Command\CreateBook;
use Book\Infrastructure\Api\ApiPlatform\Resource\Book;
use Prooph\Common\Messaging\MessageFactory;
use Prooph\ServiceBus\CommandBus;

final class BookDataPersister implements DataPersisterInterface
{
    private $commandBus;
    private $messageFactory;

    public function __construct(CommandBus $commandBus, MessageFactory $messageFactory)
    {
        $this->commandBus = $commandBus;
        $this->messageFactory = $messageFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($data): bool
    {
        return $data instanceof Book;
    }

    /**
     * {@inheritdoc}
     *
     * @param Book $data
     */
    public function persist($data)
    {
        $id = BookId::generate()->toString();

        $this->commandBus->dispatch($this->messageFactory->createMessageFromArray(CreateBook::MESSAGE_NAME, [
            'payload' => [
                'id' => $id,
                'isbn' => $data->isbn,
                'title' => $data->title,
                'description' => $data->description,
                'author' => $data->author,
            ],
        ]));

        $data->id = $id;

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($data)
    {
        // TODO: Implement remove() method.
    }
}
