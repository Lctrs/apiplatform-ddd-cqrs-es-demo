<?php

declare(strict_types=1);

use App\Core\Infrastructure\ApiPlatform\DataPersister\CommandBusPersister;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('api_platform', [
        'title' => 'Hello API Platform',
        'version' => '1.0.0',
        'mapping' => [
            'paths' => ['%kernel.project_dir%/src/Book/Infrastructure/ApiPlatform/Resource/'],
        ],
        'patch_formats' => [
            'json' => ['application/merge-patch+json'],
        ],
        'swagger' => [
            'versions' => [3],
        ],
        'defaults' => [
            'stateless' => true,
            'cache_headers' => [
                'vary' => [
                    'Content-Type',
                    'Authorization',
                    'Origin',
                ],
            ],
        ],
    ]);

    $services = $containerConfigurator->services();

    $services->set('book.property_filter')
        ->parent('api_platform.serializer.property_filter')
        ->tag('api_platform.filter');

    $services->set('review.search_filter.bookId')
        ->parent('api_platform.doctrine.orm.search_filter')
        ->tag('api_platform.filter')
        ->args([
            ['bookId' => 'exact'],
        ]);

    $services->set(CommandBusPersister::class)
        ->tag('api_platform.data_persister')
        ->args([
            service('command.bus'),
        ]);
};
