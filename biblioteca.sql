-- phpMyAdmin SQL Dump
-- version 5.2.1deb1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 05, 2025 at 10:13 AM
-- Server version: 10.6.10-MariaDB-1+b1
-- PHP Version: 8.2.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `biblioteca`
--

-- --------------------------------------------------------

--
-- Table structure for table `autores`
--

CREATE TABLE `autores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `autores`
--

INSERT INTO `autores` (`id`, `nombre`) VALUES
(1, 'Roxane Van Iperen'),
(2, 'Brandon Sanderson'),
(3, 'Dolores Redondo'),
(4, 'Elísabet Benavent'),
(5, 'Alice Kellen');

-- --------------------------------------------------------

--
-- Table structure for table `bibliotecas`
--

CREATE TABLE `bibliotecas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `estado` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bibliotecas`
--

INSERT INTO `bibliotecas` (`id`, `nombre`, `estado`) VALUES
(1, 'Rafael\nGarcía Herreros', '1'),
(2, 'Diego Jaramillo', '1');

-- --------------------------------------------------------

--
-- Table structure for table `biblioteca_seccion`
--

CREATE TABLE `biblioteca_seccion` (
  `id` int(11) NOT NULL,
  `biblioteca_id` int(11) NOT NULL,
  `seccion_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `biblioteca_seccion`
--

