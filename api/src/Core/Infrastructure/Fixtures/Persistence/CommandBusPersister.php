<?php

declare(strict_types=1);

namespace Core\Infrastructure\Fixtures\Persistence;

use Fidry\AliceDataFixtures\Persistence\PersisterInterface;
use Nelmio\Alice\IsAServiceTrait;
use Prooph\Common\Messaging\Command;
use Prooph\ServiceBus\CommandBus;

final class CommandBusPersister implements PersisterInterface
{
    use IsAServiceTrait;

    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * Persists objects into the database.
     *
     * @param object $object
     */
    public function persist($object): void
    {
        if (!$object instanceof Command) {
            return;
        }

        $this->commandBus->dispatch($object);
    }

    public function flush(): void
    {
        // No-op
    }
}
