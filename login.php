<?php 
    session_start();
    include 'inc/funciones/funciones.php';
    include 'inc/templates/header.php';

    if(isset($_GET['cerrar_sesion'])){
        $_SESSION = array();
    }
?>

    <div class="contenedor-formulario">
        <h1><img src="img/todo-logo.png" class="logo-login" alt="Logo ToDo">ToDo</h1>
        <form id="formulario" class="caja-login" method="post">
            <div class="campo">
                <label for="usuario" class="no-responsive">Usuario: </label>
                <input type="text" name="usuario" id="usuario" placeholder="Usuario">
            </div>
            <div class="campo">
                <label for="password" class="no-responsive">Password: </label>
                <input type="password" name="password" id="password" placeholder="Password">
            </div>
            <div class="campo enviar">
                <input type="hidden" id="tipo" value="login">
                <input type="submit" class="boton" value="Iniciar Sesión">
            </div>

            <div class="campo centrar">
                <a href="crear-cuenta.php">Crea nueva cuenta</a>
            </div>
        </form>
    </div>

    <?php include 'inc/templates/footer.php';?>