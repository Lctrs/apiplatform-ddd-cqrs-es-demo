<?php

declare(strict_types=1);

namespace App\Greeting\Domain\Model;

interface GreetingRepository
{
    public function save(Greeting $greeting): void;

    public function get(GreetingId $id): ?Greeting;
}
