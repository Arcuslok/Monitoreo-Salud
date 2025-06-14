<!DOCTYPE html>
<?php
require_once 'funciones/Funciones SQL.php';
require_once 'funciones/Funciones PHP.php';

session_start(); // Iniciar la sesión

$_SESSION['pagina_previa'] = $_SERVER['REQUEST_URI'];

// Verificar si existe el parámetro 'alerta' en la URL
$alerta = $_GET['alerta'] ?? null;

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
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JavaScript -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

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
                        case "Nombre":
                            td = tds[1]; break;
                        case "Descripcion":
                            td = tds[2]; break;
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
        .pagination {
            position: absolute;
            bottom: 10px; /* Espacio desde la parte inferior de la tabla */
            right: 10px;  /* Espacio desde la parte derecha de la tabla */
            display: flex;
            align-items: center;
        }

        .pagination button {
            margin: 0 5px;
            padding: 8px 16px;
            background-color: #007bff; /* Color de fondo azul */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s, transform 0.2s;
        }

        .pagination button.active {
            background-color: #0056b3; /* Color más oscuro para el botón activo */
        }

        .pagination button:hover {
            background-color: #0056b3; /* Color al pasar el mouse */
            transform: scale(1.05); /* Aumenta ligeramente el tamaño al hacer hover */
        }

        .pagination button:focus {
            outline: none; /* Elimina el borde predeterminado del enfoque */
        }

        .pagination button:disabled {
            background-color: #ccc; /* Color para botones deshabilitados */
            cursor: not-allowed;
        }

        /* Estilo para el contenedor de la tabla para permitir la posición absoluta */
        .table-container {
            position: relative; /* Necesario para que la paginación se posicione respecto a este contenedor */
        }


    </style>
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
                            $columnas = Columnas_Tabla($conn, "ejercicios");
                            foreach ($columnas as $columna) { echo "<option value='".$columna."'>".explode("_", $columna)[0]."</option>"; } 
                            ?>
                        </select>
                    </div>
                    <div class="col-md-9">
                        <input type="text" id="searchInput" onkeyup="searchTable()" class="form-control" placeholder="Buscar ejercicio...">
                    </div>
                </div>
                <table id="ejercicios" class="table table-bordered" >
                    <thead class="table-light">
                        <tr>
                            <?php
                            $columnas = Columnas_Tabla($conn, "ejercicios");
                            foreach ($columnas as $columna) if ($columna == "ID_ejercicio") {
                                continue;
                            } else { echo "<td style='text-align: center;'>".explode("_", $columna)[0]."</td>"; }
                            ?>
                            <th style='text-align: center;'>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        $ejercicios = Obtener_Todo_Ejercicios($conn);

                        if ($ejercicios) {
                            foreach ($ejercicios as $ejercicio) {
                                echo "<tr>";   
                                echo "<td style='text-align: center; display: none;'>".$ejercicio["id"]."</td>"; 
                                echo "<td style='text-align: center;'>".$ejercicio["nombre"]."</td>";   
                                echo "<td >".nl2br($ejercicio["descripcion"])."</td>";   
                                echo "<td style='text-align: center;'>
                                    <a href='principal/eliminar_ejercicio_entrenador.php?id=". $ejercicio["id"] ."' class='btn btn-danger btn-sm'>Eliminar</a>
                                    </td>";   
                                echo "</tr>";   
                            }
                        } else {
                            echo "<tr><td colspan='10'>No hay Usuarios registrados</td></tr>";
                        }

                        $conn->close();

                        ?>
                    </tbody>
                </table>
                <a href="Agregar Ejercicio.php" class="btn btn-secondary">Agregar Ejercicio</a>
                <br>
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
        document.addEventListener("DOMContentLoaded", function() {
            var table = document.getElementById("ejercicios");
            var rowsPerPage = 2; // Cambia a 2 filas por página
            var currentPage = 1;
            var rows = table.querySelectorAll("tbody tr");

            function displayTable(page) {
                var start = (page - 1) * rowsPerPage;
                var end = start + rowsPerPage;

                // Ocultar todas las filas
                rows.forEach((row, index) => {
                    row.style.display = (index >= start && index < end) ? "" : "none";
                });
            }

            function setupPagination() {
                var totalPages = Math.ceil(rows.length / rowsPerPage);
                var pagination = document.createElement("div");
                pagination.className = "pagination";

                for (var i = 1; i <= totalPages; i++) {
                    var button = document.createElement("button");
                    button.textContent = i;
                    button.addEventListener("click", function() {
                        currentPage = parseInt(this.textContent);
                        displayTable(currentPage);
                        updateActiveButton();
                    });
                    pagination.appendChild(button);
                }

                table.parentElement.appendChild(pagination);
            }

            function updateActiveButton() {
                var buttons = document.querySelectorAll(".pagination button");
                buttons.forEach((button, index) => {
                    button.className = (index + 1 === currentPage) ? "active" : "";
                });
            }

            // Inicializar la tabla
            displayTable(currentPage);
            setupPagination();
            updateActiveButton();
        });
    </script>


</body>
</html>
