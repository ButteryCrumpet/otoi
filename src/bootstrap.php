<?php
require dirname(__FILE__) . "/../vendor/autoload.php";

$dispatcher = \FastRoute\simpleDispatcher(function (\FastRoute\RouteCollector $r) {
     $r->addRoute('GET', '/contact/', function() {
         $otoi = new \Otoi\Otoi();
         return $otoi->input()->getBody();
     });
    $r->addRoute('POST', '/contact/confirm', function() {
        $otoi = new \Otoi\Otoi();
        $response = $otoi->confirm();
        if ($response->hasHeader('Location')) {
            $header = 'Location: ' . $response->getHeaderLine('Location');
            header($header, true, 303);
            die();
        }
        return $response->getBody();
    });
    $r->addRoute('POST', '/contact/mail', function() {
        $otoi = new \Otoi\Otoi();
        $response = $otoi->mail();
        if ($response->hasHeader('Location')) {
            $header = 'Location: ' . $response->getHeaderLine('Location');
            header($header, true, 303);
            die();
        }
        return $response->getBody();
    });
});


$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
$form = "";
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        header('Location: /contact', true, 301);
        die();
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        header('Location: /contact', true, 301);
        die();
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        $form = $handler($vars);
        break;
}