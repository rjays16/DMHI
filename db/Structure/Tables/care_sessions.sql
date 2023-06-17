
DROP TABLE IF EXISTS `care_sessions`;
CREATE TABLE `care_sessions` (
  `SESSKEY` varchar(32) NOT NULL DEFAULT '',
  `EXPIRY` int(11) unsigned NOT NULL DEFAULT '0',
  `expireref` varchar(64) NOT NULL DEFAULT '',
  `DATA` text NOT NULL,
  PRIMARY KEY (`SESSKEY`),
  KEY `EXPIRY` (`EXPIRY`)
);
