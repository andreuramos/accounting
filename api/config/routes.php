<?php

require_once './vendor/autoload.php';

use App\Shared\Infrastructure\Controller\SharedDomainController;
use App\Shared\Infrastructure\Controller\StatusCheckController;
use App\User\Infrastructure\Controller\GetUserController;
use App\User\Infrastructure\Controller\LoginController;
use App\User\Infrastructure\Controller\RefreshTokenController;
use App\User\Infrastructure\Controller\RegisterUserController;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();

$routes->add('domain', new Route(
    '/domain',
    ['controller' => SharedDomainController::class]
));
$routes->add('login', new Route(
    '/login',
    ['controller' => LoginController::class]
));
$routes->add('refresh', (new Route(
    '/refresh',
    ['controller' => RefreshTokenController::class]
))->setMethods('POST'));
$registerRoute = new Route(
    '/user',
    ['controller' => RegisterUserController::class]
);
$registerRoute->setMethods('POST');
$routes->add('register', $registerRoute);
$routes->add('status', new Route(
    '/status',
    ['controller' => StatusCheckController::class]
));
$routes->add('get-user', new Route(
    '/user',
    ['controller' => GetUserController::class]
));

return $routes;
