<?php
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

        if (empty($newTask['task_creation_date'])) { 
            $newTask['task_creation_date'] = date("Y-m-d H:i:s");
        }

        if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
            $newTask['task_created_by'] = $_SESSION['username']; 
        }

        $tasks = $this->getTasks();
        if (!isset($tasks['tasks'])) {
            $tasks['tasks'] = array();
        }

        $tasks['tasks'][] = $newTask;
        $this->saveTasks($tasks);
    }


    private function saveTasks($tasks){
        $newJsonContent = json_encode($tasks, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($this->json , $newJsonContent);
    }


    public function getTaskById($taskId){
        $tasks = $this->getTasks()['tasks'];
        foreach($tasks as $task){
            if($task['task_id'] == $taskId){
                return $task;
            }
        } 
        return "Task not found";
    }


    public function updateTask($updatedTask,$taskid){
        $tasks = $this->getTasks();
        
            foreach($tasks['tasks'] as $index =>$task){
            if($task['task_id']== $taskid){
                foreach($updatedTask as $key => $value){
                    if (array_key_exists($key,$task)){
                        $tasks['tasks'][$index][$key]=$value;
                    }
                }
                $this-> saveTasks($tasks);
                echo "task updated succesfullly";
                return true; 
            }
        }
        echo "error";
        return false;
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
            $users = $this->loadUserData(); 
            $newUserId = count($users["usuarios"]) + 1; 
            $newUser = [
                "user_name" => $username,
                "password" => $password,
                "email" => $email,
                "id_user" => $newUserId,
            ];

            $users["usuarios"][] = $newUser; 
            $this->saveUsers($users); 
            return $newUser; 
        }
    }


    private function saveUsers($users){
        $newJsonContent = json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($this->jsonUsers , $newJsonContent);
    }


    public function loadUserData():array {
        $jsonContent = file_get_contents($this->jsonUsers);
        $users = json_decode($jsonContent, true);
        return $users;
    }


    public function validateUser($username,$password,array $users= null){
        $users =  $this->loadUserData();
      
        foreach($users["usuarios"] as $user){
            if ($user["user_name"] == $username && $user["password"]== $password){
                echo $password;
                return true; 
            }
        } 
        return false;
    }


    public function getUserByUsername($username) {
        $users = $this->loadUserData();

        foreach ($users['usuarios'] as $user) {
            if ($user['user_name'] === $username) {
                return $user;
            }
        }
        return null; 
    }
}
