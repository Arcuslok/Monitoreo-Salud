<!DOCTYPE html>
<?php
require_once 'funciones/Funciones PHP.php';
require_once 'funciones/Funciones SQL.php';

session_start(); // Iniciar la sesión

$conn = Conectar_Base_Datos();

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
        <title>Agregar Ejercicio</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-image: url("img/background1.jpg");
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;
                background-attachment: fixed;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                padding: 20px;
            }
            .form-container {
                background-color: #fff;
                padding: 20px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                border-radius: 5px;
                width: 100%;
                max-width: 600px;
            }
            .form-container h2 {
                margin-bottom: 20px;
                text-align: center;
            }
            .form-container label {
                font-weight: bold;
            }
            .form-container input[type="text"],
            .form-container input[type="number"],
            .form-container input[type="password"],
            .form-container input[type="email"] {
                width: 100%;
                padding: 10px;
                margin-bottom: 20px;
                border: 1px solid #ccc;
                border-radius: 4px;
                box-sizing: border-box;
            }
            .form-container button,
            .form-container a.listado {
                padding: 10px 20px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-size: 16px;
                display: inline-block;
                width: calc(50% - 10px);
                text-align: center;
                text-decoration: none;
                color: #fff;
            }
            .form-container button.agregar {
                background-color: #007bff;
                margin-right: 10px;
            }
            .form-container a.listado {
                background-color: #6c757d;
            }
            .alert {
                margin-top: 20px;
            }
        </style>
    </head>
    <body>
        <div class="form-container">
            <h2>Agregar Ejercicio</h2>
            <form method="post" autocomplete="off">
                <div class="row">
                    <div class="col-md-6">
                        <label for="nombre">Nombre Ejercicio:</label>
                        <input type="text" class="nombre" id="nombre" name="nombre" onkeypress="ValidarSoloLetras(event);" required>
                    </div>
                    <label for="descripcion">Descripcion:</label>
                    <textarea type="text" class="descripcion" id="descripcion" name="descripcion" maxlength="885" required rows="4" cols="50" style="resize: vertical;"></textarea>
                </div>
                <br>
                <button type="submit" class="agregar">Agregar</button>
                <a href="Página Principal Ejercicios.php" class="listado">Volver Atras</a>
            </form>
            <?php
            
            // Verificar si se recibieron los datos del formulario
            if (isset($_POST['nombre']) && isset($_POST['descripcion']) && $_SERVER["REQUEST_METHOD"] == "POST") {

                $nombre = Validar_Input($_POST['nombre']);
                $descripcion = Validar_Input($_POST['descripcion']);

                // Verificar si el ejercicio ya existe en la base de datos
                $existe = Existe_Ejercicio($conn, $nombre);

                if ($existe) {
                    echo "<div class='alert alert-danger mt-4' role='alert'>\nError: El Ejercicio ya existe en la Base</div>";
                } else {
                    // Preparar la consulta para insertar el nuevo ejercicio
                    $consulta = "INSERT INTO ejercicios (Nombre_ejercicio, Descripcion) VALUES (?, ?)";
                    $stmt = $conn->prepare($consulta); // Aquí se inicializa correctamente $stmt

                    // Verificar si la preparación fue exitosa
                    if ($stmt) {
                        // Enlazar los parámetros y ejecutar la consulta
                        $stmt->bind_param("ss", $nombre, $descripcion);

                        if ($stmt->execute()) {
                            echo "<div class='alert alert-success mt-4' role='alert'>Nuevo Ejercicio agregado exitosamente</div>";
                        } else {
                            echo "<div class='alert alert-danger mt-4' role='alert'>\nError al agregar el Ejercicio</div>";
                        }

                        // Cerrar la declaración
                        $stmt->close();
                    } else {
                        echo "<div class='alert alert-danger mt-4' role='alert'>\nError al preparar la consulta</div>";
                    }

                    // Cerrar la conexión a la base de datos
                    $conn->close();
                }
            }

            ?>
        </div>
    </body>
</html>
<?php
ob_end_flush();
?>
