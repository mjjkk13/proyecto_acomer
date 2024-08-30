-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-08-2024 a las 04:09:31
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
  `usuarios_docente_iddocente` int(11) NOT NULL,
  `estadisticasqr_idestadisticasqr` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `admin`
--

INSERT INTO `admin` (`idadmin`, `usuarios_idusuarios`, `usuarios_tipo_documento_tdoc`, `usuarios_tipo_usuario_idtipo_usuario`, `usuarios_credenciales_idcredenciales`, `usuarios_docente_iddocente`, `estadisticasqr_idestadisticasqr`) VALUES
(1, 1, 'CC', 2, 1, 101, 1),
(2, 2, 'TI', 1, 2, 0, 2),
(3, 3, 'CC', 3, 3, 0, 3),
(4, 4, 'CC', 1, 4, 0, 4);

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

--
-- Volcado de datos para la tabla `alumnos`
--

INSERT INTO `alumnos` (`idalumnos`, `nombre`, `apellido`, `docentealumnos_iddocentealumnos`, `cursos_idcursos`) VALUES
(8, 'Juan', 'Pérez', 1, 1),
(9, 'Ana', 'Gómez', 1, 2),
(10, 'Luis', 'Martínez', 1, 3);

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
  `usuarios_docente_iddocente` int(11) NOT NULL,
  `alumnos_idalumnos` int(11) NOT NULL,
  `alumnos_nombre` varchar(20) NOT NULL,
  `alumnos_apellido` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `asistencia`
--

INSERT INTO `asistencia` (`idasistencia`, `fecha`, `estado`, `registradopor`, `qrgenerados_idqrgenerados`, `usuarios_docente_iddocente`, `alumnos_idalumnos`, `alumnos_nombre`, `alumnos_apellido`) VALUES
(1, '2024-08-27', 1, 101, 22, 101, 8, 'Juan', 'Pérez'),
(2, '2024-08-27', 0, 102, 23, 0, 9, 'Ana', 'Gómez'),
(3, '2024-08-27', 1, 103, 24, 0, 10, 'Luis', 'Martínez');

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
  `password` varchar(40) NOT NULL,
  `fecharegistro` datetime NOT NULL,
  `ultimoacceso` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `credenciales`
--

INSERT INTO `credenciales` (`idcredenciales`, `user`, `password`, `fecharegistro`, `ultimoacceso`) VALUES
(1, 'juanpablo', 'password123', '2024-08-27 10:00:00', '2024-08-27 15:00:00'),
(2, 'claudia', 'password456', '2024-08-27 11:00:00', '2024-08-27 16:00:00'),
(3, 'sandra', 'password789', '2024-08-27 12:00:00', '2024-08-27 17:00:00'),
(4, 'pedro', 'password321', '2024-08-27 13:00:00', '2024-08-27 18:00:00');

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
  `docente_usuarios_docente_iddocente` int(11) NOT NULL,
  `asistencia_idasistencia` int(11) NOT NULL,
  `qrgenerados_idqrgenerados` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`idcursos`, `nombrecurso`, `docente_iddocente`, `docente_usuarios_idusuarios`, `docente_usuarios_tipo_documento_tdoc`, `docente_usuarios_tipo_usuario_idtipo_usuario`, `docente_usuarios_credenciales_idcredenciales`, `docente_usuarios_docente_iddocente`, `asistencia_idasistencia`, `qrgenerados_idqrgenerados`) VALUES
(1, 'Curso 901', 1, 1, 'CC', 2, 1, 101, 1, 22),
(2, 'Curso 902', 2, 2, 'TI', 1, 2, 0, 2, 23),
(3, 'Curso 1103', 3, 3, 'CC', 1, 3, 0, 3, 24);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docente`
--

CREATE TABLE `docente` (
  `iddocente` int(11) NOT NULL,
  `usuarios_idusuarios` int(11) NOT NULL,
  `usuarios_tipo_documento_tdoc` varchar(10) NOT NULL,
  `usuarios_tipo_usuario_idtipo_usuario` int(11) NOT NULL,
  `usuarios_credenciales_idcredenciales` int(11) NOT NULL,
  `usuarios_docente_iddocente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `docente`
--

