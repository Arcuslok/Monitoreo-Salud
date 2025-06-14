<?php
require_once '../funciones/Funciones SQL.php';
require_once '../plantillas/Plantilla Pulsera.php';

$conn = Conectar_Base_Datos();

$pulseras = Pulsera::listarPulsera($conn);

if ($pulseras) {
    foreach ($pulseras as $pulsera) {
        echo "<tr>";   
        echo "<td style='text-align: center;'>".$pulsera["ID_pulsera"]."</td>";   

        $conexion = $pulsera["Estado_conexion_pulsera"];
        $pulso = $pulsera["Estado_pulso_pulsera"];
        $pasos = $pulsera["Estado_pasos_pulsera"];
        $temperatura = $pulsera["Estado_temperatura_pulsera"];

        if ($conexion == 1) {
            echo "<td style='text-align: center; font-weight: bold; color: green;'> CONECTADO </td>";
            echo ($pulso == 1) ? "<td style='text-align: center; font-weight: bold; color: green;'> OPERATIVO </td>" : "<td style='text-align: center; font-weight: bold; color: red;'> INOPERATIVO </td>";
            echo ($pasos == 1) ? "<td style='text-align: center; font-weight: bold; color: green;'> OPERATIVO </td>" : "<td style='text-align: center; font-weight: bold; color: red;'> INOPERATIVO </td>";
            echo ($temperatura == 1) ? "<td style='text-align: center; font-weight: bold; color: green;'> OPERATIVO </td>" : "<td style='text-align: center; font-weight: bold; color: red;'> INOPERATIVO </td>";
        } else {
            echo "<td style='text-align: center; font-weight: bold; color: red;'> DESCONECTADO </td>";
            echo "<td style='text-align: center; font-weight: bold; color: red;'> SIN CONEXIÓN </td>";
            echo "<td style='text-align: center; font-weight: bold; color: red;'> SIN CONEXIÓN </td>";
            echo "<td style='text-align: center; font-weight: bold; color: red;'> SIN CONEXIÓN </td>";
        }
        echo "</tr>";   
    }
} else {
    echo "<tr><td colspan='10'>No hay Pulseras de Usuarios registrados</td></tr>";
}
?>


