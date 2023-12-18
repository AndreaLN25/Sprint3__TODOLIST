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
		$this->view->allTasks = $allTasks; // Asignar las tareas a la vista

        $this->view->render('scripts/application/getTasks'); // Renderizar la vista correspondiente
    }


	public function createTaskAction() {
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$newTask = array(
				"task_id"=> uniqid(),
				"task_description" => $_POST["task_description"],
				"task_status" => $_POST["task_status"],
				"task_created_at" => date("Y-m-d H:i"),
				"task_updated_at" => date("Y-m-d H:i"),
				"task_deadline" => $_POST["task_deadline"],
				"task_assigned_to" => $_POST["task_assigned_to"],
				"task_created_by" => $_SESSION["user_id"],
				"task_updated_by" => $_SESSION["user_id"],
				"task_priority" => $_POST["task_priority"]
			);
	
			$taskModel = new TaskModel();
			$taskModel->createTask($newTask);

			return $this->getTasksAction();
	
		}
	
		// Si no es una solicitud POST, mostrar el formulario
		$this->view->render('createTaskForm');
	}
}
