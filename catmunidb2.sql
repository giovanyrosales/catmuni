-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         5.7.33 - MySQL Community Server (GPL)
-- SO del servidor:              Win64
-- HeidiSQL Versión:             11.2.0.6213
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para catmunidb2
CREATE DATABASE IF NOT EXISTS `catmunidb2` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci */;
USE `catmunidb2`;

-- Volcando estructura para tabla catmunidb2.actividad_economica
CREATE TABLE IF NOT EXISTS `actividad_economica` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `rubro` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `codigo` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `codigo_atc_economica` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mora` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla catmunidb2.actividad_economica: ~4 rows (aproximadamente)
/*!40000 ALTER TABLE `actividad_economica` DISABLE KEYS */;
INSERT IGNORE INTO `actividad_economica` (`id`, `rubro`, `codigo`, `codigo_atc_economica`, `mora`) VALUES
	(1, 'Comercio', '2', '11801', '32201'),
	(2, 'Industria', '1', '11802', '32201'),
	(3, 'Financiero', '4', '11803', '32201'),
	(4, 'Servicios', '3', '11804', '32201');
/*!40000 ALTER TABLE `actividad_economica` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.actividad_especifica
CREATE TABLE IF NOT EXISTS `actividad_especifica` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nom_actividad_especifica` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_actividad_economica` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_actividad_economica` (`id_actividad_economica`),
  CONSTRAINT `FK_actividad_especifica_actividad_economica` FOREIGN KEY (`id_actividad_economica`) REFERENCES `actividad_economica` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=131 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla catmunidb2.actividad_especifica: ~129 rows (aproximadamente)
/*!40000 ALTER TABLE `actividad_especifica` DISABLE KEYS */;
INSERT IGNORE INTO `actividad_especifica` (`id`, `nom_actividad_especifica`, `id_actividad_economica`) VALUES
	(1, 'ASERRADEROS CON MAQUINARIA', 2),
	(2, 'ASERRADEROS SIN MAQUINARIA', 2),
	(3, 'COHETERIAS', 2),
	(4, 'FABRICA DE TEJA Y LADRILLOS DE BARRO', 2),
	(5, 'MARMOLERIAS', 2),
	(6, 'MUEBLERIAS', 2),
	(7, 'ORFEBRERIAS', 2),
	(8, 'PANADERIAS', 2),
	(9, 'TENERIAS', 2),
	(10, 'PULPERIAS', 2),
	(11, 'ABARROTERIAS', 1),
	(12, 'AGENCIA DE AZUCAR AL POR MAYOR', 1),
	(13, 'AGENCIA DE CERVEZAS', 1),
	(14, 'AGENCIAS DE CERVEZAS Y BEBIDAS GASEOSAS ', 1),
	(15, 'AGENCIA DE BEBIDAS GASEOSAS ', 1),
	(17, 'AGENCIAS DE MAQUINAS DE COSER', 1),
	(18, 'BAZARES', 1),
	(19, 'BODEGAS DE MERCADERIAS', 1),
	(20, 'CARNICERIAS', 1),
	(21, 'CAFETINES', 1),
	(22, 'DISTRIBUIDORES DE GAS PROPANO Y SIMILARES', 1),
	(23, 'EMPRESAS O ESTUDIOS FOTOGRAFICOS', 1),
	(24, 'JOYERIAS Y PLATERIAS', 1),
	(25, 'LIBRERIAS Y PLATERIAS', 1),
	(26, 'MOTELES', 1),
	(27, 'PANADERIAS', 1),
	(28, 'DE SORBETES, HELADOS Y PRODUCTOS SIMILARES', 1),
	(29, 'TIENDAS', 1),
	(30, 'VENTAS DE DISCOS, CASSETE Y RENTA VIDEO', 1),
	(31, 'VENTA DE HIELO', 1),
	(32, 'VENTA DE SAL', 1),
	(33, 'VENTA DE FERTILIZANTES', 1),
	(34, 'CASAS DISTRIBUIDORAS DE LLANTAS', 1),
	(35, 'ACADEMIAS DE ENSEÑANZA', 4),
	(36, 'ALQUILER DE MUEBLES Y UTENSILIOS', 4),
	(37, 'AGENCIAS DE EMPRESAS DE TRANSPORTE AL EXTERIOR: Aéreas', 4),
	(38, 'AGENCIAS DE EMPRESAS DE TRANSPORTE AL EXTERIOR: Marítimas', 4),
	(39, 'AGENCIAS DE EMPRESAS DE TRANSPORTE AL EXTERIOR: Terrestres', 4),
	(40, 'COLEGIOS PRIVADOS', 4),
	(41, 'FUNERARIAS', 4),
	(42, 'LABORATORIOS CLINICOS', 4),
	(43, 'OFICINAS PROFESIONALES', 4),
	(44, 'PELUQUERIAS O BARBERIAS', 4),
	(45, 'SERVICIO DE ENGRASADO (fuera del taller o en garaje)', 4),
	(46, 'SALONES DE BELLEZA', 4),
	(47, 'SASTRERIAS, COSTURERIAS Y SIMILARES', 4),
	(48, 'SERVICIOS TECNICOS PROFESIONALES: Nacionales', 4),
	(49, 'SERVICIOS TECNICOS PROFESIONALES: Extranjeras', 4),
	(50, 'COMISIONISTAS O PRESTAMISTAS', 4),
	(51, 'VENTAS DIVERSAS: DE ATAUDES', 1),
	(52, 'VENTAS DIVERSAS: DE CAL', 1),
	(53, 'VENTAS DIVERSAS: DE CARNE', 1),
	(54, 'VENTAS DIVERSAS: DE COMESTIBLES Y VERDURAS EN GENERAL', 1),
	(55, 'VENTAS DIVERSAS: DE COSMETICOS', 1),
	(56, 'VENTAS DIVERSAS: DE CHATARRA Y HUESERAS', 1),
	(57, 'VENTAS DIVERSAS: DE GALLINAS, POLLOS Y HUEVOS', 1),
	(58, 'VENTAS DIVERSAS: DE HARINA AL POR MAYOR', 1),
	(59, 'VENTAS DIVERSAS: DE MADERA', 1),
	(60, 'VENTAS DIVERSAS: DE PIÑATAS, FLORES, CORONAS Y SIMILARES', 1),
	(61, 'VENTAS DIVERSAS: DE QUESO, MANTEQUILLA Y OTROS LACTEOS', 1),
	(62, 'VENTAS DIVERSAS: DE SUELA Y OTROS MATERIALES', 2),
	(63, 'VENTAS DIVERSAS: DE VEHICULOS Y REPUESTOS USADOS', 1),
	(64, 'CASINOS, CLUBES O CENTROS COMERCIALES', 4),
	(65, 'COMEDORES', 4),
	(66, 'CHALETS, REFRESQUERIAS, SORBETERIAS Y SIMILARES:En parques, y otros lugares públicos', 1),
	(67, 'CHALETS, REFRESQUERIAS, SORBETERIAS Y SIMILARES: En sitios particulares', 1),
	(68, 'PUPUSERIAS', 4),
	(69, 'RESTAURANTES', 4),
	(70, 'TALLERES DE CARPINTERIA', 4),
	(71, 'TALLERES DE HOJALATERIA', 4),
	(72, 'TALLERES DE REPARACION DE LLANTAS', 4),
	(73, 'TALLERES DE REPARACION DE RADIOS, TELEVISORES, MAQUINAS DE COSER, DE ESCRIBIR Y OTROS SIMILARES', 4),
	(74, 'TALLER DE REPARACION DE RELOJES', 4),
	(75, 'TALLERES DE REPARACION DE VEHICULOS AUTOMOTORES', 4),
	(76, 'TALLERES DE TAPICERIA', 4),
	(77, 'TALLERES DE ZAPATERIA', 4),
	(78, 'TALLERES DE HERRERIAS Y SIMILARES', 4),
	(79, 'AGENCIAS DE LOTERIA: NACIONAL', 1),
	(80, 'AGENCIAS DE LOTERIA: EXTRANJERA', 1),
	(81, 'AGENCIAS U OFICINAS DE TRAMITACION DE TRNASITO', 4),
	(82, 'AGENCIAS DE PUBLICIDAD', 4),
	(83, 'CASAS DESTINADAS AL TURISMO: ADYACENTES A PLAYAS', 4),
	(84, 'CASAS DESTINADAS AL TURISMO: ADYACENTES A RIOS', 4),
	(85, 'CASAS DESTINADAS AL TURISMO: ADYACENTES A OTROS LUGARES TURISTICOS', 4),
	(86, 'CASAS DE ALQUILER DE BICICLETA', 1),
	(87, 'CASAS DE FAMILIA O PUPILAJE', 1),
	(88, 'CASAS DE HUESPEDES O PENSIONES: Hasta 5 habitaciones', 4),
	(89, 'CASAS DE HUESPEDES O PENSIONES: De 6 hasta 10 habitaciones', 4),
	(90, 'CASAS DE HUESPEDES O PENSIONES: De más de 10 habitaciones', 4),
	(91, 'CASAS DE PRESTAMOS Y MONTEPIOS', 3),
	(92, 'CINQUERAS, SINFONOLAS U OTROS APARATOS DE MUSICA GRABADA: En refresquerías, tiendas, alamacenes y similares', 4),
	(93, 'CINQUERAS, SINFONOLAS U OTROS APARATOS DE MUSICA GRABADA: En cervecerías, bares, hoteles, moteles y similares', 4),
	(94, 'CINQUERAS, SINFONOLAS U OTROS APARATOS DE MUSICA GRABADA: En billares, terminales de buses, clubes sociales, casinos y similares', 4),
	(95, 'DE COMPRA DE CAFE O RECIBIDEROS(En temporadas): De 0 a 1000 qq', 2),
	(96, 'DE COMPRA DE CAFE O RECIBIDEROS(En temporadas): De 1001 a 4000 qq', 2),
	(97, 'DE COMPRA DE CAFE O RECIBIDEROS(En temporadas): De más de 4000 qq', 2),
	(98, 'EMPRESAS DE TRANSPORTE (Vehículos comerciales para el transporte de carga, cada uno): De una tonelada', 4),
	(99, 'EMPRESAS DE TRANSPORTE (Vehículos comerciales para el transporte de carga, cada uno): De más de una hasta tres toneladas', 4),
	(100, 'EMPRESAS DE TRANSPORTE (Vehículos comerciales para el transporte de carga, cada uno): De más de tres hasta ocho toneladas', 4),
	(101, 'EMPRESAS DE TRANSPORTE (Vehículos comerciales para el transporte de carga, cada uno): De más de ocho toneladas', 4),
	(102, 'FABRICA DE HIELO: Con capacidad hasta de 20 qq diarios', 2),
	(103, 'FABRICA DE HIELO: Con capacidad mayor de 20 qq diarios', 2),
	(104, 'GRANJAS AVICOLAS CON HASTA 5000 AVES', 2),
	(105, 'GRANJAS AVICOLAS CON MAS 5000 AVES', 2),
	(106, 'JUEGOS PERMITIDOS: Billares, por cada mesa', 4),
	(107, 'JUEGOS PERMITIDOS: Juegos de dominó', 4),
	(108, 'JUEGOS PERMITIDOS:Loterías de cartones, de números o figuras instaladas en tiempo que no sea fiesta patronal, al mes o fracción', 4),
	(109, 'JUEGOS PERMITIDOS: Aparatos eléctronicos que funcionen con o sin monedas cada uno', 4),
	(110, 'PRODUCTORES DE MIEL DE ABEJAS: Por cada caja, al año', 2),
	(111, 'MERCADOS PARTICULARES: Hasta 10 puestos', 1),
	(112, 'MERCADOS PARTICULARES: De 11 hasta 50 puestos', 1),
	(113, 'MERCADOS PARTICULARES: De más de 50 puestos', 1),
	(114, 'MOLINOS: Hasta de dos tolvas', 4),
	(115, 'MOLINOS: De más de dos tolvas', 4),
	(116, 'SALONES PARA EXPENDIO DE AGUARDIENTE ENVASADO', 4),
	(117, 'SALONES DE BAILE, POR EVENTO', 4),
	(118, 'RIFAS O SORTEOS', 1),
	(119, 'AGENCIAS DE ENCOMIENDAS', 4),
	(120, 'EXPLOTACION DE MINAS Y CANTERAS', 2),
	(121, 'EXPLOTACION DE MINERALES', 2),
	(122, 'EXPLOTACION DE OTROS RECURSOS', 2),
	(123, 'PARQUEO PRIVADOS PARA SERVICIO PUBLICO, GARAGE O PENSIONADOS: Con capacidad hasta 10 vehículos', 2),
	(124, 'PARQUEO PRIVADOS PARA SERVICIO PUBLICO, GARAGE O PENSIONADOS: Con capacidad de más de 10 vehículos', 2),
	(125, 'EMPRESAS CONSTRUCTORAS: Nacionales al mes', 4),
	(126, 'EMPRESAS CONSTRUCTORAS: Extranjeras al mes', 4),
	(127, 'INDUSTRIAS DE CEMENTO', 2),
	(128, 'DISTRIBUIDORES DE VEHICULOS AUTOMOTORES', 1),
	(129, 'VENTA DE LICORES NACIONALES Y EXTRAJEROS EN BARES', 1),
	(130, 'CENTROS NOCTURNOS', 4);
/*!40000 ALTER TABLE `actividad_especifica` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.alertas
CREATE TABLE IF NOT EXISTS `alertas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tipo_alerta` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla catmunidb2.alertas: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `alertas` DISABLE KEYS */;
INSERT IGNORE INTO `alertas` (`id`, `tipo_alerta`) VALUES
	(1, 'Aviso'),
	(2, 'Notificación');
