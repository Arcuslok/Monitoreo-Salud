<?php

function Conectar_Base_Datos() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "monitoreo_salud";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Conexión fallida". $conn->connect_error);
    }

    return $conn;
}

# Informacion Column Tablas

function Columnas_Tabla($conn, $nombre_tabla) {

    $consulta = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = ? AND TABLE_SCHEMA = 'monitoreo_salud'";

    if ($stmt = $conn->prepare($consulta)) {
        $stmt->bind_param("s", $nombre_tabla);
        $stmt->execute();
        $colummas = [];
        $resultado = $stmt->get_result();

        while ($fila = $resultado->fetch_assoc()) {
            $colummas[] = str_replace("_". $nombre_tabla, "", $fila['COLUMN_NAME']);
        }

        return $colummas;
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
    }

    $stmt->close();

}



// Existe

function Existe_Usuario($conn, $rut) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM usuario WHERE Rut_usuario = ?");
    $stmt->bind_param("s", $rut);

    if ($stmt->execute()) {
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count > 0;
    } else {
        $stmt->close();
        return false;
    }
}

function Existe_Correo_Usuario($conn, $correo) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM usuario WHERE Correo_usuario = ?");
    $stmt->bind_param("s", $correo);

    if ($stmt->execute()) {
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count > 0;
    } else {
        $stmt->close();
        return false;
    }
}

function Existe_Fono_Usuario($conn, $fono) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM usuario WHERE Fono_usuario = ?");
    $stmt->bind_param("s", $fono);

    if ($stmt->execute()) {
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count > 0;
    } else {
        $stmt->close();
        return false;
    }
}

function Existe_Clave_Usuario($conn, $clave) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM registro_usuarios WHERE Clave_usuario = ?");
    $stmt->bind_param("s", $clave);

    if ($stmt->execute()) {
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count > 0;
    } else {
        $stmt->close();
        return false;
    }
}

function Existe_Pulsera_Usuario($conn, $pulsera) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM pulsera WHERE ID_pulsera = ?");
    $stmt->bind_param("i", $pulsera);

    if ($stmt->execute()) {
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count > 0;
    } else {
        $stmt->close();
        return false;
    }
}


function Existe_Entrenador($conn, $rut) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM entrenador WHERE Rut_entrenador = ?");
    $stmt->bind_param("s", $rut);

    if ($stmt->execute()) {
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count > 0;
    } else {
        $stmt->close();
        return false;
    }
}

function Existe_Correo_Entrenador($conn, $correo) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM entrenador WHERE Correo_entrenador = ?");
    $stmt->bind_param("s", $correo);

    if ($stmt->execute()) {
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count > 0;
    } else {
        $stmt->close();
        return false;
    }
}

function Existe_Fono_Entrenador($conn, $fono) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM entrenador WHERE Fono_entrenador = ?");
    $stmt->bind_param("s", $fono);

    if ($stmt->execute()) {
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count > 0;
    } else {
        $stmt->close();
        return false;
    }
}

function Existe_Clave_Entrenador($conn, $clave) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM registro_entrenadores WHERE Clave_entrenador = ?");
    $stmt->bind_param("s", $clave);

    if ($stmt->execute()) {
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count > 0;
    } else {
        $stmt->close();
        return false;
    }
}

function Existe_Ejercicio($conn, $nombre) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM ejercicios WHERE Nombre_ejercicio = ?");
    $stmt->bind_param("s", $nombre);

    if ($stmt->execute()) {
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count > 0;
    } else {
        $stmt->close();
        return false;
    }
}




// Registrar

function Registrar_Usuario($conn, $correo, $clave) {

    $stmt = $conn->prepare("INSERT INTO registro_usuarios (Correo_usuario, Clave_usuario) VALUES (?, ?)");
    $stmt->bind_param("ss", $correo, $clave);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
    $stmt->close();

}

function Registrar_Entrenador($conn, $correo, $clave) {

    $stmt = $conn->prepare("INSERT INTO registro_entrenadores (Correo_entrenador, Clave_entrenador) VALUES (?, ?)");
    $stmt->bind_param("ss", $correo, $clave);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
    $stmt->close();

}

function Registrar_Suscripcion($conn, $rut, $tipo) {

    $stmt = $conn->prepare("INSERT INTO registro_suscripcion (Rut_usuario_suscripcion, Tipo_suscripcion, Fecha_pago_suscripcion, Fecha_inicio_suscripcion, Fecha_termino_suscripcion) VALUES (?, ?, ?, ?, ?)");
    
    $fecha_actual = date("Y-m-d");

    if ($tipo == "1") {
        $fecha_termino = date("Y-m-d", strtotime("+6 months", strtotime($fecha_actual)));
        $stmt->bind_param("iisss", $rut, $tipo, $fecha_actual, $fecha_actual, $fecha_termino);
    }
    elseif ($tipo == "2") {
        $fecha_termino = date("Y-m-d", strtotime("+1 years", strtotime($fecha_actual)));
        $stmt->bind_param("iisss", $rut, $tipo, $fecha_actual, $fecha_actual, $fecha_termino);

    }

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }

    $stmt->close();

}



