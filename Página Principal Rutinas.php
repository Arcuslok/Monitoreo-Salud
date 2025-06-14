<!DOCTYPE html>
<?php
require_once 'funciones/Funciones SQL.php';
require_once 'funciones/Funciones PHP.php';

session_start(); // Iniciar la sesión

$_SESSION['pagina_previa'] = $_SERVER['REQUEST_URI'];

// Verificar si la sesión está activa
if (!isset($_SESSION['Datos']) || $_SESSION['Datos']['Entrenador_autenticado'] !== true) {
    // Redirigir al inicio de sesión si no hay sesión iniciada
    header("Location: Login Entrenador.php");
    exit();
}

// Recuperar datos del formulario de la sesión si existen
if (isset($_SESSION['Datos'])) {
    $datos = $_SESSION['Datos'];
    $entrenador = $datos['Correo_entrenador'];
    $clave = $datos['Clave_entrenador'];

    $conn = Conectar_Base_Datos();

    // Consulta para obtener los datos del usuario
    $Consul = "SELECT Nombre_entrenador, Apellido_entrenador FROM entrenador WHERE Correo_entrenador = '$entrenador'";

    $resultado = mysqli_query($conn, $Consul);
    if (mysqli_num_rows($resultado)) {
        $fila = mysqli_fetch_array($resultado);
        $Nombre = $fila['Nombre_entrenador'];
        $Apellido = $fila['Apellido_entrenador'];

        // Actualizar los datos en la sesión
        $_SESSION['Datos'] = [
            'Correo_entrenador' => $entrenador,
            'Clave_entrenador' => $clave,
            'Entrenador_autenticado' => true,
            'Nombre_entrenador' => $Nombre,
            'Apellido_entrenador' => $Apellido
        ];
    }

}

