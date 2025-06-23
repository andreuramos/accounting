<?php
require 'vendor/autoload.php';

$consumer = new \App\Infrastructure\Service\RedisEventConsumer();

$consumer();