// Obtener

function Obtener_Entrenadores_Disponibles($conn) {

    $consulta = "SELECT Nombre_entrenador, Apellido_entrenador FROM entrenador WHERE Disponibilidad_entrenador = 1;";

    if ($stmt = $conn->prepare($consulta)) {
        
        $stmt->execute();
        $entrenadores = [];
        $resultado = $stmt->get_result();

        while ($fila = $resultado->fetch_assoc()) {
            $entrenadores[] = $fila['Nombre_entrenador'] . " " . $fila['Apellido_entrenador'];
        }

        return $entrenadores;
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
    }
    $stmt->close();

}

function Obtener_Entrenadores_Filtrada($conn, $Rut) {

    $consulta = "SELECT Nombre_entrenador, Apellido_entrenador FROM entrenador WHERE Rut_entrenador != ?";

    if ($stmt = $conn->prepare($consulta)) {
        
        $stmt->bind_param("s", $Rut);
        $stmt->execute();
        

        $resultado = $stmt->get_result();
        $entrenadores = [];

        while ($fila = $resultado->fetch_assoc()) {
            $entrenadores[] = $fila['Nombre_entrenador'] . " " . $fila['Apellido_entrenador'];
        }


        $stmt->close();
        

        return $entrenadores;
    } else {

        echo "Error en la preparación de la consulta: " . $conn->error;
        return false;
    }

}

function Obtener_Entrenador_Rut($conn, $Correo) {

    $consulta = "SELECT Rut_entrenador FROM entrenador WHERE Correo_entrenador = ?";

    if ($stmt = $conn->prepare($consulta)) {
        
        $stmt->bind_param("s", $Correo);

        // Ejecuta la consulta
        $stmt->execute();

        // Obtiene el resultado de la consulta
        $resultado = $stmt->get_result();

        // Verifica si hay resultados
        if ($fila = $resultado->fetch_assoc()) {
            // Retorna el Rut_entrenador si se encontró
            return $fila['Rut_entrenador'];
        } else {
            // Si no se encontró, retorna un valor nulo o indicativo
            return null;
        }
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
        return false;
    }
}


function Obtener_Entrenadores_Usuarios($conn, $Rut) {
    $consulta = "SELECT usuario.Rut_usuario, usuario.Nombre_usuario, usuario.Apellido_usuario, usuario.Correo_usuario, usuario.Pulsera_usuario FROM entrenador LEFT JOIN usuario ON entrenador.Rut_entrenador = usuario.Entrenador_usuario WHERE entrenador.Rut_entrenador = ? AND usuario.Rut_usuario IS NOT NULL";

    if ($stmt = $conn->prepare($consulta)) {
        
        $stmt->bind_param("s", $Rut);
        $stmt->execute();
        
        $resultado = $stmt->get_result();
        $usuarios = [];

        // Construir array de usuarios como arrays asociativos
        while ($fila = $resultado->fetch_assoc()) {
            $usuarios[] = [
                'Rut_usuario' => $fila['Rut_usuario'],
                'Nombre_usuario' => $fila['Nombre_usuario'],
                'Apellido_usuario' => $fila['Apellido_usuario'],
                'Correo_usuario' => $fila['Correo_usuario'],
                'Pulsera_usuario' => $fila['Pulsera_usuario']
            ];
        }

        $stmt->close();
        
        return $usuarios;
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
        return false;
    }
}


function Obtener_Entrenador($conn, $Rut) {

    $consulta = "SELECT Nombre_entrenador, Apellido_entrenador FROM entrenador WHERE Rut_entrenador = ". $Rut .";";

    if ($stmt = $conn->prepare($consulta)) {
        
        $stmt->execute();
        $entrenador = "";
        $resultado = $stmt->get_result();

        while ($fila = $resultado->fetch_assoc()) {
            $entrenador = $fila['Nombre_entrenador'] . " " . $fila['Apellido_entrenador'];
        }

        return $entrenador;
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
    }
    $stmt->close();

}

function Obtener_Pulsera_Usuarios($conn) {

    $consulta = "SELECT * FROM `pulsera` WHERE 1";

    if ($stmt = $conn->prepare($consulta)) {
        $stmt->bind_param("i", $Rut); 
        $stmt->execute();
        
        $pulsera = "";
        $resultado = $stmt->get_result();

        while ($fila = $resultado->fetch_assoc()) {
            $pulsera = $fila['Pulsera_usuario'];
        }

        $stmt->close(); 
        return $pulsera;
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
        return false;
    }

    $stmt->close(); 

}

