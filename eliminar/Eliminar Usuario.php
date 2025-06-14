<?php
include '../funciones/Funciones SQL.php';
include '../plantillas/Plantilla Usuario.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {

    $rut = (int) $_GET['id'];
    $usuario = new Usuario($rut, '', '', '', '', '', '', '', '');

    $conn = Conectar_Base_Datos();

    $pulsera = Obtener_Pulsera_Usuario($conn, $rut);
    $correo = Obtener_Correo_Usuario($conn, $rut);

    $sesion = Obtener_Sesion_Usuario($conn, $correo);
    if ($sesion) {
        header("Location: ../Página Principal Administrar Usuarios.php?alerta=Error al eliminar el Usuario: Sesión Iniciada");
        exit();
    }

    $borrado = $usuario->borrarUsuario($conn, $rut);
    
    Eliminar_Registro_Suscripcion($conn, $rut);
    Eliminar_Registro_Usuario($conn, $correo);
    Eliminar_Registro_Pulsera($conn, $pulsera);
    Eliminar_Pulsera($conn, $pulsera);

    $conn->close();

    if ($borrado) {
        header("Location: ../Página Principal Administrar Usuarios.php");
        exit();
    } else {
        header("Location: ../Página Principal Administrar Usuarios.php?alerta=Error al eliminar el Usuario");
        exit();
    }

} else {
    header("Location: ../Página Principal Administrar Usuarios.php?alerta=Rut de Usuario no proporcionado");
    exit();
}

?>