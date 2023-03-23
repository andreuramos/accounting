<?php

require_once './vendor/autoload.php';

use App\Shared\Infrastructure\Controller\SharedDomainController;
use App\Shared\Infrastructure\Controller\StatusCheckController;
use App\User\Infrastructure\Controller\RegisterUserController;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();

$routes->add('status', new Route(
    '/status',
    ['controller' => StatusCheckController::class]
));
$routes->add('domain', new Route(
    '/domain',
    ['controller' => SharedDomainController::class]
));
$registerRoute = new Route(
    '/register',
    ['controller' => RegisterUserController::class]
);
$registerRoute->setMethods('POST');
$routes->add('register', $registerRoute);

return $routes;
