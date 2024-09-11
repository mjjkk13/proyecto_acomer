-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-09-2024 a las 23:22:10
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
  `usuarios_idusuarios` int(11) NOT NULL,
  `usuarios_tipo_documento_tdoc` varchar(10) NOT NULL,
  `usuarios_tipo_usuario_idtipo_usuario` int(11) NOT NULL,
  `usuarios_credenciales_idcredenciales` int(11) NOT NULL,
  `estadisticasqr_idestadisticasqr` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `admin`
--

INSERT INTO `admin` (`idadmin`, `usuarios_idusuarios`, `usuarios_tipo_documento_tdoc`, `usuarios_tipo_usuario_idtipo_usuario`, `usuarios_credenciales_idcredenciales`, `estadisticasqr_idestadisticasqr`) VALUES
(1, 1, 'CC', 2, 1, 1),
(2, 2, 'TI', 1, 2, 2),
(3, 3, 'CC', 3, 3, 3),
(4, 4, 'CC', 1, 4, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumnos`
--

CREATE TABLE `alumnos` (
  `idalumnos` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `apellido` varchar(20) NOT NULL,
  `docentealumnos_iddocentealumnos` int(11) NOT NULL,
  `cursos_idcursos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencia`
--

CREATE TABLE `asistencia` (
  `idasistencia` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `estado` tinyint(4) NOT NULL,
  `registradopor` int(11) NOT NULL,
  `qrgenerados_idqrgenerados` int(11) NOT NULL,
  `docente_iddocente` int(11) DEFAULT NULL,
  `alumnos_idalumnos` int(11) NOT NULL,
  `alumnos_nombre` varchar(20) NOT NULL,
  `alumnos_apellido` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `asistencia_completa`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `asistencia_completa` (
`idasistencia` int(11)
,`alumnos_idalumnos` int(11)
,`alumnos_nombre` varchar(20)
,`alumnos_apellido` varchar(20)
,`fecha` date
,`estado` tinyint(4)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `credenciales`
--

CREATE TABLE `credenciales` (
  `idcredenciales` int(11) NOT NULL,
  `user` varchar(20) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `fecharegistro` datetime NOT NULL,
  `ultimoacceso` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `credenciales`
--

INSERT INTO `credenciales` (`idcredenciales`, `user`, `contrasena`, `fecharegistro`, `ultimoacceso`) VALUES
(5, 'Santiago', '$2y$10$DKxYBi6VcKwbZ0.npqNHbOsempwC8Hl6J', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 'mariana', '$2y$10$I1CVzxXQAYbHlxBACf2CwuDMLxIm/vExK', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `idcursos` int(11) NOT NULL,
  `nombrecurso` varchar(20) NOT NULL,
  `docente_iddocente` int(11) NOT NULL,
  `docente_usuarios_idusuarios` int(11) NOT NULL,
  `docente_usuarios_tipo_documento_tdoc` varchar(10) NOT NULL,
  `docente_usuarios_tipo_usuario_idtipo_usuario` int(11) NOT NULL,
  `docente_usuarios_credenciales_idcredenciales` int(11) NOT NULL,
  `asistencia_idasistencia` int(11) NOT NULL,
  `qrgenerados_idqrgenerados` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docente`
--

CREATE TABLE `docente` (
  `iddocente` int(11) NOT NULL,
  `usuarios_idusuarios` int(11) NOT NULL,
  `usuarios_tipo_documento_tdoc` varchar(10) NOT NULL,
  `usuarios_tipo_usuario_idtipo_usuario` int(11) NOT NULL,
  `usuarios_credenciales_idcredenciales` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `docente`
--

INSERT INTO `docente` (`iddocente`, `usuarios_idusuarios`, `usuarios_tipo_documento_tdoc`, `usuarios_tipo_usuario_idtipo_usuario`, `usuarios_credenciales_idcredenciales`) VALUES
(5, 10, 'CC', 2, 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docentealumnos`
--

CREATE TABLE `docentealumnos` (
  `iddocentealumnos` int(11) NOT NULL,
  `docente_iddocente` int(11) NOT NULL,
  `docente_usuarios_idusuarios` int(11) NOT NULL,
  `docente_usuarios_tipo_documento_tdoc` varchar(10) NOT NULL,
  `docente_usuarios_tipo_usuario_idtipo_usuario` int(11) NOT NULL,
  `docente_usuarios_credenciales_idcredenciales` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `docentealumnos`
--

INSERT INTO `docentealumnos` (`iddocentealumnos`, `docente_iddocente`, `docente_usuarios_idusuarios`, `docente_usuarios_tipo_documento_tdoc`, `docente_usuarios_tipo_usuario_idtipo_usuario`, `docente_usuarios_credenciales_idcredenciales`) VALUES
(1, 1, 1, 'CC', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estadisticasqr`
--

CREATE TABLE `estadisticasqr` (
  `idestadisticasqr` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `estudiantesqasistieron` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `estadisticasqr`
--

INSERT INTO `estadisticasqr` (`idestadisticasqr`, `fecha`, `estudiantesqasistieron`) VALUES
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
(26, '2024-06-24', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiante_ss`
--

CREATE TABLE `estudiante_ss` (
  `idestudiante_ss` int(11) NOT NULL,
  `qr_registrados` text NOT NULL,
  `usuarios_idusuarios` int(11) NOT NULL,
  `usuarios_tipo_documento_tdoc` varchar(10) NOT NULL,
  `usuarios_tipo_usuario_idtipo_usuario` int(11) NOT NULL,
  `usuarios_credenciales_idcredenciales` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu`
--

CREATE TABLE `menu` (
  `idmenu` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `tipomenu` varchar(20) NOT NULL,
  `descripcion` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `menu`
--

INSERT INTO `menu` (`idmenu`, `fecha`, `tipomenu`, `descripcion`) VALUES
(2, '2024-08-27', 'Almuerzo', 'pollo'),
(3, '2024-08-27', 'Refrigerio', 'avena'),
(4, '2024-08-28', 'Desayuno', 'huevos, arroz'),
(20, '2024-08-30', 'desayuno', 'nada'),
(21, '2024-09-05', 'desayuno', 'nada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `qrescaneados`
--

CREATE TABLE `qrescaneados` (
  `idqrescaneados` int(11) NOT NULL,
  `fecha_escaneo` datetime NOT NULL,
  `estudiante_ss_idestudiante_ss` int(11) NOT NULL,
  `estudiante_ss_usuarios_idusuarios` int(11) NOT NULL,
  `estudiante_ss_usuarios_tipo_documento_tdoc` varchar(10) NOT NULL,
  `estudiante_ss_usuarios_tipo_usuario_idtipo_usuario` int(11) NOT NULL,
  `estudiante_ss_usuarios_credenciales_idcredenciales` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
(1, '../qr_codes/qr_all_students_1724893419.png', '2024-08-29 03:03:39'),
(22, '../qr_codes/qr_all_students_1724296178.png', '2024-08-27 10:00:00'),
(23, '../qr_codes/qr_all_students_1724296237.png', '2024-08-27 11:30:00'),
(24, '../qr_codes/qr_all_students_1724296591.png', '2024-08-27 13:15:00');

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
  `tipo_documento_tdoc` varchar(10) NOT NULL,
  `tipo_usuario_idtipo_usuario` int(11) NOT NULL,
  `credenciales_idcredenciales` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idusuarios`, `nombre`, `apellido`, `email`, `telefono`, `direccion`, `numerodocumento`, `tipo_documento_tdoc`, `tipo_usuario_idtipo_usuario`, `credenciales_idcredenciales`) VALUES
(9, 'Kevin Santiago', 'Garcia Gomez', 'kevingarciago3@gmail.com', '3163237616', 'calle 56f sur #92a-29', 1032940369, 'TI', 3, 5),
(10, 'Mayerli', 'Torrejano', 'mtorrejano@gmail.com', '3005556666', 'Bosa', 45987621, 'CC', 2, 6);

-- --------------------------------------------------------

--
-- Estructura para la vista `asistencia_completa`
--
DROP TABLE IF EXISTS `asistencia_completa`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `asistencia_completa`  AS SELECT `a`.`idasistencia` AS `idasistencia`, `a`.`alumnos_idalumnos` AS `alumnos_idalumnos`, `al`.`nombre` AS `alumnos_nombre`, `al`.`apellido` AS `alumnos_apellido`, `a`.`fecha` AS `fecha`, `a`.`estado` AS `estado` FROM (`asistencia` `a` join `alumnos` `al` on(`a`.`alumnos_idalumnos` = `al`.`idalumnos`)) ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`idadmin`,`usuarios_idusuarios`,`usuarios_tipo_documento_tdoc`,`usuarios_tipo_usuario_idtipo_usuario`,`usuarios_credenciales_idcredenciales`),
  ADD KEY `fk_admin_usuarios1_idx` (`usuarios_idusuarios`,`usuarios_tipo_documento_tdoc`,`usuarios_tipo_usuario_idtipo_usuario`,`usuarios_credenciales_idcredenciales`),
  ADD KEY `fk_admin_estadisticasqr1_idx` (`estadisticasqr_idestadisticasqr`);

--
-- Indices de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD PRIMARY KEY (`idalumnos`),
  ADD KEY `fk_alumnos_docentealumnos1_idx` (`docentealumnos_iddocentealumnos`),
  ADD KEY `fk_alumnos_cursos1_idx` (`cursos_idcursos`);

--
-- Indices de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD PRIMARY KEY (`idasistencia`),
  ADD KEY `fk_asistencia_qrgenerados1_idx` (`qrgenerados_idqrgenerados`),
  ADD KEY `fk_asistencia_alumnos` (`alumnos_idalumnos`);

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
  ADD KEY `fk_cursos_docente1_idx` (`docente_iddocente`,`docente_usuarios_idusuarios`,`docente_usuarios_tipo_documento_tdoc`,`docente_usuarios_tipo_usuario_idtipo_usuario`,`docente_usuarios_credenciales_idcredenciales`),
  ADD KEY `fk_cursos_asistencia1_idx` (`asistencia_idasistencia`),
  ADD KEY `fk_cursos_qrgenerados1_idx` (`qrgenerados_idqrgenerados`);

--
-- Indices de la tabla `docente`
--
ALTER TABLE `docente`
  ADD PRIMARY KEY (`iddocente`,`usuarios_idusuarios`,`usuarios_tipo_documento_tdoc`,`usuarios_tipo_usuario_idtipo_usuario`,`usuarios_credenciales_idcredenciales`),
  ADD KEY `fk_docente_usuarios1_idx` (`usuarios_idusuarios`,`usuarios_tipo_documento_tdoc`,`usuarios_tipo_usuario_idtipo_usuario`,`usuarios_credenciales_idcredenciales`);

--
-- Indices de la tabla `docentealumnos`
--
ALTER TABLE `docentealumnos`
  ADD PRIMARY KEY (`iddocentealumnos`),
  ADD KEY `fk_docentealumnos_docente1_idx` (`docente_iddocente`,`docente_usuarios_idusuarios`,`docente_usuarios_tipo_documento_tdoc`,`docente_usuarios_tipo_usuario_idtipo_usuario`,`docente_usuarios_credenciales_idcredenciales`);

--
-- Indices de la tabla `estadisticasqr`
--
ALTER TABLE `estadisticasqr`
  ADD PRIMARY KEY (`idestadisticasqr`);

--
-- Indices de la tabla `estudiante_ss`
--
ALTER TABLE `estudiante_ss`
  ADD PRIMARY KEY (`idestudiante_ss`,`usuarios_idusuarios`,`usuarios_tipo_documento_tdoc`,`usuarios_tipo_usuario_idtipo_usuario`,`usuarios_credenciales_idcredenciales`),
  ADD KEY `fk_estudiante_ss_usuarios1_idx` (`usuarios_idusuarios`,`usuarios_tipo_documento_tdoc`,`usuarios_tipo_usuario_idtipo_usuario`,`usuarios_credenciales_idcredenciales`);

--
-- Indices de la tabla `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`idmenu`);

--
-- Indices de la tabla `qrescaneados`
--
ALTER TABLE `qrescaneados`
  ADD PRIMARY KEY (`idqrescaneados`),
  ADD KEY `fk_qrescaneados_estudiante_ss1_idx` (`estudiante_ss_idestudiante_ss`,`estudiante_ss_usuarios_idusuarios`,`estudiante_ss_usuarios_tipo_documento_tdoc`,`estudiante_ss_usuarios_tipo_usuario_idtipo_usuario`,`estudiante_ss_usuarios_credenciales_idcredenciales`);

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
  ADD PRIMARY KEY (`idusuarios`,`tipo_documento_tdoc`,`tipo_usuario_idtipo_usuario`,`credenciales_idcredenciales`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`),
  ADD UNIQUE KEY `numerodocumento_UNIQUE` (`numerodocumento`),
  ADD KEY `fk_usuarios_tipo_documento_idx` (`tipo_documento_tdoc`),
  ADD KEY `fk_usuarios_tipo_usuario1_idx` (`tipo_usuario_idtipo_usuario`),
  ADD KEY `fk_usuarios_credenciales1_idx` (`credenciales_idcredenciales`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `admin`
--
ALTER TABLE `admin`
  MODIFY `idadmin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  MODIFY `idalumnos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  MODIFY `idasistencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `credenciales`
--
ALTER TABLE `credenciales`
  MODIFY `idcredenciales` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `idcursos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `docente`
--
ALTER TABLE `docente`
  MODIFY `iddocente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `docentealumnos`
--
ALTER TABLE `docentealumnos`
  MODIFY `iddocentealumnos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `estadisticasqr`
--
ALTER TABLE `estadisticasqr`
  MODIFY `idestadisticasqr` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `estudiante_ss`
--
ALTER TABLE `estudiante_ss`
  MODIFY `idestudiante_ss` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `menu`
--
ALTER TABLE `menu`
  MODIFY `idmenu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `qrescaneados`
--
ALTER TABLE `qrescaneados`
  MODIFY `idqrescaneados` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `qrgenerados`
--
ALTER TABLE `qrgenerados`
  MODIFY `idqrgenerados` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idusuarios` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD CONSTRAINT `fk_alumnos_cursos1` FOREIGN KEY (`cursos_idcursos`) REFERENCES `cursos` (`idcursos`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_alumnos_docentealumnos1` FOREIGN KEY (`docentealumnos_iddocentealumnos`) REFERENCES `docentealumnos` (`iddocentealumnos`);

--
-- Filtros para la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD CONSTRAINT `fk_asistencia_alumnos` FOREIGN KEY (`alumnos_idalumnos`) REFERENCES `alumnos` (`idalumnos`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_asistencia_qrgenerados1` FOREIGN KEY (`qrgenerados_idqrgenerados`) REFERENCES `qrgenerados` (`idqrgenerados`);

--
-- Filtros para la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD CONSTRAINT `fk_cursos_docente1` FOREIGN KEY (`docente_iddocente`,`docente_usuarios_idusuarios`,`docente_usuarios_tipo_documento_tdoc`,`docente_usuarios_tipo_usuario_idtipo_usuario`) REFERENCES `docente` (`iddocente`, `usuarios_idusuarios`, `usuarios_tipo_documento_tdoc`, `usuarios_tipo_usuario_idtipo_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cursos_qrgenerados1` FOREIGN KEY (`qrgenerados_idqrgenerados`) REFERENCES `qrgenerados` (`idqrgenerados`);

--
-- Filtros para la tabla `docente`
--
ALTER TABLE `docente`
  ADD CONSTRAINT `fk_docente_usuarios1` FOREIGN KEY (`usuarios_idusuarios`,`usuarios_tipo_documento_tdoc`,`usuarios_tipo_usuario_idtipo_usuario`,`usuarios_credenciales_idcredenciales`) REFERENCES `usuarios` (`idusuarios`, `tipo_documento_tdoc`, `tipo_usuario_idtipo_usuario`, `credenciales_idcredenciales`) ON DELETE CASCADE;

--
-- Filtros para la tabla `estudiante_ss`
--
ALTER TABLE `estudiante_ss`
  ADD CONSTRAINT `fk_estudiante_ss_usuarios1` FOREIGN KEY (`usuarios_idusuarios`,`usuarios_tipo_documento_tdoc`,`usuarios_tipo_usuario_idtipo_usuario`,`usuarios_credenciales_idcredenciales`) REFERENCES `usuarios` (`idusuarios`, `tipo_documento_tdoc`, `tipo_usuario_idtipo_usuario`, `credenciales_idcredenciales`) ON DELETE CASCADE;

--
-- Filtros para la tabla `qrescaneados`
--
ALTER TABLE `qrescaneados`
  ADD CONSTRAINT `fk_qrescaneados_estudiante_ss1` FOREIGN KEY (`estudiante_ss_idestudiante_ss`,`estudiante_ss_usuarios_idusuarios`,`estudiante_ss_usuarios_tipo_documento_tdoc`,`estudiante_ss_usuarios_tipo_usuario_idtipo_usuario`) REFERENCES `estudiante_ss` (`idestudiante_ss`, `usuarios_idusuarios`, `usuarios_tipo_documento_tdoc`, `usuarios_tipo_usuario_idtipo_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_credenciales1` FOREIGN KEY (`credenciales_idcredenciales`) REFERENCES `credenciales` (`idcredenciales`),
  ADD CONSTRAINT `fk_usuarios_tipo_documento` FOREIGN KEY (`tipo_documento_tdoc`) REFERENCES `tipo_documento` (`tdoc`),
  ADD CONSTRAINT `fk_usuarios_tipo_usuario1` FOREIGN KEY (`tipo_usuario_idtipo_usuario`) REFERENCES `tipo_usuario` (`idtipo_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
