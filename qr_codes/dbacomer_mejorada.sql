CREATE SCHEMA IF NOT EXISTS `acomer` DEFAULT CHARACTER SET utf8 ;
USE `acomer` ;

CREATE TABLE `tipo_documento` (
  `tdoc` VARCHAR(10) NOT NULL,
  `descripcion` VARCHAR(30) NOT NULL,
  `estadodoc` TINYINT NOT NULL,
  PRIMARY KEY (`tdoc`)
);

INSERT INTO `tipo_documento` (`tdoc`, `descripcion`, `estadodoc`) VALUES
('CC', 'Cédula de Ciudadanía', 1),
('TI', 'Tarjeta de Identidad', 1),
('RC', 'Registro Civil', 1),
('CE', 'Cédula de Extranjería', 1),
('PA', 'Pasaporte', 1),
('PR', 'Permiso de Residencia', 1);

CREATE TABLE `tipo_usuario` (
  `idtipo_usuario` INT NOT NULL,
  `rol` VARCHAR(30) NOT NULL,
  PRIMARY KEY (`idtipo_usuario`)
);

INSERT INTO `tipo_usuario` (`idtipo_usuario`, `rol`) VALUES
(1, 'Estudiante SS'),
(2, 'Docente'),
(3, 'Administrador');


CREATE TABLE `credenciales` (
  `idcredenciales` INT NOT NULL,
  `user` VARCHAR(20) NOT NULL,
  `password` VARCHAR(40) NOT NULL,
  `fecharegistro` DATETIME NOT NULL,
  `ultimoacceso` DATETIME NOT NULL,
  PRIMARY KEY (`idcredenciales`)
);

INSERT INTO `credenciales` (`idcredenciales`, `user`, `password`, `fecharegistro`, `ultimoacceso`) VALUES
(1, 'juanpablo', 'password123', '2024-08-27 10:00:00', '2024-08-27 15:00:00'),
(2, 'claudia', 'password456', '2024-08-27 11:00:00', '2024-08-27 16:00:00'),
(3, 'sandra', 'password789', '2024-08-27 12:00:00', '2024-08-27 17:00:00'),
(4, 'pedro', 'password321', '2024-08-27 13:00:00', '2024-08-27 18:00:00');

CREATE TABLE `usuarios` (
  `idusuarios` INT NOT NULL,
  `nombre` VARCHAR(20) NOT NULL,
  `apellido` VARCHAR(20) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `telefono` INT(10) NOT NULL,
  `direccion` VARCHAR(255) NOT NULL,
  `numerodocumento` INT(10) NOT NULL,
  `tipo_documento_tdoc` VARCHAR(10) NOT NULL,
  `tipo_usuario_idtipo_usuario` INT NOT NULL,
  `credenciales_idcredenciales` INT NOT NULL,
  `docente_iddocente` INT NOT NULL,
  PRIMARY KEY (`idusuarios`, `tipo_documento_tdoc`, `tipo_usuario_idtipo_usuario`, `credenciales_idcredenciales`, `docente_iddocente`)  
);

INSERT INTO `usuarios` (`idusuarios`, `nombre`, `apellido`, `email`, `telefono`, `direccion`, `numerodocumento`, `tipo_documento_tdoc`, `tipo_usuario_idtipo_usuario`, `credenciales_idcredenciales`, `docente_iddocente`) VALUES
(1, 'Juan Pablo', 'Rodriguez', 'juan.pablo@example.com', 3001234567, 'Calle 123', 123456789, 'CC', 2, 1, 101),
(2, 'Claudia', 'Peres', 'claudia.peres@example.com', 3002345678, 'Calle 234', 987654321, 'TI', 1, 2, NULL),
(3, 'Sandra', 'Torres', 'sandra.torres@example.com', 3003456789, 'Calle 345', 456789123, 'CC', 1, 3, NULL),
(4, 'Pedro', 'Linares', 'pedro.linares@example.com', 3004567890, 'Calle 456', 789123456, 'CC', 3, 4, NULL);

