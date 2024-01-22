<?php
require_once __DIR__ . '/../models/TaskListModel.php';
/**
 * Base controller for the application.
 * Add general things in this controller.
 */

 //require_once (ROOT_PATH . 'app\models\TaskModel.php');

class ApplicationController extends Controller{

	public function getTasksAction(){  
		$taskModel = new TaskModel();
        $allTasks = $taskModel->getTasks();
		$this->view->allTasks = $allTasks; 
    }


	public function createTaskAction() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			if(isset($_SESSION["user_id"])){  
				$creationDate = isset($_POST['task_creation_date']) ? $_POST["task_creation_date"] : "";
				$taskDeadline = date('Y-m-d', strtotime($creationDate . ' +1 month'));
				$newTask = array(
					"task_id" => uniqid(),
					"task_description" => isset($_POST["task_description"]) ? $_POST["task_description"] : "",
					"task_status" => isset($_POST["task_status"]) ? $_POST["task_status"] : "pending",
					"task_creation_date" => date("Y-m-d H:i"),
					"task_updated_at" => date("Y-m-d H:i"),
					"task_deadline" => isset($_POST["task_deadline"]) ? $_POST["task_deadline"] : "",
					"task_assigned_to" => isset($_POST["task_assigned_to"]) ? intval($_POST["task_assigned_to"]) :0,
					"task_created_by" => $_SESSION["user_id"] ,
					"task_updated_by" => $_SESSION["user_id"] ,
					"task_priority" => isset($_POST["task_priority"]) ? $_POST["task_priority"] : ""
				);
            
                $taskModel = new TaskModel();
                $taskModel->createTask($newTask);

				header("Location: " . WEB_ROOT . "/getTasks");
                exit();
            } else {
                echo "User session not found.";
			}
        }
    }


	public function getTaskByIdAction() {
		if (isset($_GET['task_id'])) {
			$taskId = $_GET['task_id'];
	
			$taskModel = new TaskModel();
	
			$taskByID = $taskModel->getTaskById($taskId);
	
			if ($taskByID !== "Task not found") {
				$this->view->task = $taskByID; 
				$this->view->allTasks= $taskModel->getTasks(); 
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

			if ($result === "Task deleted"){
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
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_id'])){
	
			if (isset($_POST['task_id'])) {
				$taskId = $_POST['task_id'];
				$taskModel = new TaskModel();
				$taskData = $taskModel->getTaskById($taskId);
				if ($taskData !== "Task not found") {
					$this->view->taskData = $taskData;
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
				'task_created_by' => $_POST['task_created_by'],
				'task_updated_by' => $_POST['task_updated_by'],
				'task_priority' => $_POST['task_priority'],
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
					header("Location: " . WEB_ROOT . "/getTasks");
					exit();
				}
			} else {
				$this->view->mensaje = "Usuario no válido";
			}
		}
	}

	
    public function registerAction(){
        $loginAttempt = new TaskModel();

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
				}else{
					$this->view->mensaje = "Error en la validación después del registro";
				}


			}else{
				$this->view->mensaje = "Error en el registro de usuario";
				$this->view->render(WEB_ROOT . '/app/views/scripts/register');
			}
		}
            $verBaseDeDATOS= $loginAttempt->loadUserData();
	}
	

	public function logoutAction() {
		session_start();
		session_destroy();
		header("Location: " . WEB_ROOT."/test");
		exit;
	}

	function createTaskList($taskManager) { 
        $taskManager->displayAvailableTasks();
        $listaTareas = $taskManager->selectTasksForList();

        $nombreLista = readline("Introduce el nombre de la lista de tareas ");
        $prioridadLista = (int)readline("Ingresa la prioridad de la lista de tareas: ");

        while ($prioridadLista < 1 || $prioridadLista > 3) {
            echo "La prioridad debe estar entre 1 y 3" . PHP_EOL;
            $prioridadLista = (int)readline("Ingresa la prioridad de la lista de tareas: ");
        }

        $lista = [
            'nombre' => $nombreLista,
            'prioridad' => $prioridadLista,
            'tareas' => $listaTareas
        ];
        return $lista;
    }

    public function processTaskListAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombreLista = $_POST['nombreLista'];
            $prioridad = $_POST['prioridad'];
            $tareasSeleccionadas = isset($_POST['tareas']) ? $_POST['tareas'] : array();

            $nuevaLista = array(
                'nombre' => $nombreLista,
                'prioridad' => $prioridad,
                'tareas' => $tareasSeleccionadas
            );

            $this->saveTaskList($nuevaLista);

            header("Location: " . WEB_ROOT . "/showTaskList");
            exit();
        }
    }

    //norberto
    public function createTaskListFormAction() {
        $jsonFilePath = __DIR__ . '/../../db/dataBase.json';
        $jsonContent = file_get_contents($jsonFilePath);

        $tasksData = json_decode($jsonContent, true, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        if ($tasksData === null) {
            die('Error al decodificar el archivo JSON');
        }

        $this->view->tasksData = $tasksData;
    }

    // Función para guardar la nueva lista en un archivo JSON
    private function saveTaskList($lista) {
        $listaTareasFilePath = __DIR__ . '/../../db/nuevaLista.json';
        $jsonContent = file_get_contents($listaTareasFilePath);

        $listasExistentes = json_decode($jsonContent, true);

        if ($listasExistentes === null) {
            $listasExistentes = array();
        }

        $listasExistentes[] = $lista;

        file_put_contents($listaTareasFilePath, json_encode($listasExistentes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
	public function showTaskListAction() {
		// Cargar las listas de tareas desde el archivo JSON
		$listaTareasFilePath = __DIR__ . '/../../db/nuevaLista.json';
		$jsonContent = file_get_contents($listaTareasFilePath);
		$listasExistentes = json_decode($jsonContent, true);
		
		if ($listasExistentes === null) {
			die('Error al decodificar el archivo JSON de listas de tareas');
		}
	
		// Asignar las listas a la vista para que puedan ser renderizadas en el PHTML
		$this->view->allTasks = $listasExistentes;

	}

	public function deleteTaskListAction() {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $taskListId = isset($_POST['taskListId']) ? $_POST['taskListId'] : null;

                if ($taskListId !== null) {
                    $jsonFilePath = __DIR__ . '/../../db/nuevaLista.json'; // Ajusta la ruta según tu estructura de archivos
                    $taskListModel = new TaskListModel($jsonFilePath); // Pasa la ruta del archivo JSON a TaskListModel
                    $result = $taskListModel->deleteTaskList($taskListId);

                    if ($result === "Task list deleted") {
                        header("Location: " . WEB_ROOT . "/showTaskList"); // Cambiado a la acción correcta
                        exit();
                    } else {
                        echo "Failed to delete task list. Error: " . $result;
                    }
                } else {
                    echo "Invalid task list ID.";
                }
            } else {
                echo "Invalid request method. Method: " . $_SERVER['REQUEST_METHOD'];
            }
        } catch (Exception $e) {
            echo "Exception: " . $e->getMessage();
        }
    }
}
	
	
	
	

	

?>
