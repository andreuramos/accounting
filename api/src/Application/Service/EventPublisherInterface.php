<?php

namespace App\Application\Service;

use App\Domain\Events\Event;

interface EventPublisherInterface
{
    public function publish(Event $event): void;
}
