
DROP TABLE IF EXISTS `seg_doctors_soap`;
CREATE TABLE `seg_doctors_soap` (
  `id` char(36) NOT NULL,
  `personell_nr` int(11) NOT NULL,
  `pid` varchar(12) NOT NULL,
  `soap` enum('S','O','A','P') NOT NULL,
  `note` text NOT NULL,
  `is_cancelled` tinyint(1) DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `create_id` varchar(12) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_seg_doctors_soap_patient` (`pid`),
  KEY `FK_seg_doctors_soap_personell` (`personell_nr`),
  CONSTRAINT `FK_seg_doctors_soap_patient` FOREIGN KEY (`pid`) REFERENCES `care_person` (`pid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_doctors_soap_personell` FOREIGN KEY (`personell_nr`) REFERENCES `care_personell` (`nr`) ON DELETE CASCADE ON UPDATE CASCADE
);
