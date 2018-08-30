<?php

declare(strict_types=1);

namespace Core\Infrastructure\MessageBus\CommandBus\Middleware;

use Prooph\EventStore\TransactionalEventStore;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;

final class EventStoreTransactionMiddleware implements MiddlewareInterface
{
    private $eventStore;

    public function __construct(TransactionalEventStore $eventStore)
    {
        $this->eventStore = $eventStore;
    }

    /**
     * {@inheritdoc}
     */
    public function handle($message, callable $next)
    {
        $this->eventStore->beginTransaction();

        try {
            $result = $next($message);
            $this->eventStore->commit();
        } catch (\Throwable $e) {
            $this->eventStore->rollback();

            throw $e;
        }

        return $result;
    }
}
