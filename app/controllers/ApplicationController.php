<?php

/**
 * Base controller for the application.
 * Add general things in this controller.
 */

 //require_once (ROOT_PATH . 'app\models\TaskModel.php');

class ApplicationController extends Controller 
{

	/*
	public function indexAction()
	{
		$this->view->message = "hello from test::index";
		$tasks=new TaskModel();
		$arrayTasks=$tasks->loadData();
		$this->view->tasks=$arrayTasks;
		//var_dump($arrayTasks);
		$this->view->render('application/index');

	}

	
	public function checkAction()
	{
		echo "hello from test::check";
		//require_once "views/layouts/layout.phtml";
	}*/

	public function getTasksAction(){  
		$taskModel = new TaskModel();
        $allTasks = $taskModel->getTasks();
        //$this->view->allTasks = $dataJson->getTasks();
		//var_dump($allTasks);
		$this->view->allTasks = $allTasks; 

        $this->view->render('scripts/application/getTasks'); 
    }



	public function createTaskAction() {
        var_dump($_POST);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			if(isset($_SESSION["user_id"])){ //login 
				$creationDate = isset($_POST['task_creation_date']) ? $_POST["task_creation_date"] : "";
				$taskDeadline = date('Y-m-d', strtotime($creationDate . ' +1 month'));
				$newTask = array(
					"task_id" => uniqid(),
					"task_description" => isset($_POST["task_description"]) ? $_POST["task_description"] : "",
					"task_status" => isset($_POST["task_status"]) ? $_POST["task_status"] : "pending",
					"task_creation_date" => date("Y-m-d H:i"),
					"task_updated_at" => date("Y-m-d H:i"),
					"task_deadline" => isset($_POST["task_deadline"]) ? $_POST["task_deadline"] : "",
					"task_assigned_to" => isset($_POST["task_assigned_to"]) ? $_POST["task_assigned_to"] : "",
					"task_created_by" => $_SESSION["user_id"] ,
					"task_updated_by" => $_SESSION["user_id"] ,
					"task_priority" => isset($_POST["task_priority"]) ? $_POST["task_priority"] : ""
				);
            
                $taskModel = new TaskModel();
                $taskModel->createTask($newTask);

                header("Location: /A_IT_ACADEMY_FULL_STACK_PHP/Sprint3__TODOLIST/web/getTasks");
                exit();
            } else {
                echo "User session not found.";
			}
        }
        $this->view->render('createTask');
    }


	public function getTaskByIdAction() {
		if (isset($_GET['task_id'])) {
			$taskId = $_GET['task_id'];
	
			$taskModel = new TaskModel();
			//$allTasks = $taskModel->getTasks(); 
	
			$taskByID = $taskModel->getTaskById($taskId);
	
			if ($taskByID !== "Task not found") {
				$this->view->task = $taskByID; 
				$this->view->allTasks= $taskModel->getTasks(); 
				$this->view->render('getTaskById');
			} else {
				echo "Task not found";
			}
		}
	}
	public function loginAction()
	{
        $loginAttempt=new TaskModel();

        if($_SERVER["REQUEST_METHOD"]=="POST"){
            $username = $_POST["username"];
            $password = $_POST["password"];
            $loginResult=$loginAttempt->validateUser($username,$password,);
            var_dump($loginResult);
            if($loginResult == true){
                $this->view->mensaje = "Bienvenido";
                var_dump($username);
                //$this->view->render(ROOT_PATH . '/app/views/scripts/login');

               
            }
            else{
                $this->view->mensaje = "Usuario no valido";
                //var_dump($username);
            }
        }
		   // Lógica del controlador

          // $this->view->render(ROOT_PATH . '/app/views/scripts/login');

    }
     
      
	

    public function registerAction()
	{
        $loginAttempt=new TaskModel();
        if($_SERVER["REQUEST_METHOD"]=="POST"){
            $username = $_POST["username"];
            $password = $_POST["password"];
            $email = $_POST["email"];
            $loginRegister=$loginAttempt->registerUser($username,$password,$email);
            var_dump($loginRegister);
            $loginValidation=$loginAttempt->validateUser($username,$password);
            //var_dump($loginValidation);
            $verBaseDeDATOS= $loginAttempt->loadUserData();
            var_dump($verBaseDeDATOS);
            
            if($loginValidation==true){
                $this->view->mensaje = "Ha sido exitoso el registro de usuario";
            }
            else{
                echo "error";
            }

        }
	}

}
?>