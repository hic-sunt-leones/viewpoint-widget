<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];

    // construct some template vars that we need everywhere
    $globalTemplateVars = [
        'baseUrl' => $c->get('settings')['baseUrl'],
        'debug' => $c->get('settings')['displayErrorDetails'],
    ];
    return new Slim\Views\PhpRenderer($settings['template_path'], $globalTemplateVars);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};
