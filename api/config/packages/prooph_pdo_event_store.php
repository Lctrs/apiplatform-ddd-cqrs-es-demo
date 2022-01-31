<?php

declare(strict_types=1);

use Prooph\EventStore\EventStore;
use Prooph\EventStore\Pdo\PersistenceStrategy\PostgresPersistenceStrategy;
use Prooph\EventStore\Pdo\PersistenceStrategy\PostgresSingleStreamStrategy;
use Prooph\EventStore\Pdo\PostgresEventStore;
use Prooph\EventStore\TransactionalEventStore;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(PostgresEventStore::class)
        ->args([
            service('prooph_event_store.message_factory'),
            service(PDO::class),
            service(PostgresPersistenceStrategy::class),
        ]);
    $services->alias(TransactionalEventStore::class, PostgresEventStore::class);
    $services->alias(EventStore::class, PostgresEventStore::class);

    $services->set(PDO::class, PDO::class)
        ->factory([service('database_connection'), 'getWrappedConnection']);

    $services->set(PostgresSingleStreamStrategy::class);
    $services->alias(PostgresPersistenceStrategy::class, PostgresSingleStreamStrategy::class);
};
