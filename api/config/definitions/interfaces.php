<?php

use App\Application\Auth\AuthTokenDecoderInterface;
use App\Application\Auth\AuthTokenGeneratorInterface;
use App\Application\UseCase\RefreshToken\RefreshTokenGeneratorInterface;
use App\Domain\Repository\AccountRepositoryInterface;
use App\Domain\Repository\BusinessRepositoryInterface;
use App\Domain\Repository\ExpenseRepositoryInterface;
use App\Domain\Repository\IncomeRepositoryInterface;
use App\Domain\Repository\InvoiceAggregateRepositoryInterface;
use App\Domain\Repository\TaxDataAggregateRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Service\FileSaverInterface;
use App\Domain\Service\InvoiceRendererInterface;
use App\Infrastructure\Auth\JWTDecoder;
use App\Infrastructure\Auth\JWTGenerator;
use App\Infrastructure\Auth\JWTRefreshTokenGenerator;
use App\Infrastructure\Repository\MysqlAccountRepository;
use App\Infrastructure\Repository\MysqlBusinessRepository;
use App\Infrastructure\Repository\MysqlExpenseRepository;
use App\Infrastructure\Repository\MysqlIncomeRepository;
use App\Infrastructure\Repository\MysqlInvoiceAggregateRepository;
use App\Infrastructure\Repository\MysqlTaxDataAggregateRepository;
use App\Infrastructure\Repository\MysqlUserRepository;
use App\Infrastructure\Service\DompdfInvoiceRenderer;
use App\Infrastructure\Service\LocalFileSaver;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\MessageBusInterface;

return [
    AccountRepositoryInterface::class => DI\get(MysqlAccountRepository::class),
    AuthTokenDecoderInterface::class => DI\get(JWTDecoder::class),
    AuthTokenGeneratorInterface::class => DI\get(JWTGenerator::class),
    BusinessRepositoryInterface::class => DI\get(MysqlBusinessRepository::class),
    ExpenseRepositoryInterface::class => DI\get(MysqlExpenseRepository::class),
    FileSaverInterface::class => DI\get(LocalFileSaver::class),
    IncomeRepositoryInterface::class => DI\get(MysqlIncomeRepository::class),
    InvoiceAggregateRepositoryInterface::class => DI\get(MysqlInvoiceAggregateRepository::class),
    InvoiceRendererInterface::class => DI\get(DompdfInvoiceRenderer::class),
    MessageBusInterface::class => DI\get(MessageBus::class),
    RefreshTokenGeneratorInterface::class => DI\get(JWTRefreshTokenGenerator::class),
    TaxDataAggregateRepositoryInterface::class => DI\get(MysqlTaxDataAggregateRepository::class),
    UserRepositoryInterface::class => DI\get(MysqlUserRepository::class),
];
