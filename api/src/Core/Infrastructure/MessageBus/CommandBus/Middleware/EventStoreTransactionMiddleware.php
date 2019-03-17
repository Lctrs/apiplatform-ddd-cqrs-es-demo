<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\MessageBus\CommandBus\Middleware;

use Prooph\EventStore\TransactionalEventStore;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Throwable;

final class EventStoreTransactionMiddleware implements MiddlewareInterface
{
    /** @var TransactionalEventStore */
    private $eventStore;

    public function __construct(TransactionalEventStore $eventStore)
    {
        $this->eventStore = $eventStore;
    }

    public function handle(Envelope $envelope, StackInterface $stack) : Envelope
    {
        $this->eventStore->beginTransaction();

        try {
            $envelope = $stack->next()->handle($envelope, $stack);

            $this->eventStore->commit();
        } catch (Throwable $e) {
            $this->eventStore->rollback();

            throw $e;
        }

        return $envelope;
    }
}
