-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-11-2025 a las 21:50:47
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
-- Base de datos: `registro_usuarios`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publicaciones`
--

CREATE TABLE `publicaciones` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `imagen` varchar(500) DEFAULT NULL,
  `fecha` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `publicaciones`
--

INSERT INTO `publicaciones` (`id`, `titulo`, `descripcion`, `imagen`, `fecha`, `created_at`, `updated_at`) VALUES
(2, 'Descuento especial para grupos', 'Reserva para grupos de 6 o más personas y obtén un 15% de descuento en cualquiera de nuestros tours. ¡No te lo pierdas!', NULL, '2026-01-16', '2025-11-04 15:55:26', '2025-11-04 15:57:29'),
(5, 'Descuento especial para grupos', 'Reserva para grupos de 6 o más personas y obtén un 15% de descuento en cualquiera de nuestros tours. ¡No te lo pierdas!', 'img/publicaciones/pub_1762893197_69139d8d669c6.png', '2026-07-24', '2025-11-04 15:55:39', '2025-11-11 20:33:17'),
(6, 'Nuevo tour: En Anchorena', 'Presentamos nuestro nuevo tour al atardecer. Disfruta de las vistas más espectaculares mientras navegas por el río. Incluye bebidas y snacks.', NULL, '2025-12-31', '2025-11-04 15:55:39', '2025-11-04 15:57:53');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `tour` varchar(100) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `cantidad_personas` int(11) NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`id`, `id_usuario`, `tour`, `fecha`, `hora`, `cantidad_personas`, `creado_en`) VALUES
(47, 12, 'Riachuelo', '2025-10-25', '11:00:00', 16, '2025-10-23 17:56:48'),
(48, 12, 'Anchorena', '2025-10-26', '13:30:00', 6, '2025-10-23 18:27:27'),
(49, 15, 'Navegación Bahía', '2025-10-28', '13:30:00', 12, '2025-10-27 20:04:18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `gmail` varchar(100) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol` enum('usuario','admin') DEFAULT 'usuario',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `foto_perfil` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `gmail`, `usuario`, `contrasena`, `rol`, `fecha_registro`, `foto_perfil`) VALUES
(5, 'Bauty', 'Diaz', 'diazbautista95@gmail.com', 'bauty123', '$2y$10$RnaltxVbe9tpuD/BHaRz7OPZLNeXG.SYJ6Z2wds2nV7EIKzgQXTIG', 'admin', '2025-09-02 17:25:09', NULL),
(6, 'Bauty', 'Mmm', 'msaoafbadcna@gmail.com', 'Nobody', '$2y$10$KE084BbBHgMIGvqjjoTg6e0HC9TFvH7TWZ23GuCxkflDAHIwEtova', 'admin', '2025-09-04 16:12:15', NULL),
(7, 'kevin bastos', 'el chupa pene', 'kevinbastoschupapenesprofesional@gmail.com', 'kavin el chupa pene', '$2y$10$XsNA/mOk8SbvPhYOvcqu4unsi3xL2YIRyg8atN/9.AI6dnh52JtK.', 'usuario', '2025-09-05 21:04:47', NULL),
(8, 'sajdasjidsji', 'jbjasdjdas', 'lkjhg@gmail.com', 'dsada', '$2y$10$Vuzs/fohyETR2pd45EzJsusa3i8.80fVz14aRGOQKcCNwzhdUih/K', 'usuario', '2025-10-09 20:04:36', NULL),
(9, 'papapapapa', 'papapapapapa', 'papapapap@gmail.com', 'papapa', '$2y$10$phRl2Iv/xCp6LdwsVqFqi.579jy9XF4ksTp6dt80iro59GwAklVhu', 'usuario', '2025-10-09 20:05:10', NULL),
(10, 'papa', 'papapapa', 'papa@gmail.com', 'papa', '$2y$10$IOXCsIMzE8ininJ8Zex9aOxvJe5UwJAks1ZQAj9/Ab..RAfPE0ODC', 'usuario', '2025-10-16 16:39:06', NULL),
(11, 'ppas', 'aspdas', 'diazbautista000@gmail.com', 'Bauty1234', '$2y$10$CJYIKUHmhGN0bmgInj.skePzsW4lEVkIWZI7sf1BliEW4kFkeBKXq', 'usuario', '2025-10-23 15:47:58', NULL),
(12, 'Bautista', 'Diaz', 'diazbautista00000@gmail.com', 'Bauty12345', '$2y$10$u3UFbQ2Sc6D5XHgsXcjZU.l2iEu2Z0wjf1VmvPmmnvhoVR85AJe4W', 'usuario', '2025-10-23 16:53:39', NULL),
(13, 'Bautista', 'Diaz', 'diazbautista1@gmail.com', 'Bauty12346', '$2y$10$eVERTtHDXJJ8s8LImm5qM.7/txDCWmyAYOnDNu2U8DOWxSg6qyiY.', 'usuario', '2025-10-23 17:55:56', NULL),
(14, 'Bautista', 'Diaz', 'diazbautista555@gmail.com', 'Bauty12347', '$2y$10$4hQ/8UpCFO6xMXP5UeDIT.5srRNHe1C0uL.tVa66wpRPt3hI.JMkC', 'usuario', '2025-10-23 18:23:43', NULL),
(15, 'Bauty', 'D', 'bautyd999@gmail.com', 'Bautista', '$2y$10$TPzqsE4DFeJJ.Zfg7bl09e5nNOf3P4QnymkqLI6LRsSP5tdsJnwQ6', 'usuario', '2025-10-27 20:03:49', NULL),
(16, 'bau', 'd', 'wildcrow49@gmail.com', 'bauty', '$2y$10$7PGysp6X6.U8IInPjp4exOcJoYRFCUvpKM4LBkT4pbb2fDo6QAUOW', 'usuario', '2025-11-04 16:07:21', NULL),
(17, 'Liones', 'Messii', 'emilianoantuez13@gmail.com', 'iii', '$2y$10$ijj3Oz0Cv2qRSfe8QizYruBYGPb.6I45Ua/XqSzhh2V697pcPA8oW', 'usuario', '2025-11-04 20:15:16', 'img/perfiles/1762287316_690a5ed48db63.webp'),
(18, 'Luka', 'Rosberg', 'axel.alvarez2k4@gmail.com', 'axelll', '$2y$10$57fAwhTR/WjAjxobBJ1osuC/xMuXcnGTqI8yyQU8deg.XDSoo6Amm', 'usuario', '2025-11-05 18:37:47', 'img/perfiles/1762367867_690b997bd3d71.PNG');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `publicaciones`
--
ALTER TABLE `publicaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `gmail` (`gmail`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `publicaciones`
--
ALTER TABLE `publicaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
