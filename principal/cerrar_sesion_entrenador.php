<?php
require_once '../funciones/Funciones SQL.php';

$conn = Conectar_Base_Datos();

session_start();
$Correo = $_SESSION['Datos']['Correo_entrenador'];
Establecer_Sesion_Entrenador($conn, $Correo, false);

$_SESSION['Datos'] = [
    'Entrenador_autenticado' => false
];

header("Location: ../Login Entrenador.php"); // Redirige a la página de inicio de sesión
exit();

?>
