-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-04-2025 a las 06:20:46
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
-- Base de datos: `acomer`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin`
--

CREATE TABLE `admin` (
  `idadmin` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `admin`
--

INSERT INTO `admin` (`idadmin`, `usuario_id`) VALUES
(1, 123),
(2, 125);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumnos`
--

CREATE TABLE `alumnos` (
  `idalumno` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `apellido` varchar(20) NOT NULL,
  `curso_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `alumnos`
--

INSERT INTO `alumnos` (`idalumno`, `nombre`, `apellido`, `curso_id`) VALUES
(22, 'Juan', 'Gómez', 23),
(23, 'María', 'Rodríguez', 23),
(24, 'Carlos', 'Fernández', 23),
(25, 'Ana', 'López', 23),
(26, 'Luis', 'Martínez', 23),
(27, 'Sofía', 'Pérez', 23),
(28, 'Pedro', 'Sánchez', 23),
(29, 'Laura', 'Ramírez', 23),
(30, 'Andrés', 'Torres', 23),
(31, 'Elena', 'Díaz', 23),
(32, 'Diego', 'Moreno', 23),
(33, 'Valeria', 'Vargas', 23),
(34, 'Javier', 'Jiménez', 23),
(35, 'Camila', 'Ruiz', 23),
(36, 'Fernando', 'Gutiérrez', 23),
(37, 'Isabel', 'Hernández', 23),
(38, 'Ricardo', 'Castro', 23),
(39, 'Patricia', 'Ortega', 23),
(40, 'Manuel', 'Suárez', 23),
(41, 'Gabriela', 'Mendoza', 23),
(42, 'José', 'Romero', 23),
(43, 'Daniela', 'Delgado', 23),
(44, 'Roberto', 'Rojas', 23),
(45, 'Lucía', 'Guerrero', 23),
(46, 'Antonio', 'Acosta', 23),
(47, 'Beatriz', 'Navarro', 23),
(48, 'Miguel', 'Molina', 23);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencia`
--

CREATE TABLE `asistencia` (
  `idasistencia` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `estado` tinyint(4) NOT NULL,
  `qrgenerados_id` int(11) NOT NULL,
  `docente_id` int(11) NOT NULL,
  `alumno_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `asistencia`
--

INSERT INTO `asistencia` (`idasistencia`, `fecha`, `estado`, `qrgenerados_id`, `docente_id`, `alumno_id`) VALUES
(28, '2025-04-07', 1, 2, 8, 22),
(29, '2025-04-07', 1, 2, 8, 23),
(30, '2025-04-07', 1, 2, 8, 24),
(31, '2025-04-07', 1, 2, 8, 25),
(32, '2025-04-07', 1, 2, 8, 26),
(33, '2025-04-07', 1, 2, 8, 27),
(34, '2025-04-07', 1, 2, 8, 28),
(35, '2025-04-07', 1, 2, 8, 29),
(36, '2025-04-07', 0, 2, 8, 30),
(37, '2025-04-07', 1, 2, 8, 31),
(38, '2025-04-07', 1, 2, 8, 32),
(39, '2025-04-07', 1, 2, 8, 33),
(40, '2025-04-07', 1, 2, 8, 34),
(41, '2025-04-07', 1, 2, 8, 35),
(42, '2025-04-07', 1, 2, 8, 36),
(43, '2025-04-07', 1, 2, 8, 37),
(44, '2025-04-07', 1, 2, 8, 38),
(45, '2025-04-07', 1, 2, 8, 39),
(46, '2025-04-07', 1, 2, 8, 40),
(47, '2025-04-07', 1, 2, 8, 41),
(48, '2025-04-07', 0, 2, 8, 42),
(49, '2025-04-07', 1, 2, 8, 43),
(50, '2025-04-07', 1, 2, 8, 44),
(51, '2025-04-07', 1, 2, 8, 45),
(52, '2025-04-07', 1, 2, 8, 46),
(53, '2025-04-07', 1, 2, 8, 47),
(54, '2025-04-07', 1, 2, 8, 48);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `credenciales`
--

CREATE TABLE `credenciales` (
  `idcredenciales` int(11) NOT NULL,
  `user` varchar(20) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `fecharegistro` datetime NOT NULL,
  `ultimoacceso` datetime NOT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `credenciales`
--

INSERT INTO `credenciales` (`idcredenciales`, `user`, `contrasena`, `fecharegistro`, `ultimoacceso`, `estado`) VALUES
(21, 'Jose', '$2y$10$Cw2ko3.jIBxGp342onHTGOqYopqbPeeDE.zwKHK0UXrEED5RTncse', '2024-09-12 22:48:25', '2025-04-07 00:01:45', 1),
(103, 'Marcel', '$2y$10$SCnYIXoq5jE2grtravqWD.wmdnYvlvcvfcyMij4mpvZHESfchjJCm', '2024-09-12 23:53:50', '2025-04-07 18:17:43', 1),
(104, 'Mayerli', '$2y$10$1LwHEN2VjETknHXqR4WAA.w9rXF/mVU4QNAzmbEVdNgBjSkqlcPF2', '2024-09-12 23:55:44', '2024-09-16 11:35:54', 1),
(106, 'Jessica', '$2y$10$ir7GaQoY2SMSzQyDr6Yqm..szZ6aCEqET0k5oV2caD.6eS1bC5ate', '2024-09-12 23:57:53', '2025-04-07 22:56:34', 1),
(111, 'Santiago', '$2y$10$KrjzKf52MwdsXSWwiMfvHONcvGTlVbNSXV.092jCjBg2RrnbHbSCa', '2024-09-13 00:19:28', '2025-03-27 19:27:33', 1),
(115, 'Cristian', '$2y$10$DyUrfrA5/zdsiFuZkgBmFu5U1Po22N47x1L.r2AypvcFHhSt.MW5S', '2024-09-13 00:25:58', '0000-00-00 00:00:00', 1),
(118, 'JuanK', '$2y$10$c1r99pokB/u0t.dP9leX/O4IZb85C1AHyRquz1bAP9XANw4cLSdVW', '2024-09-16 10:41:52', '0000-00-00 00:00:00', 1),
(119, 'JuanK', '$2y$10$N7RcvsgBOn5jkFabcXllkOcvQ6JUsDdNri8/hYwk/xtCUeNJnIig2', '2024-09-16 10:43:19', '0000-00-00 00:00:00', 1),
(120, 'Ugly', '$2y$10$yN7FbVRO1uSZxENjzLG.Eek90bJqhCwrz8gt5PevKgqa8RFNAoZDi', '2024-09-18 13:09:40', '0000-00-00 00:00:00', 1),
(121, 'Mariana', '$2y$10$POHFcCsKHG1pQRR3T7mYb.BOUrUvtjmT3oXje8lkRycFl9EH/2cMW', '2025-03-27 19:28:43', '2025-04-07 23:18:54', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `idcursos` int(11) NOT NULL,
  `nombrecurso` varchar(255) NOT NULL,
  `docente_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`idcursos`, `nombrecurso`, `docente_id`) VALUES
(23, 'Curso 1004', 8),
(24, 'Curso 1005', 8),
(26, 'Curso 1101', 8),
(31, 'Curso 1104', 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docente`
--

CREATE TABLE `docente` (
  `iddocente` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `docente`
--

INSERT INTO `docente` (`iddocente`, `usuario_id`) VALUES
(7, 25),
(8, 107),
(9, 108),
(11, 119),
(12, 124);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estadisticasqr`
--

CREATE TABLE `estadisticasqr` (
  `idestadisticasqr` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `estudiantes_q_asistieron` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `estadisticasqr`
--

INSERT INTO `estadisticasqr` (`idestadisticasqr`, `fecha`, `estudiantes_q_asistieron`) VALUES
(1, '2024-08-26', 45),
(2, '2024-08-27', 45),
(3, '2024-08-28', 30),
(4, '2024-08-29', 55),
(5, '2024-08-30', 40),
(7, '2024-08-19', 15),
(8, '2024-08-20', 100),
(9, '2024-08-21', 59),
(10, '2024-08-22', 78),
(11, '2024-08-23', 92),
(12, '2024-08-12', 135),
(13, '2024-08-13', 64),
(14, '2024-08-14', 100),
(15, '2024-08-15', 120),
(16, '2024-08-16', 165),
(17, '2024-08-05', 135),
(18, '2024-08-06', 165),
(19, '2024-08-07', 200),
(20, '2024-08-08', 445),
(21, '2024-08-09', 1),
(22, '2024-06-24', 168),
(23, '2024-06-24', 1),
(24, '2024-06-24', 10),
(25, '2024-06-24', 2),
(26, '2024-06-24', 3),
(27, '2024-09-16', 1),
(28, '2024-09-16', 1),
(29, '2024-09-16', 1),
(30, '2024-09-16', 3),
(31, '2024-09-16', 5),
(32, '2024-09-18', 2),
(34, '2025-04-07', 25);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiante_ss`
--

CREATE TABLE `estudiante_ss` (
  `idestudiante_ss` int(11) NOT NULL,
  `qr_registrados` text NOT NULL,
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `estudiante_ss`
--

INSERT INTO `estudiante_ss` (`idestudiante_ss`, `qr_registrados`, `usuario_id`) VALUES
(4, '7', 110),
(5, '', 115);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu`
--

CREATE TABLE `menu` (
  `idmenu` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `tipomenu` varchar(20) NOT NULL,
  `descripcion` longtext NOT NULL,
  `admin_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `menu`
--

INSERT INTO `menu` (`idmenu`, `fecha`, `tipomenu`, `descripcion`, `admin_id`) VALUES
(2, '2024-08-27', 'Almuerzo', 'pollo', 1),
(3, '2024-08-27', 'Refrigerio', 'avena', 1),
(4, '2024-08-28', 'Desayuno', 'huevos, arroz', 1),
(20, '2024-08-30', 'desayuno', 'nada', 1),
(21, '2024-09-12', 'desayuno', 'pollo', 1),
(22, '2024-09-28', 'desayuno', 'Carne', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `qrescaneados`
--

CREATE TABLE `qrescaneados` (
  `idqrescaneados` int(11) NOT NULL,
  `fecha_escaneo` datetime NOT NULL,
  `estudiante_ss_id` int(11) NOT NULL,
  `qr_code` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `qrescaneados`
--

INSERT INTO `qrescaneados` (`idqrescaneados`, `fecha_escaneo`, `estudiante_ss_id`, `qr_code`) VALUES
(7, '2025-04-07 21:06:18', 4, 'Curso: Curso 1004\nEstudiantes presentes: 25\nFecha: 2025-04-07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `qrgenerados`
--

CREATE TABLE `qrgenerados` (
  `idqrgenerados` int(11) NOT NULL,
  `codigoqr` varchar(255) NOT NULL,
  `fechageneracion` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `qrgenerados`
--

INSERT INTO `qrgenerados` (`idqrgenerados`, `codigoqr`, `fechageneracion`) VALUES
(2, 'qr_asistencia_1744007085.png', '2025-04-07 08:24:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_documento`
--

CREATE TABLE `tipo_documento` (
  `tdoc` varchar(10) NOT NULL,
  `descripcion` varchar(30) NOT NULL,
  `estadodoc` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `tipo_documento`
--

INSERT INTO `tipo_documento` (`tdoc`, `descripcion`, `estadodoc`) VALUES
('CC', 'Cédula de Ciudadanía', 1),
('CE', 'Cédula de Extranjería', 1),
('PA', 'Pasaporte', 1),
('PR', 'Permiso de Residencia', 1),
('RC', 'Registro Civil', 1),
('TI', 'Tarjeta de Identidad', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_usuario`
--

CREATE TABLE `tipo_usuario` (
  `idtipo_usuario` int(11) NOT NULL,
  `rol` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `tipo_usuario`
--

INSERT INTO `tipo_usuario` (`idtipo_usuario`, `rol`) VALUES
(1, 'Estudiante SS'),
(2, 'Docente'),
(3, 'Administrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `idusuarios` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `apellido` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `numerodocumento` int(10) NOT NULL,
  `tipo_documento` varchar(10) NOT NULL,
  `tipo_usuario` int(11) NOT NULL,
  `credenciales` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idusuarios`, `nombre`, `apellido`, `email`, `telefono`, `direccion`, `numerodocumento`, `tipo_documento`, `tipo_usuario`, `credenciales`) VALUES
(25, 'Jose', 'Ramirez', 'filomif403@ploncy.com', '3156669875', 'Bosa', 46983579, 'CC', 2, 21),
(107, 'Marcel', 'Mazuera', 'mifam61376@obisims.com', '3556699875', 'Bosa', 89765433, 'CC', 2, 103),
(108, 'Mayerli', 'Torrejano', 'joneco9174@ploncy.com', '3165554488', 'Bosa', 46879564, 'CC', 2, 104),
(110, 'Jessica', 'Martinez', 'bibipad850@konetas.com', '3114568974', 'Bosa', 1022549877, 'TI', 1, 106),
(115, 'Kevin Santiago', 'Garcia', 'kevingarciago3@gmail.com', '3163237616', 'calle 56f sur #92a-29', 1032940369, 'TI', 3, 111),
(119, 'Cristian', 'Garcia', 'cristiangarciago3@gmail.com', '3112355239', 'calle 56f sur #92a-29', 1032937602, 'CC', 3, 115),
(123, 'Juan', 'Cardenas', 'juankarloscardenasr2@gmail.com', '3011546899', 'Bosa', 1020736727, 'TI', 3, 119),
(124, 'ujy', 'Pineda', 'juankarloscardenasr@gmail.com', '3555464894', 'Bosa', 1054986354, 'TI', 2, 120),
(125, 'Mariana', 'Jiménez Villa', 'marianajimenezv2006@gmail.com', '3133958194', 'Cra 114 #148-65', 1013261783, 'CC', 3, 121);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`idadmin`),
  ADD KEY `fk_admin_usuario_idx` (`usuario_id`);

--
-- Indices de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD PRIMARY KEY (`idalumno`),
  ADD KEY `fk_alumnos_curso_idx` (`curso_id`);

--
-- Indices de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD PRIMARY KEY (`idasistencia`),
  ADD KEY `fk_asistencia_qrgenerados_idx` (`qrgenerados_id`),
  ADD KEY `fk_asistencia_alumnos_idx` (`alumno_id`),
  ADD KEY `fk_asistencia_docente_idx` (`docente_id`);

--
-- Indices de la tabla `credenciales`
--
ALTER TABLE `credenciales`
  ADD PRIMARY KEY (`idcredenciales`);

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`idcursos`),
  ADD KEY `fk_cursos_docente_idx` (`docente_id`);

--
-- Indices de la tabla `docente`
--
ALTER TABLE `docente`
  ADD PRIMARY KEY (`iddocente`),
  ADD KEY `fk_docente_usuario_idx` (`usuario_id`);

--
-- Indices de la tabla `estadisticasqr`
--
ALTER TABLE `estadisticasqr`
  ADD PRIMARY KEY (`idestadisticasqr`);

--
-- Indices de la tabla `estudiante_ss`
--
ALTER TABLE `estudiante_ss`
  ADD PRIMARY KEY (`idestudiante_ss`),
  ADD KEY `fk_estudiante_ss_usuario_idx` (`usuario_id`);

--
-- Indices de la tabla `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`idmenu`),
  ADD KEY `fk_menu_admin_idx` (`admin_id`);

--
-- Indices de la tabla `qrescaneados`
--
ALTER TABLE `qrescaneados`
  ADD PRIMARY KEY (`idqrescaneados`),
  ADD KEY `fk_qrescaneados_estudiante_ss_idx` (`estudiante_ss_id`);

--
-- Indices de la tabla `qrgenerados`
--
ALTER TABLE `qrgenerados`
  ADD PRIMARY KEY (`idqrgenerados`),
  ADD UNIQUE KEY `codigoqr_UNIQUE` (`codigoqr`);

--
-- Indices de la tabla `tipo_documento`
--
ALTER TABLE `tipo_documento`
  ADD PRIMARY KEY (`tdoc`);

--
-- Indices de la tabla `tipo_usuario`
--
ALTER TABLE `tipo_usuario`
  ADD PRIMARY KEY (`idtipo_usuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idusuarios`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`),
  ADD UNIQUE KEY `numerodocumento_UNIQUE` (`numerodocumento`),
  ADD KEY `fk_usuarios_tipo_documento_idx` (`tipo_documento`),
  ADD KEY `fk_usuarios_tipo_usuario_idx` (`tipo_usuario`),
  ADD KEY `fk_usuarios_credenciales_idx` (`credenciales`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `admin`
--
ALTER TABLE `admin`
  MODIFY `idadmin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  MODIFY `idalumno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  MODIFY `idasistencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT de la tabla `credenciales`
--
ALTER TABLE `credenciales`
  MODIFY `idcredenciales` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `idcursos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `docente`
--
ALTER TABLE `docente`
  MODIFY `iddocente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `estadisticasqr`
--
ALTER TABLE `estadisticasqr`
  MODIFY `idestadisticasqr` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `estudiante_ss`
--
ALTER TABLE `estudiante_ss`
  MODIFY `idestudiante_ss` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `menu`
--
ALTER TABLE `menu`
  MODIFY `idmenu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `qrescaneados`
--
ALTER TABLE `qrescaneados`
  MODIFY `idqrescaneados` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `qrgenerados`
--
ALTER TABLE `qrgenerados`
  MODIFY `idqrgenerados` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tipo_usuario`
--
ALTER TABLE `tipo_usuario`
  MODIFY `idtipo_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idusuarios` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `fk_admin_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`idusuarios`) ON DELETE CASCADE;

--
-- Filtros para la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD CONSTRAINT `fk_alumnos_curso` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`idcursos`) ON DELETE CASCADE;

--
-- Filtros para la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD CONSTRAINT `fk_asistencia_alumno` FOREIGN KEY (`alumno_id`) REFERENCES `alumnos` (`idalumno`),
  ADD CONSTRAINT `fk_asistencia_docente` FOREIGN KEY (`docente_id`) REFERENCES `docente` (`iddocente`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_asistencia_qrgenerados` FOREIGN KEY (`qrgenerados_id`) REFERENCES `qrgenerados` (`idqrgenerados`) ON DELETE CASCADE;

--
-- Filtros para la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD CONSTRAINT `fk_cursos_docente` FOREIGN KEY (`docente_id`) REFERENCES `docente` (`iddocente`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `docente`
--
ALTER TABLE `docente`
  ADD CONSTRAINT `fk_docente_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`idusuarios`) ON DELETE CASCADE;

--
-- Filtros para la tabla `estudiante_ss`
--
ALTER TABLE `estudiante_ss`
  ADD CONSTRAINT `fk_estudiante_ss_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`idusuarios`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `fk_menu_admin` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`idadmin`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `qrescaneados`
--
ALTER TABLE `qrescaneados`
  ADD CONSTRAINT `fk_qrescaneados_estudiante_ss` FOREIGN KEY (`estudiante_ss_id`) REFERENCES `estudiante_ss` (`idestudiante_ss`) ON DELETE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_credenciales` FOREIGN KEY (`credenciales`) REFERENCES `credenciales` (`idcredenciales`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_usuarios_tipo_documento` FOREIGN KEY (`tipo_documento`) REFERENCES `tipo_documento` (`tdoc`),
  ADD CONSTRAINT `fk_usuarios_tipo_usuario` FOREIGN KEY (`tipo_usuario`) REFERENCES `tipo_usuario` (`idtipo_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
