<?php

declare(strict_types=1);

namespace App\Greeting\Domain\Model;

use App\Core\Domain\AggregateRoot;
use App\Core\Domain\DomainEvent;
use App\Core\Domain\IdentifiesAggregate;
use App\Greeting\Domain\Model\Event\SomeoneHasBeenGreeted;
use RuntimeException;
use function sprintf;

final class Greeting extends AggregateRoot
{
    private GreetingId $id;
    private Name $name;

    public static function greet(GreetingId $id, Name $name): self
    {
        $greeting = new self();

        $greeting->recordThat(SomeoneHasBeenGreeted::with($id, $name));

        return $greeting;
    }

    public function aggregateId(): IdentifiesAggregate
    {
        return $this->id;
    }

    public function name(): Name
    {
        return $this->name;
    }

    protected function apply(DomainEvent $event): void
    {
        if ($event instanceof SomeoneHasBeenGreeted) {
            $this->id = $event->greetingId();
            $this->name = $event->name();

            return;
        }

        throw new RuntimeException(sprintf(
            'Missing event "%s" handler method for aggregate root "%s".',
            $event::class,
            self::class
        ));
    }
}
