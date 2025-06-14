<?php
class Pulsera {

    private $id;
    private $estado_conexion;
    private $estado_pulso;
    private $estado_pasos;
    private $estado_temperatura;

    public function __construct($id, $estado_conexion, $estado_pulso, $estado_pasos, $estado_temperatura) {

        $this->id = $id;
        $this->estado_conexion = $estado_conexion;
        $this->estado_pulso = $estado_pulso;
        $this->estado_pasos = $estado_pasos;
        $this->estado_temperatura = $estado_temperatura;

    }

    public function crearPulsera($conn) {

        $stmt = $conn->prepare("INSERT INTO pulsera (ID_pulsera, Estado_conexion_pulsera, Estado_pulso_pulsera, Estado_pasos_pulsera, Estado_temperatura_pulsera) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $this->id, $this->estado_conexion, $this->estado_pulso, $this->estado_pasos, $this->estado_temperatura);
    
        if ($stmt->execute()) {
            return true;
        }
        else {
            return false;
        }

        $stmt->close();

    }

    public static function listarPulsera($conn) {

        $stmt = $conn->prepare("SELECT ID_pulsera, Estado_conexion_pulsera, Estado_pulso_pulsera, Estado_pasos_pulsera, Estado_temperatura_pulsera FROM pulsera");

        if ($stmt->execute()){
            $result = $stmt->get_result();
            $pulseras = [];
            while ($fila = $result->fetch_assoc()){
                $pulseras[] = $fila;
            }
            return $pulseras;
        }
        else {
            return false;
        }

        $stmt->close();

    }

}
?>