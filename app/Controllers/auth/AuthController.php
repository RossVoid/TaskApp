<?php

namespace App\Controllers\Auth;

use App\Models\User;
use App\Controllers\Controller as Controller;
use Respect\Validation\Validator as Respect;

class AuthController extends Controller
{   
    public function getSignOut($request, $response)
    {
        $this->auth->logout();
        return $response->withRedirect($this->router->pathFor('home')); 
    }

    public function getSignIn($request, $response)
    {
        return $this->view->render($response, 'auth/signin.twig');
    }

    public function postSignIn($request, $response)
    {
        $auth = $this->auth->attempt(
            $request->getParam('email'),
            $request->getParam('password')
        );
        
        if(!$auth){
            $this->flash->addMessage('danger', 'Inncorrect Login Details!');
            return $response->withRedirect($this->router->pathFor('auth.signin'));
        }
        return $response->withRedirect($this->router->pathFor('home'));
    }

    public function getSignUp($request, $response)
    {
        return $this->view->render($response, 'auth/signup.twig');
    }

    public function postSignUp($request, $response)
    {
        $validation = $this->validator->validate($request, [
            'email' => Respect::noWhitespace()->notEmpty()->email()->emailAvailable(),
            'name' => Respect::notEmpty()->alpha(),
            'password' => Respect::noWhitespace()->notEmpty(),
        ]);

        if ($validation->failed()){
            return $response->withRedirect($this->router->pathFor('auth.signup'));
        }

        User::create([
             'email' => $request ->getParam('email'),
             'name' => $request ->getParam('name'),
             'password' => password_hash($request ->getParam('password'), PASSWORD_DEFAULT),
        ]);

        $this->flash->addMessage('success', 'You have been successfully registered');

        $this->auth->attempt(
            $request->getParam('email'),
            $request->getParam('password')
        );

        return $response->withRedirect($this->router->pathFor('home'));
    }
}