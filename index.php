<?php 
    include 'inc/funciones/sesiones.php';
    error_reporting(E_ALL ^ E_NOTICE);
    include 'inc/funciones/funciones.php';
    include 'inc/templates/header.php';
    include 'inc/templates/barra.php';

    if(isset($_GET['id_proyecto'])){ 
        $IdproyectoActual = $_GET['id_proyecto'];
    }else{
        $IdproyectoActual = -35;
    }
?>

<div class="contenedor">
    
    <?php include 'inc/templates/sidebar.php'; ?>

    <main class="contenido-principal">

    <?php $nombreProyectoActual = obtenerNombreProyecto($IdproyectoActual); 
    if($nombreProyectoActual->num_rows > 0): ?>
        <h1> 
        <?php    foreach($nombreProyectoActual as $nombreP){ ?>
            <span> <?php echo $nombreP['nombre']; ?> </span>
            <?php } ?>
        </h1>

        <form action="#" class="agregar-tarea">
            <div class="campo campo-tarea">
                <input type="text" placeholder="Nueva tarea" class="nombre-tarea"> 

                <div class="campo enviar" id="agregar-input-enviar">
                    <input type="hidden" id="id_proyecto" value="<?php echo $IdproyectoActual; ?>">
                    <input type="submit" id="agregar-tarea-deskt" class="boton nueva-tarea" value="Agregar">
                    <input type="submit" id="agregar-tarea-mobile" class="boton nueva-tarea fa fa-input" value="&#xf055">
                </div>
            </div>
        </form>
        
        <?php 
            else: ?>
        <h1>Proyectos</h1>
        <ul id="proyectos" class="agregar-tarea elegir-proyectos">
            
            <?php 
                $proyectos = obtenerProyectos($_SESSION['id']);
                if($proyectos){
                    foreach($proyectos as $proyecto){ ?>
                    <div class="campo">
                        <li class="proyecto-individual" id="proyecto:<?php echo $proyecto['id']; ?>">
                            <a href="index.php?id_proyecto=<?php echo $proyecto['id']; ?>" id="proyecto:<?php echo $proyecto['id']; ?>"><?php echo $proyecto['nombre']; ?></a>
                            <i class="fas fa-trash borrar-proyecto"></i>
                        </li>
                    </div>
            <?php 
                    }
                }
                ?>
            
        </ul>
    </div>
        <?php    endif;
        ?>
        <?php $tareas = obtenerTareasProyecto($IdproyectoActual);
        if($tareas->num_rows > 0){ ?>
        <h2>Tareas</h2>

        <div class="listado-pendientes">
            <ul>
            
            <?php  foreach($tareas as $tarea): ?>
                    <li id="tarea:<?php echo $tarea['id']; ?>" class="tarea">
                        <p> <?php echo $tarea['nombre']; ?> </p>
                        <div class="acciones">
                            <i class="far fa-check-circle <?php echo ($tarea['estado'] === '1' ? 'completo' : '') ?>"></i>
                            <i class="fas fa-trash"></i>
                        </div>
                    </li> 
                <?php endforeach; ?>
            </div>
                <div class="avance">
                <h2>Avance del proyecto</h2>
                <div class="barra-avance" id="barra-avance">
                    <div id="porcentaje" class="porcentaje">
                    </div>
                </div>
                </div>
            <?php    }else if($tareas->num_rows < 1 && $nombreProyectoActual->num_rows > 0){
                        echo "<h2>Este proyecto no tiene tareas</h2>";
            }   ?>
                 
            </ul>
        </div>
        
    </main>
</div><!--.contenedor-->

<?php include 'inc/templates/footer.php';?>