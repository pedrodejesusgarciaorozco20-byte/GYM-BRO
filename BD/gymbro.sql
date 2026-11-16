CREATE DATABASE IF NOT EXISTS `gymbro`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `gymbro`;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `beneficios_membresias` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre_membresia` varchar(50) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `acceso_clases_grupales` tinyint(1) NOT NULL DEFAULT 0,
  `asesoria_inicial_gratuita` tinyint(1) NOT NULL DEFAULT 0,
  `acceso_un_mes` tinyint(1) NOT NULL DEFAULT 0,
  `sesiones_entrenamiento_personalizado` tinyint(1) NOT NULL DEFAULT 0,
  `acceso_cualquier_sucursal` tinyint(1) NOT NULL DEFAULT 0,
  `entrenamientos_ilimitados` tinyint(1) NOT NULL DEFAULT 0,
  `descuentos_productos` tinyint(1) NOT NULL DEFAULT 0
) ;

INSERT INTO `beneficios_membresias` (`id`, `nombre_membresia`, `precio`, `acceso_clases_grupales`, `asesoria_inicial_gratuita`, `acceso_un_mes`, `sesiones_entrenamiento_personalizado`, `acceso_cualquier_sucursal`, `entrenamientos_ilimitados`, `descuentos_productos`) VALUES
(1, 'Básico', 299.00, 1, 1, 0, 0, 0, 0, 0),
(2, 'Medio', 499.00, 1, 1, 1, 1, 0, 0, 0),
(3, 'Premium', 799.00, 1, 1, 1, 1, 1, 1, 1);

CREATE TABLE `contacto` (
  `id` int(10) UNSIGNED NOT NULL,
  `usuario_id` int(10) UNSIGNED NOT NULL,
  `mensaje` varchar(1000) NOT NULL,
  `fecha_envio` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `contacto` (`id`, `usuario_id`, `mensaje`, `fecha_envio`) VALUES
(3, 4, 'Buen día, mensaje de prueba, mensaje de prueba.', '2026-11-12 17:31:39');

-- ------------------------------------------------------
CREATE TABLE `pagos` (
  `id` int(10) UNSIGNED NOT NULL,
  `suscripcion_id` int(10) UNSIGNED NOT NULL,
  `fecha_pago` datetime NOT NULL DEFAULT current_timestamp(),
  `metodo_pago` enum('tarjeta','efectivo','transferencia','otro') NOT NULL DEFAULT 'tarjeta',
  `monto` decimal(10,2) NOT NULL,
  `estado` enum('pendiente','aprobado','rechazado','reembolsado') NOT NULL DEFAULT 'pendiente',
  `referencia` varchar(100) DEFAULT NULL,
  `notas` varchar(255) DEFAULT NULL,
  `creado_en` datetime NOT NULL DEFAULT current_timestamp()
) ;

INSERT INTO `pagos` (`id`, `suscripcion_id`, `fecha_pago`, `metodo_pago`, `monto`, `estado`, `referencia`, `notas`, `creado_en`) VALUES
(2, 2, '2026-11-12 17:32:09', 'tarjeta', 578.84, 'aprobado', 'GYM-8A3028039B', 'Pago de demostración. No se realizó un cobro real.', '2026-11-12 17:32:09');

-- --------------------------------------------------------

CREATE TABLE `planes` (
  `id` int(10) UNSIGNED NOT NULL,
  `name_plan` varchar(32) NOT NULL,
  `nombre_plan` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL DEFAULT 0.00,
  `duracion_dias` int(10) UNSIGNED NOT NULL DEFAULT 30,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ;

INSERT INTO `planes` (`id`, `name_plan`, `nombre_plan`, `descripcion`, `precio`, `duracion_dias`, `activo`) VALUES
(1, 'basic', 'Basic', 'Membresía básica de GYM-BRO.', 299.00, 30, 1),
(2, 'medium', 'Medium', 'Membresía intermedia de GYM-BRO.', 499.00, 30, 1),
(3, 'premium', 'Premium', 'Membresía premium de GYM-BRO.', 799.00, 30, 1);

-- --------------------------------------------------------

CREATE TABLE `suscripciones` (
  `id` int(10) UNSIGNED NOT NULL,
  `usuario_id` int(10) UNSIGNED NOT NULL,
  `plan_id` int(10) UNSIGNED NOT NULL,
  `fecha_compra` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_inicio` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_fin` datetime NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `auto_renovar` tinyint(1) NOT NULL DEFAULT 0,
  `total_pagado` decimal(10,2) NOT NULL DEFAULT 0.00,
  `fecha_cancelacion` datetime DEFAULT NULL,
  `creado_en` datetime NOT NULL DEFAULT current_timestamp()
) ;

INSERT INTO `suscripciones` (`id`, `usuario_id`, `plan_id`, `fecha_compra`, `fecha_inicio`, `fecha_fin`, `estado`, `auto_renovar`, `total_pagado`, `fecha_cancelacion`, `creado_en`) VALUES
(2, 4, 2, '2026-11-12 17:32:09', '2026-11-13 00:32:09', '2026-12-13 00:32:09', 1, 0, 578.84, NULL, '2026-11-12 17:32:09');

-- --------------------------------------------------------

CREATE TABLE `testimonios` (
  `id` int(10) UNSIGNED NOT NULL,
  `usuario_id` int(10) UNSIGNED DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `mensaje` varchar(500) NOT NULL,
  `fecha_publicacion` datetime NOT NULL DEFAULT current_timestamp(),
  `publicado` tinyint(1) NOT NULL DEFAULT 1
) ;

INSERT INTO `testimonios` (`id`, `usuario_id`, `nombre`, `mensaje`, `fecha_publicacion`, `publicado`) VALUES
(3, 4, 'Jose Badillo', 'Sin duda una experiencia satisfactoria, los equipos están bien cuidados y los entrenadores muy serviciales, 10 de 10.', '2026-11-12 17:31:12', 1);

-- --------------------------------------------------------

CREATE TABLE `usuarios` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `Lname` varchar(50) NOT NULL,
  `email` varchar(254) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` tinyint(1) NOT NULL DEFAULT 0,
  `singdate` timestamp NOT NULL DEFAULT current_timestamp(),
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ;

