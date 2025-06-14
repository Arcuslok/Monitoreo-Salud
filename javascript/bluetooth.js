const serviceUUID = 0xFFE0;
const serialUUID = 0xFFE1;

let device;
let serialCharacteristic;

let caloria_paso = 0.04;
let pulso;
let pasos;
let temperatura;


async function connect() {
    try {
        device = await navigator.bluetooth.requestDevice({
            filters: [{ 
                services: [serviceUUID]
            }],
        });

        const server = await device.gatt.connect();

        const service = await server.getPrimaryService(serviceUUID);
        serialCharacteristic = await service.getCharacteristic(serialUUID);

        await serialCharacteristic.startNotifications();
        serialCharacteristic.addEventListener('characteristicvaluechanged', read);
        
        // Mostrar "Tus Rutinas"
        document.querySelector('.walking-icon h1').style.display = 'block';

        // Mostrar botón "Iniciar Rutina"
        document.querySelector('.walking-icon .btn-primary').style.display = 'block';

        // Hacer visible el select
        document.getElementById('options').style.display = 'block';

        // Si la conexión es exitosa, cambia la imagen del smartwatch
        document.getElementById('smartwatch-img').src = 'img/SmartWatchActivated.png';

        // Hacer visible los valores de pulso, pasos, calorías y temperatura
        document.querySelectorAll('.card').forEach(card => {
            card.style.display = 'block';  // Muestra todas las cartas
        });

        await ejecutarPHP("pulsera/activar_estado_pulsera.php");

        pulso = false;
        pasos = false;
        temperatura = false;

        // Cambiar el evento del botón para desconectar
        document.getElementById('connect').removeEventListener("click", connect);
        document.getElementById('connect').addEventListener("click", disconnect);

        // Comenzar la verificación periódica de la conexión
        startConnectionCheck();

    } catch (error) {
        console.log('Error en la conexión Bluetooth:', error);
        // Volver a la imagen desactivada si hay un error
        document.getElementById('smartwatch-img').src = 'img/SmartWatchDisabled.png';
    }
}

// Verificación periódica de la conexión
function startConnectionCheck() {
    setInterval(() => {
        if (device && !device.gatt.connected) {
            disconnect(); // Llamar a la función de manejo de desconexión
        }
    }, 200); // Comprobar cada 3 segundos
}

async function disconnect() {
    try {
        if (device && device.gatt.connected) {
            device.gatt.disconnect();
        }

        await ejecutarPHP("pulsera/desactivar_estado_pulsera.php");
        await ejecutarPHP("pulsera/desactivar_estado_pulso.php");
        await ejecutarPHP("pulsera/desactivar_estado_pasos.php");
        await ejecutarPHP("pulsera/desactivar_estado_temperatura.php");

        pulso = false;
        pasos = false;
        temperatura = false;

        document.getElementById("pulso").textContent = 0;
        document.getElementById("pasos").textContent = 0;
        document.getElementById("calorias").textContent = 0;
        document.getElementById("temperatura").textContent = 0;

        // Hacer invisible el select al desconectar
        document.getElementById('options').style.display = 'none';
        document.getElementById('rutina_actual').style.display = 'none';
        document.getElementById('cronometro').style.display = 'none';

        document.getElementById('smartwatch-img').src = 'img/SmartWatchDisabled.png';

        document.getElementById('calorias-img').src = 'img/calories0.jpg';

        // Hacer invisible los valores de pulso, pasos, calorías y temperatura al desconectar
        document.querySelectorAll('.card').forEach(card => {
            card.style.display = 'none';  // Muestra todas las cartas
        });

        document.getElementById('Titulo_rutinas').style.display = 'none';
        document.getElementById('IniciarRutinaBtn').style.display = 'none';

        // Resetear valores a 0
        document.getElementById("pulso").textContent = "0";
        document.getElementById("pasos").textContent = "0";
        document.getElementById("calorias").textContent = "0";
        document.getElementById("temperatura").textContent = "0";

        stopCronometro();
        // Detener el descanso si está en curso
        if (descansoEnCurso) {
            detenerDescanso(() => {}); // Llamamos a la función de detener sin reiniciar el cronómetro
        }

        document.getElementById("cronometro").innerText = `Tiempo: 0 segundos`;

        document.getElementById('cronometro').style.display = 'none';
        document.getElementById('completarEjercicio').style.display = 'none';
        document.getElementById('terminarRutina').style.display = 'none';

        document.getElementById('rutina_actual').style.display = 'none';
        document.getElementById('Ejercicio_rutina').style.display = 'none';
        document.getElementById('Repeticiones_rutina').style.display = 'none';
        document.getElementById('Series_rutina').style.display = 'none';
        
        document.getElementById('connect').removeEventListener("click", disconnect);
        document.getElementById('connect').addEventListener("click", connect);

    } catch (error) {
        console.log('Error al desconectar:', error);
    }
}

async function read(event) {
    let buffer = event.target.value.buffer;
    let view = new Uint8Array(buffer);
    let decodedMessage = String.fromCharCode.apply(null, view).split(',');

    let pulsoActual = parseInt(decodedMessage[0]);
    let pasosActuales = parseInt(decodedMessage[1]);
    let temperaturaActual = parseFloat(decodedMessage[2]);
    let caloriasQuemadas = pasosActuales * caloria_paso;

    if (!isNaN(pulsoActual) && pulso == false) { 
        pulso = true;
        await ejecutarPHP("pulsera/activar_estado_pulso.php");
    }

    if (!isNaN(pasosActuales) && pasos == false) {
        pasos = true;
        await ejecutarPHP("pulsera/activar_estado_pasos.php");
    }

    if (!isNaN(temperaturaActual) && temperatura == false) {
        temperatura = true;
        await ejecutarPHP("pulsera/activar_estado_temperatura.php");
    }

    // Actualizar el contenido en la interfaz
    document.getElementById("pasos").textContent = pasosActuales;
    document.getElementById("calorias").textContent = caloriasQuemadas;
    document.getElementById("temperatura").textContent = temperaturaActual;

    // Cambiar la imagen dependiendo de las calorías quemadas
    if (caloriasQuemadas >= 100 && caloriasQuemadas < 200) {
        document.getElementById('calorias-img').src = 'img/calories1.jpg';
    } else if (caloriasQuemadas >= 200 && caloriasQuemadas < 300) {
        document.getElementById('calorias-img').src = 'img/calories2.jpg';
    } else if (caloriasQuemadas >= 300) {
        document.getElementById('calorias-img').src = 'img/calories3.jpg';
    }
}

async function ejecutarPHP(archivo) {
    try {
        const response = await fetch(archivo, {
            method: 'GET' // Omitir method ya que GET es el predeterminado
        });

        const data = await response.text(); // O usa .json() si esperas JSON
        console.log(data);
    } catch (error) {
        console.log('Error al ejecutar el PHP:', error);
    }
}

// Agregar evento al botón de conexión al cargar la página
document.getElementById('connect').addEventListener("click", connect);
