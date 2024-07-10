-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-05-2024 a las 00:30:27
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `control_estudiante`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`id21093256_santiago`@`%` PROCEDURE `consultar_admin` (IN `_Id_Administrador` INT)   BEGIN
    SELECT * 
    FROM administrador
    WHERE Id_Administrador = _Id_Administrador;
END$$

CREATE DEFINER=`id21093256_santiago`@`%` PROCEDURE `consultar_admin2` (IN `_Id` INT)   BEGIN
    SELECT * 
    FROM administrador_comedor2
    WHERE Id = _Id;
END$$

CREATE DEFINER=`id21093256_santiago`@`%` PROCEDURE `consultar_almuerzo` (IN `_Id` INT)   BEGIN
    SELECT * 
    FROM almuerzo
    WHERE Id = _Id;
END$$

CREATE DEFINER=`id21093256_santiago`@`%` PROCEDURE `consultar_desayuno` (IN `_Id` INT)   BEGIN
    SELECT * 
    FROM desayuno
    WHERE Id = _Id;
END$$

CREATE DEFINER=`id21093256_santiago`@`%` PROCEDURE `consultar_estudiantes2` (IN `_Id_Estudiantes` INT)   BEGIN
    SELECT * 
    FROM estudiantes2
    WHERE Id_Estudiantes = _Id_Estudiantes;
END$$

CREATE DEFINER=`id21093256_santiago`@`%` PROCEDURE `consultar_refrigerio` (IN `_Id` INT)   BEGIN
    SELECT * 
    FROM refrigerio
    WHERE Id = _Id;
END$$

CREATE DEFINER=`id21093256_santiago`@`%` PROCEDURE `registraradmin` (IN `_Id_Administrador` INT, IN `_nombres` VARCHAR(200), IN `_correo` VARCHAR(200), IN `_Contrasena` VARCHAR(200), IN `_rol` VARCHAR(220), IN `_Id_comedor` VARCHAR(220), IN `_Documento` VARCHAR(220))   BEGIN
    INSERT into administrador (Id_Administrador,nombres,correo,Contrasena,rol,Id_Comedor,Documento)
     values (_Id_Administrador,_nombres,_correo,_Contrasena,_rol,_Id_comedor,_Documento);
END$$

CREATE DEFINER=`id21093256_santiago`@`%` PROCEDURE `registraradmin2` (IN `_Id` INT, IN `Nombre` VARCHAR(200), IN `_Correo` VARCHAR(200), IN `_Contrasena` VARCHAR(200), IN `_Documento` VARCHAR(220))   BEGIN
    INSERT into administrador_comedor2 (Id,Nombre,Correo,Contrasena,Documento)
     values (_Id,_Nombre,_Correo,_Contrasena,_Documento);
END$$

CREATE DEFINER=`id21093256_santiago`@`%` PROCEDURE `registraralmuerzo` (IN `_Id` INT, IN `_Frutas` VARCHAR(200), IN `_Bebidas` VARCHAR(200), IN `_Carbohidratos` VARCHAR(200), IN `_Proteinas` VARCHAR(220), IN `_Cereales` VARCHAR(220), IN `_Postre` VARCHAR(220))   BEGIN
    INSERT into almuerzo (Id,Frutas,Proteinas,Carbohidratos,Bebidas, Cereales, Postre)
     values (_Id, _Bebidas, _Carbohidratos, _Frutas,_Proteinas,_Cereales,_Postre);
END$$

CREATE DEFINER=`id21093256_santiago`@`%` PROCEDURE `registrardesayuno` (IN `_id` INT, IN `_Bebidas` VARCHAR(200), IN `_Carbohidratos` VARCHAR(200), IN `_Frutas` VARCHAR(200), IN `_Proteinas` VARCHAR(220))   BEGIN
    INSERT into desayuno (id,Frutas,Proteinas,Carbohidratos,Bebidas)
     values (_id, _Bebidas, _Carbohidratos, _Frutas,_Proteinas);
