-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-06-2024 a las 11:11:56
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
(1, 'Usuario de Testing 1', '2024-03-01', 'M', 'FWGiiYXUIAAEBvI.jpg', 'Argentina', 'La Tablada', -34.6859, -58.5331),
(2, 'Agustin Pucci', '1996-08-05', 'M', 'GNvexEzWMAAXAmi.png', 'Argentina', 'Buenos Aires', -37.6795, -57.9419),
(3, 'Ornella Alonso Reyes', '2002-12-05', 'F', 'cv.jpg', 'Argentina', 'Buenos Aires', -34.685, -58.5343),
(4, 'Alejo Melissari', '2003-11-17', 'M', 'tipo_fantasma_icono.png', 'Argentina', 'Buenos Aires', -34.6566, -58.5891);

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
(1, 'test1', '1234', 'test1@gmail.comasdg', 1, 1, '1111111111111111111111'),
(2, 'pucci96', '123456', 'agustin.pucci.rubio@gmail.com', 1, 1, '17186412888255'),
(3, 'ornxllita', '1234567', 'ornxalonso@gmail.com', 3, 1, '17187437413853'),
(4, 'alejo', '12345678', 'mikxykitty@gmail.com', 2, 1, '17187450096911');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partida_clasica_general`
--

CREATE TABLE `partida_clasica_general` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_jugador` int(10) UNSIGNED NOT NULL,
  `puntos` int(10) NOT NULL,
  `hora_inicio` datetime NOT NULL,
  `hora_final` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `partida_clasica_general`
--

INSERT INTO `partida_clasica_general` (`id`, `id_jugador`, `puntos`, `hora_inicio`, `hora_final`) VALUES

