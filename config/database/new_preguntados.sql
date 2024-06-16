-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-06-2024 a las 11:12:14
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.1.25

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
  `longitud` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `datos_usuario`
--

INSERT INTO `datos_usuario` (`id_usuario`, `nombre`, `nacimiento`, `genero`, `imagen_perfil`, `pais`, `ciudad`, `latitud`, `longitud`) VALUES
(1, 'Usuario de Testing 1', '2024-03-01', 'M', 'test1.jpg', 'Argentina', 'La Tablada', -34.6859, -58.5331);

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
(1, 'test1', '1234', 'test1@gmail.com', 1, 1, '1111111111111111111111');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partida`
--

CREATE TABLE `partida` (
  `id` int(11) NOT NULL,
  `id_jugador_1` int(10) UNSIGNED NOT NULL,
  `id_jugador_2` int(10) UNSIGNED DEFAULT NULL,
  `hora_inicio` datetime NOT NULL,
  `hora_final` datetime DEFAULT NULL,
  `puntos_jugador_1` int(11) DEFAULT NULL,
  `puntos_jugador_2` int(11) DEFAULT NULL,
  `id_pregunta` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `partida`
--

INSERT INTO `partida` (`id`, `id_jugador_1`, `id_jugador_2`, `hora_inicio`, `hora_final`, `puntos_jugador_1`, `puntos_jugador_2`, `id_pregunta`) VALUES
(1, 1, NULL, '2024-06-08 13:21:25', '2024-06-13 20:57:08', 22, NULL, NULL),
(15, 2, NULL, '2024-06-11 23:35:14', '2024-06-11 23:36:41', NULL, NULL, NULL),
(16, 1, NULL, '2024-06-11 23:36:44', NULL, NULL, NULL, NULL),
(17, 2, NULL, '2024-06-11 23:42:22', NULL, NULL, NULL, NULL),
(18, 1, NULL, '2024-06-11 23:47:27', NULL, NULL, NULL, NULL),
(19, 1, NULL, '2024-06-12 17:00:40', NULL, NULL, NULL, NULL),
(20, 1, NULL, '2024-06-13 20:57:00', NULL, NULL, NULL, NULL),
(21, 1, NULL, '2024-06-13 20:57:10', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas`
--

