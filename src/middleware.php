<?php
// Application middleware

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


$app->add(new \RKA\SessionMiddleware([
    'name'   => $app->getContainer()->get('settings')['widget']['cookie_name'],
    'secure' => $app->getContainer()->get('settings')['widget']['secure_cookie']
]));

// try to fetch the project from the API if it does not exist already
$requireProject = function (Request $request, $response, $next) {
    $uuid = $request->getAttribute('route')->getArgument('uuid');
    $project = $this->get('session')->get($uuid);

    if (! $project) {
        $mapper = $this->get('volksmapper');
        $project = $mapper->getProjectByUUID($uuid);
        if (! $project) {
            unset($this->get('session')->$uuid);
            if ($mapper->getErrorCode() === 400 || $mapper->getErrorCode() === 404) {
                $this->get('flash')->addMessage('error',
                    'Sorry, het project bestaat niet (meer) of is niet meer live.');
            }

            return $response = $response->withRedirect($this->router->pathFor('home'), 403);
        } else {
            $this->get('session')->set($uuid, $project);
        }
    }


    // check project is of the correct type
    if ($project['type'] !== $this->get('settings')['widget']['type']) {
        unset($this->get('session')->$uuid);
        $this->get('flash')->addMessage('error', 'Sorry, dat project is niet geschikt voor deze widget.');

        return $response = $response->withRedirect($this->router->pathFor('home'), 403);
    }

    // pass project to the view
    $this->get('view')->addAttribute('project', $project);
    $response = $next($request, $response);

    return $response;
};

// for routes that require the user
$userExists = function ($request, $response, $next) {
    if (! $this->get('session')->get('user')) {
        $uri = $request->getUri()->withPath($this->router->pathFor('home'));

        return $response = $response->withRedirect($uri, 403);
    }

    // make user available in the view
    $this->get('view')->addAttribute('user', $this->get('session')->get('user'));
    $response = $next($request, $response);

    return $response;
};