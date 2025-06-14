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
    $Consul = "SELECT usuario.Rut_usuario, usuario.Nombre_usuario, usuario.Apellido_usuario FROM usuario WHERE usuario.Correo_usuario = '$Correo'";

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
        <title>Página Estadistica</title>
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="chart/chart.umd.js"></script>
        <link rel="stylesheet" href="css/principal.css">
        <style>
        .centered-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .card {
            position: relative;
            max-width: 1000px;
            min-height: 400px;
            margin: 20px auto;
            padding: 20px;
        }

        .graficos-estadisticas {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 30px;
            margin-top: 20px;
        }

        .graficos-estadisticas > div {
            flex: 1;
        }

        .estadisticas {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 300px;
        }

        canvas {
            width: 100% !important;
            height: 400px !important;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand">
                <?php
                $genero = identificar_genero($Nombre);
                echo $genero == "Masculino" ? "<img src='img/hombre.jpg' width='50' height='50'>" : "<img src='img/mujer.jpg' width='50' height='50'>";
                ?>
            </a>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="navbar-brand" role="button" data-bs-toggle="dropdown">
                            <?php echo "<span class='ml-2'>". $Nombre ." ". $Apellido . "</span>"; ?>
                        </a>
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
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <!-- Sección principal -->
    <section class="jumbotron d-flex justify-content-center align-items-center" style="height: 100vh; background-color: #f8f9fa;">
        <div class="card shadow">
            <div class="card-body">
                <h1 class="card-title text-center">Tu progreso</h1>
                <hr>
                <div>
                    <h3>Estadísticas y Gráficas</h3>
                    <div>
                    <label for="selectFechas">Selecciona una fecha:</label>
                    <select id="selectFechas" onchange="mostrarRegistrosPorFecha()">
                        <option value="">-- Selecciona una fecha --</option>
                    </select>
                    <label for="selectGrafico">Selecciona el gráfico:</label>
                        <select id="selectGrafico" onchange="cambiarGrafico()">
                            <option value="pulsacionesChart">Pulsaciones</option>
                            <option value="temperaturaChart">Temperatura</option>
                        </select>
                    </div>
                    <div class="graficos-grid">
                        <canvas id="pulsacionesChart"></canvas>
                        <canvas id="temperaturaChart" style="display: none;"></canvas>
                    </div>
                    <div id="estadisticas"></div>
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

        // Variable para almacenar registros subclasificados
        let subclasificados_fechas = {};

        // Función para inicializar la página
        window.onload = function () {
            obtenerRegistrosSubclasificados();
        };

        function subclasificarYOrdenarRegistros(registros) {

            // Agrupar los registros por fecha y luego por ejercicio
            registros.forEach(registro => {

                var fecha = registro.fecha;
                var ejercicio = registro.ejercicio;

                let fecha_registro = fecha.split(' ')[0];

                if (!subclasificados_fechas[fecha_registro]) {
                    subclasificados_fechas[fecha_registro] = [];
                }
                  
                if (!subclasificados_fechas[fecha_registro]['ejercicios']) {
                    subclasificados_fechas[fecha_registro]['ejercicios'] = [];
                }
                
                if (!subclasificados_fechas[fecha_registro]['datos']) {
                    subclasificados_fechas[fecha_registro]['datos'] = [];
                }
                
                if (!subclasificados_fechas[fecha_registro]['ejercicios'].some(ejercicio_registrado => ejercicio_registrado === ejercicio)) {
                    subclasificados_fechas[fecha_registro]['ejercicios'].push(ejercicio);
                }
                
                subclasificados_fechas[fecha_registro]['datos'].push(registro);

            });

        }

        // Función para obtener registros subclasificados desde el servidor
        function obtenerRegistrosSubclasificados() {
            fetch('principal/obtener_registros_pulsera.php')
            .then(response => response.json())
            .then(data => {

                if (data.error) {
                    console.error('Error:', data.error);
                    return;
                }

                const registros = data.registros || [];

                if (registros.length === 0) {
                    console.warn('No se encontraron datos de progreso');
                    return;
                }

                subclasificarYOrdenarRegistros(registros);

                mostrarOpcionesFechas();

            })
            .catch(error => {
                console.error('Error al obtener registros:', error);
            });
        }

        // Función para mostrar opciones en el selector de fechas
        function mostrarOpcionesFechas() {
            var selectFechas = document.getElementById('selectFechas');
            for (var fecha in subclasificados_fechas) {
                var option = document.createElement('option');
                option.value = fecha;
                option.text = fecha;
                selectFechas.appendChild(option);
            }
        }

        // Función para mostrar registros por fecha seleccionada
        function mostrarRegistrosPorFecha() {
            var selectFechas = document.getElementById('selectFechas');
            var fechaSeleccionada = selectFechas.value;

            // Si no hay fecha seleccionada, limpiar gráficos y estadísticas
            if (!fechaSeleccionada || !subclasificados_fechas[fechaSeleccionada]) {
                // Limpiar las estadísticas
                document.getElementById('estadisticas').innerHTML = '';

                // Limpiar los gráficos
                for (let id in charts) {
                    if (charts[id]) {
                        charts[id].destroy();
                        charts[id] = null;
                    }
                }
                console.warn('No se encontraron registros para la fecha seleccionada.');
                return;
            }

            // Extraer datos de pulsaciones y temperaturas para la fecha seleccionada
            var registros = subclasificados_fechas[fechaSeleccionada]['datos'];
            var ejercicios = subclasificados_fechas[fechaSeleccionada]['ejercicios'];


            var fechas = registros.map(item => item.fecha.split(' ')[1]);

            var pulsaciones = registros.map(item => item.pulsacion);
            var pasos = registros.map(item => item.paso);
            var calorias = registros.map(item => item.caloria);
            var temperaturas = registros.map(item => item.temperatura);

            // Mostrar estadísticas actualizadas
            mostrarEstadisticas(pulsaciones, pasos, calorias, temperaturas, ejercicios);

            // Actualizar gráficos
            crearGrafico('pulsacionesChart', 'Pulsaciones', pulsaciones, 'rgba(255, 99, 132, 1)', fechas);
            crearGrafico('temperaturaChart', 'Temperatura', temperaturas, 'rgba(54, 162, 235, 1)', fechas);
        }

        // Variable global para almacenar las instancias de los gráficos, si existen
        let charts = {};

        // Función para mostrar estadísticas en la página
        function mostrarEstadisticas(pulsaciones, pasos, calorias, temperaturas, ejercicios) {
            function calcularEstadisticas(valores) {
                if (valores.length === 0) {
                    return {
                        promedio: "N/A",
                        maximo: "N/A",
                        minimo: "N/A",
                        desviacion: "N/A"
                    };
                }
                var n = valores.length;
                var promedio = valores.reduce((acc, val) => acc + val, 0) / n;
                var maximo = Math.max(...valores);
                var minimo = Math.min(...valores);
                var desviacion = Math.sqrt(valores.map(x => Math.pow(x - promedio, 2)).reduce((a, b) => a + b) / n);
                return {
                    promedio: promedio.toFixed(2),
                    maximo: maximo,
                    minimo: minimo,
                    desviacion: desviacion.toFixed(2)
                };
            }

            var estadisticasPulsaciones = calcularEstadisticas(pulsaciones);
            var estadisticasPasos = calcularEstadisticas(pasos);
            var estadisticasCalorias = calcularEstadisticas(calorias);
            var estadisticasTemperaturas = calcularEstadisticas(temperaturas);

            document.getElementById('estadisticas').innerHTML = `
                <p>Ejercicios Realizados:  ${ejercicios} <br><br> Pulsaciones - Promedio: ${estadisticasPulsaciones.promedio}, Máximo: ${estadisticasPulsaciones.maximo}, Mínimo: ${estadisticasPulsaciones.minimo}, Desviación Estándar: ${estadisticasPulsaciones.desviacion}</p>
                <p>Pasos - Promedio: ${estadisticasPasos.promedio}, Máximo: ${estadisticasPasos.maximo}, Mínimo: ${estadisticasPasos.minimo}, Desviación Estándar: ${estadisticasPasos.desviacion}</p>
                <p>Calorías - Promedio: ${estadisticasCalorias.promedio}, Máximo: ${estadisticasCalorias.maximo}, Mínimo: ${estadisticasCalorias.minimo}, Desviación Estándar: ${estadisticasCalorias.desviacion}</p>
                <p>Temperatura - Promedio: ${estadisticasTemperaturas.promedio}, Máximo: ${estadisticasTemperaturas.maximo}, Mínimo: ${estadisticasTemperaturas.minimo}, Desviación Estándar: ${estadisticasTemperaturas.desviacion}</p>
            `;
        }

        // Función para crear gráficos con Chart.js
        function crearGrafico(id, etiqueta, datos, color, etiquetas) {
            if (charts[id]) {
                charts[id].destroy();
            }

            var ctx = document.getElementById(id).getContext('2d');
            charts[id] = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: etiquetas,
                    datasets: [{
                        label: etiqueta,
                        data: datos,
                        borderColor: color,
                        backgroundColor: color.replace('1)', '0.2)'),
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Hora'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: etiqueta
                            }
                        }
                    }
                }
            });
        }

        // Función para cambiar de gráfico
        function cambiarGrafico() {
            var selectGrafico = document.getElementById('selectGrafico').value;

            for (let id in charts) {
                document.getElementById(id).style.display = (id === selectGrafico) ? 'block' : 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', obtenerDatosProgreso);

    </script>
</body>
</html>
