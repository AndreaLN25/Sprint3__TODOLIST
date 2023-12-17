<?php
//require_once "dataBase.json";
class TaskModel extends Model{
    protected $tasks = [];
    protected $json;

    public function __construct(){
        $this-> json = ROOT_PATH . '/db/dataBase.json';
    }

    public function loadData():array {
        $jsonContent = file_get_contents($this->json);
        //var_dump($jsonContent);
        $this->tasks = json_decode($jsonContent, true);
        //var_dump($this->tasks);
        return $this->tasks;
    }
    
    private function saveTasks($tasks){
        $newJsonContent = json_encode($tasks, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($this->json , $newJsonContent);
    }
    public function getTasks(){
        $jsonContent = file_get_contents($this->json);
        $tasks = json_decode($jsonContent, true, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return $tasks;
    }

    public function createTask($newTask){
        $tasks = $this->getTasks();
        $tasks[] = $newTask;
        $this->saveTasks($tasks);
       
    }
    
    /*


    public function getTaskById($id){
        $tasks = $this->getTasks();
        foreach($tasks as $task){
            if($task['id'] == $id){
                return $task;
            }
        } 
        return "Task not found";
    }


    public function deleteTask($taskId){
        $tasks = $this->getTasks();
        $taskIndex = $this->findTaskIndexById($taskId, $tasks);
    
        if ($taskIndex !== false) {
            array_splice($tasks, $taskIndex, 1); 
            $this->saveTasks($tasks);
            return "Task deleted"; 
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
?>
