<?php
//require_once "dataBase.json";
class TaskModel extends Model{
    protected $tasks = [];
    protected $users = [];
    protected $json;
    protected $jsonUsers;


    public function __construct(){
        $this-> json = ROOT_PATH . '/db/dataBase.json';
        $this-> jsonUsers = ROOT_PATH . '/db/dataBaseUsers.json';

    }
    
    public function getTasks(){
        $jsonContent = file_get_contents($this->json);
        return json_decode($jsonContent, true, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public function createTask($newTask){
        var_dump($newTask);

        if (empty($newTask['task_creation_date'])) { //fecha creacion automatica
            $newTask['task_creation_date'] = date("Y-m-d H:i:s");
        }

        $tasks = $this->getTasks();
        if (!isset($tasks['tasks'])) {//sin esta linea se creaban arrays anidados
            $tasks['tasks'] = array();}
        //$tasks[] = $newTask; array simple, por eso no funcionaba en la vista cuando añadias una tarea nueva creada. 
        $tasks['tasks'][] = $newTask;
        $this->saveTasks($tasks);
    }

    private function saveTasks($tasks){
        $newJsonContent = json_encode($tasks, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($this->json , $newJsonContent);
    }

    public function updateTask($updatedTask,$taskid){
       $tasks = $this->getTasks();
       foreach($tasks['tasks'] as $index =>$task){
        if($task['task_id']== $taskid){
            $tasks['tasks'][$index]=$updatedTask;
            $this-> saveTasks($tasks);
            echo "task updated succesfullly";
            return true; 
        }
       }
       echo "error";
       return false;

    }


    public function getTaskById($taskId){
        $tasks = $this->getTasks()['tasks'];
        //var_dump($tasks);
        foreach($tasks as $task){
            //var_dump($tasks);
            if($task['task_id'] == $taskId){
                return $task;
            }
        } 
        return "Task not found";
    }


    public function deleteTask($taskId){
        $tasksArray = $this->getTasks()['tasks'];
    
        $taskToDelete = $this->getTaskById($taskId);
    
        if ($taskToDelete !== "Task not found") {
            $taskIndex = array_search($taskToDelete, $tasksArray, true);
            if ($taskIndex !== false) {
                array_splice($tasksArray, $taskIndex, 1); 
                $tasks['tasks'] = $tasksArray;
                $this->saveTasks($tasks);
                return "Task deleted"; 
            }
        }
    
        return "Task not found or deletion failed";
    }
    


    public function registerUser($username,$password,$email){
        $usersValidated= $this->validateUser($username,$password);
        if($usersValidated == false){
            $users = $this->loadUserData(); // Obtener datos existentes
            $newUserId = count($users["usuarios"]) + 1; // Obtener un nuevo ID único 
            $newUser = [
                "user_name" => $username,
                "password" => $password,
                "email" => $email,
                "id_user" => $newUserId,
            ];

            $users["usuarios"][] = $newUser; // Agregar el nuevo usuario al array existente
            $this->saveUsers($users); // Guardar los usuarios actualizados en el archivo JSON
            return $newUser; // Devolver el nuevo usuario agregado
  
        }
    }

    private function saveUsers($users){
        $newJsonContent = json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($this->jsonUsers , $newJsonContent);
    }

    public function loadUserData():array {
        //var_dump($_SESSION['user_id']);
        //$newUser=[];
        $jsonContent = file_get_contents($this->jsonUsers);
        //$this->users = json_decode($jsonContent, true);
        $users = json_decode($jsonContent, true);
        return $users;
    }

    public function validateUser($username,$password,array $users= null){
        $users =  $this->loadUserData();
      
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

    public function getUserByUsername($username) {
        $users = $this->loadUserData();
        //var_dump($users);

        foreach ($users['usuarios'] as $user) {
            if ($user['user_name'] === $username) {
                return $user; // Devuelve la información del usuario si se encuentra el nombre de usuario
            }
        }

        return null; // Devuelve null si no se encuentra el usuario
    }







    
    /*
    public function deleteTask($taskId){
    $tasks = $this->getTasks();
    $tasksArray = $tasks['tasks'];

    $taskToDelete = $this->getTaskById($taskId);

    if ($taskToDelete !== "Task not found") {
        $taskIndex = array_search($taskToDelete, $tasksArray, true);
        if ($taskIndex !== false) {
            array_splice($tasksArray, $taskIndex, 1); 
            $tasks['tasks'] = $tasksArray;
            $this->saveTasks($tasks);
            return "Task deleted"; 
        }
    }

    return "Task not found or deletion failed";
}
    private function findTaskIndexById($taskId, $tasks){
        foreach($tasks as $index => $task){
            if($task['id'] == $taskId){
                return $index; 
            }
        }
    
        return "Task ID not found"; 
    }



https://www.adaweb.es/modelo-vista-controlador-mvc-en-php-actualizado-2022/ 
The Model class handles basic functionality such as:

Setting up a database connection (using PDO)
fetchOne(ID)
save(array) → both update/create
delete(ID)
*/

}
