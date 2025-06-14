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

$Datos = $_SESSION['Datos'];
$Correo = $Datos['Correo_usuario'];

// Obtener el rut del usuario
$rut = Obtener_Rut_Usuario($Conn, $Correo);
$pulsera = Obtener_Pulsera_Usuario($Conn, $rut);

// Obtener los datos de la solicitud GET
$ejercicio = $_GET['ejercicio'] ?? null;
$ejercicio = Obtener_Id_Ejericio($Conn, $ejercicio);
$repeticion = $_GET['repeticion'] ?? null;
$serie = $_GET['serie'] ?? null;
$fecha = $_GET['fecha'] ?? null;
$pulso = $_GET['pulso'] ?? null;
$pasos = $_GET['pasos'] ?? null;
$calorias = $_GET['calorias'] ?? null;
$temperatura = $_GET['temperatura'] ?? null;

// Construye el mensaje para la alerta
$mensaje = "Ejercicio: $ejercicio\n";
$mensaje .= "Repetición: $repeticion\n";
$mensaje .= "Serie: $serie\n";
$mensaje .= "Fecha: $fecha\n";
$mensaje .= "Pulso: $pulso\n";
$mensaje .= "Pasos: $pasos\n";
$mensaje .= "Calorías: $calorias\n";
$mensaje .= "Temperatura: $temperatura";

// Muestra la alerta en el navegador con JavaScript
echo $mensaje;

// Validar que los datos necesarios estén presentes
if ($ejercicio == null || $repeticion == null || $serie == null || $fecha == null || $pulso == null || $pasos == null || $calorias == null || $temperatura == null) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos para agregar al registro.']);
    exit;
}



// Actualizar los ejercicios del usuario en la base de datos
$resultado = Agregar_Registro_Pulsera($Conn, $pulsera, $ejercicio, $repeticion, $serie, $fecha, $pulso, $pasos, $calorias, $temperatura);

if ($resultado['success']) {
    echo json_encode(['success' => true, 'message' => 'Ejercicio agregado correctamente.']);
} else {
    echo json_encode(['success' => false, 'message' => $resultado['error']]);
}

// Cerrar la conexión a la base de datos
mysqli_close($Conn);
?>
