CREATE SCHEMA `acomer` DEFAULT CHARACTER SET utf8 ;
USE `acomer` ;

CREATE TABLE `alumnos` (
  `idAlumnos` int(11) NOT NULL COMMENT 'Identifica a los alumnos y su identificacion es unica',
  `identificacionAlumnos` int(11) NOT NULL COMMENT 'Registro número del documento de identidad',
  `tipodocumentoAlumnos` varchar(45) NOT NULL COMMENT 'Tipo de documento de identidad (cédula, tarjeta de identidad, pasaporte...)',
  `nombreAlumnos` varchar(20) NOT NULL COMMENT 'Registro del nombre(s) de los(as) alumnos(as)',
  `apellidosAlumnos` varchar(20) NOT NULL COMMENT 'Registro de los apellidos de los(as) alumnos(as)',
  `idUsuarios` int(11) NOT NULL COMMENT 'Llave foránea, une las tablas',
  `idCursos` int(11) NOT NULL COMMENT 'Llave foránea, une las tablas'
);

INSERT INTO `alumnos` (`idAlumnos`, `identificacionAlumnos`, `tipodocumentoAlumnos`, `nombreAlumnos`, `apellidosAlumnos`, `idUsuarios`, `idCursos`) VALUES
(2, 87654321, 'Registro Civil', 'María', 'Gómez', 4, 2),
(3, 11223344, 'Tarjeta de Identidad', 'Luis', 'Rodríguez', 6, 3),
(4, 55667788, 'Registro Civil', 'Ana', 'Martínez', 8, 4),
(5, 99887766, 'Permiso de Residencia', 'Pedro', 'González', 10, 5),
(6, 44556677, 'Tarjeta de Identidad', 'Sofía', 'López', 2, 6),
(7, 22334455, 'Tarjeta de Identidad', 'Miguel', 'Díaz', 4, 7),
(8, 66778899, 'Cédula de Ciudadanía', 'Laura', 'Torres', 6, 8),
(9, 33445566, 'Cédula de Ciudadanía', 'Diego', 'Ramírez', 8, 9),
(10, 77665544, 'Pasaporte', 'Carolina', 'Moreno', 10, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumnos_asistencia`
--

CREATE TABLE `alumnos_asistencia` (
  `idAlumnos_Asistencia` int(11) NOT NULL COMMENT 'Identifica la toma de asistencia',
  `nombreAlumnos` varchar(45) NOT NULL COMMENT 'Muestra del nombre(s) de los(as) alumnos(as)',
  `apellidosAlumnos` varchar(45) NOT NULL COMMENT 'Muestra de los apellidos de los(as) alumnos(as)',
  `asistio` tinyint(1) NOT NULL COMMENT 'Indica si el alumno asistió (1 para sí, 0 para no)',
  `idAlumnos` int(11) NOT NULL COMMENT 'Llave foránea, une las tablas'
);

--
-- Volcado de datos para la tabla `alumnos_asistencia`
--

INSERT INTO `alumnos_asistencia` (`idAlumnos_Asistencia`, `nombreAlumnos`, `apellidosAlumnos`, `asistio`, `idAlumnos`) VALUES
(1, 'Juan', 'Pérez', 1, 1),
(2, 'María', 'Gómez', 0, 2),
(3, 'Luis', 'Rodríguez', 1, 3),
(4, 'Ana', 'Martínez', 1, 4),
(5, 'Pedro', 'González', 1, 5),
(6, 'Sofía', 'López', 1, 6),
(7, 'Miguel', 'Díaz', 1, 7),
(8, 'Laura', 'Torres', 0, 8),
(9, 'Diego', 'Ramírez', 1, 9),
(10, 'Carolina', 'Moreno', 0, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencia`
--

CREATE TABLE `asistencia` (
  `idAsistencia` int(11) NOT NULL COMMENT 'Identificador de la asistencia',
  `fechaAsistencia` date NOT NULL COMMENT 'Fecha en la que se registró la asistencia (día, mes, año)',
  `horaAsistencia` time NOT NULL COMMENT 'Hora en la que se registró la asistencia',
  `idQR_Asistencia` int(11) NOT NULL COMMENT 'Llave foránea, une las tablas',
  `idAlumnos_Asistencia` int(11) NOT NULL COMMENT 'Llave foránea, une las tablas'
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `idCursos` int(11) NOT NULL COMMENT 'Identifica el curso y su identificación es única',
  `nombreCurso` varchar(45) NOT NULL COMMENT 'Registra el nombre del curso (901, 902...)',
  `Director` varchar(45) NOT NULL COMMENT 'nombre de los docentes que se encargan de cada curso'
);

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`idCursos`, `nombreCurso`, `Director`) VALUES
(1, 'Curso 901', 'Mayerli Torrejano'),
(2, 'Curso 902', 'Cesar Gordillo'),
(3, 'Curso 903', 'Marcel Mazuera'),
(4, 'Curso 904', 'Ivonne Diaz'),
(5, 'Curso 905', 'Jeimy Perez'),
(6, 'Curso 906', 'Sandra Escobar'),
(7, 'Curso 907', 'Jhoana Rubiano'),
(8, 'Curso 908', 'Jhon Bermudez'),
(9, 'Curso 909', 'Jeimy Leguizamon'),
(10, 'Curso 910', 'Diana Reyes');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estadisticaslecturaqr`
--

CREATE TABLE `estadisticaslecturaqr` (
  `idEstadisticasLecturaQR` int(11) NOT NULL,
  `idEstadisticasQR` int(11) NOT NULL COMMENT 'Llave foránea, une las tablas',
  `idLecturaQR` int(11) NOT NULL COMMENT 'Llave foránea, une las tablas'
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estadisticasqr`
--

