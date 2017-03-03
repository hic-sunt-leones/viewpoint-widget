<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['view'] = function ($c) {
    $settings = $c->get('settings')['renderer'];

    // inject some template vars that we need everywhere
    $globalTemplateVars = [
        'baseUrl' => $c->get('settings')['baseUrl'],
        'debug'   => $c->get('settings')['displayErrorDetails'],
        'flash'   => $c->get('flash')->getMessages(),
        'router'  => $c->get('router')
    ];
    $view = new \Leones\View($settings['template_path'], $globalTemplateVars);
    $view->setLayout('layout.php');

    return $view;
    //return new Slim\Views\PhpRenderer($settings['template_path'], $globalTemplateVars);
};


// Session
$container['session'] = function ($c) {
    return new \RKA\Session();
};

// Flash messages
$container['flash'] = function ($c) {
    return new Slim\Flash\Messages;
};

// API mapper
$container['volksmapper'] = function ($c) {
    return new \Leones\ViewpointVolksMapper($c->get('settings')['api'], $c->get('logger'));
};


// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));

    return $logger;
};
