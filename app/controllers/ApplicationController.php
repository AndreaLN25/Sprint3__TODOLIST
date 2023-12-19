<?php

/**
 * Base controller for the application.
 * Add general things in this controller.
 */
class ApplicationController extends Controller 
{
	public function loginAction()
	{
        $loginAttempt=new TaskModel();

        if($_SERVER["REQUEST_METHOD"]=="POST"){
            $username = $_POST["username"];
            $password = $_POST["password"];
            $loginResult=$loginAttempt->validateUser($username,$password);
            if($loginResult == true){
                $this->view->mensaje = "Ha sido exitoso el registro de usuario";
                var_dump($username);
                //$this->view->render(ROOT_PATH . '/app/views/scripts/login');

               
            }
            else{
                $this->view->mensaje = "Usuario no valido";
                var_dump($username);
            }
        }
		   // Lógica del controlador

          // $this->view->render(ROOT_PATH . '/app/views/scripts/login');

    }
     
      
	

    public function registerAction()
	{
        if($_SERVER["REQUEST_METHOD"]=="POST"){
            $username = $_POST["username"];
            $password = $_POST["password"];
            $email = $_POST["email"];
		   // Lógica del controlador
           $this->view->mensaje = "Hola desde el controlador";
        
		   // Lógica del controlador
           $this->view->mensaje = "Hola desde el controlador del registro";
        }
	}

}
?>