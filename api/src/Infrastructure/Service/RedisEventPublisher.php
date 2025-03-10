<?php

namespace App\Infrastructure\Service;

use App\Application\Service\EventPublisherInterface;
use App\Domain\Events\Event;
use Predis\Client;

class RedisEventPublisher implements EventPublisherInterface
{
    private const EVENTS_CHANNEL = 'events';
    
    public function publish(Event $event): void
    {
        $redis = new Client([
            'scheme' => 'tcp',
            'host' => 'redis',
            'port' => 6379,
        ]);

        $redis->publish(self::EVENTS_CHANNEL, json_encode($event->jsonSerialize()));
    }
}