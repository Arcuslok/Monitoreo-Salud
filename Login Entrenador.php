<!DOCTYPE html>
<?php
require_once 'funciones/Funciones PHP.php';
require_once 'funciones/Funciones SQL.php';
session_start();
ob_start();

// Control de sesión
if (isset($_SESSION['Datos'])) {
  $Datos = $_SESSION['Datos'];
  $Autenticado = isset($Datos['Entrenador_autenticado']) ? $Datos['Entrenador_autenticado'] : false;

  if ($Autenticado == true) {
    header('Location: Página Principal Entrenador.php'); // Redirige correctamente al administrador de usuarios
    exit();
  }
}

?>
<html lang="es">
  <head>
    <!-- ESTO ES EL FONDO DE LA PAGINA -->
    <style>
      body {
        background-image: url("img/login.jpg");
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        background-attachment: fixed;
      }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión - SaludTracker</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/login.css">
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
      document.getElementById("formulario_iniciar_sesion").reset();
    </script>
  </head>
  <body>
    <div class="container d-flex flex-column justify-content-center align-items-center min-vh-100">
      <h1 class="mb-4">Bienvenido a SaludTracker</h1>
      <div class="card p-4">
        <form id="formulario_iniciar_sesion" method="post" autocomplete="off">
          <div class="form-group">
            <label for="entrenador">Entrenador</label>
            <input type="text" class="form-control" id="entrenador" name="entrenador" required>
          </div>
          <div class="form-group">
            <label for="clave">Contraseña</label>
            <input type="password" class="form-control" id="clave" name="clave" required>
          </div>
          <div class="form-group">
            <a href="#">¿Olvidé mi contraseña?</a>
          </div>
          <button type="submit" class="btn btn-primary mr-2" name="iniciar_sesion">Iniciar Sesión</button>
        </form>
        <?php
        if (isset($_POST['entrenador']) && $_SERVER["REQUEST_METHOD"] == "POST") {
          $Entrenador = Validar_Input($_POST['entrenador']);
          $Clave = Validar_Input($_POST['clave']);

          $conn = Conectar_Base_Datos();

          // Verificar si el entrenador ya existe en la base de datos
          $stmt = $conn->prepare("SELECT COUNT(*) FROM registro_entrenadores WHERE Correo_entrenador = ? AND Clave_entrenador = ?");
          $stmt->bind_param("ss", $Entrenador, $Clave);
          $stmt->execute();
          $stmt->bind_result($count);
          $stmt->fetch();
          $stmt->close();

          if ($count == 0) {
            echo "<div class='alert alert-danger mt-4' role='alert'>\nError Entrenador / Clave Inválido</div>";
          } else {
            $_SESSION['Datos'] = [
              'Correo_entrenador' => $Entrenador,
              'Clave_entrenador' => $Clave,
              'Entrenador_autenticado' => true
            ];
            Establecer_Sesion_Entrenador($conn, $Entrenador, true);
            $conn->close();
            header("Location: Página Principal Entrenador.php"); // Redirige a la página correcta
            exit();
          }

          $conn->close();
        }
        ?>
      </div>
    </div>
  </body>
</html>
<?php
ob_end_flush();
?>
