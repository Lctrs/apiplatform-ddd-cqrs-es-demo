<?php

declare(strict_types=1);

use App\Book\Domain\Model\Book\BookList;
use App\Book\Domain\Model\Book\Event\BookWasCreated;
use App\Book\Domain\Model\Book\Event\BookWasDeleted;
use App\Book\Domain\Model\Book\Handler\CreateBookHandler;
use App\Book\Domain\Model\Book\Handler\DeleteBookHandler;
use App\Book\Domain\Model\Review\Event\ReviewWasDeleted;
use App\Book\Domain\Model\Review\Event\ReviewWasPosted;
use App\Book\Domain\Model\Review\Handler\DeleteReviewHandler;
use App\Book\Domain\Model\Review\Handler\PostReviewHandler;
use App\Book\Domain\Model\Review\ReviewList;
use App\Book\Infrastructure\Persistence\EventStore\EventStoreBookList;
use App\Book\Infrastructure\Persistence\EventStore\EventStoreReviewList;
use App\Book\Infrastructure\Projection\BookEventAppeared;
use App\Book\Infrastructure\Projection\ReviewEventAppeared;
use App\Core\Domain\EventStore;
use App\Core\Infrastructure\Prooph\EventStore\DomainEventTransformer;
use App\Core\Infrastructure\Prooph\EventStore\EventStoreUsingProoph;
use App\Greeting\Domain\Model\Event\SomeoneHasBeenGreeted;
use Doctrine\ORM\EntityManagerInterface;
use Prooph\EventStore\EventStore as ProophEventStore;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services
        ->set(DomainEventTransformer::class)
        ->args([
            [
                SomeoneHasBeenGreeted::MESSAGE_NAME => SomeoneHasBeenGreeted::class,
            ],
        ]);

    $services
        ->set(EventStoreUsingProoph::class)
        ->args([
            service(ProophEventStore::class),
            service(DomainEventTransformer::class),
        ]);
    $services->alias(EventStore::class, EventStoreUsingProoph::class);

    $services
        ->set(EventStoreBookList::class)
        ->args([
            service(EventStore::class),
        ]);
    $services->alias(BookList::class, EventStoreBookList::class);

    $services
        ->set(CreateBookHandler::class)
        ->args([
            service(BookList::class),
        ])
        ->tag('messenger.message_handler');
    $services
        ->set(DeleteBookHandler::class)
        ->args([
            service(BookList::class),
        ])
        ->tag('messenger.message_handler');

    $services
        ->set(EventStoreReviewList::class)
        ->args([
            service(EventStore::class),
        ]);
    $services->alias(ReviewList::class, EventStoreReviewList::class);

    $services
        ->set(DeleteReviewHandler::class)
        ->args([service(ReviewList::class)])
        ->tag('messenger.message_handler');
    $services
        ->set(PostReviewHandler::class)
        ->args([
            service(BookList::class),
            service(ReviewList::class),
        ])
        ->tag('messenger.message_handler');

    $services
        ->set(BookEventAppeared::class)
        ->args([
            service(EntityManagerInterface::class),
            service(DomainEventTransformer::class),
        ]);
    $services
        ->set(ReviewEventAppeared::class)
        ->args([
            service(EntityManagerInterface::class),
            service(DomainEventTransformer::class),
        ]);
};
