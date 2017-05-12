<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

// Get All Users
$app->get('/api/users', function(Request $request, Response $response){
    $sql = "SELECT * FROM users";

    try{
        //GET DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db ->query($sql); 
        $users = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($users);


    } catch(PDOException $e){
        echo '{"error": {"text" : "'.$e->getMessage().'" }}';

    }
});

// Get Single User
$app->get('/api/user/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');
    $sql = "SELECT * FROM users WHERE id = $id";

    try{
        //GET DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db ->query($sql); 
        $user = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($user);


    } catch(PDOException $e){
        echo '{"error": {"text" : "'.$e->getMessage().'", "user_name": "'.$user_name.'", "sql": "'.$sql.'" }}';

    }
});

// Post Add User
$app->post('/api/user/add', function(Request $request, Response $response){
    $first_name = $request->getParam('first_name');
    $last_name = $request->getParam('last_name');
    $email = $request->getParam('email');
    $phone = $request->getParam('phone');
    
    $sql = "INSERT INTO users (first_name, last_name, email, phone) 
            VALUES(:first_name, :last_name, :email, :phone )";

    try{
        //GET DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db ->prepare($sql);

        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);

        $stmt->execute();
        echo '{"Notice": {"text" : "User Added" }}';
      

    } catch(PDOException $e){
        echo '{"error": {"text" : "'.$e->getMessage().'" }}';

    }
});

// Put Modify User
$app->put('/api/user/update/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');
    $first_name = $request->getParam('first_name');
    $last_name = $request->getParam('last_name');
    $email = $request->getParam('email');
    $phone = $request->getParam('phone');
    
    $sql = "UPDATE users SET
               first_name = :first_name, 
               last_name = :last_name,
               email = :email,
               phone = :phone
            WHERE id = $id";

    try{
        //GET DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db ->prepare($sql);

        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);

        $stmt->execute();
        echo '{"Notice": {"text" : "User modified" }}';
      

    } catch(PDOException $e){
        echo '{"error": {"text" : "'.$e->getMessage().'" }}';

    }
});

// Delete Single User
$app->delete('/api/user/delete/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');
    
    $sql = "DELETE FROM users WHERE id = $id";

    try{
        //GET DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db ->prepare($sql); 
        $stmt->execute();
        $db = null;
        echo '{"Notice": {"text" : "User Deleted" }}';


    } catch(PDOException $e){
        echo '{"error": {"text" : "'.$e->getMessage().'" }}';

    }
});


// Get All Tasks
$app->get('/api/tasks', function(Request $request, Response $response){
    $sql = "SELECT * FROM tasks";

    try{
        //GET DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db ->query($sql); 
        $Tasks = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($Tasks);


    } catch(PDOException $e){
        echo '{"error": {"text" : "'.$e->getMessage().'" }}';

    }
});

// Get Single Task
$app->get('/api/task/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');
    $sql = "SELECT * FROM tasks WHERE taskID = $id";

    try{
        //GET DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db ->query($sql); 
        $Task = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($Task);


    } catch(PDOException $e){
        echo '{"error": {"text" : "'.$e->getMessage().'" }}';

    }
});

// Post Add Task
$app->post('/api/task/add', function(Request $request, Response $response){
    $id = $request->getParam('user_id');
    $task_title = $request->getParam('task_title');
    $task_description = $request->getParam('task_description');
    $task_state = $request->getParam('task_state');
    
    $sql = "INSERT INTO tasks (id, task_title, task_description, task_state) 
            VALUES(:id, :task_title, :task_description, :task_state )";

    try{
        //GET DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db ->prepare($sql);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':task_title', $task_title);
        $stmt->bindParam(':task_description', $task_description);
        $stmt->bindParam(':task_state', $task_state);

        $stmt->execute();
        echo '{"Notice": {"text" : "Task Added" }}';
      

    } catch(PDOException $e){
        echo '{"error": {"text" : "'.$e->getMessage().'" }}';

    }
});

// Put Modify User
$app->put('/api/task/update/{id}', function(Request $request, Response $response){
    $taskID = $request->getAttribute('id');
    $id = $request->getParam('user_id');
    $task_title = $request->getParam('task_title');
    $task_description = $request->getParam('task_description');
    $task_state = $request->getParam('task_state');
    
    $sql = "UPDATE tasks SET
               :id = id, 
               :task_title = task_title, 
               :task_description = task_description
            WHERE taskID = $taskID";

    try{
        //GET DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db ->prepare($sql);


        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':task_title', $task_title);
        $stmt->bindParam(':task_description', $task_description);

        $stmt->execute();
        echo '{"Notice": {"text" : "User modified" }}';
      

    } catch(PDOException $e){
        echo '{"error": {"text" : "'.$e->getMessage().'" }}';

    }
});

// Delete Single User
$app->delete('/api/task/delete/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');
    
    $sql = "DELETE FROM tasks WHERE taskID = $id";

    try{
        //GET DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db ->prepare($sql); 
        $stmt->execute();
        $db = null;
        echo '{"Notice": {"text" : "User Deleted" }}';


    } catch(PDOException $e){
        echo '{"error": {"text" : "'.$e->getMessage().'" }}';

    }
});
