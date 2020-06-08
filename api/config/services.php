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
use App\Core\Infrastructure\Bridge\Prooph\DomainEventTransformer;
use App\Core\Infrastructure\EventStore\Cli\CreatePersistentSubscriptions;
use App\Core\Infrastructure\EventStore\Cli\RunProjections;
use App\Core\Infrastructure\EventStore\Http\HttpEventStore;
use App\Core\Infrastructure\Projection\PersistentSubscriptionSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Http\Message\RequestFactory;
use Prooph\EventStore\Async\EventStoreConnection as AsyncEventStoreConnection;
use Prooph\EventStore\EventStoreConnection;
use Prooph\EventStoreClient\ConnectionSettingsBuilder;
use Prooph\EventStoreClient\EventStoreConnectionFactory as AsyncEventStoreConnectionFactory;
use Prooph\EventStoreHttpClient\ConnectionSettings;
use Prooph\EventStoreHttpClient\ConnectionString;
use Prooph\EventStoreHttpClient\EventStoreConnectionFactory;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpClient\Psr18Client;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use function Symfony\Component\DependencyInjection\Loader\Configurator\inline_service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_locator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services
        ->defaults()
        ->autoconfigure();

    $services
        ->instanceof(PersistentSubscriptionSubscriber::class)
        ->tag('app.persistent_subscription_subscriber');

    $services
        ->set(ConnectionSettingsBuilder::class)
        ->call('useCustomLogger', [service(LoggerInterface::class)])
        ->call('enableVerboseLogging');
    $services
        ->set(AsyncEventStoreConnection::class)
        ->factory([AsyncEventStoreConnectionFactory::class, 'createFromConnectionString'])
        ->args([
            '%env(resolve:EVENT_STORE_ASYNC_DSN)%',
            inline_service(ConnectionSettings::class)
                ->factory([service(ConnectionSettingsBuilder::class), 'build']),
        ])
        ->call('connectAsync');

    $services
        ->set(EventStoreConnection::class)
        ->factory([EventStoreConnectionFactory::class, 'create'])
        ->args([
            inline_service(ConnectionSettings::class)
                ->factory([ConnectionString::class, 'getConnectionSettings'])
                ->args(['%env(resolve:EVENT_STORE_DSN)%']),
            inline_service(Psr18Client::class)
                ->args([
                    service(HttpClientInterface::class),
                    service(ResponseFactoryInterface::class),
                    service(StreamFactoryInterface::class),
                ]),
            service(RequestFactory::class),
        ]);

    $services
        ->set(DomainEventTransformer::class)
        ->arg(0, [
            BookWasCreated::MESSAGE_NAME => BookWasCreated::class,
            BookWasDeleted::MESSAGE_NAME => BookWasDeleted::class,
            ReviewWasDeleted::MESSAGE_NAME => ReviewWasDeleted::class,
            ReviewWasPosted::MESSAGE_NAME => ReviewWasPosted::class,
        ]);

    $services
        ->set(HttpEventStore::class)
        ->args([
            service(EventStoreConnection::class),
            service(DomainEventTransformer::class),
        ]);
    $services->alias(EventStore::class, HttpEventStore::class);

    $services
        ->set(EventStoreBookList::class)
        ->args([
            service(HttpEventStore::class),
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
            service(HttpEventStore::class),
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

    $services
        ->set(CreatePersistentSubscriptions::class)
        ->args([
            service(EventStoreConnection::class),
        ]);
    $services
        ->set(RunProjections::class)
        ->args([
            service(AsyncEventStoreConnection::class),
            tagged_locator('app.persistent_subscription_subscriber', null, 'persistentSubscriptionName'),
        ]);
};
