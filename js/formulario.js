eventListeners();

function eventListeners() {
    document.querySelector('#formulario').addEventListener('submit', validarRegistro);
}

function validarRegistro(e) {
    e.preventDefault();

    var usuario = document.querySelector('#usuario').value,
        password = document.querySelector('#password').value,
        tipo = document.querySelector('#tipo').value;

    if (usuario === '' || password === '') {
        Swal.fire({
            type: 'error',
            title: 'Oops...',
            text: 'Ambos campos son obligatorios',
        })
    } else {
        // ambos campos tienen algo

        // datos a enviar al server
        var datos = new FormData();
        datos.append('usuario', usuario);
        datos.append('password', password);
        datos.append('accion', tipo);

        // ajax llamado a
        var xhr = new XMLHttpRequest();

        // abrir conexion
        xhr.open('POST', 'inc/modelos/modelo-admin.php', true);

        // retorno de datos
        xhr.onload = function() {
            if (this.status === 200) {
                var respuesta = JSON.parse(xhr.responseText);

                if (respuesta.respuesta === 'correcto') {
                    if (respuesta.tipo === 'crear') {
                        swal({
                                title: 'Usuario creado',
                                text: 'El usuario fue creado con exito',
                                type: 'success'
                            })
                            .then(resultado => {
                                console.log(resultado);
                                if (resultado.value === true) {
                                    window.location.href = 'index.php';
                                }
                            })
                    } else if (respuesta.tipo === 'login') {
                        swal({
                                title: 'Sesion iniciada',
                                text: 'Haz iniciado sesion exitosamente',
                                type: 'success'
                            })
                            .then(resultado => {
                                console.log(resultado);
                                if (resultado.value === true) {
                                    window.location.href = 'index.php';
                                }

                            })
                    }
                } else if (respuesta.respuesta === 'error') {
                    if (respuesta.tipo === 'login') {
                        swal({
                            title: respuesta.titulomensaje,
                            text: 'Por favor chequee sus datos',
                            type: 'error'
                        })
                    } else if (respuesta.tipo === 'crear') {
                        swal({
                            title: 'Usuario existente',
                            text: 'Por favor introduzca otro usuario',
                            type: 'error'
                        })
                    }
                }
            }
        }

        // enviar peticion
        xhr.send(datos);

    }
}