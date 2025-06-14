<!DOCTYPE html>
<?php
require_once 'funciones/Funciones PHP.php';
require_once 'funciones/Funciones SQL.php';

$conn = Conectar_Base_Datos();

session_start();

$_SESSION['pagina_previa'] = $_SERVER['REQUEST_URI'];

// Verificar si existe el parámetro 'alerta' en la URL
$alerta = $_GET['alerta'] ?? null;

session_unset();
?>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Página Principal Administrar</title>
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
            function searchTable() {
                const input = document.getElementById("searchInput").value.toLowerCase();
                const filterColumn = document.getElementById("filterColumn").value;
                const table = document.getElementById("usuarios");
                const trs = table.getElementsByTagName("tr");

                for (let i = 1; i < trs.length; i++) {
                    const tds = trs[i].getElementsByTagName("td");
                    let showRow = false;

                    if (input === "") {
                        showRow = true;
                    } else {
                        let td;
                        switch (filterColumn) {
                            case "Rut":
                                td = tds[0]; break;
                            case "Nombre":
                                td = tds[1]; break;
                            case "Apellido":
                                td = tds[2]; break;
                            case "Direccion":
                                td = tds[3]; break;
                            case "Correo":
                                td = tds[4]; break;
                            case "Fono":
                                td = tds[5]; break;
                            case "Entrenador":
                                td = tds[6]; break;
                            case "Suscripcion":
                                td = tds[7]; break;
                            case "Pulsera":
                                td = tds[8]; break;
                            case "Sesion Activa":
                                td = tds[9]; break;
                            default:
                                td = null; showRow = true;
                        }
                        
                        if (td && td.innerText.toLowerCase().indexOf(input) > -1) {
                            showRow = true;
                        }
                    }

                    trs[i].style.display = showRow ? "" : "none";
                }
            }  
        </script>
    </head>
    <body>
        <?php if ($alerta): ?>
            <!-- Mostrar la alerta si existe -->
            <div class="alerta">
                <!-- Mostrar la alerta con JavaScript si existe -->
                <script>
                    alert("<?php echo htmlspecialchars($alerta, ENT_QUOTES, 'UTF-8'); ?>");
                </script>
            </div>
        <?php endif; ?>
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
                <h2 class="mb-4">Lista de Usuarios</h2>
                <div class="mb-4 row">
                    <div class="col-md-3">
                        <select id="filterColumn" class="form-select mb-2">
                            <option value="" disabled selected>Filtra un dato por:</option>      
                            <?php
                            $columnas = Columnas_Tabla($conn, "usuario");
                            foreach ($columnas as $columna) { echo "<option value='".$columna."'>".ucwords(str_replace("_", " ", $columna))."</option>"; }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-9">
                        <input type="text" id="searchInput" onkeyup="searchTable()" class="form-control" placeholder="Buscar usuario...">
                    </div>
                </div>
                <table id="usuarios" class="table table-striped table-dark">
                    <thead>
                        <tr>
                            <?php
                            $columnas = Columnas_Tabla($conn, "usuario");
                            foreach ($columnas as $columna) { echo "<td style='text-align: center;'>".ucwords(str_replace("_", " ", $columna))."</td>"; }
                            ?>
                            <th style='text-align: center;'>Acciones</th>
                        </tr>
                        <tbody>
                            <?php
                            include "plantillas/Plantilla Usuario.php";

                            $usuarios = Usuario::listarUsuario($conn);

                            if ($usuarios) {
                                foreach ($usuarios as $usuario) {
                                    $rut = $usuario["Rut_usuario"];
                                    $formatear_rut = formatearRUT($rut);
                                    echo "<tr>";
                                    echo "<td style='text-align: center;'>".$formatear_rut."</td>";
                                    echo "<td style='text-align: center;'>".$usuario["Nombre_usuario"]."</td>";
                                    echo "<td style='text-align: center;'>".$usuario["Apellido_usuario"]."</td>";
                                    echo "<td style='text-align: center;'>".$usuario["Direccion_usuario"]."</td>";
                                    echo "<td style='text-align: center;'>".$usuario["Correo_usuario"]."</td>";
                                    echo "<td style='text-align: center;'>".$usuario["Fono_usuario"]."</td>";
                                    echo "<td style='text-align: center;'>".Obtener_Entrenador($conn, $usuario["Entrenador_usuario"])."</td>";
                                    echo "<td style='text-align: center;'>".Obtener_Suscripcion($conn, $usuario["Suscripcion_usuario"])."</td>";
                                    echo "<td style='text-align: center;'>".$usuario["Pulsera_usuario"]."</td>";
                                    if ($usuario["Sesion_activa"] == 1) {
                                        echo "<td style='text-align: center; color: green';>Conectado</td>";  
                                    }
                                    else {
                                        echo "<td style='text-align: center;color: red;'>Desconectado</td>";   
                                    }
                                    echo "<td style='text-align: center;'>
                                        <a href='editar/Editar Usuario.php?id=". $usuario["Rut_usuario"] ."' class='btn btn-primary btn-sm'>Editar</a>
                                        <a href='eliminar/Eliminar Usuario.php?id=". $usuario["Rut_usuario"] ."' class='btn btn-danger btn-sm'
                                        onclick='return confirm(\"¿Estás seguro de eliminar este registro?\");'>Eliminar</a>
                                        </td>";   
                                    echo "</tr>";   
                                }
                            } else {
                                echo "<tr><td colspan='10'>No hay Usuarios registrados</td></tr>";
                            }

                            $conn->close();

                            ?>
                        </tbody>
                    </thead>
                </table>
                <form>
                    <a href="pdf/PDF Usuarios.php" class="btn btn-secondary">Exportar Tabla Completa a PDF</a>
                    <a href="agregar/Agregar Usuario.php" class="btn btn-primary">Agregar Usuario</a>
                </form>
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
