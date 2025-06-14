<!DOCTYPE html>
<?php
session_start();
require_once '../funciones/Funciones PHP.php';
require_once '../funciones/Funciones SQL.php';
$conn = Conectar_Base_Datos();

//$rut = $correo = $fono = $entrenador = $suscripcion = $pulsera = '';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $rut = (int) $_GET['id'];
    $stmt = $conn->prepare("SELECT Correo_usuario, Entrenador_usuario, Suscripcion_usuario, Pulsera_usuario FROM usuario WHERE Rut_usuario = ?");
    $stmt->bind_param("i", $rut);
    $stmt->execute();
    $stmt->bind_result($correo, $entrenador, $suscripcion, $pulsera);
    $stmt->fetch();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['rut'])) {
        $rut = Validar_Input($_POST['rut']);
        $correo = Validar_Input($_POST['correo']);
        $entrenador = Validar_Input($_POST['entrenador']);
        $entrenador = explode(" ", $entrenador);
        $entrenador = Obtener_Entrenador_Nombre($conn, $entrenador[0], $entrenador[1]);
        $suscripcion = Validar_Input($_POST['suscripcion']);
        $suscripcion = Obtener_Suscripcion_Nombre($conn, $suscripcion);
        $pulsera = Validar_Input($_POST['pulsera']);

        $sesion = Obtener_Sesion_Usuario($conn, $correo);
        if ($sesion) {
            header("Location: ../Página Principal Administrar Usuarios.php?alerta=Error al Editar el Usuario: Sesión Iniciada");
            exit();
        }

        $stmt = $conn->prepare("
            UPDATE usuario 
            JOIN registro_pulsera ON usuario.Pulsera_usuario = registro_pulsera.ID_registro_pulsera 
            JOIN pulsera ON registro_pulsera.ID_registro_pulsera = pulsera.ID_pulsera
            JOIN registro_usuarios ON usuario.Correo_usuario = registro_usuarios.Correo_usuario
            SET 
                usuario.Correo_usuario=?,
                registro_usuarios.Correo_usuario=?,
                usuario.Entrenador_usuario=?,
                usuario.Suscripcion_usuario=?,
                usuario.Pulsera_usuario = ?, 
                registro_pulsera.ID_registro_pulsera = ?, 
                pulsera.ID_pulsera = ? 
            WHERE 
            usuario.Rut_usuario = ?");
        $stmt->bind_param("sssissss", $correo, $correo, $entrenador, $suscripcion, $pulsera, $pulsera, $pulsera, $rut);

        if ($stmt->execute()) {

            $stmt->close();
            $conn->close();
            header("Location: ../Página Principal Administrar Usuarios.php");
            exit();
        } else {
            echo "<div class='alert alert-danger mt-4' role='alert'>Error al actualizar el Usuario.</div>";
        }
    }

    else {
        // Guardar datos del formulario en la sesión
        $_SESSION['datos'] = [
            'rut' => $rut,
            'nombre' => $nombre,
            'apellido' => $apellido,
            'direccion' => $direccion,
            'correo' => $correo,
            'fono' => $fono,
            'entrenador' => $entrenador,
            'suscripcion' => $suscripcion,
            'pulsera' => $pulsera
        ];

    }
}

// Si se está mostrando el formulario inicialmente (GET)
elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $rut = (int) $_GET['id'];
    $stmt = $conn->prepare("SELECT Correo_usuario, Entrenador_usuario, Suscripcion_usuario, Pulsera_usuario FROM usuario WHERE Rut_usuario = ?");
    $stmt->bind_param("i", $rut);
    $stmt->execute();
    $stmt->bind_result($correo, $entrenador, $suscripcion, $pulsera);
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
    <title>Editar Usuario</title>
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
            $nombre = Obtener_Nombre_Usuario($conn, $correo);
            $apellido = Obtener_Apellido_Usuario($conn, $correo);
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
        <form action="Editar Usuario.php" method="post" autocomplete="off">
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
                    <label for="entrenador">Entrenador:</label>
                    <select id="entrenador" name="entrenador" class="form-select mb-3">
                        <?php
                        $entrenador_actual = Obtener_Entrenador($conn, $entrenador);
                        echo "<option value='".$entrenador_actual."'>".$entrenador_actual."</option>";
                        $entrenadores = Obtener_Entrenadores_Filtrada($conn, $entrenador);
                        foreach ($entrenadores as $otro_entrenador) {
                            echo "<option value='".$otro_entrenador."'>".$otro_entrenador."</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="suscripcion">Suscripción:</label>
                    <select id="suscripcion" name="suscripcion" class="form-select mb-3">
                        <?php
                        $suscripcion_actual = Obtener_Suscripcion($conn, $suscripcion);
                        echo "<option value='".$suscripcion_actual."'>".$suscripcion_actual."</option>";
                        $suscripciones = Obtener_Suscripciones_Filtrada($conn, $suscripcion);
                        foreach ($suscripciones as $otra_suscripcion) {
                            echo "<option value='".$otra_suscripcion."'>".$otra_suscripcion."</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for="pulsera">Pulsera:</label>
                    <input type="text" class="pulsera" id="pulsera" name="pulsera" value="<?php echo $pulsera; ?>" onkeypress="ValidaSoloNumeros(event);" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="../Página Principal Administrar Usuarios.php" class="listado btn btn-secondary">Administrar Usuarios</a>
        </form>
    </div>
</body>
</html>
<?php
ob_end_flush();
?>
