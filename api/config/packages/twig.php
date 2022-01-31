<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('twig', ['default_path' => '%kernel.project_dir%/templates']);

    if ($containerConfigurator->env() !== 'test') {
        return;
    }

    $containerConfigurator->extension('twig', ['strict_variables' => true]);
};
