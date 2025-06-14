<?php 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ejercicios = $_POST['ejerccios'];

    foreach ($ejercicios as $ejercicio) {
        $nombre = $ejercicio['nombre'];
        $repeticiones = $ejercicio['repeticiones'];
        $series = $ejercicio['series'];
    }
    
    exit();
}
?>