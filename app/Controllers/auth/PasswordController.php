<?php

namespace App\Controllers\Auth;

use App\Models\User;
use App\Controllers\Controller as Controller;
use Respect\Validation\Validator as Respect;

class PasswordController extends Controller
{   
    public function getChangePassword($request, $response)
    {
        return $this->view->render($response, 'auth/password/change.twig');
    }

    public function postChangePassword($request, $response)
    {
        $validation = $this->validator->validate($request, [
            'password_old' => Respect::noWhitespace()->notEmpty()->matchesPassword($this->auth->user()->password),
            'password' => Respect::noWhitespace()->notEmpty(),
        ]);

        if ($validation->failed()){
            return $response->withRedirect($this->router->pathFor('auth.password.change'));
        }

        $this->auth->user()->setPassword($request->getParam('password'));
        $this->flash->addMessage('success', 'You have been successfully changed your password.');
        return $response->withRedirect($this->router->pathFor('home'));
    }
}