ALTER TABLE `usuarios`
ADD CONSTRAINT `fk_usuarios_tipo_documento`
    FOREIGN KEY (`tipo_documento_tdoc`)
    REFERENCES `tipo_documento` (`tdoc`);
    
ALTER TABLE `usuarios`
ADD CONSTRAINT `fk_usuarios_tipo_usuario1`
    FOREIGN KEY (`tipo_usuario_idtipo_usuario`)
    REFERENCES `tipo_usuario` (`idtipo_usuario`);

ALTER TABLE `usuarios`
ADD CONSTRAINT `fk_usuarios_credenciales1`
    FOREIGN KEY (`credenciales_idcredenciales`)
    REFERENCES `credenciales` (`idcredenciales`);
    
CREATE UNIQUE INDEX `email_UNIQUE` ON `usuarios` (`email` ASC);

CREATE UNIQUE INDEX `numerodocumento_UNIQUE` ON `usuarios` (`numerodocumento` ASC);

CREATE INDEX `fk_usuarios_tipo_documento_idx` ON `usuarios` (`tipo_documento_tdoc` ASC);

CREATE INDEX `fk_usuarios_tipo_usuario1_idx` ON `usuarios` (`tipo_usuario_idtipo_usuario` ASC);

CREATE INDEX `fk_usuarios_credenciales1_idx` ON `usuarios` (`credenciales_idcredenciales` ASC);

CREATE TABLE `menu` (
  `idmenu` INT NOT NULL,
  `fecha` DATE NOT NULL,
  `tipomenu` VARCHAR(20) NOT NULL,
  `descripcion` LONGTEXT NOT NULL,
  `usuarios_idusuarios` INT NOT NULL,
  `usuarios_tipo_documento_tdoc` VARCHAR(10) NOT NULL,
  `usuarios_tipo_usuario_idtipo_usuario` INT NOT NULL,
  `usuarios_credenciales_idcredenciales` INT NOT NULL,
  PRIMARY KEY (`idmenu`)
);

INSERT INTO `menu` (`idmenu`, `fecha`, `tipomenu`, `descripcion`, `usuarios_idusuarios`, `usuarios_tipo_documento_tdoc`, `usuarios_tipo_usuario_idtipo_usuario`, `usuarios_credenciales_idcredenciales`) VALUES
(1, '2024-08-27', 'Desayuno', 'Huevos revueltos con pan, jugo de naranja y café', 1, 'CC', 2, 1),
(2, '2024-08-27', 'Almuerzo', 'Pasta con salsa boloñesa, ensalada y agua', 2, 'TI', 1, 2),
(3, '2024-08-27', 'Refrigerio', 'Galletas y jugo de manzana', 3, 'CC', 1, 3),
(4, '2024-08-28', 'Desayuno', 'Panqueques con miel, leche y fruta fresca', 4, 'CC', 3, 4);

ALTER TABLE `menu`
ADD  CONSTRAINT `fk_menu_usuarios1`
    FOREIGN KEY (`usuarios_idusuarios` , `usuarios_tipo_documento_tdoc` , `usuarios_tipo_usuario_idtipo_usuario` , `usuarios_credenciales_idcredenciales` , `usuarios_docente_iddocente`)
    REFERENCES `usuarios` (`idusuarios` , `tipo_documento_tdoc` , `tipo_usuario_idtipo_usuario` , `credenciales_idcredenciales` , `docente_iddocente`);

CREATE INDEX `fk_menu_usuarios1_idx` ON `menu` (`usuarios_idusuarios` ASC, `usuarios_tipo_documento_tdoc` ASC, `usuarios_tipo_usuario_idtipo_usuario` ASC, `usuarios_credenciales_idcredenciales` ASC, `usuarios_docente_iddocente` ASC);

