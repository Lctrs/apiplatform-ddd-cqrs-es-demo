<?php

namespace App\Greeting\Domain\Model\Command;

use App\Greeting\Domain\Model\Greeting;
use App\Greeting\Domain\Model\GreetingRepository;

final class GreetHandler
{
    private GreetingRepository $repository;

    public function __construct(GreetingRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(Greet $command): void
    {
        $this->repository->save(
            Greeting::greet($command->greetingId(), $command->name())
        );
    }
}
