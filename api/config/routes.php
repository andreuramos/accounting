<?php

require_once './vendor/autoload.php';

use App\Shared\Infrastructure\StatusCheckController;
use App\User\Infrastructure\Controller\RegisterUserController;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();

$routes->add('status', new Route(
    '/status',
    ['controller' => StatusCheckController::class]
));
$registerRoute = new Route(
    '/register',
    ['controller' => RegisterUserController::class]
);
$registerRoute->setMethods('POST');
$routes->add('register', $registerRoute);

return $routes;
