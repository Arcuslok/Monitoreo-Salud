<!DOCTYPE html>
<?php
require_once 'funciones\Funciones PHP.php';
require_once 'funciones\Funciones SQL.php';
session_start();
ob_start();

// Control de sesión
if (isset($_SESSION['Datos'])) {
  $Datos = $_SESSION['Datos'];
  $Autenticado = isset($Datos['Usuario_autenticado']) ? $Datos['Usuario_autenticado'] : false;


  if ($Autenticado == true) {
    header('Location: Página Principal Usuario.php');
    exit();
  }
}

?>
<html lang="es">
  <head>
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
    <div class="bg-image">
      <div class="container d-flex flex-column justify-content-center align-items-center min-vh-100">
        <!-- Título en blanco -->
        <h1 class="mb-4">Bienvenido a SaludTracker</h1>
        <div class="card p-4">
          <form id="formulario_iniciar_sesion" method="post" autocomplete="off">
            <div class="form-group">
              <label for="usuario">Usuario</label>
              <input type="text" class="form-control" id="usuario" name="usuario" required>
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
          if (isset($_POST['iniciar_sesion']) && $_SERVER["REQUEST_METHOD"] == "POST") {
            $Usuario = Validar_Input($_POST['usuario']);
            $Clave = Validar_Input($_POST['clave']);

            $conn = Conectar_Base_Datos();

            // Verificar si el usuario y token coinciden en la base de datos
            $stmt = $conn->prepare("SELECT Clave_usuario FROM registro_usuarios WHERE Correo_usuario = ?");
            $stmt->bind_param("s", $Usuario);
            $stmt->execute();
            $stmt->bind_result($token);
            $stmt->fetch();
            $stmt->close();

            // Verificar si el token coincide con el proporcionado
            if ($token && $token === $Clave) {
              $_SESSION['Datos'] = [
                'Correo_usuario' => $Usuario,
                'Clave_usuario' => $Clave,
                'Estado_Pulsera_usuario' => false,
                'Estado_Pulso_usuario' => false,
                'Estado_Pasos_usuario' => false,
                'Estado_Temperatura_usuario' => false,
                'Usuario_autenticado' => true
              ];

              Establecer_Sesion_Usuario($conn, $Usuario, true);
              $conn->close();
              header("Location: Página Principal Usuario.php");
              exit();
            } else {
              echo "<div class='alert alert-danger mt-4' role='alert'>Error Usuario / Clave Invalido</div>";
            }

            $conn->close();
          }
          ?>
        </div>
      </div>
    </div>
  </body>
</html>
<?php
ob_end_flush();
?>
