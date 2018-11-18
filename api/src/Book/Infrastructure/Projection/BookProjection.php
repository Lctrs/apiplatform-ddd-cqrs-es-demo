<?php

declare(strict_types=1);

namespace App\Book\Infrastructure\Projection;

use App\Book\Domain\Model\Book\Event\BookWasCreated;
use App\Book\Domain\Model\Book\Event\BookWasDeleted;
use Prooph\Bundle\EventStore\Projection\ReadModelProjection;
use Prooph\EventStore\Projection\ReadModel;
use Prooph\EventStore\Projection\ReadModelProjector;

final class BookProjection implements ReadModelProjection
{
    public function project(ReadModelProjector $projector): ReadModelProjector
    {
        $projector->fromStream('event_stream')
            ->when([
                'book-was-created' => function ($data, BookWasCreated $event) {
                    /** @var ReadModel $readModel */
                    $readModel = $this->readModel();

                    $readModel->stack('insert', [
                        'id' => $event->aggregateId()->__toString(),
                        'isbn' => null === $event->isbn() ? null : $event->isbn()->toString(),
                        'title' => $event->title()->toString(),
                        'description' => $event->description()->toString(),
                        'author' => $event->author()->toString(),
                    ]);
                },
                BookWasDeleted::class => function ($data, BookWasDeleted $event) {
                    /** @var ReadModel $readModel */
                    $readModel = $this->readModel();

                    $readModel->stack('remove', $event->aggregateId()->__toString());
                },
            ]);

        return $projector;
    }
}
