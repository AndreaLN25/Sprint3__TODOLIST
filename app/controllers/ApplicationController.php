<?php

/**
 * Base controller for the application.
 * Add general things in this controller.
 */

 //require_once (ROOT_PATH . 'app\models\TaskModel.php');

class ApplicationController extends Controller{

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

        /*$this->view->render('scripts/application/getTasks');*/
		//$this->view->render('application/getTasks.phtml'); duplica la vista 
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
					"task_assigned_to" => isset($_POST["task_assigned_to"]) ? intval($_POST["task_assigned_to"]) :0,//onvierte el valor de $_POST["task_assigned_to"] a un entero utilizando la función intval. 
					"task_created_by" => $_SESSION["user_id"] ,
					"task_updated_by" => $_SESSION["user_id"] ,
					"task_priority" => isset($_POST["task_priority"]) ? $_POST["task_priority"] : ""
				);
            
                $taskModel = new TaskModel();
                $taskModel->createTask($newTask);

                /*header("Location: /A_IT_ACADEMY_FULL_STACK_PHP/Sprint3__TODOLIST/web/getTasks");*/
				header("Location: " . WEB_ROOT . "/getTasks");
                exit();
            } else {
                echo "User session not found.";
			}
        }
       // $this->view->render('createTask');
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
				//$this->view->render('application/getTaskById.phtml');
			} else {
				echo "Task not found";
			}
		}
	}


	public function deleteTaskAction(){
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_id'])){
			$taskId = $_POST['task_id'];

			$taskModel = new TaskModel();
			$result = $taskModel->deleteTask($taskId);

			if ($result === "Task deleted")	{
				/*header("Location: /A_IT_ACADEMY_FULL_STACK_PHP/Sprint3__TODOLIST/web/getTasks");*/
				header("Location: " . WEB_ROOT . "/getTasks");
				exit();
			} else {
				echo "Failed to delete task.";
			}
		} else {
			echo "Invalid request to delete task.";
		}
	}
	
	
	public function editTaskAction(){

        //selecionar la task a editar 
        //ingresar nuevo valore y reemplazar lo ya existentes 
        //confirmar que los cambias han sucedido 
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_id'])){
	
			if (isset($_POST['task_id'])) {
				$taskId = $_POST['task_id'];
				$taskModel = new TaskModel();
				//$allTasks = $taskModel->getTasks(); 
				$taskData = $taskModel->getTaskById($taskId);
				var_dump($taskData);
				//$taskModel->updateTask($taskData,$taskId);
				if ($taskData !== "Task not found") {
					$this->view->taskData = $taskData;
					//$this->view->render('application/editTask.phtml'); // Nombre de tu vista de edición
				} else {
					echo "Task not found";
				}
			}
		}
	
       
    }

	public function updateTaskAction(){
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_id'])){
			$taskId = $_POST['task_id'];
			$updatedTaskData = [
				'task_id'=> $_POST['task_id'],
				'task_description' => $_POST['task_description'],
				'task_status' => $_POST['task_status'],
				'task_creation_date' => $_POST['task_creation_date'],
				'task_deadline' => $_POST['task_deadline'],
				'task_assigned_to' => $_POST['task_assigned_to'],
				'task_priority' => $_POST['task_priority'],
				// Agrega otros campos si es necesario
			];
				$taskModel = new TaskModel();
				$taskModel->getTaskById($taskId);
				$newTask=$taskModel->updateTask($updatedTaskData,$taskId);
				
				if($newTask===true){
					echo "yeiii";
					header("Location: " . WEB_ROOT . "/getTasks");
				}
				elseif($newTask===false){
					echo "buuu";
				}
				

		}

	}

	

	public function loginAction(){
    $loginAttempt = new TaskModel();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST["username"];
        $password = $_POST["password"];

        $loginResult = $loginAttempt->validateUser($username, $password);

        if ($loginResult == true) {
            $authenticatedUser = $loginAttempt->getUserByUsername($username);

            if ($authenticatedUser !== null && isset($authenticatedUser['user_name'])) {
                $_SESSION['user_id'] = $authenticatedUser['id_user'];
                $_SESSION['username'] = $authenticatedUser['user_name'];
                /*header("Location: /A_IT_ACADEMY_FULL_STACK_PHP/Sprint3__TODOLIST/web/getTasks");*/
				header("Location: " . WEB_ROOT . "/getTasks");
                exit();
            }
        } else {
            $this->view->mensaje = "Usuario no válido";
            /*$this->view->render(ROOT_PATH . '/app/views/scripts/login');*/
			//$this->view->render(WEB_ROOT . '/app/views/scripts/login');
        }
    } else {
        // Si no es una solicitud POST, simplemente muestra el formulario de inicio de sesión
        //$this->view->render(ROOT_PATH . '/app/views/scripts/login');Genera un doble vista. 
    }
}

    public function registerAction()
	{
        $loginAttempt=new TaskModel();

        if($_SERVER["REQUEST_METHOD"]=="POST"){
            $username = $_POST["username"];
            $password = $_POST["password"];
            $email = $_POST["email"];

            $loginRegisterOK=$loginAttempt->registerUser($username,$password,$email);

			if ($loginRegisterOK){
				$loginValidation=$loginAttempt->validateUser($username,$password);
				var_dump($loginValidation);

				if ($loginValidation){
					$this->view->mensaje = "Registro de usuario exitoso";
					header("Location: " . WEB_ROOT . "/getTasks");
					/*$this->view->render(ROOT_PATH . '/app/views/scripts/login');*/
					//$this->view->render(WEB_ROOT . '/app/views/scripts/login');Genera un doble vista. 
				}else{
					$this->view->mensaje = "Error en la validación después del registro";
					/*$this->view->render(ROOT_PATH . '/app/views/scripts/register');*/
					//$this->view->render(WEB_ROOT . '/app/views/scripts/register');Genera un doble vista. 
				}


			}else{
				$this->view->mensaje = "Error en el registro de usuario";
				//$this->view->render(ROOT_PATH . '/app/views/scripts/register');Genera un doble vista. 
			}
		}else{
			//$this->view->render('application/register.phtml');Genera un doble vista.
		}
            $verBaseDeDATOS= $loginAttempt->loadUserData();
            //var_dump($verBaseDeDATOS);
	}
	/*public function logout() {
		session_start();
	
		if(isset($_GET['logout'])) {
			session_destroy();
			header("Location: /A_IT_ACADEMY_FULL_STACK_PHP/Sprint3__TODOLIST/web/");
			exit;
		}
    }

	public function logout() {
		session_start(); // Inicia la sesión si no se ha iniciado
	
		// Destruye todas las variables de sesión
		$_SESSION = array();
	
		// Si se desea matar la sesión, también se debe eliminar la cookie de sesión
		if (isset($_COOKIE[session_name()])) {
			setcookie(session_name(), '', time()-42000, '/');
		}
	
		// Finalmente, destruye la sesión
		session_destroy();
	
		// Redirige al usuario a la página de inicio de sesión o a donde desees
		header("Location: /A_IT_ACADEMY_FULL_STACK_PHP/Sprint3__TODOLIST/web/");
		exit();
	}*/
	public function logoutAction() {
		session_start();
		session_destroy();
		/*header("Location: /A_IT_ACADEMY_FULL_STACK_PHP/Sprint3__TODOLIST/web/");*/
		header("Location: " . WEB_ROOT."/test");
		exit;
	}
	
}
?>