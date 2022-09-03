<?php

require_once './vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();
$routes->add("home", new Route(
    '/',
    ['controller' => "HomeController"]
));

$request = Request::createFromGlobals();

$context = new RequestContext();
$context->fromRequest($request);

$matcher  = new UrlMatcher($routes, $context);
$parameters = $matcher->match($context->getPathInfo());

return new Response("hello world");
