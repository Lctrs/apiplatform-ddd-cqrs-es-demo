<?php

declare(strict_types=1);

namespace App\Book\Infrastructure\Projection;

use App\Book\Domain\Model\Book\Event\BookWasCreated;
use App\Book\Domain\Model\Book\Event\BookWasDeleted;
use App\Core\Infrastructure\Persistence\Prooph\DomainEventTransformer;
use App\Core\Infrastructure\Persistence\Prooph\EventData;
use Prooph\Bundle\EventStore\Projection\ReadModelProjection;
use Prooph\EventStore\Projection\ReadModel;
use Prooph\EventStore\Projection\ReadModelProjector;

final class BookProjection implements ReadModelProjection
{
    private $transformer;

    public function __construct(DomainEventTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function project(ReadModelProjector $projector): ReadModelProjector
    {
        $transformer = $this->transformer;

        $projector->fromStream('event_stream')
            ->when([
                BookWasCreated::MESSAGE_NAME => function ($data, EventData $eventData) use ($transformer): void {
                    /** @var ReadModel $readModel */
                    $readModel = $this->readModel();

                    /** @var BookWasCreated $event */
                    $event = $transformer->toDomainEvent($eventData);

                    $readModel->stack('insert', [
                        'id' => $event->aggregateId()->__toString(),
                        'isbn' => null === $event->isbn() ? null : $event->isbn()->toString(),
                        'title' => $event->title()->toString(),
                        'description' => $event->description()->toString(),
                        'author' => $event->author()->toString(),
                    ]);
                },
                BookWasDeleted::MESSAGE_NAME => function ($data, EventData $eventData) use ($transformer): void {
                    /** @var ReadModel $readModel */
                    $readModel = $this->readModel();

                    /** @var BookWasDeleted $event */
                    $event = $transformer->toDomainEvent($eventData);

                    $readModel->stack('remove', $event->aggregateId()->__toString());
                },
            ]);

        return $projector;
    }
}
