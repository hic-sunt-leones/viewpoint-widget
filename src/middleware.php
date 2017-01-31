<?php
// Application middleware

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


$app->add(function (Request $request, Response $response, callable $next) {
	$args['baseUrl'] = $this->get('settings')['baseUrl'];
    $renderer = $this->get('renderer');
    $response = $renderer->render($response, 'header.php',$args);
    $response = $next($request, $response);
    $response = $renderer->render($response, 'footer.php',$args);
    
    return $response;
});


$projectExists = function ($request, $response, $next) {
    if(!isset($_SESSION['project'])){
        $uri = $request->getUri()->withPath($this->router->pathFor('home'));
        return $response = $response->withRedirect($uri, 403);
    }
    $response = $next($request, $response);
    return $response;
};

$userExists = function ($request, $response, $next) {
    if(!isset($_SESSION['user'])){
        $uri = $request->getUri()->withPath($this->router->pathFor('home'));
        return $response = $response->withRedirect($uri, 403);
    }
    $response = $next($request, $response);
    return $response;
};