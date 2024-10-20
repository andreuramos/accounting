<?php

require_once './vendor/autoload.php';

use App\Infrastructure\Controller\CreateExpenseController;
use App\Infrastructure\Controller\CreateIncomeController;
use App\Infrastructure\Controller\EmitInvoiceController;
use App\Infrastructure\Controller\GetExpensesController;
use App\Infrastructure\Controller\GetIncomesController;
use App\Infrastructure\Controller\GetInvoicesController;
use App\Infrastructure\Controller\GetUserController;
use App\Infrastructure\Controller\LoginController;
use App\Infrastructure\Controller\Manual303FormController;
use App\Infrastructure\Controller\ReceiveInvoiceController;
use App\Infrastructure\Controller\RefreshTokenController;
use App\Infrastructure\Controller\RegisterUserController;
use App\Infrastructure\Controller\SetUserTaxDataController;
use App\Infrastructure\Controller\SharedDomainController;
use App\Infrastructure\Controller\StatusCheckController;
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
        'path' => '/form/303',
        'controller' => Manual303FormController::class,
        'method' => 'POST',
    ], [
        'path' => '/income',
        'controller' => GetIncomesController::class,
        'method' => 'GET'
    ], [
        'path' => '/income',
        'controller' => CreateIncomeController::class,
        'method' => 'POST',
    ],[
        'path' => '/invoice',
        'controller' => GetInvoicesController::class,
        'method' => 'GET',
    ], [
        'path' => '/invoice',
        'controller' => EmitInvoiceController::class,
        'method' => 'POST',
    ],[
        'path' => '/invoice/receive',
        'controller' => ReceiveInvoiceController::class,
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
