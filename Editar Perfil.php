<!DOCTYPE html>
<?php
require_once 'funciones/Funciones PHP.php';
require_once 'funciones/Funciones SQL.php';

session_start();

$Rut = $Nombre = $Apellido = $Direccion = $Correo = $Fono = '';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['Datos']) || !$_SESSION['Datos']['Usuario_autenticado']) {
    header('Location: Login Usuario.php'); // Redirige a la página de inicio de sesión
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

    if ($Estado_Pulsera == true) {
        $Rut = Obtener_Rut_Usuario($Conn, $Correo);
        Establecer_Estado_Pulsera($Conn, $Rut, false);
        $Estado_Pulsera = false;
    }

    if ($Estado_Pulso == true) {
        $Rut = Obtener_Rut_Usuario($Conn, $Correo);
        Establecer_Estado_Pulso($Conn, $Rut, false);
        $Estado_Pulso = false;
    }

    if ($Estado_Pasos == true) {
        $Rut = Obtener_Rut_Usuario($Conn, $Correo);
        Establecer_Estado_Pasos($Conn, $Rut, false);
        $Estado_Pasos = false;
    }

    if ($Estado_Temperatura == true) {
        $Rut = Obtener_Rut_Usuario($Conn, $Correo);
        Establecer_Estado_Temperatura($Conn, $Rut, false);
        $Estado_Temperatura = false;
    }

    $Consul = "SELECT usuario.Rut_usuario, usuario.Nombre_usuario, usuario.Apellido_usuario, usuario.Direccion_usuario, usuario.Fono_usuario
    FROM usuario, registro_usuarios WHERE (usuario.Correo_usuario = '$Correo')";

    $resultado = mysqli_query($Conn, $Consul);

    if(mysqli_num_rows($resultado)) {
        while ($fila = mysqli_fetch_array($resultado)) {
            $Rut = $fila[0];
            $Nombre = $fila[1];
            $Apellido = $fila[2];
            $Direccion = $fila[3];
            $Fono = $fila[4];
        }

        $_SESSION['Datos'] = [
            'Correo_usuario' => $Correo,
            'Clave_usuario' => $Clave,
            'Estado_Pulsera_usuario' => $Estado_Pulsera,
            'Estado_Pulso_usuario' => $Estado_Pulso,
            'Estado_Pasos_usuario' => $Estado_Pasos,
            'Estado_Temperatura_usuario' => $Estado_Temperatura,
            'Usuario_autenticado' => true
        ];
    }


}

?>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil Principal</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            <?php
            $genero = identificar_genero($Nombre);

            if ($genero == "Masculino") {
                echo "background-image: url('img/background1.jpg');";
            }

            else {
                echo "background-image: url('img/background2.jpg');";
            }
            ?>
            background-position: center;
            background-repeat: repeat-x;
            background-size: contain;
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .form-container {
            max-width: 600px;
            margin: auto;
            padding: 30px;
            border: 1px solid #ebebeb;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #f9f9f9;
        }
        .form-container label {
            font-weight: bold;
        }
        .form-container input {
            margin-bottom: 15px;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        h2 {
            margin-bottom: 0;
            text-align: left; /* Cambiado a left para alinearlo con la imagen */
            margin-left: 10px; /* Añadido margen a la izquierda para separación */
        }
        .profile-pic {
            width: 90px; /* Tamaño aumentado */
            height: 90px; /* Tamaño aumentado */
            object-fit: cover;
            border-radius: 50%;
            margin-right: 10px; /* Espacio entre la imagen y el título */
            align-self: center; /* Centra la imagen verticalmente */
        }
        .title-container {
            display: flex;
            align-items: center; /* Alinea verticalmente la imagen y el título */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <div class="title-container">
                <?php
                $genero = identificar_genero($Nombre);

                if ($genero == "Masculino") {
                    echo "<img src='img\hombre.jpg' width='50' height='50'>";
                }

                else {
                    echo "<img src='img\mujer.jpg' width='50' height='50'>";
                }
                ?>
                <h2>Perfil de Usuario</h2>
            </div>
            <br>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data" autocomplete="off">
                <div class="mb-3">
                    <label for="rut">RUT:</label>
                    <input type="text" class="form-control" id="rut" value="<?php echo formatearRUT($Rut); ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="nombre">Nombre:</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $Nombre; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="apellido">Apellido:</label>
                    <input type="text" class="form-control" id="apellido" name="apellido" value="<?php echo $Apellido; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="direccion">Dirección:</label>
                    <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo $Direccion; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="fono">Teléfono:</label>
                    <input type="text" class="form-control" id="fono" name="fono" value="<?php echo $Fono; ?>" required>
                </div>
                <form>
                    <button type="submit" class="btn btn-secondary">Guardar Cambios</button>
                    <a href="<?php echo $_SESSION['pagina_previa']; ?>" class="btn btn-primary"> Volver a la Página Anterior </a>
                </form>

                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // Conexión a la base de datos
                    $conn = Conectar_Base_Datos();
                
                    // Validación de entrada
                    $nombre = Validar_Input($_POST['nombre']);
                    $apellido = Validar_Input($_POST['apellido']);
                    $direccion = Validar_Input($_POST['direccion']);
                    $fono = Validar_Input($_POST['fono']);
                
                    // Consulta para actualizar datos
                    $stmt = $conn->prepare("UPDATE usuario, registro_usuarios SET usuario.Nombre_usuario=?, usuario.Apellido_usuario=?, usuario.Direccion_usuario=?, usuario.Fono_usuario=? WHERE usuario.Rut_usuario=? And usuario.Correo_usuario=registro_usuarios.Correo_usuario");
                    if (!$stmt) {
                        echo "<div class='alert alert-danger mt-4' role='alert'>Error al preparar la consulta: " . $conn->error . "</div>";
                        exit();
                    }
                
                    $stmt->bind_param("ssssi", $nombre, $apellido, $direccion, $fono, $Rut);
                
                    // Ejecutar la consulta y verificar el éxito
                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success mt-4' role='alert'>Datos actualizados exitosamente</div>";
                    } else {
                        echo "<div class='alert alert-danger mt-4' role='alert'>Error al actualizar los datos: " . $stmt->error . "</div>";
                    }
                
                    // Cerrar la consulta y la conexión
                    $stmt->close();
                    $conn->close();
                }    
                ?>

            </form>
        </div>
    </div>
</body>
</html>
