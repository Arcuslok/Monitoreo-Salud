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
                        case "Correo":
                            td = tds[4]; break;
                        case "Pulsera":
                            td = tds[8]; break;
                        default:
                            td = null;
                    }
                    
                    if (td && td.innerText.toLowerCase().indexOf(input) > -1) {
                        showRow = true;
                    }
                }

                trs[i].style.display = showRow ? "" : "none";
            }
        }  
    </script>
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
    <section class="jumbotron d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="card">
            <div class="container mt-5">
                <div class="mb-4 row">
                    <div class="form-container">
                        <label for="nombre">Filtrar Datos por:</label>
                    </div>
                    <div class="col-md-3">
                        <select id="filterColumn" class="form-select mb-2">    
                            <option value="Ninguno">Ninguno</option>
                            <?php
                            $columnas = Columnas_Tabla($conn, "usuario");
                            foreach ($columnas as $columna) { if ($columna == "Direccion" || $columna == "Fono" || $columna == "Entrenador" || $columna == "Suscripcion"|| $columna == "Sesion_activa") { continue; } else { echo "<option value='".$columna."'>".$columna."</option>"; } }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-9">
                        <input type="text" id="searchInput" onkeyup="searchTable()" class="form-control" placeholder="Buscar usuario...">
                    </div>
                </div>
                <table id="usuarios" class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <?php
                            $columnas = Columnas_Tabla($conn, "usuario");
                            foreach ($columnas as $columna) { if ($columna == "Direccion" || $columna == "Fono" || $columna == "Entrenador" || $columna == "Suscripcion" || $columna == "Sesion_activa") { continue; } else { echo "<td style='text-align: center;'>".$columna."</td>"; } }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include "plantillas/Plantilla Usuario.php";

                        $rut = Obtener_Entrenador_Rut($conn, $entrenador);
                        $usuarios = Obtener_Entrenadores_Usuarios($conn, $rut);

                        if ($usuarios) {
                            foreach ($usuarios as $usuario) {
                                $rut = $usuario["Rut_usuario"];
                                $formatear_rut = formatearRUT($rut);
                                echo "<tr>";   
                                echo "<td style='text-align: center;'>".$formatear_rut."</td>";   
                                echo "<td style='text-align: center;'>".$usuario["Nombre_usuario"]."</td>";   
                                echo "<td style='text-align: center;'>".$usuario["Apellido_usuario"]."</td>";   
                                echo "<td style='text-align: center;'>".$usuario["Correo_usuario"]."</td>";   
                                echo "<td style='text-align: center;'>".$usuario["Pulsera_usuario"]."</td>";   

                                echo "</tr>";   
                            }
                        } else {
                            echo "<tr><td colspan='10'>No hay Usuarios registrados</td></tr>";
                        }

                        $conn->close();

                        ?>
                    </tbody>
                </table>
                <br><br>
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
</body>
</html>
