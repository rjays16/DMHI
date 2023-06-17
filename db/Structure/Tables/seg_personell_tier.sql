
DROP TABLE IF EXISTS `seg_personell_tier`;
CREATE TABLE `seg_personell_tier` (
  `doctor_nr` int(11) NOT NULL,
  `tier_nr` int(10) unsigned NOT NULL,
  PRIMARY KEY (`doctor_nr`),
  KEY `FK_seg_personell_tier_role_tier` (`tier_nr`),
  CONSTRAINT `FK_seg_personell_tier_personell` FOREIGN KEY (`doctor_nr`) REFERENCES `care_personell` (`nr`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_personell_tier_role_tier` FOREIGN KEY (`tier_nr`) REFERENCES `seg_role_tier` (`tier_nr`) ON UPDATE CASCADE
);