/*!40000 ALTER TABLE `alertas` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.alertas_detalle
CREATE TABLE IF NOT EXISTS `alertas_detalle` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_empresa` bigint(20) unsigned DEFAULT NULL,
  `id_alerta` bigint(20) unsigned DEFAULT NULL,
  `cantidad` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_empresa` (`id_empresa`),
  KEY `id_alerta` (`id_alerta`),
  CONSTRAINT `FK_alertas_detalle_alertas` FOREIGN KEY (`id_alerta`) REFERENCES `alertas` (`id`),
  CONSTRAINT `FK_alertas_detalle_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla catmunidb2.alertas_detalle: ~10 rows (aproximadamente)
/*!40000 ALTER TABLE `alertas_detalle` DISABLE KEYS */;
INSERT IGNORE INTO `alertas_detalle` (`id`, `id_empresa`, `id_alerta`, `cantidad`) VALUES
	(1, 68, 1, 1),
	(2, 63, 2, 0),
	(3, 67, 2, 0),
	(4, 61, 2, 0),
	(5, 63, 1, 0),
	(6, 96, 1, 0),
	(7, 96, 2, 0),
	(8, 67, 1, 0),
	(9, 94, 1, 0),
	(10, 94, 2, 0),
	(11, 97, 1, 1),
	(12, 97, 2, 1),
	(13, 61, 1, 0),
	(14, 100, 1, 1),
	(15, 71, 2, 1);
