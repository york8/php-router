<?php
/**
 * User: York <lianyupeng1988@126.com>
 * Date: 2017-08-11 14:53
 */

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use York8\Router\Router;

include __DIR__ . '/../vendor/autoload.php';

// build the router rules
$router = new Router(function (ServerRequestInterface $request, ResponseInterface $response) {
    $response = $response->withStatus(404);
    $body = $response->getBody();
    $body->write('Not Found: ' . $request->getUri()->getPath());
    return $response;
});

// define the route rules
$router->get(
    '/account/:username$',
    function (ServerRequestInterface $request, ResponseInterface $response) {
        $username = $request->getAttribute('username');
        $body = $response->getBody();
        $body->write("Hello, $username!");
        return $response;
    }
);

// initialize the request
$request = ServerRequest::fromGlobals();

// use the 'php://output' stream for the response body directly
$body = fopen('php://output', 'w+');
// initialize the response
$response = new Response(200, [], $body);

// route the request and get the handler
$attrs = [];
$handler = $router->route($request, $attrs);

// set the request attributes with the 'attrs' of route result
foreach ($attrs as $attrName => $attrValue) {
    $request = $request->withAttribute($attrName, $attrValue);
}

// handle the request
$response = $handler($request, $response);
