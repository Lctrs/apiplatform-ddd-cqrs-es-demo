<?php

declare(strict_types=1);

namespace Core\Infrastructure\Fixtures\Instantiator;

use Nelmio\Alice\Definition\Object\CompleteObject;
use Nelmio\Alice\Definition\Object\SimpleObject;
use Nelmio\Alice\Definition\Property;
use Nelmio\Alice\Definition\ValueInterface;
use Nelmio\Alice\FixtureInterface;
use Nelmio\Alice\Generator\GenerationContext;
use Nelmio\Alice\Generator\Instantiator\ChainableInstantiatorInterface;
use Nelmio\Alice\Generator\ResolvedFixtureSet;
use Nelmio\Alice\Generator\ValueResolverAwareInterface;
use Nelmio\Alice\Generator\ValueResolverInterface;
use Nelmio\Alice\IsAServiceTrait;
use Nelmio\Alice\Throwable\Exception\Generator\Instantiator\InstantiationException;
use Nelmio\Alice\Throwable\Exception\Generator\Resolver\ResolverNotFoundExceptionFactory;
use Nelmio\Alice\Throwable\Exception\Generator\Resolver\UnresolvableValueDuringGenerationExceptionFactory;
use Nelmio\Alice\Throwable\ResolutionThrowable;
use Prooph\Common\Messaging\DomainMessage;
use Prooph\Common\Messaging\MessageFactory;

final class MessageInstantiator implements ChainableInstantiatorInterface, ValueResolverAwareInterface
{
    use IsAServiceTrait;

    private $messageFactory;
    private $resolver;

    public function __construct(MessageFactory $messageFactory, ValueResolverInterface $resolver = null)
    {
        $this->messageFactory = $messageFactory;
        $this->resolver = $resolver;
    }

    public function withValueResolver(ValueResolverInterface $resolver): self
    {
        return new self($this->messageFactory, $resolver);
    }

    public function canInstantiate(FixtureInterface $fixture): bool
    {
        return is_a($fixture->getClassName(), DomainMessage::class, true);
    }

    /**
     * {@inheritdoc}
     *
     * @throws InstantiationException
     */
    public function instantiate(
        FixtureInterface $fixture,
        ResolvedFixtureSet $fixtureSet,
        GenerationContext $context
    ): ResolvedFixtureSet {
        if (null === $this->resolver) {
            throw ResolverNotFoundExceptionFactory::createUnexpectedCall(__METHOD__);
        }

        $properties = $fixture->getSpecs()->getProperties();

        $scope = $fixtureSet->getParameters()->toArray();
        $scope['_instances'] = $fixtureSet->getObjects()->toArray();
        $payload = [];

        foreach ($properties as $property) {
            /** @var Property $property */
            $propertyValue = $property->getValue();
            if ($propertyValue instanceof ValueInterface) {
                try {
                    $result = $this->resolver->resolve($propertyValue, $fixture, $fixtureSet, $scope, $context);
                } catch (ResolutionThrowable $throwable) {
                    throw UnresolvableValueDuringGenerationExceptionFactory::createFromResolutionThrowable($throwable);
                }

                [$propertyValue, $fixtureSet] = [$result->getValue(), $result->getSet()];
                $property = $property->withValue($propertyValue);
            }

            $scope[$property->getName()] = $propertyValue;

            if (null !== $propertyValue && !is_scalar($propertyValue)) {
                $propertyValue = (string) $propertyValue;
            }

            $payload[$property->getName()] = $propertyValue;
        }

        $objects = $fixtureSet->getObjects()->with(
            new CompleteObject(
                new SimpleObject(
                    $fixture->getId(),
                    $this->messageFactory->createMessageFromArray($fixture->getClassName(), ['payload' => $payload])
                )
            )
        );

        return $fixtureSet->withObjects($objects);
    }
}
