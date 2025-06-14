<?php
require('fpdf/fpdf.php');
require_once '../funciones/Funciones SQL.php';

session_start(); // Iniciar la sesión

class PDF extends FPDF {
    private $titulo;

    function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    function Header() {
        $this->Image('../img/SmartWatchActivated.png', 10, 6, 20);
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(0, 10, $this->titulo, 0, 1, 'C');
        $this->Ln(10); // Reduced the space after the title
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->PageNo(), 0, 0, 'C');
    }

    function BasicTable($header, $data, $x) {
        // Calculate the widths of the columns based on the data
        $widths = [];
        foreach ($header as $col) {
            $widths[] = $this->GetStringWidth($col) + 6; // Add padding
        }

        foreach ($data as $row) {
            foreach ($row as $key => $col) {
                $widths[$key] = max($widths[$key], $this->GetStringWidth($col) + 6); // Add padding
            }
        }

        // Print the header
        $this->SetX($x); // Move the start position to the left
        foreach ($header as $key => $col) {
            $this->Cell($widths[$key], 7, $col, 1);
        }
        $this->Ln();

        // Print the data rows
        foreach ($data as $row) {
            $this->SetX($x); // Move the start position to the left
            foreach ($row as $key => $col) {
                $this->Cell($widths[$key], 6, $col, 1);
            }
            $this->Ln();

            // Check if a new page is needed
            if ($this->GetY() > $this->PageBreakTrigger - 10) {
                $this->AddPage();
                $this->SetX($x); // Move the start position to the left
                foreach ($header as $key => $col) {
                    $this->Cell($widths[$key], 7, $col, 1);
                }
                $this->Ln();
            }
        }
    }
    public function Usuarios() {

        include "../plantillas/Plantilla Usuario.php";

        $pdf = new PDF();
        $pdf->setTitulo("Lista de Usuario");
        $pdf->AddPage();

        $header = array('Rut', 'Nombre', 'Apellido', 'Direccion', 'Correo', 'Fono', 'Entrenador', 'Suscripcion', 'Pulsera');

        $conn = Conectar_Base_Datos();
        $usuarios = Usuario::listarUsuario($conn);
        $data = [];

        if ($usuarios) {
            foreach ($usuarios as $usuario) {
                $data[] = array(
                    $usuario['Rut_usuario'],
                    $usuario['Nombre_usuario'],
                    $usuario['Apellido_usuario'],
                    $usuario['Direccion_usuario'],
                    $usuario['Correo_usuario'],
                    $usuario['Fono_usuario'],
                    Obtener_Entrenador($conn, $usuario["Entrenador_usuario"]),
                    Obtener_Suscripcion($conn, $usuario["Suscripcion_usuario"]),
                    $usuario["Pulsera_usuario"]
                );
            }
        } else {
            $data[] = array('-', '-', '-', '-', '-', '-', '-', '-', '-');
        }

        $pdf->SetFont('Arial', '', 7.5); // Reduced the font size
        $pdf->BasicTable($header, $data, 5);
        $pdf->Output('lista_usuarios.pdf', 'I');
    }

    public function Entrenadores() {

        include "../plantillas/Plantilla Entrenador.php";

        $pdf = new PDF();
        $pdf->setTitulo("Lista de Entrenadores");
        $pdf->AddPage();

        $header = array('Rut', 'Nombre', 'Apellido', 'Correo', 'Fono', 'Disponibilidad');

        $conn = Conectar_Base_Datos();
        $entrenadores = Entrenador::listarEntrenador($conn);
        $data = [];

        if ($entrenadores) {
            foreach ($entrenadores as $entrenador) {
                if ($entrenador['Disponibilidad_entrenador'] == "1") {
                    $entrenador['Disponibilidad_entrenador'] = "Disponible";
                }
                else {
                    $entrenador['Disponibilidad_entrenador'] = "No Disponible";
                }
                $data[] = array(
                    $entrenador['Rut_entrenador'],
                    $entrenador['Nombre_entrenador'],
                    $entrenador['Apellido_entrenador'],
                    $entrenador['Correo_entrenador'],
                    $entrenador['Fono_entrenador'],
                    $entrenador['Disponibilidad_entrenador']
                );
            }
        } else {
            $data[] = array('-', '-', '-', '-', '-', '-');
        }

        $pdf->SetFont('Arial', '', 12); // Reduced the font size
        $pdf->BasicTable($header, $data, 15);
        $pdf->Output('lista_entrenadores.pdf', 'I');
    }
    
}

?>
