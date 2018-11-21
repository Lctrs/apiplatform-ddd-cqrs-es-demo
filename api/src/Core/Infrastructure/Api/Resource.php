<?php

namespace App\Core\Infrastructure\Api;

use App\Core\Domain\Command;

interface Resource
{
    public function toCommand(): Command;
}