CREATE TABLE `estadisticasqr` (
  `idEstadisticasQR` int(11) NOT NULL COMMENT 'Identifica las estadísticas y su identificación es única',
  `CantidadAlimentosEntregados` varchar(500) NOT NULL COMMENT 'Número de alimentos entregados',
  `idUsuarios` int(11) NOT NULL COMMENT 'Llave foránea, une las tablas',
  `idLecturaQR` int(11) NOT NULL COMMENT 'Llave foránea, une las tablas'
);

--
-- Volcado de datos para la tabla `estadisticasqr`
--

INSERT INTO `estadisticasqr` (`idEstadisticasQR`, `CantidadAlimentosEntregados`, `idUsuarios`, `idLecturaQR`) VALUES
(1, '10', 1, 1),
(2, '15', 2, 2),
(3, '8', 3, 3),
(4, '20', 4, 4),
(5, '12', 5, 5),
(6, '18', 6, 6),
(7, '25', 7, 7),
(8, '9', 8, 8),
(9, '11', 9, 9),
(10, '22', 10, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lecturaqr`
--

CREATE TABLE `lecturaqr` (
  `idLecturaQR` int(11) NOT NULL COMMENT 'Identifica la lectura QR realizada',
  `FechaLecturaQR` datetime NOT NULL COMMENT 'Registro día, mes y año y hora de la lectura del QR',
  `idQR` int(11) NOT NULL COMMENT 'Llave foránea, une las tablas'
);

--
-- Volcado de datos para la tabla `lecturaqr`
--

INSERT INTO `lecturaqr` (`idLecturaQR`, `FechaLecturaQR`, `idQR`) VALUES
(1, '0000-00-00 00:00:00', 1),
(2, '0000-00-00 00:00:00', 2),
(3, '0000-00-00 00:00:00', 3),
(4, '0000-00-00 00:00:00', 4),
(5, '0000-00-00 00:00:00', 5),
(6, '0000-00-00 00:00:00', 6),
(7, '0000-00-00 00:00:00', 7),
(8, '0000-00-00 00:00:00', 8),
(9, '0000-00-00 00:00:00', 9),
(10, '0000-00-00 00:00:00', 10),
(11, '2024-08-22 00:00:00', 14),
(12, '2024-08-22 00:00:00', 15),
(13, '2024-08-22 00:00:00', 22),
(14, '2024-08-22 00:00:00', 23),
(15, '2024-08-22 05:16:31', 24);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu`
--

CREATE TABLE `menu` (
  `idMenu` int(11) NOT NULL COMMENT 'Identifica el menú, tiene una identificación única',
  `nombreMenu` varchar(45) NOT NULL COMMENT 'Registro del nombre del menú (almuerzo, desayuno, refrigerio)',
  `diaMenu` varchar(45) NOT NULL COMMENT 'Registro del día del respectivo menú',
  `caracteristicasMenu` varchar(45) NOT NULL COMMENT 'Registro de lo que contiene el menú dependiendo del nombre del menú',
  `idUsuarios` int(11) NOT NULL COMMENT 'Llave foránea, une las tablas'
);

--
-- Volcado de datos para la tabla `menu`
--

INSERT INTO `menu` (`idMenu`, `nombreMenu`, `diaMenu`, `caracteristicasMenu`, `idUsuarios`) VALUES
(3, 'Desayuno', 'Lunes', 'Huevo, Pan, Chocolate, Manzana', 1),
(4, 'Refrigerio', 'Lunes', 'Yogurt, Mantecada, Barra de Cereales, Chocola', 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `qr`
--

CREATE TABLE `qr` (
  `idQR` int(11) NOT NULL COMMENT 'Identificador del QR',
  `CodigoQR` varchar(45) NOT NULL COMMENT 'Captura la información registrada en la asistencia'
);

--
-- Volcado de datos para la tabla `qr`
--

INSERT INTO `qr` (`idQR`, `CodigoQR`) VALUES
(22, '../qr_codes/qr_all_students_1724296178.png'),
(23, '../qr_codes/qr_all_students_1724296237.png'),
(24, '../qr_codes/qr_all_students_1724296591.png'),


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `qr_asistencia`
--

CREATE TABLE `qr_asistencia` (
  `idQR_Asistencia` int(11) NOT NULL,
  `idQR` int(11) NOT NULL COMMENT 'Llave foránea, une las tablas'
);

--
-- Volcado de datos para la tabla `qr_asistencia`
--

INSERT INTO `qr_asistencia` (`idQR_Asistencia`, `idQR`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipousuario`
--

CREATE TABLE `tipousuario` (
  `idTipoUsuario` int(11) NOT NULL COMMENT 'Concede permisos según el usuario, tiene una identificación única',
  `nombreUsuario` varchar(20) NOT NULL COMMENT 'Registra el nombre del usuario para concederle permisos'
);

--
-- Volcado de datos para la tabla `tipousuario`
--

INSERT INTO `tipousuario` (`idTipoUsuario`, `nombreUsuario`) VALUES
(1, 'Administrador'),
(2, 'Estudiante'),
(3, 'Docente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_documento`
--

CREATE TABLE `tipo_documento` (
  `idtipo_documento` int(11) NOT NULL,
  `descripcion_documento` varchar(45) NOT NULL
);

--
-- Volcado de datos para la tabla `tipo_documento`
--

INSERT INTO `tipo_documento` (`idtipo_documento`, `descripcion_documento`) VALUES
(1, 'TI'),
(2, 'RC'),
(3, 'CC'),
(4, 'PS'),
(5, 'PR');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `idUsuarios` int(11) NOT NULL COMMENT 'Sirve para identificar al usuario, la identificación del usuario es única',
  `user` varchar(20) NOT NULL COMMENT 'Registra nombre de usuario',
  `passwordUsuario` varchar(45) NOT NULL COMMENT 'Acceder a la información del sistema por medio de una contraseña',
  `correoUsuario` varchar(45) NOT NULL COMMENT 'Registra el correo electrónico del usuario',
  `Tipo_documento` varchar(45) NOT NULL COMMENT 'Registra el tipo de documento',
  `idTipoUsuario` int(11) NOT NULL COMMENT 'Llave foránea, une las tablas',
  `tipo_documento_idtipo_documento` int(11) NOT NULL
);

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idUsuarios`, `user`, `passwordUsuario`, `correoUsuario`, `Tipo_documento`, `idTipoUsuario`, `tipo_documento_idtipo_documento`) VALUES
(1, 'johndoe', 'password1', 'john.doe@example.com', 'Cédula', 1, 1),
(2, 'janedoe', 'password2', 'jane.doe@example.com', 'Pasaporte', 1, 2),
(3, 'michaeljohnson', 'password3', 'michael.johnson@example.com', 'Tarjeta de Identidad', 2, 3),
(4, 'emilydavis', 'password4', 'emily.davis@example.com', 'Cédula', 2, 1),
(5, 'williamwilson', 'password5', 'william.wilson@example.com', 'Pasaporte', 3, 2),
(6, 'oliviabrown', 'password6', 'olivia.brown@example.com', 'Tarjeta de Identidad', 3, 3),
(7, 'jamesmiller', 'password7', 'james.miller@example.com', 'Cédula', 1, 1),
(8, 'sophiawilson', 'password8', 'sophia.wilson@example.com', 'Pasaporte', 2, 2),
(9, 'benjaminmoore', 'password9', 'benjamin.moore@example.com', 'Tarjeta de Identidad', 1, 3),
(10, 'isabellajohnson', 'password10', 'isabella.johnson@example.com', 'Cédula', 2, 1);

--
-- Indices de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD PRIMARY KEY (`idAlumnos`),
  ADD KEY `idUsuarios_idx` (`idUsuarios`),
  ADD KEY `idCursos_idx` (`idCursos`);

--
-- Indices de la tabla `alumnos_asistencia`
--
ALTER TABLE `alumnos_asistencia`
  ADD PRIMARY KEY (`idAlumnos_Asistencia`),
  ADD KEY `idAlumnos_idx` (`idAlumnos`);

--
-- Indices de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD PRIMARY KEY (`idAsistencia`),
  ADD KEY `idQR_Asistencia_idx` (`idQR_Asistencia`),
  ADD KEY `idAlumnos_Asistencia_idx` (`idAlumnos_Asistencia`);

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`idCursos`);

--
-- Indices de la tabla `estadisticaslecturaqr`
--
ALTER TABLE `estadisticaslecturaqr`
  ADD PRIMARY KEY (`idEstadisticasLecturaQR`),
  ADD KEY `idEstadisticasQR_idx` (`idEstadisticasQR`),
  ADD KEY `idLecturaQR_idx` (`idLecturaQR`);

--
-- Indices de la tabla `estadisticasqr`
--
ALTER TABLE `estadisticasqr`
  ADD PRIMARY KEY (`idEstadisticasQR`),
  ADD KEY `idUsuarios_idx` (`idUsuarios`),
  ADD KEY `idLecturaQR_idx` (`idLecturaQR`);

--
-- Indices de la tabla `lecturaqr`
--
ALTER TABLE `lecturaqr`
  ADD PRIMARY KEY (`idLecturaQR`),
  ADD KEY `idQR_idx` (`idQR`);

--
-- Indices de la tabla `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`idMenu`),
  ADD KEY `idUsuarios_idx` (`idUsuarios`);

--
-- Indices de la tabla `qr`
--
ALTER TABLE `qr`
  ADD PRIMARY KEY (`idQR`),
  ADD UNIQUE KEY `CodigoQR_UNIQUE` (`CodigoQR`);

--
-- Indices de la tabla `qr_asistencia`
--
ALTER TABLE `qr_asistencia`
  ADD PRIMARY KEY (`idQR_Asistencia`),
  ADD KEY `idQR_idx` (`idQR`);

--
-- Indices de la tabla `tipousuario`
--
ALTER TABLE `tipousuario`
  ADD PRIMARY KEY (`idTipoUsuario`);

--
-- Indices de la tabla `tipo_documento`
--
ALTER TABLE `tipo_documento`
  ADD PRIMARY KEY (`idtipo_documento`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idUsuarios`),
  ADD KEY `idTipoUsuario_idx` (`idTipoUsuario`),
  ADD KEY `fk_usuarios_tipo_documento_idx` (`tipo_documento_idtipo_documento`);

--
-- AUTO_INCREMENT de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  MODIFY `idAlumnos` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identifica a los alumnos y su identificacion es unica', AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `alumnos_asistencia`
--
ALTER TABLE `alumnos_asistencia`
  MODIFY `idAlumnos_Asistencia` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identifica la toma de asistencia', AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  MODIFY `idAsistencia` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la asistencia';

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `idCursos` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identifica el curso y su identificación es única', AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `estadisticaslecturaqr`
--
ALTER TABLE `estadisticaslecturaqr`
  MODIFY `idEstadisticasLecturaQR` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estadisticasqr`
--
ALTER TABLE `estadisticasqr`
  MODIFY `idEstadisticasQR` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identifica las estadísticas y su identificación es única', AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `lecturaqr`
--
ALTER TABLE `lecturaqr`
  MODIFY `idLecturaQR` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identifica la lectura QR realizada', AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `menu`
--
ALTER TABLE `menu`
  MODIFY `idMenu` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identifica el menú, tiene una identificación única', AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `qr`
--
ALTER TABLE `qr`
  MODIFY `idQR` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del QR', AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `qr_asistencia`
--
ALTER TABLE `qr_asistencia`
  MODIFY `idQR_Asistencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `tipousuario`
--
ALTER TABLE `tipousuario`
  MODIFY `idTipoUsuario` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Concede permisos según el usuario, tiene una identificación única', AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tipo_documento`
--
ALTER TABLE `tipo_documento`
  MODIFY `idtipo_documento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idUsuarios` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Sirve para identificar al usuario, la identificación del usuario es única', AUTO_INCREMENT=11;

--
-- Filtros para la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD CONSTRAINT `fk_alumnos_cursos` FOREIGN KEY (`idCursos`) REFERENCES `cursos` (`idCursos`),
  ADD CONSTRAINT `fk_alumnos_usuarios` FOREIGN KEY (`idUsuarios`) REFERENCES `usuarios` (`idUsuarios`);

--
-- Filtros para la tabla `alumnos_asistencia`
--
ALTER TABLE `alumnos_asistencia`
  ADD CONSTRAINT `fk_alumnos_asistencia` FOREIGN KEY (`idAlumnos`) REFERENCES `alumnos` (`idAlumnos`);

--
-- Filtros para la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD CONSTRAINT `fk_asistencia_alumnos_asistencia` FOREIGN KEY (`idAlumnos_Asistencia`) REFERENCES `alumnos_asistencia` (`idAlumnos_Asistencia`),
  ADD CONSTRAINT `fk_asistencia_qr_asistencia` FOREIGN KEY (`idQR_Asistencia`) REFERENCES `qr_asistencia` (`idQR_Asistencia`);

--
-- Filtros para la tabla `estadisticaslecturaqr`
--
ALTER TABLE `estadisticaslecturaqr`
  ADD CONSTRAINT `fk_estadisticaslecturaqr_estadisticasqr` FOREIGN KEY (`idEstadisticasQR`) REFERENCES `estadisticasqr` (`idEstadisticasQR`),
  ADD CONSTRAINT `fk_estadisticaslecturaqr_lecturaqr` FOREIGN KEY (`idLecturaQR`) REFERENCES `lecturaqr` (`idLecturaQR`);

--
-- Filtros para la tabla `estadisticasqr`
--
ALTER TABLE `estadisticasqr`
  ADD CONSTRAINT `fk_estadisticasqr_lecturaqr` FOREIGN KEY (`idLecturaQR`) REFERENCES `lecturaqr` (`idLecturaQR`),
  ADD CONSTRAINT `fk_estadisticasqr_usuarios` FOREIGN KEY (`idUsuarios`) REFERENCES `usuarios` (`idUsuarios`);

--
-- Filtros para la tabla `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `fk_menu_usuarios` FOREIGN KEY (`idUsuarios`) REFERENCES `usuarios` (`idUsuarios`);

--
-- Filtros para la tabla `qr_asistencia`
--
ALTER TABLE `qr_asistencia`
  ADD CONSTRAINT `fk_qr_asistencia_qr` FOREIGN KEY (`idQR`) REFERENCES `qr` (`idQR`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_tipo_documento` FOREIGN KEY (`tipo_documento_idtipo_documento`) REFERENCES `tipo_documento` (`idtipo_documento`),
  ADD CONSTRAINT `fk_usuarios_tipo_usuario` FOREIGN KEY (`idTipoUsuario`) REFERENCES `tipousuario` (`idTipoUsuario`);


