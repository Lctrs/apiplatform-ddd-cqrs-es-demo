<?php

declare(strict_types=1);

namespace Core\Infrastructure\Persistence\Prooph\Internal;

final class CallableIterator extends \IteratorIterator
{
    private $callable;

    public function __construct(\Traversable $iterator, callable $callable)
    {
        parent::__construct($iterator);

        $this->callable = $callable;
    }

    public function current()
    {
        return \call_user_func($this->callable, parent::current());
    }
}