CREATE TABLE `docente` (
  `iddocente` INT NOT NULL,
  `usuarios_idusuarios` INT NOT NULL,
  `usuarios_tipo_documento_tdoc` VARCHAR(10) NOT NULL,
  `usuarios_tipo_usuario_idtipo_usuario` INT NOT NULL,
  `usuarios_credenciales_idcredenciales` INT NOT NULL,
  `usuarios_docente_iddocente` INT NOT NULL,
  PRIMARY KEY (`iddocente`, `usuarios_idusuarios`, `usuarios_tipo_documento_tdoc`, `usuarios_tipo_usuario_idtipo_usuario`, `usuarios_credenciales_idcredenciales`, `usuarios_docente_iddocente`)
);

INSERT INTO `docente` (`iddocente`, `usuarios_idusuarios`, `usuarios_tipo_documento_tdoc`, `usuarios_tipo_usuario_idtipo_usuario`, `usuarios_credenciales_idcredenciales`, `usuarios_docente_iddocente`) VALUES
(1, 1, 'CC', 2, 1, 101),
(2, 2, 'TI', 1, 2, NULL),
(3, 3, 'CC', 1, 3, NULL),
(4, 4, 'CC', 3, 4, NULL);

ALTER TABLE `docente`
ADD CONSTRAINT `fk_docente_usuarios1`
    FOREIGN KEY (`usuarios_idusuarios` , `usuarios_tipo_documento_tdoc` , `usuarios_tipo_usuario_idtipo_usuario` , `usuarios_credenciales_idcredenciales` , `usuarios_docente_iddocente`)
    REFERENCES `usuarios` (`idusuarios` , `tipo_documento_tdoc` , `tipo_usuario_idtipo_usuario` , `credenciales_idcredenciales` , `docente_iddocente`);

CREATE INDEX `fk_docente_usuarios1_idx` ON `docente` (`usuarios_idusuarios` ASC, `usuarios_tipo_documento_tdoc` ASC, `usuarios_tipo_usuario_idtipo_usuario` ASC, `usuarios_credenciales_idcredenciales` ASC, `usuarios_docente_iddocente` ASC);

CREATE TABLE `docentealumnos` (
  `iddocentealumnos` INT NOT NULL,
  `docente_iddocente` INT NOT NULL,
  `docente_usuarios_idusuarios` INT NOT NULL,
  `docente_usuarios_tipo_documento_tdoc` VARCHAR(10) NOT NULL,
  `docente_usuarios_tipo_usuario_idtipo_usuario` INT NOT NULL,
  `docente_usuarios_credenciales_idcredenciales` INT NOT NULL,
  `docente_usuarios_docente_iddocente` INT NOT NULL,
  PRIMARY KEY (`iddocentealumnos`)  
);

ALTER TABLE `docentealumnos`
ADD CONSTRAINT `fk_docentealumnos_docente1`
    FOREIGN KEY (`docente_iddocente` , `docente_usuarios_idusuarios` , `docente_usuarios_tipo_documento_tdoc` , `docente_usuarios_tipo_usuario_idtipo_usuario` , `docente_usuarios_credenciales_idcredenciales` , `docente_usuarios_docente_iddocente`)
    REFERENCES`docente` (`iddocente` , `usuarios_idusuarios` , `usuarios_tipo_documento_tdoc` , `usuarios_tipo_usuario_idtipo_usuario` , `usuarios_credenciales_idcredenciales` , `usuarios_docente_iddocente`);

CREATE INDEX `fk_docentealumnos_docente1_idx` ON `docentealumnos` (`docente_iddocente` ASC, `docente_usuarios_idusuarios` ASC, `docente_usuarios_tipo_documento_tdoc` ASC, `docente_usuarios_tipo_usuario_idtipo_usuario` ASC, `docente_usuarios_credenciales_idcredenciales` ASC, `docente_usuarios_docente_iddocente` ASC);

