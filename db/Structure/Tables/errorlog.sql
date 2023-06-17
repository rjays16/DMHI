
DROP TABLE IF EXISTS `errorlog`;
CREATE TABLE `errorlog` (
  `ErrorGID` int(10) unsigned NOT NULL,
  `Message` varchar(128) DEFAULT NULL,
  `Created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ErrorGID`),
  UNIQUE KEY `MessageIndex` (`Message`)
);
