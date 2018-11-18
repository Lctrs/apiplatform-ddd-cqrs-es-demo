<?php

declare(strict_types=1);

namespace App\Book\Infrastructure\Projection;

use App\Book\Domain\Model\Review\Event\ReviewWasDeleted;
use App\Book\Domain\Model\Review\Event\ReviewWasPosted;
use Prooph\Bundle\EventStore\Projection\ReadModelProjection;
use Prooph\EventStore\Projection\ReadModel;
use Prooph\EventStore\Projection\ReadModelProjector;

final class ReviewProjection implements ReadModelProjection
{
    public function project(ReadModelProjector $projector): ReadModelProjector
    {
        $projector->fromStream('event_stream')
            ->when([
                ReviewWasPosted::class => function ($data, ReviewWasPosted $event) {
                    /** @var ReadModel $readModel */
                    $readModel = $this->readModel();

                    $readModel->stack('insert', [
                        'id' => $event->aggregateId()->__toString(),
                        'bookId' => $event->bookId()->__toString(),
                        'body' => null === $event->body() ? null : $event->body()->toString(),
                        'rating' => $event->rating()->toScalar(),
                        'author' => null === $event->author() ? null : $event->author()->toString(),
                    ]);
                },
                ReviewWasDeleted::class => function ($data, ReviewWasDeleted $event) {
                    /** @var ReadModel $readModel */
                    $readModel = $this->readModel();

                    $readModel->stack('remove', $event->aggregateId()->__toString());
                },
            ]);

        return $projector;
    }
}
