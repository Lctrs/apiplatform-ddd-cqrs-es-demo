<?php

declare(strict_types=1);

namespace App\Core\Domain;

use IteratorIterator;

final class MapperIterator extends IteratorIterator
{
    private $mapper;

    public function __construct(\Traversable $iterator, callable $mapper)
    {
        parent::__construct($iterator);

        $this->mapper = $mapper;
    }

    public function current()
    {
        return \call_user_func($this->mapper, parent::current());
    }
}
