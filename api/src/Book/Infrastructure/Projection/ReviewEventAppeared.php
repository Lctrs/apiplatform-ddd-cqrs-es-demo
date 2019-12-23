<?php

declare(strict_types=1);

namespace App\Book\Infrastructure\Projection;

use Amp\Promise;
use Amp\Success;
use App\Book\Domain\Model\Review\Event\ReviewWasDeleted;
use App\Book\Domain\Model\Review\Event\ReviewWasPosted;
use App\Book\Infrastructure\Projection\Doctrine\Orm\Entity\Review;
use App\Core\Domain\DomainEventTransformer;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Prooph\EventStore\Async\EventAppearedOnPersistentSubscription;
use Prooph\EventStore\Async\EventStorePersistentSubscription;
use Prooph\EventStore\ResolvedEvent;
use function assert;
use function get_class;

final class ReviewEventAppeared implements EventAppearedOnPersistentSubscription
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
            case ReviewWasDeleted::class:
                assert($event instanceof ReviewWasDeleted);

                $review = $this->entityManager->getReference(Review::class, $event->aggregateId()->toString());

                if ($review === null) {
                    return new Success();
                }

                $this->entityManager->remove($review);
                $this->entityManager->flush();

                break;
            case ReviewWasPosted::class:
                assert($event instanceof ReviewWasPosted);

                $this->entityManager->persist(new Review(
                    $event->aggregateId()->toString(),
                    $event->bookId()->toString(),
                    $event->body() === null ? null : $event->body()->toString(),
                    $event->rating()->toInt(),
                    $event->author() === null ? null : $event->author()->toString()
                ));
                $this->entityManager->flush();

                break;
            default:
                throw new LogicException('Unknown event class "' . get_class($event) . '"');
        }

        return new Success();
    }
}
