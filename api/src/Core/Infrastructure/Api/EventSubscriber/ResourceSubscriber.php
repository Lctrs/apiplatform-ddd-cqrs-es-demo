<?php

namespace App\Core\Infrastructure\Api\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class ResourceSubscriber implements EventSubscriberInterface
{
    $messageBus;
    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        // TODO: Implement getSubscribedEvents() method.
    }
}
