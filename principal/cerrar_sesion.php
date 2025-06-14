<?php
require_once '../funciones/Funciones SQL.php';

$conn = Conectar_Base_Datos();

session_start();
$Correo = $_SESSION['Datos']['Correo_usuario'];
Establecer_Sesion_Usuario($conn, $Correo, false);

$_SESSION['Datos'] = [
    'Usuario_autenticado' => false
];

header("Location: ../Login Usuario.php"); // Redirige a la página de inicio de sesión
exit();

?>