function Obtener_Pulsera_Usuario($conn, $Rut) {

    $consulta = "SELECT Pulsera_usuario FROM usuario WHERE Rut_usuario = ?";

    if ($stmt = $conn->prepare($consulta)) {
        $stmt->bind_param("i", $Rut); 
        $stmt->execute();
        
        $pulsera = "";
        $resultado = $stmt->get_result();

        while ($fila = $resultado->fetch_assoc()) {
            $pulsera = $fila['Pulsera_usuario'];
        }

        $stmt->close(); 
        return $pulsera;
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
        return false;
    }

    $stmt->close(); 

}

function Obtener_Registro_Pulsera_Usuario($conn, $Pulsera) {

    $consulta = "SELECT rp.Fecha_registro, rp.Pulsaciones_registro, rp.Pasos_registro, rp.Calorias_registro, rp.Temperatura_registro, e.Nombre_ejercicio AS Nombre_ejercicio, rp.Serie FROM registro_pulsera rp JOIN ejercicios e ON rp.Ejercicio = e.ID_ejercicio WHERE ID_registro_pulsera = ?";

    if ($stmt = $conn->prepare($consulta)) {
        $stmt->bind_param("s", $Pulsera); 
        $stmt->execute();
        
        $registro = [];
        $resultado = $stmt->get_result();

        while ($fila = $resultado->fetch_assoc()) {
            $registro[] = [
                'fecha' => $fila['Fecha_registro'],
                'pulsacion' => $fila['Pulsaciones_registro'],
                'paso' => $fila['Pasos_registro'],
                'caloria' => $fila['Calorias_registro'],
                'temperatura' => $fila['Temperatura_registro'],
                'ejercicio' => $fila['Nombre_ejercicio'],
                'serie' => $fila['Serie']
            ];
        }

        $stmt->close(); 
        return $registro;
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
        return false;
    }

    $stmt->close(); 

}

function Obtener_Sesion_Usuario($conn, $Correo) {

    $consulta = "SELECT Sesion_activa FROM usuario WHERE Correo_usuario = ?";

    if ($stmt = $conn->prepare($consulta)) {
        $stmt->bind_param("s", $Correo); 
        $stmt->execute();
        
        $sesion = "";
        $resultado = $stmt->get_result();

        while ($fila = $resultado->fetch_assoc()) {
            $sesion = $fila['Sesion_activa'];
        }

        $stmt->close(); 
        return $sesion;
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
        return false;
    }

    $stmt->close(); 

}

function Obtener_Sesion_Entrenador($conn, $Correo) {

    $consulta = "SELECT Sesion_activa FROM entrenador WHERE Correo_entrenador = ?";

    if ($stmt = $conn->prepare($consulta)) {
        $stmt->bind_param("s", $Correo); 
        $stmt->execute();
        
        $sesion = "";
        $resultado = $stmt->get_result();

        while ($fila = $resultado->fetch_assoc()) {
            $sesion = $fila['Sesion_activa'];
        }

        $stmt->close(); 
        return $sesion;
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
        return false;
    }

    $stmt->close(); 

}


function Establecer_Sesion_Usuario($conn, $Correo, $Estado) {

    $consulta = "UPDATE usuario SET Sesion_activa = ? WHERE Correo_usuario = ?";
    
    $stmt = $conn->prepare($consulta);
    if (!$stmt) {
        die("Error al preparar la consulta: " . $conn->error); // Depurar error de preparación
    }

    $stmt->bind_param("ss", $Estado, $Correo);

    if ($stmt->execute()) {
        return true;
    } else {
        die("Error al ejecutar la consulta: " . $stmt->error); // Depurar error de ejecución
    }

    $stmt->close();
}

function Establecer_Sesion_Entrenador($conn, $Correo, $Estado) {

    $consulta = "UPDATE entrenador SET Sesion_activa = ? WHERE Correo_entrenador = ?";
    
    $stmt = $conn->prepare($consulta);
    if (!$stmt) {
        die("Error al preparar la consulta: " . $conn->error); // Depurar error de preparación
    }

    $stmt->bind_param("ss", $Estado, $Correo);

    if ($stmt->execute()) {
        return true;
    } else {
        die("Error al ejecutar la consulta: " . $stmt->error); // Depurar error de ejecución
    }

    $stmt->close();
}


function Establecer_Estado_Pulsera($conn, $Rut, $Estado) {

    $consulta = "UPDATE pulsera SET Estado_conexion_pulsera = ? WHERE ID_pulsera = (SELECT Pulsera_usuario FROM usuario WHERE Rut_usuario = ?)";
    
    $stmt = $conn->prepare($consulta);
    if (!$stmt) {
        die("Error al preparar la consulta: " . $conn->error); // Depurar error de preparación
    }

    $stmt->bind_param("is", $Estado, $Rut);

    if ($stmt->execute()) {
        return true;
    } else {
        die("Error al ejecutar la consulta: " . $stmt->error); // Depurar error de ejecución
    }

    $stmt->close();
}