END$$

CREATE DEFINER=`id21093256_santiago`@`%` PROCEDURE `registrarrefrigerio` (IN `_Id` INT, IN `_Frutas` VARCHAR(200), IN `_Cereales` VARCHAR(200), IN `_Bebidas` VARCHAR(200), IN `_Caramelo` VARCHAR(220))   BEGIN
    INSERT into desayuno (Id,Frutas,Cereales,Bebidas,Caramelo)
     values (_Id, _Frutas, _Cereales, _Bebidas,_Caramelo);
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrador`
--

CREATE TABLE `administrador` (
  `Id_Administrador` int(11) NOT NULL,
  `Nombre` varchar(90) NOT NULL,
  `correo` varchar(200) NOT NULL,
  `Contrasena` varchar(90) NOT NULL,
  `rol` varchar(90) NOT NULL,
  `Id_Comedor` int(90) NOT NULL,
  `Documento` int(90) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administrador`
--

INSERT INTO `administrador` (`Id_Administrador`, `Nombre`, `correo`, `Contrasena`, `rol`, `Id_Comedor`, `Documento`) VALUES
(1, 'Juanita Pelaez', 'juanapelaez@gmail.com', '12345', 'Administrador', 0, 1032937602),
(2, 'Santiago Garcia', 'kevingarciago3@gmail.com', '123456', 'Programador', 0, 1032940369),
(3, 'Dana Yeraldine Arismendy ', 'dana.aris1028@gmail.com', 'laroca123', 'Programador', 0, 1028862560),
(4, 'Juan Karlos Cardenas', 'juankardenas2@gmail.com', '123456', 'Administrador', 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradorcomedor_estudiantes`
--

CREATE TABLE `administradorcomedor_estudiantes` (
  `Id_AdministradorComedor` int(15) NOT NULL,
  `Id_Estudiantes` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrador_comedor2`
--

CREATE TABLE `administrador_comedor2` (
  `Id` int(11) NOT NULL,
  `Nombre` varchar(200) NOT NULL,
  `Correo` varchar(200) NOT NULL,
  `Contrasena` varchar(200) NOT NULL,
  `Documento` int(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administrador_comedor2`
--

INSERT INTO `administrador_comedor2` (`Id`, `Nombre`, `Correo`, `Contrasena`, `Documento`) VALUES
(1, 'Pedro Gonzales', 'pedrogonzales@gmail.com', '12345', 1010222544),
(2, 'Maria Angarita', 'maria@gmail.com', '1245', 1000233654),
(3, 'Santiago Perez', 'santiago23@gmail.com', '123456', 1032955411),
(4, 'juan', 'juan@xn--gmai-jqa.com', 'cucaracha1234', 1020736727);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `almuerzo`
--

CREATE TABLE `almuerzo` (
  `Id` int(11) NOT NULL,
  `Frutas` varchar(200) NOT NULL,
  `Proteinas` varchar(200) NOT NULL,
  `Bebidas` varchar(200) NOT NULL,
  `Cereales` varchar(200) NOT NULL,
  `Postre` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `almuerzo`
--

INSERT INTO `almuerzo` (`Id`, `Frutas`, `Proteinas`, `Bebidas`, `Cereales`, `Postre`) VALUES
(2, 'Ciruela', 'Pollo', 'Jugo De Mora', 'Lenteja', 'Helado'),
(3, 'Manzana', 'Carne', 'Jugo de lulo', 'arroz', 'Ninguno');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `codigos_qr`
--

CREATE TABLE `codigos_qr` (
  `id` int(11) NOT NULL,
  `fecha_hora` datetime DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `codigos_qr`
--

INSERT INTO `codigos_qr` (`id`, `fecha_hora`, `imagen`) VALUES
(8, '2024-05-22 00:18:14', '../qr_codes/qr_all_students.png'),
(9, '2024-05-22 00:20:06', '../qr_codes/qr_all_students.png'),
(10, '2024-05-22 00:20:44', '../qr_codes/qr_all_students.png'),
(11, '2024-05-22 00:20:58', '../qr_codes/qr_all_students.png'),
(12, '2024-05-22 00:21:11', '../qr_codes/qr_all_students.png'),
(13, '2024-05-22 00:22:44', '../qr_codes/qr_all_students.png'),
(14, '2024-05-22 00:23:36', '../qr_codes/qr_all_students.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comedor`
--

CREATE TABLE `comedor` (
  `Id_Comedor` int(11) NOT NULL,
  `Descripción` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comedor`
--

INSERT INTO `comedor` (`Id_Comedor`, `Descripción`) VALUES
(1, 'comedor ciudadela bosa');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `desayuno`
--

CREATE TABLE `desayuno` (
  `Id` int(11) NOT NULL,
  `Frutas` varchar(200) NOT NULL,
  `Proteinas` varchar(220) NOT NULL,
  `Carbohidratos` varchar(200) NOT NULL,
  `Bebidas` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `desayuno`
--

INSERT INTO `desayuno` (`Id`, `Frutas`, `Proteinas`, `Carbohidratos`, `Bebidas`) VALUES
(3, 'Manzana', 'Huevo', 'Pan', 'Cafe'),
(4, 'Banano', 'Carne', 'Tortilla', 'Chocolate'),
(5, 'Manzana', 'Pollo', 'Pan', 'Chocolate'),
(6, 'huevos pericos', 'Fresa', 'Pan y Tortilla', 'Cafe'),
(7, 'Manzana', 'Huevo Frito', 'Pan', 'Agua Panela');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_comedor`
--

CREATE TABLE `detalle_comedor` (
  `Id_Detalle` int(11) NOT NULL,
  `Id_Desayuno` int(11) DEFAULT NULL,
  `Id_Almuerzo` int(11) DEFAULT NULL,
  `Id_Refrigerio` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_comedor`
--

INSERT INTO `detalle_comedor` (`Id_Detalle`, `Id_Desayuno`, `Id_Almuerzo`, `Id_Refrigerio`) VALUES
(1, 4, 3, 21),
(2, 4, 3, 23),
(3, 6, 2, 8),
(4, 6, 2, 8),
(5, 6, 2, 23),
(6, 3, 3, 21),
(7, 6, 2, 23);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE `estudiantes` (
  `Id_Estudiantes` int(11) NOT NULL,
  `Nombre` varchar(90) NOT NULL,
  `Correo` varchar(90) NOT NULL,
  `Contrasena` varchar(90) NOT NULL,
  `Id_Comedor` int(90) NOT NULL,
  `Linea_Media` varchar(90) NOT NULL,
  `Documento` int(200) NOT NULL,
  `Curso` int(200) NOT NULL,
  `Asistio` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO `estudiantes` (`Id_Estudiantes`, `Nombre`, `Correo`, `Contrasena`, `Id_Comedor`, `Linea_Media`, `Documento`, `Curso`, `Asistio`) VALUES
(1, 'Mariana Gomez', 'mariana1255@gmail.com', '12345', 1, 'Biotecnologia', 1032665844, 1004, 0),
(2, 'Jose Martinez', 'jose1233@gmail.com', '12345', 1, 'Biotecnologia', 1010223655, 1001, 0),
(3, 'Juan Karlos Cardenas', 'juan@gmail.com', '12345', 1, 'Programación de Software', 1020736727, 1102, 1),
(4, 'Yeraldine Arismendy', 'dana288@gmail.com', '12345', 1, 'Programación de Software', 1028862560, 1105, 1),
(5, 'Ashly Urrea', 'ashlyurrea2@gmail.com', '12345', 1, 'Programación de Software', 1010962999, 1104, 1),
(6, 'Kevin Garcia', 'kevingarciago3@gmail.com', '12345', 1, 'Programación de Software', 1032940369, 1102, 1),
(8, 'Juliana Gonzalez', 'juliana10@gmail.com', '123456', 1, 'Programación de Software', 1032988655, 1102, 1),
(9, 'juan', 'juan@gmail.com', 'cucaracha1234', 1, 'Programación de Software', 1020736727, 1102, 0),
(10, 'Dana Arismendy', 'popo123@gmail.com', 'popo12345', 1, 'Programación de Software', 1032382064, 1105, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `refrigerio`
--

CREATE TABLE `refrigerio` (
  `Id` int(11) NOT NULL,
  `Frutas` varchar(200) NOT NULL,
  `Cereales` varchar(200) NOT NULL,
  `Bebidas` varchar(200) NOT NULL,
  `Caramelo` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `refrigerio`
--

INSERT INTO `refrigerio` (`Id`, `Frutas`, `Cereales`, `Bebidas`, `Caramelo`) VALUES
(8, 'Pera', 'Aros de Arroz', 'yogurt melocoton', 'chocolate'),
(21, 'Manzana', 'Arroz', 'Avena', 'chocolate'),
(22, 'Banano', 'Aros De Arroz', 'yogurt melocoton', 'chocolate'),
(23, 'Manzana', 'Arroz', 'yogurt melocoton', 'chocolate');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`Id_Administrador`);

--
-- Indices de la tabla `administrador_comedor2`
--
ALTER TABLE `administrador_comedor2`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `almuerzo`
--
ALTER TABLE `almuerzo`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `codigos_qr`
--
ALTER TABLE `codigos_qr`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `comedor`
--
ALTER TABLE `comedor`
  ADD PRIMARY KEY (`Id_Comedor`);

--
-- Indices de la tabla `desayuno`
--
ALTER TABLE `desayuno`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `detalle_comedor`
--
ALTER TABLE `detalle_comedor`
  ADD PRIMARY KEY (`Id_Detalle`),
  ADD KEY `Id_Almuerzo` (`Id_Almuerzo`),
  ADD KEY `Id_Refrigerio` (`Id_Refrigerio`),
  ADD KEY `Id_Desayuno` (`Id_Desayuno`);

--
-- Indices de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD PRIMARY KEY (`Id_Estudiantes`),
  ADD KEY `Id_Comedor` (`Id_Comedor`);

--
-- Indices de la tabla `refrigerio`
--
ALTER TABLE `refrigerio`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administrador`
--
ALTER TABLE `administrador`
  MODIFY `Id_Administrador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `administrador_comedor2`
--
ALTER TABLE `administrador_comedor2`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `almuerzo`
--
ALTER TABLE `almuerzo`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `codigos_qr`
--
ALTER TABLE `codigos_qr`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `desayuno`
--
ALTER TABLE `desayuno`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `detalle_comedor`
--
ALTER TABLE `detalle_comedor`
  MODIFY `Id_Detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `Id_Estudiantes` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `refrigerio`
--
ALTER TABLE `refrigerio`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle_comedor`
--
ALTER TABLE `detalle_comedor`
  ADD CONSTRAINT `detalle_comedor_ibfk_1` FOREIGN KEY (`Id_Almuerzo`) REFERENCES `almuerzo` (`Id`),
  ADD CONSTRAINT `detalle_comedor_ibfk_2` FOREIGN KEY (`Id_Refrigerio`) REFERENCES `refrigerio` (`Id`),
  ADD CONSTRAINT `detalle_comedor_ibfk_3` FOREIGN KEY (`Id_Desayuno`) REFERENCES `desayuno` (`Id`);

--
-- Filtros para la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD CONSTRAINT `estudiantes_ibfk_1` FOREIGN KEY (`Id_Comedor`) REFERENCES `comedor` (`Id_Comedor`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
