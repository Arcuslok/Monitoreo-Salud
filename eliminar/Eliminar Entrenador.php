<?php
include '../funciones/Funciones SQL.php';
include '../plantillas/Plantilla Entrenador.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {

    $rut = (int) $_GET['id'];
    $entrenador = new Entrenador($rut, '', '', '', '', '');

    $conn = Conectar_Base_Datos();
    $usuarios = Obtener_Nombre_Apellido_Usuario($conn, $rut);

    if (count($usuarios) > 0) {
        $mensaje = "Error, el Entrenador/a no se puede eliminar, ha sido asignado a: ";

        foreach ($usuarios as $usuario) {
            $mensaje .= "[".$usuario["Nombre_usuario"] . " " . $usuario["Apellido_usuario"] . "]";
        }

        header("Location: Página Principal Administrar Entrenadores.php?alerta=" . urlencode($mensaje));
        exit();
    }
    else {
        $correo = Obtener_Correo_Entrenador($conn, $rut);

        $borrado = $entrenador->borrarEntrenador($conn, $rut);
        
        Eliminar_Registro_Entrenador($conn, $correo);

        $conn->close();

        if ($borrado) {
            header("Location: ../Página Principal Administrar Entrenadores.php");
            exit();
        } else {
            header("Location: ../Página Principal Administrar Entrenadores.php?alerta=Error al eliminar el Entrenador");
            exit();
        }
    }

} else {
    header("Location: ../Página Principal Administrar Entrenadores.php?alerta=Rut de Entrenador no proporcionado");
    exit();
}

?>