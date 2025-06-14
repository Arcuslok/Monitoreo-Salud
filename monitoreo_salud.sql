-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-11-2024 a las 15:14:00
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `monitoreo_salud`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ejercicios`
--

CREATE TABLE `ejercicios` (
  `ID_ejercicio` int(11) NOT NULL,
  `Nombre_ejercicio` varchar(100) DEFAULT NULL,
  `Descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ejercicios`
--

INSERT INTO `ejercicios` (`ID_ejercicio`, `Nombre_ejercicio`, `Descripcion`) VALUES
(1, 'Flexiones', 'Las flexiones son un ejercicio de peso corporal que trabaja el pecho, los hombros y los tríceps. Para hacerlas correctamente, sigue estos pasos:\n\n1. Posición inicial: Acuéstate boca abajo con las manos a la altura de los hombros y los brazos extendidos. Mantén el cuerpo recto desde la cabeza hasta los talones, contrayendo el abdomen y los glúteos.\n2. Descenso: Dobla los codos y baja el cuerpo hacia el suelo, manteniendo los codos cerca del cuerpo en un ángulo de 45 grados. Baja hasta que el pecho esté cerca del suelo, sin tocarlo.\n3. Ascenso: Empuja con las manos para volver a la posición inicial, extendiendo los codos. Mantén el cuerpo alineado.\n4. Respiración: Inhala al bajar y exhala al subir.\n\nLas flexiones se pueden ajustar según tu nivel de dificultad.'),
(2, 'Sentadillas', 'Las sentadillas son un ejercicio de peso corporal que fortalece principalmente los músculos de las piernas y glúteos. Para realizarlas correctamente, sigue estos pasos:\n1. Posición inicial: Colócate de pie con los pies separados a la altura de los hombros y las puntas ligeramente hacia afuera. Mantén el pecho erguido y los brazos extendidos hacia adelante para equilibrarte.\n2. Descenso: Flexiona las rodillas y las caderas para bajar el cuerpo, como si te fueras a sentar en una silla. Mantén el peso en los talones y evita que las rodillas sobrepasen la punta de los pies. Baja hasta que los muslos estén paralelos al suelo o más abajo si tu flexibilidad lo permite.\n3. Ascenso: Empuja con los talones para volver a la posición inicial, extendiendo las caderas y las rodillas. Mantén la espalda recta durante todo el movimiento.\n4. Respiración: Inhala al bajar y exhala al subir.'),
(3, 'Abdominales', 'Los abdominales son un ejercicio de peso corporal que fortalece los músculos del abdomen. Para realizarlos correctamente, sigue estos pasos:\n1. Posición inicial: Acuéstate boca arriba en el suelo, con las rodillas flexionadas y los pies apoyados en el suelo. Coloca las manos detrás de la cabeza o cruzadas sobre el pecho.\n2. Elevación: Levanta el torso hacia las rodillas, contrayendo los músculos abdominales. Evita tirar del cuello con las manos, manteniendo la cabeza en una posición neutral.\n3. Descenso: Baja el torso de forma controlada hasta la posición inicial, evitando dejar caer el cuerpo rápidamente.\n4. Respiración: Exhala al subir e inhala al bajar.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entrenador`
--

