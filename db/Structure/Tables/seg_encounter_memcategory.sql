
DROP TABLE IF EXISTS `seg_encounter_memcategory`;
CREATE TABLE `seg_encounter_memcategory` (
  `encounter_nr` varchar(12) NOT NULL,
  `memcategory_id` int(8) unsigned NOT NULL,
  PRIMARY KEY (`encounter_nr`),
  KEY `memcategory_id` (`memcategory_id`),
  CONSTRAINT `FK_seg_encounter_memcategory` FOREIGN KEY (`encounter_nr`) REFERENCES `care_encounter` (`encounter_nr`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_encounter_memcategory_memcategory` FOREIGN KEY (`memcategory_id`) REFERENCES `seg_memcategory` (`memcategory_id`) ON UPDATE CASCADE
);
