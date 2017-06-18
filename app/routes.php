<?php
use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;

$app->group('', function(){
    
    $this->get('/', 'TasksController:getTasks')->setName('tasks');
    $this->post('/', 'TasksController:postTask');
    $this->get('/auth/signout', 'AuthController:getSignOut')->setName('auth.signout');
    $this->get('/auth/password/change', 'PasswordController:getChangePassword')->setName('auth.password.change');
    $this->post('/auth/password/change', 'PasswordController:postChangePassword');
    $this->get('/task/{taskID}', 'TasksController:getTask')->setName('task.details');
    $this->post('/task/{taskID}', 'TasksController:updateTask')->setName('task.update');
    $this->get('/task/delete/{taskID}', 'TasksController:getTaskDelete')->setName('task.confirmDelete');
    $this->post('/task/delete/{taskID}', 'TasksController:deleteTask')->setName('task.delete');
})->add(new AuthMiddleware($container));

$app->group('', function(){
    $this->get('/auth/signup', 'AuthController:getSignUp')->setName('auth.signup');
    $this->post('/auth/signup', 'AuthController:postSignUp');
    $this->get('/auth/signin', 'AuthController:getSignIn')->setName('auth.signin');
    $this->post('/auth/signin', 'AuthController:postSignIn');


})->add(new GuestMiddleware($container));

$app->get('/pull', function ($request, $response, $args) {
    $output = shell_exec('git pull');
    echo "<pre>$output</pre>";
});