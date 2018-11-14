<?php

declare(strict_types=1);

namespace Book\Infrastructure\Api\ApiPlatform\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Book\Infrastructure\Projection\Doctrine\Orm\Entity\Book;
use Prooph\Common\Messaging\Command;
use Symfony\Component\Messenger\MessageBusInterface;

final class MessageBusPersister implements DataPersisterInterface
{
    private $commandBus;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($data): bool
    {
        return $data instanceof Command;
    }

    /**
     * {@inheritdoc}
     *
     * @param Command $data
     */
    public function persist($data): Command
    {
        $this->commandBus->dispatch($data);

        return $data;
    }

    /**
     * {@inheritdoc}
     *
     * @param Book $data
     */
    public function remove($data): void
    {
    }
}
