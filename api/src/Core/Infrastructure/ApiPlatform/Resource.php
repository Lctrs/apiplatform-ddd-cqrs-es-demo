<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\ApiPlatform;

use App\Core\Domain\Command;

interface Resource
{
    public function toCommand() : Command;
}
