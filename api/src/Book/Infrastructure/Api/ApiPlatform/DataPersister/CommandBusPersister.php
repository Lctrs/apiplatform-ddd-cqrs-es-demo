<?php

declare(strict_types=1);

namespace App\Book\Infrastructure\Api\ApiPlatform\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Core\Infrastructure\Api\Resource;
use LogicException;
use Symfony\Component\Messenger\MessageBusInterface;

final class CommandBusPersister implements DataPersisterInterface
{
    /** @var MessageBusInterface */
    private $commandBus;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($data) : bool
    {
        return $data instanceof Resource;
    }

    /**
     * {@inheritdoc}
     *
     * @param Resource $data
     */
    public function persist($data)
    {
        $command = $data->toCommand();

        $this->commandBus->dispatch($command);

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($data) : void
    {
        throw new LogicException('Should never been called. Not relevant in a ES system.');
    }
}
