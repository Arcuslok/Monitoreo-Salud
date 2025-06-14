<?php
require_once '../funciones/Funciones SQL.php';

// Conectar a la base de datos
$Conn = Conectar_Base_Datos();

$idEjercicio = $_GET['id'] ?? null;

if (!$idEjercicio) {
    exit; // Detener el script si no hay ID
}

// Eliminar el ejercicio de la base de datos
$nombre_eliminado = Eliminar_Ejercicio($Conn, $idEjercicio);

// Cerrar la conexión a la base de datos
mysqli_close($Conn);

header("Location: ../Página Principal Ejercicios.php?alerta=Ejercicio eliminado: ". $nombre_eliminado);
exit();