INSERT INTO `docente` (`iddocente`, `usuarios_idusuarios`, `usuarios_tipo_documento_tdoc`, `usuarios_tipo_usuario_idtipo_usuario`, `usuarios_credenciales_idcredenciales`, `usuarios_docente_iddocente`) VALUES
(1, 1, 'CC', 2, 1, 101),
(2, 2, 'TI', 1, 2, 0),
(3, 3, 'CC', 1, 3, 0),
(4, 4, 'CC', 3, 4, 0);

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
  `docente_usuarios_credenciales_idcredenciales` int(11) NOT NULL,
  `docente_usuarios_docente_iddocente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `docentealumnos`
--

INSERT INTO `docentealumnos` (`iddocentealumnos`, `docente_iddocente`, `docente_usuarios_idusuarios`, `docente_usuarios_tipo_documento_tdoc`, `docente_usuarios_tipo_usuario_idtipo_usuario`, `docente_usuarios_credenciales_idcredenciales`, `docente_usuarios_docente_iddocente`) VALUES
(1, 1, 1, 'CC', 1, 1, 1);

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
(1, '2024-08-27', 45),
(2, '2024-08-27', 45),
(3, '2024-08-26', 30),
(4, '2024-08-25', 55),
(5, '2024-08-24', 40);

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
  `usuarios_credenciales_idcredenciales` int(11) NOT NULL,
  `usuarios_docente_iddocente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `estudiante_ss`
--

INSERT INTO `estudiante_ss` (`idestudiante_ss`, `qr_registrados`, `usuarios_idusuarios`, `usuarios_tipo_documento_tdoc`, `usuarios_tipo_usuario_idtipo_usuario`, `usuarios_credenciales_idcredenciales`, `usuarios_docente_iddocente`) VALUES
(1, '../qr_codes/qr_all_students_1724296178.png', 1, 'CC', 2, 1, 101),
(2, '../qr_codes/qr_all_students_1724296237.png', 2, 'TI', 1, 2, 0),
(3, '../qr_codes/qr_all_students_1724296591.png', 3, 'CC', 1, 3, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu`
--

CREATE TABLE `menu` (
  `idmenu` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `tipomenu` varchar(20) NOT NULL,
  `descripcion` longtext NOT NULL,
  `usuarios_idusuarios` int(11) NOT NULL,
  `usuarios_tipo_documento_tdoc` varchar(10) NOT NULL,
  `usuarios_tipo_usuario_idtipo_usuario` int(11) NOT NULL,
  `usuarios_credenciales_idcredenciales` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `menu`
--

INSERT INTO `menu` (`idmenu`, `fecha`, `tipomenu`, `descripcion`, `usuarios_idusuarios`, `usuarios_tipo_documento_tdoc`, `usuarios_tipo_usuario_idtipo_usuario`, `usuarios_credenciales_idcredenciales`) VALUES
(1, '2024-08-27', 'Desayuno', 'Huevos revueltos con pan, jugo de naranja y café', 1, 'CC', 2, 1),
(2, '2024-08-27', 'Almuerzo', 'Pasta con salsa boloñesa, ensalada y agua', 2, 'TI', 1, 2),
(3, '2024-08-27', 'Refrigerio', 'Galletas y jugo de manzana', 3, 'CC', 1, 3),
(4, '2024-08-28', 'Desayuno', 'Panqueques con miel, leche y fruta fresca', 4, 'CC', 3, 4);

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
  `estudiante_ss_usuarios_credenciales_idcredenciales` int(11) NOT NULL,
  `estudiante_ss_usuarios_docente_iddocente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `qrescaneados`
--

INSERT INTO `qrescaneados` (`idqrescaneados`, `fecha_escaneo`, `estudiante_ss_idestudiante_ss`, `estudiante_ss_usuarios_idusuarios`, `estudiante_ss_usuarios_tipo_documento_tdoc`, `estudiante_ss_usuarios_tipo_usuario_idtipo_usuario`, `estudiante_ss_usuarios_credenciales_idcredenciales`, `estudiante_ss_usuarios_docente_iddocente`) VALUES
(1, '2024-08-27 08:30:00', 1, 1, 'CC', 2, 1, 101),
(2, '2024-08-27 09:15:00', 2, 2, 'TI', 1, 2, 0),
(3, '2024-08-27 10:00:00', 3, 3, 'CC', 1, 3, 0);

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
  `telefono` BIGINT NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `numerodocumento` int(10) NOT NULL,
  `tipo_documento_tdoc` varchar(10) NOT NULL,
  `tipo_usuario_idtipo_usuario` int(11) NOT NULL,
  `credenciales_idcredenciales` int(11) NOT NULL,
  `docente_iddocente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idusuarios`, `nombre`, `apellido`, `email`, `telefono`, `direccion`, `numerodocumento`, `tipo_documento_tdoc`, `tipo_usuario_idtipo_usuario`, `credenciales_idcredenciales`, `docente_iddocente`) VALUES
