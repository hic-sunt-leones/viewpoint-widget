<?php
// Routes


$app->get('/', function ($request, $response, $args) {

    $args['baseUrl'] = $this->get('settings')['baseUrl'];

	if(isset($_SESSION['project'])){
		return $this->renderer->render($response, 'start.php', $args);
	}else{
	    // Render view
	    return $this->renderer->render($response, 'noproject.php', $args);
	}
})->setName('home');





$app->get('/get-project/{uuid}', function ($request, $response, $args) {

	// get project from hetvolk api
	$mapper = new Leones\VolksMapper($this->get('settings')['api']);
    $project = $mapper->getProjectByUUID($args['uuid']);
    
    if(!$project){
    	unset($_SESSION['project']);
    	$uri = $request->getUri()->withPath($this->router->pathFor('home'));
		return $response = $response->withRedirect($uri, 403);
    }else{
    	// yes - set session vars ..
		$_SESSION['project'] = $project;
    	$uri = $request->getUri()->withPath($this->router->pathFor('home'));
		return $response = $response->withRedirect($uri, 302);
    }
    
});





$app->post('/user/login', function ($request, $response, $args) {
    
    // get token from hetvolk api
	$mapper = new Leones\VolksMapper($this->get('settings')['api']);
    $token = $mapper->getToken($_POST['name'],$_POST['password']);
    
    if(!$token){
    	unset($_SESSION['user']);
    	unset($_SESSION['token']);
    	$response->getBody()->write(" Da's vervelend, we hebben geen gebruiker gevonden bij die gegevens... ");

		return $response;
    }else{
    	// yes - set session vars ..
		$_SESSION['token'] = $token;
    	$user = $mapper->getUser($token);

		if(!$user){
			$response->getBody()->write(' Nou zeg, hebben we wel een token maar kunnen we de userinfo er niet bij vinden... ');

			return $response;
		}else{

			$_SESSION['user'] = $user;
    		$uri = $request->getUri()->withPath($this->router->pathFor('home'));
			return $response = $response->withRedirect($uri, 302);
		}
    }
});




$app->get('/user/logout', function ($request, $response, $args) {
    unset($_SESSION['user']);
	$uri = $request->getUri()->withPath($this->router->pathFor('home'));
	return $response = $response->withRedirect($uri, 302);
});




$app->get('/user/stats', function ($request, $response, $args) {

    $response->getBody()->write(' Hello you stats want, eh? ');

	return $response;
});




$app->get('/get-task', function ($request, $response, $args) {

    $args['baseUrl'] = $this->get('settings')['baseUrl'];

    // get new task from hetvolk api
    $mapper = new Leones\VolksMapper($this->get('settings')['api']);
    $args['task'] = $mapper->getTask();

    if(!$args['task']){                     // probably, Expired JWT Token
        unset($_SESSION['token']);
        unset($_SESSION['user']);
        $uri = $this->router->pathFor('home');
        return $response = $response->withRedirect($uri, 403);
    }

    $mapLatLon = explode(",",$args['task']['mapLatLon']);
    $args['task']['mapLonLat'] = trim($mapLatLon[1]) . ", " . trim($mapLatLon[0]);
    
    return $this->renderer->render($response, 'task.php', $args);
})->setName('task')->add($projectExists)->add($userExists);





$app->get('/try-task', function ($request, $response, $args) { 

    // get new task from hetvolk api
    $mapper = new Leones\VolksMapper($this->get('settings')['api']);
    $args['task'] = $mapper->getTask();

    if(!$args['task']){                     // probably, Expired JWT Token
        unset($_SESSION['token']);
        unset($_SESSION['user']);
        $uri = $this->router->pathFor('home');
        return $response = $response->withRedirect($uri, 403);
    }

    $mapLatLon = explode(",",$args['task']['mapLatLon']);
    $args['task']['mapLonLat'] = trim($mapLatLon[1]) . ", " . trim($mapLatLon[0]);
    
    return $this->renderer->render($response, 'task.php', $args);
})->setName('task')->add($projectExists)->add($userExists);





$app->post('/save-task', function ($request, $response, $args) {

	// save task to hetvolk api
	$mapper = new Leones\VolksMapper($this->get('settings')['api']);
    $saved = $mapper->saveTask($_POST);

    if($saved){
    	$_SESSION['randomThanks'] = rand(1,24);
    	$uri = $this->router->pathFor('thanks');
		return $response = $response->withRedirect($uri, 302);
    }else{
    	$response->getBody()->write(' hij doet t niet :-( ');
		return $response;
    }

})->add($projectExists)->add($userExists);




$app->get('/skip-task', function ($request, $response, $args) {

    // tell hetvolk api user has skipped task
    $mapper = new Leones\VolksMapper($this->get('settings')['api']);
    $skipped = $mapper->skipTask($_GET['itemId']);

    if($skipped){
        $_SESSION['randomThanks'] = rand(1,24);
        $uri = $this->router->pathFor('task');
        return $response = $response->withRedirect($uri, 302);
    }else{
        $response->getBody()->write(' hij doet t niet :-( ');
        return $response;
    }
})->add($projectExists)->add($userExists);





$app->get('/thanks', function ($request, $response, $args) {

    $args['baseUrl'] = $this->get('settings')['baseUrl'];
    $args['toNewTask'] = $this->router->pathFor('task');

    return $this->renderer->render($response, 'thanks.php', $args);
})->setName('thanks')->add($projectExists)->add($userExists);












$app->get('/test', function ($request, $response, $args) {
    $response->getBody()->write(' Hello ');

	return $response;
})->setName('ffteste');



