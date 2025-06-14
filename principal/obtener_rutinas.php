<?php
require_once '../funciones/Funciones SQL.php'; // Incluye tu archivo de conexión a la base de datos

if (isset($_GET['rutUsuario'])) {
    $conn = Conectar_Base_Datos();
    $rutUsuario = $_GET['rutUsuario'];
    $rutinas = Obtener_Rutinas_Usuario($conn, $rutUsuario); // Debe devolver un array de rutinas

    // Aquí puedes formatear la respuesta
    if (!empty($rutinas)) {
        echo json_encode(['rutinas' => $rutinas]); // Asegúrate de que las rutinas estén en el formato correcto
    } else {
        echo json_encode(['error' => 'No hay rutinas disponibles para este usuario.']);
    }
}
?>
