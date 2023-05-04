<?php

use App\Shared\Application\Service\HasherInterface;
use App\Shared\Infrastructure\Service\Md5Hasher;
use App\Transaction\Domain\Model\ExpenseRepositoryInterface;
use App\Transaction\Domain\Model\IncomeRepositoryInterface;
use App\Transaction\Infrastructure\Repository\MysqlExpenseRepository;
use App\Transaction\Infrastructure\Repository\MysqlIncomeRepository;
use App\User\Application\Auth\AuthTokenDecoderInterface;
use App\User\Application\Auth\AuthTokenGeneratorInterface;
use App\User\Application\Auth\RefreshTokenGeneratorInterface;
use App\User\Domain\Model\UserRepositoryInterface;
use App\User\Infrastructure\Auth\JWTDecoder;
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
    AuthTokenDecoderInterface::class => DI\get(JWTDecoder::class),
    ExpenseRepositoryInterface::class => DI\get(MysqlExpenseRepository::class),
    IncomeRepositoryInterface::class => DI\get(MysqlIncomeRepository::class)
];
