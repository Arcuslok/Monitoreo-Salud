<?php
class Entrenador {

    private $rut;
    private $nombre;
    private $apellido;
    private $correo;
    private $fono;
    private $disponibilidad;
    private $sesion_activa;

    public function __construct($rut, $nombre, $apellido, $correo, $fono, $disponibilidad) {

        $this->rut = $rut;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->correo = $correo;
        $this->fono = $fono;
        $this->disponibilidad = $disponibilidad;
        $this->sesion_activa = false;

    }

    public function crearEntrenador($conn) {

        $stmt = $conn->prepare("INSERT INTO entrenador (Rut_entrenador, Nombre_entrenador, Apellido_entrenador, Correo_entrenador, Fono_entrenador, Disponibilidad_entrenador, Sesion_activa) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $this->rut, $this->nombre, $this->apellido, $this->correo, $this->fono, $this->disponibilidad, $this->sesion_activa);

        if ($stmt->execute()) { return true; }
        else { return false; }
        $stmt->close();

    }
    
    

    public function editarEntrenador($conn) {

        $stmt = $conn->prepare("UPDATE entrenador SET Nombre_entrenador = ?, Apellido_entrenador = ?, Correo_entrenador = ?, Fono_entrenador = ?, Disponibilidad_entrenador = ? WHERE Rut_entrenador = ?");
        $stmt->bind_param("sssss", $this->nombre, $this->apellido, $this->correo, $this->fono, $this->disponibilidad, $this->rut);
    
        if ($stmt->execute()) { 
            $stmt->close();
            return true; 
        } else { 
            $stmt->close();
            return false; 
        }
    }

    public static function listarEntrenador($conn) {

        $stmt = $conn->prepare("SELECT Rut_entrenador, Nombre_entrenador, Apellido_entrenador, Correo_entrenador, Fono_entrenador, Disponibilidad_entrenador, Sesion_activa FROM entrenador");

        if ($stmt->execute()){
            $result = $stmt->get_result();
            $estudantes = [];
            while ($fila = $result->fetch_assoc()){
                $estudantes[] = $fila;
            }
            return $estudantes;
        }
        else {
            return false;
        }

        $stmt->close();

    }

    public static function borrarEntrenador($conn, $rut) {

        $stmt = $conn->prepare("DELETE FROM entrenador WHERE Rut_entrenador = ?");
        $stmt->bind_param("i", $rut);

        if ($stmt->execute()) { return true; } 
        else { return false; }

        $stmt->close();

    }

}
?>