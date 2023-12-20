<?php

class TaskManager{

    private $tasksData; //almacenar los datos de tareas
    
    public function __construct($jsonFilePath){
        //leer el archivo json de tareas y los guarda
        $this->tasksData = json_decode(file_get_contents($jsonFilePath), true);  
    }

    //funciones 
    //mostrar tareas que hay en dataBase
    public function displayAvailableTasks(){
        echo "Tareas disponibles: " . PHP_EOL;
        foreach ($this->tasksData['tareas'] as $tarea){
            echo "ID " . $tarea['task_id'] . " - Descripcion: " . $tarea['task_description'] . PHP_EOL;
        }
    }

    //seleccionar las las tareas para crear la lista de tareas
    public function selectTasksForList(){
        $listaTareas = [];
        while (true){
            $idTarea = readline("Selecciona una tarea por su ID para agregar a la lista (0 para terminar): ");
            if ($idTarea == 0){
                break;
            }

            foreach ($this->tasksData['tareas'] as $tarea){
                if ($tarea['task_id']== $idTarea){
                    $listaTareas[]= $idTarea;
                }
            }
        }
        return $listaTareas;
    }
}

function createTaskList($taskManager){ //funcion crear lista de tareas !!!!ESTO DEBERIA IR EN EL CONTROLADOR?¿?!!!!
    $taskManager->displayAvailableTasks();
    $listaTareas = $taskManager->selectTasksForList();

    $nombreLista = readline("Introduce el nombre de la lista de tareas ");//cambiar para que se introduzcan los datos a traves del html de la web mediante formulario
    $prioridadLista = (int)readline("Ingresa la prioridad de la lista de tareas: ");

    //bucle por si introduce un número erróneo (esto ha de controlarse luego mediante html)
    while ($prioridadLista < 1 || $prioridadLista > 3){
        echo "La prioridad debe estar entre 1 y 3" . PHP_EOL;
        $prioridadLista = (int)realine("Ingresa la prioridad de la lista de tareas: ");
    }

    $lista = [
        'nombre' => $nombreLista,
        'prioridad' => $prioridadLista,
        'tareas' => $listaTareas
    ];
    return $lista;
}
//funcion que guarda cada lista de tareas en un archivo nuevo json (esto se puede cambiar si genera problemas --de momento los json de lista de tareas se están guardando en la carpeta models, esto he de cambiarlo--)

function saveTaskList($lista){//!!!!ESTO DEBERIA IR EN EL CONTROLADOR?¿?!!!!
    $listaJson = json_encode($lista, JSON_PRETTY_PRINT); //codifica el array asociativo en JSON para poder almacenar la informacion
    $numeroLista = rand(1,1000);//numero random para cada archivo que se genere (puede ocurrir q se generen dos listas con el mismo numero y pete XD aunq la probabilidad es baja teniendo en cuenta que es hasta 1000)
    $nombreArchivoLista = "Lista$numeroLista.json";

    file_put_contents($nombreArchivoLista,$listaJson);
}

$ruta_json = 'C:/Users/formacio/Desktop/Sprint3__TODOLIST/db/dataBase.json';
$taskManager = new TaskManager($ruta_json);
$lista = createTaskList($taskManager);
saveTaskList($lista);




?>