function Establecer_Estado_Pulso($conn, $Rut, $Estado) {

    $consulta = "UPDATE pulsera SET Estado_pulso_pulsera = ? WHERE ID_pulsera = (SELECT Pulsera_usuario FROM usuario WHERE Rut_usuario = ?)";
    
    $stmt = $conn->prepare($consulta);
    if (!$stmt) {
        die("Error al preparar la consulta: " . $conn->error); // Depurar error de preparación
    }

    $stmt->bind_param("is", $Estado, $Rut);

    if ($stmt->execute()) {
        return true;
    } else {
        die("Error al ejecutar la consulta: " . $stmt->error); // Depurar error de ejecución
    }

    $stmt->close();
}

function Establecer_Estado_Pasos($conn, $Rut, $Estado) {

    $consulta = "UPDATE pulsera SET Estado_pasos_pulsera = ? WHERE ID_pulsera = (SELECT Pulsera_usuario FROM usuario WHERE Rut_usuario = ?)";
    
    $stmt = $conn->prepare($consulta);
    if (!$stmt) {
        die("Error al preparar la consulta: " . $conn->error); // Depurar error de preparación
    }

    $stmt->bind_param("is", $Estado, $Rut);

    if ($stmt->execute()) {
        return true;
    } else {
        die("Error al ejecutar la consulta: " . $stmt->error); // Depurar error de ejecución
    }

    $stmt->close();
}

function Establecer_Estado_Temperatura($conn, $Rut, $Estado) {

    $consulta = "UPDATE pulsera SET Estado_temperatura_pulsera = ? WHERE ID_pulsera = (SELECT Pulsera_usuario FROM usuario WHERE Rut_usuario = ?)";
    
    $stmt = $conn->prepare($consulta);
    if (!$stmt) {
        die("Error al preparar la consulta: " . $conn->error); // Depurar error de preparación
    }

    $stmt->bind_param("is", $Estado, $Rut);

    if ($stmt->execute()) {
        return true;
    } else {
        die("Error al ejecutar la consulta: " . $stmt->error); // Depurar error de ejecución
    }

    $stmt->close();
}

function Obtener_Correo_Usuario($conn, $Rut) {

    $consulta = "SELECT Correo_usuario FROM usuario WHERE Rut_usuario = ?";

    if ($stmt = $conn->prepare($consulta)) {
        $stmt->bind_param("i", $Rut); 
        $stmt->execute();
        
        $correo = "";
        $resultado = $stmt->get_result();

        while ($fila = $resultado->fetch_assoc()) {
            $correo = $fila['Correo_usuario'];
        }

        $stmt->close(); 
        return $correo;
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
        return false;
    }

    $stmt->close(); 

}

function Obtener_Rut_Usuario($conn, $Correo) {

    $consulta = "SELECT Rut_usuario FROM usuario WHERE Correo_usuario = ?";

    if ($stmt = $conn->prepare($consulta)) {
        $stmt->bind_param("s", $Correo); 
        $stmt->execute();
        
        $rut = "";
        $resultado = $stmt->get_result();

        while ($fila = $resultado->fetch_assoc()) {
            $rut = $fila['Rut_usuario'];
        }

        $stmt->close(); 
        return $rut;
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
        return false;
    }

    $stmt->close(); 

}

function Obtener_Rutinas_Usuario($conn, $Rut) {

    $consulta = "SELECT DISTINCT r.Descripcion_rutina FROM rutinas r JOIN usuario u ON r.RUT_usuario = u.Rut_usuario WHERE u.Rut_usuario = ?";

    if ($stmt = $conn->prepare($consulta)) {
        $stmt->bind_param("s", $Rut); 
        $stmt->execute();
        
        $rutinas = [];
        $resultado = $stmt->get_result();

        while ($fila = $resultado->fetch_assoc()) {
            $rutinas[] = $fila['Descripcion_rutina'];
        }

        $stmt->close(); 
        return $rutinas;
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
        return false;
    }

    $stmt->close(); 

}

function Obtener_Ejercicios($conn) {

    $consulta = "SELECT Nombre_ejercicio FROM ejercicios";

    if ($stmt = $conn->prepare($consulta)) {
        $stmt->execute();
        
        $ejercicios = [];
        $resultado = $stmt->get_result();

        while ($fila = $resultado->fetch_assoc()) {
            $ejercicios[] = $fila['Nombre_ejercicio'];
        }

        $stmt->close(); 
        return $ejercicios;
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
        return false;
    }

    $stmt->close(); 

}

