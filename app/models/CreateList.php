<?php
//json route
$ruta_json = 'C:/Users/formacio/Desktop/Sprint3__TODOLIST/db/dataBase.json'; 

$json_content = file_get_contents($ruta_json);

$data = json_decode($json_content, true);//decode json info to php 

//if decode isnt ok (error)
if ($data === null) { //if decode is wrong...
    echo "Error al decodificar el archivo JSON." . PHP_EOL;
} else {//if decode ok
    // task by prority 1-3 (1->important task 2->middle task 3->not important task)
    function sortByPriority($a, $b) {
        return $a['task_priority'] - $b['task_priority'];
    }

    $tasks = $data['tareas'];
    usort($tasks, 'sortByPriority');

    // show all task ordered by prority
    foreach ($tasks as $task) {
        echo "ID: " . $task['task_id'] . PHP_EOL;
        echo "Descripción: " . $task['task_description'] . PHP_EOL;
        echo "Prioridad: " . $task['task_priority'] . PHP_EOL . PHP_EOL;
    }
}
?>
