<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Symfony\DependencyInjection\CompilerPass;

use App\Core\Domain\DomainEvent;
use App\Core\Infrastructure\Persistence\Prooph\DomainEventTransformer;
use ReflectionClass;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class NameToDomainEventMapFactoryCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container) : void
    {
        $services = $container->findTaggedServiceIds('app.domain_event');

        $map = [];
        foreach ($services as $id => $tags) {
            $className = $container->getDefinition($id)->getClass();
            /** @var DomainEvent $reflectionClass */
            $reflectionClass = (new ReflectionClass($className))->newInstanceWithoutConstructor();

            $map[$reflectionClass->name()] = $className;

            $container->removeDefinition($id);
        }

        $container->getDefinition(DomainEventTransformer::class)->replaceArgument(0, $map);
    }
}
