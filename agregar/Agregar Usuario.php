<!DOCTYPE html>
<?php
require_once '../funciones/Funciones PHP.php';
require_once '../funciones/Funciones SQL.php';
clearstatcache();
$conn = Conectar_Base_Datos();
//<form action="Agregar Usuario.php" method="post" autocomplete="off">
?>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
        <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
        <title>Agregar Usuario</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-image: url("../img/background1.jpg");
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
            <h2>Agregar Usuario</h2>
            <form action="Agregar Usuario.php" method="post" autocomplete="off">
                <div class="row">
                    <div class="col-md-6">
                        <label for="rut">Rut: <div id="rutFeedback"></div></label>
                        <input type="text" class="rut" id="rut" name="rut_visual" maxlength="12" oninput="ValidarRut(this)" required>
                        <input type="hidden" id="rutHidden" name="rut">
                    </div>
                    <div class="col-md-6">
                        <label for="correo">Correo Electrónico:</label>
                        <input type="email" class="correo" id="correo" name="correo" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label for="nombre">Nombre:</label>
                        <input type="text" class="nombre" id="nombre" name="nombre" onkeypress="ValidarSoloLetras(event);" required>
                    </div>
                    <div class="col-md-6">
                        <label for="apellido">Apellido:</label>
                        <input type="text" class="apellido" id="apellido" name="apellido" onkeypress="ValidarSoloLetras(event);" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label for="direccion">Dirección:</label>
                        <input type="text" class="direccion" id="direccion" name="direccion" required>
                    </div>
                    <div class="col-md-6">
                        <label for="fono">Fono:</label>
                        <input type="text" class="fono" id="fono" name="fono" onkeypress="ValidaSoloNumeros(event);" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label for="entrenador">Entrenador:</label>
                        <select id="entrenador" name="entrenador" class="form-select mb-3">
                            <option value="Ninguno">Ninguno</option>
                            <?php
                            $entrenadores = Obtener_Entrenadores_Disponibles($conn);
                            foreach ($entrenadores as $entrenador) { echo "<option value='".$entrenador."'>".$entrenador."</option>"; }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="suscripcion">Suscripción:</label>
                        <select id="suscripcion" name="suscripcion" class="form-select mb-3">
                            <option value="Ninguno">Ninguno</option>
                            <?php
                            $suscripciones = Obtener_Suscripciones($conn);
                            foreach ($suscripciones as $suscripcion) { echo "<option value='".$suscripcion."'>".$suscripcion."</option>"; }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label for="pulsera">Pulsera:</label>
                        <input type="text" class="pulsera" id="pulsera" name="pulsera" onkeypress="ValidaSoloNumeros(event);" required>
                    </div>
                    <div class="col-md-6">
                        <label for="clave">Contraseña:</label>
                        <input type="password" class="clave" id="clave" name="clave" required>
                    </div>
                </div>
                <button type="submit" class="agregar">Agregar</button>
                <a href="../Página Principal Administrar Usuarios.php" class="listado">Administrar Usuarios</a>
            </form>
            <?php
            include "../plantillas/Plantilla Usuario.php";
            include "../plantillas/Plantilla Pulsera.php";
            
            if (isset($_POST['rut']) && $_SERVER["REQUEST_METHOD"] == "POST") {

                $rut = Validar_Input($_POST['rut']);
                $nombre = Validar_Input($_POST['nombre']);
                $apellido = Validar_Input($_POST['apellido']);
                $direccion = Validar_Input($_POST['direccion']);
                $correo = Validar_Input($_POST['correo']);
                $fono = Validar_Input($_POST['fono']);
                
                if (isset($_POST['entrenador']) && isset($_POST['suscripcion'])) {
                    $entrenador = Validar_Input($_POST['entrenador']);
                    $suscripcion = Validar_Input($_POST['suscripcion']);

                    if ($entrenador == "Ninguno" && $suscripcion == "Ninguno") {
                        echo "<div class='alert alert-danger mt-4' role='alert'>\nError: Ingrese un/a Entrenador/a y una Suscripcion</div>";
                    }

                    elseif ($entrenador == "Ninguno" && $suscripcion != "Ninguno") {
                        echo "<div class='alert alert-danger mt-4' role='alert'>\nError: Ingrese un/a Entrenador/a</div>";
                    }

                    elseif ($entrenador != "Ninguno" && $suscripcion == "Ninguno") {
                        echo "<div class='alert alert-danger mt-4' role='alert'>\nError: Ingrese una Suscripcion</div>";
                    }

                    else{
                        $entrenador = explode(" ", $entrenador);
                        $entrenador = Obtener_Entrenador_Nombre($conn, $entrenador[0], $entrenador[1]);
                        $suscripcion = Obtener_Suscripcion_Nombre($conn, $suscripcion);
                    }

                }

                $pulsera = Validar_Input($_POST['pulsera']);
                $clave = $_POST['clave'];

                if (validarRUT($rut)) {

                    $rut_existente = Existe_Usuario($conn, $rut);
                    $correo_existente = Existe_Correo_Usuario($conn, $correo);
                    $fono_existente = Existe_Fono_Usuario($conn, $fono);
                    $pulsera_existente = Existe_Pulsera_Usuario($conn, $pulsera);
                    $clave_existente = Existe_Clave_Usuario($conn, $clave);
                
                    if ($rut_existente == 0 && $pulsera_existente == 0 && $correo_existente == 1) {
                        echo "<div class='alert alert-danger mt-4' role='alert'>\nError: El Correo ya existe en la Base</div>";
                    }
                    elseif ($rut_existente == 0 && $pulsera_existente == 1 && $correo_existente == 0) {
                        echo "<div class='alert alert-danger mt-4' role='alert'>\nError: La Pulsera ya existe en la Base</div>";
                    }
                    elseif ($rut_existente == 0 && $pulsera_existente == 1 && $correo_existente == 1) {
                        echo "<div class='alert alert-danger mt-4' role='alert'>\nError: La Pulsera y el Correo ya existen en la Base</div>";
                    }
                    elseif ($rut_existente == 1 && $pulsera_existente == 0 && $correo_existente == 0) {
                        echo "<div class='alert alert-danger mt-4' role='alert'>\nError: El Rut y el Correo ya existen en la Base</div>";
                    }
                    elseif ($rut_existente == 1 && $pulsera_existente == 1 && $correo_existente == 1) {
                        echo "<div class='alert alert-danger mt-4' role='alert'>\nError: El Rut, Correo y la Pulsera ya existen en la Base</div>";
                    }
                    elseif ($rut_existente == 0 && $pulsera_existente == 0 && $fono_existente == 1) {
                        echo "<div class='alert alert-danger mt-4' role='alert'>\nError: El Teléfono ya existe en la Base</div>";
                    }
                    elseif ($rut_existente == 0 && $pulsera_existente == 1 && $fono_existente == 1) {
                        echo "<div class='alert alert-danger mt-4' role='alert'>\nError: La Pulsera y el Teléfono ya existen en la Base</div>";
                    }
                    elseif ($rut_existente == 1 && $pulsera_existente == 0 && $fono_existente == 1) {
                        echo "<div class='alert alert-danger mt-4' role='alert'>\nError: El Rut y el Teléfono ya existen en la Base</div>";
                    }
                    elseif ($rut_existente == 1 && $pulsera_existente == 1 && $fono_existente == 1) {
                        echo "<div class='alert alert-danger mt-4' role='alert'>\nError: El Rut, Pulsera y Teléfono ya existen en la Base</div>";
                    }
                    elseif ($rut_existente == 0 && $pulsera_existente == 0 && $clave_existente == 1) {
                        echo "<div class='alert alert-danger mt-4' role='alert'>\nError: La Clave ya existe en la Base</div>";
                    }
                    elseif ($rut_existente == 0 && $pulsera_existente == 1 && $clave_existente == 1) {
                        echo "<div class='alert alert-danger mt-4' role='alert'>\nError: La Pulsera y la Clave ya existen en la Base</div>";
                    }
                    else {

                        $pulsera_nueva = new Pulsera($pulsera, false, false, false, false);

                        $usuario = new Usuario($rut, $nombre, $apellido, $direccion, $correo, $fono, $entrenador, $suscripcion, $pulsera);
                        
                        if ($pulsera_nueva->crearPulsera($conn) && $usuario->crearUsuario($conn) && Registrar_Usuario($conn, $correo, $clave) && Registrar_Suscripcion($conn, $rut, $suscripcion)) {
                            echo "<div class='alert alert-success mt-4' role='alert'>Nuevo Usuario agregado exitosamente</div>";
                        } else {
                            echo "<div class='alert alert-danger mt-4' role='alert'>\nError al agregar el Usuario</div>";
                        }
                    }
                } else {
                    echo "<div class='alert alert-danger mt-4' role='alert'>\nError: Rut Invalido</div>";
                }

                $conn->close();
            }
            ?>
        </div>
    </body>
</html>
<?php
ob_end_flush();
?>
