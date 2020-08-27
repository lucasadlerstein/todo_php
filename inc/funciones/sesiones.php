<?php 

function usuario_autenticado(){
    if(!revisar_usuario()){
        // si no hay un usuario o no esta ok la sesion
        header('Location:login.php');
        exit();
    }
}

function revisar_usuario(){
    return isset($_SESSION['nombre']);
}

session_start();
usuario_autenticado();

?>