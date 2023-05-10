<?php

require_once './vendor/autoload.php';

use App\Shared\Infrastructure\Controller\SharedDomainController;
use App\Shared\Infrastructure\Controller\StatusCheckController;
use App\Tax\Infrastructure\Controller\SetTaxDataController;
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

$routes = new RouteCollection();

$routes->add('domain', new Route(
    '/domain', ['controller' => SharedDomainController::class]
));
$routes->add('get_expenses', (new Route(
    '/expense', ['controller' => GetExpensesController::class]
))->setMethods('GET'));
$routes->add('create_expense', (new Route(
    '/expense', ['controller' => CreateExpenseController::class]
))->setMethods('POST'));
$routes->add('get_incomes', (new Route(
    '/income', ['controller' => GetIncomesController::class]
))->setMethods('GET'));
$routes->add('create_income', (new Route(
    '/income', ['controller' => CreateIncomeController::class]
))->setMethods('POST'));
$routes->add('login', new Route(
    '/login', ['controller' => LoginController::class]
));
$routes->add('refresh', (new Route(
    '/refresh', ['controller' => RefreshTokenController::class]
))->setMethods('POST'));
$routes->add('register', (new Route(
    '/user', ['controller' => RegisterUserController::class]
))->setMethods('POST'));
$routes->add('status', new Route(
    '/status', ['controller' => StatusCheckController::class]
));
$routes->add('POST_tax_data', (new Route(
    '/tax/data', ['controller' => SetTaxDataController::class]
))->setMethods('POST'));
$routes->add('get-user', new Route(
    '/user', ['controller' => GetUserController::class]
));

return $routes;
