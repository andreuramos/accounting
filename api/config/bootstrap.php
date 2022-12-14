<?php

require_once './vendor/autoload.php';

use DI\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

$routes = require('routes.php');
$definitions = require('definitions/interfaces.php');

$request = Request::createFromGlobals();

$context = new RequestContext();
$context->fromRequest($request);
$matcher  = new UrlMatcher($routes, $context);

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions($definitions);
$container = $containerBuilder->build();
try {
    $parameters = $matcher->match($context->getPathInfo());
    $controller = $container->get($parameters['controller']);
    $response = $controller($request);
} catch (RouteNotFoundException $exception) {
    $response = new Response("APP ERROR: Not found " . $exception->getMessage(), Response::HTTP_NOT_FOUND);
} catch (Throwable $exception) {
    $response = new Response("APP ERROR: " . $exception->getMessage(), Response::HTTP_BAD_REQUEST);
}

$response->send();
