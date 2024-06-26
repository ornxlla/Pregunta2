-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-06-2024 a las 22:29:18
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `preguntados`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `datos_usuario`
--

CREATE TABLE `datos_usuario` (
  `id_usuario` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `nacimiento` date NOT NULL,
  `genero` char(1) NOT NULL,
  `imagen_perfil` varchar(255) NOT NULL,
  `pais` varchar(50) NOT NULL,
  `ciudad` varchar(50) NOT NULL,
  `latitud` float NOT NULL,
  `longitud` float NOT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `datos_usuario`
--

INSERT INTO `datos_usuario` (`id_usuario`, `nombre`, `nacimiento`, `genero`, `imagen_perfil`, `pais`, `ciudad`, `latitud`, `longitud`, `fecha_registro`) VALUES
(1, 'Usuario de Testing 1', '2024-03-01', 'M', 'FWGiiYXUIAAEBvI.jpg', 'Argentina', 'La Tablada', -34.6859, -58.5331, '2024-06-23 22:27:44'),
(2, 'Agustin Pucci', '1996-08-05', 'M', 'GNvexEzWMAAXAmi.png', 'Argentina', 'Buenos Aires', -37.6795, -57.9419, '2024-06-23 22:27:44'),
(3, 'Ornella Alonso Reyes', '2002-12-05', 'F', 'cv.jpg', 'Argentina', 'Buenos Aires', -34.685, -58.5343, '2024-06-23 22:27:44'),
(4, 'Alejo Melissari', '2003-11-17', 'M', 'tipo_fantasma_icono.png', 'Argentina', 'Buenos Aires', -34.6566, -58.5891, '2024-06-23 22:27:44'),
(7, 'Test3', '2024-06-05', 'M', 'ailenLopez.jpg', 'Argentina', 'Buenos Aires', 20.6163, -103.922, '2024-06-24 05:25:39'),
(8, 'Test4', '2024-06-27', 'F', 'mujer-joven.jpg', 'Argentina', 'Buenos Aires', -28.1805, -55.7859, '2024-06-25 06:37:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dificultad`
--

CREATE TABLE `dificultad` (
  `id` tinyint(4) NOT NULL,
  `nombre` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `dificultad`
--

INSERT INTO `dificultad` (`id`, `nombre`) VALUES
(0, 'sin dificultad'),
(1, 'facil'),
(2, 'medio'),
(3, 'dificil');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `login`
--

CREATE TABLE `login` (
  `id_usuario` int(10) UNSIGNED NOT NULL,
  `username` varchar(70) NOT NULL,
  `password` varchar(50) NOT NULL,
  `correo` varchar(200) NOT NULL,
  `rol` int(10) UNSIGNED NOT NULL,
  `activado` tinyint(4) NOT NULL,
  `hash` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `login`
--

INSERT INTO `login` (`id_usuario`, `username`, `password`, `correo`, `rol`, `activado`, `hash`) VALUES
(1, 'test1', '1234', 'test1@gmail.comasdg', 3, 1, '1111111111111111111111'),
(2, 'pucci96', '123456', 'agustin.pucci.rubio@gmail.com', 1, 1, '17186412888255'),
(3, 'ornxllita', '1234567', 'ornxalonso@gmail.com', 3, 1, '17187437413853'),
(4, 'alejo', '12345678', 'mikxykitty@gmail.com', 2, 1, '17187450096911'),
(5, 'fazzari', 'ailen123', 'mayrafazzariclerici.01@gmail.com', 1, 1, '17191925982041'),
(7, 'test3', 'test3', 'fraganciasfg2024@gmail.com', 3, 1, '17191995394336'),
(8, 'test4', 'test4', 'fraganciasfg2024@gmail.com', 1, 0, '17192902305601');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partida_clasica_general`
--

CREATE TABLE `partida_clasica_general` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_jugador` int(10) UNSIGNED NOT NULL,
  `puntos` int(10) NOT NULL,
  `hora_inicio` datetime NOT NULL,
  `hora_final` datetime NOT NULL,
  `fecha` date NOT NULL DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `partida_clasica_general`
--

INSERT INTO `partida_clasica_general` (`id`, `id_jugador`, `puntos`, `hora_inicio`, `hora_final`, `fecha`) VALUES
(1, 1, 100, '2024-06-21 23:40:39', '2024-06-21 23:40:55', '2024-06-25'),
(2, 1, 300, '2024-06-21 23:41:57', '2024-06-21 23:42:27', '2024-06-25'),
(6, 2, 0, '2024-06-23 19:37:15', '2024-06-23 19:37:59', '2024-06-25'),
(7, 2, 0, '2024-06-23 19:38:34', '2024-06-23 19:38:36', '2024-06-25'),
(8, 2, 0, '2024-06-23 19:38:38', '2024-06-23 19:38:40', '2024-06-25'),
(9, 2, 0, '2024-06-23 19:38:41', '2024-06-23 19:38:51', '2024-06-25'),
(10, 3, 0, '2024-06-23 19:39:16', '2024-06-23 19:39:17', '2024-06-25'),
(11, 4, 0, '2024-06-23 21:49:54', '0000-00-00 00:00:00', '2024-06-25'),
(12, 1, 0, '2024-06-24 06:44:00', '2024-06-24 06:52:22', '2024-06-25'),
(13, 1, 0, '2024-06-24 06:52:26', '2024-06-24 06:54:29', '2024-06-25'),
(14, 1, 0, '2024-06-24 06:56:02', '2024-06-24 06:56:30', '2024-06-25'),
(15, 1, 0, '2024-06-24 06:56:34', '2024-06-24 06:57:42', '2024-06-25'),
(16, 1, 0, '2024-06-24 06:57:46', '2024-06-24 06:58:13', '2024-06-25'),
(17, 1, 0, '2024-06-24 06:58:16', '2024-06-24 06:59:16', '2024-06-25'),
(18, 1, 0, '2024-06-24 06:59:20', '2024-06-24 06:59:25', '2024-06-25'),
(19, 1, 0, '2024-06-24 06:59:29', '2024-06-24 06:59:37', '2024-06-25'),
(20, 1, 0, '2024-06-24 07:00:20', '2024-06-24 07:00:35', '2024-06-25'),
(21, 1, 0, '2024-06-24 23:06:22', '2024-06-24 23:13:16', '2024-06-25'),
(22, 1, 0, '2024-06-24 23:13:21', '2024-06-24 23:13:58', '2024-06-25'),
(23, 1, 0, '2024-06-24 23:14:02', '2024-06-24 23:15:11', '2024-06-25'),
(24, 1, 0, '2024-06-24 23:15:13', '2024-06-24 23:16:06', '2024-06-25'),
(25, 1, 0, '2024-06-24 23:16:09', '2024-06-24 23:16:40', '2024-06-25'),
(26, 1, 0, '2024-06-24 23:16:43', '2024-06-24 23:17:17', '2024-06-25'),
(27, 1, 0, '2024-06-24 23:17:22', '2024-06-24 23:17:31', '2024-06-25'),
(28, 7, 0, '2024-06-24 23:23:40', '0000-00-00 00:00:00', '2024-06-25'),
(29, 7, 150, '2024-06-25 00:11:07', '2024-06-25 00:11:47', '2024-06-25'),
(30, 3, 400, '2024-06-25 00:34:41', '2024-06-25 00:35:14', '2024-06-25'),
(31, 4, 0, '2024-06-25 00:37:08', '2024-06-25 00:37:29', '2024-06-25'),
(32, 3, 0, '2024-06-25 00:57:55', '2024-06-25 00:58:01', '2024-06-25'),
(33, 4, 0, '2024-06-25 05:07:19', '2024-06-25 05:07:24', '2024-06-25'),
(34, 4, 0, '2024-06-25 05:07:36', '2024-06-25 05:07:37', '2024-06-25'),
(35, 2, 0, '2024-06-25 05:09:37', '2024-06-25 05:09:39', '2024-06-25'),
(36, 2, 0, '2024-06-25 05:13:38', '0000-00-00 00:00:00', '2024-06-25'),
(37, 4, 0, '2024-06-25 05:55:53', '2024-06-25 06:03:44', '2024-06-25'),
(38, 4, 0, '2024-06-25 06:03:48', '2024-06-25 06:04:25', '2024-06-25'),
(39, 4, 0, '2024-06-25 06:04:27', '2024-06-25 06:05:00', '2024-06-25'),
(40, 4, 0, '2024-06-25 06:05:03', '2024-06-25 06:05:24', '2024-06-25'),
(41, 4, 0, '2024-06-25 06:05:26', '0000-00-00 00:00:00', '2024-06-25'),
(42, 1, 0, '2024-06-25 06:24:02', '0000-00-00 00:00:00', '2024-06-25'),
(43, 1, 0, '2024-06-25 06:39:44', '0000-00-00 00:00:00', '2024-06-25'),
(44, 4, 0, '2024-06-25 06:42:30', '2024-06-25 06:46:09', '2024-06-25'),
(45, 4, 0, '2024-06-25 06:47:39', '0000-00-00 00:00:00', '2024-06-25'),
(46, 4, 0, '2024-06-25 19:42:05', '0000-00-00 00:00:00', '2024-06-25'),
(47, 7, 0, '2024-06-25 23:09:10', '0000-00-00 00:00:00', '2024-06-25'),
(48, 3, 200, '2024-06-26 20:28:27', '2024-06-26 20:28:39', '2024-06-26'),
(49, 3, 50, '2024-06-26 20:28:45', '2024-06-26 20:28:56', '2024-06-26'),
(50, 3, 200, '2024-06-26 20:28:59', '2024-06-26 20:29:10', '2024-06-26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partida_clasica_respuestas`
--

CREATE TABLE `partida_clasica_respuestas` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_jugador` int(10) UNSIGNED NOT NULL,
  `id_partida` int(10) UNSIGNED NOT NULL,
  `id_pregunta` int(10) UNSIGNED NOT NULL,
  `acertado` tinyint(1) NOT NULL,
  `hora_final` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `partida_clasica_respuestas`
--

INSERT INTO `partida_clasica_respuestas` (`id`, `id_jugador`, `id_partida`, `id_pregunta`, `acertado`, `hora_final`) VALUES
(1, 2, 6, 20, 1, '2024-06-25 20:05:19'),
(2, 2, 6, 13, 1, '2024-06-25 20:05:19'),
(3, 2, 6, 17, 1, '2024-06-25 20:05:19'),
(4, 2, 6, 1, 1, '2024-06-25 20:05:19'),
(5, 2, 6, 14, 1, '2024-06-25 20:05:19'),
(6, 2, 6, 6, 0, '2024-06-25 20:05:19'),
(7, 2, 9, 2, 1, '2024-06-25 20:05:19'),
(8, 2, 9, 5, 1, '2024-06-25 20:05:19'),
(9, 2, 9, 17, 0, '2024-06-25 20:05:19'),
(10, 1, 18, 10, 0, '2024-06-25 20:05:19'),
(11, 1, 27, 30, 0, '2024-06-25 20:05:19'),
(12, 7, 29, 6, 1, '2024-06-25 20:05:19'),
(13, 7, 29, 26, 1, '2024-06-25 20:05:19'),
(14, 7, 29, 24, 0, '2024-06-25 20:05:19'),
(15, 3, 30, 14, 1, '2024-06-25 20:05:19'),
(16, 3, 30, 5, 1, '2024-06-25 20:05:19'),
(17, 3, 30, 26, 1, '2024-06-25 20:05:19'),
(18, 3, 30, 1, 1, '2024-06-25 20:05:19'),
(19, 3, 30, 21, 0, '2024-06-25 20:05:19'),
(20, 3, 48, 5, 1, '2024-06-26 15:28:30'),
(21, 3, 48, 32, 1, '2024-06-26 15:28:33'),
(22, 3, 48, 15, 0, '2024-06-26 15:28:39'),
(23, 3, 49, 30, 1, '2024-06-26 15:28:48'),
(24, 3, 49, 20, 0, '2024-06-26 15:28:56'),
(25, 3, 50, 14, 1, '2024-06-26 15:29:02'),
(26, 3, 50, 18, 1, '2024-06-26 15:29:05'),
(27, 3, 50, 17, 0, '2024-06-26 15:29:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partida_duelo_general`
--

CREATE TABLE `partida_duelo_general` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_jug1` int(10) UNSIGNED NOT NULL,
  `puntos_jug1` int(10) NOT NULL,
  `id_jug2` int(10) UNSIGNED NOT NULL,
  `puntos_jug2` int(10) NOT NULL,
  `hora_inicio` datetime NOT NULL,
  `hora_fin` datetime NOT NULL,
  `fecha` date NOT NULL DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partida_duelo_respuestas`
--

CREATE TABLE `partida_duelo_respuestas` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_jugador` int(10) UNSIGNED NOT NULL,
  `id_partida` int(10) UNSIGNED NOT NULL,
  `id_pregunta` int(10) UNSIGNED NOT NULL,
  `acertado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas_listado`
--

CREATE TABLE `preguntas_listado` (
  `id_pregunta` int(255) UNSIGNED NOT NULL,
  `texto` varchar(250) NOT NULL,
  `id_tematica` int(10) NOT NULL,
  `id_dificultad` tinyint(4) NOT NULL,
  `aprobado` tinyint(2) NOT NULL,
  `es_sugerida` tinyint(2) NOT NULL DEFAULT 0,
  `fecha_creacion` date NOT NULL DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `preguntas_listado`
--

INSERT INTO `preguntas_listado` (`id_pregunta`, `texto`, `id_tematica`, `id_dificultad`, `aprobado`, `es_sugerida`, `fecha_creacion`) VALUES
(1, '¿Cual es el resultado de la siguiente cuenta? ( 9 + 5 ) * 4 ', 1, 1, 1, 0, '2024-06-25'),
(2, '¿Quién fue el primer presidente de Estados Unidos?', 2, 1, 1, 0, '2024-06-25'),
(3, '¿Cómo se apellidan los cuatro integrantes de Los Simuladores?', 6, 1, 1, 0, '2024-06-25'),
(4, '¿Cuál es la fórmula para calcular el área de un círculo?', 1, 2, 1, 0, '2024-06-25'),
(5, '¿Quién fue el primer presidente de Estados Unidos?', 2, 2, 1, 0, '2024-06-25'),
(6, '¿Qué es la mitosis?', 3, 3, 1, 0, '2024-06-25'),
(7, '¿Cuál es la capital de Francia?', 4, 1, 1, 0, '2024-06-25'),
(8, '¿Cuánto es la raíz cuadrada de 25?', 1, 2, 1, 0, '2024-06-25'),
(9, '¿En qué año se firmó la Declaración de Independencia de Estados Unidos?', 2, 3, 1, 0, '2024-06-25'),
(10, '¿Cuál es el símbolo químico del agua?', 3, 1, 1, 0, '2024-06-25'),
(11, '¿Dónde se encuentra la Gran Barrera de Coral?', 4, 2, 1, 0, '2024-06-25'),
(12, '¿Cuántos lados tiene un hexágono?', 1, 3, 1, 0, '2024-06-25'),
(13, '¿Quién fue el líder de la Revolución Rusa en 1917?', 2, 2, 1, 0, '2024-06-25'),
(14, '¿Cual es la capital de Argentina?', 4, 1, 1, 0, '2024-06-25'),
(15, '¿Cuando es 3 + 4 * 2?', 1, 1, 1, 0, '2024-06-25'),
(16, 'En que año termino la primera guerra mundial?', 2, 1, 1, 0, '2024-06-25'),
(17, '¿Cual es el simbolo quimico de oxigeno?', 7, 1, 1, 0, '2024-06-25'),
(18, '¿Cual es el oceano mas grande del mundo)', 4, 1, 1, 0, '2024-06-25'),
(19, '¿Cual es el resultado de 5+15?', 1, 1, 1, 0, '2024-06-25'),
(20, '¿Cual es la formula del area de un triangulo?', 1, 2, 1, 0, '2024-06-25'),
(21, '¿Quien fue el primer emperador romano?', 2, 2, 1, 0, '2024-06-25'),
(22, '¿Que elemento quimico tiene el simbolo \"Fe\"?', 3, 2, 1, 0, '2024-06-25'),
(23, '¿Cual es el pais mas grande del mundo por area de tierra?', 4, 2, 1, 0, '2024-06-25'),
(24, '¿Cual es el pH del agua pura?', 7, 2, 1, 0, '2024-06-25'),
(25, '¿Cual es el resultado de 13x0 + 13?', 1, 2, 1, 0, '2024-06-25'),
(26, '¿Cual es el resultado de 12/2*3?', 1, 3, 1, 0, '2024-06-25'),
(27, '¿Cual fue la batalla que marco el fin de la guerra de los Cien Años?', 2, 3, 1, 0, '2024-06-25'),
(30, '¿Cuál es la raíz cuadrada de 256?', 1, 1, 1, 0, '2024-06-25'),
(31, '¿Cuántos dientes tienen los humanos?', 3, 1, 1, 0, '2024-06-25'),
(32, '¿Cuál es el océano más grande del mundo?', 4, 1, 1, 0, '2024-06-25'),
(33, 'En la película \"La Sirenita\", ¿Para qué utiliza Ariel un tenedor?', 6, 1, 1, 0, '2024-06-25'),
(34, '¿Cuántos mares existen en la Tierra?', 4, 1, 1, 0, '2024-06-25'),
(35, '¿Quién fue el presidente argentino que inauguró el período conocido como la Década Infame en 1930?', 2, 1, 1, 0, '2024-06-26'),
(36, '¿En qué año se produjo la Revolución de Mayo en Argentina?', 2, 0, 1, 0, '2024-06-26'),
(37, '¿Quién pintó la famosa obra \"La Gioconda\" (también conocida como \"La Mona Lisa\")?', 7, 0, 1, 0, '2024-06-26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas_reportadas`
--

CREATE TABLE `preguntas_reportadas` (
  `id` int(255) NOT NULL,
  `id_pregunta` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas_sugeridas`
--

CREATE TABLE `preguntas_sugeridas` (
  `pregunta` varchar(255) NOT NULL,
  `respuesta_correcta` varchar(255) NOT NULL,
  `primera_respuesta_incorrecta` varchar(255) NOT NULL,
  `segunda_respuesta_incorrecta` varchar(255) NOT NULL,
  `tercera_respuesta_incorrecta` varchar(255) NOT NULL,
  `aprobada` tinyint(2) NOT NULL DEFAULT 0,
  `id` int(255) NOT NULL,
  `id_tematica` int(10) NOT NULL,
  `id_dificultad` tinyint(4) NOT NULL,
  `fecha` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `preguntas_sugeridas`
--

INSERT INTO `preguntas_sugeridas` (`pregunta`, `respuesta_correcta`, `primera_respuesta_incorrecta`, `segunda_respuesta_incorrecta`, `tercera_respuesta_incorrecta`, `aprobada`, `id`, `id_tematica`, `id_dificultad`, `fecha`) VALUES
('¿Cómo se llaman los integrantes de Los Simuladores?', 'Ravenna, Santos, Medina, Lamponne', 'Raul, Rodrigo Santos, Lamponne', 'Ravenna, Sanos, Medina, Lamponne', 'Rolo, Santos, Mauricio, Leandro', 0, 1, 6, 1, '2024-06-25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuesta_listado`
--

CREATE TABLE `respuesta_listado` (
  `id_respuesta` int(20) UNSIGNED NOT NULL,
  `id_pregunta` int(10) UNSIGNED NOT NULL,
  `texto` varchar(250) NOT NULL,
  `correcta` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `respuesta_listado`
--

INSERT INTO `respuesta_listado` (`id_respuesta`, `id_pregunta`, `texto`, `correcta`) VALUES
(1, 1, '50', 0),
(2, 1, '56', 1),
(3, 1, '55', 0),
(4, 1, '60', 0),
(5, 1, '75', 0),
(6, 2, 'Abraham Lincoln', 0),
(7, 2, 'George Washington', 1),
(8, 2, 'Thomas Jefferson', 0),
(9, 2, 'John Adams', 0),
(34, 3, 'Ravenna, Santos, Medina, Lamponne', 1),
(35, 3, 'Ravenna, Sanos, Medina, Lamponne', 0),
(36, 3, 'Rodriguez, Santos, Medina, Lamponne', 0),
(37, 3, 'Rodriguez, Saldoval, Martinez, Lamponne', 0),
(50, 4, 'πr²', 0),
(51, 4, '2πr', 0),
(52, 4, 'πr', 1),
(53, 4, '2πr²', 0),
(54, 5, 'Abraham Lincoln', 0),
(55, 5, 'George Washington', 1),
(56, 5, 'Thomas Jefferson', 0),
(57, 5, 'John Adams', 0),
(58, 6, 'Proceso de división celular', 1),
(59, 6, 'Proceso de fusión celular', 0),
(60, 6, 'Proceso de generación de energía', 0),
(61, 6, 'Proceso de transcripción genética', 0),
(62, 7, 'Berlín', 0),
(63, 7, 'Madrid', 0),
(64, 7, 'Roma', 0),
(65, 7, 'París', 1),
(66, 8, '3', 0),
(67, 8, '5', 0),
(68, 8, '4', 1),
(69, 9, '1778', 0),
(70, 9, '1776', 1),
(71, 9, '1789', 0),
(72, 9, '1812', 0),
(73, 10, 'H₂O', 1),
(74, 10, 'CO₂', 0),
(75, 10, 'O₂', 0),
(76, 11, 'Australia', 0),
(77, 11, 'Brasil', 0),
(78, 11, 'Hawái', 0),
(79, 11, 'Australia', 1),
(80, 12, '5', 0),
(81, 12, '6', 1),
(82, 12, '7', 0),
(83, 12, '8', 0),
(84, 13, 'Vladimir Lenin', 1),
(85, 13, 'Joseph Stalin', 0),
(86, 13, 'Leon Trotsky', 0),
(87, 14, 'Cordoba', 0),
(88, 14, 'La Plata', 0),
(89, 14, 'Buenos Aires', 1),
(90, 14, 'Ninguna', 0),
(91, 15, '4', 0),
(92, 15, '11', 1),
(93, 15, '15', 0),
(94, 15, '10', 1),
(95, 16, '1918', 1),
(96, 16, '1914', 0),
(97, 16, '1920', 0),
(98, 16, '1939', 0),
(99, 17, 'O', 0),
(100, 17, 'Oc', 0),
(101, 17, 'Ox', 1),
(102, 17, 'Oxg', 0),
(103, 18, 'Oceano Pacifico', 1),
(104, 18, 'Oceano Atlantico', 0),
(105, 18, 'Oceano Indico', 0),
(106, 18, 'Oceano Artico', 0),
(107, 19, '10', 0),
(108, 19, '20', 1),
(109, 19, '15', 0),
(110, 19, '14', 0),
(111, 20, '(base*altura)/2', 1),
(112, 20, 'base*altura', 0),
(113, 20, 'base + altura', 0),
(114, 20, '(base*altura)*2', 0),
(115, 21, 'Julio Cesar', 0),
(116, 21, 'Augusto', 1),
(117, 21, 'Neron', 0),
(118, 21, 'Trajano', 0),
(119, 22, 'Hierro', 1),
(120, 22, 'Plata', 0),
(121, 22, 'Oro', 0),
(122, 22, 'Cobre', 0),
(123, 23, 'China', 0),
(124, 23, 'Canada', 1),
(125, 23, 'Rusia', 0),
(126, 23, 'Estado Unidos', 0),
(127, 23, 'Argentina', 0),
(128, 23, 'Africa', 0),
(129, 23, 'Europa', 0),
(130, 24, '6', 0),
(131, 24, '7', 1),
(132, 24, '8', 0),
(133, 24, '9', 0),
(134, 24, '5', 0),
(135, 24, '4', 0),
(136, 24, '10', 0),
(137, 24, '11', 0),
(138, 25, '14', 0),
(139, 25, '13', 1),
(140, 25, '10', 0),
(141, 25, '0', 0),
(142, 26, '18', 0),
(143, 26, '20', 1),
(144, 26, '24', 0),
(145, 26, '15', 0),
(146, 27, 'Batalla de Hastings', 0),
(147, 27, 'Batalla de Agincourt', 0),
(148, 27, 'Batalla de Castillon', 1),
(149, 27, 'Batalla de Poitiers', 0),
(150, 30, '16', 1),
(151, 30, '6', 0),
(152, 30, '17', 0),
(153, 30, '5', 0),
(154, 31, '32', 1),
(155, 31, '40', 0),
(156, 31, '50', 0),
(157, 31, '35', 0),
(158, 31, '38', 0),
(159, 31, '28', 0),
(160, 32, 'Atlántico', 0),
(161, 32, 'Pacifico', 1),
(162, 32, 'Indico', 0),
(163, 32, 'Ártico', 0),
(164, 32, 'Antártico', 0),
(165, 33, 'Para rascarse la espalda.', 0),
(166, 33, 'Para peinarse.', 1),
(167, 33, 'Para comer.', 0),
(168, 33, 'Para dibujar en la arena.', 0),
(169, 34, '60', 1),
(170, 34, '30', 0),
(171, 34, '50', 0),
(172, 34, '48', 0),
(173, 34, '55', 0),
(174, 35, 'Juan Domingo Perón', 0),
(175, 35, 'Hipólito Yrigoyen', 0),
(176, 35, 'Marcelo Torcuato de Alvear', 1),
(177, 35, 'Raúl Alfonsín', 0),
(178, 35, 'Arturo Frondizi', 0),
(179, 36, '1810', 1),
(180, 36, '1820', 0),
(181, 36, '1830', 0),
(182, 36, '1850', 0),
(183, 37, 'Leonardo da Vinci', 1),
(184, 37, 'Pablo Picasso', 0),
(185, 37, 'Vincent van Gogh', 0),
(186, 37, 'Michelangelo Buonarroti', 0),
(187, 37, 'Claude Monet', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre`) VALUES
(1, 'Jugador'),
(2, 'Editor'),
(3, 'Administrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tematicas`
--

CREATE TABLE `tematicas` (
  `id_tematica` int(25) NOT NULL,
  `nombre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tematicas`
--

INSERT INTO `tematicas` (`id_tematica`, `nombre`) VALUES
(1, 'Matemáticas'),
(2, 'Historia'),
(3, 'Ciencias'),
(4, 'Geografía'),
(5, 'Química'),
(6, 'Entretenimiento'),
(7, 'Arte'),
(8, 'Deporte');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `datos_usuario`
--
ALTER TABLE `datos_usuario`
  ADD KEY `fk_idusuario` (`id_usuario`);

--
-- Indices de la tabla `dificultad`
--
ALTER TABLE `dificultad`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indices de la tabla `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id_usuario`,`username`),
  ADD KEY `fk_rol` (`rol`);

--
-- Indices de la tabla `partida_clasica_general`
--
ALTER TABLE `partida_clasica_general`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_partida_general_idusuario` (`id_jugador`);

--
-- Indices de la tabla `partida_clasica_respuestas`
--
ALTER TABLE `partida_clasica_respuestas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_jugador` (`id_jugador`),
  ADD KEY `fk_partida` (`id_partida`),
  ADD KEY `fk_pregunta` (`id_pregunta`);

--
-- Indices de la tabla `partida_duelo_general`
--
ALTER TABLE `partida_duelo_general`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_jug1` (`id_jug1`),
  ADD KEY `fk_jug2` (`id_jug2`);

--
-- Indices de la tabla `partida_duelo_respuestas`
--
ALTER TABLE `partida_duelo_respuestas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_partida_duelo` (`id_partida`),
  ADD KEY `fk_jugador_duelo` (`id_jugador`),
  ADD KEY `fk_pregunta_duelo` (`id_pregunta`);

--
-- Indices de la tabla `preguntas_listado`
--
ALTER TABLE `preguntas_listado`
  ADD PRIMARY KEY (`id_pregunta`),
  ADD KEY `fk_preg_listado` (`id_tematica`),
  ADD KEY `fk_dificultad` (`id_dificultad`);

--
-- Indices de la tabla `preguntas_reportadas`
--
ALTER TABLE `preguntas_reportadas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pregunta_rep` (`id_pregunta`);

--
-- Indices de la tabla `preguntas_sugeridas`
--
ALTER TABLE `preguntas_sugeridas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_dif` (`id_dificultad`),
  ADD KEY `fk_tematica` (`id_tematica`);

--
-- Indices de la tabla `respuesta_listado`
--
ALTER TABLE `respuesta_listado`
  ADD PRIMARY KEY (`id_respuesta`),
  ADD KEY `fk_respuesta_preg` (`id_pregunta`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `tematicas`
--
ALTER TABLE `tematicas`
  ADD PRIMARY KEY (`id_tematica`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `dificultad`
--
ALTER TABLE `dificultad`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `login`
--
ALTER TABLE `login`
  MODIFY `id_usuario` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `partida_clasica_general`
--
ALTER TABLE `partida_clasica_general`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de la tabla `partida_clasica_respuestas`
--
ALTER TABLE `partida_clasica_respuestas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `partida_duelo_general`
--
ALTER TABLE `partida_duelo_general`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `partida_duelo_respuestas`
--
ALTER TABLE `partida_duelo_respuestas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `preguntas_listado`
--
ALTER TABLE `preguntas_listado`
  MODIFY `id_pregunta` int(255) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de la tabla `preguntas_reportadas`
--
ALTER TABLE `preguntas_reportadas`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `preguntas_sugeridas`
--
ALTER TABLE `preguntas_sugeridas`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `respuesta_listado`
--
ALTER TABLE `respuesta_listado`
  MODIFY `id_respuesta` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=188;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `datos_usuario`
--
ALTER TABLE `datos_usuario`
  ADD CONSTRAINT `fk_idusuario` FOREIGN KEY (`id_usuario`) REFERENCES `login` (`id_usuario`);

--
-- Filtros para la tabla `login`
--
ALTER TABLE `login`
  ADD CONSTRAINT `fk_rol` FOREIGN KEY (`rol`) REFERENCES `roles` (`id_rol`);

--
-- Filtros para la tabla `partida_clasica_general`
--
ALTER TABLE `partida_clasica_general`
  ADD CONSTRAINT `fk_partida_general_idusuario` FOREIGN KEY (`id_jugador`) REFERENCES `login` (`id_usuario`);

--
-- Filtros para la tabla `partida_clasica_respuestas`
--
ALTER TABLE `partida_clasica_respuestas`
  ADD CONSTRAINT `fk_jugador` FOREIGN KEY (`id_jugador`) REFERENCES `login` (`id_usuario`),
  ADD CONSTRAINT `fk_partida` FOREIGN KEY (`id_partida`) REFERENCES `partida_clasica_general` (`id`),
  ADD CONSTRAINT `fk_pregunta` FOREIGN KEY (`id_pregunta`) REFERENCES `preguntas_listado` (`id_pregunta`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
