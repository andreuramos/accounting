<?php

use App\Shared\Application\Service\HasherInterface;
use App\Shared\Infrastructure\Service\Md5Hasher;
use App\User\Application\Auth\AuthTokenGeneratorInterface;
use App\User\Application\Auth\RefreshTokenGeneratorInterface;
use App\User\Domain\Model\UserRepositoryInterface;
use App\User\Infrastructure\Auth\JWTRefreshTokenGenerator;
use App\User\Infrastructure\Repository\MysqlUserRepository;
use App\User\Infrastructure\Auth\JWTGenerator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\MessageBusInterface;

return [
    MessageBusInterface::class => DI\get(MessageBus::class),
    UserRepositoryInterface::class => DI\get(MysqlUserRepository::class),
    HasherInterface::class => DI\get(Md5Hasher::class),
    AuthTokenGeneratorInterface::class => DI\get(JWTGenerator::class),
    RefreshTokenGeneratorInterface::class => DI\get(JWTRefreshTokenGenerator::class),
];
