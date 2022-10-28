<?php

use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\MessageBusInterface;

return [
    MessageBusInterface::class => DI\get(MessageBus::class)
];
