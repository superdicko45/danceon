-- danceon.cat_ambientes definition

CREATE TABLE `cat_ambientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ambiente` varchar(100) COLLATE utf8_bin NOT NULL,
  `orden` tinyint(4) NOT NULL DEFAULT 1,
  `activo` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


-- danceon.cat_generos definition

CREATE TABLE `cat_generos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `genero` varchar(100) COLLATE utf8_bin NOT NULL,
  `imagen` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `orden` tinyint(4) NOT NULL DEFAULT 1,
  `activo` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


-- danceon.cat_redes_sociales definition

CREATE TABLE `cat_redes_sociales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `red_social` varchar(100) COLLATE utf8_bin NOT NULL,
  `url` varchar(255) COLLATE utf8_bin NOT NULL,
  `icono` varchar(100) COLLATE utf8_bin NOT NULL,
  `activo` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


-- danceon.cat_tipo_evento definition

CREATE TABLE `cat_tipo_evento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_evento` varchar(200) COLLATE utf8_bin NOT NULL,
  `activo` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


-- danceon.failed_jobs definition

CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- danceon.migrations definition

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- danceon.organizadores definition

CREATE TABLE `organizadores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organizador` varchar(255) COLLATE utf8_bin NOT NULL,
  `imagen` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `descripcion` mediumtext COLLATE utf8_bin DEFAULT NULL,
  `activo` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `organizador_ft` (`organizador`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


-- danceon.password_resets definition

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- danceon.personal_access_tokens definition

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- danceon.users definition

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo_usuario` tinyint(4) NOT NULL DEFAULT 2,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- danceon.eventos definition

CREATE TABLE `eventos` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `url` varchar(100) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `direccion_url` varchar(255) NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 DEFAULT NULL,
  `activo` tinyint(4) NOT NULL DEFAULT 1,
  `tipo_frecuencia` tinyint(4) NOT NULL,
  `organizador_id` int(11) NOT NULL,
  `tipo_ambiente_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `eta_tip_idx` (`tipo_ambiente_id`),
  KEY `eor_org_idx` (`organizador_id`),
  FULLTEXT KEY `tit_des_ft` (`titulo`,`descripcion`),
  FULLTEXT KEY `url_ft` (`url`),
  CONSTRAINT `eor_org_fk` FOREIGN KEY (`organizador_id`) REFERENCES `organizadores` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `eta_tip_fk` FOREIGN KEY (`tipo_ambiente_id`) REFERENCES `cat_ambientes` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;


-- danceon.organizador_redes definition

CREATE TABLE `organizador_redes` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `organizador_id` int(11) NOT NULL,
  `red_id` int(11) NOT NULL,
  `url` varchar(255) COLLATE utf8_bin NOT NULL,
  `activo` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `ore_org_idx` (`organizador_id`),
  KEY `ore_red_idx` (`red_id`),
  CONSTRAINT `ore_org_fk` FOREIGN KEY (`organizador_id`) REFERENCES `cat_redes_sociales` (`id`),
  CONSTRAINT `ore_red_fk` FOREIGN KEY (`red_id`) REFERENCES `organizadores` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


-- danceon.evento_clases definition

CREATE TABLE `evento_clases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `evento_id` bigint(20) NOT NULL,
  `clase` varchar(255) COLLATE utf8_bin NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_final` time DEFAULT NULL,
  `activo` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `ecl_eve_idx` (`evento_id`),
  CONSTRAINT `ecl_eve_fk` FOREIGN KEY (`evento_id`) REFERENCES `eventos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


-- danceon.evento_generos definition

CREATE TABLE `evento_generos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `evento_id` bigint(20) NOT NULL,
  `genero_id` int(11) NOT NULL,
  `activo` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `ege_eve_idx` (`evento_id`),
  KEY `ege_gen_idx` (`genero_id`),
  CONSTRAINT `ege_eve_fk` FOREIGN KEY (`evento_id`) REFERENCES `eventos` (`id`),
  CONSTRAINT `ege_gen_fk` FOREIGN KEY (`genero_id`) REFERENCES `cat_generos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


-- danceon.evento_irregulares definition

CREATE TABLE `evento_irregulares` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `evento_id` bigint(20) NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_final` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `eir_eve_idx` (`evento_id`),
  CONSTRAINT `eir_eve_fk` FOREIGN KEY (`evento_id`) REFERENCES `eventos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


-- danceon.evento_precios definition

CREATE TABLE `evento_precios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `evento_id` bigint(20) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_bin NOT NULL,
  `precio` int(11) DEFAULT 0,
  `nota` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `moneda` char(4) COLLATE utf8_bin DEFAULT NULL,
  `activo` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `epr_eve_idx` (`evento_id`),
  CONSTRAINT `epr_eve_fk` FOREIGN KEY (`evento_id`) REFERENCES `eventos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


-- danceon.evento_regulares definition

CREATE TABLE `evento_regulares` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `evento_id` bigint(20) NOT NULL,
  `dia` tinyint(4) NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_final` time DEFAULT NULL,
  `periodicidad` tinyint(4) NOT NULL DEFAULT 2,
  PRIMARY KEY (`id`),
  KEY `ere_eve_idx` (`evento_id`),
  CONSTRAINT `ere_eve_fk` FOREIGN KEY (`evento_id`) REFERENCES `eventos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO danceon.cat_ambientes (ambiente,orden,activo) VALUES
	 ('outdoor',1,1),
	 ('practice_plus',1,1),
	 ('party',1,1),
	 ('tango',1,1);INSERT INTO danceon.cat_generos (genero,imagen,orden,activo) VALUES
	 ('Salsa cubana',NULL,1,1),
	 ('Salsa',NULL,1,1),
	 ('Bachata',NULL,1,1),
	 ('Kizomba',NULL,1,1),
	 ('Zouk',NULL,1,1),
	 ('Tango',NULL,1,1);INSERT INTO danceon.evento_clases (evento_id,clase,hora_inicio,hora_final,activo) VALUES
	 (1,'Zouk','15:00:00',NULL,1),
	 (2,'Varía (consultar la información del organizador)','20:00:00',NULL,1);INSERT INTO danceon.evento_generos (evento_id,genero_id,activo) VALUES
	 (1,5,1),
	 (2,1,1),
	 (2,2,1),
	 (2,3,1),
	 (2,4,1);INSERT INTO danceon.evento_irregulares (evento_id,fecha_inicio,fecha_final) VALUES
	 (2,'2023-02-28 21:00:00','2023-03-01 01:00:00');INSERT INTO danceon.evento_precios (evento_id,nombre,precio,nota,moneda,activo) VALUES
	 (1,'Sólo social',0,NULL,'COP',1),
	 (1,'Clase + Social',0,'Precio varía - revisar la página de instagram','COP',1),
	 (2,'Sólo social',12,NULL,'COP',1),
	 (2,'Clase + Social',12,NULL,'COP',1);INSERT INTO danceon.evento_regulares (evento_id,dia,hora_inicio,hora_final,periodicidad) VALUES
	 (1,7,'16:00:00',NULL,2);INSERT INTO danceon.eventos (titulo,imagen,url,direccion,direccion_url,descripcion,activo,tipo_frecuencia,organizador_id,tipo_ambiente_id,created_at,updated_at) VALUES
	 ('Zouk Sundays',NULL,'zouk-sundays','Parques del Rio, 6CVC+C5, Medellín','','No siempre hay clases antes de la práctica; consultar las redes e información de contacto del organizador. Los bailarines se juntan en zona techada junto a los restaurantes ("Aquí paró Lucho").',1,1,1,2,'0000-00-00 00:00:00','0000-00-00 00:00:00'),
	 ('Trifásico Social',NULL,'trifasico-social-nueva-guardia','Nueva Guardia Dance Academy, Carrera 69 #43-55, Laureles, Medellín','','Social mensual que ocurre cada último sábado del mes, con buen balance de todos los géneros: 30% salsa, 30% bachata, 20% kizomba, 20% zouk.',1,2,2,1,'0000-00-00 00:00:00','0000-00-00 00:00:00');INSERT INTO danceon.migrations (migration,batch) VALUES
	 ('2014_10_12_000000_create_users_table',1),
	 ('2014_10_12_100000_create_password_resets_table',1),
	 ('2019_08_19_000000_create_failed_jobs_table',1),
	 ('2019_12_14_000001_create_personal_access_tokens_table',1);INSERT INTO danceon.organizadores (organizador,imagen,descripcion,activo) VALUES
	 ('Colombia Zouk Collective',NULL,'We are a community that seeks to promote Brazilian Zouk in Medellín! We create spaces for dancers to share, learn, practice and connect to one another through Zouk. Anyone interested is welcome!',1),
	 ('Nueva Guardia Dance Club',NULL,'Nueva Guardia DC is a Dance School that teaches all rhythms. We are located in the most upbeat neighborhood in Medellín... Here you''ll find the best Bachata and Salsa socials and the best Milonga spaces in the city, with Tango, Milonga and Vals socials. Phone number: (+57) 3054349461 - Email: nuevaguardiadc@gmail.com.',1);INSERT INTO danceon.users (name,email,email_verified_at,password,remember_token,tipo_usuario,created_at,updated_at) VALUES
	 ('Mauricio OR','superdicko45@gmail.com',NULL,'$2y$10$q30Ka947xUHWMuRezUhe6.WqZ1aTk2.nz2JVXBYskYjjAXFpuxg8C',NULL,1,'2022-01-06 17:20:31','2022-01-06 17:20:31');