CREATE TABLE `preguntas` (
  `id_pregunta` int(11) NOT NULL,
  `pregunta_texto` text NOT NULL,
  `id_tematica` int(11) DEFAULT NULL,
  `utilizada` tinyint(1) NOT NULL,
  `contador_respuestas_correctas` int(11) NOT NULL DEFAULT 0,
  `contador_respuestas_incorrectas` int(11) NOT NULL DEFAULT 0,
  `id_dificultad` tinyint(4) NOT NULL,
  `estado` tinyint(4) NOT NULL,
  `apariciones` int(11) NOT NULL,
  `reportada` tinyint(1) NOT NULL DEFAULT 0,
  `es_sugerida` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `preguntas`
--

INSERT INTO `preguntas` (`id_pregunta`, `pregunta_texto`, `id_tematica`, `utilizada`, `contador_respuestas_correctas`, `contador_respuestas_incorrectas`, `id_dificultad`, `estado`, `apariciones`, `reportada`, `es_sugerida`) VALUES
(1, '¿Cuál es la fórmula para calcular el área de un círculo?', 1, 0, 8, 1, 1, 1, 14, 0, 0),
(2, '¿Quién fue el primer presidente de Estados Unidos?', 2, 0, 0, 0, 2, 1, 4, 0, 0),
(3, '¿Qué es la mitosis?', 3, 0, 0, 0, 3, 1, 5, 0, 0),
(4, '¿Cuál es la capital de Francia?', 4, 0, 8, 0, 1, 1, 11, 0, 0),
(5, '¿Cuánto es la raíz cuadrada de 25?', 1, 0, 1, 0, 2, 1, 4, 0, 0),
(6, '¿En qué año se firmó la Declaración de Independencia de Estados Unidos?', 2, 0, 3, 0, 3, 1, 9, 0, 0),
(7, '¿Cuál es el símbolo químico del agua?', 3, 0, 11, 0, 1, 1, 7, 0, 0),
(8, '¿Dónde se encuentra la Gran Barrera de Coral?', 4, 0, 2, 0, 2, 1, 6, 0, 0),
(9, '¿Cuántos lados tiene un hexágono?', 1, 0, 2, 0, 3, 1, 9, 0, 0),
(10, '¿Quién fue el líder de la Revolución Rusa en 1917?', 2, 0, 7, 0, 1, 1, 11, 0, 0),
(11, '¿Cual es la capital de Argentina?', 4, 0, 0, 0, 1, 1, 0, 0, 0),
(12, '¿Cuando es 3 + 4 * 2?', 1, 0, 0, 0, 1, 1, 0, 0, 0),
(13, 'En que año termino la primera guerra mundial?', 2, 0, 0, 0, 1, 1, 0, 0, 0),
(14, '¿Cual es el simbolo quimico de oxigeno?', 7, 0, 0, 0, 1, 1, 0, 0, 0),
(15, '¿Cual es el oceano mas grande del mundo)', 4, 0, 0, 0, 1, 1, 0, 0, 0),
(16, '¿Cual es el resultado de 5+15?', 1, 0, 1, 0, 1, 1, 1, 0, 0),
(17, '¿Cual es la formula del area de un triangulo?', 1, 0, 0, 0, 2, 1, 0, 0, 0),
(18, '¿Quien fue el primer emperador romano?', 2, 0, 0, 0, 2, 1, 0, 0, 0),
(19, '¿Que elemento quimico tiene el simbolo \"Fe\"?', 3, 0, 0, 0, 2, 1, 0, 0, 0),
(20, '¿Cual es el pais mas grande del mundo por area de tierra?', 4, 0, 0, 0, 2, 1, 0, 0, 0),
(21, '¿Cual es el pH del agua pura?', 7, 0, 0, 0, 2, 1, 0, 0, 0),
(22, '¿Cual es el resultado de 13x0 + 13?', 1, 0, 0, 0, 2, 1, 0, 0, 0),
(23, '¿Cual es el resultado de 12/2*3?', 1, 0, 0, 0, 3, 1, 0, 0, 0),
(24, '¿Cual fue la batalla que marco el fin de la guerra de los Cien Años?', 2, 0, 0, 0, 3, 1, 0, 0, 0),
(25, 'Que cientifico formulo la eoria de la relatividad?', 3, 0, 0, 0, 3, 1, 0, 0, 0),
(53, '¿Cuál es el animal más rápido del mundo?', 3, 0, 0, 0, 1, 1, 0, 0, 0),
(54, '¿Qué animal tiene mejor visión, el perro o el gato?', 4, 0, 0, 0, 1, 1, 0, 1, 0),
(55, '¿Qué animal es conocido por ser el más venenoso del mundo?', 5, 0, 0, 0, 1, 1, 0, 1, 0),
(56, '¿Cuál es el mamífero terrestre más grande?', 6, 0, 0, 0, 1, 1, 0, 1, 0),
(57, '¿Qué animal es famoso por su camuflaje en la naturaleza?', 7, 0, 0, 0, 1, 1, 0, 1, 0),
(58, '¿Qué animal hiberna durante el invierno?', 8, 0, 0, 0, 1, 1, 0, 1, 0),
(59, '¿Cuál es el animal más inteligente después de los humanos?', 9, 0, 0, 0, 1, 1, 0, 1, 0),
(60, '¿Qué animal tiene la mordida más fuerte en relación a su tamaño?', 10, 0, 0, 0, 1, 1, 0, 1, 0),
(61, '¿Cuál es el animal más grande del mundo?', 1, 0, 0, 0, 1, 1, 0, 1, 0),
(62, '¿Qué animal es conocido como \"el rey de la selva\"?', 2, 0, 0, 0, 1, 1, 0, 1, 0),
(63, '¿Cuál es el animal más rápido del mundo?', 3, 0, 0, 0, 1, 1, 0, 1, 0),
(64, '¿Qué animal tiene mejor visión, el perro o el gato?', 4, 0, 0, 0, 1, 1, 0, 1, 0),
(65, '¿Qué animal es conocido por ser el más venenoso del mundo?', 5, 0, 0, 0, 1, 1, 0, 1, 0),
(66, '¿Cuál es el mamífero terrestre más grande?', 6, 0, 0, 0, 1, 1, 0, 1, 0),
(67, '¿Qué animal es famoso por su camuflaje en la naturaleza?', 7, 0, 0, 0, 1, 1, 0, 1, 0),
(68, '¿Qué animal hiberna durante el invierno?', 8, 0, 0, 0, 1, 1, 0, 1, 0),
(69, '¿Cuál es el animal más inteligente después de los humanos?', 9, 0, 0, 0, 1, 1, 0, 1, 0),
(71, '¿Cuál es el nombre del fenómeno climático que se caracteriza por temperaturas extremadamente altas?', 1, 0, 0, 0, 1, 1, 0, 0, 0),
(72, '¿Qué tipo de nubes forman un halo alrededor de la luna o el sol?', 1, 0, 0, 0, 1, 1, 0, 0, 0),
(73, '¿Qué es un tornado?', 1, 0, 0, 0, 1, 1, 0, 0, 0),
(74, '¿Qué es la precipitación pluvial?', 1, 0, 0, 0, 1, 1, 0, 0, 1),
(75, '¿Cuál es la capa de la atmósfera que se ubica entre la troposfera y la mesosfera?', 1, 0, 0, 0, 1, 1, 0, 0, 1),
(76, '¿Cuál es el nombre del fenómeno atmosférico que se produce cuando dos masas de aire con distintas temperaturas se encuentran?', 1, 0, 0, 0, 1, 1, 0, 0, 1),
(77, '¿Qué instrumento se utiliza para medir la humedad relativa del aire?', 1, 0, 0, 0, 1, 1, 0, 0, 1),
(78, '¿Cuál es el fenómeno atmosférico que se produce cuando el vapor de agua se condensa y se convierte en gotas?', 1, 0, 0, 0, 1, 1, 0, 0, 1),
(79, '¿Cuál es la presión atmosférica promedio a nivel del mar?', 1, 0, 0, 0, 1, 1, 0, 0, 0),
(80, '¿Qué es la evaporación?', 1, 0, 0, 0, 1, 1, 0, 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas_partida`
--

CREATE TABLE `preguntas_partida` (
  `id_partida` int(11) NOT NULL,
  `id_pregunta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `preguntas_partida`
--

INSERT INTO `preguntas_partida` (`id_partida`, `id_pregunta`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(1, 16),
(15, 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas_reportadas`
--

CREATE TABLE `preguntas_reportadas` (
  `id_pregunta_reportada` int(11) NOT NULL,
  `motivo` varchar(255) NOT NULL
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
  `aprobada` tinyint(1) NOT NULL DEFAULT 0,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuesta`
--

CREATE TABLE `respuesta` (
  `id_respuesta` int(11) NOT NULL,
  `id_pregunta` int(11) NOT NULL,
  `correcta` tinyint(1) NOT NULL DEFAULT 0,
  `respuesta_texto` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `respuesta`
--

INSERT INTO `respuesta` (`id_respuesta`, `id_pregunta`, `correcta`, `respuesta_texto`) VALUES
(206, 7, 0, 'CO2'),
(207, 7, 0, 'H2SO4'),
(208, 7, 1, 'H2O'),
(212, 8, 1, 'África'),
(213, 8, 0, 'Asia'),
(214, 8, 0, 'Europa'),
(215, 8, 0, 'América'),
(219, 9, 1, '5'),
(220, 9, 0, '4'),
(221, 9, 0, '6'),
(222, 9, 0, '7'),
(226, 10, 1, 'Thomas Jefferson'),
(227, 10, 0, 'George Washington'),
(228, 10, 0, 'Benjamin Franklin'),
(229, 10, 0, 'John Adams'),
(249, 13, 0, '2'),
(250, 13, 0, '5'),
(332, 1, 0, 'πr²'),
(333, 1, 0, '2πr'),
(334, 1, 1, 'πr'),
(335, 1, 0, '2πr²'),
(336, 2, 0, 'Abraham Lincoln'),
(337, 2, 1, 'George Washington'),
(338, 2, 0, 'Thomas Jefferson'),
(339, 2, 0, 'John Adams'),
(340, 3, 1, 'Proceso de división celular'),
(341, 3, 0, 'Proceso de fusión celular'),
(342, 3, 0, 'Proceso de generación de energía'),
(343, 3, 0, 'Proceso de transcripción genética'),
(344, 4, 0, 'Berlín'),
(345, 4, 0, 'Madrid'),
(346, 4, 0, 'Roma'),
(347, 4, 1, 'París'),
(348, 5, 0, '3'),
(349, 5, 0, '5'),
(350, 5, 1, '4'),
(352, 6, 0, '1776'),
(353, 6, 1, '1776'),
(354, 6, 0, '1789'),
(355, 6, 0, '1812'),
(356, 7, 1, 'H₂O'),
(357, 7, 0, 'NaCl'),
(358, 7, 0, 'CO₂'),
(359, 7, 0, 'O₂'),
(360, 8, 0, 'Australia'),
(361, 8, 0, 'Brasil'),
(362, 8, 0, 'Hawái'),
(363, 8, 1, 'Australia'),
(364, 9, 0, '5'),
(365, 9, 1, '6'),
(366, 9, 0, '7'),
(367, 9, 0, '8'),
(369, 10, 1, 'Vladimir Lenin'),
(370, 10, 0, 'Joseph Stalin'),
(371, 10, 0, 'Leon Trotsky'),
(372, 11, 0, 'Cordoba'),
(373, 11, 0, 'La Plata'),
(374, 11, 1, 'Buenos Aires'),
(375, 11, 0, 'Ninguna'),
(376, 12, 0, '4'),
(377, 12, 1, '11'),
(378, 12, 0, '15'),
(379, 12, 0, '10'),
(380, 13, 1, '1918'),
(381, 13, 0, '1914'),
(382, 13, 0, '1920'),
(383, 13, 0, '1939'),
(384, 14, 0, 'O'),
(385, 14, 0, 'Oc'),
(386, 14, 1, 'Ox'),
(387, 14, 0, 'Oxg'),
(388, 15, 1, 'Oceano Pacifico'),
(389, 15, 0, 'Oceano Atlantico'),
(390, 15, 0, 'Oceano Indico'),
(391, 15, 0, 'Oceano Artico'),
(392, 16, 0, '10'),
(393, 16, 1, '20'),
(394, 16, 0, '15'),
(395, 16, 0, '14'),
(396, 17, 1, '(base*altura)/2'),
(397, 17, 0, 'base*altura'),
(398, 17, 0, 'base + altura'),
(399, 17, 0, '(base*altura)*2'),
(400, 18, 0, 'Julio Cesar'),
(401, 18, 1, 'Augusto'),
(402, 18, 0, 'Neron'),
(403, 18, 0, 'Trajano'),
(404, 19, 1, 'Hierro'),
(405, 19, 0, 'Plata'),
(406, 19, 0, 'Oro'),
(407, 19, 0, 'Cobre'),
(408, 20, 0, 'China'),
(409, 20, 1, 'Canada'),
(410, 20, 0, 'Rusia'),
(411, 20, 0, 'Estado unidos'),
(412, 21, 0, '6'),
(413, 21, 1, '7'),
(414, 21, 0, '8'),
(415, 21, 0, '9'),
(416, 22, 0, '14'),
(417, 22, 1, '13'),
(418, 22, 0, '10'),
(419, 22, 0, '0'),
(420, 23, 0, '18'),
(421, 23, 1, '20'),
(422, 23, 0, '24'),
(423, 23, 0, '15'),
(424, 24, 0, 'Batalla de Hastings'),
(425, 24, 0, 'Batalla de Agincourt'),
(426, 24, 1, 'Batalla de Castillon'),
(427, 24, 0, 'Batalla de Poitiers'),
(428, 25, 0, 'Issac Newton'),
(429, 25, 1, 'Albert Einstein'),
(430, 25, 0, 'Niels Bohr'),
(431, 25, 0, 'Galileo Galilei');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuestas_partida`
--

CREATE TABLE `respuestas_partida` (
  `id_partida` int(11) NOT NULL,
  `id_respuesta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `respuestas_partida`
--

INSERT INTO `respuestas_partida` (`id_partida`, `id_respuesta`) VALUES
(1, 213),
(1, 221),
(1, 222),
(1, 227),
(1, 228),
(1, 332),
(1, 333),
(1, 334),
(1, 336),
(1, 341),
(1, 343),
(1, 347),
(1, 348),
(1, 349),
(1, 350),
(1, 352),
(1, 353),
(1, 354),
(1, 355),
(1, 356),
(1, 360),
(1, 362),
(1, 363),
(1, 365),
(1, 369),
(1, 393),
(15, 365);

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
  `id_tematica` int(11) NOT NULL,
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
(5, 'Matemáticas'),
(6, 'Historia'),
(7, 'Química'),
(8, 'Geografía'),
(9, 'Matemáticas'),
(10, 'Historia'),
(11, 'Química'),
(12, 'Geografía'),
(13, 'Matemáticas'),
(14, 'Historia'),
(15, 'Química'),
(16, 'Geografía');

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
-- Indices de la tabla `partida`
--
ALTER TABLE `partida`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_id_pregunta` (`id_pregunta`);

--
-- Indices de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  ADD PRIMARY KEY (`id_pregunta`),
  ADD KEY `tematicaID` (`id_tematica`),
  ADD KEY `fk_id_dificultad` (`id_dificultad`);

--
-- Indices de la tabla `preguntas_partida`
--
ALTER TABLE `preguntas_partida`
  ADD PRIMARY KEY (`id_partida`,`id_pregunta`),
  ADD KEY `id_pregunta` (`id_pregunta`);

--
-- Indices de la tabla `respuesta`
--
ALTER TABLE `respuesta`
  ADD PRIMARY KEY (`id_respuesta`) USING BTREE,
  ADD KEY `Pregunta_ID` (`id_pregunta`) USING BTREE;

--
-- Indices de la tabla `respuestas_partida`
--
ALTER TABLE `respuestas_partida`
  ADD PRIMARY KEY (`id_partida`,`id_respuesta`),
  ADD KEY `id_respuesta` (`id_respuesta`);

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
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `login`
--
ALTER TABLE `login`
  MODIFY `id_usuario` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `partida`
--
ALTER TABLE `partida`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  MODIFY `id_pregunta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT de la tabla `respuesta`
--
ALTER TABLE `respuesta`
  MODIFY `id_respuesta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=432;

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
-- Filtros para la tabla `preguntas`
--
ALTER TABLE `preguntas`
  ADD CONSTRAINT `fk_id_dificultad` FOREIGN KEY (`id_dificultad`) REFERENCES `dificultad` (`id`);

--
-- Filtros para la tabla `preguntas_partida`
--
ALTER TABLE `preguntas_partida`
  ADD CONSTRAINT `preguntas_partida_ibfk_1` FOREIGN KEY (`id_partida`) REFERENCES `partida` (`id`),
  ADD CONSTRAINT `preguntas_partida_ibfk_2` FOREIGN KEY (`id_pregunta`) REFERENCES `preguntas` (`id_pregunta`);

--
-- Filtros para la tabla `respuestas_partida`
--
ALTER TABLE `respuestas_partida`
  ADD CONSTRAINT `respuestas_partida_ibfk_1` FOREIGN KEY (`id_partida`) REFERENCES `partida` (`id`),
  ADD CONSTRAINT `respuestas_partida_ibfk_2` FOREIGN KEY (`id_respuesta`) REFERENCES `respuesta` (`id_respuesta`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