function Eliminar_Ejercicio($conn, $Id) {
    // Consulta para obtener el nombre del ejercicio antes de eliminarlo
    $consulta_select = "SELECT Nombre_ejercicio FROM ejercicios WHERE ID_ejercicio = ?";
    
    if ($stmt_select = $conn->prepare($consulta_select)) {
        // Vincula el parámetro
        $stmt_select->bind_param("i", $Id); // 'i' indica que el parámetro es un entero
        $stmt_select->execute();
        
        // Obtener el resultado
        $resultado = $stmt_select->get_result();
        $ejercicio_eliminado = $resultado->fetch_assoc(); // Obtiene la fila

        // Ahora, eliminamos el ejercicio
        $stmt_select->close(); // Cierra la declaración de selección

        $consulta_delete = "DELETE FROM ejercicios WHERE ID_ejercicio = ?";
        
        if ($stmt_delete = $conn->prepare($consulta_delete)) {
            // Vincula el parámetro
            $stmt_delete->bind_param("i", $Id);
            $stmt_delete->execute();
            $stmt_delete->close(); // Cierra la declaración de eliminación
            
            // Devuelve el nombre del ejercicio eliminado, o null si no existe
            return $ejercicio_eliminado ? $ejercicio_eliminado['Nombre_ejercicio'] : null;
        } else {
            echo "Error en la preparación de la consulta de eliminación: " . $conn->error;
            return false;
        }
    } else {
        echo "Error en la preparación de la consulta de selección: " . $conn->error;
        return false;
    }
}


function Obtener_Ejercicios_Usuario($conn, $Rutina, $Rut) {

    $consulta = "SELECT r.ID_rutina, e.Nombre_ejercicio, e.Descripcion, r.Repeticiones, r.Series FROM monitoreo_salud.rutinas r JOIN monitoreo_salud.usuario u ON r.RUT_usuario = u.Rut_usuario JOIN monitoreo_salud.ejercicios e ON r.ID_Ejercicio = e.ID_ejercicio WHERE u.Rut_usuario = ? AND r.Descripcion_rutina = ?";

    if ($stmt = $conn->prepare($consulta)) {
        $stmt->bind_param("ss", $Rut, $Rutina); 
        $stmt->execute();
        
        $rutina = []; // Arreglo para guardar la rutina en el formato solicitado
        $resultado = $stmt->get_result();

        while ($fila = $resultado->fetch_assoc()) {
            // Agregar un arreglo asociativo a $rutina con las claves nombre, repeticiones y series
            $rutina[] = [
                'id' => $fila['ID_rutina'],
                'nombre' => $fila['Nombre_ejercicio'],
                'descripcion' => $fila['Descripcion'],
                'repeticiones' => $fila['Repeticiones'],
                'series' => $fila['Series']
            ];
        }

        $stmt->close(); 
        return $rutina; 
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
        return false;
    }

    $stmt->close(); 

}

function Obtener_Todo_Ejercicios($conn) {

    $consulta = "SELECT ID_ejercicio, Nombre_ejercicio, Descripcion FROM ejercicios WHERE 1";

    if ($stmt = $conn->prepare($consulta)) {
        $stmt->execute();
        
        $ejercicios = []; // Arreglo para guardar la rutina en el formato solicitado
        $resultado = $stmt->get_result();

        while ($fila = $resultado->fetch_assoc()) {
            // Agregar un arreglo asociativo a $rutina con las claves nombre, repeticiones y series
            $ejercicios[] = [
                'id' => $fila['ID_ejercicio'],
                'nombre' => $fila['Nombre_ejercicio'],
                'descripcion' => $fila['Descripcion'],
            ];
        }

        $stmt->close(); 
        return $ejercicios; 
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
        return false;
    }

    $stmt->close(); 

}

function Obtener_Id_Ejericio($conn, $ejercicio) {

    $consulta = "SELECT ID_ejercicio FROM ejercicios WHERE Nombre_ejercicio = ?";

    if ($stmt = $conn->prepare($consulta)) {
        $stmt->bind_param("s", $ejercicio); 
        $stmt->execute();
        
        $id = "";
        $resultado = $stmt->get_result();

        while ($fila = $resultado->fetch_assoc()) {
            $id = $fila['ID_ejercicio'];
        }

        $stmt->close(); 
        return $id; 
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
        return false;
    }

    $stmt->close(); 

}

function Obtener_Descripcion_Ejericio($conn, $Id) {

    $consulta = "SELECT Descripcion FROM ejercicios WHERE ID_ejercicio = ?";

    if ($stmt = $conn->prepare($consulta)) {
        $stmt->bind_param("s", $Id); 
        $stmt->execute();
        
        $descripcion = "";
        $resultado = $stmt->get_result();

        while ($fila = $resultado->fetch_assoc()) {
            $descripcion = $fila['Descripcion'];
        }

        $stmt->close(); 
        return $descripcion; 
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
        return false;
    }

    $stmt->close(); 

}



