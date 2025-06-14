<?php
require_once '../funciones/Funciones SQL.php';

session_start();

$Datos = $_SESSION['Datos'];
$Correo = $Datos['Correo_usuario'];
$Clave = $Datos['Clave_usuario'];
$Estado_Pulsera = $Datos['Estado_Pulsera_usuario'];
$Estado_Pulso = $Datos['Estado_Pulso_usuario'];
$Estado_Pasos = $Datos['Estado_Pasos_usuario'];
$Estado_Temperatura = $Datos['Estado_Temperatura_usuario'];
$Autenticado = $Datos['Usuario_autenticado'];

$conn = Conectar_Base_Datos();

$Rut = Obtener_Rut_Usuario($conn, $Correo);

Establecer_Estado_Pasos($conn, $Rut, true);

$conn->close();

$_SESSION['Datos'] = [
    'Correo_usuario' => $Correo,
    'Clave_usuario' => $Clave,
    'Estado_Pulsera_usuario' => $Estado_Pulsera,
    'Estado_Pulso_usuario' => $Estado_Pulso,
    'Estado_Pasos_usuario' => true,
    'Estado_Temperatura_usuario' => $Estado_Temperatura,
    'Usuario_autenticado' => true
];

?>
