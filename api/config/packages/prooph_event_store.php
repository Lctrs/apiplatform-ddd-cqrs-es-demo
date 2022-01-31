<?php

declare(strict_types=1);

use Prooph\EventStore\EventStore;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('prooph_event_store', [
        'stores' => [
            'book' => ['event_store' => EventStore::class],
        ],
    ]);
};
