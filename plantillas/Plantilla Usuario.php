<?php
class Usuario {

    private $rut;
    private $nombre;
    private $apellido;
    private $direccion;
    private $correo;
    private $fono;
    private $entrenador;
    private $suscripcion;
    private $pulsera;
    private $sesion_activa;

    public function __construct($rut, $nombre, $apellido, $direccion, $correo, $fono, $entrenador, $suscripcion, $pulsera) {

        $this->rut = $rut;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->direccion = $direccion;
        $this->correo = $correo;
        $this->fono = $fono;
        $this->entrenador = $entrenador;
        $this->suscripcion = $suscripcion;
        $this->pulsera = $pulsera;
        $this->sesion_activa = false;

    }

    public function crearUsuario($conn) {
        // Verificar si el entrenador existe
        $entrenadorExist = $conn->prepare("SELECT Rut_entrenador FROM entrenador WHERE Rut_entrenador = ?");
        $entrenadorExist->bind_param("s", $this->entrenador);
        $entrenadorExist->execute();
        $entrenadorExist->store_result();
        
        if ($entrenadorExist->num_rows == 0) {
            printf("Error: El entrenador con Rut %s no existe.\n", $this->entrenador);
            $entrenadorExist->close();
            return false;
        }
        
        $entrenadorExist->close();
        
        // Insertar usuario
        $stmt = $conn->prepare("INSERT INTO usuario (Rut_usuario, Nombre_usuario, Apellido_usuario, Direccion_usuario, Correo_usuario, Fono_usuario, Entrenador_usuario, Suscripcion_usuario, Pulsera_usuario, Sesion_activa) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssiiis", $this->rut, $this->nombre, $this->apellido, $this->direccion, $this->correo, $this->fono, $this->entrenador, $this->suscripcion, $this->pulsera, $this->sesion_activa);

        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            printf("Error: %s.\n", $stmt->error);
            $stmt->close();
            return false;
        }
    }
    
    
    public function editarUsuario($conn) {

        $stmt = $conn->prepare("UPDATE usuario SET Nombre_usuario = ?, Apellido_usuario = ?, Direccion_usuario = ?, Correo_usuario = ?, Fono_usuario = ?, Entrenador_usuario = ?, Suscripcion_usuario = ?, Pulsera_usuario = ? WHERE Rut_usuario = ?");
        $stmt->bind_param("ssssssiii", $this->nombre, $this->apellido, $this->direccion, $this->correo, $this->fono, $this->entrenador, $this->suscripcion, $this->pulsera, $this->rut);
    
        if ($stmt->execute()) { 
            return true; 
        } else { 
            return false; 
        }
        $stmt->close();

    }

    public static function listarUsuario($conn) {

        $stmt = $conn->prepare("SELECT Rut_usuario, Nombre_usuario, Apellido_usuario, Direccion_usuario, Correo_usuario, Fono_usuario, Entrenador_usuario, Suscripcion_usuario, Pulsera_usuario, Sesion_activa FROM usuario");

        if ($stmt->execute()){
            $result = $stmt->get_result();
            $usuarios = [];
            while ($fila = $result->fetch_assoc()){
                $usuarios[] = $fila;
            }
            return $usuarios;
        }
        else {
            return false;
        }

        $stmt->close();

    }

    public static function borrarUsuario($conn, $rut) {

        $stmt = $conn->prepare("DELETE FROM usuario WHERE Rut_usuario = ?");
        $stmt->bind_param("i", $rut);

        if ($stmt->execute()) { return true; } 
        else { return false; }

        $stmt->close();

    }

}
?>