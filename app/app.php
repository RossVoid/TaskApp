<?php

use Respect\Validation\Validator as v;

session_start();

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/config/db.php';

$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true,
        'determineRouteBeforeAppMiddleware' => true,
        'addContentLengthHeader' => false,
        'db' => [
        'driver' => 'mysql',
        'host' => 'localhost',
        'database' => 'tasks',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => '',
        ]
    ],
]);

$container = $app->getContainer();

$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['db'] = function($container) use ($capsule){
    return $capsule;
};

$container['auth'] = function ($container) {
    return new \App\Auth\Auth;
};

$container['flash'] = function ($container) {
    return new \Slim\Flash\Messages;
};

$container['view'] = function ($container) {

    $view = new Slim\Views\Twig(__DIR__.'/views',[
        'cache' => false,
    ]);


    $view->addExtension(new \Slim\Views\TwigExtension(

        $container->router,
        $container->request->getUri()

    ));

    $view->getEnvironment()->addGlobal('auth', [
        'check' => $container->auth->check(),
        'user' => $container->auth->user(),
    ]);

    $view->getEnvironment()->addGlobal('flash', $container->flash);

    return $view;

};

$container['validator'] = function ($container) {
    return new \App\Validation\Validator;
};

$container['HomeController'] = function ($container) {
    return new \App\Controllers\HomeController($container);
};

$container['AuthController'] = function ($container) {
    return new \App\Controllers\Auth\AuthController($container);
};

$container['PasswordController'] = function ($container) {
    return new \App\Controllers\Auth\PasswordController($container);
};

$container['csrf'] = function ($c) {
    return new \Slim\Csrf\Guard;
};


$app->add(new \App\Middleware\ValidationErrorsMiddleware($container));
$app->add(new \App\Middleware\PersistentFormMiddleware($container));
$app->add(new \App\Middleware\CsrfViewMiddleware($container));

$app->add($container->get('csrf'));

v::with('App\\Validation\\Rules\\');

//User Routes
require __DIR__ . '/routes/users.php';
require __DIR__ . '/routes/tasks.php';
require __DIR__ . '/routes.php';
