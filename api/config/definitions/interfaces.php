<?php

use App\Application\Auth\AuthTokenDecoderInterface;
use App\Application\Auth\AuthTokenGeneratorInterface;
use App\Domain\AccountRepositoryInterface;
use App\Domain\BusinessRepositoryInterface;
use App\Domain\ExpenseRepositoryInterface;
use App\Domain\IncomeRepositoryInterface;
use App\Domain\InvoiceLineRepositoryInterface;
use App\Domain\InvoiceRepositoryInterface;
use App\Domain\TaxDataAggregateRepositoryInterface;
use App\Domain\UserRepositoryInterface;
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
use App\Service\HasherInterface;
use App\Service\Md5Hasher;
use App\UseCase\RefreshToken\RefreshTokenGeneratorInterface;
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
