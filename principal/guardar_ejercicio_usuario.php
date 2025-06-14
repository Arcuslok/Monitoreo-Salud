<?php
require_once '../funciones/Funciones SQL.php';

session_start();

// Conectar a la base de datos
$Conn = Conectar_Base_Datos();

// Verificar si la sesión contiene los datos del usuario
if (!isset($_SESSION['Datos'])) {
    echo json_encode(['success' => false, 'message' => 'No se pudo obtener el usuario de la sesión.']);
    exit;
}


$rut = $_GET['rut_usuario'] ?? null;

// Obtener los datos de la solicitud GET
$id = $_GET['id'] ?? null;
$rutina = $_GET['rutina'] ?? null;
$nombre = $_GET['nombre'] ?? null;
$repeticiones = $_GET['repeticiones'] ?? null;
$series = $_GET['series'] ?? null;

// Validar que los datos necesarios estén presentes
if (!$id || !$rutina || !$nombre || !$repeticiones || !$series) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos para actualizar el ejercicio.']);
    exit;
}

// Actualizar los ejercicios del usuario en la base de datos
$resultado = Actualizar_Ejercicios_Usuario($Conn, $rut, $id, $rutina, $nombre, $repeticiones, $series);

// Verificar si la actualización fue exitosa
if ($resultado) {
    echo json_encode(['success' => true, 'message' => 'Ejercicio actualizado correctamente.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al actualizar el ejercicio.']);
}

// Cerrar la conexión a la base de datos
mysqli_close($Conn);
?>
