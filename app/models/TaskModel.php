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
    public function registerUser($username,$password,$email){
        $usersValidated= $this->validateUser($username,$password);
        if($usersValidated == false){
            $users = $this->loadUserData(); // Obtener datos existentes
            $newUserId = count($users["usuarios"]) + 1; // Obtener un nuevo ID único (puedes ajustar esto según tu lógica)
            $newUser = [
                "user_name" => $username,
                "password" => $password,
                "email" => $email,
                "id_user" => $newUserId,
            ];

            $users["usuarios"][] = $newUser; // Agregar el nuevo usuario al array existente
            $this->saveUsers($users); // Guardar los usuarios actualizados en el archivo JSON
            return $newUser; // Devolver el nuevo usuario agregado

            
            /*$newUser=["user_name"=>$username,"password"=>$password,"email"=>$email,"id_user"=>4];
            $users["usuarios"][] = $newUser;
            $jsonUsers = json_encode($users);
            file_put_contents($this->jsonUsers, $jsonUsers);
            return $jsonUsers;*/

 
            /*$jsonUsers=json_encode($newUser);
            $this->users[]=$jsonUsers;
            //var_dump($this->users);
            return $jsonUsers;*/
  
        }


    }

    private function saveUsers($users){
        $newJsonContent = json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($this->jsonUsers , $newJsonContent);
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
            if ($user["user_name"] == $username && $user["password"]== $password){
                echo $password;
                return true; 
            }
        } 
        return false;
    }

}
