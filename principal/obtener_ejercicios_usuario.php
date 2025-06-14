<?php
require_once '../funciones/Funciones SQL.php';

// Iniciar la sesión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['Datos'])) {
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

// Conectar a la base de datos
$Conn = Conectar_Base_Datos();

// Verificar si se ha recibido el parámetro 'rut'
if ($Conn) {

    $Datos = $_SESSION['Datos'];
    $Rut = $_GET['rut_usuario'] ?? null;
    $Rutina = $_GET['rutina'] ?? null;

    // Consulta para obtener los ejercicios de la rutina asociada al RUT
    $ejercicios = Obtener_Ejercicios_Usuario($Conn, $Rutina, $Rut);

    // Devolver los ejercicios en formato JSON
    echo json_encode(['ejercicios' => $ejercicios]);

} else {
    echo json_encode(['error' => 'Parámetro RUT no recibido']);
}

// Cerrar la conexión a la base de datos
mysqli_close($Conn);
?>
