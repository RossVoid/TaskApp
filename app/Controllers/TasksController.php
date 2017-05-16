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
        $tasks = Task::all();
        $users = User::all();
        

        return $this->view->render($response, 'tasks.twig', [
            'users' => $users,
            'tasks' => $tasks
        ]);
    }

    public function postTask($request, $response)
    {
        $validation = $this->validator->validate($request, [
            'tasktitle' => Respect::notEmpty(),
            'taskdescription' => Respect::notEmpty(),
        ]);

        if ($validation->failed()){
            $this->flash->addMessage('danger', 'Failed to create new task!');
            return $response->withRedirect($this->router->pathFor('tasks'));
        }

        Task::create([
             'user_id' => $this->auth->user()->id,
             'task_title' => $request->getParam('tasktitle'),
             'task_description' => $request ->getParam('taskdescription'),
             'task_status' => $request ->getParam('taskstatus')
        ]);

        $this->flash->addMessage('success', 'New Task has been registered');

        return $response->withRedirect($this->router->pathFor('tasks'));
    }

    public function getTask($request, $response, $args)
    {   
        $taskID = intval($args['taskID']);
        $task = Task::where('id', $taskID)->get()->first();
        $users = User::all();

        return $this->view->render($response, 'task.twig', [
            'task' => $task,
            'users' => $users
        ]);
    }

    public function updateTask($request, $response, $args)
    {
        $taskID = intval($args['taskID']);
        $user = User::where('email', $request->getParam('assignedto'))->get()->first();
        
        $userid = intval($user['id']);

        $validation = $this->validator->validate($request, [
            'tasktitle' => Respect::notEmpty(),
            'taskdescription' => Respect::notEmpty(),
        ]);

        if ($validation->failed()){
            $this->flash->addMessage('danger', 'Failed to update task #'. $taskID .'!');
            return $response->withRedirect($this->router->pathFor('tasks'));
        }

        Task::where('id', $taskID)->get()->first()->update([
            'user_id' => $userid,
            'task_title' => $request->getParam('tasktitle'),
            'task_description' => $request ->getParam('taskdescription'),
            'task_status' => $request ->getParam('taskstatus'),
        ]);

        $this->flash->addMessage('success', 'Task #'. $taskID .' has been updated');

        return $response->withRedirect($this->router->pathFor('tasks'));
    }

    public function getTaskDelete($request, $response, $args)
    {   
        $taskID = intval($args['taskID']);
        $task = Task::where('id', $taskID)->get()->first();
        $users = User::all();

        return $this->view->render($response, 'taskDelete.twig', [
            'task' => $task,
            'users' => $users
        ]);
    }

    public function deleteTask($request, $response, $args)
    {
        $taskID = intval($args['taskID']);

        Task::where('id', $taskID)->get()->first()->delete();

        $this->flash->addMessage('success', 'Task #'. $taskID .' has been Deleted');

        return $response->withRedirect($this->router->pathFor('tasks'));
    }
}