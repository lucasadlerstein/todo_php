<aside class="contenedor-proyectos">
    <div class="panel crear-proyecto">
        <a href="#" class="boton">Nuevo Proyecto <i class="fas fa-plus"></i> </a>
    </div>

    <div class="panel lista-proyectos">
        <h2>Proyectos</h2>
        <ul id="proyectos" class="listado-proyectos">
            <?php 
                $proyectos = obtenerProyectos($_SESSION['id']);
                if($proyectos){
                    foreach($proyectos as $proyecto){ ?>
                        <li class="proyecto-individual" id="proyecto:<?php echo $proyecto['id']; ?>">
                            <a href="index.php?id_proyecto=<?php echo $proyecto['id']; ?>" id="proyecto:<?php echo $proyecto['id']; ?>"><?php echo $proyecto['nombre']; ?></a>
                            <i class="fas fa-trash borrar-proyecto"></i>
                        </li>
            <?php 
                    }
                }
                ?>
            
        </ul>
    </div>
</aside>