CREATE TABLE `entrenador` (
  `Rut_entrenador` varchar(13) NOT NULL,
  `Nombre_entrenador` varchar(25) DEFAULT NULL,
  `Apellido_entrenador` varchar(25) DEFAULT NULL,
  `Correo_entrenador` varchar(50) DEFAULT NULL,
  `Fono_entrenador` int(11) DEFAULT NULL,
  `Disponibilidad_entrenador` tinyint(1) DEFAULT NULL,
  `Sesion_activa` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `entrenador`
--

INSERT INTO `entrenador` (`Rut_entrenador`, `Nombre_entrenador`, `Apellido_entrenador`, `Correo_entrenador`, `Fono_entrenador`, `Disponibilidad_entrenador`, `Sesion_activa`) VALUES
('111127743', 'Carl', 'Johnson', 'negropandillero@gmail.com', 123789, 1, 0),
('188445195', 'Pedro', 'Caceres', 'deiwex@gmail.com', 52869456, 1, 0),
('211681055', 'Eduardo', 'Cuevas', 'jakle153@gmail.com', 2147483647, 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pulsera`
--

CREATE TABLE `pulsera` (
  `ID_pulsera` int(11) NOT NULL,
  `Estado_conexion_pulsera` tinyint(1) DEFAULT NULL,
  `Estado_pulso_pulsera` tinyint(1) DEFAULT NULL,
  `Estado_pasos_pulsera` tinyint(1) DEFAULT NULL,
  `Estado_temperatura_pulsera` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pulsera`
--

INSERT INTO `pulsera` (`ID_pulsera`, `Estado_conexion_pulsera`, `Estado_pulso_pulsera`, `Estado_pasos_pulsera`, `Estado_temperatura_pulsera`) VALUES
(4, 0, 0, 0, 0),
(1045, 0, 0, 0, 0),
(12345, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_entrenadores`
--

CREATE TABLE `registro_entrenadores` (
  `Correo_entrenador` varchar(50) DEFAULT NULL,
  `Clave_entrenador` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registro_entrenadores`
--

INSERT INTO `registro_entrenadores` (`Correo_entrenador`, `Clave_entrenador`) VALUES
('deiwex@gmail.com', '1234'),
('jakle153@gmail.com', '123456'),
('negropandillero@gmail.com', '1004');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_pulsera`
--

CREATE TABLE `registro_pulsera` (
  `ID_registro_pulsera` int(11) DEFAULT NULL,
  `Fecha_registro` datetime DEFAULT NULL,
  `Pulsaciones_registro` int(11) DEFAULT NULL,
  `Pasos_registro` int(11) DEFAULT NULL,
  `Calorias_registro` int(11) DEFAULT NULL,
  `Temperatura_registro` float DEFAULT NULL,
  `Ejercicio` int(11) DEFAULT NULL,
  `Serie` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registro_pulsera`
--

INSERT INTO `registro_pulsera` (`ID_registro_pulsera`, `Fecha_registro`, `Pulsaciones_registro`, `Pasos_registro`, `Calorias_registro`, `Temperatura_registro`, `Ejercicio`, `Serie`) VALUES
(1045, '2024-10-16 00:44:53', 169, 0, 0, 22.8, 1, 6),
(1045, '2024-10-16 00:44:53', 163, 0, 0, 22.1, 1, 6),
(1045, '2024-10-16 00:44:54', 155, 0, 0, 22.2, 1, 6),
(1045, '2024-10-16 00:44:54', 158, 0, 0, 22.7, 1, 6),
(1045, '2024-10-16 00:44:55', 158, 0, 0, 22, 1, 6),
(1045, '2024-10-16 00:44:55', 158, 0, 0, 23, 1, 6),
(1045, '2024-10-16 00:44:56', 158, 0, 0, 23.9, 1, 6),
(1045, '2024-10-16 00:44:56', 158, 0, 0, 22.4, 1, 6),
(1045, '2024-10-16 00:44:57', 0, 0, 0, 24.5, 1, 6),
(1045, '2024-10-16 00:44:57', 0, 0, 0, 22, 1, 6),
(1045, '2024-10-16 00:44:58', 0, 0, 0, 22.7, 1, 6),
(1045, '2024-10-16 00:44:58', 0, 0, 0, 22.3, 1, 6),
(1045, '2024-10-16 00:44:59', 0, 0, 0, 24.7, 1, 6),
(1045, '2024-10-16 00:44:59', 0, 0, 0, 23.5, 1, 6),
(1045, '2024-10-16 00:45:00', 0, 0, 0, 22.4, 1, 6),
(1045, '2024-10-16 00:45:00', 0, 0, 0, 23.6, 1, 6),
(1045, '2024-10-16 00:45:01', 0, 0, 0, 23.6, 1, 6),
(1045, '2024-10-16 00:45:01', 0, 0, 0, 23.2, 1, 6),
(1045, '2024-10-16 00:45:02', 0, 0, 0, 23.2, 1, 6),
(1045, '2024-10-16 00:45:02', 0, 0, 0, 24.2, 1, 6),
(1045, '2024-10-16 00:45:03', 0, 0, 0, 22.8, 1, 6),
(1045, '2024-10-16 00:45:15', 0, 0, 0, 22.1, 1, 5),
(1045, '2024-10-16 00:45:16', 0, 0, 0, 24.4, 1, 5),
(1045, '2024-10-16 00:45:16', 0, 0, 0, 24.6, 1, 5),
(1045, '2024-10-16 00:45:17', 0, 0, 0, 24.6, 1, 5),
(1045, '2024-10-16 00:45:17', 0, 0, 0, 22.1, 1, 5),
(1045, '2024-10-16 00:45:18', 0, 0, 0, 23, 1, 5),
(1045, '2024-10-16 00:45:18', 0, 0, 0, 23.6, 1, 5),
(1045, '2024-10-16 00:45:19', 0, 0, 0, 24, 1, 5),
(1045, '2024-10-16 00:45:19', 0, 0, 0, 22.1, 1, 5),
(1045, '2024-10-16 00:45:20', 0, 0, 0, 24.6, 1, 5),
(1045, '2024-10-16 00:45:20', 0, 0, 0, 24.5, 1, 5),
(1045, '2024-10-16 00:45:21', 0, 0, 0, 24.7, 1, 5),
(1045, '2024-10-16 00:45:21', 0, 0, 0, 24.2, 1, 5),
(1045, '2024-10-16 00:45:22', 0, 0, 0, 25, 1, 5),
(1045, '2024-10-16 00:45:22', 0, 0, 0, 23.4, 1, 5),
(1045, '2024-10-16 00:45:23', 0, 0, 0, 23.7, 1, 5),
(1045, '2024-10-16 00:45:29', 0, 0, 0, 23.6, 1, 4),
(1045, '2024-10-16 00:45:30', 0, 0, 0, 24.7, 1, 4),
(1045, '2024-10-16 00:45:30', 0, 0, 0, 24.6, 1, 4),
(1045, '2024-10-16 00:45:31', 0, 0, 0, 23.1, 1, 4),
(1045, '2024-10-16 00:45:31', 0, 0, 0, 23.8, 1, 4),
(1045, '2024-10-16 00:45:32', 0, 0, 0, 22.5, 1, 4),
(1045, '2024-10-16 00:45:32', 0, 0, 0, 22, 1, 4),
(1045, '2024-10-16 00:45:33', 0, 0, 0, 24.7, 1, 4),
(1045, '2024-10-16 00:45:33', 0, 0, 0, 22.3, 1, 4),
(1045, '2024-10-16 00:45:34', 0, 0, 0, 22.6, 1, 4),
(1045, '2024-10-16 00:45:34', 0, 0, 0, 23.2, 1, 4),
(1045, '2024-10-16 00:45:35', 0, 0, 0, 22.9, 1, 4),
(1045, '2024-10-16 00:45:35', 0, 0, 0, 24.2, 1, 4),
(1045, '2024-10-16 00:45:36', 0, 0, 0, 24.3, 1, 4),
(1045, '2024-10-16 00:45:43', 0, 0, 0, 23, 1, 3),
(1045, '2024-10-16 00:45:44', 0, 0, 0, 23.8, 1, 3),
(1045, '2024-10-16 00:45:44', 0, 0, 0, 23.3, 1, 3),
(1045, '2024-10-16 00:45:45', 0, 0, 0, 24.9, 1, 3),
(1045, '2024-10-16 00:45:45', 0, 0, 0, 24.5, 1, 3),
(1045, '2024-10-16 00:45:46', 0, 0, 0, 24.8, 1, 3),
(1045, '2024-10-16 00:45:46', 0, 0, 0, 22.5, 1, 3),
(1045, '2024-10-16 00:45:47', 0, 0, 0, 26, 1, 3),
(1045, '2024-10-16 00:45:47', 0, 0, 0, 22.5, 1, 3),
(1045, '2024-10-16 00:45:48', 0, 0, 0, 23.4, 1, 3),
(1045, '2024-10-16 00:45:48', 0, 0, 0, 24.7, 1, 3),
(1045, '2024-10-16 00:45:49', 0, 0, 0, 24.3, 1, 3),
(1045, '2024-10-16 00:45:49', 0, 0, 0, 22.3, 1, 3),
(1045, '2024-10-16 00:45:50', 0, 0, 0, 23.5, 1, 3),
(1045, '2024-10-16 00:46:00', 0, 0, 0, 22.7, 1, 2),
(1045, '2024-10-16 00:46:00', 0, 0, 0, 22.9, 1, 2),
(1045, '2024-10-16 00:46:01', 0, 0, 0, 23.6, 1, 2),
(1045, '2024-10-16 00:46:01', 0, 0, 0, 23.2, 1, 2),
(1045, '2024-10-16 00:46:02', 0, 0, 0, 24.2, 1, 2),
(1045, '2024-10-16 00:46:02', 0, 0, 0, 24.4, 1, 2),
(1045, '2024-10-16 00:46:03', 0, 0, 0, 23.5, 1, 2),
(1045, '2024-10-16 00:46:03', 0, 0, 0, 22.3, 1, 2),
(1045, '2024-10-16 00:46:04', 0, 0, 0, 23.9, 1, 2),
(1045, '2024-10-16 00:46:04', 0, 0, 0, 24.4, 1, 2),
(1045, '2024-10-16 00:46:05', 0, 0, 0, 24.6, 1, 2),
(1045, '2024-10-16 00:46:05', 0, 0, 0, 24.8, 1, 2),
(1045, '2024-10-16 00:46:06', 0, 0, 0, 24, 1, 2),
(1045, '2024-10-16 00:46:11', 0, 0, 0, 22.5, 1, 1),
(1045, '2024-10-16 00:46:12', 0, 0, 0, 24.7, 1, 1),
(1045, '2024-10-16 00:46:12', 0, 0, 0, 23, 1, 1),
(1045, '2024-10-16 00:46:13', 0, 0, 0, 22.8, 1, 1),
(1045, '2024-10-16 00:46:13', 0, 0, 0, 23.9, 1, 1),
(1045, '2024-10-16 00:46:14', 0, 0, 0, 22.2, 1, 1),
(1045, '2024-10-16 00:46:14', 0, 0, 0, 22.2, 1, 1),
(1045, '2024-10-16 00:46:15', 0, 0, 0, 22.5, 1, 1),
(1045, '2024-10-16 00:46:15', 0, 0, 0, 24.7, 1, 1),
(1045, '2024-10-16 00:46:23', 0, 0, 0, 23.2, 2, 3),
(1045, '2024-10-16 00:46:24', 0, 0, 0, 22.7, 2, 3),
(1045, '2024-10-16 00:46:24', 0, 0, 0, 22.8, 2, 3),
(1045, '2024-10-16 00:46:25', 0, 0, 0, 24, 2, 3),
(1045, '2024-10-16 00:46:25', 0, 0, 0, 24.8, 2, 3),
(1045, '2024-10-16 00:46:26', 0, 0, 0, 24.1, 2, 3),
(1045, '2024-10-16 00:46:26', 0, 0, 0, 24.8, 2, 3),
(1045, '2024-10-16 00:46:27', 0, 0, 0, 24.6, 2, 3),
(1045, '2024-10-16 00:46:27', 0, 0, 0, 22.8, 2, 3),
(1045, '2024-10-16 00:46:28', 0, 0, 0, 24, 2, 3),
(1045, '2024-10-16 00:46:28', 0, 0, 0, 24.9, 2, 3),
(1045, '2024-10-16 00:46:29', 0, 0, 0, 24.2, 2, 3),
(1045, '2024-10-16 00:46:29', 0, 0, 0, 24.3, 2, 3),
(1045, '2024-10-16 00:46:30', 0, 0, 0, 22.1, 2, 3),
(1045, '2024-10-16 00:46:30', 0, 0, 0, 23.4, 2, 3),
(1045, '2024-10-16 00:46:31', 0, 0, 0, 23.6, 2, 3),
(1045, '2024-10-16 00:46:31', 0, 0, 0, 23.9, 2, 3),
(1045, '2024-10-16 00:46:39', 0, 0, 0, 24.6, 2, 2),
(1045, '2024-10-16 00:46:40', 0, 0, 0, 24.3, 2, 2),
(1045, '2024-10-16 00:46:40', 0, 0, 0, 24.6, 2, 2),
(1045, '2024-10-16 00:46:41', 0, 0, 0, 24.1, 2, 2),
(1045, '2024-10-16 00:46:41', 0, 0, 0, 25.6, 2, 2),
(1045, '2024-10-16 00:46:42', 0, 0, 0, 24.7, 2, 2),
(1045, '2024-10-16 00:46:42', 0, 0, 0, 23.9, 2, 2),
(1045, '2024-10-16 00:46:43', 0, 0, 0, 23.1, 2, 2),
(1045, '2024-10-16 00:46:43', 0, 0, 0, 22.1, 2, 2),
(1045, '2024-10-16 00:46:44', 0, 0, 0, 24.9, 2, 2),
(1045, '2024-10-16 00:46:44', 0, 0, 0, 24.5, 2, 2),
(1045, '2024-10-16 00:46:45', 0, 0, 0, 24.5, 2, 2),
(1045, '2024-10-16 00:46:45', 0, 0, 0, 23.1, 2, 2),
(1045, '2024-10-16 00:46:46', 0, 0, 0, 22.8, 2, 2),
(1045, '2024-10-16 00:46:46', 0, 0, 0, 23.9, 2, 2),
(1045, '2024-10-16 00:46:54', 0, 0, 0, 22.1, 2, 1),
(1045, '2024-10-16 00:46:54', 0, 0, 0, 22.9, 2, 1),
(1045, '2024-10-16 00:46:55', 0, 0, 0, 22.1, 2, 1),
(1045, '2024-10-16 00:46:55', 0, 0, 0, 23.9, 2, 1),
(1045, '2024-10-16 00:46:56', 0, 0, 0, 23, 2, 1),
(1045, '2024-10-16 00:46:56', 0, 0, 0, 23.2, 2, 1),
(1045, '2024-10-16 00:46:57', 0, 0, 0, 24.3, 2, 1),
(1045, '2024-10-16 00:46:57', 0, 0, 0, 24.7, 2, 1),
(1045, '2024-10-16 00:46:58', 0, 0, 0, 22.7, 2, 1),
(1045, '2024-10-16 00:46:58', 0, 0, 0, 24.5, 2, 1),
(1045, '2024-10-16 00:46:59', 0, 0, 0, 24.3, 2, 1),
(1045, '2024-10-16 00:46:59', 0, 0, 0, 24.1, 2, 1),
(1045, '2024-10-16 00:47:00', 0, 0, 0, 22.7, 2, 1),
(1045, '2024-10-16 00:47:00', 0, 0, 0, 22.2, 2, 1),
(1045, '2024-10-16 00:47:01', 0, 0, 0, 24.8, 2, 1),
(1045, '2024-10-16 00:47:01', 0, 0, 0, 24.4, 2, 1),
(1045, '2024-10-16 00:47:02', 0, 0, 0, 24.6, 2, 1),
(1045, '2024-10-16 00:47:02', 0, 0, 0, 22.7, 2, 1),
(1045, '2024-10-16 00:47:03', 0, 0, 0, 22.6, 2, 1),
(1045, '2024-10-16 00:47:03', 0, 0, 0, 22.1, 2, 1),
(1045, '2024-10-16 00:47:04', 0, 0, 0, 22.7, 2, 1),
(1045, '2024-10-16 00:47:22', 0, 0, 0, 23.9, 3, 5),
(1045, '2024-10-16 00:47:23', 0, 0, 0, 24.8, 3, 5),
(1045, '2024-10-16 00:47:23', 0, 0, 0, 22.1, 3, 5),
(1045, '2024-10-16 00:47:24', 0, 0, 0, 22.1, 3, 5),
(1045, '2024-10-16 00:47:24', 0, 0, 0, 22.1, 3, 5),
(1045, '2024-10-16 00:47:25', 0, 0, 0, 22.1, 3, 5),
(1045, '2024-10-16 00:47:25', 0, 0, 0, 24.5, 3, 5),
(1045, '2024-10-16 00:47:26', 0, 0, 0, 23, 3, 5),
(1045, '2024-10-16 00:47:26', 0, 0, 0, 24.7, 3, 5),
(1045, '2024-10-16 00:47:27', 0, 0, 0, 23.4, 3, 5),
(1045, '2024-10-16 00:47:27', 0, 0, 0, 22.1, 3, 5),
(1045, '2024-10-16 00:47:39', 0, 0, 0, 23, 3, 4),
(1045, '2024-10-16 00:47:39', 0, 0, 0, 25, 3, 4),
(1045, '2024-10-16 00:47:40', 0, 0, 0, 24.9, 3, 4),
(1045, '2024-10-16 00:47:40', 0, 0, 0, 24.3, 3, 4),
(1045, '2024-10-16 00:47:41', 0, 0, 0, 25, 3, 4),
(1045, '2024-10-16 00:47:41', 0, 0, 0, 23.2, 3, 4),
(1045, '2024-10-16 00:47:42', 0, 0, 0, 25, 3, 4),
(1045, '2024-10-16 00:47:42', 0, 0, 0, 23.3, 3, 4),
(1045, '2024-10-16 00:47:43', 0, 0, 0, 23.5, 3, 4),
(1045, '2024-10-16 00:47:43', 0, 0, 0, 23.8, 3, 4),
(1045, '2024-10-16 00:47:44', 0, 0, 0, 23.4, 3, 4),
(1045, '2024-10-16 00:47:44', 0, 0, 0, 24.6, 3, 4),
(1045, '2024-10-16 00:47:45', 0, 0, 0, 23.9, 3, 4),
(1045, '2024-10-16 00:47:45', 0, 0, 0, 24.7, 3, 4),
(1045, '2024-10-16 00:47:46', 0, 0, 0, 22.7, 3, 4),
(1045, '2024-10-16 00:47:54', 0, 0, 0, 23.5, 3, 3),
(1045, '2024-10-16 00:47:55', 0, 0, 0, 23.2, 3, 3),
(1045, '2024-10-16 00:47:55', 0, 0, 0, 23.6, 3, 3),
(1045, '2024-10-16 00:47:56', 0, 0, 0, 24.6, 3, 3),
(1045, '2024-10-16 00:47:56', 0, 0, 0, 24, 3, 3),
(1045, '2024-10-16 00:47:57', 0, 0, 0, 24.2, 3, 3),
(1045, '2024-10-16 00:47:57', 0, 0, 0, 22.8, 3, 3),
(1045, '2024-10-16 00:47:58', 0, 0, 0, 22.7, 3, 3),
(1045, '2024-10-16 00:47:58', 0, 0, 0, 24.8, 3, 3),
(1045, '2024-10-16 00:47:59', 0, 0, 0, 22.1, 3, 3),
(1045, '2024-10-16 00:47:59', 0, 0, 0, 24, 3, 3),
(1045, '2024-10-16 00:48:00', 0, 0, 0, 23.8, 3, 3),
(1045, '2024-10-16 00:48:00', 0, 0, 0, 24.8, 3, 3),
(1045, '2024-10-16 00:48:01', 0, 0, 0, 24.6, 3, 3),
(1045, '2024-10-16 00:48:01', 0, 0, 0, 24.6, 3, 3),
(1045, '2024-10-16 00:48:14', 0, 0, 0, 23.2, 3, 2),
(1045, '2024-10-16 00:48:14', 0, 0, 0, 22.4, 3, 2),
(1045, '2024-10-16 00:48:15', 0, 0, 0, 22.4, 3, 2),
(1045, '2024-10-16 00:48:15', 0, 0, 0, 24.9, 3, 2),
(1045, '2024-10-16 00:48:16', 0, 0, 0, 24.2, 3, 2),
(1045, '2024-10-16 00:48:16', 0, 0, 0, 24.3, 3, 2),
(1045, '2024-10-16 00:48:17', 0, 0, 0, 22.8, 3, 2),
(1045, '2024-10-16 00:48:17', 0, 0, 0, 22, 3, 2),
(1045, '2024-10-16 00:48:18', 0, 0, 0, 22.7, 3, 2),
(1045, '2024-10-16 00:48:27', 0, 0, 0, 22.7, 3, 1),
(1045, '2024-10-16 00:48:27', 0, 0, 0, 24.2, 3, 1),
(1045, '2024-10-16 00:48:28', 0, 0, 0, 24.7, 3, 1),
(1045, '2024-10-16 00:48:28', 0, 0, 0, 24.3, 3, 1),
(1045, '2024-10-16 00:48:29', 0, 0, 0, 23.2, 3, 1),
(1045, '2024-10-16 00:48:29', 0, 0, 0, 23, 3, 1),
(1045, '2024-10-16 00:48:30', 0, 0, 0, 22.4, 3, 1),
(1045, '2024-10-16 00:48:30', 0, 0, 0, 22.1, 3, 1),
(1045, '2024-10-16 00:48:31', 0, 0, 0, 25.1, 3, 1),
(1045, '2024-10-16 00:48:31', 0, 0, 0, 23.3, 3, 1),
(1045, '2024-10-16 00:48:32', 0, 0, 0, 23.1, 3, 1),
(1045, '2024-10-22 17:27:49', 0, 0, 0, 23.7, 3, 6),
(1045, '2024-10-22 17:27:49', 0, 0, 0, 23.3, 3, 6),
(1045, '2024-10-22 17:27:50', 0, 0, 0, 25.1, 3, 6),
(1045, '2024-10-22 17:27:50', 0, 0, 0, 25.9, 3, 6),
(1045, '2024-10-22 17:27:51', 0, 0, 0, 25.2, 3, 6),
(1045, '2024-10-22 17:27:51', 0, 0, 0, 25.4, 3, 6),
(1045, '2024-10-22 17:27:52', 0, 0, 0, 23.4, 3, 6),
(1045, '2024-10-22 17:27:52', 0, 0, 0, 25.6, 3, 6),
(1045, '2024-10-22 17:27:53', 0, 0, 0, 23.9, 3, 6),
(1045, '2024-10-22 17:27:53', 0, 0, 0, 25.7, 3, 6),
(1045, '2024-10-22 17:27:54', 0, 0, 0, 23.8, 3, 6),
(1045, '2024-10-22 17:27:54', 0, 0, 0, 25.9, 3, 6),
(1045, '2024-10-22 17:27:55', 0, 0, 0, 23.9, 3, 6),
(1045, '2024-10-22 17:27:55', 0, 0, 0, 26, 3, 6),
(1045, '2024-10-22 17:28:02', 0, 0, 0, 23.1, 3, 5),
(1045, '2024-10-22 17:28:03', 0, 0, 0, 23.8, 3, 5),
(1045, '2024-10-22 17:28:03', 0, 0, 0, 24.5, 3, 5),
(1045, '2024-10-22 17:28:04', 0, 0, 0, 24, 3, 5),
(1045, '2024-10-22 17:28:04', 0, 0, 0, 23.5, 3, 5),
(1045, '2024-10-22 17:28:05', 0, 0, 0, 23.7, 3, 5),
(1045, '2024-10-22 17:28:05', 0, 0, 0, 23.8, 3, 5),
(1045, '2024-10-22 17:28:06', 0, 0, 0, 25.9, 3, 5),
(1045, '2024-10-22 17:28:06', 0, 0, 0, 25, 3, 5),
(1045, '2024-10-22 17:28:12', 0, 0, 0, 23.5, 3, 4),
(1045, '2024-10-22 17:28:12', 0, 0, 0, 23.3, 3, 4),
(1045, '2024-10-22 17:28:13', 0, 0, 0, 26, 3, 4),
(1045, '2024-10-22 17:28:13', 0, 0, 0, 23, 3, 4),
(1045, '2024-10-22 17:28:14', 0, 0, 0, 23.2, 3, 4),
(1045, '2024-10-27 11:14:13', 75, 3, 0, 24.3, 3, 1),
(1045, '2024-10-27 11:14:14', 75, 3, 0, 24.2, 3, 1),
(1045, '2024-10-27 11:14:14', 75, 3, 0, 24.1, 3, 1),
(1045, '2024-10-27 11:14:15', 75, 3, 0, 26, 3, 1),
(1045, '2024-10-27 11:14:15', 75, 3, 0, 25.8, 3, 1),
(1045, '2024-10-27 11:14:16', 80, 3, 0, 25.7, 3, 1),
(1045, '2024-10-27 11:14:16', 80, 3, 0, 25.9, 3, 1),
(1045, '2024-10-27 11:14:17', 80, 3, 0, 26, 3, 1),
(1045, '2024-10-27 11:14:17', 80, 3, 0, 25.8, 3, 1),
(1045, '2024-10-27 11:14:18', 75, 3, 0, 26.2, 3, 1),
(1045, '2024-10-27 11:14:18', 75, 3, 0, 25.9, 3, 1),
(1045, '2024-10-27 11:14:19', 75, 3, 0, 26, 3, 1),
(1045, '2024-10-27 11:14:19', 80, 3, 0, 26, 3, 1),
(1045, '2024-10-27 11:14:20', 80, 3, 0, 24.3, 3, 1),
(1045, '2024-10-27 11:14:20', 80, 3, 0, 25.9, 3, 1),
(1045, '2024-10-27 11:14:21', 75, 3, 0, 24.2, 3, 1),
(1045, '2024-10-27 11:14:21', 75, 3, 0, 24.4, 3, 1),
(1045, '2024-10-27 11:14:22', 75, 3, 0, 26.3, 3, 1),
(1045, '2024-10-27 11:14:22', 80, 3, 0, 25.9, 3, 1),
(1045, '2024-10-27 11:14:23', 80, 3, 0, 24, 3, 1),
(1045, '2024-10-27 11:14:23', 75, 3, 0, 24, 3, 1),
(1045, '2024-10-27 11:14:24', 75, 3, 0, 24.3, 3, 1),
(1045, '2024-10-27 11:14:24', 75, 3, 0, 24.1, 3, 1),
(1045, '2024-10-27 11:14:25', 80, 3, 0, 25.9, 3, 1),
(1045, '2024-10-27 11:14:25', 80, 3, 0, 26, 3, 1),
(1045, '2024-10-27 11:14:26', 80, 3, 0, 24, 3, 1),
(1045, '2024-10-27 11:14:26', 75, 3, 0, 24.2, 3, 1),
(1045, '2024-10-27 11:14:27', 75, 3, 0, 24.1, 3, 1),
(1045, '2024-10-27 11:14:27', 80, 3, 0, 26.1, 3, 1),
(1045, '2024-10-27 11:14:28', 80, 3, 0, 26.1, 3, 1),
(1045, '2024-10-27 11:14:28', 75, 3, 0, 24.1, 3, 1),
(1045, '2024-10-27 11:14:29', 75, 3, 0, 24.3, 3, 1),
(1045, '2024-10-27 11:14:29', 80, 3, 0, 25.9, 3, 1),
(1045, '2024-10-27 11:14:30', 80, 3, 0, 26.1, 3, 1),
(1045, '2024-10-27 11:17:32', 80, 3, 0, 24.3, 3, 1),
(1045, '2024-10-27 11:17:32', 80, 3, 0, 26.1, 3, 1),
(1045, '2024-10-27 11:17:33', 80, 3, 0, 26.2, 3, 1),
(1045, '2024-10-27 11:17:33', 80, 3, 0, 26, 3, 1),
(1045, '2024-10-27 11:17:34', 80, 3, 0, 26.2, 3, 1),
(1045, '2024-10-28 11:06:17', 65, 26, 1, 29.2, 3, 1),
(1045, '2024-10-28 11:06:17', 65, 26, 1, 30.9, 3, 1),
(1045, '2024-10-28 11:06:18', 65, 26, 1, 31, 3, 1),
(1045, '2024-10-28 11:06:18', 65, 26, 1, 30.9, 3, 1),
(1045, '2024-10-28 11:06:19', 65, 26, 1, 29.2, 3, 1),
(1045, '2024-10-28 11:06:19', 65, 27, 1, 31.1, 3, 1),
(1045, '2024-10-28 11:06:20', 65, 27, 1, 29.4, 3, 1),
(1045, '2024-10-28 11:06:20', 65, 27, 1, 31.1, 3, 1),
(1045, '2024-10-28 11:06:21', 65, 27, 1, 29.2, 3, 1),
(1045, '2024-10-28 11:06:21', 65, 28, 1, 29.4, 3, 1),
(1045, '2024-10-28 11:06:22', 65, 28, 1, 29.1, 3, 1),
(1045, '2024-10-28 11:06:22', 65, 29, 1, 29.1, 3, 1),
(1045, '2024-10-28 11:06:23', 65, 29, 1, 31.2, 3, 1),
(1045, '2024-10-28 11:06:23', 65, 29, 1, 29.4, 3, 1),
(1045, '2024-10-28 11:06:24', 65, 29, 1, 29.3, 3, 1),
(1045, '2024-10-28 11:06:24', 65, 30, 1, 29.3, 3, 1),
(1045, '2024-10-28 11:06:25', 65, 31, 1, 29.3, 3, 1),
(1045, '2024-10-28 11:06:25', 65, 31, 1, 29.3, 3, 1),
(1045, '2024-10-28 11:06:26', 65, 31, 1, 31.1, 3, 1),
(1045, '2024-10-28 11:06:26', 65, 31, 1, 29.3, 3, 1),
(1045, '2024-10-28 11:06:27', 65, 31, 1, 31.1, 3, 1),
(1045, '2024-10-28 11:06:27', 65, 31, 1, 29.3, 3, 1),
(1045, '2024-10-28 11:06:28', 65, 31, 1, 31, 3, 1),
(1045, '2024-10-28 11:06:28', 65, 31, 1, 29.3, 3, 1),
(1045, '2024-10-28 11:06:29', 65, 31, 1, 29.4, 3, 1),
(1045, '2024-10-28 11:06:29', 65, 31, 1, 29.3, 3, 1),
(1045, '2024-10-28 11:06:30', 65, 32, 1, 29.3, 3, 1),
(1045, '2024-10-28 11:06:30', 65, 32, 1, 31.5, 3, 1),
(1045, '2024-10-28 11:06:31', 65, 32, 1, 29.3, 3, 1),
(1045, '2024-10-28 11:06:31', 65, 32, 1, 29.3, 3, 1),
(1045, '2024-10-28 11:06:32', 65, 33, 1, 29.2, 3, 1),
(1045, '2024-10-28 11:06:32', 65, 33, 1, 31.2, 3, 1),
(1045, '2024-10-28 11:06:33', 65, 33, 1, 31.1, 3, 1),
(1045, '2024-10-28 11:06:33', 65, 33, 1, 29.3, 3, 1),
(1045, '2024-10-28 11:06:34', 65, 33, 1, 30.9, 3, 1),
(1045, '2024-10-28 11:06:34', 65, 33, 1, 29.3, 3, 1),
(1045, '2024-10-28 11:06:35', 65, 33, 1, 29.3, 3, 1),
(1045, '2024-10-28 11:06:35', 65, 33, 1, 29.3, 3, 1),
(1045, '2024-10-28 11:06:36', 65, 33, 1, 29.4, 3, 1),
(1045, '2024-10-28 11:06:36', 65, 33, 1, 31.2, 3, 1),
(1045, '2024-10-28 11:06:37', 65, 33, 1, 29.4, 3, 1),
(1045, '2024-10-28 11:06:37', 65, 33, 1, 29.3, 3, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_suscripcion`
--

CREATE TABLE `registro_suscripcion` (
  `Rut_usuario_suscripcion` int(11) NOT NULL,
  `Tipo_suscripcion` int(11) DEFAULT NULL,
  `Fecha_pago_suscripcion` date DEFAULT NULL,
  `Fecha_inicio_suscripcion` date DEFAULT NULL,
  `Fecha_termino_suscripcion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registro_suscripcion`
--

INSERT INTO `registro_suscripcion` (`Rut_usuario_suscripcion`, `Tipo_suscripcion`, `Fecha_pago_suscripcion`, `Fecha_inicio_suscripcion`, `Fecha_termino_suscripcion`) VALUES
(214710935, 2, '2024-06-27', '2024-06-27', '2025-06-27'),
(208528408, 1, '2024-06-27', '2024-06-27', '2024-12-27'),
(21318930, 1, '2024-10-27', '2024-10-27', '2025-04-27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_usuarios`
--

CREATE TABLE `registro_usuarios` (
  `Correo_usuario` varchar(50) NOT NULL,
  `Clave_usuario` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registro_usuarios`
--

INSERT INTO `registro_usuarios` (`Correo_usuario`, `Clave_usuario`) VALUES
('holaqhace2@gmail.com', '2020'),
('arkanlok@gmail.com', '1234'),
('diegops@gmail.com', '2004');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rutinas`
--

CREATE TABLE `rutinas` (
  `ID_rutina` int(11) NOT NULL,
  `RUT_usuario` varchar(13) DEFAULT NULL,
  `Descripcion_rutina` varchar(50) DEFAULT NULL,
  `ID_Ejercicio` int(11) DEFAULT NULL,
  `Repeticiones` int(11) DEFAULT NULL,
  `Series` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rutinas`
--

INSERT INTO `rutinas` (`ID_rutina`, `RUT_usuario`, `Descripcion_rutina`, `ID_Ejercicio`, `Repeticiones`, `Series`) VALUES
(1, '208528408', 'Brazos', 1, 12, 6),
(2, '208528408', 'Brazos', 2, 11, 3),
(3, '208528408', 'Piernas', 3, 8, 1),
(13, '208528408', 'Brazos', 3, 10, 5),
(16, '208528408', 'Brazos', 2, 5, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `suscripciones`
--

CREATE TABLE `suscripciones` (
  `ID_suscripcion` int(11) DEFAULT NULL,
  `Tipo_suscripcion` varchar(25) DEFAULT NULL,
  `Pago_suscripcion` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `suscripciones`
--

INSERT INTO `suscripciones` (`ID_suscripcion`, `Tipo_suscripcion`, `Pago_suscripcion`) VALUES
(1, 'semestral', 150000),
(2, 'anual', 300000);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `Rut_usuario` varchar(13) NOT NULL,
  `Nombre_usuario` varchar(25) DEFAULT NULL,
  `Apellido_usuario` varchar(25) DEFAULT NULL,
  `Direccion_usuario` varchar(50) DEFAULT NULL,
  `Correo_usuario` varchar(50) DEFAULT NULL,
  `Fono_usuario` int(11) DEFAULT NULL,
  `Entrenador_usuario` varchar(13) DEFAULT NULL,
  `Suscripcion_usuario` int(11) DEFAULT NULL,
  `Pulsera_usuario` int(11) DEFAULT NULL,
  `Sesion_activa` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`Rut_usuario`, `Nombre_usuario`, `Apellido_usuario`, `Direccion_usuario`, `Correo_usuario`, `Fono_usuario`, `Entrenador_usuario`, `Suscripcion_usuario`, `Pulsera_usuario`, `Sesion_activa`) VALUES
('214710935', 'Matilde', 'Fuentes', 'Rancagua', 'holaqhace2@gmail.com', 95377406, '211681055', 1, 12345, 0),
('208528408', 'Alejandro', 'Gutierrez', 'Pasaje los lirios 523', 'arkanlok@gmail.com', 984936728, '188445195', 1, 1045, 0),
('21318930', 'Alonso', 'perez', 'avenida rio cachapoal', 'diegops@gmail.com', 777666999, '188445195', 2, 4, 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ejercicios`
--
ALTER TABLE `ejercicios`
  ADD PRIMARY KEY (`ID_ejercicio`);

--
-- Indices de la tabla `entrenador`
--
ALTER TABLE `entrenador`
  ADD PRIMARY KEY (`Rut_entrenador`);

--
-- Indices de la tabla `pulsera`
--
ALTER TABLE `pulsera`
  ADD PRIMARY KEY (`ID_pulsera`);

--
-- Indices de la tabla `registro_pulsera`
--
ALTER TABLE `registro_pulsera`
  ADD KEY `ID_registro_pulsera` (`ID_registro_pulsera`);

--
-- Indices de la tabla `rutinas`
--
ALTER TABLE `rutinas`
  ADD PRIMARY KEY (`ID_rutina`),
  ADD KEY `ID_Ejercicio` (`ID_Ejercicio`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD KEY `usuario_ibfk_1` (`Entrenador_usuario`),
  ADD KEY `usuario_ibfk_2` (`Pulsera_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `ejercicios`
--
ALTER TABLE `ejercicios`
  MODIFY `ID_ejercicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `rutinas`
--
ALTER TABLE `rutinas`
  MODIFY `ID_rutina` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `registro_pulsera`
--
ALTER TABLE `registro_pulsera`
  ADD CONSTRAINT `registro_pulsera_ibfk_1` FOREIGN KEY (`ID_registro_pulsera`) REFERENCES `pulsera` (`ID_pulsera`);

--
-- Filtros para la tabla `rutinas`
--
ALTER TABLE `rutinas`
  ADD CONSTRAINT `rutinas_ibfk_1` FOREIGN KEY (`ID_Ejercicio`) REFERENCES `ejercicios` (`ID_ejercicio`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`Entrenador_usuario`) REFERENCES `entrenador` (`Rut_entrenador`),
  ADD CONSTRAINT `usuario_ibfk_2` FOREIGN KEY (`Pulsera_usuario`) REFERENCES `pulsera` (`ID_pulsera`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
