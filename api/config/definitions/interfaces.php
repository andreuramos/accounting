<?php

use App\User\Domain\Model\UserRepositoryInterface;
use App\User\Infrastructure\Repository\MysqlUserRepository;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\MessageBusInterface;

return [
    MessageBusInterface::class => DI\get(MessageBus::class),
    UserRepositoryInterface::class => DI\get(MysqlUserRepository::class),
];