INSERT INTO `usuarios` (`id`, `name`, `Lname`, `email`, `password`, `rol`, `singdate`, `activo`) VALUES
(3, 'admin', '01', 'admin01@local.com', '$2y$12$E3dGWLnDxh8rAO9tyvFyDOLksRPiK2RIStIrfOnAo1F3cuiV1t00y', 1, '2026-11-12 23:23:29', 1),
(4, 'Jose', 'Badillo', 'jos12@gmail.com', '$2y$12$iVewwSbMHXtnyqmduzJjM./i85Z/C5aGUncHz6NYfqb/hKxGlL0GK', 0, '2026-11-12 23:25:15', 1);

ALTER TABLE `beneficios_membresias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_beneficios_nombre` (`nombre_membresia`);

ALTER TABLE `contacto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_contacto_fecha` (`fecha_envio`),
  ADD KEY `idx_contacto_usuario` (`usuario_id`);

ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_pagos_referencia` (`referencia`),
  ADD KEY `idx_pagos_suscripcion` (`suscripcion_id`),
  ADD KEY `idx_pagos_fecha_estado` (`fecha_pago`,`estado`);

ALTER TABLE `planes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_planes_name_plan` (`name_plan`),
  ADD KEY `idx_planes_activo` (`activo`);

ALTER TABLE `suscripciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_suscripciones_usuario` (`usuario_id`),
  ADD KEY `idx_suscripciones_plan` (`plan_id`),
  ADD KEY `idx_suscripciones_estado_fechas` (`estado`,`fecha_inicio`,`fecha_fin`);

ALTER TABLE `testimonios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_testimonios_publicados_fecha` (`publicado`,`fecha_publicacion`),
  ADD KEY `idx_testimonios_usuario` (`usuario_id`);

ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_usuarios_email` (`email`),
  ADD KEY `idx_usuarios_rol_activo` (`rol`,`activo`);

ALTER TABLE `beneficios_membresias`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `contacto`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `pagos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `planes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `suscripciones`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `testimonios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `usuarios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `contacto`
  ADD CONSTRAINT `fk_contacto_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE;

ALTER TABLE `pagos`
  ADD CONSTRAINT `fk_pagos_suscripcion` FOREIGN KEY (`suscripcion_id`) REFERENCES `suscripciones` (`id`) ON UPDATE CASCADE;

ALTER TABLE `suscripciones`
  ADD CONSTRAINT `fk_suscripciones_plan` FOREIGN KEY (`plan_id`) REFERENCES `planes` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_suscripciones_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE;

ALTER TABLE `testimonios`
  ADD CONSTRAINT `fk_testimonios_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
