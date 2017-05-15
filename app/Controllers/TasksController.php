<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Task;
use Slim\Views\Twig as View;
use Respect\Validation\Validator as Respect;

class TasksController extends Controller
{
    public function getTasks($request, $response)
    {   
        return $this->view->render($response, 'tasks.twig');
    }

    public function postTask($request, $response)
    {
        $validation = $this->validator->validate($request, [
            'tasktitle' => Respect::noWhitespace()->notEmpty(),
            'taskdescription' => Respect::notEmpty(),
        ]);

        if ($validation->failed()){
            return $response->withRedirect($this->router->pathFor('task'));
        }

        Task::create([
             'task_title' => $request->getParam('tasktitle'),
             'task_description' => $request ->getParam('taskdescription'),
             'task_status' => $request ->getParam('taskstatus'),
             'id' => $this->auth->user()->id,
        ]);

        $this->flash->addMessage('success', 'New Task has been registered');

        return $response->withRedirect($this->router->pathFor('tasks'));
    }
}