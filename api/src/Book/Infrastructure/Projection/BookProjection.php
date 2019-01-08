<?php

declare(strict_types=1);

namespace App\Book\Infrastructure\Projection;

use App\Book\Domain\Model\Book\Event\BookWasCreated;
use App\Book\Domain\Model\Book\Event\BookWasDeleted;
use App\Book\Infrastructure\Projection\Doctrine\Data\InsertBook;
use App\Book\Infrastructure\Projection\Doctrine\Data\RemoveBook;
use App\Core\Infrastructure\Persistence\Prooph\DomainEventTransformer;
use App\Core\Infrastructure\Persistence\Prooph\EventData;
use Prooph\Bundle\EventStore\Projection\ReadModelProjection;
use Prooph\EventStore\Projection\ReadModel;
use Prooph\EventStore\Projection\ReadModelProjector;

final class BookProjection implements ReadModelProjection
{
    /** @var DomainEventTransformer */
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

                    $readModel->stack('insert', new InsertBook(
                        $event->aggregateId()->__toString(),
                        $event->isbn() === null ? null : $event->isbn()->toString(),
                        $event->title()->toString(),
                        $event->description()->toString(),
                        $event->author()->toString(),
                        $event->publicationDate()->value()
                    ));
                },
                BookWasDeleted::MESSAGE_NAME => function ($data, EventData $eventData) use ($transformer): void {
                    /** @var ReadModel $readModel */
                    $readModel = $this->readModel();

                    /** @var BookWasDeleted $event */
                    $event = $transformer->toDomainEvent($eventData);

                    $readModel->stack('remove', new RemoveBook($event->aggregateId()->__toString()));
                },
            ]);

        return $projector;
    }
}