CREATE TABLE `qrgenerados` (
  `idqrgenerados` INT NOT NULL,
  `codigoqr` VARCHAR(255) NOT NULL,
  `fechageneracion` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idqrgenerados`)
);

INSERT INTO `qrgenerados` (`idqrgenerados`, `codigoqr`, `fechageneracion`) VALUES
(22, '../qr_codes/qr_all_students_1724296178.png', '2024-08-27 10:00:00'),
(23, '../qr_codes/qr_all_students_1724296237.png', '2024-08-27 11:30:00'),
(24, '../qr_codes/qr_all_students_1724296591.png', '2024-08-27 13:15:00');

CREATE UNIQUE INDEX `codigoqr_UNIQUE` ON `qrgenerados` (`codigoqr` ASC);

CREATE TABLE `asistencia` (
  `idasistencia` INT NOT NULL,
  `fecha` DATE NOT NULL,
  `estado` TINYINT NOT NULL,
  `registradopor` INT NOT NULL,
  `qrgenerados_idqrgenerados` INT NOT NULL,
  `usuarios_idusuarios` INT NOT NULL,
  `usuarios_tipo_documento_tdoc` VARCHAR(10) NOT NULL,
  `usuarios_tipo_usuario_idtipo_usuario` INT NOT NULL,
  `usuarios_credenciales_idcredenciales` INT NOT NULL,
  `usuarios_docente_iddocente` INT NOT NULL,
  PRIMARY KEY (`idasistencia`)
);

INSERT INTO `asistencia` (`idasistencia`,`fecha`, `estado`, `registradopor`, `qrgenerados_idqrgenerados`, `usuarios_idusuarios`, `usuarios_tipo_documento_tdoc`, `usuarios_tipo_usuario_idtipo_usuario`, `usuarios_credenciales_idcredenciales`, `usuarios_docente_iddocente`) VALUES
(1,'2024-08-27', 1, 101, 22, 1, 'CC', 2, 1, 101),
(2,'2024-08-27', 0, 102, 23, 2, 'TI', 1, 2, NULL),
(3,'2024-08-27', 1, 103, 24, 3, 'CC', 1, 3, NULL);

ALTER TABLE `asistencia`
ADD CONSTRAINT `fk_asistencia_qrgenerados1`
    FOREIGN KEY (`qrgenerados_idqrgenerados`)
    REFERENCES `qrgenerados` (`idqrgenerados`);
    
ALTER TABLE `asistencia`
ADD CONSTRAINT `fk_asistencia_usuarios1`
    FOREIGN KEY (`usuarios_idusuarios` , `usuarios_tipo_documento_tdoc` , `usuarios_tipo_usuario_idtipo_usuario` , `usuarios_credenciales_idcredenciales` , `usuarios_docente_iddocente`)
    REFERENCES `usuarios` (`idusuarios` , `tipo_documento_tdoc` , `tipo_usuario_idtipo_usuario` , `credenciales_idcredenciales` , `docente_iddocente`);

CREATE INDEX `fk_asistencia_qrgenerados1_idx` ON `asistencia` (`qrgenerados_idqrgenerados` ASC);

CREATE INDEX `fk_asistencia_usuarios1_idx` ON `asistencia` (`usuarios_idusuarios` ASC, `usuarios_tipo_documento_tdoc` ASC, `usuarios_tipo_usuario_idtipo_usuario` ASC, `usuarios_credenciales_idcredenciales` ASC, `usuarios_docente_iddocente` ASC);

CREATE TABLE `cursos` (
  `idcursos` INT NOT NULL,
  `nombrecurso` VARCHAR(20) NOT NULL,
  `docente_iddocente` INT NOT NULL,
  `docente_usuarios_idusuarios` INT NOT NULL,
  `docente_usuarios_tipo_documento_tdoc` VARCHAR(10) NOT NULL,
  `docente_usuarios_tipo_usuario_idtipo_usuario` INT NOT NULL,
  `docente_usuarios_credenciales_idcredenciales` INT NOT NULL,
  `docente_usuarios_docente_iddocente` INT NOT NULL,
  `asistencia_idasistencia` INT NOT NULL,
  `qrgenerados_idqrgenerados` INT NOT NULL,
  PRIMARY KEY (`idcursos`) 
);

INSERT INTO `cursos` (`idcursos`,`nombrecurso`, `docente_iddocente`, `docente_usuarios_idusuarios`, `docente_usuarios_tipo_documento_tdoc`, `docente_usuarios_tipo_usuario_idtipo_usuario`, `docente_usuarios_credenciales_idcredenciales`, `docente_usuarios_docente_iddocente`, `asistencia_idasistencia`, `qrgenerados_idqrgenerados`) VALUES
(1,'Curso 901', 1, 1, 'CC', 2, 1, 101, 1, 22),
(2,'Curso 902', 2, 2, 'TI', 1, 2, NULL, 2, 23),
(3,'Curso 1103', 3, 3, 'CC', 1, 3, NULL, 3, 24);

ALTER TABLE `cursos`
ADD CONSTRAINT `fk_cursos_docente1`
    FOREIGN KEY (`docente_iddocente` , `docente_usuarios_idusuarios` , `docente_usuarios_tipo_documento_tdoc` , `docente_usuarios_tipo_usuario_idtipo_usuario` , `docente_usuarios_credenciales_idcredenciales` , `docente_usuarios_docente_iddocente`)
    REFERENCES `docente` (`iddocente` , `usuarios_idusuarios` , `usuarios_tipo_documento_tdoc` , `usuarios_tipo_usuario_idtipo_usuario` , `usuarios_credenciales_idcredenciales` , `usuarios_docente_iddocente`);
    
ALTER TABLE `cursos`
ADD CONSTRAINT `fk_cursos_asistencia1`
    FOREIGN KEY (`asistencia_idasistencia`)
    REFERENCES `asistencia` (`idasistencia`);
 
ALTER TABLE `cursos`
ADD CONSTRAINT `fk_cursos_qrgenerados1`
    FOREIGN KEY (`qrgenerados_idqrgenerados`)
    REFERENCES `qrgenerados` (`idqrgenerados`);

CREATE INDEX `fk_cursos_docente1_idx` ON `cursos` (`docente_iddocente` ASC, `docente_usuarios_idusuarios` ASC, `docente_usuarios_tipo_documento_tdoc` ASC, `docente_usuarios_tipo_usuario_idtipo_usuario` ASC, `docente_usuarios_credenciales_idcredenciales` ASC, `docente_usuarios_docente_iddocente` ASC);

CREATE INDEX `fk_cursos_asistencia1_idx` ON `cursos` (`asistencia_idasistencia` ASC);

CREATE INDEX `fk_cursos_qrgenerados1_idx` ON `cursos` (`qrgenerados_idqrgenerados` ASC);

CREATE TABLE `alumnos` (
  `idalumnos` INT NOT NULL,
  `nombre` VARCHAR(20) NOT NULL,
  `apellido` VARCHAR(20) NOT NULL,
  `docentealumnos_iddocentealumnos` INT NOT NULL,
  `cursos_idcursos` INT NOT NULL,
  PRIMARY KEY (`idalumnos`)  
);

INSERT INTO `alumnos` (`nombre`, `apellido`, `docentealumnos_iddocentealumnos`, `cursos_idcursos`) VALUES
('Juan', 'Pérez', 1, 1),
('Ana', 'Gómez', 1, 2),
('Luis', 'Martínez', 1, 3);


ALTER TABLE `alumnos`
ADD CONSTRAINT `fk_alumnos_docentealumnos1`
    FOREIGN KEY (`docentealumnos_iddocentealumnos`)
    REFERENCES `docentealumnos` (`iddocentealumnos`);
    
ALTER TABLE `alumnos`
ADD CONSTRAINT `fk_alumnos_cursos1`
    FOREIGN KEY (`cursos_idcursos`)
    REFERENCES `cursos` (`idcursos`);

CREATE INDEX `fk_alumnos_docentealumnos1_idx` ON `alumnos` (`docentealumnos_iddocentealumnos` ASC);

CREATE INDEX `fk_alumnos_cursos1_idx` ON `alumnos` (`cursos_idcursos` ASC);

CREATE TABLE `estadisticasqr` (
  `idestadisticasqr` INT NOT NULL,
  `fecha` DATE NOT NULL,
  `estudiantesqasistieron` INT NOT NULL,
  PRIMARY KEY (`idestadisticasqr`)
);

INSERT INTO `estadisticasqr` (`fecha`, `estudiantesqasistieron`) VALUES
('2024-08-27', 45),
('2024-08-26', 30),
('2024-08-25', 55),
('2024-08-24', 40);


CREATE TABLE `admin` (
  `idadmin` INT NOT NULL,
  `usuarios_idusuarios` INT NOT NULL,
  `usuarios_tipo_documento_tdoc` VARCHAR(10) NOT NULL,
  `usuarios_tipo_usuario_idtipo_usuario` INT NOT NULL,
  `usuarios_credenciales_idcredenciales` INT NOT NULL,
  `usuarios_docente_iddocente` INT NOT NULL,
  `estadisticasqr_idestadisticasqr` INT NOT NULL,
  PRIMARY KEY (`idadmin`, `usuarios_idusuarios`, `usuarios_tipo_documento_tdoc`, `usuarios_tipo_usuario_idtipo_usuario`, `usuarios_credenciales_idcredenciales`, `usuarios_docente_iddocente`)  
);

INSERT INTO `admin` (`usuarios_idusuarios`, `usuarios_tipo_documento_tdoc`, `usuarios_tipo_usuario_idtipo_usuario`, `usuarios_credenciales_idcredenciales`, `usuarios_docente_iddocente`, `estadisticasqr_idestadisticasqr`) VALUES
(1, 'CC', 2, 1, 101, 1),
(2, 'TI', 1, 2, NULL, 2),
(3, 'CC', 3, 3, NULL, 3),
(4, 'CC', 1, 4, NULL, 4);

ALTER TABLE `admin`
ADD CONSTRAINT `fk_admin_usuarios1`
    FOREIGN KEY (`usuarios_idusuarios` , `usuarios_tipo_documento_tdoc` , `usuarios_tipo_usuario_idtipo_usuario` , `usuarios_credenciales_idcredenciales` , `usuarios_docente_iddocente`)
    REFERENCES `usuarios` (`idusuarios` , `tipo_documento_tdoc` , `tipo_usuario_idtipo_usuario` , `credenciales_idcredenciales` , `docente_iddocente`);

ALTER TABLE `admin`
ADD CONSTRAINT `fk_admin_estadisticasqr1`
    FOREIGN KEY (`estadisticasqr_idestadisticasqr`)
    REFERENCES `estadisticasqr` (`idestadisticasqr`);

CREATE INDEX `fk_admin_usuarios1_idx` ON `admin` (`usuarios_idusuarios` ASC, `usuarios_tipo_documento_tdoc` ASC, `usuarios_tipo_usuario_idtipo_usuario` ASC, `usuarios_credenciales_idcredenciales` ASC, `usuarios_docente_iddocente` ASC);

CREATE INDEX `fk_admin_estadisticasqr1_idx` ON `admin` (`estadisticasqr_idestadisticasqr` ASC);

CREATE TABLE `estudiante_ss` (
  `idestudiante_ss` INT NOT NULL,
  `qr_registrados` TEXT NOT NULL,
  `usuarios_idusuarios` INT NOT NULL,
  `usuarios_tipo_documento_tdoc` VARCHAR(10) NOT NULL,
  `usuarios_tipo_usuario_idtipo_usuario` INT NOT NULL,
  `usuarios_credenciales_idcredenciales` INT NOT NULL,
  `usuarios_docente_iddocente` INT NOT NULL,
  PRIMARY KEY (`idestudiante_ss`, `usuarios_idusuarios`, `usuarios_tipo_documento_tdoc`, `usuarios_tipo_usuario_idtipo_usuario`, `usuarios_credenciales_idcredenciales`, `usuarios_docente_iddocente`)
);

INSERT INTO `estudiante_ss` (`qr_registrados`, `usuarios_idusuarios`, `usuarios_tipo_documento_tdoc`, `usuarios_tipo_usuario_idtipo_usuario`, `usuarios_credenciales_idcredenciales`, `usuarios_docente_iddocente`) VALUES
('../qr_codes/qr_all_students_1724296178.png', 1, 'CC', 2, 1, 101),
('../qr_codes/qr_all_students_1724296237.png', 2, 'TI', 1, 2, NULL),
('../qr_codes/qr_all_students_1724296591.png', 3, 'CC', 1, 3, NULL);

ALTER TABLE `estudiante_ss`
ADD CONSTRAINT `fk_estudiante_ss_usuarios1`
    FOREIGN KEY (`usuarios_idusuarios` , `usuarios_tipo_documento_tdoc` , `usuarios_tipo_usuario_idtipo_usuario` , `usuarios_credenciales_idcredenciales` , `usuarios_docente_iddocente`)
    REFERENCES `usuarios` (`idusuarios` , `tipo_documento_tdoc` , `tipo_usuario_idtipo_usuario` , `credenciales_idcredenciales` , `docente_iddocente`);

CREATE INDEX `fk_estudiante_ss_usuarios1_idx` ON `estudiante_ss` (`usuarios_idusuarios` ASC, `usuarios_tipo_documento_tdoc` ASC, `usuarios_tipo_usuario_idtipo_usuario` ASC, `usuarios_credenciales_idcredenciales` ASC, `usuarios_docente_iddocente` ASC);

CREATE TABLE `qrescaneados` (
  `idqrescaneados` INT NOT NULL,
  `fecha_escaneo` DATETIME NOT NULL,
  `estudiante_ss_idestudiante_ss` INT NOT NULL,
  `estudiante_ss_usuarios_idusuarios` INT NOT NULL,
  `estudiante_ss_usuarios_tipo_documento_tdoc` VARCHAR(10) NOT NULL,
  `estudiante_ss_usuarios_tipo_usuario_idtipo_usuario` INT NOT NULL,
  `estudiante_ss_usuarios_credenciales_idcredenciales` INT NOT NULL,
  `estudiante_ss_usuarios_docente_iddocente` INT NOT NULL,
  PRIMARY KEY (`idqrescaneados`)
);

  INSERT INTO `qrescaneados` (`fecha_escaneo`, `estudiante_ss_idestudiante_ss`, `estudiante_ss_usuarios_idusuarios`, `estudiante_ss_usuarios_tipo_documento_tdoc`, `estudiante_ss_usuarios_tipo_usuario_idtipo_usuario`, `estudiante_ss_usuarios_credenciales_idcredenciales`, `estudiante_ss_usuarios_docente_iddocente`) VALUES
  ('2024-08-27 08:30:00', 1, 1, 'CC', 2, 1, 101),
  ('2024-08-27 09:15:00', 2, 2, 'TI', 1, 2, NULL),
  ('2024-08-27 10:00:00', 3, 3, 'CC', 1, 3, NULL);


ALTER TABLE `qrescaneados`
ADD CONSTRAINT `fk_qrescaneados_estudiante_ss1`
    FOREIGN KEY (`estudiante_ss_idestudiante_ss` , `estudiante_ss_usuarios_idusuarios` , `estudiante_ss_usuarios_tipo_documento_tdoc` , `estudiante_ss_usuarios_tipo_usuario_idtipo_usuario` , `estudiante_ss_usuarios_credenciales_idcredenciales` , `estudiante_ss_usuarios_docente_iddocente`)
    REFERENCES `estudiante_ss` (`idestudiante_ss` , `usuarios_idusuarios` , `usuarios_tipo_documento_tdoc` , `usuarios_tipo_usuario_idtipo_usuario` , `usuarios_credenciales_idcredenciales` , `usuarios_docente_iddocente`);

CREATE INDEX `fk_qrescaneados_estudiante_ss1_idx` ON `qrescaneados` (`estudiante_ss_idestudiante_ss` ASC, `estudiante_ss_usuarios_idusuarios` ASC, `estudiante_ss_usuarios_tipo_documento_tdoc` ASC, `estudiante_ss_usuarios_tipo_usuario_idtipo_usuario` ASC, `estudiante_ss_usuarios_credenciales_idcredenciales` ASC, `estudiante_ss_usuarios_docente_iddocente` ASC);
