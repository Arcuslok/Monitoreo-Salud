<!DOCTYPE html>
<?php
require_once 'funciones/Funciones PHP.php';
require_once 'funciones/Funciones SQL.php';

session_start();

$_SESSION['pagina_previa'] = $_SERVER['REQUEST_URI'];

// Verificar si el usuario está autenticado
if (!isset($_SESSION['Datos']) || !$_SESSION['Datos']['Usuario_autenticado']) {
    header('Location: Login Usuario.php'); // Redirige a la página de inicio de sesión
    exit();
}

// Procesar la solicitud de cierre de sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'logout') {
    $_SESSION['Datos'] = [
        'Usuario_autenticado' => false
    ];
    header("Location: Login Usuario.php"); // Redirige a la página de inicio de sesión
    exit();
}

// Recuperar datos del formulario de la sesión si existen
if (isset($_SESSION['Datos'])) {
    $Datos = $_SESSION['Datos'];
    $Correo = $Datos['Correo_usuario'];
    $Clave = $Datos['Clave_usuario'];
    $Estado_Pulsera = $Datos['Estado_Pulsera_usuario'];
    $Estado_Pulso = $Datos['Estado_Pulso_usuario'];
    $Estado_Pasos = $Datos['Estado_Pasos_usuario'];
    $Estado_Temperatura = $Datos['Estado_Temperatura_usuario'];
    $Autenticado = $Datos['Usuario_autenticado'];

    $Conn = Conectar_Base_Datos();
    $Rut = Obtener_Rut_Usuario($Conn, $Correo);  // Obtener RUT una vez, se usa varias veces

    // Actualización de estados
    if ($Estado_Pulsera) {
        Establecer_Estado_Pulsera($Conn, $Rut, false);
        $Estado_Pulsera = false;
    }

    if ($Estado_Pulso) {
        Establecer_Estado_Pulso($Conn, $Rut, false);
        $Estado_Pulso = false;
    }

    if ($Estado_Pasos) {
        Establecer_Estado_Pasos($Conn, $Rut, false);
        $Estado_Pasos = false;
    }

    if ($Estado_Temperatura) {
        Establecer_Estado_Temperatura($Conn, $Rut, false);
        $Estado_Temperatura = false;
    }

    // Consulta para obtener los datos del usuario
    $Consul = "SELECT usuario.Rut_usuario, usuario.Nombre_usuario, usuario.Apellido_usuario
               FROM usuario WHERE usuario.Correo_usuario = '$Correo'";

    $resultado = mysqli_query($Conn, $Consul);

    if (mysqli_num_rows($resultado)) {
        $fila = mysqli_fetch_array($resultado);
        $Rut = $fila['Rut_usuario'];
        $Nombre = $fila['Nombre_usuario'];
        $Apellido = $fila['Apellido_usuario'];

        // Actualizar los datos en la sesión
        $_SESSION['Datos'] = [
            'Correo_usuario' => $Correo,
            'Clave_usuario' => $Clave,
            'Estado_Pulsera_usuario' => $Estado_Pulsera,
            'Estado_Pulso_usuario' => $Estado_Pulso,
            'Estado_Pasos_usuario' => $Estado_Pasos,
            'Estado_Temperatura_usuario' => $Estado_Temperatura,
            'Usuario_autenticado' => true,
            'Nombre_usuario' => $Nombre,
            'Apellido_usuario' => $Apellido
        ];
    }
}

