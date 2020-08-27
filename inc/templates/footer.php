<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script language="JavaScript" type="text/javascript" src="js/jquery.js"></script>
<script src="js/sweetalert2.all.min.js"></script>
<?php 

    $actual = obtenerPaginaActual();
    if ($actual == 'crear-cuenta' || $actual == 'login'){
            echo '<script src="js/formulario.js"></script>';
    }else{
        echo '<script src="js/scripts.js"></script>';
    }
?>

</body>
</html>