function Actualizar_Ejercicios_Usuario($conn, $Rut, $Id, $Rutina, $Nombre, $Repeticiones, $Series) {

    $consulta = "UPDATE rutinas SET ID_Ejercicio = ?, Repeticiones = ?, Series = ? WHERE (ID_rutina = ? AND RUT_usuario = ? AND Descripcion_rutina = ?)";

    $stmt = $conn->prepare($consulta);
    if (!$stmt) {
        die("Error al preparar la consulta: " . $conn->error); // Depurar error de preparación
    }

    $conn = Conectar_Base_Datos();
    $Nombre = Obtener_Id_Ejericio($conn, $Nombre);

    $stmt->bind_param("ssssss", $Nombre, $Repeticiones, $Series, $Id, $Rut, $Rutina); 

    if ($stmt->execute()) {
        return true;
    } else {
        die("Error al ejecutar la consulta: " . $stmt->error); // Depurar error de ejecución
    }

    $stmt->close(); 

}

function Agregar_Ejercicios_Usuario($conn, $Rut, $Rutina, $Nombre, $Repeticiones, $Series) {

    // Consulta SQL sin el campo ID_rutina si es AUTO_INCREMENT
    $consulta = "INSERT INTO rutinas (RUT_usuario, Descripcion_rutina, ID_Ejercicio, Repeticiones, Series) VALUES (?, ?, ?, ?, ?)";

    // Preparar la consulta
    $stmt = $conn->prepare($consulta);
    if (!$stmt) {
        return ['success' => false, 'error' => "Error al preparar la consulta: " . $conn->error];
    }

    // Vincular los parámetros
    $stmt->bind_param("sssss", $Rut, $Rutina, $Nombre, $Repeticiones, $Series);

    // Ejecutar la consulta y manejar errores
    if ($stmt->execute()) {
        $stmt->close(); // Cerrar la declaración preparada
        return ['success' => true];
    } else {
        $stmt->close(); // Cerrar la declaración preparada incluso en caso de error
        return ['success' => false, 'error' => "Error al ejecutar la consulta: " . $stmt->error];
    }
}

function Agregar_Registro_Pulsera($conn, $Pulsera, $Ejercicio, $Repeticion, $Serie, $Fecha, $Pulso, $Pasos, $Calorias, $Temperatura) {

    // Consulta SQL sin el campo ID_rutina si es AUTO_INCREMENT
    $consulta = "INSERT INTO registro_pulsera (ID_registro_pulsera, Fecha_registro, Pulsaciones_registro, Pasos_registro, Calorias_registro, Temperatura_registro, Ejercicio, Serie) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    // Preparar la consulta
    $stmt = $conn->prepare($consulta);
    if (!$stmt) {
        return ['success' => false, 'error' => "Error al preparar la consulta: " . $conn->error];
    }

    // Vincular los parámetros
    $stmt->bind_param("ssssssss", $Pulsera, $Fecha, $Pulso, $Pasos, $Calorias, $Temperatura, $Ejercicio, $Serie);

    // Ejecutar la consulta y manejar errores
    if ($stmt->execute()) {
        $stmt->close(); // Cerrar la declaración preparada
        return ['success' => true];
    } else {
        $stmt->close(); // Cerrar la declaración preparada incluso en caso de error
        return ['success' => false, 'error' => "Error al ejecutar la consulta: " . $stmt->error];
    }
}

function Eliminar_Ejercicio_Usuario($conn, $Rut, $idEjercicio) {
    $consulta = "DELETE FROM rutinas WHERE RUT_usuario = ? AND ID_Rutina = ?";

    $stmt = $conn->prepare($consulta);
    if (!$stmt) {
        // Mostrar el error en lugar de detener el script
        return false;
    }

    $stmt->bind_param("ss", $Rut, $idEjercicio); 

    if ($stmt->execute()) {
        return true;
    } else {
        return false; // Si hay error en la ejecución, retorna false
    }

    $stmt->close();
}


function Establecer_Rutina_Usuario($conn, $Rutina, $Rut) {

    $consulta = "SELECT r.ID_rutina, e.Nombre_ejercicio, r.Repeticiones, r.Series FROM monitoreo_salud.rutinas r JOIN monitoreo_salud.usuario u ON r.RUT_usuario = u.Rut_usuario JOIN monitoreo_salud.ejercicios e ON r.ID_Ejercicio = e.ID_ejercicio WHERE u.Rut_usuario = ? AND r.Descripcion_rutina = ?";

    if ($stmt = $conn->prepare($consulta)) {
        $stmt->bind_param("ss", $Rut, $Rutina); 
        $stmt->execute();
        
        $rutina = []; // Arreglo para guardar la rutina en el formato solicitado
        $resultado = $stmt->get_result();

        while ($fila = $resultado->fetch_assoc()) {
            // Agregar un arreglo asociativo a $rutina con las claves nombre, repeticiones y series
            $rutina[] = [
                'id' => $fila['ID_rutina'],
                'nombre' => $fila['Nombre_ejercicio'],
                'repeticiones' => $fila['Repeticiones'],
                'series' => $fila['Series']
            ];
        }

        $stmt->close(); 
        return $rutina; 
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
        return false;
    }

    $stmt->close(); 

}


