CREATE SCHEMA IF NOT EXISTS `acomer` DEFAULT CHARACTER SET utf8;
USE `acomer`;

-- Tabla: credenciales (sin dependencias)
CREATE TABLE IF NOT EXISTS `acomer`.`credenciales` (
  `idcredenciales` INT(11) NOT NULL AUTO_INCREMENT,
  `user` VARCHAR(20) NOT NULL,
  `contrasena` VARCHAR(255) NOT NULL,  -- Considera almacenar contraseñas hasheadas
  `fecharegistro` DATETIME NOT NULL,
  `ultimoacceso` DATETIME NOT NULL,
  `estado` TINYINT(1) NOT NULL,
  PRIMARY KEY (`idcredenciales`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;

-- Tabla: tipo_documento (sin dependencias)
CREATE TABLE IF NOT EXISTS `acomer`.`tipo_documento` (
  `tdoc` VARCHAR(10) NOT NULL,
  `descripcion` VARCHAR(30) NOT NULL,
  `estadodoc` TINYINT(4) NOT NULL,
  PRIMARY KEY (`tdoc`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;

-- Tabla: tipo_usuario (sin dependencias)
CREATE TABLE IF NOT EXISTS `acomer`.`tipo_usuario` (
  `idtipo_usuario` INT(11) NOT NULL AUTO_INCREMENT,
  `rol` VARCHAR(30) NOT NULL,
  PRIMARY KEY (`idtipo_usuario`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;

-- Tabla: usuarios (depende de credenciales, tipo_documento y tipo_usuario)
CREATE TABLE IF NOT EXISTS `acomer`.`usuarios` (
  `idusuarios` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(20) NOT NULL,
  `apellido` VARCHAR(20) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL, -- Aumentado tamaño para almacenar hash completo
  `telefono` VARCHAR(20) NOT NULL,
  `direccion` VARCHAR(255) NOT NULL,
  `numerodocumento` INT(10) NOT NULL,
  `tipo_documento` VARCHAR(10) NOT NULL,
  `tipo_usuario` INT(11) NOT NULL,
  `credenciales` INT(11) NOT NULL,
  PRIMARY KEY (`idusuarios`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  UNIQUE INDEX `numerodocumento_UNIQUE` (`numerodocumento` ASC),
  INDEX `fk_usuarios_tipo_documento_idx` (`tipo_documento` ASC),
  INDEX `fk_usuarios_tipo_usuario_idx` (`tipo_usuario` ASC),
  INDEX `fk_usuarios_credenciales_idx` (`credenciales` ASC),
  CONSTRAINT `fk_usuarios_tipo_documento`
    FOREIGN KEY (`tipo_documento`)
    REFERENCES `acomer`.`tipo_documento` (`tdoc`),
  CONSTRAINT `fk_usuarios_tipo_usuario`
    FOREIGN KEY (`tipo_usuario`)
    REFERENCES `acomer`.`tipo_usuario` (`idtipo_usuario`),
  CONSTRAINT `fk_usuarios_credenciales`
    FOREIGN KEY (`credenciales`)
    REFERENCES `acomer`.`credenciales` (`idcredenciales`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;

-- Tabla: admin (depende de usuarios)
CREATE TABLE IF NOT EXISTS `acomer`.`admin` (
  `idadmin` INT(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` INT(11) NOT NULL,
  PRIMARY KEY (`idadmin`),
  INDEX `fk_admin_usuario_idx` (`usuario_id` ASC),
  CONSTRAINT `fk_admin_usuario`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `acomer`.`usuarios` (`idusuarios`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;

-- Tabla: docente (depende de usuarios)
CREATE TABLE IF NOT EXISTS `acomer`.`docente` (
  `iddocente` INT(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` INT(11) NOT NULL,
  PRIMARY KEY (`iddocente`),
  INDEX `fk_docente_usuario_idx` (`usuario_id` ASC),
  CONSTRAINT `fk_docente_usuario`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `acomer`.`usuarios` (`idusuarios`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;

-- Tabla: qrgenerados (independiente)
CREATE TABLE IF NOT EXISTS `acomer`.`qrgenerados` (
  `idqrgenerados` INT(11) NOT NULL AUTO_INCREMENT,
  `codigoqr` VARCHAR(255) NOT NULL,
  `fechageneracion` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idqrgenerados`),
  UNIQUE INDEX `codigoqr_UNIQUE` (`codigoqr` ASC)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;

-- Tabla: cursos (depende de docente)
CREATE TABLE IF NOT EXISTS `acomer`.`cursos` (
  `idcursos` INT(11) NOT NULL AUTO_INCREMENT,
  `nombrecurso` VARCHAR(255) NOT NULL,
  `docente_id` INT(11) NOT NULL,
  PRIMARY KEY (`idcursos`),
  INDEX `fk_cursos_docente_idx` (`docente_id` ASC),
  CONSTRAINT `fk_cursos_docente`
    FOREIGN KEY (`docente_id`)
    REFERENCES `acomer`.`docente` (`iddocente`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;

-- Tabla: alumnos (depende de cursos)
CREATE TABLE IF NOT EXISTS `acomer`.`alumnos` (
  `idalumno` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(20) NOT NULL,
  `apellido` VARCHAR(20) NOT NULL,
  `curso_id` INT(11) NOT NULL,
  PRIMARY KEY (`idalumno`),
  INDEX `fk_alumnos_curso_idx` (`curso_id` ASC),
  CONSTRAINT `fk_alumnos_curso`
    FOREIGN KEY (`curso_id`)
    REFERENCES `acomer`.`cursos` (`idcursos`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;

-- Tabla: asistencia (depende de qrgenerados, docente y alumnos)
CREATE TABLE IF NOT EXISTS `acomer`.`asistencia` (
  `idasistencia` INT(11) NOT NULL AUTO_INCREMENT,
  `fecha` DATE NOT NULL,
  `estado` TINYINT(4) NOT NULL,
  `qrgenerados_id` INT(11) NOT NULL,
  `docente_id` INT(11) NOT NULL,
  `alumno_id` INT(11) NOT NULL,
  PRIMARY KEY (`idasistencia`),
  INDEX `fk_asistencia_qrgenerados_idx` (`qrgenerados_id` ASC),
  INDEX `fk_asistencia_alumnos_idx` (`alumno_id` ASC),
  INDEX `fk_asistencia_docente_idx` (`docente_id` ASC),
  CONSTRAINT `fk_asistencia_docente`
    FOREIGN KEY (`docente_id`)
    REFERENCES `acomer`.`docente` (`iddocente`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_asistencia_alumno`
    FOREIGN KEY (`alumno_id`)
    REFERENCES `acomer`.`alumnos` (`idalumno`),
  CONSTRAINT `fk_asistencia_qrgenerados`
    FOREIGN KEY (`qrgenerados_id`)
    REFERENCES `acomer`.`qrgenerados` (`idqrgenerados`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;

-- Tabla: estadisticasqr (depende de admin)
CREATE TABLE IF NOT EXISTS `acomer`.`estadisticasqr` (
  `idestadisticasqr` INT(11) NOT NULL AUTO_INCREMENT,
  `fecha` DATE NOT NULL,
  `estudiantes_q_asistieron` INT(11) NOT NULL,
  `admin_id` INT(11) NOT NULL,
  PRIMARY KEY (`idestadisticasqr`),
  INDEX `fk_estadisticasqr_admin_idx` (`admin_id` ASC),
  CONSTRAINT `fk_estadisticasqr_admin`
    FOREIGN KEY (`admin_id`)
    REFERENCES `acomer`.`admin` (`idadmin`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;

-- Tabla: estudiante_ss (depende de usuarios)
CREATE TABLE IF NOT EXISTS `acomer`.`estudiante_ss` (
  `idestudiante_ss` INT(11) NOT NULL AUTO_INCREMENT,
  `qr_registrados` TEXT NOT NULL,
  `usuario_id` INT(11) NOT NULL,
  PRIMARY KEY (`idestudiante_ss`),
  INDEX `fk_estudiante_ss_usuario_idx` (`usuario_id` ASC),
  CONSTRAINT `fk_estudiante_ss_usuario`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `acomer`.`usuarios` (`idusuarios`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;

-- Tabla: menu (depende de admin)
CREATE TABLE IF NOT EXISTS `acomer`.`menu` (
  `idmenu` INT(11) NOT NULL AUTO_INCREMENT,
  `fecha` DATE NOT NULL,
  `tipomenu` VARCHAR(20) NOT NULL,
  `descripcion` LONGTEXT NOT NULL,
  `admin_id` INT(11) NOT NULL,
  PRIMARY KEY (`idmenu`),
  INDEX `fk_menu_admin_idx` (`admin_id` ASC),
  CONSTRAINT `fk_menu_admin`
    FOREIGN KEY (`admin_id`)
    REFERENCES `acomer`.`admin` (`idadmin`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;

-- Tabla: qrescaneados (depende de estudiante_ss)
CREATE TABLE IF NOT EXISTS `acomer`.`qrescaneados` (
  `idqrescaneados` INT(11) NOT NULL AUTO_INCREMENT,
  `fecha_escaneo` DATETIME NOT NULL,
  `estudiante_ss_id` INT(11) NOT NULL,
  PRIMARY KEY (`idqrescaneados`),
  INDEX `fk_qrescaneados_estudiante_ss_idx` (`estudiante_ss_id` ASC),
  CONSTRAINT `fk_qrescaneados_estudiante_ss`
    FOREIGN KEY (`estudiante_ss_id`)
    REFERENCES `acomer`.`estudiante_ss` (`idestudiante_ss`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;

-- INSERTS PARA LA TABLA: tipo_documento
INSERT INTO `tipo_documento` (`tdoc`, `descripcion`, `estadodoc`) VALUES
('CC', 'Cédula de Ciudadanía', 1),
('CE', 'Cédula de Extranjería', 1),
('PA', 'Pasaporte', 1),
('PR', 'Permiso de Residencia', 1),
('RC', 'Registro Civil', 1),
('TI', 'Tarjeta de Identidad', 1);

-- INSERTS PARA LA TABLA: tipo_usuario
INSERT INTO `tipo_usuario` (`idtipo_usuario`, `rol`) VALUES
(1, 'Estudiante SS'),
(2, 'Docente'),
(3, 'Administrador');

-- INSERTS PARA LA TABLA: credenciales
INSERT INTO `credenciales` (`idcredenciales`, `user`, `contrasena`, `fecharegistro`, `ultimoacceso`, `estado`) VALUES
(21, 'Jose', '$2y$10$Cw2ko3.jIBxGp342onHTGOqYopqbPeeDE.zwKHK0UXrEED5RTncse', '2024-09-12 22:48:25', '2025-04-02 13:46:54', 1),
(103, 'Marcel', '$2y$10$SCnYIXoq5jE2grtravqWD.wmdnYvlvcvfcyMij4mpvZHESfchjJCm', '2024-09-12 23:53:50', '2025-04-02 13:50:34', 1),
(104, 'Mayerli', '$2y$10$1LwHEN2VjETknHXqR4WAA.w9rXF/mVU4QNAzmbEVdNgBjSkqlcPF2', '2024-09-12 23:55:44', '2024-09-16 11:35:54', 1),
(106, 'Jessica', '$2y$10$ir7GaQoY2SMSzQyDr6Yqm..szZ6aCEqET0k5oV2caD.6eS1bC5ate', '2024-09-12 23:57:53', '2025-03-31 11:40:11', 1),
(111, 'Santiago', '$2y$10$KrjzKf52MwdsXSWwiMfvHONcvGTlVbNSXV.092jCjBg2RrnbHbSCa', '2024-09-13 00:19:28', '2025-03-27 19:27:33', 1),
(115, 'Cristian', '$2y$10$DyUrfrA5/zdsiFuZkgBmFu5U1Po22N47x1L.r2AypvcFHhSt.MW5S', '2024-09-13 00:25:58', '0000-00-00 00:00:00', 1),
(118, 'JuanK', '$2y$10$c1r99pokB/u0t.dP9leX/O4IZb85C1AHyRquz1bAP9XANw4cLSdVW', '2024-09-16 10:41:52', '0000-00-00 00:00:00', 1),
(119, 'JuanK', '$2y$10$N7RcvsgBOn5jkFabcXllkOcvQ6JUsDdNri8/hYwk/xtCUeNJnIig2', '2024-09-16 10:43:19', '0000-00-00 00:00:00', 1),
(120, 'Ugly', '$2y$10$yN7FbVRO1uSZxENjzLG.Eek90bJqhCwrz8gt5PevKgqa8RFNAoZDi', '2024-09-18 13:09:40', '0000-00-00 00:00:00', 1),
(121, 'Mariana', '$2y$10$POHFcCsKHG1pQRR3T7mYb.BOUrUvtjmT3oXje8lkRycFl9EH/2cMW', '2025-03-27 19:28:43', '2025-04-01 21:26:24', 1);

-- INSERTS PARA LA TABLA: usuarios
INSERT INTO `usuarios` 
(`idusuarios`, `nombre`, `apellido`, `email`, `password`, `telefono`, `direccion`, `numerodocumento`, `tipo_documento`, `tipo_usuario`, `credenciales`)
VALUES
(25, 'Jose', 'Ramirez', 'filomif403@ploncy.com', '', '3156669875', 'Bosa', 46983579, 'CC', 2, 21),
(107, 'Marcel', 'Mazuera', 'mifam61376@obisims.com', '', '3556699875', 'Bosa', 89765433, 'CC', 2, 103),
(108, 'Mayerli', 'Torrejano', 'joneco9174@ploncy.com', '', '3165554488', 'Bosa', 46879564, 'CC', 2, 104),
(110, 'Jessica', 'Martinez', 'bibipad850@konetas.com', '', '3114568974', 'Bosa', 1022549877, 'TI', 1, 106),
(115, 'Kevin Santiago', 'Garcia', 'kevingarciago3@gmail.com', '', '3163237616', 'calle 56f sur #92a-29', 1032940369, 'TI', 3, 111),
(119, 'Cristian', 'Garcia', 'cristiangarciago3@gmail.com', '', '3112355239', 'calle 56f sur #92a-29', 1032937602, 'CC', 3, 115),
(123, 'Juan', 'Cardenas', 'juankarloscardenasr2@gmail.com', '', '3011546899', 'Bosa', 1020736727, 'TI', 3, 119),
(124, 'ujy', 'Pineda', 'juankarloscardenasr@gmail.com', '', '3555464894', 'Bosa', 1054986354, 'TI', 2, 120),
(125, 'Mariana', 'Jiménez Villa', 'marianajimenezv2006@gmail.com', '', '3133958194', 'Cra 114 #148-65', 1013261783, 'CC', 3, 121);

-- INSERTS PARA LA TABLA: docente  
INSERT INTO `docente` (`iddocente`, `usuario_id`) VALUES
(7, 25),
(8, 107),
(9, 108),
(11, 119),
(12, 124);

-- INSERTS PARA LA TABLA: admin  
INSERT INTO `admin` (`usuario_id`) VALUES
(123),
(125);

-- INSERTS PARA LA TABLA: cursos  
INSERT INTO `cursos` (`idcursos`, `nombrecurso`, `docente_id`) VALUES
(23, 'Curso 1004', 8),
(24, 'Curso 1005', 8),
(26, 'Curso 1101', 8),
(31, 'Curso 1104', 9);

-- INSERTS PARA LA TABLA: alumnos  
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

-- INSERTS PARA LA TABLA: estadisticasqr  
INSERT INTO `estadisticasqr` (`idestadisticasqr`, `fecha`, `estudiantes_q_asistieron`, `admin_id`) VALUES
(1, '2024-08-26', 45, 1),
(2, '2024-08-27', 45, 1),
(3, '2024-08-28', 30, 1),
(4, '2024-08-29', 55, 1),
(5, '2024-08-30', 40, 1),
(7, '2024-08-19', 15, 1),
(8, '2024-08-20', 100, 1),
(9, '2024-08-21', 59, 1),
(10, '2024-08-22', 78, 1),
(11, '2024-08-23', 92, 1),
(12, '2024-08-12', 135, 1),
(13, '2024-08-13', 64, 1),
(14, '2024-08-14', 100, 1),
(15, '2024-08-15', 120, 1),
(16, '2024-08-16', 165, 1),
(17, '2024-08-05', 135, 1),
(18, '2024-08-06', 165, 1),
(19, '2024-08-07', 200, 1),
(20, '2024-08-08', 445, 1),
(21, '2024-08-09', 1, 1),
(22, '2024-06-24', 168, 1),
(23, '2024-06-24', 1, 1),
(24, '2024-06-24', 10, 1),
(25, '2024-06-24', 2, 1),
(26, '2024-06-24', 3, 1),
(27, '2024-09-16', 1, 1),
(28, '2024-09-16', 1, 1),
(29, '2024-09-16', 1, 1),
(30, '2024-09-16', 3, 1),
(31, '2024-09-16', 5, 1),
(32, '2024-09-18', 2, 1);

-- INSERTS PARA LA TABLA: estudiante_ss  
INSERT INTO `estudiante_ss` (`idestudiante_ss`, `qr_registrados`, `usuario_id`) VALUES
(4, '', 110),
(5, '', 115);

-- INSERTS PARA LA TABLA: menu  
INSERT INTO `menu` (`idmenu`, `fecha`, `tipomenu`, `descripcion`, `admin_id`) VALUES
(2, '2024-08-27', 'Almuerzo', 'pollo', 1),
(3, '2024-08-27', 'Refrigerio', 'avena', 1),
(4, '2024-08-28', 'Desayuno', 'huevos, arroz', 1),
(20, '2024-08-30', 'desayuno', 'nada', 1),
(21, '2024-09-12', 'desayuno', 'pollo', 1),
(22, '2024-09-28', 'desayuno', 'Carne', 1);
