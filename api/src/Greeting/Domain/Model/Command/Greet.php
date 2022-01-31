<?php

declare(strict_types=1);

namespace App\Greeting\Domain\Model\Command;

use App\Core\Domain\Command;
use App\Greeting\Domain\Model\GreetingId;
use App\Greeting\Domain\Model\Name;

final class Greet implements Command
{
    private GreetingId $greetingId;
    private Name $name;

    public function __construct(GreetingId $greetingId, Name $name)
    {
        $this->greetingId = $greetingId;
        $this->name       = $name;
    }

    public function greetingId(): GreetingId
    {
        return $this->greetingId;
    }

    public function name(): Name
    {
        return $this->name;
    }
}
