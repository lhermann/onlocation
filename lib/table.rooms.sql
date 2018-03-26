CREATE TABLE `rooms` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '',
  `gender` varchar(8) DEFAULT NULL,
  `priority` smallint(6) DEFAULT '1',
  `status` varchar(20) DEFAULT 'Teilnehmer',
  `early_or_late` varchar(11) DEFAULT 'late',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
