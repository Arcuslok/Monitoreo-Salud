<?php

function Validar_Input($data) {
    return htmlspecialchars(trim($data));
}

function validarRUT($rut) {

    $rut = str_replace(['.', '-'], '', $rut);

    if (strlen($rut) < 8) return false;

    $dv = strtoupper(substr($rut, -1));
    $rut = substr($rut, 0, -1);
    $suma = 0;
    $multiplo = 2;

    for ($i = strlen($rut) - 1; $i >= 0; $i--) {
        $suma += $multiplo * $rut[$i];
        $multiplo = $multiplo < 7 ? $multiplo + 1 : 2;
    }

    $dvEsperado = 11 - ($suma % 11);

    if ($dvEsperado == 11) $dvEsperado = '0';
    if ($dvEsperado == 10) $dvEsperado = 'K';

    return $dvEsperado == $dv;

}

function formatearRUT($rut) {
    // Eliminar puntos y guion si existen
    $rut = preg_replace('/[^k0-9]/i', '', $rut);
    
    // Obtener el dígito verificador
    $dv = substr($rut, -1);
    
    // Obtener el número sin el dígito verificador
    $numero = substr($rut, 0, -1);
    
    // Agregar puntos
    $numeroConPuntos = number_format($numero, 0, '', '.');
    
    // Concatenar con el dígito verificador usando un guion
    $rutFormateado = $numeroConPuntos . '-' . strtoupper($dv);
    
    return $rutFormateado;
}

function identificar_genero($nombre) {
    // Convertir el nombre a minúsculas para facilitar las comparaciones
    $nombre = strtolower($nombre);

    // Reglas heurísticas mejoradas
    // Reglas basadas en la terminación del nombre
    if (preg_match('/(a|ia|ina|elle|ine|is|e)$/', $nombre)) {
        return "Femenino";
    } elseif (preg_match('/(o|el|er|on|an|ar|s|us)$/', $nombre)) {
        return "Masculino";
    }
    
    // Reglas basadas en vocales y combinaciones internas
    if (preg_match('/(ia|ana|ella)/', $nombre)) {
        return "Femenino";
    } elseif (preg_match('/(ro|el|ar|us)/', $nombre)) {
        return "Masculino";
    }
    
    // Regla basada en la longitud del nombre
    if (strlen($nombre) > 5 && preg_match('/[aeiou]$/', $nombre)) {
        return "Femenino";
    } elseif (strlen($nombre) <= 5 && preg_match('/[^aeiou]$/', $nombre)) {
        return "Masculino";
    }

    // Si no se puede determinar, devolver "Desconocido"
    return "Desconocido";
}

?>
<script>

    function ValidaSoloNumeros(event) {
        var charCode = (event.which) ? event.which : event.keyCode;
        if (charCode < 48 || charCode > 57) {
            event.returnValue = false;
        }
    }

    function ValidarSoloLetras(event) {
        var charCode = (event.which) ? event.which : event.keyCode;
        if ((event.keyCode != 32) && (event.keyCode < 65) || (event.keyCode > 90) && (event.keyCode < 97) || (event.keyCode > 122)) {
            event.returnValue = false;
        }
    }

    function formatearRUT(rut) {
        return rut.replace(/^(\d{1,2})(\d{3})(\d{3})([\dkK])$/, '$1.$2.$3-$4');
    }

    function limpiarRUT(rut) {
        return rut.replace(/\./g, '').replace('-', '');
    }

    function ValidarRut(input) {

        const rutInput = document.getElementById('rut');
        const rutHidden = document.getElementById('rutHidden');
        let rut = input.value.trim();

        if (/[^0-9kK]/i.test(rut)) {
            document.getElementById('rutFeedback').textContent = 'Solo se permiten números y la letra K';
            document.getElementById('rutFeedback').style.color = 'red';
            return;
        }

        if (rut.length < 2) {
            document.getElementById('rutFeedback').textContent = '';
            return;
        }

        let numero = rut.slice(0, -1);
        let dv = rut.slice(-1).toUpperCase();

        let suma = 0;
        let multiplo = 2;

        for (let i = numero.length - 1; i >= 0; i--) {
            suma += parseInt(numero.charAt(i)) * multiplo;
            if (multiplo < 7) multiplo++;
            else multiplo = 2;
        }

        let dvEsperado = 11 - (suma % 11);
        dvEsperado = (dvEsperado === 11) ? 0 : ((dvEsperado === 10) ? 'K' : dvEsperado);

        let valido = (dv == dvEsperado);

        if (valido) {
            document.getElementById('rutFeedback').textContent = '';
            rutHidden.value = limpiarRUT(rutInput.value);
            rutInput.value = formatearRUT(rutHidden.value);
        } else {
            document.getElementById('rutFeedback').textContent = 'Rut Inválido';
            document.getElementById('rutFeedback').style.color = 'red';
        }
    }

</script>