function Obtener_Nombre_Usuario($conn, $Correo) {

    $consulta = "SELECT Nombre_usuario FROM usuario WHERE Correo_usuario = ?";

    if ($stmt = $conn->prepare($consulta)) {
        $stmt->bind_param("s", $Correo); 
        $stmt->execute();
        
        $rut = "";
        $resultado = $stmt->get_result();

        while ($fila = $resultado->fetch_assoc()) {
            $rut = $fila['Nombre_usuario'];
        }

        $stmt->close(); 
        return $rut;
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
        return false;
    }

    $stmt->close(); 

}

function Obtener_Nombre_Entrenador($conn, $Correo) {

    $consulta = "SELECT Nombre_entrenador FROM entrenador WHERE Correo_entrenador = ?";

    if ($stmt = $conn->prepare($consulta)) {
        $stmt->bind_param("s", $Correo); 
        $stmt->execute();
        
        $rut = "";
        $resultado = $stmt->get_result();

        while ($fila = $resultado->fetch_assoc()) {
            $rut = $fila['Nombre_entrenador'];
        }

        $stmt->close(); 
        return $rut;
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
        return false;
    }

    $stmt->close(); 

}

function Obtener_Apellido_Usuario($conn, $Correo) {

    $consulta = "SELECT Apellido_usuario FROM usuario WHERE Correo_usuario = ?";

    if ($stmt = $conn->prepare($consulta)) {
        $stmt->bind_param("s", $Correo); 
        $stmt->execute();
        
        $rut = "";
        $resultado = $stmt->get_result();

        while ($fila = $resultado->fetch_assoc()) {
            $rut = $fila['Apellido_usuario'];
        }

        $stmt->close(); 
        return $rut;
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
        return false;
    }

    $stmt->close(); 

}

function Obtener_Apellido_Entrenador($conn, $Correo) {

    $consulta = "SELECT Apellido_entrenador FROM entrenador WHERE Correo_entrenador = ?";

    if ($stmt = $conn->prepare($consulta)) {
        $stmt->bind_param("s", $Correo); 
        $stmt->execute();
        
        $rut = "";
        $resultado = $stmt->get_result();

        while ($fila = $resultado->fetch_assoc()) {
            $rut = $fila['Apellido_entrenador'];
        }

        $stmt->close(); 
        return $rut;
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
        return false;
    }

    $stmt->close(); 

}


function Obtener_Correo_Entrenador($conn, $Rut) {

    $consulta = "SELECT Correo_entrenador FROM entrenador WHERE Rut_entrenador = ?";

    if ($stmt = $conn->prepare($consulta)) {
        $stmt->bind_param("i", $Rut); 
        $stmt->execute();
        
        $correo = "";
        $resultado = $stmt->get_result();

        while ($fila = $resultado->fetch_assoc()) {
            $correo = $fila['Correo_entrenador'];
        }

        $stmt->close(); 
        return $correo;
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
        return false;
    }

    $stmt->close(); 


}

/*function Obtener_Disponibilidad_Entrenador($conn, $Rut) {

    $consulta = "SELECT Disponibilidad_entrenador FROM entrenador WHERE Rut_entrenador = ?";

    if ($stmt = $conn->prepare($consulta)) {
        $stmt->bind_param("i", $Rut); 
        $stmt->execute();
        
        $disponibilidad = "";
        $resultado = $stmt->get_result();

        while ($fila = $resultado->fetch_assoc()) {
            $disponibilidad = $fila['Disponibilidad_entrenador'];
        }

        $stmt->close();

        if ($disponibilidad == "1") {
            return "Disponible";
        }
        else {
            return "No Disponible";
        }

    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
        return false;
    }

    $stmt->close(); 


}*/

function Obtener_Nombre_Apellido_Usuario($conn, $Rut_entrenador) {
    $consulta = "SELECT usuario.Nombre_usuario, usuario.Apellido_usuario
        FROM usuario, entrenador
        WHERE Rut_entrenador = ?
        GROUP BY Rut_entrenador";

    if ($stmt = $conn->prepare($consulta)) {
        $stmt->bind_param("i", $Rut_entrenador); 
        $stmt->execute();
        
        $resultado = $stmt->get_result();
        $usuarios = [];

        while ($fila = $resultado->fetch_assoc()) {
            $usuarios[] = [
                'Nombre_usuario' => $fila['Nombre_usuario'],
                'Apellido_usuario' => $fila['Apellido_usuario']
            ];
        }

        $stmt->close(); 
        return $usuarios;
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
        return false;
    }
}


