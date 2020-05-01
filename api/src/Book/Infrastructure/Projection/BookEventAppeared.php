<?php

declare(strict_types=1);

namespace App\Book\Infrastructure\Projection;

use Amp\Promise;
use Amp\Success;
use App\Book\Domain\Model\Book\Event\BookWasCreated;
use App\Book\Domain\Model\Book\Event\BookWasDeleted;
use App\Book\Infrastructure\Projection\Doctrine\Orm\Entity\Book;
use App\Core\Infrastructure\Bridge\Prooph\DomainEventTransformer;
use App\Core\Infrastructure\Projection\PersistentSubscriptionSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Prooph\EventStore\Async\EventStorePersistentSubscription;
use Prooph\EventStore\ResolvedEvent;
use function get_class;

final class BookEventAppeared implements PersistentSubscriptionSubscriber
{
    private EntityManagerInterface $entityManager;
    private DomainEventTransformer $transformer;

    public function __construct(EntityManagerInterface $entityManager, DomainEventTransformer $transformer)
    {
        $this->entityManager = $entityManager;
        $this->transformer   = $transformer;
    }

    public static function persistentSubscriptionName() : string
    {
        return '$ce-book';
    }

    /**
     * @return Promise<null>
     */
    public function __invoke(
        EventStorePersistentSubscription $subscription,
        ResolvedEvent $resolvedEvent,
        ?int $retryCount = null
    ) : Promise {
        $event = $this->transformer->toDomainEvent($resolvedEvent);

        switch (get_class($event)) {
            case BookWasCreated::class:
                $isbn = $event->isbn();

                $this->entityManager->persist(new Book(
                    $event->aggregateId()->toString(),
                    $isbn === null ? null : $isbn->toString(),
                    $event->title()->toString(),
                    $event->description()->toString(),
                    $event->author()->toString(),
                    $event->publicationDate()->toDateTime()
                ));
                $this->entityManager->flush();

                break;
            case BookWasDeleted::class:
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
