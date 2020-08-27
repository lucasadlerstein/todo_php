eventListeners();

function eventListeners() {
    // Document READY
    document.addEventListener('DOMContentLoaded', function() {
        actualizarProgreso();

    });

    document.querySelector('.crear-proyecto a').addEventListener('click', nuevoProyecto);
    document.querySelector('#agregar-tarea-deskt').addEventListener('click', nuevaTarea);
    document.querySelector('#agregar-tarea-mobile').addEventListener('click', nuevaTarea);
    document.querySelector('.listado-pendientes').addEventListener('click', accionesTareas);
    document.querySelector('.listado-proyectos').addEventListener('click', eliminarProyecto)
}

function nuevoProyecto(e) {
    e.preventDefault();

    // Crear un input para el nombre del proyecto
    var nuevoProyecto = document.createElement('li');
    nuevoProyecto.innerHTML = '<input type="text" placeholder="Nuevo proyecto" class="nuevo-proyecto-campo" id="nuevo-proyecto" autofocus="autofocus">';
    document.querySelector('ul#proyectos').appendChild(nuevoProyecto);

    // ID del nuevo proyecto
    var inputNuevoProyecto = document.querySelector('#nuevo-proyecto');

    // cuando ponen enter, agregamos el proyecto
    inputNuevoProyecto.addEventListener('keypress', function(e) {
        var tecla = e.which || e.keyCode;
        if (tecla == 13) {
            guardarProyectoDB(inputNuevoProyecto.value);
            document.querySelector('ul#proyectos').removeChild(nuevoProyecto);
        }
    })
}

function guardarProyectoDB(nombreProyecto) {

    var xhr = new XMLHttpRequest();

    var datos = new FormData();
    datos.append('proyecto', nombreProyecto);
    datos.append('accion', 'crear');

    xhr.open('POST', 'inc/modelos/modelo-proyecto.php', true);

    xhr.onload = function() {
        if (this.status === 200) {
            var respuesta = JSON.parse(xhr.responseText);
            var proyecto = respuesta.nombre_proyecto,
                id_proyecto = respuesta.id_insertado,
                tipo = respuesta.tipo,
                resultado = respuesta.respuesta;

            // comprobar que se inserto a la BD
            if (resultado === 'correcto') {
                if (tipo === 'crear') {
                    // Inyectar HTML
                    var nuevoProyecto = document.createElement('li');
                    nuevoProyecto.innerHTML = `
                        <a href="index.php?id_respuesta=${id_proyecto}" id="proyecto:${id_proyecto}">${proyecto}</a>
                    `;
                    document.querySelector('ul#proyectos').appendChild(nuevoProyecto);

                    // ALERTA
                    swal({
                            title: 'Proyecto creado con exito',
                            text: `El proyecto "${proyecto}" fue agregado a tu lista`,
                            type: 'success'
                        })
                        .then(resultado => {
                            if (resultado.value) {
                                // Redireccionar al proyecto
                                window.location.href = 'index.php?id_proyecto=' + id_proyecto;
                            }
                        })


                } else {
                    // se actualizo o elimino
                }
            } else {
                swal({
                    title: 'Error al crear proyecto',
                    text: 'Ha habido un error',
                    type: 'error'
                })
            }
        }

    }

    xhr.send(datos);
}

function nuevaTarea(e) {
    e.preventDefault();
    var nombreTarea = document.querySelector('.nombre-tarea').value;
    var idProyectoo = document.querySelector('#id_proyecto').value;

    if (nombreTarea === "") {
        swal({
            type: 'error',
            title: 'Error',
            text: 'No incluiste el nombre de la nueva tarea'
        });
    } else {
        var xhr = new XMLHttpRequest();
        var datos = new FormData();
        datos.append('tarea', nombreTarea);
        datos.append('accion', 'crear');
        datos.append('id_proyecto', idProyectoo);

        xhr.open('POST', 'inc/modelos/modelo-tareas.php', true);

        xhr.onload = function() {
            if (this.status === 200) {
                var respuesta = JSON.parse(xhr.responseText);
                var resultado = respuesta.respuesta,
                    tarea = respuesta.tarea,
                    id_insertado = respuesta.id_insertado,
                    tipo = respuesta.tipo;

                if (resultado === 'correcto') {
                    if (tipo === 'crear') {
                        swal({
                            type: 'success',
                            title: 'UHRRA!',
                            text: 'Su tarea fue agregada con exito!'
                        });

                        // parrafo que infica que no hay tareas
                        var parrafoListaVacia = document.querySelectorAll('.lista-vacia');
                        if (parrafoListaVacia.length > 0) {
                            document.querySelector('.lista-vacia').remove();
                        }

                        var nuevaTarea = document.createElement('li');
                        nuevaTarea.id = 'tarea:' + id_insertado;
                        nuevaTarea.classList.add('tarea');
                        nuevaTarea.innerHTML = `
                            <p>${tarea}</p>
                            <div class="acciones">
                                <i class="far fa-check-circle "></i>
                                <i class="fas fa-trash"></i>
                            </div>
                        `;
                        var listado = document.querySelector('.listado-pendientes ul');
                        listado.appendChild(nuevaTarea);

                        //limpiar form
                        document.querySelector('.agregar-tarea').reset();
                        actualizarProgreso();
                    }
                } else {
                    swal({
                        type: 'error',
                        title: 'Error',
                        text: 'Ha habido un error'
                    });
                }
            }
        }
        xhr.send(datos);

    }
}

