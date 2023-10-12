<?php

use App\Application\Auth\AuthTokenDecoderInterface;
use App\Application\Auth\AuthTokenGeneratorInterface;
use App\Application\Service\HasherInterface;
use App\Application\Service\Md5Hasher;
use App\Application\UseCase\RefreshToken\RefreshTokenGeneratorInterface;
use App\Domain\Repository\AccountRepositoryInterface;
use App\Domain\Repository\BusinessRepositoryInterface;
use App\Domain\Repository\ExpenseRepositoryInterface;
use App\Domain\Repository\IncomeRepositoryInterface;
use App\Domain\Repository\InvoiceLineRepositoryInterface;
use App\Domain\Repository\InvoiceRepositoryInterface;
use App\Domain\Repository\TaxDataAggregateRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Infrastructure\Auth\JWTDecoder;
use App\Infrastructure\Auth\JWTGenerator;
use App\Infrastructure\Auth\JWTRefreshTokenGenerator;
use App\Infrastructure\Repository\MysqlAccountRepository;
use App\Infrastructure\Repository\MysqlBusinessRepository;
use App\Infrastructure\Repository\MysqlExpenseRepository;
use App\Infrastructure\Repository\MysqlIncomeRepository;
use App\Infrastructure\Repository\MysqlInvoiceLineRepository;
use App\Infrastructure\Repository\MysqlInvoiceRepository;
use App\Infrastructure\Repository\MysqlTaxDataAggregateRepository;
use App\Infrastructure\Repository\MysqlUserRepository;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\MessageBusInterface;

return [
    AccountRepositoryInterface::class => DI\get(MysqlAccountRepository::class),
    AuthTokenDecoderInterface::class => DI\get(JWTDecoder::class),
    AuthTokenGeneratorInterface::class => DI\get(JWTGenerator::class),
    BusinessRepositoryInterface::class => DI\get(MysqlBusinessRepository::class),
    ExpenseRepositoryInterface::class => DI\get(MysqlExpenseRepository::class),
    HasherInterface::class => DI\get(Md5Hasher::class),
    IncomeRepositoryInterface::class => DI\get(MysqlIncomeRepository::class),
    InvoiceRepositoryInterface::class => DI\get(MysqlInvoiceRepository::class),
    InvoiceLineRepositoryInterface::class => DI\get(MysqlInvoiceLineRepository::class),
    MessageBusInterface::class => DI\get(MessageBus::class),
    RefreshTokenGeneratorInterface::class => DI\get(JWTRefreshTokenGenerator::class),
    TaxDataAggregateRepositoryInterface::class => DI\get(MysqlTaxDataAggregateRepository::class),
    UserRepositoryInterface::class => DI\get(MysqlUserRepository::class),
];
