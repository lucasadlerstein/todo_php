<?php 
session_start();

        error_reporting(E_ALL ^ E_NOTICE);
    $accion = $_POST['accion'];
    $proyecto = $_POST['proyecto'];
    $id_proyecto = (int) $_POST['id'];
    $id_usuario = $_SESSION['id'];

    
    if($accion === 'crear'){
        // codigo para crear los admins

        // HASHEAR password
        $opciones = array(
            'cost' => '12'
        );

        $hash_password = password_hash($password, PASSWORD_BCRYPT, $opciones);

        // importar conexion 
        include '../funciones/conexion.php';
        
        try {
            // Realizar la consulta a la base de datos
            $stmt = $conn->prepare("INSERT INTO proyectos (nombre, usuario_proyecto) VALUES (?, ?) ");
            $stmt->bind_param('si', $proyecto, $id_usuario);
            $stmt->execute();
            if($stmt->affected_rows > 0) {
                $respuesta = array(
                    'respuesta' => 'correcto',
                    'id_insertado' => $stmt->insert_id,
                    'tipo' => $accion,
                    'nombre_proyecto' => $proyecto
                );
            }  else {
                $respuesta = array(
                    'respuesta' => 'error'
                );
            }
            $stmt->close();
            $conn->close();
        } catch(Exception $e) {
            // En caso de un error, tomar la exepcion
            $respuesta = array(
                'error' => $e->getMessage()
            );
        }
        
        echo json_encode($respuesta);
    } 

    if($accion === 'eliminar'){
        include '../funciones/conexion.php';
 
        try{
            $stmt2 = $conn->prepare("DELETE FROM tareas WHERE id_proyecto = ? ");
            $stmt2->bind_param('i', $id_proyecto);
            $stmt2->execute();

            $stmt = $conn->prepare("DELETE FROM proyectos WHERE id = ? ");
            $stmt->bind_param('i', $id_proyecto);
            $stmt->execute();
            if($stmt->affected_rows > 0) {
                $respuesta = array(
                    'respuesta' => 'correcto'
                );
            }  else {
                $respuesta = array(
                    'respuesta' => 'error'
                );
            }
            $stmt->close();
            $stmt2->close();

            $conn->close();
        }catch(Exception $e){
            $respuesta = array(
                'error' => $e->getMessage()
            );
        } 
        echo json_encode($respuesta);

    }

?>