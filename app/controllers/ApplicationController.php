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
            $loginValidation=$loginAttempt->registerUser($username,$password,$email);
            //var_dump($loginValidation);
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