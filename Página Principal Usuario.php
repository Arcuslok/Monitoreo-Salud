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
        <title>Página Principal</title>
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
                position: relative;  /* Para evitar que afecten el layout general */
                max-width: 300px;
                min-height: 150px;
                margin: 10px auto;  /* Centra las cartas y agrega separación */
            }

            canvas {
                border: 1px solid #80808;

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

            #cronometro {
                font-size: 24px; /* Aumentar el tamaño de la fuente */
                margin: 10px 0;
                text-align: center;
                font-weight: bold; /* Hacer que los números sean más gruesos */
            }

            #rutina_actual {
                display: flex;
                flex-direction: row; /* Asegúrate de que esté en fila */
                justify-content: space-around;
                align-items: center;
                width: 100%;
            }

            #rutina_actual {
                display: flex !important;
                flex-direction: row !important;
            }

            #Ejercicio_rutina, #Repeticiones_rutina, #Series_rutina {
                width: auto; /* Ajusta el tamaño automáticamente */
                flex: 1; /* Permite que los elementos crezcan de manera uniforme */
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
        <!-- <a class="dropdown-item" href="principal/cerrar_sesion.php">Cerrar Sesión</a> -->
        <!-- Sección principal -->
        <section class="jumbotron d-flex justify-content-center align-items-center" style="height: 100vh;">

            <div class="container">
                <div class="row mt-4">
                    <!-- Sección Pulso Cardiaco (carta oculta) -->
                    <div class="col-md-3">
                        <div class="card text-center" style="display: none;">
                            <div class="card-body">
                                <h5 class="card-title">Pulso Cardiaco</h5>
                                <hr>
                                <!-- Canvas para el gráfico de pulso -->
                                <canvas id="graficoPulso" width="100" height="50"></canvas> <!-- Cambiado a <canvas> -->
                                <h4><span id="pulso"></span><small> BPM</small></h4>
                                <input type="text" id="estado_pulso" value="0" style="display: none;">
                            </div>
                        </div>
                        <br><br><br><br><br><br><br>
                        <!-- Sección Cuenta Pasos (carta oculta) -->
                        <div class="card text-center mt-3" style="display: none;">
                            <div class="card-body justify-content-center align-items-center">
                                <h5 class="card-title">Cuenta Pasos</h5>
                                <hr>
                                <div class="centered-content">
                                    <div class="circle">
                                        <h4><span id="pasos">0</span></h4>
                                    </div>
                                </div>
                                <input type="text" id="estado_pasos" value="0" style="display: none;">
                            </div>
                        </div>
                    </div>

                    <!-- Sección Smartwatch Conectar (esta no se oculta) -->
                    <div class="col-md-6 text-center d-flex justify-content-center align-items-center" style="position: relative; top: -30px;">

                        <!-- <div class="walking-icon" style="display: none;> -->
                        <div class="walking-icon">

                            <div id="rutina_actual" class="input-group mb-3" >
                                <select class="form-control" id="Ejercicio_rutina" style="text-align: center; display: none;" disabled></select>
                                <input type="number" class="form-control" id="Repeticiones_rutina" style="text-align: center; display: none;" placeholder="Repeticiones" value="0" disabled>
                                <input type="number" class="form-control" id="Series_rutina" style="text-align: center; display: none;" placeholder="Series" value="0" disabled>
                            </div>

                            <!-- Contenedor del cronómetro con clases de Bootstrap -->
                            <div class="alert alert-info" id="cronometro" style="display: none;">00:00:00</div>

                            <h1 id="Titulo_rutinas" style="display: none;">
                                <small class="d-block">Tus Rutinas</small>
                            </h1>
                            <div class="d-flex justify-content-center align-items-center" >
                                <select id="options" class="form-control" style="width: auto; display: none;">
                                    <?php
                                    $rutinas = Obtener_Rutinas_Usuario($Conn, $Rut);
                                    foreach ($rutinas as $rutina) { echo "<option value=".$rutina.">".$rutina."</option>"; }
                                    ?>
                                </select>
                            </div>

                            <button id="connect" style="border: none; background: none;">
                                <img id="smartwatch-img" src="img/SmartWatchDisabled.png" alt="Smartwatch Image">
                            </button>
                            <!-- Nuevo botón debajo de la imagen -->
                            <div class="mt-3 d-flex justify-content-center align-items-center">
                                <button class="btn btn-primary" id="IniciarRutinaBtn" style="display: none;" onclick="IniciarRutina()">Iniciar Rutina</button>

                                <!-- Añadir un botón para completar el ejercicio -->
                                <button class="btn btn-primary me-2" id="completarEjercicio" style="display: none;">Avanzar</button>

                                <!-- Botón para terminar la rutina -->
                                <button class="btn btn-primary me-2" id="terminarRutina" style="display: none;">Terminar Rutina</button>
                            </div>

                            <script src="javascript/bluetooth.js"></script>
                        </div>
                    </div>

                    <!-- Sección Calorías (carta oculta) -->
                    <div class="col-md-3">
                        <div class="card text-center" style="display: none;">
                            <div class="card-body">
                                <h5 class="card-title">Calorías</h5>
                                <hr>
                                <p class="card-text">Usted ha quemado:</p>
                                <img id="calorias-img" src="img/calories0.jpg" alt="calories" class="img-fluid" width='50' height='50'>
                                <h2><span id="calorias">0</span> <small>Calorías</small></h2>
                            </div>
                        </div>
                        <br><br><br><br><br><br><br>
                        <!-- Sección Temperatura (carta oculta) -->
                        <div class="card text-center mt-3" style="display: none;">
                            <div class="card-body">
                                <h5 class="card-title">Temperatura</h5>
                                <hr>
                                <p class="card-text">Su temperatura es:</p>
                                <h4><span id="temperatura">0</span><small> °C</small></h4>
                                <input type="text" id="estado_temperatura" value="0" style="display: none;">
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

            let ecgData = Array(100).fill(0); // Cambié la longitud a 100 para iniciar
            let timeData = Array.from({length: 100}, (_, i) => i / 500);
            let samplingRate = 125; 
            let updateInterval = 20; 
            // Inicializar el bpm con un valor
            let bpm = 75;
            let isGraphRunning = false; // Variable para controlar el estado del gráfico

            // Función para generar un BPM aleatorio entre 50 y 120
            function generarBPMAleatorio() {
                return Math.floor(Math.random() * (120 - 50 + 1)) + 50; // Genera un número entre 50 y 120
            }

            function ecgWave(x, c) {
                function pWave(x, c) {
                    let l = c;
                    let a = 0.25; // Amplitud constante
                    x = x + (l / 1.8);
                    let b = 3; // Duración constante
                    let n = 100;
                    let p2 = 0;

                    for (let i = 1; i <= n; i++) {
                        let harm1 = (((Math.sin((Math.PI / (2 * b)) * (b - (2 * i)))) / (b - (2 * i)) +
                            (Math.sin((Math.PI / (2 * b)) * (b + (2 * i)))) / (b + (2 * i))) * (2 / Math.PI)) * Math.cos((i * Math.PI * x) / l);
                        p2 += harm1;
                    }

                    return a * p2; // Retorna la onda P
                }

                function qrsWave(x, c) {
                    let l = c;
                    let a = 1; // Amplitud constante
                    let b = 5; // Duración constante
                    let n = 100;
                    let qrs2 = 0;

                    for (let i = 1; i <= n; i++) {
                        let harm = (((2 * b * a) / (i * i * Math.PI * Math.PI)) * (1 - Math.cos((i * Math.PI) / b))) * Math.cos((i * Math.PI * x) / l);
                        qrs2 += harm;
                    }

                    return qrs2; // Retorna la onda QRS
                }

                function tWave(x, c) {
                    let l = c;
                    let a = 0.35; // Amplitud constante
                    x = x - l / 1.8;
                    let b = 7; // Duración constante
                    let n = 100;
                    let t2 = 0;

                    for (let i = 1; i <= n; i++) {
                        let harm2 = (((Math.sin((Math.PI / (2 * b)) * (b - (2 * i)))) / (b - (2 * i)) +
                            (Math.sin((Math.PI / (2 * b)) * (b + (2 * i)))) / (b + (2 * i))) * (2 / Math.PI)) * Math.cos((i * Math.PI * x) / l);
                        t2 += harm2;
                    }

                    return a * t2; // Retorna la onda T
                }

                return pWave(x, c) + qrsWave(x, c) + tWave(x, c); // Suma las ondas P, QRS y T
            }

            // Función para aumentar el bpm
            function aumentarBPM() {
                bpm = Number((bpm + Math.random()).toFixed(2)); // Aumentar el bpm en 5 (ajusta este valor según sea necesario)
                document.getElementById("pulso").textContent = bpm; // Actualiza el texto en el elemento pulso
            }

            // Función para disminuir el bpm
            function disminuirBPM() {
                bpm = Number((bpm - Math.random()).toFixed(2)); // Disminuye el bpm en 5, pero no permite que sea menor que 0
                document.getElementById("pulso").textContent = bpm; // Actualiza el texto en el elemento pulso
            }

            // Evento de escucha para las teclas
            document.addEventListener("keydown", function(event) {
                if (event.key === "w" || event.key === "W") { // Verifica si la tecla presionada es "W"
                    aumentarBPM(); // Llama a la función para aumentar el bpm
                } else if (event.key === "s" || event.key === "S") { // Verifica si la tecla presionada es "S"
                    disminuirBPM(); // Llama a la función para disminuir el bpm
                }
            });

            // Función para alertar sobre taquicardia y bradicardia
            function verificarFrecuenciaCardiaca() {
                if (bpm > 100) {
                    alert("¡Alerta! Taquicardia: el pulso es mayor a 100 BPM.");
                    bpm -= 5;
                } else if (bpm < 60) {
                    alert("¡Alerta! Bradicardia: el pulso es menor a 60 BPM.");
                    bpm += 5;
                }
            }

            function updateECG() {
                
                let heartbeatInterval = 60 / bpm; // Intervalo entre latidos
                let c = heartbeatInterval / 2; // Mitad del intervalo

                // Genera un nuevo valor de ECG basado en el tiempo actual
                let newEcgValue = ecgWave(timeData[timeData.length - 1] % heartbeatInterval, c);

                ecgData.push(newEcgValue); // Agrega el nuevo valor
                timeData.push(timeData[timeData.length - 1] + 1 / samplingRate); // Incrementa el tiempo

                // Mantiene solo los últimos 100 datos
                if (ecgData.length > 100) {
                    ecgData.shift();
                    timeData.shift();
                }

                document.getElementById("pulso").textContent = bpm;

                // Verifica la frecuencia cardíaca
                verificarFrecuenciaCardiaca();

                // Actualiza el gráfico
                myChart.data.labels = timeData; // Actualiza las etiquetas del eje x
                myChart.data.datasets[0].data = ecgData; // Actualiza los datos de la señal
                myChart.update(); // Redibuja el gráfico

            }

            const ctx = document.getElementById('graficoPulso').getContext('2d');
            const myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: timeData,
                    datasets: [{
                        label: 'ECG Waveform',
                        data: ecgData,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 2,
                        fill: false,
                        pointRadius: 0, // Elimina los puntos
                        tension: 0.3 // Suaviza la curva
                    }]
                },
                options: {
                    animation: false,
                    scales: {
                        x: {
                            display: false, // Oculta el eje x
                            grid: {
                                display: true // Oculta la rejilla del eje x
                            }
                        },
                        y: {
                            min: -0.5,
                            max: 1,
                            display: false, // Oculta el eje y
                            grid: {
                                display: true // Oculta la rejilla del eje y
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false // Oculta la leyenda
                        }
                    }
                }
            });


            setInterval(updateECG, updateInterval); // Actualiza el gráfico cada 40 ms

            let cronometro;  // Para almacenar la referencia del cronómetro
            let descanso;    // Para el temporizador de descanso
            let descansoEnCurso = false; // Indicar si el descanso está en curso
            let intervaloEnvioDatos; // Para almacenar la referencia del intervalo de envío de datos

            // Función para iniciar el cronómetro
            function startCronometro() {
                let segundos = 0;
                clearInterval(cronometro); // Reiniciar el cronómetro si estaba corriendo
                clearInterval(intervaloEnvioDatos); // Detener cualquier intervalo de envío de datos previo

                const ejercicioActual = document.getElementById("Ejercicio_rutina").value;
                const seriesRestantes = parseInt(document.getElementById("Series_rutina").value, 10);
                const repeticiones = parseInt(document.getElementById("Repeticiones_rutina").value, 10);

                // Iniciar cronómetro
                cronometro = setInterval(() => {
                    segundos++;
                    document.getElementById("cronometro").innerText = `Tiempo: ${segundos} segundos`;
                }, 1000);

                // Iniciar el envío de datos cada 5 segundos (5000 ms)
                intervaloEnvioDatos = setInterval(() => {
                    enviarDatos(ejercicioActual, seriesRestantes, repeticiones);
                }, 500); // Enviar datos cada 5 segundos

            }

            // Función para detener el cronómetro
            function stopCronometro() {
                clearInterval(cronometro);
                clearInterval(intervaloEnvioDatos); // Detener el envío de datos al detener el cronómetro
            }

            // Función para iniciar el temporizador de descanso (sin límite de tiempo)
            function iniciarDescanso() {
                let segundosDescanso = 0;
                descansoEnCurso = true; // El descanso está en curso
                clearInterval(descanso); // Limpiar cualquier temporizador de descanso previo
                clearInterval(intervaloEnvioDatos); // Detener el envío de datos durante el descanso

                descanso = setInterval(() => {
                    document.getElementById("cronometro").innerText = `Descanso: ${segundosDescanso} segundos`;
                    segundosDescanso++;
                }, 1000);
            }

            // Función para detener el descanso manualmente
            function detenerDescanso(callback) {
                clearInterval(descanso); // Detener el temporizador de descanso
                descansoEnCurso = false; // Marcar que el descanso ha terminado
                callback(); // Ejecutar la función para reanudar el cronómetro
            }

            // Función para iniciar la rutina
            function IniciarRutina() {

                document.getElementById('Titulo_rutinas').style.display = 'none';
                document.getElementById('IniciarRutinaBtn').style.display = 'none';
                document.getElementById('options').style.display = 'none';

                document.getElementById('Ejercicio_rutina').style.display = 'block';
                document.getElementById('Repeticiones_rutina').style.display = 'block';
                document.getElementById('Series_rutina').style.display = 'block';

                document.getElementById('cronometro').style.display = 'block';
                document.getElementById('completarEjercicio').style.display = 'block';
                document.getElementById('terminarRutina').style.display = 'block';

                alert("Rutina Iniciada");

                var rutinaSeleccionada = document.getElementById("options").value;

                // Realizar una solicitud AJAX para obtener los ejercicios de la rutina
                fetch(`principal/obtener_ejercicios.php?rutina=${rutinaSeleccionada}`)
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

                    const ejercicioSelect = document.getElementById("Ejercicio_rutina");
                    const repeticionesInput = document.getElementById("Repeticiones_rutina");
                    const seriesInput = document.getElementById("Series_rutina");

                    ejercicioSelect.value = '';
                    repeticionesInput.value = 0;
                    seriesInput.value = 0;

                    const ejercicios = data.ejercicios;

                    // Agregar los ejercicios al select
                    ejercicios.forEach(ejercicio => {
                        const option = document.createElement('option');
                        option.value = ejercicio.nombre;
                        option.text = ejercicio.nombre;
                        ejercicioSelect.appendChild(option);
                    });

                    // Seleccionar el primer ejercicio automáticamente
                    if (ejercicios.length > 0) {
                        ejercicioSelect.value = ejercicios[0].nombre;
                        repeticionesInput.value = ejercicios[0].repeticiones;
                        seriesInput.value = ejercicios[0].series;
                    }

                    // Iniciar el cronómetro para la primera serie
                    startCronometro();

                    // Función para completar una serie
                    function completarSerie() {
                        let seriesActuales = parseInt(seriesInput.value, 10);

                        if (seriesActuales > 0) {
                            seriesActuales--;
                            seriesInput.value = seriesActuales; // Actualizar el valor en el input de series

                            if (seriesActuales === 0) {
                                alert("¡Has completado todas las series para este ejercicio!");

                                // Mover al siguiente ejercicio si es posible
                                const ejercicioActual = ejercicioSelect.selectedIndex;
                                if (ejercicioActual + 1 < ejercicioSelect.options.length) {
                                    ejercicioSelect.selectedIndex = ejercicioActual + 1;

                                    const siguienteEjercicio = ejercicios[ejercicioActual + 1];
                                    repeticionesInput.value = siguienteEjercicio.repeticiones;
                                    seriesInput.value = siguienteEjercicio.series;

                                    // Iniciar el descanso sin límite de tiempo antes del próximo ejercicio
                                    stopCronometro(); // Detener el cronómetro antes de iniciar el descanso
                                    iniciarDescanso(); 
                                } else {
                                    alert("¡Has completado todos los ejercicios de la rutina!");
                                    stopCronometro(); // Detener el cronómetro al finalizar la rutina
                                    document.getElementById("cronometro").innerText = `Tiempo: 0 segundos`; // Reiniciar a 0

                                    // Detener el descanso si está en curso
                                    if (descansoEnCurso) {
                                        detenerDescanso(() => {}); // Llamamos a la función de detener sin reiniciar el cronómetro
                                    }

                                    // Limpiar los campos de entrada
                                    ejercicioSelect.innerHTML = '';
                                    repeticionesInput.value = 0;
                                    seriesInput.value = 0;
                                    repeticionesInput.disabled = true;
                                    seriesInput.disabled = true;

                                    // Mensaje de confirmación
                                    alert("Rutina terminada.");

                                    document.getElementById('Titulo_rutinas').style.display = 'block';
                                    document.getElementById('IniciarRutinaBtn').style.display = 'block';
                                    document.getElementById('options').style.display = 'block';

                                    document.getElementById('cronometro').style.display = 'none';
                                    document.getElementById('completarEjercicio').style.display = 'none';
                                    document.getElementById('terminarRutina').style.display = 'none';

                                    document.getElementById('rutina_actual').style.display = 'none';
                                    document.getElementById('Ejercicio_rutina').style.display = 'none';
                                    document.getElementById('Repeticiones_rutina').style.display = 'none';
                                    document.getElementById('Series_rutina').style.display = 'none';
                                    
                                }
                            } else {
                                // Iniciar el descanso sin límite de tiempo entre series
                                stopCronometro(); // Detener el cronómetro del ejercicio
                                iniciarDescanso(); // Iniciar el descanso
                            }
                        }
                    }

                    // Botón para completar una serie o interrumpir el descanso
                    document.getElementById("completarEjercicio").addEventListener("click", () => {
                        if (descansoEnCurso) {
                            // Si el descanso está en curso, interrumpirlo y continuar la rutina
                            detenerDescanso(startCronometro); 
                        } else {
                            // Si no hay descanso en curso, completar la serie normalmente
                            completarSerie();
                        }
                    });

                    // Botón para terminar la rutina
                    document.getElementById("terminarRutina").addEventListener("click", () => {
                        // Detener el cronómetro y reiniciar a 0
                        stopCronometro();
                        document.getElementById("cronometro").innerText = `Tiempo: 0 segundos`; // Reiniciar a 0

                        // Detener el descanso si está en curso
                        if (descansoEnCurso) {
                            detenerDescanso(() => {}); // Llamamos a la función de detener sin reiniciar el cronómetro
                        }

                        // Limpiar los campos de entrada
                        ejercicioSelect.innerHTML = '';
                        repeticionesInput.value = 0;
                        seriesInput.value = 0;
                        repeticionesInput.disabled = true;
                        seriesInput.disabled = true;

                        // Mensaje de confirmación
                        alert("Rutina terminada.");

                        document.getElementById('Titulo_rutinas').style.display = 'block';
                        document.getElementById('IniciarRutinaBtn').style.display = 'block';
                        document.getElementById('options').style.display = 'block';

                        document.getElementById('cronometro').style.display = 'none';
                        document.getElementById('completarEjercicio').style.display = 'none';
                        document.getElementById('terminarRutina').style.display = 'none';

                        document.getElementById('rutina_actual').style.display = 'none';
                        document.getElementById('Ejercicio_rutina').style.display = 'none';
                        document.getElementById('Repeticiones_rutina').style.display = 'none';
                        document.getElementById('Series_rutina').style.display = 'none';
                        

                    });

                })
                .catch(error => {
                    console.error(error);
                    alert('No se pudieron cargar los ejercicios. Intenta nuevamente.');
                });
            }

            // Función para enviar los datos al servidor PHP
            function enviarDatos(ejercicio, seriesRestantes, repeticiones) {

                const fechaActual = new Date();

                // Función para agregar un cero inicial si el valor es menor de 10
                const agregarCero = (valor) => (valor < 10 ? `0${valor}` : valor);

                const año = fechaActual.getFullYear();
                const mes = agregarCero(fechaActual.getMonth() + 1); // Mes (de 0-11, por eso se suma 1)
                const dia = agregarCero(fechaActual.getDate());

                const horas = agregarCero(fechaActual.getHours());
                const minutos = agregarCero(fechaActual.getMinutes());
                const segundos = agregarCero(fechaActual.getSeconds());

                // Formatear la fecha y hora al estilo MySQL
                const fechaMysql = `${año}-${mes}-${dia} ${horas}:${minutos}:${segundos}`;

                // Obtener los valores de los <span>
                const pulso = document.getElementById("pulso").innerText;
                const pasos = document.getElementById("pasos").innerText;
                const calorias = document.getElementById("calorias").innerText;
                const temperatura = document.getElementById("temperatura").innerText;

                const url = `principal/agregar_registro.php?ejercicio=${ejercicio}&repeticion=${repeticiones}&serie=${seriesRestantes}&fecha=${fechaMysql}&pulso=${pulso}&pasos=${pasos}&calorias=${calorias}&temperatura=${temperatura}`;

                // Enviar los datos usando fetch a un archivo PHP
                fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al enviar los datos al servidor.');
                    }
                    return response.json(); // Suponiendo que el PHP devuelve una respuesta JSON
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }


        </script>

    </body>
</html>
