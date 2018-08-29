<?php

declare(strict_types=1);

namespace Core\Infrastructure\Container;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class RemoveDoctrineOrmPersisterCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('api_platform.doctrine.orm.data_persister')) {
            return;
        }

        $container->removeDefinition('api_platform.doctrine.orm.data_persister');
    }
}