function Obtener_Entrenador_Nombre($conn, $Nombre, $Apellido) {
    $consulta = "SELECT Rut_entrenador FROM entrenador WHERE Nombre_entrenador = ? AND Apellido_entrenador = ?";

    if ($stmt = $conn->prepare($consulta)) {
        $stmt->bind_param("ss", $Nombre, $Apellido); 
        $stmt->execute();
        
        $entrenador = "";
        $resultado = $stmt->get_result();

        while ($fila = $resultado->fetch_assoc()) {
            $entrenador = $fila['Rut_entrenador'];
        }

        $stmt->close(); 
        return $entrenador;
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
        return false;
    }

}

function Obtener_Suscripciones($conn) {

    $consulta = "SELECT Tipo_suscripcion FROM suscripciones";

    if ($stmt = $conn->prepare($consulta)) {
        
        $stmt->execute();
        $suscripciones = [];
        $resultado = $stmt->get_result();

        while ($fila = $resultado->fetch_assoc()) {
            $suscripciones[] = ucfirst($fila['Tipo_suscripcion']);
        }

        return $suscripciones;
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
    }

    $stmt->close();

}

function Obtener_Suscripciones_Filtrada($conn, $suscripcion) {

    $consulta = "SELECT Tipo_suscripcion FROM suscripciones WHERE ID_suscripcion != ?";

    if ($stmt = $conn->prepare($consulta)) {
        
        $stmt->bind_param("s", $suscripcion);
        $stmt->execute();
        

        $resultado = $stmt->get_result();
        $suscripciones = [];

        while ($fila = $resultado->fetch_assoc()) {
            $suscripciones[] = ucfirst($fila['Tipo_suscripcion']);
        }

        $stmt->close();
        
        return $suscripciones;
    } else {

        echo "Error en la preparación de la consulta: " . $conn->error;
        return false;
    }

}

function Obtener_Suscripcion($conn, $suscripcion) {

    $consulta = "SELECT Tipo_suscripcion FROM suscripciones WHERE ID_suscripcion = ". $suscripcion .";";

    if ($stmt = $conn->prepare($consulta)) {
        
        $stmt->execute();
        $suscripcion = "";
        $resultado = $stmt->get_result();

        while ($fila = $resultado->fetch_assoc()) {
            $suscripcion = ucfirst($fila['Tipo_suscripcion']);
        }

        $stmt->close();
        return $suscripcion;
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
    }

}

function Obtener_Suscripcion_Nombre($conn, $Nombre) {
    $consulta = "SELECT ID_suscripcion FROM suscripciones WHERE Tipo_suscripcion = ?";

    if ($stmt = $conn->prepare($consulta)) {
        $Nombre = lcfirst($Nombre);
        $stmt->bind_param("s", $Nombre);
        $stmt->execute();
        
        $suscripcion = 0;
        $resultado = $stmt->get_result();

        while ($fila = $resultado->fetch_assoc()) {
            $suscripcion = (int)$fila['ID_suscripcion'];
        }

        $stmt->close();
        return $suscripcion;
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
        return false;
    }
}


// Eliminar

function Eliminar_Pulsera($conn, $id) {

    $stmt = $conn->prepare("DELETE FROM pulsera WHERE ID_pulsera = ?");

    $stmt->bind_param("s", $id);

    if ($stmt->execute()) { return true; } 
    else { return false; }

    $stmt->close();

}

function Eliminar_Registro_Pulsera($conn, $id) {

    $stmt = $conn->prepare("DELETE FROM registro_pulsera WHERE ID_registro_pulsera = ?");
    $stmt->bind_param("s", $id);

    if ($stmt->execute()) { return true; } 
    else { return false; }

    $stmt->close();

}

function Eliminar_Registro_Usuario($conn, $correo) {

    $stmt = $conn->prepare("DELETE FROM registro_usuarios WHERE Correo_usuario = ?");
    $stmt->bind_param("s", $correo);

    if ($stmt->execute()) { return true; } 
    else { return false; }

    $stmt->close();

}

function Eliminar_Registro_Entrenador($conn, $correo) {

    $stmt = $conn->prepare("DELETE FROM registro_entrenadores WHERE Correo_entrenador = ?");
    $stmt->bind_param("s", $correo);

    if ($stmt->execute()) { return true; } 
    else { return false; }

    $stmt->close();

}

function Eliminar_Registro_Suscripcion($conn, $rut) {

    $stmt = $conn->prepare("DELETE FROM registro_suscripcion WHERE Rut_usuario_suscripcion = ?");
    $stmt->bind_param("s", $rut);

    if ($stmt->execute()) { return true; } 
    else { return false; }

    $stmt->close();

}
