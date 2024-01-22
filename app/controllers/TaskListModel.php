<?php

class TaskListModel {

    private $jsonFilePath;

    public function __construct($jsonFilePath) {
        $this->jsonFilePath = $jsonFilePath;
    }

    public function deleteTaskList($taskListId) {
        try {
            $jsonContent = file_get_contents($this->jsonFilePath);
            $listasExistentes = json_decode($jsonContent, true);

            if ($listasExistentes === null) {
                $listasExistentes = array();
            }

            // Buscar y eliminar la lista de tareas por ID
            $indexToDelete = -1;
            foreach ($listasExistentes as $index => $lista) {
                if (isset($lista['nombre']) && $lista['nombre'] == $taskListId) {
                    $indexToDelete = $index;
                    break;
                }
            }

            if ($indexToDelete !== -1) {
                unset($listasExistentes[$indexToDelete]);
                $listasExistentes = array_values($listasExistentes); // Reindexar el array

                // Guardar el archivo actualizado
                file_put_contents($this->jsonFilePath, json_encode($listasExistentes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                return "Task list deleted";
            } else {
                return "Task list not found";
            }
        } catch (Exception $e) {
            return "Exception: " . $e->getMessage();
        }
    }
}
?>