function accionesTareas(e) {
    e.preventDefault();
    // ejemplo de delegation
    if (e.target.classList.contains('fa-check-circle')) {
        if (e.target.classList.contains('completo')) {
            e.target.classList.remove('completo');
            cambiarEstadoTarea(e.target, 0);
        } else {
            e.target.classList.add('completo');
            cambiarEstadoTarea(e.target, 1);
        }
    }
    if (e.target.classList.contains('fa-trash')) {
        Swal.fire({
            title: 'Estas seguro?',
            text: "Esta accion no se puede deshacer",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Si, eliminar'
        }).then((result) => {
            if (result.value) {
                var tareaEliminar = e.target.parentElement.parentElement;
                // borrar del html
                tareaEliminar.remove();
                // borrar de la DB
                eliminarTareaBD(tareaEliminar);
            }
        })
    }
}

function cambiarEstadoTarea(tarea, estado) {
    idTarea = tarea.parentElement.parentElement.id.split(':');
    idTarea = idTarea[1];

    var xhr = new XMLHttpRequest;

    var datos = new FormData();
    datos.append('id', idTarea);
    datos.append('accion', 'actualizar');
    datos.append('estado', estado);

    xhr.open('POST', 'inc/modelos/modelo-tareas.php', true);

    xhr.onload = function() {
        if (this.status === 200) {
            actualizarProgreso();
        }
    }

    xhr.send(datos);

}

function eliminarTareaBD(tarea) {
    idTarea = tarea.id.split(':');
    idTarea = idTarea[1];

    var xhr = new XMLHttpRequest();

    var datos = new FormData();
    datos.append('id', idTarea);
    datos.append('accion', 'eliminar');

    xhr.open('POST', 'inc/modelos/modelo-tareas.php', true);

    xhr.onload = function() {
        if (this.status === 200) {

            var listaTareasRestantes = document.querySelectorAll('li.tarea');
            if (listaTareasRestantes.length === 0) {
                document.querySelector('.listado-pendientes ul').innerHTML =
                    '<p class="lista-vacia">Este proyecto no tiene tareas.</p>';
                document.querySelector('.avance').remove();
            }
            actualizarProgreso();
        }
    }
    xhr.send(datos);
}

function actualizarProgreso() {
    const tareas = document.querySelectorAll('li.tarea');
    const tareasCompletadas = document.querySelectorAll('i.completo');
    const avance = Math.round((tareasCompletadas.length / tareas.length) * 100);

    // asignar el avance a la barra
    const porcentaje = document.querySelector('#porcentaje');
    porcentaje.style.width = avance + '%';
}

function eliminarProyecto(e) {
    if (e.target.classList.contains('fa-trash')) {
        Swal.fire({
            title: 'Estas seguro?',
            text: "Esta accion no se puede deshacer",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Si, eliminar'
        }).then((result) => {
            if (result.value) {
                var proyectoEliminar = e.target.parentElement;
                // borrar del html
                proyectoEliminar.remove();
                // borrar de la DB
                eliminarProyectoBD(proyectoEliminar);
            }
        })
    }
}

function eliminarProyectoBD(proyecto) {
    idProyecto = proyecto.id.split(':');
    idProyecto = idProyecto[1];

    var xhr = new XMLHttpRequest();

    var datos = new FormData();
    datos.append('id', idProyecto);
    datos.append('accion', 'eliminar');

    xhr.open('POST', 'inc/modelos/modelo-proyecto.php', true);


    xhr.onload = function() {
        if (this.status === 200) {

            var listaProyectosRestantes = document.querySelectorAll('li.proyecto-individual');
            if (listaProyectosRestantes.length === 0) {
                document.querySelector('.listado-proyectos').innerHTML =
                    '<p class="lista-vacia">Todavia no tienes proyectos.</p>';
            }
        }
    }
    xhr.send(datos);
}