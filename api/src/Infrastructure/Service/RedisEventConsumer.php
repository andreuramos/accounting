<?php

namespace App\Infrastructure\Service;

use Predis\Client;

class RedisEventConsumer
{
    public function __invoke()
    {
        $client = new Client([
            'scheme' => 'tcp',
            'host' => 'redis',
            'port' => 6379,
        ]);

        $subscriber = $client->pubSubLoop();
        $subscriber->subscribe('events');

        echo "listening events...";

        try {
            foreach ($subscriber as $message) {
                switch ($message->kind) {
                    case 'subscribe':
                        echo "subscribed\n";
                        break;
                    case 'message':
                        echo "received message {$message->payload}\n";
                        break;
                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
