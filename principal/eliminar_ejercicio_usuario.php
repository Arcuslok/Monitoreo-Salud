<?php
require_once '../funciones/Funciones SQL.php';

session_start();

// Conectar a la base de datos
$Conn = Conectar_Base_Datos();

// Verificar si la sesión contiene los datos del usuario
if (!isset($_SESSION['Datos'])) {
    echo json_encode(['success' => false, 'message' => 'No se pudo obtener el usuario de la sesión.']);
    exit; // Asegúrate de detener el script si hay un error
}

$rut = $_GET['rut_usuario'] ?? null;
// Obtener el id del ejercicio a eliminar desde la solicitud
$idEjercicio = $_GET['id'] ?? null;

if (!$idEjercicio) {
    echo json_encode(['success' => false, 'message' => 'Falta el id del ejercicio para eliminar.']);
    exit; // Detener el script si no hay ID
}

// Eliminar el ejercicio de la base de datos
$resultado = Eliminar_Ejercicio_Usuario($Conn, $rut, $idEjercicio);

if ($resultado) {
    echo json_encode(['success' => true, 'message' => 'Ejercicio eliminado correctamente.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al eliminar el ejercicio.']);
    exit; // Detener el script si hay error en la eliminación
}

// Cerrar la conexión a la base de datos
mysqli_close($Conn);
