<?php

declare(strict_types=1);

/**
 * @param iterable<TKey, T>         $iterable
 * @param callable(T=, TKey=): NewT $callback
 *
 * @return Generator<TKey, NewT>
 *
 * @template TKey
 * @template T
 * @template NewT
 */
function iterable_map(iterable $iterable, callable $callback): Generator
{
    foreach ($iterable as $key => $value) {
        yield $key => $callback($value, $key);
    }
}
