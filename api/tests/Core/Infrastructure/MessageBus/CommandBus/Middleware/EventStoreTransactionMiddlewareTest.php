<?php

declare(strict_types=1);

namespace App\Tests\Core\Infrastructure\MessageBus\CommandBus\Middleware;

use App\Core\Infrastructure\MessageBus\CommandBus\Middleware\EventStoreTransactionMiddleware;
use Prooph\EventStore\TransactionalEventStore;
use stdClass;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Test\Middleware\MiddlewareTestCase;

class EventStoreTransactionMiddlewareTest extends MiddlewareTestCase
{
    /** @var TransactionalEventStore */
    private $eventStore;
    /** @var EventStoreTransactionMiddleware */
    private $middleware;

    public function setUp() : void
    {
        $this->eventStore = $this->createMock(TransactionalEventStore::class);

        $this->middleware = new EventStoreTransactionMiddleware($this->eventStore);
    }

    public function testMiddlewareWrapsInTransactionAndFlushes() : void
    {
        $this->eventStore->expects($this->once())
            ->method('beginTransaction');
        $this->eventStore->expects($this->once())
            ->method('commit');

        $this->middleware->handle(new Envelope(new stdClass()), $this->getStackMock());
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Thrown from next middleware.
     */
    public function testTransactionIsRolledBackOnException() : void
    {
        $this->eventStore->expects($this->once())
            ->method('beginTransaction');
        $this->eventStore->expects($this->once())
            ->method('rollback');

        $this->middleware->handle(new Envelope(new stdClass()), $this->getThrowingStackMock());
    }
}
