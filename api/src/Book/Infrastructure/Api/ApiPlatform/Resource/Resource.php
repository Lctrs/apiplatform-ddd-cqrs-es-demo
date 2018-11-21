<?php

namespace App\Book\Infrastructure\Api\ApiPlatform\Resource;

use App\Core\Domain\Command;

interface Resource
{
    public function toCommand(): Command;
}
