<?php

require_once './vendor/autoload.php';

use App\Business\Infrastructure\Controller\SetUserTaxDataController;
use App\Invoice\Infrastructure\Controller\EmitInvoiceController;
use App\Shared\Infrastructure\Controller\SharedDomainController;
use App\Shared\Infrastructure\Controller\StatusCheckController;
use App\Transaction\Infrastructure\Controller\CreateExpenseController;
use App\Transaction\Infrastructure\Controller\CreateIncomeController;
use App\Transaction\Infrastructure\Controller\GetExpensesController;
use App\Transaction\Infrastructure\Controller\GetIncomesController;
use App\User\Infrastructure\Controller\GetUserController;
use App\User\Infrastructure\Controller\LoginController;
use App\User\Infrastructure\Controller\RefreshTokenController;
use App\User\Infrastructure\Controller\RegisterUserController;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = [
    [
        'path' => '/domain',
        'controller' => SharedDomainController::class,
        'method' => 'GET'
    ], [
        'path' => '/expense',
        'controller' => GetExpensesController::class,
        'method' => 'GET'
    ], [
        'path' => '/expense',
        'controller' => CreateExpenseController::class,
        'method' => 'POST'
    ], [
        'path' => '/income',
        'controller' => GetIncomesController::class,
        'method' => 'GET'
    ], [
        'path' => '/income',
        'controller' => CreateIncomeController::class,
        'method' => 'POST',
    ], [
        'path' => '/invoice',
        'controller' => EmitInvoiceController::class,
        'method' => 'POST',
    ], [
        'path' => '/login',
        'controller' => LoginController::class,
        'method' => 'POST',
    ], [
        'path' => '/refresh',
        'controller' => RefreshTokenController::class,
        'method' => 'POST',
    ], [
        'path' => '/user',
        'controller' => RegisterUserController::class,
        'method' => 'POST',
    ], [
        'path' => '/user',
        'controller' => GetUserController::class,
        'method' => 'GET',
    ], [
        'path' => '/status',
        'controller' => StatusCheckController::class,
        'method' => 'GET'
    ], [
        'path' => '/user/tax_data',
        'controller' => SetUserTaxDataController::class,
        'method' => 'POST'
    ],
];

$routesCollection = new RouteCollection();

foreach ($routes as $route) {
    $name = $route['path'] . '_' . $route['method'];
    $routesCollection->add($name, (new Route(
        $route['path'], ['controller' => $route['controller']]
    ))->setMethods($route['method']));
}

return $routesCollection;
