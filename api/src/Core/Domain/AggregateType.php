<?php

declare(strict_types=1);

namespace App\Core\Domain;

use InvalidArgumentException;
use function is_subclass_of;
use function sprintf;

final class AggregateType
{
    /** @var string */
    private $aggregateType;

    /** @var string */
    private $aggregateRootClass;

    public function __construct(string $aggregateType, string $aggregateRootClass)
    {
        if (! is_subclass_of($aggregateRootClass, AggregateRoot::class, true)) {
            throw new InvalidArgumentException(sprintf(
                '"%s" is not a valid aggregate root class. It must extends "%s".',
                $aggregateRootClass,
                AggregateRoot::class
            ));
        }

        $this->aggregateType      = $aggregateType;
        $this->aggregateRootClass = $aggregateRootClass;
    }

    public function aggregateType() : string
    {
        return $this->aggregateType;
    }

    public function aggregateRootClass() : string
    {
        return $this->aggregateRootClass;
    }
}
