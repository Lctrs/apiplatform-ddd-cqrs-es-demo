<?php

declare(strict_types=1);

namespace App\Core\Domain;

use IteratorIterator;
use Traversable;
use function call_user_func;

final class MapperIterator extends IteratorIterator
{
    /** @var callable */
    private $mapper;

    public function __construct(Traversable $iterator, callable $mapper)
    {
        parent::__construct($iterator);

        $this->mapper = $mapper;
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return call_user_func($this->mapper, parent::current());
    }
}
