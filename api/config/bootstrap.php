<?php

require_once './vendor/autoload.php';

use App\Domain\Exception\InvalidCredentialsException;
use App\Domain\Exception\MissingMandatoryParameterException;
use App\Infrastructure\ContainerFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

$routes = require('routes.php');

$request = Request::createFromGlobals();

$context = new RequestContext();
$context->fromRequest($request);
$matcher  = new UrlMatcher($routes, $context);

$container = ContainerFactory::create();

try {
    $parameters = $matcher->match($context->getPathInfo());
    $controller = $container->get($parameters['controller']);
    $response = $controller($request);
} catch (RouteNotFoundException | ResourceNotFoundException $exception) {
    $response = new Response("APP ERROR: Not found " . $exception->getMessage(), Response::HTTP_NOT_FOUND);
} catch (InvalidCredentialsException $exception) {
    $response = new Response("APP ERROR: Unauthorized ", Response::HTTP_UNAUTHORIZED);
} catch (MissingMandatoryParameterException $exception) {
    $response = new Response($exception->getMessage(), Response::HTTP_BAD_REQUEST);
} catch (Throwable $exception) {
    $className = get_class($exception);
    $errorText = <<<EOF
    ${className}:
    {$exception->getMessage()}
    
    {$exception->getTraceAsString()}
    EOF;
    $status = $exception->getCode() ?? Response::HTTP_INTERNAL_SERVER_ERROR;
    if (!array_key_exists($status, Response::$statusTexts)) {
        $status = Response::HTTP_INTERNAL_SERVER_ERROR;
    }
    $response = new Response($errorText, $status, [
        'Content-Type' => 'text/plain',
    ]);
}

$response->send();
