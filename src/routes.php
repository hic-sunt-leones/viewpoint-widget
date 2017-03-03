<?php

// home - no project
$app->get('/', function ($request, $response) {
    return $this->view->render($response, 'noproject.php');
})->setName('home');


// start
$app->get('/start/{uuid}', function ($request, $response, $args) {
    $this->view->addAttribute('user', $this->get('session')->get('user'));
    return $this->view->render($response, 'start.php');
})->setName('start')
    ->add($requireProject);


// get token from hetvolk api
$app->post('/user/login', function ($request, $response, $args) {

    /** @var \Leones\VolksMapper $mapper */
    $mapper = $this->get('volksmapper');

    $postData = $request->getParsedBody();
    $uuid = filter_var($postData['uuid'], FILTER_SANITIZE_STRING);
    $name = filter_var($postData['name'], FILTER_SANITIZE_STRING);
    $pass = filter_var($postData['password'], FILTER_SANITIZE_STRING);

    //$token = $mapper->getToken($_POST['name'], $_POST['password']);
    $token = $mapper->getToken($name, $pass);
    $uri = $request->getUri()->withPath($this->router->pathFor('home'));
    if (! $token) {
        unset($this->get('session')->user);
        unset($this->get('session')->token);

        $this->get('flash')->addMessage('error',
            'Dat is vervelend, we hebben geen gebruiker gevonden bij die gegevens...');

        return $response->withStatus(302)->withHeader('Location', $uri);
    } else {
        // yes - set session vars ..
        $this->get('session')->token = $token;
        $user = $mapper->getUser($token);

        if (! $user) {
            $this->get('flash')->addMessage('error',
                'Nou zeg, hebben we wel een token maar kunnen we de userinfo er niet bij vinden...');

            return $response->withStatus(302)->withHeader('Location', $uri);
        } else {
            $this->get('session')->set('user', $user);

            return $response->withStatus(302)->withHeader('Location', $request->getUri()->withPath($this->router->pathFor('start', ['uuid' => $uuid])));
        }
    }
})->setName('login');


$app->get('/user/logout/{uuid}', function ($request, $response, $args) {
    $this->get('session')->destroy();
    $uri = $request->getUri()->withPath($this->router->pathFor('start', ['uuid' => $args['uuid']]));

    $this->get('flash')->addMessage('notice', 'Bedankt voor het meedoen en hopelijk tot snel!');

    return $response->withStatus(302)->withHeader('Location', $uri);
})->setName('logout');

/* later....
$app->get('/user/stats', function ($request, $response, $args) {
    $response->getBody()->write(' Hello you stats want, eh? ');
    return $response;
});
*/



// get new task from hetvolk api (NOT saving this in the session anymore)
$app->get('/get-task/{uuid}', function ($request, $response, $args) {
    $project = $this->get('session')->get($args['uuid']);

    /** @var \Leones\VolksMapper $mapper */
    $mapper = $this->get('volksmapper');
    $task = $mapper->getTask($this->get('session')->get('token'), $project['id']);

    if (! $task) {
        /** @var \Slim\Flash\Messages $flash */
        $flash = $this->get('flash');

        if ($mapper->getErrorCode() === 404) {
            $flash->addMessage('error', 'Sorry, het project bestaat niet (meer) of staat niet langer live.');
        }
        if ($mapper->getErrorCode() === 204) {
            $flash->addMessage('error', 'Sorry, maar de taken zijn op!');
        }
        if ($mapper->getErrorCode() === 401 || $mapper->getErrorCode() === 403) {
            $flash->addMessage('error', 'Sorry, maar je hebt geen toegang tot dat project. ');
        }

        unset($this->get('session')->token);
        unset($this->get('session')->user);
        $uri = $this->router->pathFor('home');

        return $response = $response->withRedirect($uri, 403);
    }

    $task = $mapper->getMapLatLon($task);

    return $this->view->render($response, 'task.php', ['task' => $task]);
})->setName('get-task')->add($requireProject)->add($userExists);



// get new task from hetvolk api
$app->get('/try-task/{uuid}', function ($request, $response, $args) {

    /** @var \Leones\VolksMapper $mapper */
    $mapper = $this->get('volksmapper');
    $args['task'] = $mapper->demoTask($args['uuid']);

    $args['task'] = $mapper->getMapLatLon($args['task']);

    return $this->view->render($response, 'demo.php', $args);
})->setName('try-task')->add($requireProject)->add($userExists);



// save task to hetvolk api
$app->post('/save-task/{uuid}', function ($request, $response, $args) {
    $project = $this->get('session')->get($args['uuid']);

    $mapper = $this->get('volksmapper');
    $saved = $mapper->saveTask($_POST, $project['id'], $this->get('session')->get('token'));

    if ($saved) {
        $this->get('session')->randomThanks = mt_rand(1, 24);
        $uri = $this->router->pathFor('thanks', ['uuid' => $project['uuid']]);
        return $response->withRedirect($uri, 302);
    } else {
        $this->get('flash')->addMessage('error', ' nou moe ... het opslaan lijkt niet gelukt :-( ');
        $uri = $this->router->pathFor('start', ['uuid' => $project['uuid']]);
        return $response->withRedirect($uri, 302);
    }

})->setName('save-task')->add($requireProject)->add($userExists);



// tell hetvolk api user has skipped task
$app->get('/skip-task/{uuid}', function ($request, $response, $args) {
    $project = $this->get('session')->get($args['uuid']);

    $itemId = $_GET['itemId'];
    if (!ctype_digit($itemId)) {
        $response->getBody()->write('Sorry, die hebben we niet.');
        return $response;
    }

    $mapper = $this->get('volksmapper');
    $skipped = $mapper->skipTask($_GET['itemId'], $project['id'], $this->get('session')->get('token'));

    if ($skipped) {
        $uri = $this->router->pathFor('get-task', ['uuid' => $project['uuid']]);

        return $response = $response->withRedirect($uri, 302);
    } else {
        $this->get('logger')->addError("API error: could not skip task with itemId ({$itemId}) for user " . $this->get('session')->get('user')['id']);
        $this->get('flash')->addMessage('error', ' nou moe ... te snel geklikt? :-( ');
        $uri = $this->router->pathFor('start', ['uuid' => $project['uuid']]);
        return $response->withRedirect($uri, 302);
    }
})->setName('skip-task')->add($requireProject)->add($userExists);


$app->get('/thanks/{uuid}', function ($request, $response, $args) {
    return $this->view->render($response, 'thanks.php', [
        'randomThanks' => $this->get('session')->get('randomThanks')
    ]);

})->setName('thanks')->add($requireProject)->add($userExists);


$app->get('/manual', function ($request, $response, $args) {

    return $this->view->render($response, 'manual.php', $args);
})->setName('manual')->add($requireProject);

