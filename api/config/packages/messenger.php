<?php

declare(strict_types=1);

use App\Core\Infrastructure\Symfony\Messenger\EventStoreTransactionMiddleware;
use Prooph\EventStore\TransactionalEventStore;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('framework', [
        'messenger' => [
            'buses' => [
                'command.bus' => [
                    'middleware' => [
                        EventStoreTransactionMiddleware::class,
                    ],
                ],
            ],
        ],
    ]);

    $services = $containerConfigurator->services();
    $services->set(EventStoreTransactionMiddleware::class)
        ->args([
            service(TransactionalEventStore::class),
        ]);
};
