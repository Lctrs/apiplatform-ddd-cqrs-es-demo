<?php

declare(strict_types=1);

namespace App\Book\Infrastructure\Projection;

use App\Book\Domain\Model\Review\Event\ReviewWasDeleted;
use App\Book\Domain\Model\Review\Event\ReviewWasPosted;
use App\Book\Infrastructure\Projection\Doctrine\Data\InsertReview;
use App\Book\Infrastructure\Projection\Doctrine\Data\RemoveReview;
use App\Core\Infrastructure\Persistence\Prooph\DomainEventTransformer;
use App\Core\Infrastructure\Persistence\Prooph\EventData;
use Prooph\Bundle\EventStore\Projection\ReadModelProjection;
use Prooph\EventStore\Projection\ReadModel;
use Prooph\EventStore\Projection\ReadModelProjector;

final class ReviewProjection implements ReadModelProjection
{
    /** @var DomainEventTransformer */
    private $transformer;

    public function __construct(DomainEventTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function project(ReadModelProjector $projector) : ReadModelProjector
    {
        $transformer = $this->transformer;

        $projector->fromStream('event_stream')
            ->when([
                ReviewWasPosted::MESSAGE_NAME => function ($data, EventData $eventData) use ($transformer) : void {
                    /** @var ReadModel $readModel */
                    $readModel = $this->readModel();

                    /** @var ReviewWasPosted $event */
                    $event = $transformer->toDomainEvent($eventData);

                    $readModel->stack('insert', new InsertReview(
                        $event->aggregateId()->__toString(),
                        $event->bookId()->__toString(),
                        $event->body() === null ? null : $event->body()->toString(),
                        $event->rating()->toScalar(),
                        $event->author() === null ? null : $event->author()->toString()
                    ));
                },
                ReviewWasDeleted::MESSAGE_NAME => function ($data, EventData $eventData) use ($transformer) : void {
                    /** @var ReadModel $readModel */
                    $readModel = $this->readModel();

                    /** @var ReviewWasDeleted $event */
                    $event = $transformer->toDomainEvent($eventData);

                    $readModel->stack('remove', new RemoveReview($event->aggregateId()->__toString()));
                },
            ]);

        return $projector;
    }
}
