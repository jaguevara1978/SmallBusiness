-- -------------------------------------------
SET AUTOCOMMIT=0;
START TRANSACTION;
SET SQL_QUOTE_SHOW_CREATE=1;
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
-- -------------------------------------------
-- -------------------------------------------
-- START BACKUP
-- -------------------------------------------
-- -------------------------------------------
-- TABLE AuthAssignment
-- -------------------------------------------
DROP TABLE IF EXISTS AuthAssignment;
CREATE TABLE IF NOT EXISTS `AuthAssignment` (
  `itemname` varchar(64) NOT NULL,
  `userid` varchar(64) NOT NULL,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`itemname`,`userid`),
  KEY `fk_AuthAssignment_AuthItem1_idx` (`itemname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE AuthItem
-- -------------------------------------------
DROP TABLE IF EXISTS AuthItem;
CREATE TABLE IF NOT EXISTS `AuthItem` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE AuthItemChild
-- -------------------------------------------
DROP TABLE IF EXISTS AuthItemChild;
CREATE TABLE IF NOT EXISTS `AuthItemChild` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  KEY `fk_AuthItemChild_AuthItem1_idx` (`parent`),
  KEY `fk_AuthItemChild_AuthItem2_idx` (`child`),
  CONSTRAINT `fk_AuthItemChild_AuthItem1` FOREIGN KEY (`parent`) REFERENCES `AuthItem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_AuthItemChild_AuthItem2` FOREIGN KEY (`child`) REFERENCES `AuthItem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE Rights
-- -------------------------------------------
DROP TABLE IF EXISTS Rights;
CREATE TABLE IF NOT EXISTS `Rights` (
  `itemname` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  KEY `fk_Rights_AuthItem1_idx` (`itemname`),
  CONSTRAINT `fk_Rights_AuthItem1` FOREIGN KEY (`itemname`) REFERENCES `AuthItem` (`name`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE User
-- -------------------------------------------
DROP TABLE IF EXISTS User;
CREATE TABLE IF NOT EXISTS `User` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(10) NOT NULL,
  `name` varchar(80) NOT NULL,
  `password` varchar(254) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE client
-- -------------------------------------------
DROP TABLE IF EXISTS client;
CREATE TABLE IF NOT EXISTS `client` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `code` varchar(15) DEFAULT NULL,
  `name` varchar(75) NOT NULL COMMENT 'TABLA DE CLIENTES DE JOHNSON',
  `description` varchar(100) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `mobile` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=540 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE client_invoice
-- -------------------------------------------
DROP TABLE IF EXISTS client_invoice;
CREATE TABLE IF NOT EXISTS `client_invoice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `client` int(3) NOT NULL,
  `status` int(2) NOT NULL,
  `payment_date` date NOT NULL,
  `notes` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ci_status_idx` (`status`),
  KEY `fk_ci_client_idx` (`client`),
  CONSTRAINT `fk_ci_client` FOREIGN KEY (`client`) REFERENCES `client` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_ci_status` FOREIGN KEY (`status`) REFERENCES `status` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE client_invoice_deposit
-- -------------------------------------------
DROP TABLE IF EXISTS client_invoice_deposit;
CREATE TABLE IF NOT EXISTS `client_invoice_deposit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_invoice` int(11) NOT NULL,
  `date` date NOT NULL,
  `value` decimal(11,0) NOT NULL,
  `notes` varchar(80) DEFAULT NULL,
  `final_payment` varchar(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_cid_client_invoice_idx` (`client_invoice`),
  CONSTRAINT `fk_joh_abonos_factura_cliente_joh_encabezado_factura_cliente1` FOREIGN KEY (`client_invoice`) REFERENCES `client_invoice` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE client_invoice_detail
-- -------------------------------------------
DROP TABLE IF EXISTS client_invoice_detail;
CREATE TABLE IF NOT EXISTS `client_invoice_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_invoice` int(11) NOT NULL,
  `quantity` decimal(11,2) NOT NULL DEFAULT '0.00',
  `product` int(3) NOT NULL,
  `unit_value` decimal(11,2) NOT NULL DEFAULT '0.00',
  `notes` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cidet_product_idx` (`product`),
  KEY `fk_cidet_client_invoice_idx` (`client_invoice`),
  CONSTRAINT `fk_cidet_client_invoice` FOREIGN KEY (`client_invoice`) REFERENCES `client_invoice` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_cidet_product` FOREIGN KEY (`product`) REFERENCES `product` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE measure_unit
-- -------------------------------------------
DROP TABLE IF EXISTS measure_unit;
CREATE TABLE IF NOT EXISTS `measure_unit` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `code` varchar(5) NOT NULL,
  `name` varchar(45) NOT NULL,
  `eq_reference` decimal(6,2) NOT NULL COMMENT 'Es la equivalencia entre la unidad de medida y kilogramos, es decir a cuantos kilos equivale esta medida.',
  `reference` varchar(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE product
-- -------------------------------------------
DROP TABLE IF EXISTS product;
CREATE TABLE IF NOT EXISTS `product` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `code` varchar(5) NOT NULL,
  `name` varchar(45) NOT NULL,
  `measure_unit` int(3) NOT NULL,
  `custom_order` int(4) DEFAULT NULL,
  `default_qty` decimal(11,2) DEFAULT '0.00',
  `default_value` decimal(11,2) DEFAULT '0.00',
  `stock_movement` varchar(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_measure_unit_idx` (`measure_unit`),
  CONSTRAINT `fk_measure_unit` FOREIGN KEY (`measure_unit`) REFERENCES `measure_unit` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=178 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE provider
-- -------------------------------------------
DROP TABLE IF EXISTS provider;
CREATE TABLE IF NOT EXISTS `provider` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `code` varchar(15) DEFAULT NULL,
  `name` varchar(75) NOT NULL COMMENT 'TABLA DE CLIENTES DE JOHNSON',
  `description` varchar(100) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `mobile` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE provider_invoice
-- -------------------------------------------
DROP TABLE IF EXISTS provider_invoice;
CREATE TABLE IF NOT EXISTS `provider_invoice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `provider` int(5) NOT NULL,
  `date` date NOT NULL,
  `status` int(2) NOT NULL,
  `payment_date` date NOT NULL,
  `notes` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pi_status` (`status`),
  KEY `fk_pi_provider` (`provider`),
  CONSTRAINT `fk_pi_provider_idx` FOREIGN KEY (`provider`) REFERENCES `provider` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_pi_status_idx` FOREIGN KEY (`status`) REFERENCES `status` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE provider_invoice_deposit
-- -------------------------------------------
DROP TABLE IF EXISTS provider_invoice_deposit;
CREATE TABLE IF NOT EXISTS `provider_invoice_deposit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `provider_invoice` int(11) NOT NULL,
  `date` date NOT NULL,
  `value` decimal(11,0) NOT NULL,
  `notes` varchar(80) DEFAULT NULL,
  `final_payment` varchar(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_pid_provider_invoice_idx` (`provider_invoice`),
  CONSTRAINT `fk_pid_provider_invoice` FOREIGN KEY (`provider_invoice`) REFERENCES `provider_invoice` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE provider_invoice_detail
-- -------------------------------------------
DROP TABLE IF EXISTS provider_invoice_detail;
CREATE TABLE IF NOT EXISTS `provider_invoice_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `provider_invoice` int(11) NOT NULL,
  `quantity` decimal(11,2) NOT NULL DEFAULT '0.00',
  `product` int(3) NOT NULL,
  `unit_value` decimal(11,2) NOT NULL DEFAULT '0.00',
  `notes` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pidet_product_idx` (`product`),
  KEY `fk_pidet_provider_invoice_idx` (`provider_invoice`),
  CONSTRAINT `fk_pidet_product` FOREIGN KEY (`product`) REFERENCES `product` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_pidet_provider_invoice` FOREIGN KEY (`provider_invoice`) REFERENCES `provider_invoice` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=129 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE settings
-- -------------------------------------------
DROP TABLE IF EXISTS settings;
CREATE TABLE IF NOT EXISTS `settings` (
  `key` varchar(100) NOT NULL,
  `group` varchar(45) NOT NULL,
  `group_order` int(2) NOT NULL,
  `type` varchar(45) NOT NULL,
  `type_specs` varchar(200) DEFAULT NULL,
  `value` varchar(200) DEFAULT NULL,
  `data_type` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE status
-- -------------------------------------------
DROP TABLE IF EXISTS status;
CREATE TABLE IF NOT EXISTS `status` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `use_type` varchar(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE stock
-- -------------------------------------------
DROP TABLE IF EXISTS stock;
CREATE TABLE IF NOT EXISTS `stock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quantity` decimal(8,2) NOT NULL DEFAULT '0.00',
  `product` int(3) NOT NULL,
  `date` date NOT NULL,
  `notes` varchar(100) DEFAULT NULL,
  `init_stock_qty` decimal(9,2) NOT NULL DEFAULT '0.00',
  `final_stock_qty` decimal(9,2) NOT NULL DEFAULT '0.00',
  `movement_type` varchar(1) NOT NULL DEFAULT '0',
  `client_invoice` int(11) DEFAULT NULL,
  `client_invoice_detail` int(11) DEFAULT NULL,
  `provider_invoice` int(11) DEFAULT NULL,
  `provider_invoice_detail` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_stock_product_idx` (`product`),
  CONSTRAINT `fk_stock_product` FOREIGN KEY (`product`) REFERENCES `product` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=178 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE DATA AuthAssignment
-- -------------------------------------------
INSERT INTO `AuthAssignment` (`itemname`,`userid`,`bizrule`,`data`) VALUES
('Admin','5','','N;');



-- -------------------------------------------
-- TABLE DATA AuthItem
-- -------------------------------------------
INSERT INTO `AuthItem` (`name`,`type`,`description`,`bizrule`,`data`) VALUES
('Admin','2','Administrator','','N;');
INSERT INTO `AuthItem` (`name`,`type`,`description`,`bizrule`,`data`) VALUES
('Aux','2','Aux','','N;');



-- -------------------------------------------
-- TABLE DATA User
-- -------------------------------------------
INSERT INTO `User` (`id`,`username`,`name`,`password`) VALUES
('5','test','test','$2a$12$mAuaxBr4gcZxZLIbn.Qd9OCv1U6PnpBo/92ask/8ISVpK2F7L6WZy');



-- -------------------------------------------
-- TABLE DATA client
-- -------------------------------------------
INSERT INTO `client` (`id`,`code`,`name`,`description`,`address`,`phone`,`mobile`) VALUES
('517','2','Ventas Contado','Gracias por Su Compra','DIOS LOS DENDIGA','','');
INSERT INTO `client` (`id`,`code`,`name`,`description`,`address`,`phone`,`mobile`) VALUES
('518','1','Merka Cell','Albeiro Arbelaez','Av 50A # 52-08 
Bello','2751967','3108431182');
INSERT INTO `client` (`id`,`code`,`name`,`description`,`address`,`phone`,`mobile`) VALUES
('519','3','Bilmark Leonel Giraldo ','La Casa Del Video Juego','C.C Modernisimo #107','5123164','');
INSERT INTO `client` (`id`,`code`,`name`,`description`,`address`,`phone`,`mobile`) VALUES
('520','4','Sebastian ','','Girardota ','2897963','3104685421');
INSERT INTO `client` (`id`,`code`,`name`,`description`,`address`,`phone`,`mobile`) VALUES
('521','5','Anibal Sanchez','Discontrol','Bello','','3207198858');
INSERT INTO `client` (`id`,`code`,`name`,`description`,`address`,`phone`,`mobile`) VALUES
('522','6','Didier Ruiz','Villatina','','','3007947921');
INSERT INTO `client` (`id`,`code`,`name`,`description`,`address`,`phone`,`mobile`) VALUES
('523','7','Hambled Neira','Artes Graficas Rino
','Robledo Santa Maria','3206182404','3168020980');
INSERT INTO `client` (`id`,`code`,`name`,`description`,`address`,`phone`,`mobile`) VALUES
('524','13','Cristian Villa','Trabajador Ruben Henao','Calle 96 # 52-15','3893788','');
INSERT INTO `client` (`id`,`code`,`name`,`description`,`address`,`phone`,`mobile`) VALUES
('525','8','Carlos Cano','C.C 70542398','Taraza Ant','3122585201','3207564009');
INSERT INTO `client` (`id`,`code`,`name`,`description`,`address`,`phone`,`mobile`) VALUES
('526','9','Mario gomez','Power Gamez','Modernisimo # 112','2312815','');
INSERT INTO `client` (`id`,`code`,`name`,`description`,`address`,`phone`,`mobile`) VALUES
('527','10','Mauricio','Celuplay','Carrera 53 # 45-106','5131190','3103749352');
INSERT INTO `client` (`id`,`code`,`name`,`description`,`address`,`phone`,`mobile`) VALUES
('528','11','Diego','Video Juegos Gotcha','C.C Esquina del hueco','5136951','');
INSERT INTO `client` (`id`,`code`,`name`,`description`,`address`,`phone`,`mobile`) VALUES
('529','12','Hernan','H & M Technology','Carrera 54 # 46-08 # 105','5142335','');
INSERT INTO `client` (`id`,`code`,`name`,`description`,`address`,`phone`,`mobile`) VALUES
('530','14','Amparo Gomez','Celumed.com SAS','carrera 50 # 53-32 # 33','2519937','3008172602');
INSERT INTO `client` (`id`,`code`,`name`,`description`,`address`,`phone`,`mobile`) VALUES
('531','15','Carlos Mario Zuluaga','Tecnologia JP # 2','San Andresito Principal # 431','2317440','3148502668');
INSERT INTO `client` (`id`,`code`,`name`,`description`,`address`,`phone`,`mobile`) VALUES
('532','16','William','Video Juegos BW','C.C la Cascada # 212','5112229','');
INSERT INTO `client` (`id`,`code`,`name`,`description`,`address`,`phone`,`mobile`) VALUES
('533','17','Diomar Valencia','Bodega Jeisaly','Bulevard Cundinamarca # 134-135','5133172','2318226');
INSERT INTO `client` (`id`,`code`,`name`,`description`,`address`,`phone`,`mobile`) VALUES
('534','18','Hernan Rios','Accesorios Medellin','Modernisimo # 105','2313533','');
INSERT INTO `client` (`id`,`code`,`name`,`description`,`address`,`phone`,`mobile`) VALUES
('535','19','Henry Montoya','New Games ','C.C hacendado #208 C','5122240','');
INSERT INTO `client` (`id`,`code`,`name`,`description`,`address`,`phone`,`mobile`) VALUES
('536','20','Juan ','El Centro Del Play','C.C Hacendado #104','5137345','');
INSERT INTO `client` (`id`,`code`,`name`,`description`,`address`,`phone`,`mobile`) VALUES
('537','21','Celu Mega','Alex ','Carrera 52 # 44A-36 ','5119205','');
INSERT INTO `client` (`id`,`code`,`name`,`description`,`address`,`phone`,`mobile`) VALUES
('538','22','Jhon Ciro','Game Store','C.C Mayoristas del Hueco #118','2519462','3002461293');
INSERT INTO `client` (`id`,`code`,`name`,`description`,`address`,`phone`,`mobile`) VALUES
('539','23','Jaime Higuita','Electrodomesticos','Carrrera 52 #49-76 # 114','5112462','');



-- -------------------------------------------
-- TABLE DATA client_invoice
-- -------------------------------------------
INSERT INTO `client_invoice` (`id`,`date`,`client`,`status`,`payment_date`,`notes`) VALUES
('9','2014-08-23','522','30','2014-09-22','');
INSERT INTO `client_invoice` (`id`,`date`,`client`,`status`,`payment_date`,`notes`) VALUES
('10','2014-08-23','523','20','2014-09-22','');
INSERT INTO `client_invoice` (`id`,`date`,`client`,`status`,`payment_date`,`notes`) VALUES
('11','2014-08-23','519','20','2014-09-03','');
INSERT INTO `client_invoice` (`id`,`date`,`client`,`status`,`payment_date`,`notes`) VALUES
('12','2014-08-23','517','30','2014-09-22','');
INSERT INTO `client_invoice` (`id`,`date`,`client`,`status`,`payment_date`,`notes`) VALUES
('13','2014-08-23','524','20','2014-08-23','');
INSERT INTO `client_invoice` (`id`,`date`,`client`,`status`,`payment_date`,`notes`) VALUES
('14','2014-08-23','526','20','2014-09-03','');
INSERT INTO `client_invoice` (`id`,`date`,`client`,`status`,`payment_date`,`notes`) VALUES
('15','2014-08-23','539','30','2014-08-23','');



-- -------------------------------------------
-- TABLE DATA client_invoice_deposit
-- -------------------------------------------
INSERT INTO `client_invoice_deposit` (`id`,`client_invoice`,`date`,`value`,`notes`,`final_payment`) VALUES
('4','13','2014-08-23','550000','para entregarlo','0');
INSERT INTO `client_invoice_deposit` (`id`,`client_invoice`,`date`,`value`,`notes`,`final_payment`) VALUES
('5','15','2014-08-23','260000','','1');



-- -------------------------------------------
-- TABLE DATA client_invoice_detail
-- -------------------------------------------
INSERT INTO `client_invoice_detail` (`id`,`client_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('5','9','1.00','99','270000.00','');
INSERT INTO `client_invoice_detail` (`id`,`client_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('6','9','3.00','101','6500.00','');
INSERT INTO `client_invoice_detail` (`id`,`client_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('7','9','2.00','156','22000.00','');
INSERT INTO `client_invoice_detail` (`id`,`client_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('8','10','2.00','78','105000.00','');
INSERT INTO `client_invoice_detail` (`id`,`client_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('9','10','1.00','99','255000.00','');
INSERT INTO `client_invoice_detail` (`id`,`client_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('10','10','1.00','70','410000.00','');
INSERT INTO `client_invoice_detail` (`id`,`client_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('11','10','10.00','72','45000.00','');
INSERT INTO `client_invoice_detail` (`id`,`client_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('12','10','3.00','159','4500.00','');
INSERT INTO `client_invoice_detail` (`id`,`client_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('13','11','2.00','151','860000.00','');
INSERT INTO `client_invoice_detail` (`id`,`client_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('14','12','58.00','78','110000.00','falta descontar las que tengo en la casa');
INSERT INTO `client_invoice_detail` (`id`,`client_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('15','12','8.00','73','12000.00','');
INSERT INTO `client_invoice_detail` (`id`,`client_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('16','13','1.00','68','800000.00','');
INSERT INTO `client_invoice_detail` (`id`,`client_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('17','12','39.00','88','33000.00','');
INSERT INTO `client_invoice_detail` (`id`,`client_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('18','12','17.00','89','33000.00','');
INSERT INTO `client_invoice_detail` (`id`,`client_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('19','12','87.00','111','33000.00','');
INSERT INTO `client_invoice_detail` (`id`,`client_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('20','14','1.00','99','260000.00','');
INSERT INTO `client_invoice_detail` (`id`,`client_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('21','12','33.00','114','75000.00','');
INSERT INTO `client_invoice_detail` (`id`,`client_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('22','12','16.00','81','25000.00','');
INSERT INTO `client_invoice_detail` (`id`,`client_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('23','12','1.00','124','35000.00','');
INSERT INTO `client_invoice_detail` (`id`,`client_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('24','12','18.00','72','45000.00','');
INSERT INTO `client_invoice_detail` (`id`,`client_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('29','12','1.00','105','50000.00','');
INSERT INTO `client_invoice_detail` (`id`,`client_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('30','15','1.00','110','260000.00','');



-- -------------------------------------------
-- TABLE DATA measure_unit
-- -------------------------------------------
INSERT INTO `measure_unit` (`id`,`code`,`name`,`eq_reference`,`reference`) VALUES
('5','SU','SIN UNIDAD DE MEDIDA','0.00','0');
INSERT INTO `measure_unit` (`id`,`code`,`name`,`eq_reference`,`reference`) VALUES
('7','UN','Unidad','1.00','1');



-- -------------------------------------------
-- TABLE DATA product
-- -------------------------------------------
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('67','3','iPhone 5 S','7','10','1.00','0.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('68','1','Samsung Galaxy S4 ','7','1','10.00','800000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('69','2','Samsung Galaxy S4 mini','7','20','1.00','550000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('70','4','Samsung Galaxy Grand ','7','30','1.00','410000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('71','5','Cable Audio y Video play 2','7','40','1.00','3000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('72','6','Mini Land Rover ','7','50','1.00','45000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('73','7','Carga y Juega X-box 360','7','60','1.00','11000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('74','8','Multi One Huskee Ps one','7','70','1.00','55000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('75','9','Estuche + Teclado Top Sonic Tablet','7','80','1.00','10000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('76','10','Play Station 3 250 Gb','7','90','1.00','490000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('77','11','Play Station 4 500 Gb ','7','100','1.00','860000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('78','12','Tablet Top Sonic 1Gb Ram ','7','110','1.00','110000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('79','13','Fun Game 2 76 juegos especiales','7','120','1.00','20000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('80','14','Diadema NiA con radio y micro','7','130','1.00','22000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('81','15','Bocina Bluethoo redonda colores','7','140','1.00','24000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('82','16','Camisa Parlante ','7','150','1.00','13000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('83','17','Parlante Minius ','7','160','1.00','22000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('84','18','Parlante Monster Inc','7','170','1.00','22000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('85','19','Parlante Lata Gaseosa Equipos','7','180','1.00','25000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('86','20','Parlante Carro Nano Tec ','7','190','1.00','22000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('87','21','X-Box 360 4 GB','7','200','1.00','430000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('88','22','Control Play Station 3 Negro','7','210','1.00','33000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('89','23','Control Play Station 3 Camuflado','7','220','1.00','38000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('90','24','Telefono Panasonic Inalambrico KX-TG4051','7','230','1.00','65000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('91','25','telefono Panasonic KX-TS500','7','240','1.00','30000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('92','26','Sony Xperia V Lte25i','7','250','1.00','480000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('93','27','Mp 5 Ps Vita 8Gb ','7','260','1.00','75000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('94','28','Parlante Fuleko Grande ','7','270','1.00','30000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('95','29','Audifono Diadema Sankey','7','280','1.00','12000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('96','30','Cable HDMI 3 en 1 ','7','290','1.00','15000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('97','31','Adaptador psp ','7','300','1.00','8000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('98','32','Adaptador Galaxy S4 y Note','7','310','1.00','7000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('99','33','Huawey Y-530 ','7','320','1.00','255000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('100','34','huawey Y-530 3G ','7','330','1.00','270000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('101','35','Memory Card ps 2 Sony 8MG','7','340','1.00','6000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('102','36','Control Play Station 2 Blister','7','350','1.00','15000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('103','37','Camara Web Unitec ','7','360','1.00','18000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('104','38','Estuche Silicona Tablet','7','370','1.00','9000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('105','39','PVP Nes juegos','7','380','1.00','50000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('106','40','Multiplayer Play 2 ','7','380','1.00','14000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('107','41','Estuche Cierre Psp 3000','7','390','1.00','5000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('108','42','Adaptador Psp Vita','7','400','1.00','15000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('109','43','Kyt Wii 3 x 1 Sports','7','410','1.00','15000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('110','44','Sony Play Station 2 + Chip','7','420','1.00','265000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('111','45','Celular Mini Nokia  ','7','430','1.00','33000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('112','46','Sony Psp Vita + Borderlands ','7','440','1.00','490000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('113','47','Dvd Portatil Silver Max 7 Pulgadas','7','450','1.00','110000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('114','48','Control Original X-box 360 ','7','460','1.00','75000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('115','49','Disco Duro X-box 360 320 GB','7','470','1.00','140000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('116','50','Teclado Pc Unitec','7','480','1.00','14000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('117','51','Diadema con Microfono Unitec','7','490','1.00','20000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('118','52','Combo Pc Unitec','7','500','1.00','25000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('119','53','Control PC usb ','7','510','1.00','12000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('120','54','Mause Genius ','7','520','1.00','12000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('121','55','Mause Unitec','7','530','1.00','10000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('122','56','Adaptador Ds lite','7','540','1.00','6000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('123','57','Cable VGA X-box 360','7','550','1.00','20000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('124','58','Diademas Bluethoo con radio','7','560','1.00','30000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('125','59','Diadema K11 ','7','570','1.00','15000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('126','60','Kyt Move ps 3 Sony','7','580','1.00','100000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('127','61','Silicona Control X-box 360','7','590','1.00','4000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('128','62','Control X-box Forza','7','600','1.00','15000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('129','63','Cable HDMI  Blister','7','610','1.00','8000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('130','64','Parlante Carro Bluethoo','7','620','1.00','35000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('131','65','Bocina Beats Capsule Colores','7','630','1.00','60000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('132','66','Bocina Bluethoo D502','7','640','1.00','100000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('133','67','bocina Bluethoo D501','7','650','1.00','65000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('134','68','Manos Libres Galaxy ','7','660','1.00','8000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('135','69','Bluethoo Apple ','7','670','1.00','30000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('136','70','Parlante JBL','7','680','1.00','55000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('137','71','FlipCover Galaxy S5','7','690','1.00','10000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('138','72','Flip Cover Moto G','7','700','1.00','10000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('139','75','Estuche Murano Celular','7','710','1.00','16000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('140','73','Planta Vs Zombies','7','720','1.00','100000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('141','74','Memoria Micro SD 8Gb suelta','7','730','1.00','8000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('142','76','Convertidor Pro Duo','7','740','1.00','3000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('143','77','Infamous Ps 4 ','7','750','1.00','120000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('144','78','X-Box 360 + Kinect','7','760','1.00','630000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('145','79','Sony Psp Slim 3001','7','770','1.00','250000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('146','80','Samsung Galaxy S3 mini','7','780','1.00','355000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('148','81','Sony Play Station 2 ','7','790','1.00','250000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('149','82','USB 8 GB Kingston','7','800','1.00','9600.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('150','83','Micro Sd 8 Gb Kingston','7','810','1.00','9000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('151','84','Control Play Staion 4','7','820','1.00','125000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('152','85','Control Play Staion 3 Original','7','830','1.00','80000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('153','86','Banco de Carga 5500 Mph','7','840','1.00','26000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('154','87','Dvd Virgen Matrix','7','850','1.00','215.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('155','88','Radio Huskee','7','860','1.00','20000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('156','89','Control Play Station 2 Inalambrico Recargable','7','870','1.00','25000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('157','90','Guitarra Huskee','7','880','1.00','100000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('158','91','Mini Componente Premier','7','890','1.00','120000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('159','92','Audifono Adidas Colores','7','900','1.00','5000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('160','93','Adaptador Kinect','7','910','1.00','25000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('161','94','Parlante Copa del Mundo ','7','920','1.00','15000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('162','95','Parlante Cabeza Fuleko','7','930','1.00','15000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('163','96','Parlantes pc unitec','7','940','1.00','13000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('164','97','Parlante Basuka','7','950','1.00','20000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('165','98','Mp 3 ','7','960','1.00','10000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('166','99','Convertidor Micro Sd a Usb','7','970','1.00','2000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('167','100','Convertdor Teclado Tablet','7','980','1.00','5000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('168','101','Tablet Huskee + Sim Card','7','990','1.00','140000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('169','102','Tablet Silver max','7','1000','1.00','110000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('170','103','Samsung Galaxy Trend','7','1010','1.00','265000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('171','104','Samsung Galaxy Fame','7','1020','250000.00','0.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('172','105','Huawey Y-600 3G','7','1030','1.00','295000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('173','106','Sony Xperia Z1','7','1040','1.00','840000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('174','107','Juegos X-box 360 ','7','1050','1.00','1500.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('175','108','Juegos Play Station 2','7','1060','1.00','1000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('176','109','Juegos Nintendo wii','7','1070','1.00','2000.00','1');
INSERT INTO `product` (`id`,`code`,`name`,`measure_unit`,`custom_order`,`default_qty`,`default_value`,`stock_movement`) VALUES
('177','110','Servicio Chip','7','1080','1.00','10000.00','1');



-- -------------------------------------------
-- TABLE DATA provider
-- -------------------------------------------
INSERT INTO `provider` (`id`,`code`,`name`,`description`,`address`,`phone`,`mobile`) VALUES
('60','13','Juan Gomez ','Garra','Bucaramanga','','');
INSERT INTO `provider` (`id`,`code`,`name`,`description`,`address`,`phone`,`mobile`) VALUES
('61','2','Bilmark Giraldo','La Casa Del Video Juego','Modernisimo # 107','5123164','');
INSERT INTO `provider` (`id`,`code`,`name`,`description`,`address`,`phone`,`mobile`) VALUES
('62','1','Inventario local','','','','');
INSERT INTO `provider` (`id`,`code`,`name`,`description`,`address`,`phone`,`mobile`) VALUES
('63','4','Juan Correa','Impo Electro','Centro Comercial Japon #522','2514028','');
INSERT INTO `provider` (`id`,`code`,`name`,`description`,`address`,`phone`,`mobile`) VALUES
('64','3','Ruben Henao','Dprimero','C.C Metro Hueco ','5113790','');
INSERT INTO `provider` (`id`,`code`,`name`,`description`,`address`,`phone`,`mobile`) VALUES
('65','6','Digital Video Juegos','Camilo Ruiz','Carrera 8 # 32-04 Pereira','3260347','3182824138');
INSERT INTO `provider` (`id`,`code`,`name`,`description`,`address`,`phone`,`mobile`) VALUES
('66','7','Tecno B &  J','Victor Vecino','','5117556','');
INSERT INTO `provider` (`id`,`code`,`name`,`description`,`address`,`phone`,`mobile`) VALUES
('67','8','Alex Zuluaga','+ Play','Metrohueco # 607
','5120205','');
INSERT INTO `provider` (`id`,`code`,`name`,`description`,`address`,`phone`,`mobile`) VALUES
('68','9','Alejandro Gomez','Electrogamez','Gran Plaza','','');
INSERT INTO `provider` (`id`,`code`,`name`,`description`,`address`,`phone`,`mobile`) VALUES
('69','10','Jairo Quintero','El Primo','El Diamante','','');
INSERT INTO `provider` (`id`,`code`,`name`,`description`,`address`,`phone`,`mobile`) VALUES
('70','11','Wilson Serna','','','','');
INSERT INTO `provider` (`id`,`code`,`name`,`description`,`address`,`phone`,`mobile`) VALUES
('71','12','Importadora Paliao','','Centro Comercial Hollywood #1204','3668808','3187268401');



-- -------------------------------------------
-- TABLE DATA provider_invoice
-- -------------------------------------------
INSERT INTO `provider_invoice` (`id`,`provider`,`date`,`status`,`payment_date`,`notes`) VALUES
('5','63','2014-08-22','50','2014-09-22','');
INSERT INTO `provider_invoice` (`id`,`provider`,`date`,`status`,`payment_date`,`notes`) VALUES
('6','63','2014-08-22','50','2014-09-22','');
INSERT INTO `provider_invoice` (`id`,`provider`,`date`,`status`,`payment_date`,`notes`) VALUES
('7','64','2014-08-22','50','2014-09-22','');
INSERT INTO `provider_invoice` (`id`,`provider`,`date`,`status`,`payment_date`,`notes`) VALUES
('8','65','2014-08-22','50','2014-08-22','');
INSERT INTO `provider_invoice` (`id`,`provider`,`date`,`status`,`payment_date`,`notes`) VALUES
('9','66','2014-08-22','50','2014-09-22','');
INSERT INTO `provider_invoice` (`id`,`provider`,`date`,`status`,`payment_date`,`notes`) VALUES
('11','64','2014-08-22','50','2014-09-21','');
INSERT INTO `provider_invoice` (`id`,`provider`,`date`,`status`,`payment_date`,`notes`) VALUES
('13','67','2014-08-22','50','2014-08-22','');
INSERT INTO `provider_invoice` (`id`,`provider`,`date`,`status`,`payment_date`,`notes`) VALUES
('14','67','2014-08-22','50','2014-09-16','');
INSERT INTO `provider_invoice` (`id`,`provider`,`date`,`status`,`payment_date`,`notes`) VALUES
('15','68','2014-08-22','50','2014-08-22','');
INSERT INTO `provider_invoice` (`id`,`provider`,`date`,`status`,`payment_date`,`notes`) VALUES
('16','67','2014-08-09','50','2014-09-12','');
INSERT INTO `provider_invoice` (`id`,`provider`,`date`,`status`,`payment_date`,`notes`) VALUES
('17','64','2014-08-12','50','2014-09-12','');
INSERT INTO `provider_invoice` (`id`,`provider`,`date`,`status`,`payment_date`,`notes`) VALUES
('18','64','2014-08-12','50','2014-09-12','');
INSERT INTO `provider_invoice` (`id`,`provider`,`date`,`status`,`payment_date`,`notes`) VALUES
('19','67','2014-08-12','50','2014-09-12','');
INSERT INTO `provider_invoice` (`id`,`provider`,`date`,`status`,`payment_date`,`notes`) VALUES
('20','66','2014-08-08','50','2014-09-08','');
INSERT INTO `provider_invoice` (`id`,`provider`,`date`,`status`,`payment_date`,`notes`) VALUES
('21','63','2014-08-06','50','2014-09-06','');
INSERT INTO `provider_invoice` (`id`,`provider`,`date`,`status`,`payment_date`,`notes`) VALUES
('22','69','2014-08-09','50','2014-09-09','');
INSERT INTO `provider_invoice` (`id`,`provider`,`date`,`status`,`payment_date`,`notes`) VALUES
('23','67','2014-08-12','50','2014-09-12','');
INSERT INTO `provider_invoice` (`id`,`provider`,`date`,`status`,`payment_date`,`notes`) VALUES
('24','67','2014-08-13','50','2014-09-13','');
INSERT INTO `provider_invoice` (`id`,`provider`,`date`,`status`,`payment_date`,`notes`) VALUES
('25','64','2014-08-13','50','2014-09-13','');
INSERT INTO `provider_invoice` (`id`,`provider`,`date`,`status`,`payment_date`,`notes`) VALUES
('26','63','2014-08-13','50','2014-09-13','');
INSERT INTO `provider_invoice` (`id`,`provider`,`date`,`status`,`payment_date`,`notes`) VALUES
('27','67','2014-07-30','50','2014-08-30','');
INSERT INTO `provider_invoice` (`id`,`provider`,`date`,`status`,`payment_date`,`notes`) VALUES
('28','67','2014-07-30','50','2014-08-30','');
INSERT INTO `provider_invoice` (`id`,`provider`,`date`,`status`,`payment_date`,`notes`) VALUES
('29','67','2014-07-29','50','2014-08-29','');
INSERT INTO `provider_invoice` (`id`,`provider`,`date`,`status`,`payment_date`,`notes`) VALUES
('30','63','2014-07-23','50','2014-08-23','');
INSERT INTO `provider_invoice` (`id`,`provider`,`date`,`status`,`payment_date`,`notes`) VALUES
('31','63','2014-07-23','50','2014-08-23','');
INSERT INTO `provider_invoice` (`id`,`provider`,`date`,`status`,`payment_date`,`notes`) VALUES
('32','63','2014-07-25','50','2014-08-25','');
INSERT INTO `provider_invoice` (`id`,`provider`,`date`,`status`,`payment_date`,`notes`) VALUES
('33','64','2014-07-14','50','2014-08-14','');
INSERT INTO `provider_invoice` (`id`,`provider`,`date`,`status`,`payment_date`,`notes`) VALUES
('34','66','2014-07-10','50','2014-08-10','');
INSERT INTO `provider_invoice` (`id`,`provider`,`date`,`status`,`payment_date`,`notes`) VALUES
('35','63','2014-07-10','50','2014-08-10','');
INSERT INTO `provider_invoice` (`id`,`provider`,`date`,`status`,`payment_date`,`notes`) VALUES
('36','63','2014-07-07','50','2014-08-07','');
INSERT INTO `provider_invoice` (`id`,`provider`,`date`,`status`,`payment_date`,`notes`) VALUES
('37','63','2014-07-09','50','2014-08-09','');
INSERT INTO `provider_invoice` (`id`,`provider`,`date`,`status`,`payment_date`,`notes`) VALUES
('38','63','2014-07-02','50','2014-08-02','');
INSERT INTO `provider_invoice` (`id`,`provider`,`date`,`status`,`payment_date`,`notes`) VALUES
('39','63','2014-07-03','50','2014-08-03','');
INSERT INTO `provider_invoice` (`id`,`provider`,`date`,`status`,`payment_date`,`notes`) VALUES
('40','63','2014-07-03','50','2014-08-03','');
INSERT INTO `provider_invoice` (`id`,`provider`,`date`,`status`,`payment_date`,`notes`) VALUES
('41','70','2014-07-14','50','2014-08-14','');
INSERT INTO `provider_invoice` (`id`,`provider`,`date`,`status`,`payment_date`,`notes`) VALUES
('42','62','2014-08-23','60','2014-08-23','');
INSERT INTO `provider_invoice` (`id`,`provider`,`date`,`status`,`payment_date`,`notes`) VALUES
('43','61','2014-08-08','50','2014-09-23','');
INSERT INTO `provider_invoice` (`id`,`provider`,`date`,`status`,`payment_date`,`notes`) VALUES
('44','71','2014-07-31','50','2014-08-31','');



-- -------------------------------------------
-- TABLE DATA provider_invoice_deposit
-- -------------------------------------------
INSERT INTO `provider_invoice_deposit` (`id`,`provider_invoice`,`date`,`value`,`notes`,`final_payment`) VALUES
('1','8','2014-08-22','500000','','0');
INSERT INTO `provider_invoice_deposit` (`id`,`provider_invoice`,`date`,`value`,`notes`,`final_payment`) VALUES
('3','13','2014-08-22','211624','','0');
INSERT INTO `provider_invoice_deposit` (`id`,`provider_invoice`,`date`,`value`,`notes`,`final_payment`) VALUES
('4','15','2014-08-22','13500000','','0');
INSERT INTO `provider_invoice_deposit` (`id`,`provider_invoice`,`date`,`value`,`notes`,`final_payment`) VALUES
('5','13','2014-08-22','300000','','1');



-- -------------------------------------------
-- TABLE DATA provider_invoice_detail
-- -------------------------------------------
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('2','5','6.00','122','3500.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('3','5','50.00','72','41000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('4','5','2.00','86','19000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('5','5','6.00','98','5000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('6','5','2.00','96','12000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('7','5','6.00','128','12500.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('12','5','30.00','101','5000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('13','5','50.00','71','1600.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('14','5','2.00','133','60000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('15','5','1.00','132','90000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('16','5','7.00','131','50000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('17','5','8.00','137','8000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('18','5','1.00','136','50000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('19','5','24.00','139','14500.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('20','5','6.00','138','6500.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('21','5','2.00','135','23000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('22','5','6.00','134','3800.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('23','6','10.00','138','6500.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('24','6','10.00','137','8000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('25','7','1.00','140','85000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('26','8','20.00','114','70000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('27','9','25.00','141','7000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('28','9','20.00','142','2000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('29','11','1.00','143','110000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('30','13','1.00','144','590000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('31','14','4.00','114','72000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('32','14','1.00','145','240000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('33','15','108.00','99','250000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('34','15','10.00','74','50000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('35','16','1.00','144','580000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('36','18','2.00','148','245000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('37','20','6.00','149','8600.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('38','20','6.00','150','8000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('39','21','6.00','89','29000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('40','22','50.00','73','10000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('41','23','5.00','148','240000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('42','24','2.00','151','115000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('43','25','1.00','87','390000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('44','26','6.00','89','29000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('45','27','5.00','114','72000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('46','27','1.00','144','590000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('47','28','5.00','114','72000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('48','29','4.00','152','76000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('49','30','26.00','111','32000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('50','31','15.00','111','32000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('51','32','6.00','89','31000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('52','33','2.00','151','120000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('53','34','25.00','141','6500.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('54','35','3.00','111','32000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('55','36','10.00','111','32000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('56','36','2.00','73','10000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('57','37','10.00','111','32000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('58','38','20.00','127','3000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('59','38','10.00','111','33000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('60','39','30.00','88','24000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('61','39','10.00','130','30000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('62','39','10.00','81','22000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('63','39','10.00','111','32000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('64','40','30.00','88','24000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('65','40','10.00','130','30000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('66','40','10.00','81','22000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('67','40','10.00','111','32000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('68','40','10.00','153','22000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('69','41','600.00','154','215.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('70','42','7.00','120','9000.00','pague de contado al negro 2 piso');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('71','42','2.00','105','45000.00','Juan Gomez');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('72','42','1.00','123','5000.00','Bilmark ');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('73','42','1.00','102','13000.00','Camilo Ruiz
');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('74','42','6.00','104','8000.00','Monica Prima');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('75','42','2.00','103','15000.00','Unitec');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('76','42','47.00','107','1500.00','Porcelana');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('77','42','2.00','116','9000.00','Unitec');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('78','42','1.00','118','15000.00','Unitec');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('79','42','28.00','124','30000.00','Juan Gomez');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('80','42','3.00','125','12000.00','Juan Gomez');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('81','42','1.00','106','10000.00','Barny');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('82','42','48.00','79','18000.00','Juan Gomez');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('83','42','5.00','95','10000.00','Juan Gomez');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('84','42','8.00','119','8000.00','Ruben Botero');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('85','42','1.00','90','65000.00','Monica Prima');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('86','42','1.00','91','25000.00','Monica Prima');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('87','42','21.00','80','20000.00','Juan Correa');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('88','42','14.00','93','70000.00','Juan Gomez');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('89','42','3.00','85','24000.00','Monica Gomez');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('90','42','2.00','113','110000.00','Juan Gomez');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('91','42','56.00','82','12500.00','Monica Gomez');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('92','42','4.00','156','22000.00','Play Tronic');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('93','42','1.00','109','10000.00','Fernando Ramirez');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('94','42','1.00','157','90000.00','Distribuidora Paliado');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('95','42','29.00','159','3500.00','Juan Correa');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('96','43','150.00','78','90000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('97','43','200.00','75','8000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('98','42','9.00','97','3500.00','Juan Correa');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('99','42','4.00','129','5000.00','Juan Correa');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('100','42','3.00','77','860000.00','Electrogamez
');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('101','42','4.00','76','490000.00','Electrogamez
');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('102','42','4.00','126','50000.00','Piña');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('103','42','4.00','155','15000.00','Juan Gomez');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('104','42','1.00','158','1.00','100000');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('105','42','2.00','162','15000.00','Monica Prima');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('106','42','1.00','161','12500.00','Monica Prima');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('107','42','4.00','94','32000.00','Monica Prima');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('108','42','1.00','115','120000.00','Videolandia');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('109','42','6.00','165','4500.00','Monica Prima');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('110','42','2.00','163','12000.00','Unitec');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('111','42','8.00','71','2000.00','Play Tronic');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('112','42','7.00','166','1200.00','Juan Correa');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('113','42','4.00','167','4.00','2000');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('114','42','13.00','75','10000.00','Juan Gomez');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('115','42','1.00','68','800000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('116','42','1.00','168','140000.00','Monica Prima');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('117','42','1.00','169','110000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('118','42','1.00','160','15000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('119','42','3.00','69','540000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('120','42','9.00','70','400000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('121','42','1.00','171','250000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('122','42','1.00','170','265000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('123','42','18.00','100','270000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('124','42','6.00','172','295000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('125','42','1.00','92','420000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('126','42','1.00','173','840000.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('127','42','193.00','174','800.00','');
INSERT INTO `provider_invoice_detail` (`id`,`provider_invoice`,`quantity`,`product`,`unit_value`,`notes`) VALUES
('128','42','48.00','176','300.00','');



-- -------------------------------------------
-- TABLE DATA settings
-- -------------------------------------------
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('ClientInvoiceDeposit_def_date','030_ClientInvoiceDeposit','1','text','','0','date');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('ClientInvoiceDeposit_def_final_payment','030_ClientInvoiceDeposit','4','toggle','','0','boolean_num');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('ClientInvoiceDeposit_def_notes','030_ClientInvoiceDeposit','3','text','','','text');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('ClientInvoiceDeposit_def_value','030_ClientInvoiceDeposit','2','text','','','number');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('ClientInvoiceDetail_def_notes','020_ClientInvoiceDetail','4','text','','','number');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('ClientInvoiceDetail_def_product','020_ClientInvoiceDetail','2','select','entity=Product','','number');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('ClientInvoiceDetail_def_quantity','020_ClientInvoiceDetail','1','text','','','number');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('ClientInvoiceDetail_def_unit_value','020_ClientInvoiceDetail','3','text','','','number');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('ClientInvoice_autoGenerate_details','010_ClientInvoice','4','toggle','','0','boolean');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('ClientInvoice_def_client','010_ClientInvoice','5','select','entity=Client','','number');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('ClientInvoice_def_date','010_ClientInvoice','6','text','','0','date');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('ClientInvoice_def_notes','010_ClientInvoice','9','text','','','text');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('ClientInvoice_def_payment_date','010_ClientInvoice','8','text','','30','date');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('ClientInvoice_def_status','010_ClientInvoice','7','select','entity=Status&listFilter=CLI','20','number');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('ClientInvoice_filter_def_status','010_ClientInvoice','3','select','entity=Status&listFilter=CLI','20','number');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('ClientInvoice_final_status','010_ClientInvoice','2','select','entity=Status&listFilter=CLI','30','number');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('ClientInvoice_stock_status','010_ClientInvoice','1','select','entity=Status&listFilter=CLI','20','number');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('Client_def_address','510_Client','4','text','','','text');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('Client_def_code','510_Client','1','text','','','text');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('Client_def_description','510_Client','3','text','','','text');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('Client_def_mobile','510_Client','6','text','','','text');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('Client_def_name','510_Client','2','text','','','text');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('Client_def_phone','510_Client','5','text','','','text');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('General_ActivateToolTips','550_General','4','toggle','','1','boolean');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('General_adminEmail','550_General','1','text','','@gmail.com','text');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('General_appName','550_General','2','text','','','text');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('General_DefaultShowHeaders','550_General','3','toggle','','1','boolean');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('General_Styles_DepositsColumn','550_General','8','text','','text-align: right; color: #5882FA;','text');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('General_Styles_DepositsFooter','550_General','7','text','','text-align: right; color: #5882FA;','text');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('General_Styles_PendingColumn','550_General','10','text','','text-align: right; color: #FA5858;','text');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('General_Styles_PendingFooter','550_General','9','text','','text-align: right; color: #FA5858;','text');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('General_Styles_TotalColumn','550_General','6','text','','text-align: right; color: #585858;','text');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('General_Styles_TotalFooter','550_General','5','text','','text-align: right; color: #585858;','text');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('MeasureUnit_def_code','530_MeasureUnit','1','text','','','text');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('MeasureUnit_def_eq_reference','530_MeasureUnit','3','text','','','number');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('MeasureUnit_def_name','530_MeasureUnit','2','text','','','text');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('MeasureUnit_def_reference','530_MeasureUnit','4','toggle','','0','boolean_num');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('Product_def_code','500_Product','1','text','','','text');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('Product_def_default_qty','500_Product','4','text','','1','number');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('Product_def_default_value','500_Product','5','text','','','number');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('Product_def_measure_unit','500_Product','3','select','entity=MeasureUnit','7','text');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('Product_def_name','500_Product','2','text','','','text');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('Product_def_stock_movement','500_Product','6','toggle','','1','boolean_num');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('ProviderInvoiceDeposit_def_date','070_ProviderInvoiceDeposit','1','text','','','date');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('ProviderInvoiceDeposit_def_final_payment','070_ProviderInvoiceDeposit','4','toggle','','0','boolean_num');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('ProviderInvoiceDeposit_def_notes','070_ProviderInvoiceDeposit','3','text','','','text');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('ProviderInvoiceDeposit_def_value','070_ProviderInvoiceDeposit','2','text','','','text');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('ProviderInvoiceDetail_def_notes','050_ProviderInvoiceDetail','4','text','','','number');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('ProviderInvoiceDetail_def_product','050_ProviderInvoiceDetail','2','select','entity=Product','','number');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('ProviderInvoiceDetail_def_quantity','050_ProviderInvoiceDetail','1','text','','','number');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('ProviderInvoiceDetail_def_unit_value','050_ProviderInvoiceDetail','3','text','','','number');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('ProviderInvoice_autoGenerate_details','050_ProviderInvoice','4','toggle','','0','boolean');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('ProviderInvoice_def_date','050_ProviderInvoice','6','text','','','date');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('ProviderInvoice_def_notes','050_ProviderInvoice','9','text','','','text');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('ProviderInvoice_def_payment_date','050_ProviderInvoice','8','text','','','date');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('ProviderInvoice_def_provider','050_ProviderInvoice','5','select','entity=Provider','','number');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('ProviderInvoice_def_status','050_ProviderInvoice','7','select','entity=Status&listFilter=PRI','50','number');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('ProviderInvoice_filter_def_status','050_ProviderInvoice','3','select','entity=Status&listFilter=PRI','50','number');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('ProviderInvoice_final_status','050_ProviderInvoice','2','select','entity=Status&listFilter=PRI','','number');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('ProviderInvoice_stock_status','050_ProviderInvoice','1','select','entity=Status&listFilter=PRI','50','number');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('Provider_def_address','520_Provider','4','text','','','text');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('Provider_def_code','520_Provider','1','text','','','text');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('Provider_def_description','520_Provider','3','text','','','text');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('Provider_def_mobile','520_Provider','6','text','','','text');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('Provider_def_name','520_Provider','2','text','','','text');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('Provider_def_phone','520_Provider','5','text','','','text');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('Stock_auto_date','040_Stock','5','toggle','','0','boolean');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('Stock_def_date','040_Stock','3','text','','','date');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('Stock_def_movement_type','040_Stock','4','toggle','enabled=general.label.stockIn&disabled=general.label.stockOut','1','text');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('Stock_def_product','040_Stock','2','select','entity=Product','','text');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('Stock_def_quantity','040_Stock','1','text','','','number');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('User_def_name','540_User','2','text','','','text');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('User_def_password','540_User','3','text','','','text');
INSERT INTO `settings` (`key`,`group`,`group_order`,`type`,`type_specs`,`value`,`data_type`) VALUES
('User_def_username','540_User','1','text','','','text');



-- -------------------------------------------
-- TABLE DATA status
-- -------------------------------------------
INSERT INTO `status` (`id`,`name`,`use_type`) VALUES
('10','PEDIDO','CLI');
INSERT INTO `status` (`id`,`name`,`use_type`) VALUES
('20','COBRO','CLI');
INSERT INTO `status` (`id`,`name`,`use_type`) VALUES
('30','PAGADA','CLI');
INSERT INTO `status` (`id`,`name`,`use_type`) VALUES
('40','PEDIDO','PRI');
INSERT INTO `status` (`id`,`name`,`use_type`) VALUES
('50','PAGAR','PRI');
INSERT INTO `status` (`id`,`name`,`use_type`) VALUES
('60','PAGADA','PRI');



-- -------------------------------------------
-- TABLE DATA stock
-- -------------------------------------------
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('3','10.00','69','2014-08-22','','0.00','0.00','1','','','','');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('5','6.00','122','2014-08-22','','0.00','0.00','1','','','5','2');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('6','50.00','72','2014-08-22','','0.00','0.00','1','','','5','3');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('7','2.00','86','2014-08-22','','0.00','0.00','1','','','5','4');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('8','6.00','98','2014-08-22','','0.00','0.00','1','','','5','5');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('9','2.00','96','2014-08-22','','0.00','0.00','1','','','5','6');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('10','6.00','128','2014-08-22','','0.00','0.00','1','','','5','7');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('15','30.00','101','2014-08-22','','0.00','0.00','1','','','5','12');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('16','50.00','71','2014-08-22','','0.00','0.00','1','','','5','13');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('17','2.00','133','2014-08-22','','0.00','0.00','1','','','5','14');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('18','1.00','132','2014-08-22','','0.00','0.00','1','','','5','15');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('19','7.00','131','2014-08-22','','0.00','0.00','1','','','5','16');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('20','8.00','137','2014-08-22','','0.00','0.00','1','','','5','17');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('21','1.00','136','2014-08-22','','0.00','0.00','1','','','5','18');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('22','24.00','139','2014-08-22','','0.00','0.00','1','','','5','19');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('23','6.00','138','2014-08-22','','0.00','0.00','1','','','5','20');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('24','2.00','135','2014-08-22','','0.00','0.00','1','','','5','21');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('25','6.00','134','2014-08-22','','0.00','0.00','1','','','5','22');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('26','10.00','138','2014-08-22','','0.00','0.00','1','','','6','23');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('27','10.00','137','2014-08-22','','0.00','0.00','1','','','6','24');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('28','10.00','138','2014-08-22','','0.00','0.00','1','','','6','23');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('29','1.00','140','2014-08-22','','0.00','0.00','1','','','7','25');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('30','20.00','114','2014-08-22','','0.00','0.00','1','','','8','26');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('31','25.00','141','2014-08-22','','0.00','0.00','1','','','9','27');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('32','20.00','142','2014-08-22','','0.00','0.00','1','','','9','28');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('33','1.00','143','2014-08-22','','0.00','0.00','1','','','11','29');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('34','1.00','144','2014-08-22','','0.00','0.00','1','','','13','30');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('35','4.00','114','2014-08-22','','0.00','0.00','1','','','14','31');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('36','1.00','145','2014-08-22','','0.00','0.00','1','','','14','32');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('37','108.00','99','2014-08-22','','0.00','0.00','1','','','15','33');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('38','10.00','74','2014-08-22','','0.00','0.00','1','','','15','34');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('39','1.00','144','2014-08-09','','0.00','0.00','1','','','16','35');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('40','2.00','148','2014-08-12','','0.00','0.00','1','','','18','36');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('41','6.00','149','2014-08-08','','0.00','0.00','1','','','20','37');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('42','6.00','150','2014-08-08','','0.00','0.00','1','','','20','38');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('43','6.00','89','2014-08-06','','0.00','0.00','1','','','21','39');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('44','50.00','73','2014-08-09','','0.00','0.00','1','','','22','40');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('45','5.00','148','2014-08-12','','0.00','0.00','1','','','23','41');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('46','2.00','151','2014-08-13','','0.00','0.00','1','','','24','42');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('47','1.00','87','2014-08-13','','0.00','0.00','1','','','25','43');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('48','6.00','89','2014-08-13','','0.00','0.00','1','','','26','44');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('49','5.00','114','2014-07-30','','0.00','0.00','1','','','27','45');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('50','1.00','144','2014-07-30','','0.00','0.00','1','','','27','46');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('51','5.00','114','2014-07-30','','0.00','0.00','1','','','28','47');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('52','4.00','152','2014-07-29','','0.00','0.00','1','','','29','48');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('53','26.00','111','2014-07-23','','0.00','0.00','1','','','30','49');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('54','15.00','111','2014-07-23','','0.00','0.00','1','','','31','50');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('55','6.00','89','2014-07-25','','0.00','0.00','1','','','32','51');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('56','2.00','151','2014-07-14','','0.00','0.00','1','','','33','52');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('57','25.00','141','2014-07-10','','0.00','0.00','1','','','34','53');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('58','3.00','111','2014-07-10','','0.00','0.00','1','','','35','54');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('59','10.00','111','2014-07-07','','0.00','0.00','1','','','36','55');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('60','2.00','73','2014-07-07','','0.00','0.00','1','','','36','56');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('61','10.00','111','2014-07-09','','0.00','0.00','1','','','37','57');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('62','20.00','127','2014-07-02','','0.00','0.00','1','','','38','58');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('63','10.00','111','2014-07-02','','0.00','0.00','1','','','38','59');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('64','30.00','88','2014-07-03','','0.00','0.00','1','','','39','60');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('65','10.00','130','2014-07-03','','0.00','0.00','1','','','39','61');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('66','10.00','81','2014-07-03','','0.00','0.00','1','','','39','62');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('67','10.00','111','2014-07-03','','0.00','0.00','1','','','39','63');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('68','30.00','88','2014-07-03','','0.00','0.00','1','','','40','64');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('69','10.00','130','2014-07-03','','0.00','0.00','1','','','40','65');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('70','10.00','81','2014-07-03','','0.00','0.00','1','','','40','66');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('71','10.00','111','2014-07-03','','0.00','0.00','1','','','40','67');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('72','10.00','153','2014-07-03','','0.00','0.00','1','','','40','68');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('73','600.00','154','2014-07-14','','0.00','0.00','1','','','41','69');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('75','7.00','120','2014-08-23','','0.00','0.00','1','','','42','70');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('76','2.00','105','2014-08-23','','0.00','0.00','1','','','42','71');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('77','1.00','123','2014-08-23','','0.00','0.00','1','','','42','72');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('78','1.00','102','2014-08-23','','0.00','0.00','1','','','42','73');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('79','6.00','104','2014-08-23','','0.00','0.00','1','','','42','74');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('80','2.00','103','2014-08-23','','0.00','0.00','1','','','42','75');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('81','47.00','107','2014-08-23','','0.00','0.00','1','','','42','76');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('82','2.00','116','2014-08-23','','0.00','0.00','1','','','42','77');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('83','1.00','118','2014-08-23','','0.00','0.00','1','','','42','78');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('84','28.00','124','2014-08-23','','0.00','0.00','1','','','42','79');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('85','3.00','125','2014-08-23','','0.00','0.00','1','','','42','80');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('86','1.00','106','2014-08-23','','0.00','0.00','1','','','42','81');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('87','48.00','79','2014-08-23','','0.00','0.00','1','','','42','82');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('88','5.00','95','2014-08-23','','0.00','0.00','1','','','42','83');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('89','8.00','119','2014-08-23','','0.00','0.00','1','','','42','84');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('90','1.00','90','2014-08-23','','0.00','0.00','1','','','42','85');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('91','1.00','91','2014-08-23','','0.00','0.00','1','','','42','86');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('92','21.00','80','2014-08-23','','0.00','0.00','1','','','42','87');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('93','14.00','93','2014-08-23','','0.00','0.00','1','','','42','88');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('94','3.00','85','2014-08-23','','0.00','0.00','1','','','42','89');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('95','2.00','113','2014-08-23','','0.00','0.00','1','','','42','90');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('96','56.00','82','2014-08-23','','0.00','0.00','1','','','42','91');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('97','4.00','156','2014-08-23','','0.00','0.00','1','','','42','92');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('98','1.00','109','2014-08-23','','0.00','0.00','1','','','42','93');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('99','1.00','157','2014-08-23','','0.00','0.00','1','','','42','94');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('100','29.00','159','2014-08-23','','0.00','0.00','1','','','42','95');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('101','1.00','99','2014-08-23','','0.00','0.00','0','9','5','','');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('102','3.00','101','2014-08-23','','0.00','0.00','0','9','6','','');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('103','2.00','156','2014-08-23','','0.00','0.00','0','9','7','','');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('104','150.00','78','2014-08-08','','0.00','0.00','1','','','43','96');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('105','200.00','75','2014-08-08','','0.00','0.00','1','','','43','97');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('106','9.00','97','2014-08-23','','0.00','0.00','1','','','42','98');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('107','4.00','129','2014-08-23','','0.00','0.00','1','','','42','99');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('108','3.00','77','2014-08-23','','0.00','0.00','1','','','42','100');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('109','4.00','76','2014-08-23','','0.00','0.00','1','','','42','101');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('110','4.00','126','2014-08-23','','0.00','0.00','1','','','42','102');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('111','4.00','76','2014-08-23','','0.00','0.00','1','','','42','101');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('112','3.00','77','2014-08-23','','0.00','0.00','1','','','42','100');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('113','4.00','155','2014-08-23','','0.00','0.00','1','','','42','103');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('114','1.00','158','2014-08-23','','0.00','0.00','1','','','42','104');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('115','2.00','162','2014-08-23','','0.00','0.00','1','','','42','105');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('116','1.00','161','2014-08-23','','0.00','0.00','1','','','42','106');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('117','4.00','94','2014-08-23','','0.00','0.00','1','','','42','107');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('118','1.00','115','2014-08-23','','0.00','0.00','1','','','42','108');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('119','4.00','126','2014-08-23','','0.00','0.00','1','','','42','102');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('120','2.00','78','2014-08-23','','0.00','0.00','0','10','8','','');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('121','1.00','99','2014-08-23','','0.00','0.00','0','10','9','','');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('122','1.00','70','2014-08-23','','0.00','0.00','0','10','10','','');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('123','10.00','72','2014-08-23','','0.00','0.00','0','10','11','','');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('124','3.00','159','2014-08-23','','0.00','0.00','0','10','12','','');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('125','6.00','165','2014-08-23','','0.00','0.00','1','','','42','109');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('126','2.00','163','2014-08-23','','0.00','0.00','1','','','42','110');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('127','8.00','71','2014-08-23','','0.00','0.00','1','','','42','111');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('128','4.00','94','2014-08-23','','0.00','0.00','1','','','42','107');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('129','4.00','129','2014-08-23','','0.00','0.00','1','','','42','99');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('130','1.00','161','2014-08-23','','0.00','0.00','1','','','42','106');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('131','2.00','162','2014-08-23','','0.00','0.00','1','','','42','105');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('132','6.00','165','2014-08-23','','0.00','0.00','1','','','42','109');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('133','7.00','166','2014-08-23','','0.00','0.00','1','','','42','112');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('134','4.00','167','2014-08-23','','0.00','0.00','1','','','42','113');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('135','2.00','151','2014-08-23','','0.00','0.00','0','11','13','','');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('137','13.00','75','2014-08-23','','0.00','0.00','1','','','42','114');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('139','1.00','68','2014-08-23','','0.00','0.00','0','13','16','','');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('140','1.00','68','2014-08-23','','0.00','0.00','1','','','42','115');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('144','1.00','168','2014-08-23','','0.00','0.00','1','','','42','116');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('145','1.00','169','2014-08-23','','0.00','0.00','1','','','42','117');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('146','1.00','99','2014-08-23','','0.00','0.00','0','14','20','','');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('150','1.00','160','2014-08-23','','0.00','0.00','1','','','42','118');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('156','3.00','69','2014-08-23','','0.00','0.00','1','','','42','119');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('157','9.00','70','2014-08-23','','0.00','0.00','1','','','42','120');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('158','1.00','171','2014-08-23','','0.00','0.00','1','','','42','121');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('159','1.00','170','2014-08-23','','0.00','0.00','1','','','42','122');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('160','18.00','100','2014-08-23','','0.00','0.00','1','','','42','123');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('161','6.00','172','2014-08-23','','0.00','0.00','1','','','42','124');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('162','1.00','92','2014-08-23','','0.00','0.00','1','','','42','125');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('163','1.00','173','2014-08-23','','0.00','0.00','1','','','42','126');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('164','193.00','174','2014-08-23','','0.00','0.00','1','','','42','127');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('165','48.00','176','2014-08-23','','0.00','0.00','1','','','42','128');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('166','1.00','110','2014-08-23','','0.00','0.00','0','15','30','','');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('167','1.00','110','2014-08-23','','0.00','0.00','0','15','30','','');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('168','58.00','78','2014-08-23','','0.00','0.00','0','12','14','','');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('169','8.00','73','2014-08-23','','0.00','0.00','0','12','15','','');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('170','39.00','88','2014-08-23','','0.00','0.00','0','12','17','','');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('171','17.00','89','2014-08-23','','0.00','0.00','0','12','18','','');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('172','87.00','111','2014-08-23','','0.00','0.00','0','12','19','','');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('173','33.00','114','2014-08-23','','0.00','0.00','0','12','21','','');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('174','16.00','81','2014-08-23','','0.00','0.00','0','12','22','','');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('175','1.00','124','2014-08-23','','0.00','0.00','0','12','23','','');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('176','18.00','72','2014-08-23','','0.00','0.00','0','12','24','','');
INSERT INTO `stock` (`id`,`quantity`,`product`,`date`,`notes`,`init_stock_qty`,`final_stock_qty`,`movement_type`,`client_invoice`,`client_invoice_detail`,`provider_invoice`,`provider_invoice_detail`) VALUES
('177','1.00','105','2014-08-23','','0.00','0.00','0','12','29','','');



-- -------------------------------------------
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
COMMIT;
-- -------------------------------------------
-- -------------------------------------------
-- END BACKUP
-- -------------------------------------------
