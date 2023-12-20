<?php
class TaskModel extends Model{
    protected $tasks = [];
    protected $users = [];
    protected $json;
    protected $jsonUsers;

    public function __construct(){
        $this-> jsonUsers = ROOT_PATH . '/db/dataBaseUsers.json';
        $this-> json = ROOT_PATH . '/db/dataBase.json';

    }
    public function createUser(){

    }

    public function loadUserData():array {
        //$newUser=[];
        $jsonContent = file_get_contents($this->jsonUsers);
        //$this->users = json_decode($jsonContent, true);
        $users = json_decode($jsonContent, true);
      
        return $users;
    }

    public function validateUser($username,$password,array $users= null){
      if($users==null){
        $users =  $this->loadUserData();
      }
        foreach($users["usuarios"] as $user){
            //var_dump($user);
            //print_r($user[2]["user_name"]);
            if ($user["user_name"]== $username && $user["password"]== $password){
                echo $password;
                return true; 
              
            }
        
        } 

      
        return false;


       
    }

}