?>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Página Rutina</title>
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="css/principal.css">
        <style>
            .centered-content {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }

            .circle {
                display: flex;
                justify-content: center;
                align-items: center;
                width: 100px;
                height: 100px;
                border-radius: 50%;
                background-color: #f0f0f0;
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand">
                    <?php
                    $genero = identificar_genero($Nombre);
                    if ($genero == "Masculino") {
                        echo "<img src='img/hombre.jpg' width='50' height='50'>";
                    } else {
                        echo "<img src='img/mujer.jpg' width='50' height='50'>";
                    }
                    ?>
                </a>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <?php
                            echo "<a class='navbar-brand' role='button' data-bs-toggle='dropdown'><span class='ml-2'>". $Nombre ." ". $Apellido . "</span></a>";
                            ?>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="Editar Perfil.php">Editar Perfil</a></li>
                                <li><a class="dropdown-item" href="principal/cerrar_sesion.php">Cerrar Sesión</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Página Principal Estadistica.php">Estadísticas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Página Principal Usuario.php">Aplicación</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Página Principal Rutina.php">Rutinas</a>
                        </li>
                    </ul>
                </div>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </nav>
        
        <!-- Sección principal -->
        <section class="jumbotron d-flex justify-content-center align-items-center" style="height: 100vh;">
            <div class="container">
                <div class="card text-center">
                    <div class="card-body">
                        <h1 class="text-center mb-4">Editar Rutina</h1>
                        <div class="row">
                            <div class="col-md-4">
                                <h4>Tu Rutina</h4>
                                <ul class="list-group exercise-list">
                                    <?php
                                    $rutinas = Obtener_Rutinas_Usuario($Conn, $Rut);
                                    if (!empty($rutinas)) {
                                        foreach ($rutinas as $rutina) {
                                            echo "<li class='list-group-item' onclick='cargarEjercicios(\"$rutina\")' style='cursor: pointer;'>$rutina</li>"; 
                                        } 
                                    } else {
                                        echo "<li class='list-group-item'>No presenta ninguna rutina actualmente</li>"; 
                                    }
                                    ?>
                                </ul>
                                <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#añadirRutinaModal">Añadir Rutina</button>
                                <input type="hidden" id="rutinaSeleccionada" value="">

                                <!-- Modal para añadir nueva rutina -->
                                <div class="modal fade" id="añadirRutinaModal" tabindex="-1" aria-labelledby="añadirRutinaModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="añadirRutinaModalLabel">Nueva Rutina</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form id="formNuevaRutina">
                                                    <div class="mb-3">
                                                        <label for="nombreRutina" class="form-label">Nombre de la Rutina</label>
                                                        <input type="text" class="form-control" id="nombreRutina"  autocomplete="off" required>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="button" class="btn btn-primary" onclick="añadirRutina()">Añadir Rutina</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <h4>Ejercicios, Repeticiones y Series</h4>
                                <form method="POST" id="formEjercicios">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Ejercicio</th>
                                                <th>Repeticiones</th>
                                                <th>Series</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbodyEjercicios">
                                            <!-- Aquí se cargarán los ejercicios -->
                                            <tr><td colspan='4' class='text-center'>Ninguna rutina seleccionada</td></tr>
                                        </tbody>
                                    </table>
                                    <div class="input-group mb-3">

                                        <select class="form-control" id="nuevoEjercicio" style='text-align: center;'>
                                            <?php
                                            $ejercicios = Obtener_Ejercicios($Conn);
                                            foreach ($ejercicios as $ejercicio) {
                                                $id = Obtener_Id_Ejericio($Conn, $ejercicio);
                                                echo "<option value=".$id.">".$ejercicio."</option>";
                                            } 
                                            ?>
                                        </select>
                                        <input type="number" class="form-control" id="nuevasRepeticiones" style='text-align: center;' placeholder="Repeticiones"  value="0">
                                        <input type="number" class="form-control" id="nuevasSeries" style='text-align: center;' placeholder="Series" value="0">
                                        <button type="button" class="btn btn-success" id="btnGuardarEjercicio">Agregar Ejercicio</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <footer class="footer p-5"> 
            <div class="container">
                <div class="row"> 
                    <div class="col-md-3"> 
                        <p>SaludTracker</p> 
                    </div> 
                    <div class="col-md-4"> 
                        <p>Contactos</p> 
                        <ul class="list-unstyled"> 
                            <p>Gmail: saludtracker@gmail.com</p> 
                            <p>Phone:<br> 600 458 5682</p> 
                        </ul>  
                    </div> 
                    <div class="col-md-4"> 
                        <p>Redes Sociales</p> 
                        <ul class="list-unstyled"> 
                            <p><a href="">Instagram</a></p> 
                        </ul> 
                    </div> 
                </div> 
                <hr> 
                <div class="text-center"> 
                    <p>© 2024. Todos los derechos reservados</p> 
                </div> 
            </div> 
        </footer>

        <script>

            function añadirRutina() {
                const nombreRutina = document.getElementById('nombreRutina').value;

                // Validar el nombre de la rutina
                if (nombreRutina.trim() === '') {
                    alert('Por favor, ingrese un nombre válido para la rutina.');
                    return;
                }

                // Crear un nuevo elemento de lista para la rutina
                const nuevaRutina = document.createElement('li');
                nuevaRutina.classList.add('list-group-item');
                nuevaRutina.textContent = nombreRutina;
                nuevaRutina.style.cursor = 'pointer';

                // Agregar evento onclick para cargar ejercicios en la nueva rutina
                nuevaRutina.onclick = function () {
                    cargarEjercicios(nombreRutina);
                };

                // Añadir la nueva rutina a la lista de rutinas
                const listaRutinas = document.querySelector('.exercise-list');
                listaRutinas.appendChild(nuevaRutina);

                // Limpiar el campo de nombre de la rutina
                document.getElementById('nombreRutina').value = '';

                // Cerrar el modal (si estás usando uno)
                const modal = document.getElementById('añadirRutinaModal');
                if (modal) {
                    modal.classList.remove('show');
                    document.querySelector('.modal-backdrop').remove();
                }

                alert('Rutina añadida con éxito.');
            }

            // Función para cargar los ejercicios de la rutina seleccionada
            function cargarEjercicios(rutina) {

                // Actualiza el input hidden con la rutina seleccionada
                document.getElementById('rutinaSeleccionada').value = rutina;

                let tbody = document.querySelector('tbody');
                tbody.innerHTML = ''; // Limpiar la tabla

                // Realizar una solicitud AJAX para obtener los ejercicios
                fetch(`principal/obtener_ejercicios.php?rutina=${rutina}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al obtener ejercicios');
                    }
                    return response.json();
                })
                .then(data => {
                    // Comprobar si hay errores en la respuesta
                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    const ejercicios = data.ejercicios; // Asegúrate de que estás accediendo a 'ejercicios'
                    // Agregar los ejercicios a la tabla
                    
                    ejercicios.forEach(ejercicio => {
                        const tr = document.createElement('tr');

                        // Generar las opciones del select, pero asegurarte de que la opción correspondiente al ejercicio actual esté seleccionada
                        const selectHTML = data.ejercicios.map(e => `
                            <option value="${e.nombre}" ${e.nombre === ejercicio.nombre ? 'selected' : ''} data-toggle="tooltip" title="${e.descripcion}">

                                ${e.nombre}
                            </option>
                        `).join('');

                        tr.innerHTML = `
                            <td>
                                <select id="nombre-${ejercicio.id}" class="form-control">
                                    ${selectHTML}
                                </select>
                            </td>
                            <td>
                                <input type="number" id="repeticiones-${ejercicio.id}" value="${ejercicio.repeticiones}" class="form-control" style='text-align: center;'>
                            </td>
                            <td>
                                <input type="number" id="series-${ejercicio.id}" value="${ejercicio.series}" class="form-control" style='text-align: center;'>
                            </td>
                            <td>
                                <button type="button" class="btn btn-primary" onclick="guardarEjercicio(this)">Guardar</button>
                                <button type="button" class="btn btn-danger" onclick="eliminarEjercicio(this)">Eliminar</button>
                            </td>
                        `;
                        tbody.appendChild(tr);
                    });
                

                })
                .catch(error => {
                    console.error(error);
                    alert('No se pudieron cargar los ejercicios. Intenta nuevamente.');
                });

            }

            function guardarEjercicio(button) {
                // Obtener la fila del botón
                const tr = button.closest('tr');

                // Obtener el ID del ejercicio a partir del select
                const selectInput = tr.querySelector('select');
                const id = selectInput ? selectInput.id.split('-')[1] : null;

                // Validar que el ID exista
                if (!id) {
                    alert("Error: no se encontró el ID del ejercicio.");
                    return;
                }

                // Obtener los valores de repeticiones y series
                const repeticionesInput = document.getElementById(`repeticiones-${id}`);
                const seriesInput = document.getElementById(`series-${id}`);

                if (!repeticionesInput || !seriesInput) {
                    alert("Error: no se encontraron los campos de repeticiones o series.");
                    return;
                }

                const repeticiones = repeticionesInput.value;
                const series = seriesInput.value;

                // Validar que los campos no estén vacíos o sean 0
                if (!repeticiones || !series) {
                    alert("Por favor, completa todos los campos.");
                    return;
                }

                if (repeticiones == 0 || series == 0) {
                    alert("Las repeticiones o las series no pueden ser 0.");
                    return;
                }

                // Obtener el nombre del ejercicio
                const nombre = selectInput.value;

                // Obtener los valores de rutina y rut de otros elementos
                const rutina = document.getElementById('rutinaSeleccionada').value;

                // Hacer una llamada AJAX para guardar el ejercicio en la base de datos
                const url = `principal/guardar_ejercicio.php?id=${id}&rutina=${rutina}&nombre=${nombre}&repeticiones=${repeticiones}&series=${series}`;

                fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Ejercicio guardado exitosamente.");
                        cargarEjercicios(rutina);
                        // Reiniciar los valores de los campos de entrada a 0
                        document.getElementById('nuevasRepeticiones').value = 0;
                        document.getElementById('nuevasSeries').value = 0;
                    } else {
                        alert("Error al guardar el ejercicio: " + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert("Error al guardar el ejercicio.");
                });
            }

            document.getElementById('btnGuardarEjercicio').addEventListener('click', function() {

                const rutina = document.getElementById('rutinaSeleccionada').value;

                if (!rutina) {
                    alert('Por favor, selecciona una rutina antes de añadir un ejercicio.');
                    return; // Evita que se ejecute el resto del código si no hay rutina seleccionada
                }

                const nombre = document.getElementById('nuevoEjercicio').value;
                const repeticiones = document.getElementById('nuevasRepeticiones').value;
                const series = document.getElementById('nuevasSeries').value;

                const url = `principal/agregar_ejercicio.php?rutina=${encodeURIComponent(rutina)}&nombre=${encodeURIComponent(nombre)}&repeticiones=${encodeURIComponent(repeticiones)}&series=${encodeURIComponent(series)}`;

                fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor: ' + response.status);  // Lanza error si la respuesta no es 2xx
                    }
                    return response.json();  // Procesa el JSON si la respuesta es válida
                })
                .then(data => {
                    if (data.success) {
                        alert("Ejercicio agregado exitosamente.");
                        cargarEjercicios(rutina);
                        // Reiniciar los valores de los campos de entrada a 0
                        document.getElementById('nuevasRepeticiones').value = 0;
                        document.getElementById('nuevasSeries').value = 0;
                    } else {
                        alert("Error al agregar el ejercicio: " + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert("Error al agregar el ejercicio. Verifica la consola para más detalles.");
                });

            });

            function eliminarEjercicio(button) {
                // Obtener la fila del botón
                const tr = button.closest('tr');

                // Obtener el ID del ejercicio a partir del select
                const selectInput = tr.querySelector('select');
                const idEjercicio = selectInput ? selectInput.id.split('-')[1] : null;

                // Validar que el ID exista
                if (!idEjercicio) {
                    alert("Error: no se encontró el ID del ejercicio.");
                    return; // Evitar continuar si no se encuentra el ID
                }

                const url = `principal/eliminar_ejercicio.php?id=${encodeURIComponent(idEjercicio)}`;

                fetch(url, { method: 'DELETE' }) // Usar el método DELETE
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor: ' + response.status);
                    }
                    return response.json(); // Convertir la respuesta en JSON
                })
                .then(data => {
                    if (data.success) {
                        alert("Ejercicio eliminado exitosamente.");
                        // Eliminar la fila completa en la interfaz
                        tr.remove();  // Esto elimina toda la fila <tr>, no solo los botones
                    } else {
                        alert("Error al eliminar el ejercicio: " + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert("Error al eliminar el ejercicio.");
                });
            }

        </script>
    </body>
</html>
