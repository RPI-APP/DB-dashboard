-- Adminer 4.2.4 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `ip_address` varchar(45) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `user_agent` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `current_clients`;
CREATE TABLE `current_clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ip` (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `data_field`;
CREATE TABLE `data_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `instrument` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL COMMENT '0=int,1=float,2=string',
  `enabled` tinyint(4) NOT NULL DEFAULT '1',
  `timeout` int(11) NOT NULL DEFAULT '10000',
  PRIMARY KEY (`id`),
  UNIQUE KEY `guid` (`guid`),
  UNIQUE KEY `name_instrument` (`name`,`instrument`),
  KEY `data_field_group` (`instrument`),
  CONSTRAINT `data_field_ibfk_2` FOREIGN KEY (`instrument`) REFERENCES `instrument` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `data_field` (`id`, `guid`, `name`, `description`, `instrument`, `type`, `enabled`, `timeout`) VALUES
(63,	'17582d45-6afa-11e7-971b-6c0b843e9461',	'Current',	'',	13,	1,	1,	10000),
(64,	'256cb785-6afa-11e7-971b-6c0b843e9461',	'Position',	'',	13,	1,	1,	10000),
(65,	'294ad913-6afa-11e7-971b-6c0b843e9461',	'Velocity',	'',	13,	1,	1,	10000),
(66,	'f298d433-6afc-11e7-971b-6c0b843e9461',	'PT5',	'pressure transducer after flow meter',	18,	1,	1,	10000),
(67,	'f755e614-6afc-11e7-971b-6c0b843e9461',	'Flow Meter',	'',	18,	1,	1,	10000),
(68,	'0212e8ea-6afd-11e7-971b-6c0b843e9461',	'PT4',	'pressure transducer before flow meter',	18,	1,	1,	10000),
(69,	'0c326b8b-6afd-11e7-971b-6c0b843e9461',	'PT1',	'',	19,	1,	1,	10000),
(70,	'0e9b0ffe-6afd-11e7-971b-6c0b843e9461',	'PT2',	'',	19,	1,	1,	10000),
(71,	'115181d0-6afd-11e7-971b-6c0b843e9461',	'PT3',	'',	19,	1,	1,	10000),
(72,	'32ed8501-9d59-11e7-971b-6c0b843e9461',	'Pirani Pressure',	'Pressure from Pirani Guage',	20,	1,	1,	10000),
(73,	'519dcee9-9d59-11e7-971b-6c0b843e9461',	'Transducer Pressure',	'Pressure from 10 bar Pressure Transducer',	20,	1,	1,	10000),
(74,	'9fba78d4-9d5a-11e7-971b-6c0b843e9461',	'Electrometer',	'Current Reading from Electrometer',	20,	1,	1,	10000),
(75,	'295a98b8-a458-11e7-971b-6c0b843e9461',	'LED Shutter',	'Servo angle of LED shutter\n180 = closed\n90 = open',	20,	1,	1,	10000),
(76,	'76ea9b55-a458-11e7-971b-6c0b843e9461',	'Event Trigger',	'Shutter box button to signal manual events',	20,	0,	1,	10000),
(77,	'a0fb2bc7-a85c-11e7-971b-6c0b843e9461',	'temperature',	'coil temp',	13,	1,	1,	10000),
(78,	'c47fa972-ad0c-11e7-971b-6c0b843e9461',	'Pirani',	'Pirani Guage',	19,	1,	1,	10000);

DROP TABLE IF EXISTS `data_type_float`;
CREATE TABLE `data_type_float` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `data_field` int(11) NOT NULL,
  `data` decimal(34,17) NOT NULL,
  `timestamp` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `data_field_timestamp` (`data_field`,`timestamp`),
  CONSTRAINT `data_type_float_ibfk_2` FOREIGN KEY (`data_field`) REFERENCES `data_field` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `data_type_int`;
CREATE TABLE `data_type_int` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `data_field` int(11) NOT NULL,
  `data` bigint(20) NOT NULL,
  `timestamp` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `data_field_timestamp` (`data_field`,`timestamp`),
  CONSTRAINT `data_type_int_ibfk_1` FOREIGN KEY (`data_field`) REFERENCES `data_field` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `data_type_string`;
CREATE TABLE `data_type_string` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `data_field` int(11) NOT NULL,
  `data` text COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `data_field_timestamp` (`data_field`,`timestamp`),
  CONSTRAINT `data_type_string_ibfk_1` FOREIGN KEY (`data_field`) REFERENCES `data_field` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `freeboard`;
CREATE TABLE `freeboard` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `json` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  CONSTRAINT `freeboard_ibfk_1` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `information`;
CREATE TABLE `information` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `value` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `information` (`id`, `title`, `value`) VALUES
(1,	'clock',	1508805113736);

DROP TABLE IF EXISTS `instrument`;
CREATE TABLE `instrument` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `instrument` (`id`, `name`, `enabled`) VALUES
(13,	'MagPump',	1),
(18,	'Front Room Sensors',	1),
(19,	'Back Room Pressure Sensors',	1),
(20,	'PC Test',	1);

DROP TABLE IF EXISTS `log`;
CREATE TABLE `log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `text` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `time_marker`;
CREATE TABLE `time_marker` (
  `id` int(11) NOT NULL,
  `name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `can_users_edit` tinyint(4) NOT NULL DEFAULT '0',
  `can_instruments_rem` tinyint(4) NOT NULL DEFAULT '0',
  `can_instruments_add` tinyint(4) NOT NULL DEFAULT '0',
  `can_data_view` tinyint(4) NOT NULL DEFAULT '1',
  `can_markers_manage` tinyint(4) NOT NULL DEFAULT '0',
  `json` mediumtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `users` (`id`, `username`, `password`, `name`, `can_users_edit`, `can_instruments_rem`, `can_instruments_add`, `can_data_view`, `can_markers_manage`, `json`) VALUES
(1,	'centod',	'29218137c77d38259d18a55d319098b07478cd48',	'Daniel Centore',	1,	1,	1,	1,	1,	'{\n	\"version\": 1,\n	\"allow_edit\": true,\n	\"plugins\": [],\n	\"panes\": [\n		{\n			\"width\": 1,\n			\"row\": {\n				\"2\": 1,\n				\"3\": 1\n			},\n			\"col\": {\n				\"2\": 1,\n				\"3\": 2\n			},\n			\"col_width\": \"2\",\n			\"widgets\": [\n				{\n					\"type\": \"sparkline\",\n					\"settings\": {\n						\"title\": \"(dbl) [ TestInstrument / TestField ]\",\n						\"value\": [\n							\"min(max(datasources[\\\"(dbl) [ TestInstrument / TestField ]\\\"],20),0)\"\n						]\n					}\n				},\n				{\n					\"type\": \"indicator\",\n					\"settings\": {\n						\"value\": \"datasources[\\\"(dbl) [ TestInstrument / TestField ]\\\"][\\\"connected\\\"]\",\n						\"on_text\": \"Connected\",\n						\"off_text\": \"Not connected\"\n					}\n				}\n			]\n		}\n	],\n	\"datasources\": [\n		{\n			\"name\": \"(dbl) [ TestInstrument / TestField ]\",\n			\"type\": \"centek_database_plugin\",\n			\"settings\": {\n				\"guid\": \"(dbl) [ TestInstrument / TestField ] 86b0eb58-2a87-11e7-820d-6c0b843e9461\",\n				\"refresh_time\": 200,\n				\"name\": \"(dbl) [ TestInstrument / TestField ]\"\n			}\n		}\n	],\n	\"columns\": 3\n}'),
(11,	'admin',	'd948d86510198ef7171c469b63acba2b18564ca6',	'Administrator',	1,	0,	1,	1,	1,	'{\n	\"version\": 1,\n	\"allow_edit\": true,\n	\"plugins\": [],\n	\"panes\": [],\n	\"datasources\": [],\n	\"columns\": 3\n}'),
(15,	'berget2',	'99a7bd63080e53b25e204f783d3c87cc044829de',	'Ted Berger',	1,	1,	1,	1,	1,	'{\n	\"version\": 1,\n	\"allow_edit\": true,\n	\"plugins\": [],\n	\"panes\": [\n		{\n			\"width\": 1,\n			\"row\": {\n				\"2\": 1,\n				\"3\": 1,\n				\"4\": 1,\n				\"5\": 1\n			},\n			\"col\": {\n				\"2\": 2,\n				\"3\": 3,\n				\"4\": 4,\n				\"5\": 3\n			},\n			\"col_width\": 1,\n			\"widgets\": [\n				{\n					\"type\": \"indicator\",\n					\"settings\": {\n						\"title\": \"Mag Pump Amplifier On\",\n						\"value\": \"datasources[\\\"Mag Pump Temperature\\\"][\\\"connected\\\"]\"\n					}\n				},\n				{\n					\"type\": \"gauge\",\n					\"settings\": {\n						\"title\": \"Coil Temperature\",\n						\"value\": \"datasources[\\\"Mag Pump Temperature\\\"][\\\"raw_value\\\"]\",\n						\"units\": \"Ohms\",\n						\"min_value\": 0,\n						\"max_value\": \"2000\"\n					}\n				},\n				{\n					\"type\": \"sparkline\",\n					\"settings\": {\n						\"title\": \"Mag Pump Temperature\",\n						\"value\": [\n							\"datasources[\\\"Mag Pump Temperature\\\"][\\\"raw_value\\\"]\"\n						]\n					}\n				},\n				{\n					\"type\": \"gauge\",\n					\"settings\": {\n						\"title\": \"Position\",\n						\"value\": \"datasources[\\\"Mag Pump Position\\\"][\\\"raw_value\\\"]\",\n						\"units\": \"mm\",\n						\"min_value\": 0,\n						\"max_value\": \"200\"\n					}\n				},\n				{\n					\"type\": \"sparkline\",\n					\"settings\": {\n						\"title\": \"Pump Position\",\n						\"value\": [\n							\"datasources[\\\"Mag Pump Position\\\"][\\\"raw_value\\\"]\"\n						]\n					}\n				}\n			]\n		},\n		{\n			\"width\": 1,\n			\"row\": {\n				\"2\": 7,\n				\"3\": 1,\n				\"4\": 1,\n				\"5\": 1\n			},\n			\"col\": {\n				\"2\": 1,\n				\"3\": 2,\n				\"4\": 3,\n				\"5\": 4\n			},\n			\"col_width\": 1,\n			\"widgets\": [\n				{\n					\"type\": \"indicator\",\n					\"settings\": {\n						\"title\": \"Electrometer On\",\n						\"value\": \"datasources[\\\"Electrometer\\\"][\\\"connected\\\"]\"\n					}\n				},\n				{\n					\"type\": \"sparkline\",\n					\"settings\": {\n						\"title\": \"Current\",\n						\"value\": [\n							\"datasources[\\\"Electrometer\\\"][\\\"raw_value\\\"]\"\n						]\n					}\n				},\n				{\n					\"type\": \"gauge\",\n					\"settings\": {\n						\"title\": \"Electrometer\",\n						\"value\": \"datasources[\\\"Electrometer\\\"][\\\"raw_value\\\"]\",\n						\"units\": \"A\",\n						\"min_value\": \"-0.000000001\",\n						\"max_value\": \"0.000000001\"\n					}\n				}\n			]\n		},\n		{\n			\"width\": 1,\n			\"row\": {\n				\"2\": 21,\n				\"3\": 1,\n				\"5\": 1\n			},\n			\"col\": {\n				\"2\": 1,\n				\"3\": 2,\n				\"5\": 2\n			},\n			\"col_width\": 1,\n			\"widgets\": [\n				{\n					\"type\": \"indicator\",\n					\"settings\": {\n						\"title\": \"FR Sensors On (Flow Meter, PT4-5)\",\n						\"value\": \"datasources[\\\"Flow Meter\\\"][\\\"connected\\\"]\"\n					}\n				},\n				{\n					\"type\": \"sparkline\",\n					\"settings\": {\n						\"title\": \"Flow Meter\",\n						\"value\": [\n							\"datasources[\\\"Flow Meter\\\"][\\\"raw_value\\\"]\"\n						]\n					}\n				},\n				{\n					\"type\": \"sparkline\",\n					\"settings\": {\n						\"title\": \"Pressure Transducers\",\n						\"value\": [\n							\"datasources[\\\"PT4\\\"][\\\"raw_value\\\"]\",\n							\"datasources[\\\"PT5\\\"][\\\"raw_value\\\"]\"\n						],\n						\"include_legend\": true,\n						\"legend\": \"PT4, PT5\"\n					}\n				},\n				{\n					\"type\": \"gauge\",\n					\"settings\": {\n						\"title\": \"Flow meter\",\n						\"value\": \"datasources[\\\"Flow Meter\\\"][\\\"raw_value\\\"]*50.0/1024.0\",\n						\"units\": \"SLPM\",\n						\"min_value\": 0,\n						\"max_value\": \"50\"\n					}\n				},\n				{\n					\"type\": \"gauge\",\n					\"settings\": {\n						\"title\": \"PT4\",\n						\"value\": \"datasources[\\\"PT4\\\"][\\\"raw_value\\\"]\",\n						\"units\": \"ADC\",\n						\"min_value\": 0,\n						\"max_value\": \"1023\"\n					}\n				},\n				{\n					\"type\": \"gauge\",\n					\"settings\": {\n						\"title\": \"PT5\",\n						\"value\": \"datasources[\\\"PT5\\\"][\\\"raw_value\\\"]\",\n						\"units\": \"ADC\",\n						\"min_value\": 0,\n						\"max_value\": \"1023\"\n					}\n				}\n			]\n		},\n		{\n			\"title\": \"Shutter Box\",\n			\"width\": 1,\n			\"row\": {\n				\"2\": 1,\n				\"3\": 9,\n				\"4\": 9,\n				\"5\": 1\n			},\n			\"col\": {\n				\"2\": 1,\n				\"3\": 3,\n				\"4\": 4,\n				\"5\": 5\n			},\n			\"col_width\": 1,\n			\"widgets\": [\n				{\n					\"type\": \"indicator\",\n					\"settings\": {\n						\"title\": \"Shutter\",\n						\"value\": \"datasources[\\\"LED Shutter\\\"][\\\"connected\\\"]\"\n					}\n				},\n				{\n					\"type\": \"indicator\",\n					\"settings\": {\n						\"title\": \"Event Trigger\",\n						\"value\": \"datasources[\\\"Event Trigger\\\"][\\\"connected\\\"]\"\n					}\n				}\n			]\n		},\n		{\n			\"width\": 1,\n			\"row\": {\n				\"2\": 1,\n				\"3\": 75,\n				\"4\": 75,\n				\"5\": 1\n			},\n			\"col\": {\n				\"2\": -8,\n				\"3\": -8,\n				\"4\": -7,\n				\"5\": -8\n			},\n			\"col_width\": \"1\",\n			\"widgets\": [\n				{\n					\"type\": \"indicator\",\n					\"settings\": {\n						\"title\": \"BR Pressure Sensors On (Pirani, PT1-3)\",\n						\"value\": \"datasources[\\\"Pirani\\\"][\\\"connected\\\"]\"\n					}\n				},\n				{\n					\"type\": \"sparkline\",\n					\"settings\": {\n						\"title\": \"Pirani Pressure\",\n						\"value\": [\n							\"datasources[\\\"Pirani\\\"][\\\"raw_value\\\"]\"\n						]\n					}\n				},\n				{\n					\"type\": \"sparkline\",\n					\"settings\": {\n						\"title\": \"Pressure Transducers\",\n						\"value\": [\n							\"datasources[\\\"PT1\\\"][\\\"raw_value\\\"]\",\n							\"datasources[\\\"PT2\\\"][\\\"raw_value\\\"]\",\n							\"datasources[\\\"PT3\\\"][\\\"raw_value\\\"]\"\n						],\n						\"include_legend\": true,\n						\"legend\": \"PT1, PT2, PT3\"\n					}\n				},\n				{\n					\"type\": \"gauge\",\n					\"settings\": {\n						\"title\": \"Pirani\",\n						\"value\": \"datasources[\\\"Pirani\\\"][\\\"raw_value\\\"]\",\n						\"units\": \"ADC\",\n						\"min_value\": 0,\n						\"max_value\": \"1023\"\n					}\n				},\n				{\n					\"type\": \"gauge\",\n					\"settings\": {\n						\"title\": \"PT1\",\n						\"value\": \"datasources[\\\"PT1\\\"][\\\"raw_value\\\"]\",\n						\"units\": \"ADC\",\n						\"min_value\": 0,\n						\"max_value\": \"1023\"\n					}\n				},\n				{\n					\"type\": \"gauge\",\n					\"settings\": {\n						\"title\": \"PT2\",\n						\"value\": \"datasources[\\\"PT2\\\"][\\\"raw_value\\\"]\",\n						\"units\": \"ADC\",\n						\"min_value\": 0,\n						\"max_value\": \"1023\"\n					}\n				},\n				{\n					\"type\": \"gauge\",\n					\"settings\": {\n						\"title\": \"PT3\",\n						\"value\": \"datasources[\\\"PT3\\\"][\\\"raw_value\\\"]\",\n						\"units\": \"ADC\",\n						\"min_value\": 0,\n						\"max_value\": \"1023\"\n					}\n				}\n			]\n		}\n	],\n	\"datasources\": [\n		{\n			\"name\": \"Electrometer\",\n			\"type\": \"centek_database_plugin\",\n			\"settings\": {\n				\"guid\": \"(dbl) [ PC Test / Electrometer ] 9fba78d4-9d5a-11e7-971b-6c0b843e9461\",\n				\"refresh_time\": 200,\n				\"name\": \"Electrometer\"\n			}\n		},\n		{\n			\"name\": \"LED Shutter\",\n			\"type\": \"centek_database_plugin\",\n			\"settings\": {\n				\"guid\": \"(dbl) [ PC Test / LED Shutter ] 295a98b8-a458-11e7-971b-6c0b843e9461\",\n				\"refresh_time\": \"100\"\n			}\n		},\n		{\n			\"name\": \"Event Trigger\",\n			\"type\": \"centek_database_plugin\",\n			\"settings\": {\n				\"guid\": \"(int) [ PC Test / Event Trigger ] 76ea9b55-a458-11e7-971b-6c0b843e9461\",\n				\"refresh_time\": \"100\"\n			}\n		},\n		{\n			\"name\": \"Mag Pump Temperature\",\n			\"type\": \"centek_database_plugin\",\n			\"settings\": {\n				\"guid\": \"(dbl) [ MagPump / temperature ] a0fb2bc7-a85c-11e7-971b-6c0b843e9461\",\n				\"refresh_time\": 200\n			}\n		},\n		{\n			\"name\": \"Pirani\",\n			\"type\": \"centek_database_plugin\",\n			\"settings\": {\n				\"guid\": \"(dbl) [ Back Room Pressure Sensors / Pirani ] c47fa972-ad0c-11e7-971b-6c0b843e9461\",\n				\"refresh_time\": \"100\"\n			}\n		},\n		{\n			\"name\": \"PT1\",\n			\"type\": \"centek_database_plugin\",\n			\"settings\": {\n				\"guid\": \"(dbl) [ Back Room Pressure Sensors / PT1 ] 0c326b8b-6afd-11e7-971b-6c0b843e9461\",\n				\"refresh_time\": \"100\"\n			}\n		},\n		{\n			\"name\": \"PT2\",\n			\"type\": \"centek_database_plugin\",\n			\"settings\": {\n				\"guid\": \"(dbl) [ Back Room Pressure Sensors / PT2 ] 0e9b0ffe-6afd-11e7-971b-6c0b843e9461\",\n				\"refresh_time\": \"100\"\n			}\n		},\n		{\n			\"name\": \"PT3\",\n			\"type\": \"centek_database_plugin\",\n			\"settings\": {\n				\"guid\": \"(dbl) [ Back Room Pressure Sensors / PT3 ] 115181d0-6afd-11e7-971b-6c0b843e9461\",\n				\"refresh_time\": \"100\"\n			}\n		},\n		{\n			\"name\": \"Flow Meter\",\n			\"type\": \"centek_database_plugin\",\n			\"settings\": {\n				\"guid\": \"(dbl) [ Front Room Sensors / Flow Meter ] f755e614-6afc-11e7-971b-6c0b843e9461\",\n				\"refresh_time\": \"100\"\n			}\n		},\n		{\n			\"name\": \"PT4\",\n			\"type\": \"centek_database_plugin\",\n			\"settings\": {\n				\"guid\": \"(dbl) [ Front Room Sensors / PT4 ] 0212e8ea-6afd-11e7-971b-6c0b843e9461\",\n				\"refresh_time\": \"100\"\n			}\n		},\n		{\n			\"name\": \"PT5\",\n			\"type\": \"centek_database_plugin\",\n			\"settings\": {\n				\"guid\": \"(dbl) [ Front Room Sensors / PT5 ] f298d433-6afc-11e7-971b-6c0b843e9461\",\n				\"refresh_time\": \"100\"\n			}\n		},\n		{\n			\"name\": \"Mag Pump Current\",\n			\"type\": \"centek_database_plugin\",\n			\"settings\": {\n				\"guid\": \"(dbl) [ MagPump / Current ] 17582d45-6afa-11e7-971b-6c0b843e9461\",\n				\"refresh_time\": \"100\"\n			}\n		},\n		{\n			\"name\": \"Mag Pump Position\",\n			\"type\": \"centek_database_plugin\",\n			\"settings\": {\n				\"guid\": \"(dbl) [ MagPump / Position ] 256cb785-6afa-11e7-971b-6c0b843e9461\",\n				\"refresh_time\": \"100\"\n			}\n		},\n		{\n			\"name\": \"Mag Pump Velocity\",\n			\"type\": \"centek_database_plugin\",\n			\"settings\": {\n				\"guid\": \"(dbl) [ MagPump / Velocity ] 294ad913-6afa-11e7-971b-6c0b843e9461\",\n				\"refresh_time\": \"100\"\n			}\n		}\n	],\n	\"columns\": 5\n}'),
(16,	'youngs4',	'3101a4ad4f75d6fa724fd664537031e531e3e026',	'Sam Youngs',	1,	1,	1,	1,	1,	'{\n	\"version\": 1,\n	\"allow_edit\": true,\n	\"plugins\": [],\n	\"panes\": [\n		{\n			\"title\": \"Pump Readout\",\n			\"width\": 1,\n			\"row\": {\n				\"2\": 1,\n				\"3\": 1\n			},\n			\"col\": {\n				\"2\": 1,\n				\"3\": 1\n			},\n			\"col_width\": \"4\",\n			\"widgets\": [\n				{\n					\"type\": \"sparkline\",\n					\"settings\": {\n						\"title\": \"\",\n						\"value\": [\n							\"datasources[\\\"Position\\\"][\\\"raw_value\\\"]\",\n							\"datasources[\\\"Velocity\\\"][\\\"raw_value\\\"]\"\n						],\n						\"include_legend\": true,\n						\"legend\": \"Position, Velocity\"\n					}\n				},\n				{\n					\"type\": \"sparkline\",\n					\"settings\": {\n						\"title\": \"Flow Meter Output\",\n						\"value\": [\n							\"datasources[\\\"Flow\\\"][\\\"raw_value\\\"]\"\n						],\n						\"include_legend\": true,\n						\"legend\": \"Flow\"\n					}\n				}\n			]\n		}\n	],\n	\"datasources\": [\n		{\n			\"name\": \"Position\",\n			\"type\": \"centek_database_plugin\",\n			\"settings\": {\n				\"guid\": \"256cb785-6afa-11e7-971b-6c0b843e9461\",\n				\"refresh_time\": 200\n			}\n		},\n		{\n			\"name\": \"Velocity\",\n			\"type\": \"centek_database_plugin\",\n			\"settings\": {\n				\"guid\": \"294ad913-6afa-11e7-971b-6c0b843e9461\",\n				\"refresh_time\": 200\n			}\n		},\n		{\n			\"name\": \"Flow\",\n			\"type\": \"centek_database_plugin\",\n			\"settings\": {\n				\"guid\": \"f755e614-6afc-11e7-971b-6c0b843e9461\",\n				\"refresh_time\": 200\n			}\n		}\n	],\n	\"columns\": 3\n}'),
(18,	'wur6',	'd948d86510198ef7171c469b63acba2b18564ca6',	'Raymond Wu',	0,	1,	1,	1,	1,	'{\n	\"version\": 1,\n	\"allow_edit\": true,\n	\"plugins\": [],\n	\"panes\": [\n		{\n			\"width\": 1,\n			\"row\": {\n				\"3\": 1\n			},\n			\"col\": {\n				\"3\": 1\n			},\n			\"col_width\": 1,\n			\"widgets\": []\n		}\n	],\n	\"datasources\": [],\n	\"columns\": 3\n}'),
(19,	'Ishpish',	'cc8f058fdeb7ea144cc15388aefe76bc655975d3',	'Ian Holland',	1,	1,	1,	1,	1,	'{\n	\"version\": 1,\n	\"allow_edit\": true,\n	\"plugins\": [],\n	\"panes\": [],\n	\"datasources\": [],\n	\"columns\": 3\n}'),
(20,	'paulmiller',	'64b51c197f2e7b2b62e3947d793a433886885f77',	'Paul Miller',	1,	1,	1,	1,	1,	NULL),
(21,	'labview',	'10b38d472ead94199ac6cf3d941f0c3841782dc1',	'Labview',	0,	0,	0,	1,	0,	'{\n	\"version\": 1,\n	\"allow_edit\": true,\n	\"plugins\": [],\n	\"panes\": [],\n	\"datasources\": [],\n	\"columns\": 3\n}'),
(22,	'LeafSwordy',	'986d1afbe1e96b553bd3b411e2cc0e091467c98c',	'Leaf Swordy',	1,	1,	1,	1,	1,	'{\n	\"version\": 1,\n	\"allow_edit\": true,\n	\"plugins\": [],\n	\"panes\": [\n		{\n			\"width\": 1,\n			\"row\": {\n				\"3\": 1\n			},\n			\"col\": {\n				\"3\": 1\n			},\n			\"col_width\": 1,\n			\"widgets\": [\n				{\n					\"type\": \"sparkline\",\n					\"settings\": {\n						\"title\": \"Current\",\n						\"value\": [\n							\"datasources[\\\"Current\\\"][\\\"raw_value\\\"]\"\n						]\n					}\n				}\n			]\n		},\n		{\n			\"width\": 1,\n			\"row\": {\n				\"3\": 7\n			},\n			\"col\": {\n				\"3\": 1\n			},\n			\"col_width\": 1,\n			\"widgets\": [\n				{\n					\"type\": \"sparkline\",\n					\"settings\": {\n						\"title\": \"Coil Te3mp\",\n						\"value\": [\n							\"datasources[\\\"CT\\\"][\\\"raw_value\\\"]\"\n						]\n					}\n				}\n			]\n		}\n	],\n	\"datasources\": [\n		{\n			\"name\": \"CT\",\n			\"type\": \"centek_database_plugin\",\n			\"settings\": {\n				\"guid\": \"(dbl) [ MagPump / temperature ] a0fb2bc7-a85c-11e7-971b-6c0b843e9461\",\n				\"refresh_time\": 200,\n				\"name\": \"CT\"\n			}\n		},\n		{\n			\"name\": \"Current\",\n			\"type\": \"centek_database_plugin\",\n			\"settings\": {\n				\"guid\": \"(dbl) [ MagPump / Current ] 17582d45-6afa-11e7-971b-6c0b843e9461\",\n				\"refresh_time\": 200,\n				\"name\": \"Current\"\n			}\n		}\n	],\n	\"columns\": 3\n}');

-- 2017-10-24 00:32:11
