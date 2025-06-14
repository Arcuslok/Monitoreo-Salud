<!DOCTYPE html>
<?php
session_start();
require_once '../funciones/Funciones PHP.php';
require_once '../funciones/Funciones SQL.php';
$conn = Conectar_Base_Datos();

$rut = $nombre = $apellido = $correo = $fono = $disponibilidad = '';

// Recuperar datos del formulario de la sesión si existen
if (isset($_SESSION['datos'])) {
    $datos = $_SESSION['datos'];
    $rut = $datos['rut'];
    $nombre = $datos['nombre'];
    $apellido = $datos['apellido'];
    $correo = $datos['correo'];
    $fono = $datos['fono'];
    $disponibilidad = $datos['disponibilidad'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['rut'])) {
        $rut = Validar_Input($_POST['rut']);
        $nombre = Validar_Input($_POST['nombre']);
        $apellido = Validar_Input($_POST['apellido']);
        $correo = Validar_Input($_POST['correo']);
        $fono = Validar_Input($_POST['fono']);
        $disponibilidad = Validar_Input($_POST['disponibilidad']);

        $sesion = Obtener_Sesion_Entrenador($conn, $correo);
        if ($sesion) {
            header("Location: ../Página Principal Administrar Entrenadores.php?alerta=Error al Editar al Entrenador/a: Sesión Iniciada");
            exit();
        }
        
        $stmt = $conn->prepare("
            UPDATE entrenador 
            JOIN registro_entrenadores ON entrenador.Correo_entrenador = registro_entrenadores.Correo_entrenador
            SET 
                entrenador.Nombre_entrenador=?,
                entrenador.Apellido_entrenador=?,
                entrenador.Correo_entrenador=?,
                registro_entrenadores.Correo_entrenador=?,
                entrenador.Fono_entrenador=?,
                entrenador.Disponibilidad_entrenador=?
            WHERE 
                Rut_entrenador=?");
        $stmt->bind_param("ssssssi", $nombre, $apellido, $correo, $correo, $fono, $disponibilidad, $rut);

        if ($stmt->execute()) {

            $stmt->close();
            $conn->close();
            header("Location: ../Página Principal Administrar Entrenadores.php");
            exit();
        } else {
            echo "<div class='alert alert-danger mt-4' role='alert'>Error al actualizar al Entrenador.</div>";
        }
    }

    else {
        // Guardar datos del formulario en la sesión
        $_SESSION['datos'] = [
            'rut' => $rut,
            'nombre' => $nombre,
            'apellido' => $apellido,
            'correo' => $correo,
            'fono' => $fono,
            'disponibilidad' => $disponibilidad
        ];

    }
}

// Si se está mostrando el formulario inicialmente (GET)
elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $rut = (int) $_GET['id'];
    $stmt = $conn->prepare("SELECT Nombre_entrenador, Apellido_entrenador, Correo_entrenador, Fono_entrenador, Disponibilidad_entrenador FROM entrenador WHERE Rut_entrenador = ?");
    $stmt->bind_param("i", $rut);
    $stmt->execute();
    $stmt->bind_result($nombre, $apellido, $correo, $fono, $disponibilidad);
    $stmt->fetch();
    $stmt->close();
}

?>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../css/editar.css">
    <title>Editar Entrenador</title>
    <style>
        body {
            <?php
            $genero = identificar_genero($nombre);
            if ($genero == "Masculino") {
                echo "background-image: url('../img/background1.jpg');";
            }

            else {
                echo "background-image: url('../img/background2.jpg');";
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
    <div class="form-container">
        <div class="title-container">
            <?php

            $nombre = Obtener_Nombre_Entrenador($conn, $correo);
            $apellido = Obtener_Apellido_Entrenador($conn, $correo);
            $genero = identificar_genero($nombre);

            if ($genero == "Masculino") {
                echo "<img src='../img/hombre.jpg' width='50' height='50'>";
            }

            else {
                echo "<img src='../img/mujer.jpg' width='50' height='50'>";
            }

            echo "<h2>". $nombre ." ". $apellido ."</h2>";
            ?>
        </div>
        <form action="Editar Entrenador.php" method="post" autocomplete="off">
            <input type="hidden" name="rut" value="<?php echo $rut; ?>">
            <div class="row">
                <div class="col-md-6">
                    <label for="rut">Rut:</label>
                    <input type="text" class="rut" id="rut" name="rut" maxlength="12" value="<?php echo formatearRUT($rut); ?>" disabled>
                </div>
                <div class="col-md-6">
                    <label for="correo">Correo Electrónico:</label>
                    <input type="email" class="correo" id="correo" name="correo" value="<?php echo $correo; ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for="nombre">Nombre:</label>
                    <input type="text" class="nombre" id="nombre" name="nombre" value="<?php echo $nombre; ?>" onkeypress="ValidarSoloLetras(event);" required>
                </div>
                <div class="col-md-6">
                    <label for="apellido">Apellido:</label>
                    <input type="text" class="apellido" id="apellido" name="apellido" value="<?php echo $apellido; ?>" onkeypress="ValidarSoloLetras(event);" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for="fono">Fono:</label>
                    <input type="text" class="fono" id="fono" name="fono" value="<?php echo $fono;?>" onkeypress="ValidaSoloNumeros(event);" required>
                </div>
                <div class="col-md-6">
                    <label for="disponibilidad">Disponibilidad:</label>
                    <select id="disponibilidad" name="disponibilidad" class="form-select mb-3">
                        <?php
                        if ($disponibilidad == "1") {
                            echo "<option value='1'>Disponible</option>";
                            echo "<option value='0'>No Disponible</option>";
                        }
                        else {
                            echo "<option value='0'>No Disponible</option>";
                            echo "<option value='1'>Disponible</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="../Página Principal Administrar Entrenadores.php" class="listado btn btn-secondary">Administrar Entrenadores</a>
        </form>
    </div>
</body>
</html>
<?php
ob_end_flush();
?>
