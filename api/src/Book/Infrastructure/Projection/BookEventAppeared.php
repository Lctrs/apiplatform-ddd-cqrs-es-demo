<?php

declare(strict_types=1);

namespace App\Book\Infrastructure\Projection;

use Amp\Promise;
use Amp\Success;
use App\Book\Domain\Model\Book\Event\BookWasCreated;
use App\Book\Domain\Model\Book\Event\BookWasDeleted;
use App\Book\Infrastructure\Projection\Doctrine\Orm\Entity\Book;
use App\Core\Domain\DomainEventTransformer;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Prooph\EventStore\Async\EventAppearedOnPersistentSubscription;
use Prooph\EventStore\Async\EventStorePersistentSubscription;
use Prooph\EventStore\ResolvedEvent;
use function assert;
use function get_class;

final class BookEventAppeared implements EventAppearedOnPersistentSubscription
{
    /** @var EntityManagerInterface */
    private $entityManager;
    /** @var DomainEventTransformer */
    private $transformer;

    public function __construct(EntityManagerInterface $entityManager, DomainEventTransformer $transformer)
    {
        $this->entityManager = $entityManager;
        $this->transformer   = $transformer;
    }

    public function __invoke(
        EventStorePersistentSubscription $subscription,
        ResolvedEvent $resolvedEvent,
        ?int $retryCount = null
    ) : Promise {
        $event = $this->transformer->toDomainEvent($resolvedEvent);

        switch (get_class($event)) {
            case BookWasCreated::class:
                assert($event instanceof BookWasCreated);

                $this->entityManager->persist(new Book(
                    $event->aggregateId()->toString(),
                    $event->isbn() === null ? null : $event->isbn()->toString(),
                    $event->title()->toString(),
                    $event->description()->toString(),
                    $event->author()->toString(),
                    $event->publicationDate()->toDateTime()
                ));
                $this->entityManager->flush();

                break;
            case BookWasDeleted::class:
                assert($event instanceof BookWasDeleted);

                $book = $this->entityManager->getReference(Book::class, $event->aggregateId()->toString());

                if ($book === null) {
                    return new Success();
                }

                $this->entityManager->remove($book);
                $this->entityManager->flush();

                break;
            default:
                throw new LogicException('Unknown event class "' . get_class($event) . '"');
        }

        return new Success();
    }
}
