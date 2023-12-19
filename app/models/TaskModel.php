<?php
class TaskModel extends Model{
    protected $tasks = [];
    protected $json;
    protected $jsonUsers;

    public function __construct(){
        $this-> jsonUsers = ROOT_PATH . 'db/usersDataBase.json';
        $this-> json = ROOT_PATH . 'db/dataBase.json';

    }
    public function createUser(){

    }

    public function loadUserData():array {
        $jsonContent = file_get_contents($this->jsonUsers);
        $this->tasks = json_decode($jsonContent, true);
        //var_dump($this->tasks);
        return $this->tasks;
    }

    public function validateUser($username,$password){
        foreach($this->tasks as $task){
            if ($task ["user_name"]== $username){
                return false; 
        
            }
            elseif ($task ["user_name"]!== $username){
                
                $this->tasks[]=["username"=>$username, "user_password"=>$password];

                return true;

            }



        } 
        


       
    }

}
