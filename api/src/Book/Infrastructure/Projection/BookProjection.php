<?php

declare(strict_types=1);

namespace Book\Infrastructure\Projection;

use Book\Domain\Model\Book\Event\BookWasCreated;
use Core\Infrastructure\EventSourcing\Prooph\Stream\Streams;
use Prooph\Bundle\EventStore\Projection\ReadModelProjection;
use Prooph\EventStore\Projection\ReadModel;
use Prooph\EventStore\Projection\ReadModelProjector;

final class BookProjection implements ReadModelProjection
{
    public function project(ReadModelProjector $projector): ReadModelProjector
    {
        $projector->fromStream(Streams::BOOK)
            ->when([
                BookWasCreated::class => function ($data, BookWasCreated $event) {
                    /** @var ReadModel $readModel */
                    $readModel = $this->readModel();

                    $readModel->stack('insert', [
                        'id' => $event->id()->toString(),
                        'isbn' => $event->isbn()->toString(),
                        'title' => $event->title()->toString(),
                        'description' => $event->description()->toString(),
                        'author' => $event->author()->toString(),
                    ]);
                },
            ]);

        return $projector;
    }
}