/*!40000 ALTER TABLE `alertas_detalle` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.buses_detalle
CREATE TABLE IF NOT EXISTS `buses_detalle` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_contribuyente` bigint(20) unsigned NOT NULL,
  `id_estado_buses` bigint(20) unsigned NOT NULL,
  `fecha_apertura` date NOT NULL,
  `nFicha` varchar(50) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `cantidad` int(50) NOT NULL,
  `tarifa` decimal(10,2) NOT NULL DEFAULT '0.00',
  `monto_pagar` decimal(10,2) NOT NULL DEFAULT '0.00',
  `estado_especificacion` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `nom_empresa` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `dir_empresa` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `nit_empresa` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `tel_empresa` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `email_empresa` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `r_comerciante` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `FK_buses_detalle_estado_buses` (`id_estado_buses`),
  KEY `id_contribuyente` (`id_contribuyente`),
  CONSTRAINT `FK_buses_detalle_contribuyente` FOREIGN KEY (`id_contribuyente`) REFERENCES `contribuyente` (`id`),
  CONSTRAINT `FK_buses_detalle_estado_buses` FOREIGN KEY (`id_estado_buses`) REFERENCES `estado_buses` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla catmunidb2.buses_detalle: ~5 rows (aproximadamente)
/*!40000 ALTER TABLE `buses_detalle` DISABLE KEYS */;
INSERT IGNORE INTO `buses_detalle` (`id`, `id_contribuyente`, `id_estado_buses`, `fecha_apertura`, `nFicha`, `cantidad`, `tarifa`, `monto_pagar`, `estado_especificacion`, `nom_empresa`, `dir_empresa`, `nit_empresa`, `tel_empresa`, `email_empresa`, `r_comerciante`, `created_at`, `updated_at`) VALUES
	(89, 1, 2, '2022-08-08', '100', 3, 51.42, 53.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-15 14:51:55', '2022-08-15 14:51:55'),
	(90, 4, 2, '2022-08-15', '101', 1, 17.14, 18.00, 'especificada', NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-15 16:29:54', '2022-08-17 16:11:12'),
	(91, 9, 2, '2022-08-03', '102', 1, 17.14, 18.00, 'especificada', NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-15 16:30:56', '2022-08-16 14:35:51'),
	(92, 9, 2, '2022-08-08', '103', 1, 17.14, 18.00, 'especificada', NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-15 21:22:03', '2022-08-18 21:28:14'),
	(94, 1, 2, '2022-08-18', '200', 3, 51.42, 53.00, 'especificada', 'Sociedad Transportes Marin S.A de C.V.', 'Metapán', '1111-444444-666', '24659800', 'transportes_marin@gmail.com', NULL, '2022-08-19 17:58:11', '2022-08-19 20:27:52');
/*!40000 ALTER TABLE `buses_detalle` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.buses_detalle_especifico
CREATE TABLE IF NOT EXISTS `buses_detalle_especifico` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_buses_detalle` bigint(20) unsigned NOT NULL,
  `placa` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `nombre` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `ruta` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `telefono` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `FK__buses_detalle` (`id_buses_detalle`),
  CONSTRAINT `FK__buses_detalle` FOREIGN KEY (`id_buses_detalle`) REFERENCES `buses_detalle` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla catmunidb2.buses_detalle_especifico: ~6 rows (aproximadamente)
/*!40000 ALTER TABLE `buses_detalle_especifico` DISABLE KEYS */;
INSERT IGNORE INTO `buses_detalle_especifico` (`id`, `id_buses_detalle`, `placa`, `nombre`, `ruta`, `telefono`, `created_at`, `updated_at`) VALUES
	(36, 91, 'AAC-0124', 'Unidad 1', '235', '24158765', '2022-08-15 21:02:40', '2022-08-16 14:35:51'),
	(37, 90, 'AAC-0198', 'Unidad 3', '201', '24158765', '2022-08-16 15:50:38', '2022-08-17 16:11:12'),
	(38, 92, 'AAC-0120', 'Unidad 10', '201', '24098765', '2022-08-18 21:28:13', '2022-08-18 21:28:13'),
	(39, 94, 'AAC-0126', 'Unidad 1', '235', '24158765', '2022-08-19 20:27:52', '2022-08-19 20:27:52'),
	(40, 94, 'AAC-0120', 'Unidad 6', '235', '24158765', '2022-08-19 20:27:52', '2022-08-19 20:27:52'),
	(41, 94, 'AAC-0123', 'Unidad 0', '235', '24158765', '2022-08-19 20:27:52', '2022-08-19 20:27:52');
/*!40000 ALTER TABLE `buses_detalle_especifico` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.calificacion
CREATE TABLE IF NOT EXISTS `calificacion` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_empresa` bigint(20) unsigned NOT NULL,
  `id_estado_licencia_licor` bigint(20) unsigned NOT NULL,
  `id_multa` bigint(20) unsigned NOT NULL,
  `id_estado_multa` bigint(20) unsigned NOT NULL,
  `fecha_calificacion` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_tarifa` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado_calificacion` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `licencia` decimal(20,2) DEFAULT NULL,
  `matricula` decimal(20,2) DEFAULT NULL,
  `total_mat_permisos` decimal(20,2) DEFAULT NULL,
  `fondofp_licencia_permisos` decimal(20,2) DEFAULT NULL,
  `pago_anual_permisos` decimal(20,2) DEFAULT NULL,
  `activo_total` decimal(20,2) DEFAULT NULL,
  `deducciones` decimal(20,2) DEFAULT NULL,
  `activo_imponible` decimal(20,2) DEFAULT NULL,
  `año_calificacion` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tarifa` decimal(20,2) NOT NULL DEFAULT '0.00',
  `tarifa_colones` decimal(20,2) NOT NULL DEFAULT '0.00',
  `pago_mensual` decimal(20,2) NOT NULL DEFAULT '0.00',
  `pago_anual` decimal(20,2) DEFAULT '0.00',
  `fondofp_mensual` decimal(20,2) DEFAULT '0.00',
  `fondofp_anual` decimal(20,2) DEFAULT '0.00',
  `total_impuesto` decimal(20,2) NOT NULL DEFAULT '0.00',
  `total_impuesto_anual` decimal(20,2) DEFAULT '0.00',
  `multa_balance` decimal(20,2) DEFAULT NULL,
  `codigo_tarifa` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_calificacion_empresa` (`id_empresa`),
  KEY `id_estado_licencia_licor` (`id_estado_licencia_licor`),
  KEY `id_multa` (`id_multa`),
  KEY `id_estado_multa` (`id_estado_multa`),
  CONSTRAINT `FK_calificacion_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id`),
  CONSTRAINT `FK_calificacion_estado_licencia_licor` FOREIGN KEY (`id_estado_licencia_licor`) REFERENCES `estado_licencia_licor` (`id`),
  CONSTRAINT `FK_calificacion_estado_multa` FOREIGN KEY (`id_estado_multa`) REFERENCES `estado_multa` (`id`),
  CONSTRAINT `FK_calificacion_multas` FOREIGN KEY (`id_multa`) REFERENCES `multas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=142 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla catmunidb2.calificacion: ~22 rows (aproximadamente)
/*!40000 ALTER TABLE `calificacion` DISABLE KEYS */;
INSERT IGNORE INTO `calificacion` (`id`, `id_empresa`, `id_estado_licencia_licor`, `id_multa`, `id_estado_multa`, `fecha_calificacion`, `tipo_tarifa`, `estado_calificacion`, `licencia`, `matricula`, `total_mat_permisos`, `fondofp_licencia_permisos`, `pago_anual_permisos`, `activo_total`, `deducciones`, `activo_imponible`, `año_calificacion`, `tarifa`, `tarifa_colones`, `pago_mensual`, `pago_anual`, `fondofp_mensual`, `fondofp_anual`, `total_impuesto`, `total_impuesto_anual`, `multa_balance`, `codigo_tarifa`, `created_at`, `updated_at`) VALUES
	(1, 61, 1, 1, 1, '2021-12-01', 'Fija', 'calificado', 0.00, 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, '2021', 12.00, 0.00, 11.00, 0.00, 0.00, 0.00, 12.00, 0.00, NULL, NULL, '2022-02-08 15:02:30', '2022-02-08 15:02:30'),
	(24, 63, 2, 1, 1, '2022-03-02', 'Variable', 'calificado', 365.00, 530.00, NULL, NULL, 939.75, NULL, NULL, NULL, '2020', 15.23, 0.00, 15.23, 0.00, 0.00, 0.00, 9.48, 0.00, 0.00, NULL, '2022-03-02 15:33:03', '2022-06-23 17:33:30'),
	(43, 63, 2, 1, 1, '2022-03-07', 'Variable', 'recalificado', 365.00, 530.00, NULL, NULL, 939.75, NULL, NULL, NULL, '2021', 13.38, 0.00, 13.38, 0.00, 0.00, 0.00, 11.21, 0.00, 2.86, NULL, '2022-03-07 16:34:19', '2022-06-23 17:33:30'),
	(50, 63, 2, 1, 1, '2022-03-31', 'Variable', 'recalificado', 365.00, 210.00, NULL, NULL, 220.50, NULL, NULL, NULL, '2022', 13.38, 0.00, 13.38, 0.00, 0.00, 0.00, 11.21, 0.00, 2.86, NULL, '2022-03-31 21:56:08', '2022-06-23 17:33:30'),
	(52, 61, 1, 1, 1, '2022-04-05', 'Variable', 'recalificado', 0.00, 50.00, NULL, NULL, 52.50, NULL, NULL, NULL, '2022', 9.03, 0.00, 9.03, 0.00, 0.00, 0.00, 9.48, 0.00, 2.86, NULL, '2022-04-05 15:35:43', '2022-04-05 15:35:43'),
	(57, 64, 2, 1, 2, '2022-05-03', 'Variable', 'calificado', 0.00, 12.00, NULL, NULL, 12.60, NULL, NULL, NULL, '2021', 7.45, 0.00, 7.45, 0.00, 0.00, 0.00, 7.82, 0.00, 0.00, NULL, '2022-05-03 15:05:57', '2022-05-03 15:05:57'),
	(58, 64, 2, 1, 2, '2022-05-03', 'Variable', 'recalificado', 0.00, 12.00, NULL, NULL, 12.60, NULL, NULL, NULL, '2022', 7.47, 0.00, 7.47, 0.00, 0.00, 0.00, 7.85, 0.00, 2.86, NULL, '2022-05-03 15:07:05', '2022-05-03 15:07:05'),
	(59, 65, 2, 1, 2, '2022-05-05', 'Variable', 'calificado', 365.00, 0.00, NULL, NULL, 383.25, NULL, NULL, NULL, '2021', 7.45, 0.00, 7.45, 0.00, 0.00, 0.00, 7.82, 0.00, 0.00, NULL, '2022-05-05 17:37:46', '2022-05-05 17:45:47'),
	(60, 65, 2, 1, 2, '2022-05-05', 'Variable', 'recalificado', 365.00, 0.00, NULL, NULL, 383.25, NULL, NULL, NULL, '2022', 7.45, 0.00, 7.45, 0.00, 0.00, 0.00, 7.82, 0.00, 2.86, NULL, '2022-05-05 17:38:13', '2022-05-05 17:45:47'),
	(62, 67, 1, 1, 1, '2022-05-18', 'Fija', 'calificado', 0.00, 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, '2021', 5.71, 0.00, 5.71, 0.00, 0.00, 0.00, 6.00, 0.00, 0.00, NULL, '2022-05-18 20:57:28', '2022-08-17 17:14:39'),
	(64, 62, 2, 1, 2, '2022-06-07', 'Variable', 'calificado', 0.00, 6.00, NULL, NULL, 6.30, NULL, NULL, NULL, '2022', 7.45, 0.00, 7.45, 0.00, 0.00, 0.00, 7.82, 0.00, 0.00, NULL, '2022-06-07 20:14:33', '2022-06-07 20:14:33'),
	(81, 66, 2, 1, 2, '2022-06-10', 'Fija', 'recalificado', 365.00, 121.00, 486.00, 24.30, 510.30, 2000.00, 0.00, 2000.00, '2022', 11.43, 100.00, 11.43, 137.16, 0.57, 6.86, 12.00, 144.02, 2.86, '13.6.20.1', '2022-06-10 21:14:44', '2022-06-10 21:14:44'),
	(83, 67, 1, 1, 1, '2022-06-10', 'Variable', 'recalificado', 365.00, 12.00, 377.00, 18.85, 395.85, 15000.00, 0.00, 15000.00, '2022', 28.05, 245.44, 28.05, 336.60, 1.40, 16.83, 29.45, 353.43, 2.86, 'N/A', '2022-06-10 21:28:07', '2022-08-17 17:14:39'),
	(88, 69, 2, 1, 2, '2022-08-12', 'Fija', 'calificado', 365.00, 0.00, 365.00, 18.25, 383.25, 2000.00, 0.00, 2000.00, '2022', 5.71, 50.00, 5.71, 68.52, 0.29, 3.43, 6.00, 71.95, 0.00, '13.5.7.2', '2022-08-12 19:48:36', '2022-08-12 19:48:36'),
	(90, 71, 2, 1, 2, '2022-08-15', 'Variable', 'calificado', 0.00, 0.00, 0.00, 0.00, 0.00, 12000.00, 0.00, 12000.00, '2014', 26.97, 235.99, 26.97, 323.64, 1.35, 16.18, 28.32, 339.82, 0.00, '13.3.2.3', '2022-08-15 16:06:15', '2022-08-15 16:06:15'),
	(100, 71, 2, 1, 2, '2022-08-15', 'Variable', 'recalificado', 0.00, 0.00, 0.00, 0.00, 0.00, 12000.00, 0.00, 12000.00, '2015', 26.97, 235.99, 26.97, 323.64, 1.35, 16.18, 28.32, 339.82, 48.01, '13.3.2.3', '2022-08-15 21:18:50', '2022-08-15 21:18:50'),
	(105, 71, 2, 1, 2, '2022-08-16', 'Variable', 'recalificado', 0.00, 0.00, 0.00, 0.00, 0.00, 12000.00, 0.00, 12000.00, '2016', 26.97, 235.99, 26.97, 323.64, 1.35, 16.18, 28.32, 339.82, 41.53, '13.3.2.3', '2022-08-16 14:57:27', '2022-08-16 14:57:27'),
	(106, 71, 2, 1, 2, '2022-08-16', 'Variable', 'recalificado', 0.00, 0.00, 0.00, 0.00, 0.00, 12000.00, 0.00, 12000.00, '2017', 26.97, 235.99, 26.97, 323.64, 1.35, 16.18, 28.32, 339.82, 35.06, '13.3.2.3', '2022-08-16 20:14:21', '2022-08-16 20:14:21'),
	(107, 71, 2, 1, 2, '2022-08-16', 'Variable', 'recalificado', 0.00, 0.00, 0.00, 0.00, 0.00, 12000.00, 0.00, 12000.00, '2018', 26.97, 235.99, 26.97, 323.64, 1.35, 16.18, 28.32, 339.82, 28.59, '13.3.2.3', '2022-08-16 21:08:43', '2022-08-16 21:08:43'),
	(108, 70, 2, 1, 2, '2022-08-29', 'Variable', 'calificado', 0.00, 0.00, 0.00, 0.00, 0.00, 12000.00, 0.00, 12000.00, '2022', 26.97, 235.99, 26.97, 323.64, 1.35, 16.18, 28.32, 339.82, 0.00, 'N/A', '2022-08-29 18:44:24', '2022-08-29 18:44:24'),
	(119, 71, 2, 1, 2, '2022-09-01', 'Variable', 'recalificado', 0.00, 0.00, 0.00, 0.00, 0.00, 12000.00, 0.00, 12000.00, '2019', 26.97, 235.99, 26.97, 323.64, 1.35, 16.18, 28.32, 339.82, 22.65, '13.3.2.3', '2022-09-01 14:25:01', '2022-09-01 14:25:01'),
	(140, 100, 2, 1, 2, '2022-09-01', 'Fija', 'calificado', 0.00, 0.00, 0.00, 0.00, 0.00, 2000.00, 0.00, 2000.00, '2021', 11.43, 100.00, 11.43, 137.16, 0.57, 6.86, 12.00, 144.00, 0.00, '13.6.20.1', '2022-09-01 17:37:52', '2022-09-01 17:37:52'),
	(141, 100, 2, 1, 2, '2022-09-01', 'Fija', 'recalificado', 0.00, 0.00, 0.00, 0.00, 0.00, 2000.00, 0.00, 2000.00, '2022', 11.43, 100.00, 11.43, 137.16, 0.57, 6.86, 12.00, 144.00, 2.86, '13.6.20.1', '2022-09-01 17:38:25', '2022-09-01 17:38:25');
/*!40000 ALTER TABLE `calificacion` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.calificacion_bus
CREATE TABLE IF NOT EXISTS `calificacion_bus` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_buses` bigint(20) unsigned NOT NULL,
  `id_empresa` bigint(20) unsigned NOT NULL,
  `fecha_calificacion` date NOT NULL,
  `tarifa_mensual` decimal(20,2) NOT NULL,
  `tarifa_total` decimal(20,2) NOT NULL,
  `estado_calificacion` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `FK_calificacion_bus_buses` (`id_buses`),
  KEY `FK_calificacion_bus_empresa` (`id_empresa`),
  CONSTRAINT `FK_calificacion_bus_buses` FOREIGN KEY (`id_buses`) REFERENCES `buses` (`id`),
  CONSTRAINT `FK_calificacion_bus_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla catmunidb2.calificacion_bus: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `calificacion_bus` DISABLE KEYS */;
INSERT IGNORE INTO `calificacion_bus` (`id`, `id_buses`, `id_empresa`, `fecha_calificacion`, `tarifa_mensual`, `tarifa_total`, `estado_calificacion`, `created_at`, `updated_at`) VALUES
	(61, 5, 64, '2022-06-07', 17.14, 18.00, 'calificado', '2022-07-04 21:15:17', '2022-07-04 21:15:17');
/*!40000 ALTER TABLE `calificacion_bus` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.calificacion_buses
CREATE TABLE IF NOT EXISTS `calificacion_buses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_buses_detalle` bigint(20) unsigned NOT NULL DEFAULT '0',
  `id_empresa` bigint(20) unsigned NOT NULL DEFAULT '0',
  `fecha_calificacion` varchar(50) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `cantidad` int(11) NOT NULL DEFAULT '0',
  `monto` decimal(10,2) NOT NULL DEFAULT '0.00',
  `pago_mensual` decimal(10,2) NOT NULL DEFAULT '0.00',
  `estado_calificacion` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `id_buses_detalle` (`id_buses_detalle`),
  KEY `FK_calificacion_buses_empresa` (`id_empresa`),
  CONSTRAINT `FK_calificacion_buses_buses_detalle` FOREIGN KEY (`id_buses_detalle`) REFERENCES `buses_detalle` (`id`),
  CONSTRAINT `FK_calificacion_buses_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla catmunidb2.calificacion_buses: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `calificacion_buses` DISABLE KEYS */;
/*!40000 ALTER TABLE `calificacion_buses` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.calificacion_matriculas
CREATE TABLE IF NOT EXISTS `calificacion_matriculas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_matriculas_detalle` bigint(20) unsigned NOT NULL,
  `id_estado_matricula` bigint(20) unsigned NOT NULL,
  `nombre_matricula` varchar(50) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `cantidad` int(11) NOT NULL DEFAULT '0',
  `fecha_calificacion` varchar(50) COLLATE utf8_spanish_ci NOT NULL DEFAULT '0',
  `monto_matricula` decimal(20,2) NOT NULL DEFAULT '0.00',
  `pago_mensual` decimal(20,2) NOT NULL DEFAULT '0.00',
  `fondofp` decimal(20,2) NOT NULL DEFAULT '0.00',
  `pago_anual` decimal(20,2) NOT NULL DEFAULT '0.00',
  `tarifa_colones` decimal(20,2) NOT NULL DEFAULT '0.00',
  `total_impuesto_mat` decimal(20,2) NOT NULL DEFAULT '0.00',
  `fondofp_impuesto_mat` decimal(20,2) NOT NULL DEFAULT '0.00',
  `año_calificacion` varchar(50) COLLATE utf8_spanish_ci NOT NULL DEFAULT '0',
  `estado_calificacion` varchar(50) COLLATE utf8_spanish_ci NOT NULL DEFAULT '0',
  `tipo_tarifa` varchar(50) COLLATE utf8_spanish_ci NOT NULL DEFAULT '0',
  `codigo_tarifa` varchar(50) COLLATE utf8_spanish_ci DEFAULT '0',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_calificacion_matriculas_matriculas_detalle` (`id_matriculas_detalle`),
  KEY `id_estado_matricula` (`id_estado_matricula`),
  CONSTRAINT `FK_calificacion_matriculas_estado_matricula` FOREIGN KEY (`id_estado_matricula`) REFERENCES `estado_matricula` (`id`),
  CONSTRAINT `FK_calificacion_matriculas_matriculas_detalle` FOREIGN KEY (`id_matriculas_detalle`) REFERENCES `matriculas_detalle` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla catmunidb2.calificacion_matriculas: ~17 rows (aproximadamente)
/*!40000 ALTER TABLE `calificacion_matriculas` DISABLE KEYS */;
INSERT IGNORE INTO `calificacion_matriculas` (`id`, `id_matriculas_detalle`, `id_estado_matricula`, `nombre_matricula`, `cantidad`, `fecha_calificacion`, `monto_matricula`, `pago_mensual`, `fondofp`, `pago_anual`, `tarifa_colones`, `total_impuesto_mat`, `fondofp_impuesto_mat`, `año_calificacion`, `estado_calificacion`, `tipo_tarifa`, `codigo_tarifa`, `created_at`, `updated_at`) VALUES
	(53, 136, 1, 'Maquinas electrónicas', 3, '2022-08-16', 150.00, 60.00, 7.50, 157.50, 525.00, 63.00, 3.00, '2009', 'calificado', 'Fija', '02.03.02.01', '2022-08-16 17:37:39', '2022-08-17 17:31:15'),
	(60, 136, 2, 'Maquinas electrónicas', 3, '2022-08-16', 150.00, 60.00, 7.50, 157.50, 525.00, 63.00, 3.00, '2010', 'Recalificado', 'Fija', '02.03.02.01', '2022-08-16 21:28:45', '2022-08-17 14:53:19'),
	(61, 136, 2, 'Maquinas electrónicas', 3, '2022-08-16', 150.00, 60.00, 7.50, 157.50, 525.00, 63.00, 3.00, '2011', 'Recalificado', 'Fija', '02.03.02.01', '2022-08-16 21:28:51', '2022-08-17 14:53:19'),
	(62, 136, 2, 'Maquinas electrónicas', 3, '2022-08-16', 150.00, 60.00, 7.50, 157.50, 525.00, 63.00, 3.00, '2012', 'Recalificado', 'Fija', '02.03.02.01', '2022-08-16 21:29:40', '2022-08-17 14:53:19'),
	(63, 136, 2, 'Maquinas electrónicas', 3, '2022-08-16', 150.00, 60.00, 7.50, 157.50, 525.00, 63.00, 3.00, '2013', 'Recalificado', 'Fija', '02.03.02.01', '2022-08-16 21:29:43', '2022-08-17 14:53:19'),
	(64, 136, 2, 'Maquinas electrónicas', 3, '2022-08-16', 150.00, 60.00, 7.50, 157.50, 525.00, 63.00, 3.00, '2014', 'Recalificado', 'Fija', '02.03.02.01', '2022-08-16 21:29:47', '2022-08-17 14:53:19'),
	(65, 136, 2, 'Maquinas electrónicas', 3, '2022-08-16', 150.00, 60.00, 7.50, 157.50, 525.00, 63.00, 3.00, '2015', 'Recalificado', 'Fija', '02.03.02.01', '2022-08-16 21:29:53', '2022-08-17 14:53:19'),
	(66, 136, 2, 'Maquinas electrónicas', 3, '2022-08-16', 150.00, 60.00, 7.50, 157.50, 525.00, 63.00, 3.00, '2016', 'Recalificado', 'Fija', '02.03.02.01', '2022-08-16 21:30:00', '2022-08-17 14:53:19'),
	(67, 136, 2, 'Maquinas electrónicas', 3, '2022-08-16', 150.00, 60.00, 7.50, 157.50, 525.00, 63.00, 3.00, '2017', 'Recalificado', 'Fija', '02.03.02.01', '2022-08-16 21:30:04', '2022-08-17 14:53:19'),
	(68, 136, 2, 'Maquinas electrónicas', 3, '2022-08-16', 150.00, 60.00, 7.50, 157.50, 525.00, 63.00, 3.00, '2018', 'Recalificado', 'Fija', '02.03.02.01', '2022-08-16 21:30:08', '2022-08-17 14:53:19'),
	(69, 136, 2, 'Maquinas electrónicas', 3, '2022-08-16', 150.00, 60.00, 7.50, 157.50, 525.00, 63.00, 3.00, '2019', 'Recalificado', 'Fija', '02.03.02.01', '2022-08-16 21:30:12', '2022-08-17 14:53:19'),
	(70, 136, 2, 'Maquinas electrónicas', 3, '2022-08-16', 150.00, 60.00, 7.50, 157.50, 525.00, 63.00, 3.00, '2020', 'Recalificado', 'Fija', '02.03.02.01', '2022-08-16 21:30:15', '2022-08-17 14:53:19'),
	(71, 136, 2, 'Maquinas electrónicas', 3, '2022-08-16', 150.00, 60.00, 7.50, 157.50, 525.00, 63.00, 3.00, '2021', 'Recalificado', 'Fija', '02.03.02.01', '2022-08-16 21:30:18', '2022-08-17 14:53:19'),
	(72, 136, 2, 'Maquinas electrónicas', 3, '2022-08-16', 150.00, 60.00, 7.50, 157.50, 525.00, 63.00, 3.00, '2022', 'Recalificado', 'Fija', '02.03.02.01', '2022-08-16 21:30:23', '2022-08-17 14:53:19'),
	(75, 148, 2, 'Sinfonolas', 1, '2022-08-29', 50.00, 5.71, 2.50, 52.50, 50.00, 6.00, 0.29, '2022', 'calificado', 'Fija', '13.6.10.2', '2022-08-29 18:04:18', '2022-08-29 18:04:18'),
	(76, 147, 2, 'Mesa de billar', 1, '2022-09-01', 6.00, 5.71, 0.30, 6.30, 50.00, 6.00, 0.29, '2021', 'calificado', 'Fija', '13.6.18.1', '2022-09-01 15:01:12', '2022-09-01 15:01:12'),
	(83, 149, 2, 'Mesa de billar', 1, '2022-09-01', 6.00, 5.71, 0.30, 6.30, 50.00, 6.00, 0.29, '2020', 'calificado', 'Fija', '13.6.18.1', '2022-09-01 16:36:58', '2022-09-01 16:36:58'),
	(84, 149, 2, 'Mesa de billar', 1, '2022-09-01', 6.00, 5.71, 0.30, 6.30, 50.00, 6.00, 0.29, '2021', 'recalificado', 'Fija', '13.6.18.1', '2022-09-01 17:02:31', '2022-09-01 17:02:31');
/*!40000 ALTER TABLE `calificacion_matriculas` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.calificacion_rotulo
CREATE TABLE IF NOT EXISTS `calificacion_rotulo` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_rotulos` bigint(20) unsigned NOT NULL DEFAULT '0',
  `id_empresa` bigint(20) unsigned NOT NULL DEFAULT '0',
  `tarifa_mensual` decimal(20,2) NOT NULL,
  `total_impuesto` decimal(20,2) NOT NULL,
  `fecha_calificacion` date NOT NULL,
  `estado_calificacion` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `año_calificacion` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `FK_calificacion_rotulo_rotulos` (`id_rotulos`),
  KEY `id_empresa` (`id_empresa`),
  CONSTRAINT `FK_calificacion_rotulo_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id`),
  CONSTRAINT `FK_calificacion_rotulo_rotulos` FOREIGN KEY (`id_rotulos`) REFERENCES `rotulos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla catmunidb2.calificacion_rotulo: ~1 rows (aproximadamente)
/*!40000 ALTER TABLE `calificacion_rotulo` DISABLE KEYS */;
INSERT IGNORE INTO `calificacion_rotulo` (`id`, `id_rotulos`, `id_empresa`, `tarifa_mensual`, `total_impuesto`, `fecha_calificacion`, `estado_calificacion`, `año_calificacion`, `created_at`, `updated_at`) VALUES
	(39, 2, 61, 2.50, 2.63, '2022-07-15', 'calificado', NULL, '2022-07-15 16:53:02', '2022-07-15 16:53:02'),
	(40, 5, 61, 10.00, 10.50, '2022-07-15', 'calificado', NULL, '2022-07-15 16:53:02', '2022-07-15 16:53:02');
/*!40000 ALTER TABLE `calificacion_rotulo` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.cierres_reaperturas
CREATE TABLE IF NOT EXISTS `cierres_reaperturas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_empresa` bigint(20) unsigned NOT NULL,
  `fecha_a_partir_de` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL DEFAULT '',
  `tipo_operacion` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_cierres_empresa` (`id_empresa`),
  CONSTRAINT `FK_cierres_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla catmunidb2.cierres_reaperturas: ~7 rows (aproximadamente)
/*!40000 ALTER TABLE `cierres_reaperturas` DISABLE KEYS */;
INSERT IGNORE INTO `cierres_reaperturas` (`id`, `id_empresa`, `fecha_a_partir_de`, `tipo_operacion`, `created_at`, `updated_at`) VALUES
	(20, 63, '2022-06-02', 'Cierre', '2022-06-07 16:35:49', '2022-06-07 16:35:49'),
	(21, 63, '2022-06-07', 'Reapertura', '2022-06-07 16:36:10', '2022-06-07 16:36:10'),
	(27, 67, '2021-06-07', 'Cierre', '2022-06-07 17:07:33', '2022-06-07 17:07:33'),
	(28, 62, '2022-06-06', 'Cierre', '2022-06-07 20:50:05', '2022-06-07 20:50:05'),
	(33, 67, '2022-06-19', 'Reapertura', '2022-06-21 19:45:53', '2022-06-21 19:45:53'),
	(34, 67, '2022-07-20', 'Cierre', '2022-06-21 19:48:13', '2022-06-21 19:48:13'),
	(35, 67, '2022-08-16', 'Reapertura', '2022-06-21 19:48:30', '2022-06-21 19:48:30');
/*!40000 ALTER TABLE `cierres_reaperturas` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.cierres_reaperturas_buses
CREATE TABLE IF NOT EXISTS `cierres_reaperturas_buses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_buses` bigint(20) unsigned NOT NULL,
  `fecha_a_partir_de` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `tipo_operacion` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `FK_cierres_reaperturas_buses_buses` (`id_buses`),
  CONSTRAINT `FK_cierres_reaperturas_buses_buses` FOREIGN KEY (`id_buses`) REFERENCES `buses` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla catmunidb2.cierres_reaperturas_buses: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `cierres_reaperturas_buses` DISABLE KEYS */;
INSERT IGNORE INTO `cierres_reaperturas_buses` (`id`, `id_buses`, `fecha_a_partir_de`, `tipo_operacion`, `created_at`, `updated_at`) VALUES
	(1, 5, '2022-07-06', 'Cierre', '2022-07-06 16:40:03', '2022-07-06 16:40:03'),
	(2, 5, '2022-07-07', 'Reapertura', '2022-07-06 16:43:16', '2022-07-06 16:43:16'),
	(3, 5, '2022-07-15', 'Cierre', '2022-07-15 16:56:05', '2022-07-15 16:56:05');
/*!40000 ALTER TABLE `cierres_reaperturas_buses` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.cobros
CREATE TABLE IF NOT EXISTS `cobros` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_empresa` bigint(20) unsigned NOT NULL,
  `id_usuario` bigint(20) unsigned NOT NULL,
  `cantidad_meses_cobro` int(20) NOT NULL DEFAULT '0',
  `impuesto_mora` decimal(20,2) DEFAULT '0.00',
  `impuesto` decimal(20,2) DEFAULT '0.00',
  `intereses_moratorios` decimal(20,2) DEFAULT '0.00',
  `monto_multa_balance` decimal(20,2) DEFAULT '0.00',
  `monto_multaPE` decimal(20,2) DEFAULT '0.00',
  `fondo_fiestasP` decimal(20,2) DEFAULT '0.00',
  `pago_total` decimal(20,2) NOT NULL DEFAULT '0.00',
  `fecha_cobro` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `periodo_cobro_inicio` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `periodo_cobro_fin` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_cobro` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cobros_id_empresa_foreign` (`id_empresa`),
  KEY `cobros_id_usuario_foreign` (`id_usuario`),
  CONSTRAINT `cobros_id_empresa_foreign` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id`),
  CONSTRAINT `cobros_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla catmunidb2.cobros: ~6 rows (aproximadamente)
/*!40000 ALTER TABLE `cobros` DISABLE KEYS */;
INSERT IGNORE INTO `cobros` (`id`, `id_empresa`, `id_usuario`, `cantidad_meses_cobro`, `impuesto_mora`, `impuesto`, `intereses_moratorios`, `monto_multa_balance`, `monto_multaPE`, `fondo_fiestasP`, `pago_total`, `fecha_cobro`, `periodo_cobro_inicio`, `periodo_cobro_fin`, `tipo_cobro`, `created_at`, `updated_at`) VALUES
	(3, 63, 2, 2, 28.61, 0.00, 3.46, 5.72, 2.86, 1.43, 42.08, '2022-06-23', '2020-12-01', '2021-01-31', 'impuesto', '2022-06-23 17:33:30', '2022-06-23 17:33:30'),
	(4, 63, 2, 17, 147.18, 80.28, 10.80, 0.00, 16.75, 11.37, 266.38, '2022-06-23', '2021-02-01', '2022-06-30', 'impuesto', '2022-06-23 17:34:17', '2022-06-23 17:34:17'),
	(6, 63, 2, 1, 0.00, 13.38, 0.00, 0.00, 0.00, 0.67, 14.05, '2022-08-12', '2022-07-01', '2022-07-31', 'impuesto', '2022-08-12 15:49:01', '2022-08-12 15:49:01'),
	(9, 69, 2, 8, 0.00, 45.68, 0.56, 0.00, 2.86, 2.28, 51.38, '2022-08-12', '2022-01-01', '2022-08-31', 'impuesto', '2022-08-12 19:49:38', '2022-08-12 19:49:38'),
	(10, 63, 2, 1, 0.00, 13.38, 0.00, 0.00, 0.00, 0.67, 14.05, '2022-08-12', '2022-08-01', '2022-08-31', 'impuesto', '2022-08-12 19:52:10', '2022-08-12 19:52:10'),
	(11, 67, 2, 8, 0.00, 224.40, 2.88, 2.86, 9.82, 11.22, 251.18, '2022-08-17', '2022-01-01', '2022-08-31', 'impuesto', '2022-08-17 17:14:39', '2022-08-17 17:14:39'),
	(12, 61, 2, 18, 110.00, 72.24, 9.96, 0.00, 14.15, 9.11, 215.46, '2022-08-23', '2021-03-01', '2022-08-31', 'impuesto', '2022-08-23 20:22:37', '2022-08-23 20:22:37'),
	(13, 68, 2, 8, 0.00, 112000.00, 1743.59, 0.00, 6300.00, 5600.00, 125643.59, '2022-09-02', '2022-01-01', '2022-08-31', 'impuesto', '2022-09-02 20:21:19', '2022-09-02 20:21:19');
/*!40000 ALTER TABLE `cobros` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.cobros_buses
CREATE TABLE IF NOT EXISTS `cobros_buses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_buses_detalle` bigint(20) unsigned NOT NULL DEFAULT '0',
  `id_empresa` bigint(20) unsigned NOT NULL DEFAULT '0',
  `id_usuario` bigint(20) unsigned NOT NULL DEFAULT '0',
  `cantidad_meses_cobro` int(10) unsigned NOT NULL DEFAULT '0',
  `impuesto_mora` decimal(20,2) NOT NULL DEFAULT '0.00',
  `impuesto` decimal(20,2) NOT NULL DEFAULT '0.00',
  `intereses_moratorios` decimal(20,2) NOT NULL DEFAULT '0.00',
  `fondo_fiestasP` decimal(20,2) NOT NULL DEFAULT '0.00',
  `pago_total` decimal(20,2) NOT NULL DEFAULT '0.00',
  `fecha_cobro` varchar(50) COLLATE utf8_spanish_ci NOT NULL DEFAULT '0',
  `periodo_cobro_inicio` varchar(50) COLLATE utf8_spanish_ci NOT NULL DEFAULT '0',
  `periodo_cobro_fin` varchar(50) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `FK_cobros_rotulo_empresa` (`id_empresa`) USING BTREE,
  KEY `id_usuario` (`id_usuario`) USING BTREE,
  KEY `FK_cobros_rotulo_rotulos` (`id_buses_detalle`) USING BTREE,
  KEY `id` (`id`),
  CONSTRAINT `FK_cobros_buses_buses_detalle` FOREIGN KEY (`id_buses_detalle`) REFERENCES `buses_detalle` (`id`),
  CONSTRAINT `FK_cobros_buses_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id`),
  CONSTRAINT `FK_cobros_buses_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci ROW_FORMAT=DYNAMIC;

-- Volcando datos para la tabla catmunidb2.cobros_buses: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `cobros_buses` DISABLE KEYS */;
INSERT IGNORE INTO `cobros_buses` (`id`, `id_buses_detalle`, `id_empresa`, `id_usuario`, `cantidad_meses_cobro`, `impuesto_mora`, `impuesto`, `intereses_moratorios`, `fondo_fiestasP`, `pago_total`, `fecha_cobro`, `periodo_cobro_inicio`, `periodo_cobro_fin`, `created_at`, `updated_at`) VALUES
	(1, 78, 62, 1, 5, 0.00, 171.40, 1.08, 8.57, 181.05, '2022-06-09', '2022-02-01', '2022-06-30', '2022-06-09 14:28:09', '2022-06-09 14:28:09'),
	(2, 79, 66, 1, 1, 17.14, 0.00, 0.99, 0.86, 18.99, '2022-06-09', '2021-09-01', '2021-09-30', '2022-06-09 16:04:23', '2022-06-09 16:04:23');
/*!40000 ALTER TABLE `cobros_buses` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.cobros_licencia_licor
CREATE TABLE IF NOT EXISTS `cobros_licencia_licor` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_empresa` bigint(20) unsigned NOT NULL,
  `id_usuario` bigint(20) unsigned NOT NULL,
  `monto_multa_licencia` decimal(20,2) DEFAULT NULL,
  `pago_total` decimal(20,2) NOT NULL DEFAULT '0.00',
  `fecha_cobro` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `periodo_cobro_inicio` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `periodo_cobro_fin` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_cobro` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `cobros_id_empresa_foreign` (`id_empresa`) USING BTREE,
  KEY `cobros_id_usuario_foreign` (`id_usuario`) USING BTREE,
  CONSTRAINT `cobros_licencia_licor_ibfk_1` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id`),
  CONSTRAINT `cobros_licencia_licor_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- Volcando datos para la tabla catmunidb2.cobros_licencia_licor: ~3 rows (aproximadamente)
/*!40000 ALTER TABLE `cobros_licencia_licor` DISABLE KEYS */;
INSERT IGNORE INTO `cobros_licencia_licor` (`id`, `id_empresa`, `id_usuario`, `monto_multa_licencia`, `pago_total`, `fecha_cobro`, `periodo_cobro_inicio`, `periodo_cobro_fin`, `tipo_cobro`, `created_at`, `updated_at`) VALUES
	(4, 63, 2, 18615.00, 18980.00, '2022-05-23 15:21:28', '2020-01-01', '2020-12-31', 'licencia', '2022-05-23 15:21:28', '2022-05-23 15:21:28'),
	(5, 63, 2, 18615.00, 18980.00, '2022-05-23 15:22:07', '2021-01-01', '2021-12-31', 'licencia', '2022-05-23 15:22:07', '2022-05-23 15:22:07'),
	(6, 67, 2, 7665.00, 8030.00, '2022-06-10 21:33:14', '2021-01-01', '2022-12-31', 'licencia', '2022-06-10 21:33:14', '2022-06-10 21:33:14');
/*!40000 ALTER TABLE `cobros_licencia_licor` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.cobros_matriculas
CREATE TABLE IF NOT EXISTS `cobros_matriculas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_matriculas_detalle` bigint(20) unsigned NOT NULL,
  `id_usuario` bigint(20) unsigned NOT NULL,
  `cantidad_meses_cobro` int(20) DEFAULT '0',
  `impuesto_mora` decimal(20,2) DEFAULT '0.00',
  `impuesto` decimal(20,2) DEFAULT '0.00',
  `intereses_moratorios` decimal(20,2) DEFAULT '0.00',
  `monto_multa_matricula` decimal(20,2) DEFAULT '0.00',
  `monto_multaPE` decimal(20,2) DEFAULT '0.00',
  `fondo_fiestasP` decimal(20,2) DEFAULT '0.00',
  `pago_total` decimal(20,2) NOT NULL DEFAULT '0.00',
  `fecha_cobro` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `periodo_cobro_inicio` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `periodo_cobro_fin` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `periodo_cobro_inicioMatricula` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `periodo_cobro_finMatricula` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `tipo_cobro` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_matriculas_detalle` (`id_matriculas_detalle`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `FK_cobros_matriculas_matriculas_detalle` FOREIGN KEY (`id_matriculas_detalle`) REFERENCES `matriculas_detalle` (`id`),
  CONSTRAINT `FK_cobros_matriculas_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla catmunidb2.cobros_matriculas: ~1 rows (aproximadamente)
/*!40000 ALTER TABLE `cobros_matriculas` DISABLE KEYS */;
INSERT IGNORE INTO `cobros_matriculas` (`id`, `id_matriculas_detalle`, `id_usuario`, `cantidad_meses_cobro`, `impuesto_mora`, `impuesto`, `intereses_moratorios`, `monto_multa_matricula`, `monto_multaPE`, `fondo_fiestasP`, `pago_total`, `fecha_cobro`, `periodo_cobro_inicio`, `periodo_cobro_fin`, `periodo_cobro_inicioMatricula`, `periodo_cobro_finMatricula`, `tipo_cobro`, `created_at`, `updated_at`) VALUES
	(27, 137, 2, 12, 0.00, 0.00, 0.00, 15.00, 0.00, 0.75, 30.75, '2022-08-16 15:29:58', '2022-01-01', '2022-12-31', NULL, NULL, 'matricula', '2022-08-16 15:29:58', '2022-08-16 15:29:58'),
	(30, 136, 2, 0, 0.00, 0.00, 0.00, 150.00, 0.00, 7.50, 307.50, '2022-08-17 17:31:15', NULL, NULL, '2009-10-01', '2009-10-31', 'matricula', '2022-08-17 17:31:15', '2022-08-17 17:31:15');
/*!40000 ALTER TABLE `cobros_matriculas` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.cobros_rotulo
CREATE TABLE IF NOT EXISTS `cobros_rotulo` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_rotulos` bigint(20) unsigned NOT NULL DEFAULT '0',
  `id_empresa` bigint(20) unsigned NOT NULL DEFAULT '0',
  `id_usuario` bigint(20) unsigned NOT NULL DEFAULT '0',
  `cantidad_meses_cobro` int(10) unsigned NOT NULL DEFAULT '0',
  `impuesto_mora` decimal(20,2) NOT NULL DEFAULT '0.00',
  `impuesto` decimal(20,2) NOT NULL DEFAULT '0.00',
  `intereses_moratorios` decimal(20,2) NOT NULL DEFAULT '0.00',
  `fondo_fiestasP` decimal(20,2) NOT NULL DEFAULT '0.00',
  `pago_total` decimal(20,2) NOT NULL DEFAULT '0.00',
  `fecha_cobro` varchar(50) COLLATE utf8_spanish_ci NOT NULL DEFAULT '0',
  `periodo_cobro_inicio` varchar(50) COLLATE utf8_spanish_ci NOT NULL DEFAULT '0',
  `periodo_cobro_fin` varchar(50) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_cobros_rotulo_rotulos` (`id_rotulos`),
  KEY `FK_cobros_rotulo_empresa` (`id_empresa`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `FK_cobros_rotulo_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id`),
  CONSTRAINT `FK_cobros_rotulo_rotulos` FOREIGN KEY (`id_rotulos`) REFERENCES `rotulos` (`id`),
  CONSTRAINT `FK_cobros_rotulo_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla catmunidb2.cobros_rotulo: ~3 rows (aproximadamente)
/*!40000 ALTER TABLE `cobros_rotulo` DISABLE KEYS */;
INSERT IGNORE INTO `cobros_rotulo` (`id`, `id_rotulos`, `id_empresa`, `id_usuario`, `cantidad_meses_cobro`, `impuesto_mora`, `impuesto`, `intereses_moratorios`, `fondo_fiestasP`, `pago_total`, `fecha_cobro`, `periodo_cobro_inicio`, `periodo_cobro_fin`, `created_at`, `updated_at`) VALUES
	(1, 4, 62, 1, 1, 2.50, 0.00, 0.07, 0.13, 2.70, '2022-05-16', '2021-12-01', '2021-12-31', '2022-05-16 15:00:11', '2022-05-16 15:00:11'),
	(2, 4, 62, 1, 5, 0.00, 37.50, 0.27, 1.88, 39.65, '2022-05-16', '2022-01-01', '2022-05-31', '2022-05-16 15:08:26', '2022-05-16 15:08:26'),
	(3, 2, 61, 1, 1, 0.00, 12.50, 0.00, 0.63, 13.13, '2022-06-15', '2022-05-01', '2022-05-31', '2022-06-15 20:09:13', '2022-06-15 20:09:13');
/*!40000 ALTER TABLE `cobros_rotulo` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.contribuyente
CREATE TABLE IF NOT EXISTS `contribuyente` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dui` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nit` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `registro_comerciante` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fax` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla catmunidb2.contribuyente: ~7 rows (aproximadamente)
/*!40000 ALTER TABLE `contribuyente` DISABLE KEYS */;
INSERT IGNORE INTO `contribuyente` (`id`, `nombre`, `apellido`, `direccion`, `dui`, `nit`, `registro_comerciante`, `telefono`, `email`, `fax`) VALUES
	(1, 'Juan Carlos', 'Perez Valle', 'Col. La Esperanza, Calles los juzgados av.  Orellana', '23456787', '02071702931048', '8876543265', '24021721', 'junaperez@gmail.com', NULL),
	(2, 'Gisselle Arlette', 'Ramirez Mancía', 'Col. las brisas del sur.', '12345678', '02071702931049', NULL, '24021826', 'gisselleramirez@gmail.com', '2202'),
	(3, 'José Leopoldo', 'Guerra Cisneros', 'Col. Las Vegas', '12345674', '02071702931049', '', '24021922', 'josecisneros@gmail.com', NULL),
	(4, 'Juan José', 'Pleitez Ruballos', 'Col. Las americas #1', '12345671', '01071702931067', '', '24027733', 'juanruballos@gmail.com', NULL),
	(5, 'Santiago Elí', 'Cartagena Mancía', 'Caserío Agua fria, Lotificación Prados de Montecristo.', '047831500', '02071702931067', '', '24021721', 'santiagocartagena@gmail.com', '2203'),
	(6, 'Jannette', 'Castaneda', 'Col. La Esperanza, Calles los juzgados', '234567878', '02071702931047', '8876544', '24021721', 'jannettecastaneda@gmail.com', NULL),
	(8, 'Wilbert Elí', 'Magaña Mancía', 'Col. la esperanza Calle los juzgados.', '234567890', '23345678675432', '2345678', '24531234', 'wilbertmagaña@gmail.com', NULL);
/*!40000 ALTER TABLE `contribuyente` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.empresa
CREATE TABLE IF NOT EXISTS `empresa` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_contribuyente` bigint(20) unsigned NOT NULL,
  `id_estado_empresa` bigint(20) unsigned NOT NULL,
  `id_giro_comercial` bigint(20) unsigned NOT NULL,
  `id_actividad_economica` bigint(20) unsigned NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `matricula_comercio` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nit` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referencia_catastral` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo_comerciante` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inicio_operaciones` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `num_tarjeta` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `num_resolucion` int(20) DEFAULT NULL,
  `excepciones_especificas` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'NO',
  PRIMARY KEY (`id`),
  KEY `empresa_id_contribuyente_foreign` (`id_contribuyente`),
  KEY `empresa_id_estado_empresa_foreign` (`id_estado_empresa`),
  KEY `empresa_id_giro_comercial_foreign` (`id_giro_comercial`),
  KEY `id_actividad_economica` (`id_actividad_economica`),
  CONSTRAINT `empresa_actividad_economica` FOREIGN KEY (`id_actividad_economica`) REFERENCES `actividad_economica` (`id`),
  CONSTRAINT `empresa_id_contribuyente_foreign` FOREIGN KEY (`id_contribuyente`) REFERENCES `contribuyente` (`id`),
  CONSTRAINT `empresa_id_estado_empresa_foreign` FOREIGN KEY (`id_estado_empresa`) REFERENCES `estado_empresa` (`id`),
  CONSTRAINT `empresa_id_giro_comercial_foreign` FOREIGN KEY (`id_giro_comercial`) REFERENCES `giro_comercial` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla catmunidb2.empresa: ~20 rows (aproximadamente)
/*!40000 ALTER TABLE `empresa` DISABLE KEYS */;
INSERT IGNORE INTO `empresa` (`id`, `id_contribuyente`, `id_estado_empresa`, `id_giro_comercial`, `id_actividad_economica`, `nombre`, `matricula_comercio`, `nit`, `referencia_catastral`, `tipo_comerciante`, `inicio_operaciones`, `direccion`, `num_tarjeta`, `telefono`, `num_resolucion`, `excepciones_especificas`) VALUES
	(61, 5, 2, 1, 1, 'Almacén y librería la confianza', NULL, '00042434454365', NULL, NULL, '2021-02-17', 'Av. Benjamín Estrada Valiente 8, Metapán', '1911', '24022852', NULL, 'NO'),
	(62, 5, 1, 1, 4, 'Constructora Flores', NULL, NULL, NULL, NULL, '2021-12-31', 'Col. San Luis, Avenida las Arboledas', '1912', '24021726', NULL, 'NO'),
	(63, 6, 2, 1, 1, 'Allis Restaurant', NULL, NULL, NULL, NULL, '2020-11-30', '3a avenida norte, Plaza la Constitución, frente a parque central.', '1913', '72682279', NULL, 'NO'),
	(64, 4, 1, 1, 1, 'Gasolinera Uno Metapán', NULL, NULL, NULL, NULL, '2019-12-16', 'CA 12N, Metapan', '1914', '24560212', NULL, 'NO'),
	(65, 5, 2, 1, 1, 'La Toscana', NULL, NULL, NULL, NULL, '2021-11-10', 'Av. Benjamín Estrada Valiente, Metapán', '1915', '72805287', NULL, 'NO'),
	(66, 1, 2, 1, 1, 'Digital Solutions', NULL, NULL, NULL, NULL, '2021-01-01', '3a avenida norte, Plaza la Constitución, frente a parque central.', '1919', '24021817', NULL, 'NO'),
	(67, 6, 2, 1, 2, 'Abejitas S.A de C.V', NULL, NULL, NULL, NULL, '2022-01-01', 'Col. la esperanza Calle los juzgados.', '1918', '24022043', NULL, 'NO'),
	(68, 3, 2, 1, 2, 'Holcim El Salvador, S.A de C.V', NULL, '06141710490010', NULL, NULL, '2021-12-31', 'Avenida El espino y Boulevard Sur, Urb. Madreselva, Antiguo Cuscatlán, La libertad', '2356', '24022224', NULL, 'SI'),
	(69, 8, 2, 1, 4, 'River House', NULL, '12345678902345', NULL, NULL, '2022-01-01', '3a avenida norte, Plaza la Constitución, frente a parque central.', '1960', '24025253', NULL, 'NO'),
	(70, 3, 2, 1, 1, 'Lemus Company, S. A. de C. V. (Farmacia Central)', NULL, NULL, NULL, NULL, '2015-07-01', '8va. Avenida Sur y 2da. Calle Oriente, Barrio Santa cruz', '600', '24021212', NULL, 'NO'),
	(71, 4, 2, 1, 4, 'Hostal Villa Blanca', NULL, NULL, NULL, NULL, '2014-08-01', 'Av. Isidro Menendez #4 entre calle 15 de sept. y 1ra Calle Ote. Barrio San Pedro.', '768', '24020214', NULL, 'NO'),
	(94, 5, 2, 3, 4, 'Tienda Diana (Maquina electrónica)', NULL, NULL, NULL, NULL, '2009-09-30', 'Av. Doctor Ignacio Gómez', '2015', '25814785', NULL, 'NO'),
	(96, 6, 2, 5, 4, 'Aparatos parlantes (Allis Restaurant)', NULL, NULL, NULL, NULL, '2022-01-01', '3a avenida norte, Plaza la Constitución, frente a parque central.', '2016', '72682279', NULL, 'NO'),
	(97, 5, 2, 4, 4, 'Prueba eliminación de matricula', NULL, NULL, NULL, NULL, '2022-08-10', 'Av. Doctor Ignacio Gómez', '2050', '24145263', NULL, 'NO'),
	(98, 1, 2, 2, 1, 'Prueba nada mas de toggle (Sinfonolas)', NULL, NULL, NULL, NULL, '2022-08-12', 'Av. Doctor Ignacio Gómez', '5856', '58749632', NULL, 'NO'),
	(99, 6, 2, 1, 2, 'Prueba Toggle SI', NULL, NULL, NULL, NULL, '2022-08-10', 'Av. Doctor Ignacio Gómez', '3000', '58749632', NULL, 'SI'),
	(100, 6, 2, 1, 1, 'Cells Shop', NULL, NULL, NULL, NULL, '2022-01-01', 'Av. Benjamín Estrada Valiente', '2040', '24026688', 344, 'NO'),
	(101, 1, 2, 1, 4, 'probando recuperar ultima resolucion', NULL, NULL, NULL, NULL, '2022-08-30', 'Av. Doctor Ignacio Gómez', '2060', '24028565', 345, 'NO'),
	(102, 6, 2, 4, 4, 'Allis Restaurant(Mesas de billar)', NULL, NULL, NULL, NULL, '2020-01-01', '3a avenida norte, Plaza la Constitución, frente a parque central.', '2041', '72682279', 346, 'NO'),
	(103, 1, 2, 1, 1, 'prueba de empresa sin calificacion', NULL, NULL, NULL, NULL, '2022-09-02', 'Av. Doctor Ignacio Gómez', '2062', '25635412', 347, 'NO');
/*!40000 ALTER TABLE `empresa` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.estado_buses
CREATE TABLE IF NOT EXISTS `estado_buses` (
  `id` bigint(20) unsigned NOT NULL,
  `estado` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla catmunidb2.estado_buses: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `estado_buses` DISABLE KEYS */;
INSERT IGNORE INTO `estado_buses` (`id`, `estado`) VALUES
	(1, 'Cerrado'),
	(2, 'Activo');
/*!40000 ALTER TABLE `estado_buses` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.estado_empresa
CREATE TABLE IF NOT EXISTS `estado_empresa` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `estado` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla catmunidb2.estado_empresa: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `estado_empresa` DISABLE KEYS */;
INSERT IGNORE INTO `estado_empresa` (`id`, `estado`) VALUES
	(1, 'Cerrado'),
	(2, 'Activo');
/*!40000 ALTER TABLE `estado_empresa` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.estado_licencia_licor
CREATE TABLE IF NOT EXISTS `estado_licencia_licor` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `estado` varchar(50) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla catmunidb2.estado_licencia_licor: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `estado_licencia_licor` DISABLE KEYS */;
INSERT IGNORE INTO `estado_licencia_licor` (`id`, `estado`) VALUES
	(1, 'Cancelada'),
	(2, 'Sin Cancelar');
/*!40000 ALTER TABLE `estado_licencia_licor` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.estado_matricula
CREATE TABLE IF NOT EXISTS `estado_matricula` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `estado` varchar(50) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla catmunidb2.estado_matricula: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `estado_matricula` DISABLE KEYS */;
INSERT IGNORE INTO `estado_matricula` (`id`, `estado`) VALUES
	(1, 'Cancelada'),
	(2, 'Sin Cancelar');
/*!40000 ALTER TABLE `estado_matricula` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.estado_moratorio
CREATE TABLE IF NOT EXISTS `estado_moratorio` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `estado` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla catmunidb2.estado_moratorio: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `estado_moratorio` DISABLE KEYS */;
INSERT IGNORE INTO `estado_moratorio` (`id`, `estado`) VALUES
	(1, 'Solvente'),
	(2, 'En mora');
/*!40000 ALTER TABLE `estado_moratorio` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.estado_multa
CREATE TABLE IF NOT EXISTS `estado_multa` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `estado` varchar(50) CHARACTER SET utf32 COLLATE utf32_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla catmunidb2.estado_multa: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `estado_multa` DISABLE KEYS */;
INSERT IGNORE INTO `estado_multa` (`id`, `estado`) VALUES
	(1, 'Cancelada'),
	(2, 'Sin Cancelar');
/*!40000 ALTER TABLE `estado_multa` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.estado_rotulo
CREATE TABLE IF NOT EXISTS `estado_rotulo` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `estado` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla catmunidb2.estado_rotulo: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `estado_rotulo` DISABLE KEYS */;
INSERT IGNORE INTO `estado_rotulo` (`id`, `estado`) VALUES
	(1, 'Cerrado'),
	(2, 'Activo');
/*!40000 ALTER TABLE `estado_rotulo` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.giro_comercial
CREATE TABLE IF NOT EXISTS `giro_comercial` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre_giro` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `matricula` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'N/A',
  `slug` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'N/A',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla catmunidb2.giro_comercial: ~5 rows (aproximadamente)
/*!40000 ALTER TABLE `giro_comercial` DISABLE KEYS */;
INSERT IGNORE INTO `giro_comercial` (`id`, `nombre_giro`, `matricula`, `slug`) VALUES
	(1, 'Empresas', 'N/A', 'empresas'),
	(2, 'Sinfonolas', 'SI', 'sinfonolas'),
	(3, 'Maquinas Electrónicas', 'SI', 'maquinas_electronicas'),
	(4, 'Mesas de billar', 'SI', 'mesa_de_billar'),
	(5, 'Aparatos Parlantes', 'SI', 'aparatos_parlantes');
/*!40000 ALTER TABLE `giro_comercial` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.interes
CREATE TABLE IF NOT EXISTS `interes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `monto_interes` decimal(20,2) NOT NULL,
  `fecha_inicio` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `fecha_fin` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla catmunidb2.interes: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `interes` DISABLE KEYS */;
INSERT IGNORE INTO `interes` (`id`, `monto_interes`, `fecha_inicio`, `fecha_fin`, `created_at`, `updated_at`) VALUES
	(2, 8.00, '2022-05-01', '2022-05-31', '2022-03-08 08:46:04', '2022-06-15 21:38:09'),
	(3, 9.51, '2022-06-01', '2022-06-30', '2022-06-15 21:37:29', '2022-06-15 21:38:23');
/*!40000 ALTER TABLE `interes` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.matriculas
CREATE TABLE IF NOT EXISTS `matriculas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_permiso` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `codigo` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `monto` decimal(20,2) NOT NULL DEFAULT '0.00',
  `tarifa` decimal(20,2) NOT NULL DEFAULT '0.00',
  `slug` varchar(50) COLLATE utf8_spanish_ci NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla catmunidb2.matriculas: ~5 rows (aproximadamente)
/*!40000 ALTER TABLE `matriculas` DISABLE KEYS */;
INSERT IGNORE INTO `matriculas` (`id`, `nombre`, `tipo_permiso`, `codigo`, `monto`, `tarifa`, `slug`) VALUES
	(1, 'Mesa de billar', 'Matrícula', '13.6.18.1', 6.00, 5.71, 'mesa_de_billar'),
	(2, 'Aparatos parlantes', 'Matrícula', NULL, 15.00, 0.00, 'aparatos_parlantes'),
	(3, 'Maquinas electrónicas', 'Matrícula', '02.03.02.01', 50.00, 20.00, 'maquinas_electronicas'),
	(4, 'Sinfonolas', 'Matrícula', '13.6.10.2', 50.00, 5.71, 'sinfonolas'),
	(5, 'Licencia licor', 'Licencia', '02.03.02', 365.00, 6.00, 'licencia_licor');
/*!40000 ALTER TABLE `matriculas` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.matriculas_detalle
CREATE TABLE IF NOT EXISTS `matriculas_detalle` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_empresa` bigint(20) unsigned NOT NULL,
  `id_matriculas` bigint(20) unsigned NOT NULL,
  `id_estado_moratorio` bigint(20) unsigned NOT NULL,
  `cantidad` int(20) NOT NULL,
  `monto` decimal(20,2) NOT NULL,
  `pago_mensual` decimal(20,2) NOT NULL,
  `estado_especificacion` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_empresa` (`id_empresa`),
  KEY `id_matriculas` (`id_matriculas`),
  KEY `id_estado_moratorio` (`id_estado_moratorio`),
  CONSTRAINT `FK_matriculas_detalle_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id`),
  CONSTRAINT `FK_matriculas_detalle_estado_moratorio` FOREIGN KEY (`id_estado_moratorio`) REFERENCES `estado_moratorio` (`id`),
  CONSTRAINT `FK_matriculas_detalle_matriculas` FOREIGN KEY (`id_matriculas`) REFERENCES `matriculas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=150 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla catmunidb2.matriculas_detalle: ~5 rows (aproximadamente)
/*!40000 ALTER TABLE `matriculas_detalle` DISABLE KEYS */;
INSERT IGNORE INTO `matriculas_detalle` (`id`, `id_empresa`, `id_matriculas`, `id_estado_moratorio`, `cantidad`, `monto`, `pago_mensual`, `estado_especificacion`) VALUES
	(136, 94, 3, 2, 3, 150.00, 60.00, 'especificada'),
	(137, 96, 2, 1, 1, 15.00, 0.00, 'especificada'),
	(147, 97, 1, 1, 1, 6.00, 5.71, NULL),
	(148, 98, 4, 1, 1, 50.00, 5.71, 'especificada'),
	(149, 102, 1, 2, 1, 6.00, 5.71, 'especificada');
/*!40000 ALTER TABLE `matriculas_detalle` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.matriculas_detalle_especifico
CREATE TABLE IF NOT EXISTS `matriculas_detalle_especifico` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_matriculas_detalle` bigint(20) unsigned NOT NULL,
  `cod_municipal` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `codigo` int(20) DEFAULT NULL,
  `num_serie` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `direccion` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_matriculas_detalle_especifico_matriculas_detalle` (`id_matriculas_detalle`),
  CONSTRAINT `FK_matriculas_detalle_especifico_matriculas_detalle` FOREIGN KEY (`id_matriculas_detalle`) REFERENCES `matriculas_detalle` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla catmunidb2.matriculas_detalle_especifico: ~5 rows (aproximadamente)
/*!40000 ALTER TABLE `matriculas_detalle_especifico` DISABLE KEYS */;
INSERT IGNORE INTO `matriculas_detalle_especifico` (`id`, `id_matriculas_detalle`, `cod_municipal`, `codigo`, `num_serie`, `direccion`) VALUES
	(51, 136, 'N/A', 1212, '2015  _03_019', 'Av. Doctor Ignacio Gómez, el calvario'),
	(52, 136, 'N/A', 1213, '2015  _03_020', 'Av. Doctor Ignacio Gómez, barrio el calvario'),
	(54, 136, 'N/A', 1214, '2015  _03_021', 'Av. Doctor Ignacio Gómez, barrio el calvario'),
	(55, 137, 'N/A', 12210, 'Perifoneo Allis Restaurant', '3a avenida norte, Plaza la Constitución, frente a parque central.'),
	(56, 148, 'N/A', 11899, 'N/A', 'Av Benjamin E. Valiente entre 2a y 4a Calle Poniente Barrio Las Flores'),
	(57, 149, 'N/A', 11899, 'N/A', '3a avenida norte, Plaza la Constitución, frente a parque central.');
/*!40000 ALTER TABLE `matriculas_detalle_especifico` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla catmunidb2.migrations: ~14 rows (aproximadamente)
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT IGNORE INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(2, '2021_12_01_201026_create_permission_tables', 1),
	(3, '2022_12_01_201126_create_usuario_table', 1),
	(4, '2021_12_09_201728_create_contribuyente_table', 2),
	(5, '2021_12_09_205744_create_giro_comercial_table', 3),
	(6, '2021_12_09_210026_create_estado_empresa_table', 4),
	(7, '2021_12_09_210114_create_empresa_table', 5),
	(8, '2022_01_05_171926_create_actividad_economica_table', 6),
	(9, '2022_01_05_173522_create_giro_comercial_table', 7),
	(10, '2022_01_11_170949_create_detalle_actividad_economica_table', 8),
	(11, '2022_01_19_210130_create_interes_table', 9),
	(12, '2022_01_24_174444_create_cobros_table', 10),
	(13, '2022_01_24_180510_create_cobros_table', 11),
	(14, '2022_01_24_161636_create_tarifa_fija_table', 12);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.model_has_permissions
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla catmunidb2.model_has_permissions: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.model_has_roles
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla catmunidb2.model_has_roles: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT IGNORE INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
	(2, 'App\\Models\\Usuario', 1),
	(2, 'App\\Models\\Usuario', 2);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.multas
CREATE TABLE IF NOT EXISTS `multas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `codigo` int(20) NOT NULL DEFAULT '0',
  `tipo_multa` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla catmunidb2.multas: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `multas` DISABLE KEYS */;
INSERT IGNORE INTO `multas` (`id`, `codigo`, `tipo_multa`) VALUES
	(1, 15313, 'Multas al comercio');
/*!40000 ALTER TABLE `multas` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla catmunidb2.permissions: ~3 rows (aproximadamente)
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT IGNORE INTO `permissions` (`id`, `name`, `description`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'seccion.roles.y.permisos', 'Cuando hace login, se podra visualizar roles y permisos', 'web', '2021-12-09 17:24:42', '2021-12-09 17:24:42'),
	(2, 'url.empresa.crear.index', 'Cuando hace login, se redirigirá la vista Empresas Crear', 'web', '2021-12-09 17:24:42', '2021-12-09 17:24:42'),
	(3, 'url.inmueble.crear.index', 'Cuando hace login, se redirigirá la vista Inmuebles Crear', 'web', '2021-12-09 17:24:43', '2021-12-09 17:24:43');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla catmunidb2.personal_access_tokens: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla catmunidb2.roles: ~4 rows (aproximadamente)
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT IGNORE INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'Encargado-Administrador', 'web', '2021-12-09 17:24:42', '2021-12-09 17:24:42'),
	(2, 'Encargado-Empresas', 'web', '2021-12-09 17:24:42', '2021-12-09 17:24:42'),
	(3, 'Encargado-Inmuebles', 'web', '2021-12-09 17:24:42', '2021-12-09 17:24:42'),
	(4, 'Inspector de campo - Rótulos', 'web', '2022-03-03 20:54:32', '2022-03-03 20:54:32');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.role_has_permissions
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla catmunidb2.role_has_permissions: ~3 rows (aproximadamente)
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
INSERT IGNORE INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
	(1, 1),
	(2, 2),
	(3, 3);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.rotulos
CREATE TABLE IF NOT EXISTS `rotulos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_empresa` bigint(20) unsigned NOT NULL,
  `id_estado_rotulo` bigint(20) unsigned NOT NULL DEFAULT '2',
  `nom_rotulo` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `direccion` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `actividad_economica` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_apertura` date NOT NULL,
  `permiso_instalacion` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `medidas` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `total_medidas` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `total_caras` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `coordenadas` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `imagen` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_cierre` date DEFAULT NULL,
  `nom_inspeccion` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `cargo_inspeccion` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_rotulos_empresa` (`id_empresa`),
  KEY `id_estado_rotulo` (`id_estado_rotulo`),
  CONSTRAINT `FK_rotulos_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id`),
  CONSTRAINT `FK_rotulos_estado_rotulo` FOREIGN KEY (`id_estado_rotulo`) REFERENCES `estado_rotulo` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla catmunidb2.rotulos: ~4 rows (aproximadamente)
/*!40000 ALTER TABLE `rotulos` DISABLE KEYS */;
INSERT IGNORE INTO `rotulos` (`id`, `id_empresa`, `id_estado_rotulo`, `nom_rotulo`, `direccion`, `actividad_economica`, `fecha_apertura`, `permiso_instalacion`, `medidas`, `total_medidas`, `total_caras`, `coordenadas`, `imagen`, `fecha_cierre`, `nom_inspeccion`, `cargo_inspeccion`) VALUES
	(1, 65, 2, 'Rótulo La Toscana', 'Colonia Jardines de Metapán', 'Valla publicitaria', '2022-03-21', 'Temporal', '1 metro de alto por 1.25 metro de ancho', '1.25', '2', '14°19\'51\'\'N, 89°26\'34\'\'W', 'kchVOUp04cYEJFN0.02831000_1647962986.jpg', NULL, 'Roberto Solito', 'Inspector de campo'),
	(2, 61, 2, 'Rótulo Almacen y Libreria La Confianza', 'Metapán', 'Valla publicitaria', '2022-03-15', 'Permanente', '1 metros de ancho por 1.25 metros de largo', '1.25', '1', '24°19\'51\'\'N, 89°26\'34\'\'W', 't7to48hbQiVcCZI0.82238900_1648139499.png', NULL, 'Roberto Solito', 'Inspector de campo'),
	(4, 62, 2, 'Rótulo Constructora Flores', 'Colonia Jardines de Metapán', 'Valla publicitaria', '2022-03-15', 'Temporal', '2 metros de largo por 1  metros de ancho', '2', '1', '14°19\'51\'\'N, 89°26\'34\'\'A', 'lP1sLTzo9m5e72A0.87754300_1648071768.png', NULL, 'Roberto Solito', 'Inspector de campo'),
	(5, 61, 2, 'Rótulo Almacen y Libreria La Confianza (una cara)', 'Col. Jardines de Metapán', 'Valla publicitaria', '2022-03-23', 'Permanente', '1.50 metro de alto por 3 metros de ancho', '3.50', '2', '14°19\'51\'\'N, 89°26\'34\'\'S', 'Dm1eEhYgfBgWCsB0.95845000_1648151565.png', NULL, 'Roberto Solito', 'Inspector de campo');
/*!40000 ALTER TABLE `rotulos` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.tarifa_bus
CREATE TABLE IF NOT EXISTS `tarifa_bus` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `monto_tarifa` decimal(20,2) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla catmunidb2.tarifa_bus: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `tarifa_bus` DISABLE KEYS */;
INSERT IGNORE INTO `tarifa_bus` (`id`, `monto_tarifa`, `created_at`, `updated_at`) VALUES
	(1, 17.14, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
/*!40000 ALTER TABLE `tarifa_bus` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.tarifa_fija
CREATE TABLE IF NOT EXISTS `tarifa_fija` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_actividad_economica` bigint(20) unsigned NOT NULL,
  `id_actividad_especifica` bigint(20) unsigned NOT NULL,
  `codigo` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `limite_inferior` decimal(10,2) DEFAULT NULL,
  `limite_superior` decimal(10,2) DEFAULT NULL,
  `impuesto_mensual` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `id_actividad_especifica` (`id_actividad_especifica`),
  KEY `id_actividad_economica` (`id_actividad_economica`),
  CONSTRAINT `FK_tarifa_fija_actividad_economica` FOREIGN KEY (`id_actividad_economica`) REFERENCES `actividad_economica` (`id`),
  CONSTRAINT `FK_tarifa_fija_actividad_especifica` FOREIGN KEY (`id_actividad_especifica`) REFERENCES `actividad_especifica` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=178 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla catmunidb2.tarifa_fija: ~177 rows (aproximadamente)
/*!40000 ALTER TABLE `tarifa_fija` DISABLE KEYS */;
INSERT IGNORE INTO `tarifa_fija` (`id`, `id_actividad_economica`, `id_actividad_especifica`, `codigo`, `limite_inferior`, `limite_superior`, `impuesto_mensual`) VALUES
	(1, 2, 1, '13.1.2.1', NULL, NULL, 75.00),
	(2, 2, 2, '13.1.2.2', NULL, NULL, 40.00),
	(3, 2, 3, '13.1.3', NULL, NULL, 75.00),
	(4, 2, 4, '13.1.4', NULL, NULL, 75.00),
	(5, 2, 5, '13.1.6', NULL, NULL, 75.00),
	(6, 2, 6, '13.1.7.1', NULL, 10000.00, 25.00),
	(7, 2, 6, '13.1.7.2', 10000.01, 25000.00, 50.00),
	(8, 2, 7, '13.1.7.2', NULL, NULL, 50.00),
	(9, 2, 8, '13.1.9.1', NULL, 10000.00, 15.00),
	(10, 2, 8, '13.1.9.2', 10000.01, 25000.00, 25.00),
	(11, 2, 9, '13.1.10.1', NULL, 10000.00, 15.00),
	(12, 2, 9, '13.1.10.1', 10000.01, 25000.00, 25.00),
	(13, 2, 10, '13.1.30', NULL, NULL, 4.00),
	(14, 1, 11, '13.2.1.1', NULL, 10000.00, 15.00),
	(15, 1, 11, '13.2.1.2', 10000.01, 25000.00, 25.00),
	(16, 1, 12, '13.2.2', NULL, NULL, 100.00),
	(17, 1, 13, '13.2.3', NULL, NULL, 100.00),
	(18, 1, 14, '13.2.4', NULL, NULL, 100.00),
	(19, 1, 15, '13.2.5', NULL, NULL, 75.00),
	(20, 1, 17, '13.2.6', NULL, NULL, 30.00),
	(21, 1, 18, '13.2.7.1', NULL, 5000.00, 25.00),
	(22, 1, 18, '13.2.7.2', 5000.01, 10000.00, 50.00),
	(23, 1, 18, '13.2.7.3', 10000.01, 25000.00, 100.00),
	(24, 1, 19, '13.2.8', NULL, NULL, 75.00),
	(25, 1, 20, '13.2.9.1', NULL, 5000.00, 25.00),
	(26, 1, 20, '13.2.9.2', 5000.01, 10000.00, 50.00),
	(27, 1, 20, '13.2.9.3', 10000.01, 25000.00, 100.00),
	(28, 1, 21, '13.2.10.1', NULL, 5000.00, 25.00),
	(29, 1, 21, '13.2.10.2', 5000.01, 10000.00, 50.00),
	(30, 1, 21, '13.2.10.3', 10000.01, 25000.00, 100.00),
	(31, 1, 22, '13.2.11.1', NULL, 10000.00, 10.00),
	(32, 1, 22, '13.2.11.2', 10000.01, 25000.00, 20.00),
	(33, 1, 23, '13.2.12.1', NULL, 5000.00, 25.00),
	(34, 1, 23, '13.2.12.2', 5000.01, 10000.00, 50.00),
	(35, 1, 23, '13.2.12.3', 10000.01, 25000.00, 100.00),
	(36, 1, 24, '13.2.15.1', NULL, 5000.00, 10.00),
	(37, 1, 24, '13.2.15.2', 5000.01, 10000.00, 25.00),
	(38, 1, 24, '13.2.15.3', 10000.01, 25000.00, 50.00),
	(39, 1, 25, '13.2.16.1', 2000.00, 5000.00, 15.00),
	(40, 1, 25, '13.2.16.2', 5000.01, 25000.00, 25.00),
	(41, 1, 26, '13.2.17.1', NULL, 10000.00, 75.00),
	(42, 1, 26, '13.2.17.2', 10000.01, 25000.00, 100.00),
	(43, 1, 27, '13.2.19.1', NULL, 10000.00, 15.00),
	(44, 1, 27, '13.2.19.2', 10000.01, 25000.00, 25.00),
	(45, 1, 28, '13.2.20.1', NULL, 5000.00, 10.00),
	(46, 1, 28, '13.2.20.2', 5000.01, 10000.00, 15.00),
	(47, 1, 28, '13.2.20.3', 10000.01, 25000.00, 20.00),
	(48, 1, 29, '13.2.21.1', NULL, 5000.00, 10.00),
	(49, 1, 29, '13.2.21.2', 5000.01, 10000.00, 15.00),
	(50, 1, 29, '13.2.21.3', 10000.01, 25000.00, 25.00),
	(51, 1, 30, '13.2.22', NULL, NULL, 25.00),
	(52, 1, 31, '13.2.23', NULL, NULL, 50.00),
	(53, 1, 32, '13.2.24', NULL, NULL, 50.00),
	(54, 1, 33, '13.2.26.1', NULL, 10000.00, 35.00),
	(55, 1, 33, '13.2.26.2', 10000.01, 25.00, 50.00),
	(56, 1, 34, '13.2.32.1', NULL, 10000.00, 35.00),
	(57, 1, 34, '13.2.32.2', 10000.01, 25000.00, 50.00),
	(58, 4, 35, '13.3.1.1', NULL, 10000.00, 50.00),
	(59, 4, 35, '13.3.1.2', 10000.01, 25000.00, 75.00),
	(60, 4, 36, '13.3.2.1', NULL, 5000.00, 25.00),
	(61, 4, 36, '13.3.2.2', 5000.01, 10000.00, 50.00),
	(62, 4, 36, '13.3.2.3', 10000.01, 25000.00, 75.00),
	(63, 4, 37, '13.3.4.1', NULL, NULL, 100.00),
	(64, 4, 38, '13.3.4.2', NULL, NULL, 50.00),
	(65, 4, 39, '13.3.4.3', NULL, NULL, 50.00),
	(66, 4, 40, '13.3.5.1', NULL, 10000.00, 25.00),
	(67, 4, 40, '13.3.5.2', 10000.01, 25000.00, 50.00),
	(68, 4, 41, '13.3.6.1', NULL, 10000.00, 75.00),
	(69, 4, 41, '13.3.6.2', 10000.01, 25000.00, 100.00),
	(70, 4, 42, '13.3.9', NULL, NULL, 50.00),
	(71, 4, 43, '13.3.11', NULL, NULL, 50.00),
	(72, 4, 44, '13.3.12.1', NULL, 5000.00, 10.00),
	(73, 4, 44, '13.3.12.2', 5000.01, 10000.00, 15.00),
	(74, 4, 44, '13.3.12.3', 10000.01, 25000.00, 20.00),
	(75, 4, 45, '13.3.14', NULL, NULL, 75.00),
	(76, 4, 46, '13.3.15.1', NULL, 10000.00, 30.00),
	(77, 4, 46, '13.3.15.2', 10000.01, 25000.00, 40.00),
	(78, 4, 47, '13.3.16.1', NULL, 10000.00, 10.00),
	(79, 4, 47, '13.3.16.2', 10000.01, 25000.00, 25.00),
	(80, 4, 48, '13.3.17.1', NULL, NULL, 100.00),
	(81, 4, 49, '13.3.17.2', NULL, NULL, 100.00),
	(82, 4, 50, '13.3.29', NULL, NULL, 50.00),
	(83, 1, 51, '13.4.2', NULL, NULL, 50.00),
	(84, 1, 52, '13.4.3', NULL, NULL, 10.00),
	(85, 1, 53, '13.4.4', NULL, NULL, 10.00),
	(86, 1, 54, '13.4.6', NULL, NULL, 5.00),
	(87, 1, 55, '13.4.7', NULL, NULL, 15.00),
	(88, 1, 56, '13.4.8', NULL, NULL, 10.00),
	(89, 1, 57, '13.4.9.1', NULL, 10000.00, 15.00),
	(90, 1, 57, '13.4.9.2', 10000.01, 25000.00, 25.00),
	(91, 1, 58, '13.4.10', NULL, NULL, 25.00),
	(92, 1, 59, '13.4.11.1', NULL, 10000.00, 25.00),
	(93, 1, 59, '13.4.11.2', 10000.01, 25000.00, 50.00),
	(94, 1, 60, '13.4.13.1', NULL, 10000.00, 15.00),
	(95, 1, 60, '13.4.13.2', 10000.01, 25000.00, 25.00),
	(96, 1, 61, '13.4.13.2', NULL, NULL, 10.00),
	(97, 2, 62, '13.4.16', NULL, NULL, 15.00),
	(98, 1, 63, '13.4.17.1', NULL, 10000.00, 50.00),
	(99, 1, 63, '13.4.17.2', 10000.01, 25000.00, 75.00),
	(100, 4, 64, '13.5.2.1', NULL, 15000.00, 50.00),
	(101, 4, 64, '13.5.2.2', 10000.01, 25000.00, 100.00),
	(102, 4, 65, '13.5.4.1', NULL, 10000.00, 15.00),
	(103, 4, 65, '13.5.4.2', 10000.01, 25000.00, 25.00),
	(104, 1, 66, '13.5.5.1', NULL, NULL, 50.00),
	(105, 1, 67, '13.5.5.2.1', NULL, 10000.00, 10.00),
	(106, 1, 67, '13.5.5.2.2', 10000.01, 25000.00, 25.00),
	(107, 4, 68, '13.5.6.1', NULL, 10000.00, 10.00),
	(108, 4, 68, '13.5.6.2', 10000.01, 25000.00, 25.00),
	(109, 4, 69, '13.5.7.1', NULL, 10000.00, 25.00),
	(110, 4, 69, '13.5.7.2', 10000.01, 25000.00, 50.00),
	(111, 4, 70, '13.5.9.1', NULL, 10000.00, 15.00),
	(112, 4, 70, '13.5.9.2', 10000.01, 25000.00, 0.00),
	(113, 4, 71, '13.5.10', NULL, NULL, 5.00),
	(114, 4, 72, '13.5.11', NULL, NULL, 25.00),
	(115, 4, 73, '13.5.12.1', NULL, 10000.00, 15.00),
	(116, 4, 73, '13.5.12.2', 10000.01, 25000.00, 25.00),
	(117, 4, 74, '13.5.13', NULL, NULL, 25.00),
	(118, 4, 75, '13.5.14.1', NULL, 10000.00, 25.00),
	(119, 4, 75, '13.5.14.2', 10000.01, 25000.00, 50.00),
	(120, 4, 76, '13.5.15.1', NULL, 10000.00, 5.00),
	(121, 4, 76, '13.5.15.2', 10000.01, 25000.00, 10.00),
	(122, 4, 77, '13.5.16.1', NULL, 10000.00, 15.00),
	(123, 4, 77, '13.5.16.2', 10000.01, 25000.00, 25.00),
	(124, 4, 78, '13.5.30', NULL, NULL, 15.00),
	(125, 1, 79, '13.16.1.1', NULL, NULL, 100.00),
	(126, 1, 80, '13.16.1.2', NULL, NULL, 200.00),
	(127, 4, 81, '13.6.2', NULL, NULL, 50.00),
	(128, 4, 82, '13.6.3', NULL, NULL, 50.00),
	(129, 4, 83, '13.6.4.1', NULL, NULL, 150.00),
	(130, 4, 84, '13.6.4.2', NULL, NULL, 50.00),
	(131, 4, 85, '13.6.4.3', NULL, NULL, 100.00),
	(132, 1, 86, '13.6.6', NULL, NULL, 15.00),
	(133, 1, 87, '13.6.7', NULL, NULL, 50.00),
	(134, 4, 88, '13.6.8.1', NULL, NULL, 25.00),
	(135, 4, 89, '13.6.8.2', NULL, NULL, 50.00),
	(136, 4, 90, '13.6.8.3', NULL, NULL, 75.00),
	(137, 3, 91, '13.6.9.1', NULL, 15000.00, 100.00),
	(138, 3, 91, '13.6.9.2', 15000.01, 25000.00, 150.00),
	(139, 4, 92, '13.6.10.1', NULL, NULL, 10.00),
	(140, 4, 93, '13.6.10.2', NULL, NULL, 50.00),
	(141, 4, 94, '13.6.10.3', NULL, NULL, 50.00),
	(142, 2, 95, '13.6.11.1', NULL, NULL, 100.00),
	(143, 2, 96, '13.6.11.2', NULL, NULL, 200.00),
	(144, 2, 97, '13.6.11.3', NULL, NULL, 300.00),
	(145, 4, 98, '13.6.12.4.1', NULL, NULL, 2.00),
	(146, 4, 99, '13.6.12.4.2', NULL, NULL, 8.00),
	(147, 4, 100, '13.6.12.4.3', NULL, NULL, 10.00),
	(148, 4, 101, '13.6.12.4.4', NULL, NULL, 15.00),
	(149, 2, 102, '13.6.16.1', NULL, NULL, 50.00),
	(150, 2, 103, '13.6.16.2', NULL, NULL, 75.00),
	(151, 2, 104, '13.6.17.1', NULL, NULL, 250.00),
	(152, 2, 105, '13.6.17.2', NULL, NULL, 500.00),
	(153, 4, 106, '13.6.18.1', NULL, NULL, 50.00),
	(154, 4, 107, '13.6.18.2', NULL, NULL, 15.00),
	(155, 4, 108, '13.6.18.3', NULL, NULL, 100.00),
	(156, 4, 109, '13.6.18.4', NULL, NULL, 100.00),
	(157, 2, 110, '13.6.19', NULL, NULL, 10.00),
	(158, 1, 111, '13.6.20.1', NULL, NULL, 100.00),
	(159, 1, 112, '13.6.20.2', NULL, NULL, 250.00),
	(160, 1, 113, '13.6.20.3', NULL, NULL, 500.00),
	(161, 4, 114, '13.6.22.1', NULL, NULL, 10.00),
	(162, 4, 115, '13.6.22.2', NULL, NULL, 20.00),
	(163, 4, 116, '13.6.23', NULL, NULL, 50.00),
	(164, 4, 117, '13.6.24', NULL, NULL, 75.00),
	(165, 1, 118, '13.6.25', NULL, NULL, 0.00),
	(166, 2, 119, '13.6.26', NULL, NULL, 50.00),
	(167, 2, 120, '13.6.31.1', NULL, NULL, 0.00),
	(168, 2, 121, '13.6.31.2', NULL, NULL, 0.00),
	(169, 2, 122, '13.6.31.3', NULL, NULL, 1.00),
	(170, 2, 123, '13.6.42.1', NULL, NULL, 40.00),
	(171, 2, 124, '13.6.42.2', NULL, NULL, 50.00),
	(172, 4, 125, '13.6.46.1', NULL, NULL, 250.00),
	(173, 4, 126, '13.6.46.2', NULL, NULL, 350.00),
	(174, 2, 127, '13.6.49', NULL, NULL, 0.00),
	(175, 1, 128, '13.6.50', NULL, NULL, 1000.00),
	(176, 1, 129, '13.6.54', NULL, NULL, 400.00),
	(177, 1, 130, '13.6.55', NULL, NULL, 800.00);
/*!40000 ALTER TABLE `tarifa_fija` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.tarifa_rotulo
CREATE TABLE IF NOT EXISTS `tarifa_rotulo` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `limite_inferior` decimal(20,2) DEFAULT '0.00',
  `limite_superior` decimal(20,2) NOT NULL DEFAULT '0.00',
  `monto_tarifa` decimal(20,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla catmunidb2.tarifa_rotulo: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `tarifa_rotulo` DISABLE KEYS */;
INSERT IGNORE INTO `tarifa_rotulo` (`id`, `limite_inferior`, `limite_superior`, `monto_tarifa`) VALUES
	(1, 0.00, 2.00, 2.50),
	(2, 2.01, 8.00, 5.00);
/*!40000 ALTER TABLE `tarifa_rotulo` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.tarifa_variable
CREATE TABLE IF NOT EXISTS `tarifa_variable` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_actividad_economica` bigint(20) unsigned NOT NULL,
  `limite_inferior` decimal(10,2) DEFAULT NULL,
  `limite_superior` decimal(10,2) DEFAULT NULL,
  `fijo` decimal(10,2) DEFAULT NULL,
  `millar` decimal(10,2) DEFAULT NULL,
  `excedente` decimal(10,2) DEFAULT NULL,
  `categoria` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `FK_detalle_actividad_economica_actividad_economica` (`id_actividad_economica`) USING BTREE,
  CONSTRAINT `tarifa_variable_ibfk_1` FOREIGN KEY (`id_actividad_economica`) REFERENCES `actividad_economica` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci ROW_FORMAT=DYNAMIC;

-- Volcando datos para la tabla catmunidb2.tarifa_variable: ~25 rows (aproximadamente)
/*!40000 ALTER TABLE `tarifa_variable` DISABLE KEYS */;
INSERT IGNORE INTO `tarifa_variable` (`id`, `id_actividad_economica`, `limite_inferior`, `limite_superior`, `fijo`, `millar`, `excedente`, `categoria`) VALUES
	(1, 2, 0.00, 100000.01, 0.00, 0.00, 0.00, 0),
	(2, 2, 100000.01, 500000.00, 233.00, 0.40, 100000.00, 1),
	(3, 2, 500000.01, 2500000.00, 723.00, 0.40, 500000.00, 2),
	(4, 2, 2500000.01, 5000000.00, 1853.00, 0.30, 2500000.00, 3),
	(5, 2, 5000000.01, 5000000.01, 3553.00, 0.30, 5000000.00, 4),
	(6, 1, NULL, 25000.00, NULL, 0.00, 0.00, 0),
	(7, 1, 25000.00, 50000.00, 50.00, 0.80, 25000.00, 1),
	(8, 1, 50000.00, 100000.00, 63.00, 0.80, 50000.00, 2),
	(9, 1, 100000.00, 500000.00, 233.00, 0.60, 100000.00, 3),
	(10, 1, 500000.00, 2500000.00, 723.00, 0.60, 500000.00, 4),
	(11, 1, 2500000.00, 5000000.00, 1853.00, 0.60, 2500000.00, 5),
	(12, 1, 5000000.01, 5000000.01, 3553.00, 0.50, 5000000.00, 6),
	(13, 4, NULL, 25000.00, NULL, 0.00, 0.00, 0),
	(14, 4, 25000.01, 50000.00, 50.00, 0.80, 25000.00, 1),
	(15, 4, 50000.01, 100000.00, 63.00, 0.80, 50000.00, 2),
	(16, 4, 100000.01, 500000.00, 233.00, 0.60, 100000.00, 3),
	(17, 4, 500000.01, 2500000.00, 723.00, 0.60, 500000.00, 4),
	(18, 4, 2500000.01, 5000000.00, 1853.00, 0.50, 2500000.00, 5),
	(19, 4, 5000000.01, 5000000.01, 3553.00, 0.50, 5000000.00, 6),
	(20, 3, NULL, 50000.00, NULL, 0.00, 0.00, 0),
	(21, 3, 50000.01, 100000.00, 75.00, 75.00, 50000.00, 1),
	(22, 3, 100000.01, 2500000.00, 425.00, 0.80, 100000.00, 2),
	(23, 3, 2500000.01, 5000000.00, 850.00, 0.60, 2500000.00, 3),
	(24, 3, 5000000.01, 10000000.00, 1700.00, 0.60, 5000000.00, 4),
	(25, 3, 10000000.01, 10000000.01, 3400.00, 0.50, 10000000.00, 5);
/*!40000 ALTER TABLE `tarifa_variable` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.traspasos
CREATE TABLE IF NOT EXISTS `traspasos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_empresa` bigint(20) unsigned NOT NULL,
  `propietario_anterior` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `propietario_nuevo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `fecha_a_partir_de` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_empresa` (`id_empresa`),
  CONSTRAINT `FK_traspasos_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla catmunidb2.traspasos: ~11 rows (aproximadamente)
/*!40000 ALTER TABLE `traspasos` DISABLE KEYS */;
INSERT IGNORE INTO `traspasos` (`id`, `id_empresa`, `propietario_anterior`, `propietario_nuevo`, `fecha_a_partir_de`, `created_at`, `updated_at`) VALUES
	(9, 63, 'Santiago Elí Cartagena Mancía', 'Jannette Castaneda', '2021-02-17', '2022-06-02 19:18:51', '2022-06-02 19:18:51'),
	(13, 61, 'Wilbert Elí Magaña Mancía', 'Santiago Elí Cartagena Mancía', '2022-03-02', '2022-06-03 15:22:49', '2022-06-03 15:22:49'),
	(14, 61, 'Santiago Elí Cartagena Mancía', 'Santiago Elí Cartagena Mancía', '2022-03-02', '2022-06-03 15:23:29', '2022-06-03 15:23:29'),
	(18, 62, 'Gisselle Arlette Ramirez Mancía', 'Juan José Pleitez Ruballos', '2022-04-13', '2022-06-03 16:32:39', '2022-06-03 16:32:39'),
	(19, 62, 'Juan José Pleitez Ruballos', 'Santiago Elí Cartagena Mancía', '2022-04-06', '2022-06-03 17:20:46', '2022-06-03 17:20:46'),
	(20, 67, 'José Leopoldo Guerra Cisneros', 'Jannette Castaneda', '2022-02-17', '2022-06-03 21:29:58', '2022-06-03 21:29:58'),
	(21, 67, 'Jannette Castaneda', 'Santiago Elí Cartagena Mancía', '2022-04-06', '2022-06-03 21:33:29', '2022-06-03 21:33:29'),
	(22, 66, 'Wilbert Elí Magaña Mancía', 'Juan Carlos Perez Valle', '2022-03-01', '2022-06-06 15:01:34', '2022-06-06 15:01:34'),
	(31, 67, 'Gisselle Arlette Ramirez Mancía', 'Juan José Pleitez Ruballos', '2022-06-08', '2022-06-06 20:04:44', '2022-06-06 20:04:44'),
	(32, 67, 'Juan José Pleitez Ruballos', 'José Leopoldo Guerra Cisneros', '2022-06-01', '2022-06-07 16:47:55', '2022-06-07 16:47:55'),
	(33, 67, 'José Leopoldo Guerra Cisneros', 'Jannette Castaneda', '2022-06-07', '2022-06-07 17:08:00', '2022-06-07 17:08:00');
/*!40000 ALTER TABLE `traspasos` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.traspaso_buses
CREATE TABLE IF NOT EXISTS `traspaso_buses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_buses` bigint(20) unsigned NOT NULL,
  `propietario_anterior` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `propietario_nuevo` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_a_partir_de` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `id_buses_detalle` (`id_buses`) USING BTREE,
  CONSTRAINT `FK_traspaso_buses_buses` FOREIGN KEY (`id_buses`) REFERENCES `buses` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla catmunidb2.traspaso_buses: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `traspaso_buses` DISABLE KEYS */;
INSERT IGNORE INTO `traspaso_buses` (`id`, `id_buses`, `propietario_anterior`, `propietario_nuevo`, `fecha_a_partir_de`, `created_at`, `updated_at`) VALUES
	(2, 5, 'Constructora Flores', 'Almacén y librería la confianza', '2022-07-05', '2022-07-06 20:53:00', '2022-07-06 20:53:00'),
	(3, 5, 'Almacén y librería la confianza', 'Digital Solutions', '2022-07-06', '2022-07-06 20:56:19', '2022-07-06 20:56:19'),
	(4, 5, 'Digital Solutions', 'Gasolinera Uno Metapán', '2022-07-13', '2022-07-13 15:47:36', '2022-07-13 15:47:36'),
	(5, 5, 'Gasolinera Uno Metapán', 'Almacén y librería la confianza', '2022-07-14', '2022-07-15 16:55:53', '2022-07-15 16:55:53');
/*!40000 ALTER TABLE `traspaso_buses` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.traspaso_rotulo
CREATE TABLE IF NOT EXISTS `traspaso_rotulo` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_rotulos` bigint(20) unsigned NOT NULL,
  `propietario_anterior` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `propietario_nuevo` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_a_partir_de` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `id` (`id`) USING BTREE,
  KEY `id_buses_detalle` (`id_rotulos`) USING BTREE,
  CONSTRAINT `FK_traspaso_buses_rotulo_rotulos` FOREIGN KEY (`id_rotulos`) REFERENCES `rotulos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci ROW_FORMAT=DYNAMIC;

-- Volcando datos para la tabla catmunidb2.traspaso_rotulo: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `traspaso_rotulo` DISABLE KEYS */;
INSERT IGNORE INTO `traspaso_rotulo` (`id`, `id_rotulos`, `propietario_anterior`, `propietario_nuevo`, `fecha_a_partir_de`, `created_at`, `updated_at`) VALUES
	(1, 1, 'La Toscana', 'Holcim El Salvador, S.A de C.V', '2022-07-07', '2022-07-07 20:02:53', '2022-07-07 20:02:53');
/*!40000 ALTER TABLE `traspaso_rotulo` ENABLE KEYS */;

-- Volcando estructura para tabla catmunidb2.usuario
CREATE TABLE IF NOT EXISTS `usuario` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL,
  `usuario` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla catmunidb2.usuario: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT IGNORE INTO `usuario` (`id`, `nombre`, `apellido`, `activo`, `usuario`, `password`) VALUES
	(1, 'Giovany', 'Rosales', 1, 'admin', '$2y$10$UO3aeW1Ir7fvOoPvvXRr3eh7IWqxsr09nOyRKdCvM.0nC0xffzYH.'),
	(2, 'Santiago', 'Mancía', 1, 'thiago', '$2y$10$7V2SSYk1bRFDR.RGhW8G9ON1FjDKP25BlbZQGK2e0An9qAN1MIB2G');
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