(1, 'Juan Pablo', 'Rodriguez', 'juan.pablo@example.com', 2147483647, 'Calle 123', 123456789, 'CC', 2, 1, 101),
(2, 'Claudia', 'Peres', 'claudia.peres@example.com', 2147483647, 'Calle 234', 987654321, 'TI', 1, 2, 0),
(3, 'Sandra', 'Torres', 'sandra.torres@example.com', 2147483647, 'Calle 345', 456789123, 'CC', 1, 3, 0),
(4, 'Pedro', 'Linares', 'pedro.linares@example.com', 2147483647, 'Calle 456', 789123456, 'CC', 3, 4, 0);

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
  ADD PRIMARY KEY (`idadmin`,`usuarios_idusuarios`,`usuarios_tipo_documento_tdoc`,`usuarios_tipo_usuario_idtipo_usuario`,`usuarios_credenciales_idcredenciales`,`usuarios_docente_iddocente`),
  ADD KEY `fk_admin_usuarios1_idx` (`usuarios_idusuarios`,`usuarios_tipo_documento_tdoc`,`usuarios_tipo_usuario_idtipo_usuario`,`usuarios_credenciales_idcredenciales`,`usuarios_docente_iddocente`),
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
  ADD KEY `fk_asistencia_usuarios1_idx` (`usuarios_docente_iddocente`),
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
  ADD KEY `fk_cursos_docente1_idx` (`docente_iddocente`,`docente_usuarios_idusuarios`,`docente_usuarios_tipo_documento_tdoc`,`docente_usuarios_tipo_usuario_idtipo_usuario`,`docente_usuarios_credenciales_idcredenciales`,`docente_usuarios_docente_iddocente`),
  ADD KEY `fk_cursos_asistencia1_idx` (`asistencia_idasistencia`),
  ADD KEY `fk_cursos_qrgenerados1_idx` (`qrgenerados_idqrgenerados`);

--
-- Indices de la tabla `docente`
--
ALTER TABLE `docente`
  ADD PRIMARY KEY (`iddocente`,`usuarios_idusuarios`,`usuarios_tipo_documento_tdoc`,`usuarios_tipo_usuario_idtipo_usuario`,`usuarios_credenciales_idcredenciales`,`usuarios_docente_iddocente`),
  ADD KEY `fk_docente_usuarios1_idx` (`usuarios_idusuarios`,`usuarios_tipo_documento_tdoc`,`usuarios_tipo_usuario_idtipo_usuario`,`usuarios_credenciales_idcredenciales`,`usuarios_docente_iddocente`);

--
-- Indices de la tabla `docentealumnos`
--
ALTER TABLE `docentealumnos`
  ADD PRIMARY KEY (`iddocentealumnos`),
  ADD KEY `fk_docentealumnos_docente1_idx` (`docente_iddocente`,`docente_usuarios_idusuarios`,`docente_usuarios_tipo_documento_tdoc`,`docente_usuarios_tipo_usuario_idtipo_usuario`,`docente_usuarios_credenciales_idcredenciales`,`docente_usuarios_docente_iddocente`);

--
-- Indices de la tabla `estadisticasqr`
--
ALTER TABLE `estadisticasqr`
  ADD PRIMARY KEY (`idestadisticasqr`);

