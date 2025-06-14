<?php
require_once '../funciones/Funciones SQL.php';

// Iniciar la sesión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['Datos']) || !$_SESSION['Datos']['Usuario_autenticado']) {
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

// Conectar a la base de datos
$Conn = Conectar_Base_Datos();

// Verificar si se ha recibido el parámetro 'rut'
if ($Conn) {

    $Datos = $_SESSION['Datos'];
    $Rut = Obtener_Rut_Usuario($Conn, $Datos['Correo_usuario']); 
    $Pulsera = Obtener_Pulsera_Usuario($Conn, $Rut);
    $Registros = Obtener_Registro_Pulsera_Usuario($Conn, $Pulsera);

    // Devolver los ejercicios en formato JSON
    echo json_encode(['registros' => $Registros]);

} else {
    echo json_encode(['error' => 'Parámetro RUT no recibido']);
}

// Cerrar la conexión a la base de datos
mysqli_close($Conn);
?>
