<?php 
        error_reporting(E_ALL ^ E_NOTICE);


// die(json_encode($_POST));

    $usuario =  $_POST['usuario'];
    $password = $_POST['password'];
    $accion =   $_POST['accion'];

    if($accion === 'crear'){
        // codigo para crear los admins

        // HASHEAR password
        $opciones = array(
            'cost' => '12'
        );

        $hash_password = password_hash($password, PASSWORD_BCRYPT, $opciones);

        // importar conexion 
        include '../funciones/conexion.php';
        
        try{
            $stmt = $conn->prepare("INSERT INTO usuarios (usuario, password) VALUES (?, ?) ");
            $stmt->bind_param('ss', $usuario  , $hash_password);
            $stmt->execute();
            if($stmt->affected_rows > 0) {
                $respuesta = array(
                    'respuesta' => 'correcto',
                    'id_insertado' => $stmt->insert_id,
                    'tipo' => $accion
                );
            } else {
                $respuesta = array(
                    'respuesta' => 'error',
                    'tipo' => $accion
                );
            }
            $stmt->close();
            $conn->close();
        } catch(Exception $e) {
            // En caso de un error, tomar la exepcion
            $respuesta = array(
                'error' => 'ERROR 100051!'
            );
        }
        echo json_encode($respuesta);        
    } 

    if($accion === 'login'){
        include '../funciones/conexion.php';

        try{
            $stmt = $conn->prepare(" SELECT usuario, id, password FROM usuarios WHERE usuario = ? ");
            $stmt->bind_param('s', $usuario);
            $stmt->execute();

            $stmt->bind_result($nombre_usuario, $id_usuario, $password_usuario);
            $stmt->fetch();
            if($nombre_usuario){
                if(password_verify($password, $password_usuario)){
                    //iniciar la sesion
                    session_start();
                    $_SESSION['nombre'] = $nombre_usuario;
                    $_SESSION['id'] = $id_usuario;
                    $_SESSION['login'] = true;

                    //login correcto ARRAY
                    $respuesta = array(
                        'respuesta' => 'correcto',
                        'nombre' => $nombre_usuario,
                        'tipo' => $accion
                        );
                }else{
                    $respuesta = array(
                        'respuesta' => 'error',
                        'tipo' => $accion,
                        'titulomensaje' => 'Password invalida'
                    );
                }
                
            } else{
                $respuesta = array(
                    'respuesta' => 'error',
                    'tipo' => $accion,
                    'titulomensaje' => 'Usuario inexistente'
                );
            }
            
            $stmt->close();
            $conn->close();

        }catch(Exception $e) {
            // En caso de un error, tomar la exepcion
            $respuesta = array(
                'error' => $e->getMessage()
            );
        }
        echo json_encode($respuesta);
    }

?>