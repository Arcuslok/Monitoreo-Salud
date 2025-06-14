<!DOCTYPE html>
<?php
require_once 'funciones/Funciones PHP.php';
require_once 'funciones/Funciones SQL.php';

$conn = Conectar_Base_Datos();

session_unset();
session_start();

$_SESSION['pagina_previa'] = $_SERVER['REQUEST_URI'];

?>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Página Principal Administrar</title>
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="jquery/jquery-3.7.1.min.js"></script>
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
                border: 1px solid #000;
                display: block;
                margin: auto;
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
        <script>
            function actualizarPulseras() {
                $.ajax({
                    url: 'pulsera/listar_pulseras.php',
                    success: function(data) {
                        $('#tablaPulseras tbody').html(data);
                    }
                });
            }

            $(document).ready(function(){
                setInterval(actualizarPulseras, 200);
                actualizarPulseras();
            });
        </script>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="Página Principal Administrar Usuarios.php">Administrar Usuarios</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Página Principal Administrar Entrenadores.php">Administrar Entrenadores</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Página Principal Administrar Pulseras.php">Monitorear Pulseras</a>
                        </li>
                    </ul>
                </div>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </nav>
        <!-- a class="dropdown-item" href="principal/cerrar_sesion.php">Cerrar Sesión</a> -->
        <!-- Sección principal -->
        <section class="jumbotron d-flex justify-content-center align-items-center" style="height: 100vh;">

            <div class="container mt-5">
                <h2 class="mb-4">Lista de Pulseras de Usuarios</h2>
                <table id="tablaPulseras" class="table table-striped table-dark">
                    <thead>
                        <tr>
                            <?php
                            $columnas = Columnas_Tabla($conn, "pulsera");
                            foreach ($columnas as $columna) { echo "<td style='text-align: center;'>".ucwords(str_replace("_", " ", $columna))."</td>"; }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
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
    </body>
</html>
