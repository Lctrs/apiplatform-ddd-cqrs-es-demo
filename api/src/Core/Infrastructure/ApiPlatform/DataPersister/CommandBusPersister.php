<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\ApiPlatform\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Core\Infrastructure\ApiPlatform\Resource;
use LogicException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

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
     *
     * @param mixed $data
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
        $envelope = $this->commandBus->dispatch($data->toCommand());

        $handledStamp = $envelope->last(HandledStamp::class);
        if (! $handledStamp instanceof HandledStamp) {
            return $data;
        }

        return $handledStamp->getResult();
    }

    /**
     * {@inheritdoc}
     */
    public function remove($data) : void
    {
        throw new LogicException('Should never been called. Not relevant in a ES system.');
    }
}