(1, 1, 100, '2024-06-21 23:40:39', '2024-06-21 23:40:55'),
(2, 1, 300, '2024-06-21 23:41:57', '2024-06-21 23:42:27'),
(3, 11, 0, '2024-06-22 16:19:53', '2024-06-22 16:20:01'),
(4, 11, 300, '2024-06-22 18:09:57', '2024-06-22 18:10:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partida_clasica_respuestas`
--

CREATE TABLE `partida_clasica_respuestas` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_jugador` int(10) UNSIGNED NOT NULL,
  `id_partida` int(10) UNSIGNED NOT NULL,
  `id_pregunta` int(10) UNSIGNED NOT NULL,
  `acertado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `hora_fin` datetime NOT NULL
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
  `aprobado` tinyint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `preguntas_listado`
--

INSERT INTO `preguntas_listado` (`id_pregunta`, `texto`, `id_tematica`, `id_dificultad`, `aprobado`) VALUES
(1, '¿Cual es el resultado de la siguiente cuenta? ( 9 + 5 ) * 4 ', 1, 1, 1),
(2, '¿Quién fue el primer presidente de Estados Unidos?', 2, 1, 1),
(3, '¿Cómo se apellidan los cuatro integrantes de Los Simuladores?', 6, 1, 0),
(4, '¿Cuál es la fórmula para calcular el área de un círculo?', 1, 2, 1),
(5, '¿Quién fue el primer presidente de Estados Unidos?', 2, 2, 1),
(6, '¿Qué es la mitosis?', 3, 3, 1),
(7, '¿Cuál es la capital de Francia?', 4, 1, 1),
(8, '¿Cuánto es la raíz cuadrada de 25?', 1, 2, 1),
(9, '¿En qué año se firmó la Declaración de Independencia de Estados Unidos?', 2, 3, 1),
(10, '¿Cuál es el símbolo químico del agua?', 3, 1, 1),
(11, '¿Dónde se encuentra la Gran Barrera de Coral?', 4, 2, 1),
(12, '¿Cuántos lados tiene un hexágono?', 1, 3, 1),
(13, '¿Quién fue el líder de la Revolución Rusa en 1917?', 2, 2, 1),
(14, '¿Cual es la capital de Argentina?', 4, 1, 1),
(15, '¿Cuando es 3 + 4 * 2?', 1, 1, 1),
(16, 'En que año termino la primera guerra mundial?', 2, 1, 1),
(17, '¿Cual es el simbolo quimico de oxigeno?', 7, 1, 1),
(18, '¿Cual es el oceano mas grande del mundo)', 4, 1, 1),
(19, '¿Cual es el resultado de 5+15?', 1, 1, 1),
(20, '¿Cual es la formula del area de un triangulo?', 1, 2, 1),
(21, '¿Quien fue el primer emperador romano?', 2, 2, 1),
(22, '¿Que elemento quimico tiene el simbolo \"Fe\"?', 3, 2, 1),
(23, '¿Cual es el pais mas grande del mundo por area de tierra?', 4, 2, 1),
(24, '¿Cual es el pH del agua pura?', 7, 2, 1),
(25, '¿Cual es el resultado de 13x0 + 13?', 1, 2, 1),
(26, '¿Cual es el resultado de 12/2*3?', 1, 3, 1),
(27, '¿Cual fue la batalla que marco el fin de la guerra de los Cien Años?', 2, 3, 1);

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
  `id_dificultad` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `preguntas_sugeridas`
--

INSERT INTO `preguntas_sugeridas` (`pregunta`, `respuesta_correcta`, `primera_respuesta_incorrecta`, `segunda_respuesta_incorrecta`, `tercera_respuesta_incorrecta`, `aprobada`, `id`, `id_tematica`, `id_dificultad`) VALUES
('¿Cómo se llaman los integrantes de Los Simuladores?', 'Ravenna, Santos, Medina, Lamponne', 'Raul, Rodrigo Santos, Lamponne', 'Ravenna, Sanos, Medina, Lamponne', 'Rolo, Santos, Mauricio, Leandro', 0, 1, 6, 1);

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
(149, 27, 'Batalla de Poitiers', 0);

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
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `login`
--
ALTER TABLE `login`
  MODIFY `id_usuario` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `partida_clasica_general`
--
ALTER TABLE `partida_clasica_general`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `partida_clasica_respuestas`
--
ALTER TABLE `partida_clasica_respuestas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `id_pregunta` int(255) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `preguntas_reportadas`
--
ALTER TABLE `preguntas_reportadas`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `preguntas_sugeridas`
--
ALTER TABLE `preguntas_sugeridas`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `respuesta_listado`
--
ALTER TABLE `respuesta_listado`
  MODIFY `id_respuesta` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=150;

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

--
-- Filtros para la tabla `partida_duelo_general`
--
ALTER TABLE `partida_duelo_general`
  ADD CONSTRAINT `fk_jug1` FOREIGN KEY (`id_jug1`) REFERENCES `login` (`id_usuario`),
  ADD CONSTRAINT `fk_jug2` FOREIGN KEY (`id_jug2`) REFERENCES `login` (`id_usuario`);

--
-- Filtros para la tabla `partida_duelo_respuestas`
--
ALTER TABLE `partida_duelo_respuestas`
  ADD CONSTRAINT `fk_jugador_duelo` FOREIGN KEY (`id_jugador`) REFERENCES `login` (`id_usuario`),
  ADD CONSTRAINT `fk_partida_duelo` FOREIGN KEY (`id_partida`) REFERENCES `partida_duelo_general` (`id`),
  ADD CONSTRAINT `fk_pregunta_duelo` FOREIGN KEY (`id_pregunta`) REFERENCES `preguntas_listado` (`id_pregunta`);

--
-- Filtros para la tabla `preguntas_listado`
--
ALTER TABLE `preguntas_listado`
  ADD CONSTRAINT `fk_dificultad` FOREIGN KEY (`id_dificultad`) REFERENCES `dificultad` (`id`),
  ADD CONSTRAINT `fk_preg_listado` FOREIGN KEY (`id_tematica`) REFERENCES `tematicas` (`id_tematica`);

--
-- Filtros para la tabla `preguntas_reportadas`
--
ALTER TABLE `preguntas_reportadas`
  ADD CONSTRAINT `fk_pregunta_rep` FOREIGN KEY (`id_pregunta`) REFERENCES `preguntas_listado` (`id_pregunta`);

--
-- Filtros para la tabla `preguntas_sugeridas`
--
ALTER TABLE `preguntas_sugeridas`
  ADD CONSTRAINT `fk_dif` FOREIGN KEY (`id_dificultad`) REFERENCES `dificultad` (`id`),
  ADD CONSTRAINT `fk_tematica` FOREIGN KEY (`id_tematica`) REFERENCES `tematicas` (`id_tematica`);

--
-- Filtros para la tabla `respuesta_listado`
--
ALTER TABLE `respuesta_listado`
  ADD CONSTRAINT `fk_respuesta_preg` FOREIGN KEY (`id_pregunta`) REFERENCES `preguntas_listado` (`id_pregunta`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
