-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-11-2025 a las 04:03:56
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
-- Base de datos: `gestor_tareas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comunidad_autonoma`
--

CREATE TABLE `comunidad_autonoma` (
  `id` tinyint(4) NOT NULL DEFAULT 0,
  `nombre` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comunidad_autonoma`
--

INSERT INTO `comunidad_autonoma` (`id`, `nombre`) VALUES
(1, 'Andalucía'),
(2, 'Aragón'),
(5, 'Canarias'),
(6, 'Cantabria'),
(8, 'Castilla y León'),
(7, 'Castilla-La Mancha'),
(9, 'Cataluña'),
(18, 'Ceuta'),
(13, 'Comunidad de Madrid'),
(15, 'Comunidad Foral de Navarra'),
(10, 'Comunidad Valenciana'),
(11, 'Extremadura'),
(12, 'Galicia'),
(4, 'Islas Baleares'),
(17, 'La Rioja'),
(19, 'Melilla'),
(16, 'País Vasco'),
(3, 'Principado de Asturias'),
(14, 'Región de Murcia');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `provincias`
--

CREATE TABLE `provincias` (
  `cp` char(2) NOT NULL DEFAULT '00' COMMENT 'Código postal',
  `nombre` varchar(50) NOT NULL DEFAULT '' COMMENT 'Nombre de la provincia',
  `comunidad_id` tinyint(4) NOT NULL COMMENT 'Código de la comunidad a la que pertenece'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `provincias`
--

INSERT INTO `provincias` (`cp`, `nombre`, `comunidad_id`) VALUES
('01', 'Alava', 16),
('02', 'Albacete', 7),
('03', 'Alicante', 10),
('04', 'Almera', 1),
('05', 'Avila', 8),
('06', 'Badajoz', 11),
('07', 'Islas Baleares', 4),
('08', 'Barcelona', 9),
('09', 'Burgos', 8),
('10', 'Cáceres', 11),
('11', 'Cádiz', 1),
('12', 'Castellón', 10),
('13', 'Ciudad Real', 7),
('14', 'Córdoba', 1),
('15', 'A Coruña', 12),
('16', 'Cuenca', 7),
('17', 'Gerona', 9),
('18', 'Granada', 1),
('19', 'Guadalajara', 7),
('20', 'Guipzcoa', 16),
('21', 'Huelva', 1),
('22', 'Huesca', 2),
('23', 'Jaén', 1),
('24', 'León', 8),
('25', 'Lleida', 9),
('26', 'La Rioja', 17),
('27', 'Lugo', 12),
('28', 'Madrid', 13),
('29', 'Málaga', 1),
('30', 'Murcia', 14),
('31', 'Navarra', 15),
('32', 'Ourense', 12),
('33', 'Asturias', 3),
('34', 'Palencia', 8),
('35', 'Las Palmas', 5),
('36', 'Pontevedra', 12),
('37', 'Salamanca', 8),
('38', 'Santa Cruz de Tenerife', 5),
('39', 'Cantabria', 6),
('40', 'Segovia', 8),
('41', 'Sevilla', 1),
('42', 'Soria', 8),
('43', 'Tarragona', 9),
('44', 'Teruel', 2),
('45', 'Toledo', 7),
('46', 'Valencia', 10),
('47', 'Valladolid', 8),
('48', 'Vizcaya', 16),
('49', 'Zamora', 8),
('50', 'Zaragoza', 2),
('51', 'Ceuta', 18),
('52', 'Melilla', 19);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sesiones`
--

CREATE TABLE `sesiones` (
  `sesiones_id` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tarea`
--

CREATE TABLE `tarea` (
  `tarea_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `nif_cif` varchar(20) DEFAULT NULL,
  `client_contacto` varchar(150) NOT NULL,
  `client_telefono` varchar(50) DEFAULT NULL,
  `client_email` varchar(150) NOT NULL,
  `client_direccion` varchar(255) DEFAULT NULL,
  `client_poblacion` varchar(100) DEFAULT NULL,
  `cp` varchar(5) DEFAULT NULL,
  `provincia` varchar(250) NOT NULL,
  `estado` enum('B','P','R','C') NOT NULL DEFAULT 'P',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_realizacion` date DEFAULT NULL,
  `anotaciones_anteriores` text DEFAULT NULL,
  `anotaciones_posteriores` text DEFAULT NULL,
  `fichero_resumen` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tarea`
--

INSERT INTO `tarea` (`tarea_id`, `user_id`, `nif_cif`, `client_contacto`, `client_telefono`, `client_email`, `client_direccion`, `client_poblacion`, `cp`, `provincia`, `estado`, `fecha_creacion`, `fecha_realizacion`, `anotaciones_anteriores`, `anotaciones_posteriores`, `fichero_resumen`, `created_at`, `updated_at`) VALUES
(1, 1, '12345678A', 'Juan Pérez', '612345678', 'juanperez@email.com', 'Calle Falsa 123', 'Madrid', '28001', 'Madrid', 'P', '2025-10-27 03:58:55', NULL, 'Llamada realizada para confirmar datos.', 'Pendiente revisión de la solicitud.', 'resumen1.pdf', '2025-10-27 03:58:55', NULL),
(2, 2, '87654321B', 'Ana Gómez', '622345678', 'anagomez@email.com', 'Avenida Libertad 45', 'Barcelona', '08010', 'Barcelona', 'R', '2025-10-27 03:58:55', '2025-11-06', 'Cliente solicita cambio de dirección.', 'Prueba 3', 'resumen_2_1.jpg,resumen_2_2.png,resumen_2_3.png', '2025-10-27 03:58:55', '2025-11-06 03:00:08'),
(3, 3, '11223344C', 'Carlos Ruiz', '632345678', 'carlosruiz@email.com', 'Calle Mayor 12', 'Sevilla', '41001', 'Sevilla', 'P', '2025-10-27 03:58:55', NULL, 'Problema con la facturación, revisar.', 'Revisar el historial de pagos.', 'resumen3.pdf', '2025-10-27 03:58:55', NULL),
(4, 4, '44332211D', 'María Sánchez', '642345678', 'mariasanchez@email.com', 'Plaza de España 5', 'Valencia', '46001', 'Valencia', 'P', '2025-10-27 03:58:55', NULL, 'Cliente satisfecha con el servicio.', 'Revisar posible mejora de servicio.', 'resumen4.pdf', '2025-10-27 03:58:55', NULL),
(5, 1, '22334455E', 'Pedro Martín', '652345678', 'pedromartin@email.com', 'Calle Sol 9', 'Granada', '18001', 'Granada', 'P', '2025-10-27 03:58:55', NULL, 'En espera de respuesta del cliente.', 'Confirmar disponibilidad de fechas.', 'resumen5.pdf', '2025-10-27 03:58:55', NULL),
(6, 2, '03383139T', 'Laura Fernández', '662345678', 'laurafernandez@email.com', 'Avenida del Mar 20', 'Malaga', '29001', 'Málaga', 'R', '2025-10-27 03:58:55', '2025-11-05', 'Cliente solicita más información sobre productos.', 'dwqd', 'resumen_6_1.png,resumen_6_2.png', '2025-10-27 03:58:55', '2025-11-05 00:47:38'),
(7, 3, '99887766G', 'José López', '672345678', 'jose.lopez@email.com', 'Calle Río 34', 'Zaragoza', '50001', 'Zaragoza', 'P', '2025-10-27 03:58:55', NULL, 'Confirmar recepción de la última factura.', 'Esperar respuesta del departamento de ventas.', 'resumen7.pdf', '2025-10-27 03:58:55', NULL),
(8, 4, '66778899H', 'Sofía Martínez', '682345678', 'sofia.martinez@email.com', 'Calle Los Robles 13', 'Vigo', '36201', 'Pontevedra', 'P', '2025-10-27 03:58:55', '2025-10-29', 'Problema técnico con el servicio.', 'Reemplazo solicitado.', 'resumen8.pdf', '2025-10-27 03:58:55', NULL),
(9, 5, '11223344I', 'David Ruiz', '692345678', 'david.ruiz@email.com', 'Calle Toledo 78', 'Alicante', '03001', 'Alicante', 'P', '2025-10-27 03:58:55', NULL, 'Solicitud de cita agendada.', 'Reconfirmar fecha de cita.', 'resumen9.pdf', '2025-10-27 03:58:55', NULL),
(10, 1, '77889900J', 'Carmen Díaz', '612345678', 'carmen.diaz@email.com', 'Avenida de la Constitución 150', 'Bilbao', '48001', 'Vizcaya', 'P', '2025-10-27 03:58:55', NULL, 'Llamada de seguimiento realizada.', 'Confirmar la resolución del problema.', 'resumen10.pdf', '2025-10-27 03:58:55', NULL),
(11, 2, '23456789A', 'Alba Gómez', '612345678', 'alba.gomez@email.com', 'Calle Arco 3', 'Santander', '39001', 'Cantabria', 'P', '2025-10-27 03:59:52', '2025-10-28', 'Solicitud de presupuesto pendiente.', 'Confirmar asistencia del cliente a la reunión.', NULL, '2025-10-27 03:59:52', '2025-10-28 20:30:35'),
(12, 3, '34567890B', 'Ricardo Fernández', '622345678', 'ricardo.fernandez@email.com', 'Calle Olmo 56', 'Valladolid', '47001', 'Valladolid', 'P', '2025-10-27 03:59:52', '2025-11-15', 'Pedido de cancelación recibido.', 'Esperando confirmación de cancelación.', 'resumen12.pdf', '2025-10-27 03:59:52', NULL),
(14, 1, '56789012D', 'Felipe Martínez', '642345678', 'felipe.martinez@email.com', 'Avenida Castilla 10', 'Burgos', '09001', 'Burgos', 'P', '2025-10-27 03:59:52', NULL, 'Llamada para confirmar datos de contacto.', 'Esperando respuesta del cliente.', 'resumen14.pdf', '2025-10-27 03:59:52', NULL),
(15, 5, '67890123E', 'Antonio Pérez', '652345678', 'antonio.perez@email.com', 'Calle San Pedro 4', 'Murcia', '30001', 'Murcia', 'P', '2025-10-27 03:59:52', '2025-10-31', 'Solicitar actualización de datos fiscales.', 'Confirmar con el cliente nuevos datos.', 'resumen15.pdf', '2025-10-27 03:59:52', NULL),
(16, 2, '78901234F', 'Elena Ruiz', '662345678', 'elena.ruiz@email.com', 'Calle Larga 78', 'Toledo', '45001', 'Toledo', 'P', '2025-10-27 03:59:52', '2029-10-28', 'Cliente ha solicitado un cambio de dirección.', 'tftiu', NULL, '2025-10-27 03:59:52', '2025-10-28 20:05:33'),
(17, 3, '89012345G', 'Samuel López', '672345678', 'samuel.lopez@email.com', 'Calle La Palmera 99', 'Salamanca', '37001', 'Salamanca', 'P', '2025-10-27 03:59:52', '2025-10-29', 'Problema con la entrega de producto.', 'Investigar retraso en el envío.', 'resumen17.pdf', '2025-10-27 03:59:52', NULL),
(18, 4, '90123456H', 'Lidia Morales', '682345678', 'lidia.morales@email.com', 'Calle Sevilla 55', 'Logroño', '26001', 'La Rioja', 'P', '2025-10-27 03:59:52', NULL, 'Solicitud de devolución de producto.', 'Esperando confirmación de devolución.', 'resumen18.pdf', '2025-10-27 03:59:52', NULL),
(20, 1, '23456789J', 'Silvia González', '702345678', 'silvia.gonzalez@email.com', 'Calle San Juan 9', 'Huesca', '22001', 'Huesca', 'P', '2025-10-27 03:59:52', NULL, 'Confirmación de cita para reunión de seguimiento.', 'Esperando asistencia de cliente.', 'resumen20.pdf', '2025-10-27 03:59:52', NULL),
(21, 3, '23456789A', 'Marcela Ruiz', '612345678', 'marcela.ruiz@email.com', 'Calle Gran Vía 78', 'Vigo', '36202', 'Pontevedra', 'P', '2025-10-27 04:00:54', '2025-11-12', 'Revisión de condiciones del contrato.', 'Confirmar con el cliente los términos finales.', 'resumen21.pdf', '2025-10-27 04:00:54', NULL),
(22, 5, '15767164C', 'Javier Ramírez', '622345678', 'javier.ramirez@email.com', 'Calle del Sol 2', 'Murcia', '30002', 'Murcia', 'P', '2025-10-27 04:00:54', NULL, 'Consulta de tarifas para nuevo servicio.', NULL, 'resumen22.pdf', '2025-10-27 04:00:54', '2025-10-28 02:23:45'),
(23, 5, '89090125Q', 'Raquel Martín', '679592422', 'raquel.martin@email.com', 'Calle del Mar 10', 'Valencia', '46002', 'Valencia', 'P', '2025-10-27 04:00:54', NULL, 'Aprobación de presupuesto pendiente.', NULL, 'resumen23.pdf', '2025-10-27 04:00:54', '2025-10-29 00:52:48'),
(24, 2, '56789012D', 'Víctor López', '642345678', 'victor.lopez@email.com', 'Calle de la Paz 34', 'Zaragoza', '50002', 'Zaragoza', 'P', '2025-10-27 04:00:54', '2025-10-29', 'Problema con el acceso a la cuenta.', 'pgu', 'resumen_24_1.pdf', '2025-10-27 04:00:54', '2025-10-29 18:02:06'),
(25, 5, '67890123E', 'Clara Fernández', '652345678', 'clara.fernandez@email.com', 'Avenida Libertad 10', 'La Coruña', '15001', 'A Coruña', 'P', '2025-10-27 04:00:54', NULL, 'Solicitar entrega de documentos.', 'Confirmar entrega de documentos al cliente.', 'resumen25.pdf', '2025-10-27 04:00:54', NULL),
(26, 3, '78901234F', 'Pablo Pérez', '662345678', 'pablo.perez@email.com', 'Calle del Cielo 7', 'León', '24001', 'León', 'P', '2025-10-27 04:00:54', NULL, 'Recordatorio de reunión pendiente.', 'Confirmar asistencia del cliente a la reunión.', 'resumen26.pdf', '2025-10-27 04:00:54', NULL),
(27, 4, '89012345G', 'Sonia García', '672345678', 'sonia.garcia@email.com', 'Calle Sevilla 20', 'Salamanca', '37002', 'Salamanca', 'P', '2025-10-27 04:00:54', '2025-11-10', 'Cliente solicita revisar el contrato.', 'Esperando respuesta a los términos propuestos.', 'resumen27.pdf', '2025-10-27 04:00:54', NULL),
(28, 1, '90123456H', 'Carlos Torres', '682345678', 'carlos.torres@email.com', 'Calle Mayor 5', 'Bilbao', '48002', 'Vizcaya', 'P', '2025-10-27 04:00:54', NULL, 'Sugerencia de nuevos productos a cliente.', 'Confirmar interés del cliente en nuevos productos.', 'resumen28.pdf', '2025-10-27 04:00:54', NULL),
(29, 2, '12345678I', 'Marta González', '692345678', 'marta.gonzalez@email.com', 'Calle Rosa 33', 'Alicante', '03002', 'Alicante', 'P', '2025-10-27 04:00:54', '2025-10-29', 'Confirmación de cita para demostración.', 'eqfwfgqa', 'resumen_29_1.pdf', '2025-10-27 04:00:54', '2025-10-29 00:38:31'),
(30, 5, '23456789J', 'Luis Sánchez', '702345678', 'luis.sanchez@email.com', 'Avenida España 15', 'Córdoba', '14001', 'Córdoba', 'P', '2025-10-27 04:00:54', '2025-10-20', 'Problema con el pago de la factura.', 'Verificar la situación de pago con el cliente.', 'resumen30.pdf', '2025-10-27 04:00:54', NULL),
(31, 5, '24589008F', 'Dafne Ruíz Pérez', '759122123', 'ana@gmail.com', 'Avenida Pio XII 23', 'Ciudad Real', '13008', 'Ciudad Real', 'P', '2025-10-27 13:36:46', NULL, '', NULL, NULL, NULL, '2025-10-28 02:24:44'),
(32, 2, '03383139T', 'Dafne Ruíz Pérez', '690425742', 'ana@gmail.com', 'Avenida Lola Flores 3', 'Ciudad Real', '11008', 'Cádiz', 'P', '2025-10-27 15:05:28', '2025-10-30', 'Sin anotaciones anteriores', 'Hola', 'resumen_32_1.pdf', '2025-10-27 15:05:28', '2025-10-30 00:36:17'),
(63, 3, '29428460Y', 'Víctor Rondón Jackson', '672896148', 'vic.rondon@gmail.com', 'Calle Jilguero 34', 'Tolosa', '20273', 'Guipzcoa', 'P', '2025-10-27 15:22:35', NULL, 'Sin anotaciones anteriores', NULL, NULL, '2025-10-27 15:22:35', '2025-10-28 02:24:39'),
(64, 5, '77210502P', 'Lucas Parra Gómez', '699351281', 'luka.parra@gmail.com', 'Calle Mónaco 2', 'La Roda', '02153', 'Albacete', 'P', '2025-10-27 15:27:01', NULL, 'Sin anotaciones anteriores', NULL, NULL, '2025-10-27 15:27:01', '2025-10-28 02:24:28'),
(65, 3, '00422522N', 'Lucas Parra Gómez', '759122123', 'luka.parra@gmail.com', 'Calle Rosa 2', 'La Roda', '35153', 'Las Palmas', 'P', '2025-10-28 00:28:46', NULL, 'Sin anotaciones anteriores', NULL, NULL, '2025-10-28 00:28:46', '2025-10-29 05:43:33'),
(67, 3, '89090125Q', 'Raquel Pérez', '759122123', 'raquel.martin@email.com', 'Calle del Mar 10', 'Barcelona', '21008', 'Huelva', 'P', '2025-10-29 06:47:18', NULL, 'Sin anotaciones anteriores', NULL, NULL, '2025-10-29 06:47:18', '2025-10-30 00:33:22'),
(68, 2, '89090125Q', 'Raquel Martín', '759122123', 'raquel.martin@email.com', 'Calle del Mar 10', 'La Roda', '26153', 'La Rioja', 'R', '2025-11-04 01:02:53', '2025-11-05', 'Sin anotaciones anteriores', 'hOLA', 'resumen_68_1.png', '2025-11-04 01:02:53', '2025-11-05 00:03:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_surname` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `role` enum('admin','operario') NOT NULL DEFAULT 'operario',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`user_id`, `user_name`, `user_surname`, `user_email`, `user_password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Rafael', 'Pérez Campina', 'rafa@gmail.com', '1234', 'admin', NULL, '2025-10-26 15:55:18', '2025-10-26 15:55:18'),
(2, 'Ana', 'Lopez Ocoro', 'ana@gmail.com', 'david', 'operario', NULL, '2025-10-26 15:55:18', '2025-10-26 15:55:18'),
(3, 'Gonzalo', 'Cortegano Jeremuald', 'gon@gmail.com', 'Kingkong', 'operario', NULL, '2025-10-26 15:55:18', '2025-10-26 15:55:18'),
(4, 'Luisa', 'Abenante Pardo', 'lapardo@gmail.com', 'reli', 'admin', NULL, '2025-10-26 15:55:18', '2025-10-26 15:55:18'),
(5, 'Alejandro', 'Nieves De la Corte', 'ale@gmail.com', 'pass', 'operario', NULL, '2025-10-26 15:55:18', '2025-10-26 15:55:18');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `tarea`
--
ALTER TABLE `tarea`
  ADD PRIMARY KEY (`tarea_id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tarea`
--
ALTER TABLE `tarea`
  MODIFY `tarea_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
