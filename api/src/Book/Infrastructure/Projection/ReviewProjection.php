<?php

declare(strict_types=1);

namespace Book\Infrastructure\Projection;

use Book\Domain\Model\Review\Event\ReviewWasPosted;
use Core\Infrastructure\EventSourcing\Prooph\Stream\Streams;
use Prooph\Bundle\EventStore\Projection\ReadModelProjection;
use Prooph\EventStore\Projection\ReadModel;
use Prooph\EventStore\Projection\ReadModelProjector;

final class ReviewProjection implements ReadModelProjection
{
    public function project(ReadModelProjector $projector): ReadModelProjector
    {
        $projector->fromStream(Streams::REVIEW)
            ->when([
                ReviewWasPosted::class => function ($data, ReviewWasPosted $event) {
                    /** @var ReadModel $readModel */
                    $readModel = $this->readModel();

                    $readModel->stack('insert', [
                        'id' => $event->id()->toString(),
                        'body' => null === $event->body() ? null : $event->body()->toString(),
                        'rating' => $event->rating()->toScalar(),
                        'author' => $event->author()->toString(),
                    ]);
                },
            ]);

        return $projector;
    }
}
