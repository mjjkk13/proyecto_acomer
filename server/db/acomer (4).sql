-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-09-2024 a las 20:13:43
-- Versión del servidor: 10.4.22-MariaDB
-- Versión de PHP: 8.1.2

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `admin`
--

INSERT INTO `admin` (`idadmin`, `usuarios_idusuarios`, `usuarios_tipo_documento_tdoc`, `usuarios_tipo_usuario_idtipo_usuario`, `usuarios_credenciales_idcredenciales`, `estadisticasqr_idestadisticasqr`) VALUES
(0, 122, 'TI', 3, 118, 0),
(0, 123, 'TI', 3, 119, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumnos`
--

CREATE TABLE `alumnos` (
  `idalumnos` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `apellido` varchar(20) NOT NULL,
  `docentealumnos_iddocentealumnos` int(11) DEFAULT NULL,
  `cursos_idcursos` int(11) NOT NULL,
  `CodigosGenerados` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `alumnos`
--

INSERT INTO `alumnos` (`idalumnos`, `nombre`, `apellido`, `docentealumnos_iddocentealumnos`, `cursos_idcursos`, `CodigosGenerados`) VALUES
(16, 'Juan', 'Cardenas', NULL, 27, 0),
(17, 'Kevin', 'Garcia', NULL, 27, 0),
(18, 'Lizeth', 'Rubiano', NULL, 20, 0),
(19, 'Maria Jose', 'Ramirez', NULL, 19, 0),
(20, 'Ashly', 'Urrea', NULL, 25, 0);

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
  `docente_iddocente` int(11) NOT NULL,
  `alumnos_idalumnos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `asistencia`
--

INSERT INTO `asistencia` (`idasistencia`, `fecha`, `estado`, `registradopor`, `qrgenerados_idqrgenerados`, `docente_iddocente`, `alumnos_idalumnos`) VALUES
(6, '2024-09-18', 1, 4, 30, 9, 16);

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
  `ultimoacceso` datetime NOT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `credenciales`
--

INSERT INTO `credenciales` (`idcredenciales`, `user`, `contrasena`, `fecharegistro`, `ultimoacceso`, `estado`) VALUES
(21, 'Jose', '$2y$10$Cw2ko3.jIBxGp342onHTGOqYopqbPeeDE.zwKHK0UXrEED5RTncse', '2024-09-12 22:48:25', '2024-09-16 11:13:17', 1),
(103, 'Marcel', '$2y$10$SCnYIXoq5jE2grtravqWD.wmdnYvlvcvfcyMij4mpvZHESfchjJCm', '2024-09-12 23:53:50', '0000-00-00 00:00:00', 1),
(104, 'Mayerli', '$2y$10$1LwHEN2VjETknHXqR4WAA.w9rXF/mVU4QNAzmbEVdNgBjSkqlcPF2', '2024-09-12 23:55:44', '2024-09-16 11:35:54', 1),
(106, 'Jessica', '$2y$10$ir7GaQoY2SMSzQyDr6Yqm..szZ6aCEqET0k5oV2caD.6eS1bC5ate', '2024-09-12 23:57:53', '2024-09-18 11:34:17', 1),
(111, 'Santiago', '$2y$10$KrjzKf52MwdsXSWwiMfvHONcvGTlVbNSXV.092jCjBg2RrnbHbSCa', '2024-09-13 00:19:28', '2024-09-16 11:33:57', 1),
(115, 'Cristian', '$2y$10$DyUrfrA5/zdsiFuZkgBmFu5U1Po22N47x1L.r2AypvcFHhSt.MW5S', '2024-09-13 00:25:58', '0000-00-00 00:00:00', 1),
(118, 'JuanK', '$2y$10$c1r99pokB/u0t.dP9leX/O4IZb85C1AHyRquz1bAP9XANw4cLSdVW', '2024-09-16 10:41:52', '0000-00-00 00:00:00', 1),
(119, 'JuanK', '$2y$10$N7RcvsgBOn5jkFabcXllkOcvQ6JUsDdNri8/hYwk/xtCUeNJnIig2', '2024-09-16 10:43:19', '0000-00-00 00:00:00', 1),
(120, 'Ugly', '$2y$10$yN7FbVRO1uSZxENjzLG.Eek90bJqhCwrz8gt5PevKgqa8RFNAoZDi', '2024-09-18 13:09:40', '0000-00-00 00:00:00', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `idcursos` int(11) NOT NULL,
  `nombrecurso` varchar(20) NOT NULL,
  `docente_iddocente` int(11) DEFAULT NULL,
  `docente_usuarios_idusuarios` int(11) DEFAULT NULL,
  `docente_usuarios_tipo_documento_tdoc` varchar(50) DEFAULT NULL,
  `docente_usuarios_tipo_usuario_idtipo_usuario` int(11) DEFAULT NULL,
  `docente_usuarios_credenciales_idcredenciales` int(11) NOT NULL,
  `asistencia_idasistencia` int(11) DEFAULT NULL,
  `qrgenerados_idqrgenerados` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`idcursos`, `nombrecurso`, `docente_iddocente`, `docente_usuarios_idusuarios`, `docente_usuarios_tipo_documento_tdoc`, `docente_usuarios_tipo_usuario_idtipo_usuario`, `docente_usuarios_credenciales_idcredenciales`, `asistencia_idasistencia`, `qrgenerados_idqrgenerados`) VALUES
(17, 'Curso 902', 107, NULL, NULL, NULL, 0, NULL, NULL),
(18, 'Curso 903', 108, NULL, NULL, NULL, 0, NULL, NULL),
(19, 'Curso 904', 25, NULL, NULL, NULL, 0, NULL, 31),
(20, 'Curso 1001', 107, NULL, NULL, NULL, 0, NULL, 31),
(21, 'Curso 1002', 108, NULL, NULL, NULL, 0, NULL, NULL),
(22, 'Curso 1003', 25, NULL, NULL, NULL, 0, NULL, NULL),
(23, 'Curso 1004', 107, NULL, NULL, NULL, 0, NULL, NULL),
(24, 'Curso 1005', 107, NULL, NULL, NULL, 0, NULL, NULL),
(25, 'Curso 1006', 25, NULL, NULL, NULL, 0, NULL, 31),
(26, 'Curso 1101', 107, NULL, NULL, NULL, 0, NULL, NULL),
(27, 'Curso 1102', 25, NULL, NULL, NULL, 0, NULL, 32),
(28, 'Curso 901', 25, NULL, NULL, NULL, 0, NULL, NULL);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `docente`
--

INSERT INTO `docente` (`iddocente`, `usuarios_idusuarios`, `usuarios_tipo_documento_tdoc`, `usuarios_tipo_usuario_idtipo_usuario`, `usuarios_credenciales_idcredenciales`) VALUES
(7, 25, 'CC', 2, 21),
(8, 107, 'CC', 2, 103),
(9, 108, 'CC', 2, 104),
(11, 119, 'CC', 3, 115),
(12, 124, 'TI', 2, 120);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estadisticasqr`
--

CREATE TABLE `estadisticasqr` (
  `idestadisticasqr` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `estudiantesqasistieron` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
(26, '2024-06-24', 3),
(27, '2024-09-16', 1),
(28, '2024-09-16', 1),
(29, '2024-09-16', 1),
(30, '2024-09-16', 3),
(31, '2024-09-16', 5),
(32, '2024-09-18', 2);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `estudiante_ss`
--

INSERT INTO `estudiante_ss` (`idestudiante_ss`, `qr_registrados`, `usuarios_idusuarios`, `usuarios_tipo_documento_tdoc`, `usuarios_tipo_usuario_idtipo_usuario`, `usuarios_credenciales_idcredenciales`) VALUES
(4, '', 110, 'TI', 1, 106),
(5, '', 115, 'TI', 3, 111);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu`
--

CREATE TABLE `menu` (
  `idmenu` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `tipomenu` varchar(20) NOT NULL,
  `descripcion` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `menu`
--

INSERT INTO `menu` (`idmenu`, `fecha`, `tipomenu`, `descripcion`) VALUES
(2, '2024-08-27', 'Almuerzo', 'pollo'),
(3, '2024-08-27', 'Refrigerio', 'avena'),
(4, '2024-08-28', 'Desayuno', 'huevos, arroz'),
(20, '2024-08-30', 'desayuno', 'nada'),
(21, '2024-09-12', 'desayuno', 'pollo'),
(22, '2024-09-28', 'desayuno', 'Carne');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `qrgenerados`
--

CREATE TABLE `qrgenerados` (
  `idqrgenerados` int(11) NOT NULL,
  `codigoqr` varchar(255) NOT NULL,
  `fechageneracion` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `qrgenerados`
--

INSERT INTO `qrgenerados` (`idqrgenerados`, `codigoqr`, `fechageneracion`) VALUES
(30, '../qr_codes/qr_all_students_1726503129.png', '2024-09-16 18:12:09'),
(31, '../qr_codes/qr_all_students_1726504014.png', '2024-09-16 18:26:54'),
(32, '../qr_codes/qr_all_students_1726677367.png', '2024-09-18 18:36:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_documento`
--

CREATE TABLE `tipo_documento` (
  `tdoc` varchar(10) NOT NULL,
  `descripcion` varchar(30) NOT NULL,
  `estadodoc` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `contraseña` varchar(40) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `numerodocumento` int(10) NOT NULL,
  `tipo_documento_tdoc` varchar(10) NOT NULL,
  `tipo_usuario_idtipo_usuario` int(11) NOT NULL,
  `credenciales_idcredenciales` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idusuarios`, `nombre`, `apellido`, `email`, `contraseña`, `telefono`, `direccion`, `numerodocumento`, `tipo_documento_tdoc`, `tipo_usuario_idtipo_usuario`, `credenciales_idcredenciales`) VALUES
(25, 'Jose', 'Ramirez', 'filomif403@ploncy.com', '', '3156669875', 'Bosa', 46983579, 'CC', 2, 21),
(107, 'Marcel', 'Mazuera', 'mifam61376@obisims.com', '', '3556699875', 'Bosa', 89765433, 'CC', 2, 103),
(108, 'Mayerli', 'Torrejano', 'joneco9174@ploncy.com', '', '3165554488', 'Bosa', 46879564, 'CC', 2, 104),
(110, 'Jessica', 'Martinez', 'bibipad850@konetas.com', '', '3114568974', 'Bosa', 1022549877, 'TI', 1, 106),
(115, 'Kevin Santiago', 'Garcia', 'kevingarciago3@gmail.com', '', '3163237616', 'calle 56f sur #92a-29', 1032940369, 'TI', 3, 111),
(119, 'Cristian', 'Garcia', 'cristiangarciago3@gmail.com', '', '3112355239', 'calle 56f sur #92a-29', 1032937602, 'CC', 3, 115),
(123, 'Juan', 'Cardenas', 'juankarloscardenasr2@gmail.com', '', '3011546899', 'Bosa', 1020736727, 'TI', 3, 119),
(124, 'ujy', 'Pineda', 'juankarloscardenasr@gmail.com', '', '3555464894', 'Bosa', 1054986354, 'TI', 2, 120);

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
  ADD KEY `fk_asistencia_alumnos` (`alumnos_idalumnos`),
  ADD KEY `Docente_asistencia_fk` (`docente_iddocente`),
  ADD KEY `estudiante_asistencia_fk` (`registradopor`);

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
  ADD KEY `fk_cursos_qrgenerados1_idx` (`qrgenerados_idqrgenerados`),
  ADD KEY `idx_docente_iddocente` (`docente_iddocente`),
  ADD KEY `idx_docente_usuarios_idusuarios` (`docente_usuarios_idusuarios`),
  ADD KEY `idx_docente_tipo_documento_tdoc` (`docente_usuarios_tipo_documento_tdoc`),
  ADD KEY `idx_docente_tipo_usuario_idtipo_usuario` (`docente_usuarios_tipo_usuario_idtipo_usuario`);

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
  ADD KEY `fk_docentealumnos_docente1_idx` (`docente_iddocente`,`docente_usuarios_idusuarios`,`docente_usuarios_tipo_documento_tdoc`,`docente_usuarios_tipo_usuario_idtipo_usuario`,`docente_usuarios_credenciales_idcredenciales`),
  ADD KEY `fk_docentealumnos_usuarios` (`docente_usuarios_idusuarios`),
  ADD KEY `fk_docentealumnos_tipo_documento` (`docente_usuarios_tipo_documento_tdoc`),
  ADD KEY `fk_docentealumnos_tipo_usuario` (`docente_usuarios_tipo_usuario_idtipo_usuario`),
  ADD KEY `fk_docentealumnos_credenciales` (`docente_usuarios_credenciales_idcredenciales`);

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
-- AUTO_INCREMENT de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  MODIFY `idalumnos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  MODIFY `idasistencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `credenciales`
--
ALTER TABLE `credenciales`
  MODIFY `idcredenciales` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `idcursos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `docente`
--
ALTER TABLE `docente`
  MODIFY `iddocente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `docentealumnos`
--
ALTER TABLE `docentealumnos`
  MODIFY `iddocentealumnos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `estadisticasqr`
--
ALTER TABLE `estadisticasqr`
  MODIFY `idestadisticasqr` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

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
  MODIFY `idqrescaneados` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `qrgenerados`
--
ALTER TABLE `qrgenerados`
  MODIFY `idqrgenerados` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idusuarios` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD CONSTRAINT `fk_cursos` FOREIGN KEY (`cursos_idcursos`) REFERENCES `cursos` (`idcursos`) ON DELETE CASCADE;

--
-- Filtros para la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD CONSTRAINT `Docente_asistencia_fk` FOREIGN KEY (`docente_iddocente`) REFERENCES `docente` (`iddocente`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `estudiante_asistencia_fk` FOREIGN KEY (`registradopor`) REFERENCES `estudiante_ss` (`idestudiante_ss`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_asistencia_alumnos` FOREIGN KEY (`alumnos_idalumnos`) REFERENCES `alumnos` (`idalumnos`),
  ADD CONSTRAINT `fk_asistencia_qrgenerados1` FOREIGN KEY (`qrgenerados_idqrgenerados`) REFERENCES `qrgenerados` (`idqrgenerados`) ON DELETE CASCADE;

--
-- Filtros para la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD CONSTRAINT `fk_cursos_asistencia1` FOREIGN KEY (`asistencia_idasistencia`) REFERENCES `asistencia` (`idasistencia`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cursos_docente1` FOREIGN KEY (`docente_iddocente`,`docente_usuarios_idusuarios`,`docente_usuarios_tipo_documento_tdoc`,`docente_usuarios_tipo_usuario_idtipo_usuario`) REFERENCES `docente` (`iddocente`, `usuarios_idusuarios`, `usuarios_tipo_documento_tdoc`, `usuarios_tipo_usuario_idtipo_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cursos_qrgenerados1` FOREIGN KEY (`qrgenerados_idqrgenerados`) REFERENCES `qrgenerados` (`idqrgenerados`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `docente`
--
ALTER TABLE `docente`
  ADD CONSTRAINT `fk_docente_usuarios` FOREIGN KEY (`usuarios_idusuarios`,`usuarios_tipo_documento_tdoc`,`usuarios_tipo_usuario_idtipo_usuario`,`usuarios_credenciales_idcredenciales`) REFERENCES `usuarios` (`idusuarios`, `tipo_documento_tdoc`, `tipo_usuario_idtipo_usuario`, `credenciales_idcredenciales`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `docentealumnos`
--
ALTER TABLE `docentealumnos`
  ADD CONSTRAINT `fk_docentealumnos_alumnos` FOREIGN KEY (`iddocentealumnos`) REFERENCES `alumnos` (`idalumnos`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_docentealumnos_credenciales` FOREIGN KEY (`docente_usuarios_credenciales_idcredenciales`) REFERENCES `credenciales` (`idcredenciales`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_docentealumnos_docente` FOREIGN KEY (`docente_iddocente`) REFERENCES `docente` (`iddocente`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_docentealumnos_tipo_documento` FOREIGN KEY (`docente_usuarios_tipo_documento_tdoc`) REFERENCES `tipo_documento` (`tdoc`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_docentealumnos_tipo_usuario` FOREIGN KEY (`docente_usuarios_tipo_usuario_idtipo_usuario`) REFERENCES `tipo_usuario` (`idtipo_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_docentealumnos_usuarios` FOREIGN KEY (`docente_usuarios_idusuarios`) REFERENCES `usuarios` (`idusuarios`) ON DELETE CASCADE;

--
-- Filtros para la tabla `estudiante_ss`
--
ALTER TABLE `estudiante_ss`
  ADD CONSTRAINT `fk_estudiante_ss_usuarios` FOREIGN KEY (`usuarios_idusuarios`,`usuarios_tipo_documento_tdoc`,`usuarios_tipo_usuario_idtipo_usuario`,`usuarios_credenciales_idcredenciales`) REFERENCES `usuarios` (`idusuarios`, `tipo_documento_tdoc`, `tipo_usuario_idtipo_usuario`, `credenciales_idcredenciales`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `qrescaneados`
--
ALTER TABLE `qrescaneados`
  ADD CONSTRAINT `fk_qrescaneados_estudiante_ss1` FOREIGN KEY (`estudiante_ss_idestudiante_ss`,`estudiante_ss_usuarios_idusuarios`,`estudiante_ss_usuarios_tipo_documento_tdoc`) REFERENCES `estudiante_ss` (`idestudiante_ss`, `usuarios_idusuarios`, `usuarios_tipo_documento_tdoc`) ON DELETE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_credenciales1` FOREIGN KEY (`credenciales_idcredenciales`) REFERENCES `credenciales` (`idcredenciales`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_usuarios_tipo_documento` FOREIGN KEY (`tipo_documento_tdoc`) REFERENCES `tipo_documento` (`tdoc`),
  ADD CONSTRAINT `fk_usuarios_tipo_usuario1` FOREIGN KEY (`tipo_usuario_idtipo_usuario`) REFERENCES `tipo_usuario` (`idtipo_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
