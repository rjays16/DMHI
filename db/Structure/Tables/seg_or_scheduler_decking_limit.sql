
DROP TABLE IF EXISTS `seg_or_scheduler_decking_limit`;
CREATE TABLE `seg_or_scheduler_decking_limit` (
  `dept_nr` mediumint(8) unsigned NOT NULL,
  `decking_limit` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`dept_nr`),
  CONSTRAINT `FK_seg_or_scheduler_decking_limit` FOREIGN KEY (`dept_nr`) REFERENCES `care_department` (`nr`) ON DELETE NO ACTION ON UPDATE CASCADE
);