INSERT INTO `biblioteca_seccion` (`id`, `biblioteca_id`, `seccion_id`) VALUES
(1, 2, 2),
(2, 2, 1),
(3, 1, 2),
(4, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `configuracion`
--

CREATE TABLE `configuracion` (
  `configuracion_id` int(11) NOT NULL,
  `razonsocial` varchar(150) COLLATE latin1_spanish_ci DEFAULT '0',
  `direccion` varchar(100) COLLATE latin1_spanish_ci NOT NULL DEFAULT 'Ninguna',
  `nit` varchar(25) COLLATE latin1_spanish_ci NOT NULL DEFAULT '0',
  `telefono` varchar(30) COLLATE latin1_spanish_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Dumping data for table `configuracion`
--

INSERT INTO `configuracion` (`configuracion_id`, `razonsocial`, `direccion`, `nit`, `telefono`) VALUES
(1, 'CORPORACIÓN UNIVERSITARIA MINUTO DE DIOS', 'pruebas', '800.116.217-2', '3176683062');

-- --------------------------------------------------------

--
-- Table structure for table `materiales`
--

CREATE TABLE `materiales` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `isbn` varchar(20) DEFAULT NULL,
  `issn` varchar(20) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `codigo_referencia` varchar(50) DEFAULT NULL,
  `cantidad` int(11) DEFAULT 1,
  `valor_monetario` decimal(10,0) DEFAULT NULL,
  `seccion_id` int(11) DEFAULT NULL,
  `tipo_material_id` int(11) DEFAULT NULL,
  `biblioteca_id` int(11) NOT NULL,
  `estado` char(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `materiales`
--

INSERT INTO `materiales` (`id`, `titulo`, `isbn`, `issn`, `descripcion`, `codigo_referencia`, `cantidad`, `valor_monetario`, `seccion_id`, `tipo_material_id`, `biblioteca_id`, `estado`) VALUES
(1, 'Las hermanas de Auschwitz', '1', '1', 'La inolvidable historia de dos heroínas anónimas que se enfrentaron a los nazis y ayudaron a salvar decenas de vidas', '123456', 1, 5000, 1, 1, 1, '1'),
(2, 'LUCKY BOY', '9780735212275', '97807352122753', 'TWO WOMEN. TWO POSSIBLE FUTURES. ONE LUCKY BOY', 'DDd', 10, 90000000, 1, 1, 1, '1');

-- --------------------------------------------------------

--
-- Table structure for table `material_autor`
--

CREATE TABLE `material_autor` (
  `id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `autor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `material_autor`
--

INSERT INTO `material_autor` (`id`, `material_id`, `autor_id`) VALUES
(20, 2, 4),
(21, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `modulos`
--

CREATE TABLE `modulos` (
  `modulo_id` int(11) NOT NULL,
  `modulo` varchar(100) NOT NULL,
  `nombrevista` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `modulos`
--

INSERT INTO `modulos` (`modulo_id`, `modulo`, `nombrevista`) VALUES
(1, 'inventario', 'vistas/inventarios.php'),
(2, 'usuarios', 'vistas/usuarios.php'),
(3, 'configuracion', 'vistas/configuracion.php');

-- --------------------------------------------------------

--
-- Table structure for table `modulosxrol`
--

CREATE TABLE `modulosxrol` (
  `moduloxrol_id` int(11) NOT NULL,
  `modulo_id` int(11) NOT NULL,
  `rol_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `modulosxrol`
--

INSERT INTO `modulosxrol` (`moduloxrol_id`, `modulo_id`, `rol_id`) VALUES
(1, 2, 1),
(2, 1, 1),
(3, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `rol_id` int(11) NOT NULL,
  `rol` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`rol_id`, `rol`) VALUES
(1, 'Administrador');

-- --------------------------------------------------------

--
-- Table structure for table `secciones`
--

CREATE TABLE `secciones` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `secciones`
--

INSERT INTO `secciones` (`id`, `nombre`) VALUES
(1, 'Historia'),
(2, 'Ciencias Sociales');

-- --------------------------------------------------------

--
-- Table structure for table `tipos_material`
--

CREATE TABLE `tipos_material` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tipos_material`
--

INSERT INTO `tipos_material` (`id`, `nombre`) VALUES
(3, 'Articulo'),
(1, 'Libro'),
(2, 'Revista');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `usuario_id` int(11) NOT NULL,
  `usuario` varchar(35) NOT NULL,
  `contrasena` varchar(100) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `rol_id` int(11) NOT NULL,
  `estado` char(1) NOT NULL DEFAULT '1',
  `biblioteca_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`usuario_id`, `usuario`, `contrasena`, `nombres`, `apellidos`, `rol_id`, `estado`, `biblioteca_id`) VALUES
(1, 'bibliotecario1', 'f7c3bc1d808e04732adf679965ccc34ca7ae3441', 'Bibliotecario', 'Uno', 1, '1', 1),
(2, 'bibliotecario2', 'f7c3bc1d808e04732adf679965ccc34ca7ae3441', 'Bibliotecario', 'Dos', 1, '1', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `autores`
--
ALTER TABLE `autores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bibliotecas`
--
ALTER TABLE `bibliotecas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `biblioteca_seccion`
--
ALTER TABLE `biblioteca_seccion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `biblioteca_id` (`biblioteca_id`),
  ADD KEY `seccion_id` (`seccion_id`);

--
-- Indexes for table `configuracion`
--
ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`configuracion_id`);

--
-- Indexes for table `materiales`
--
ALTER TABLE `materiales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_referencia` (`codigo_referencia`),
  ADD KEY `seccion_id` (`seccion_id`),
  ADD KEY `biblioteca_id` (`biblioteca_id`),
  ADD KEY `tipo_material_id` (`tipo_material_id`);

--
-- Indexes for table `material_autor`
--
ALTER TABLE `material_autor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `material_id` (`material_id`),
  ADD KEY `autor_id` (`autor_id`);

--
-- Indexes for table `modulos`
--
ALTER TABLE `modulos`
  ADD PRIMARY KEY (`modulo_id`);

--
-- Indexes for table `modulosxrol`
--
ALTER TABLE `modulosxrol`
  ADD PRIMARY KEY (`moduloxrol_id`),
  ADD KEY `modulosxrol_ibfk_1` (`modulo_id`),
  ADD KEY `modulosxrol_ibfk_2` (`rol_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`rol_id`);

--
-- Indexes for table `secciones`
--
ALTER TABLE `secciones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tipos_material`
--
ALTER TABLE `tipos_material`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`usuario_id`),
  ADD KEY `biblioteca_id` (`biblioteca_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `autores`
--
ALTER TABLE `autores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `bibliotecas`
--
ALTER TABLE `bibliotecas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `biblioteca_seccion`
--
ALTER TABLE `biblioteca_seccion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `configuracion_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `materiales`
--
ALTER TABLE `materiales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `material_autor`
--
ALTER TABLE `material_autor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `modulosxrol`
--
ALTER TABLE `modulosxrol`
  MODIFY `moduloxrol_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `rol_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `secciones`
--
ALTER TABLE `secciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tipos_material`
--
ALTER TABLE `tipos_material`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `usuario_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `biblioteca_seccion`
--
ALTER TABLE `biblioteca_seccion`
  ADD CONSTRAINT `biblioteca_seccion_ibfk_1` FOREIGN KEY (`biblioteca_id`) REFERENCES `bibliotecas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `biblioteca_seccion_ibfk_2` FOREIGN KEY (`seccion_id`) REFERENCES `secciones` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `materiales`
--
ALTER TABLE `materiales`
  ADD CONSTRAINT `materiales_ibfk_1` FOREIGN KEY (`seccion_id`) REFERENCES `secciones` (`id`),
  ADD CONSTRAINT `materiales_ibfk_2` FOREIGN KEY (`biblioteca_id`) REFERENCES `bibliotecas` (`id`),
  ADD CONSTRAINT `materiales_ibfk_3` FOREIGN KEY (`tipo_material_id`) REFERENCES `tipos_material` (`id`);

--
-- Constraints for table `material_autor`
--
ALTER TABLE `material_autor`
  ADD CONSTRAINT `material_autor_ibfk_1` FOREIGN KEY (`material_id`) REFERENCES `materiales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `material_autor_ibfk_2` FOREIGN KEY (`autor_id`) REFERENCES `autores` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `modulosxrol`
--
ALTER TABLE `modulosxrol`
  ADD CONSTRAINT `modulosxrol_ibfk_1` FOREIGN KEY (`modulo_id`) REFERENCES `modulos` (`modulo_id`),
  ADD CONSTRAINT `modulosxrol_ibfk_2` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`rol_id`);

--
-- Constraints for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`biblioteca_id`) REFERENCES `bibliotecas` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