--
-- Indices de la tabla `estudiante_ss`
--
ALTER TABLE `estudiante_ss`
  ADD PRIMARY KEY (`idestudiante_ss`,`usuarios_idusuarios`,`usuarios_tipo_documento_tdoc`,`usuarios_tipo_usuario_idtipo_usuario`,`usuarios_credenciales_idcredenciales`,`usuarios_docente_iddocente`),
  ADD KEY `fk_estudiante_ss_usuarios1_idx` (`usuarios_idusuarios`,`usuarios_tipo_documento_tdoc`,`usuarios_tipo_usuario_idtipo_usuario`,`usuarios_credenciales_idcredenciales`,`usuarios_docente_iddocente`);

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
  ADD KEY `fk_qrescaneados_estudiante_ss1_idx` (`estudiante_ss_idestudiante_ss`,`estudiante_ss_usuarios_idusuarios`,`estudiante_ss_usuarios_tipo_documento_tdoc`,`estudiante_ss_usuarios_tipo_usuario_idtipo_usuario`,`estudiante_ss_usuarios_credenciales_idcredenciales`,`estudiante_ss_usuarios_docente_iddocente`);

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
  ADD PRIMARY KEY (`idusuarios`,`tipo_documento_tdoc`,`tipo_usuario_idtipo_usuario`,`credenciales_idcredenciales`,`docente_iddocente`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`),
  ADD UNIQUE KEY `numerodocumento_UNIQUE` (`numerodocumento`),
  ADD KEY `fk_usuarios_tipo_documento_idx` (`tipo_documento_tdoc`),
  ADD KEY `fk_usuarios_tipo_usuario1_idx` (`tipo_usuario_idtipo_usuario`),
  ADD KEY `fk_usuarios_credenciales1_idx` (`credenciales_idcredenciales`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  MODIFY `idalumnos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `estadisticasqr`
--
ALTER TABLE `estadisticasqr`
  MODIFY `idestadisticasqr` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `qrescaneados`
--
ALTER TABLE `qrescaneados`
  MODIFY `idqrescaneados` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD CONSTRAINT `fk_alumnos_cursos1` FOREIGN KEY (`cursos_idcursos`) REFERENCES `cursos` (`idcursos`),
  ADD CONSTRAINT `fk_alumnos_docentealumnos1` FOREIGN KEY (`docentealumnos_iddocentealumnos`) REFERENCES `docentealumnos` (`iddocentealumnos`);

--
-- Filtros para la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD CONSTRAINT `fk_asistencia_alumnos` FOREIGN KEY (`alumnos_idalumnos`) REFERENCES `alumnos` (`idalumnos`),
  ADD CONSTRAINT `fk_asistencia_qrgenerados1` FOREIGN KEY (`qrgenerados_idqrgenerados`) REFERENCES `qrgenerados` (`idqrgenerados`);

--
-- Filtros para la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD CONSTRAINT `fk_cursos_asistencia1` FOREIGN KEY (`asistencia_idasistencia`) REFERENCES `asistencia` (`idasistencia`),
  ADD CONSTRAINT `fk_cursos_docente1` FOREIGN KEY (`docente_iddocente`,`docente_usuarios_idusuarios`,`docente_usuarios_tipo_documento_tdoc`,`docente_usuarios_tipo_usuario_idtipo_usuario`,`docente_usuarios_credenciales_idcredenciales`,`docente_usuarios_docente_iddocente`) REFERENCES `docente` (`iddocente`, `usuarios_idusuarios`, `usuarios_tipo_documento_tdoc`, `usuarios_tipo_usuario_idtipo_usuario`, `usuarios_credenciales_idcredenciales`, `usuarios_docente_iddocente`),
  ADD CONSTRAINT `fk_cursos_qrgenerados1` FOREIGN KEY (`qrgenerados_idqrgenerados`) REFERENCES `qrgenerados` (`idqrgenerados`);

--
-- Filtros para la tabla `docente`
--
ALTER TABLE `docente`
  ADD CONSTRAINT `fk_docente_usuarios1` FOREIGN KEY (`usuarios_idusuarios`,`usuarios_tipo_documento_tdoc`,`usuarios_tipo_usuario_idtipo_usuario`,`usuarios_credenciales_idcredenciales`,`usuarios_docente_iddocente`) REFERENCES `usuarios` (`idusuarios`, `tipo_documento_tdoc`, `tipo_usuario_idtipo_usuario`, `credenciales_idcredenciales`, `docente_iddocente`);

--
-- Filtros para la tabla `estudiante_ss`
--
ALTER TABLE `estudiante_ss`
  ADD CONSTRAINT `fk_estudiante_ss_usuarios1` FOREIGN KEY (`usuarios_idusuarios`,`usuarios_tipo_documento_tdoc`,`usuarios_tipo_usuario_idtipo_usuario`,`usuarios_credenciales_idcredenciales`,`usuarios_docente_iddocente`) REFERENCES `usuarios` (`idusuarios`, `tipo_documento_tdoc`, `tipo_usuario_idtipo_usuario`, `credenciales_idcredenciales`, `docente_iddocente`);

--
-- Filtros para la tabla `qrescaneados`
--
ALTER TABLE `qrescaneados`
  ADD CONSTRAINT `fk_qrescaneados_estudiante_ss1` FOREIGN KEY (`estudiante_ss_idestudiante_ss`,`estudiante_ss_usuarios_idusuarios`,`estudiante_ss_usuarios_tipo_documento_tdoc`,`estudiante_ss_usuarios_tipo_usuario_idtipo_usuario`,`estudiante_ss_usuarios_credenciales_idcredenciales`,`estudiante_ss_usuarios_docente_iddocente`) REFERENCES `estudiante_ss` (`idestudiante_ss`, `usuarios_idusuarios`, `usuarios_tipo_documento_tdoc`, `usuarios_tipo_usuario_idtipo_usuario`, `usuarios_credenciales_idcredenciales`, `usuarios_docente_iddocente`);

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
