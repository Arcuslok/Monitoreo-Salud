<!DOCTYPE html>
<?php
require_once '../funciones/Funciones PHP.php';
require_once '../funciones/Funciones SQL.php';

clearstatcache();
$conn = Conectar_Base_Datos();
?>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
        <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
        <title>Agregar Entrenador</title>
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
            <h2>Agregar Entrenador</h2>
            <form action="Agregar Entrenador.php" method="post" autocomplete="off">
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
                        <label for="fono">Fono:</label>
                        <input type="text" class="fono" id="fono" name="fono" onkeypress="ValidaSoloNumeros(event);" required>
                    </div>
                    <div class="col-md-6">
                        <label for="clave">Contraseña:</label>
                        <input type="password" class="clave" id="clave" name="clave" required>
                    </div>
                </div>
                <button type="submit" class="agregar">Agregar</button>
                <a href="../Página Principal Administrar Entrenadores.php" class="listado">Administrar Entrenadores</a>
            </form>
            <?php
            include "../plantillas/Plantilla Entrenador.php";

            if (isset($_POST['rut']) && $_SERVER["REQUEST_METHOD"] == "POST") {

                $rut = Validar_Input($_POST['rut']);
                $nombre = Validar_Input($_POST['nombre']);
                $apellido = Validar_Input($_POST['apellido']);
                $correo = Validar_Input($_POST['correo']);
                $fono = Validar_Input($_POST['fono']);
                $clave = $_POST['clave'];

                if (validarRUT($rut)) {

                    $rut_existente = Existe_Entrenador($conn, $rut);
                    $correo_existente = Existe_Correo_Entrenador($conn, $correo);
                    $fono_existente = Existe_Fono_Entrenador($conn, $fono);
                    $clave_existente = Existe_Clave_Entrenador($conn, $clave);

                    if ($rut_existente == 1 && $correo_existente == 1 && $fono_existente == 1 && $clave_existente == 1) {
                        echo "<div class='alert alert-danger mt-4' role='alert'>\nError: El Rut, Correo, Teléfono y Clave ya existen en la Base</div>";
                    }
                    elseif ($rut_existente == 1 && $correo_existente == 1 && $fono_existente == 1) {
                        echo "<div class='alert alert-danger mt-4' role='alert'>\nError: El Rut, Correo y Teléfono ya existen en la Base</div>";
                    }
                    elseif ($rut_existente == 1 && $correo_existente == 1 && $clave_existente == 1) {
                        echo "<div class='alert alert-danger mt-4' role='alert'>\nError: El Rut, Correo y Clave ya existen en la Base</div>";
                    }
                    elseif ($rut_existente == 1 && $fono_existente == 1 && $clave_existente == 1) {
                        echo "<div class='alert alert-danger mt-4' role='alert'>\nError: El Rut, Teléfono y Clave ya existen en la Base</div>";
                    }
                    elseif ($correo_existente == 1 && $fono_existente == 1 && $clave_existente == 1) {
                        echo "<div class='alert alert-danger mt-4' role='alert'>\nError: El Correo, Teléfono y Clave ya existen en la Base</div>";
                    }
                    elseif ($rut_existente == 1 && $correo_existente == 1) {
                        echo "<div class='alert alert-danger mt-4' role='alert'>\nError: El Rut y el Correo ya existen en la Base</div>";
                    }
                    elseif ($rut_existente == 1 && $fono_existente == 1) {
                        echo "<div class='alert alert-danger mt-4' role='alert'>\nError: El Rut y el Teléfono ya existen en la Base</div>";
                    }
                    elseif ($rut_existente == 1 && $clave_existente == 1) {
                        echo "<div class='alert alert-danger mt-4' role='alert'>\nError: El Rut y la Clave ya existen en la Base</div>";
                    }
                    elseif ($correo_existente == 1 && $fono_existente == 1) {
                        echo "<div class='alert alert-danger mt-4' role='alert'>\nError: El Correo y el Teléfono ya existen en la Base</div>";
                    }
                    elseif ($correo_existente == 1 && $clave_existente == 1) {
                        echo "<div class='alert alert-danger mt-4' role='alert'>\nError: El Correo y la Clave ya existen en la Base</div>";
                    }
                    elseif ($fono_existente == 1 && $clave_existente == 1) {
                        echo "<div class='alert alert-danger mt-4' role='alert'>\nError: El Teléfono y la Clave ya existen en la Base</div>";
                    }
                    elseif ($rut_existente == 1) {
                        echo "<div class='alert alert-danger mt-4' role='alert'>\nError: El Rut ya existe en la Base</div>";
                    }
                    elseif ($correo_existente == 1) {
                        echo "<div class='alert alert-danger mt-4' role='alert'>\nError: El Correo ya existe en la Base</div>";
                    }
                    elseif ($fono_existente == 1) {
                        echo "<div class='alert alert-danger mt-4' role='alert'>\nError: El Teléfono ya existe en la Base</div>";
                    }
                    elseif ($clave_existente == 1) {
                        echo "<div class='alert alert-danger mt-4' role='alert'>\nError: La Clave ya existe en la Base</div>";
                    }

                    else {
                        $entrenador = new Entrenador($rut, $nombre, $apellido, $correo, $fono, true);
                        
                        if ($entrenador->crearEntrenador($conn) && Registrar_Entrenador($conn, $correo, $clave)) {
                            echo "<div class='alert alert-success mt-4' role='alert'>Nuevo Entrenador agregado exitosamente</div>";
                        } else {
                            echo "<div class='alert alert-danger mt-4' role='alert'>\nError al agregar al Entrenador</div>";
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
