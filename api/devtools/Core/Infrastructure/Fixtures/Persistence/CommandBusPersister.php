<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Fixtures\Persistence;

use App\Core\Domain\Command;
use Fidry\AliceDataFixtures\Persistence\PersisterInterface;
use Nelmio\Alice\IsAServiceTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class CommandBusPersister implements PersisterInterface
{
    use IsAServiceTrait;

    /** @var MessageBusInterface */
    private $commandBus;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @inheritdoc
     */
    public function persist($object) : void
    {
        if (! $object instanceof Command) {
            return;
        }

        $this->commandBus->dispatch($object);
    }

    public function flush() : void
    {
        // No-op
    }
}
