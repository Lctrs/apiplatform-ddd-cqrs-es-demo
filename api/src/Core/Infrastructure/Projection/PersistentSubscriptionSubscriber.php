<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Projection;

use Prooph\EventStore\Async\EventAppearedOnPersistentSubscription;

interface PersistentSubscriptionSubscriber extends EventAppearedOnPersistentSubscription
{
    public static function persistentSubscriptionName(): string;
}