?>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="css/principal.css">
    <title>Administrador de Usuarios para Entrenadores</title>
    <style>
        .form-container label {
            padding: 5px;
            margin-bottom: 5px;
            font-weight: bold;
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
                            <li><a class="dropdown-item" href="Editar Perfil Entrenador.php">Editar Perfil</a></li>
                            <li><a class="dropdown-item" href="principal/cerrar_sesion_entrenador.php">Cerrar Sesión</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Página Principal Ejercicios.php">Ejercicios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Página Principal Entrenador.php">Usuarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Página Principal Rutinas.php">Rutinas</a>
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
                    <h1 class="text-center mb-4">Rutinas</h1>
                    <div class="row">
                        <div class="col-md-4">
                            <div style="display: flex; align-items: center;">
                                <h4 style="margin: 0; white-space: nowrap;">Rutinas de</h4>
                                <?php
                                $Rut = Obtener_Entrenador_Rut($conn, $entrenador);
                                $usuarios = Obtener_Entrenadores_Usuarios($conn, $Rut);

                                if ($usuarios) {
                                    echo "<select id='usuarios' name='usuarios' class='form-select' style='margin-left: 10px;' onchange='mostrarRutinasUsuario(this.value)'>";
                                    echo "<option value=''>Seleccione un usuario</option>"; // Opción por defecto
                                    foreach ($usuarios as $usuario) {
                                        $rut = $usuario["Rut_usuario"];
                                        $formatear_rut = formatearRUT($rut);
                                        $nombre_completo = $usuario["Nombre_usuario"] . " " . $usuario["Apellido_usuario"];
                                        echo "<option value='$rut'>$nombre_completo</option>";
                                    }
                                    echo "</select>";
                                } else {
                                    echo "<span style='margin-left: 10px;'>No hay Usuarios registrados</span>";
                                }
                                ?>
                            </div>

                            <ul id="rutinasList" class="list-group exercise-list">
                                <br>
                                <li class="list-group-item">Seleccione un usuario para ver sus rutinas.</li>
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
                                                    <input type="text" class="form-control" id="nombreRutina" autocomplete="off" required>
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
                                        $ejercicios = Obtener_Ejercicios($conn);
                                        foreach ($ejercicios as $ejercicio) {
                                            $id = Obtener_Id_Ejericio($conn, $ejercicio);
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
            <div class="row"> 
                <div class="col-md-6"> 
                    <p>© 2024 SaludTracker. All rights reserved.</p> 
                </div> 
            </div>
        </div> 
    </footer>
  
    <script>

        function mostrarRutinasUsuario(rutUsuario) {
            //console.log("Usuario seleccionado:", rutUsuario); // Confirmar si se está llamando la función
            const rutinasList = document.getElementById("rutinasList");

            if (rutUsuario === "") {
                rutinasList.innerHTML = "<br><li class='list-group-item'>Seleccione un usuario para ver sus rutinas.</li>";
                return; // Salir de la función
            }

            // Realiza la solicitud para obtener las rutinas del usuario seleccionado
            fetch(`principal/obtener_rutinas.php?rutUsuario=${rutUsuario}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al obtener rutinas');
                }
                return response.json(); // Asegúrate de que se esté devolviendo JSON
            })
            .then(data => {
                console.log(data); // Verificar la respuesta del servidor
                const rutinas = data.rutinas; // Asegúrate de que `rutinas` esté definido
                rutinasList.innerHTML = "<br>"; // Limpia la lista antes de agregar nuevos elementos

                if (rutinas && rutinas.length > 0) {
                    rutinas.forEach(rutina => {
                        const li = document.createElement('li');
                        li.className = 'list-group-item';
                        li.textContent = rutina; // Cambia `nombre` por el campo correcto
                        
                        // Aquí agregas el evento onclick que llama a cargarEjercicios
                        li.onclick = function() {
                            cargarEjercicios(rutina, rutUsuario); // Llama a la función con la rutina
                            // Almacena la rutina seleccionada en un input oculto o similar
                            document.getElementById('rutinaSeleccionada').value = rutina; // Almacenar en un input oculto
                        };

                        rutinasList.appendChild(li);
                    });
                } else {
                    rutinasList.innerHTML = "<li class='list-group-item'>No hay rutinas disponibles.</li>";
                }
            })
            .catch(error => {
                console.error(error);
                alert('No se pudieron cargar las rutinas. Intenta nuevamente.');
            });
        }


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
                cargarEjercicios(nombreRutina);  // Cargar ejercicios para la rutina seleccionada
                document.getElementById('rutinaSeleccionada').value = nombreRutina;  // Guardar la rutina seleccionada
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
        function cargarEjercicios(rutina, rutUsuario) {

            fetch(`principal/obtener_ejercicios_usuario.php?rut_usuario=${rutUsuario}&rutina=${rutina}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al obtener ejercicios');
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }

                const ejercicios = data.ejercicios; // Asegúrate de que estás accediendo a 'ejercicios'
                const tbody = document.getElementById("tbodyEjercicios");
                tbody.innerHTML = ''; // Limpiar el tbody antes de agregar nuevos ejercicios

                if (!ejercicios || ejercicios.length === 0) {
                    tbody.innerHTML = "<tr><td colspan='4' class='text-center'>No hay ejercicios disponibles.</td></tr>";
                    return;
                }

                ejercicios.forEach(ejercicio => {
                    const tr = document.createElement('tr');
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

            const rut_usuario = document.getElementById('usuarios').value;

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
            const url = `principal/guardar_ejercicio_usuario.php?rut_usuario=${rut_usuario}&id=${id}&rutina=${rutina}&nombre=${nombre}&repeticiones=${repeticiones}&series=${series}`;

            fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Ejercicio guardado exitosamente.");
                    // Llamar a la función para actualizar la tabla con el nuevo ejercicio
                    cargarEjercicios(rutina, rut_usuario);
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

            console.log("Rutina seleccionada:", rutina);  // Agrega este log para verificar la rutina seleccionada.

            if (!rutina) {
                alert('Por favor, selecciona una rutina antes de añadir un ejercicio.');
                return; // Evita que se ejecute el resto del código si no hay rutina seleccionada
            }
            
            const rut_usuario = document.getElementById('usuarios').value;
            const nombre = document.getElementById('nuevoEjercicio').value;
            const repeticiones = document.getElementById('nuevasRepeticiones').value;
            const series = document.getElementById('nuevasSeries').value;

            const url = `principal/agregar_ejercicio_usuario.php?rut_usuario=${encodeURIComponent(rut_usuario)}&rutina=${encodeURIComponent(rutina)}&nombre=${encodeURIComponent(nombre)}&repeticiones=${encodeURIComponent(repeticiones)}&series=${encodeURIComponent(series)}`;

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
                    // Aquí podrías también actualizar la tabla de ejercicios para mostrar el nuevo ejercicio
                    cargarEjercicios(rutina, rut_usuario);
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

            const rut_usuario = document.getElementById('usuarios').value;

            // Obtener el ID del ejercicio a partir del select
            const selectInput = tr.querySelector('select');
            const idEjercicio = selectInput ? selectInput.id.split('-')[1] : null;

            // Validar que el ID exista
            if (!idEjercicio) {
                alert("Error: no se encontró el ID del ejercicio.");
                return; // Evitar continuar si no se encuentra el ID
            }

            const url = `principal/eliminar_ejercicio_usuario.php?rut_usuario=${encodeURIComponent(rut_usuario)}&id=${encodeURIComponent(idEjercicio)}`;

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
