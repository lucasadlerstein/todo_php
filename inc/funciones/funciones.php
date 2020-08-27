<?php 

function obtenerPaginaActual(){
    $archivo = basename($_SERVER['PHP_SELF']);
    $pagina = str_replace(".php", "", $archivo); 
    return $pagina;
}


// CONSULTAS

function obtenerProyectos($id_usuario){
    include 'conexion.php';
    try{
        return $conn->query(" SELECT id, nombre FROM proyectos WHERE usuario_proyecto = $id_usuario ");        
         
    }catch(Exception $e){
        echo 'error: ' . $e->getMessage();
        return false;
    }
}

function obtenerNombreProyecto($id = null){
    include 'conexion.php';
    try{
        return $conn->query(" SELECT nombre FROM proyectos WHERE id = {$id} ");

    }catch(Exception $e){
        echo 'error: ' . $e->getMessage();
        return false;
    }
}

function obtenerTareasProyecto($id = null){
    include 'conexion.php';
    try{
        return $conn->query(" SELECT id, nombre, estado FROM tareas WHERE id_proyecto = {$id} ");

    }catch(Exception $e){
        echo 'error: ' . $e->getMessage();
        return false;
    }
}



?>