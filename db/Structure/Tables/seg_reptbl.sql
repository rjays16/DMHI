
DROP TABLE IF EXISTS `seg_reptbl`;
CREATE TABLE `seg_reptbl` (
  `rep_nr` int(4) unsigned NOT NULL,
  `rep_name` varchar(80) NOT NULL,
  `rep_script` varchar(64) NOT NULL,
  `rep_dept_nr` mediumint(8) unsigned DEFAULT NULL,
  `rep_category` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`rep_nr`),
  KEY `FK_seg_reptbl` (`rep_dept_nr`),
  KEY `FK_seg_reptbl_category` (`rep_category`),
  CONSTRAINT `FK_seg_reptbl` FOREIGN KEY (`rep_dept_nr`) REFERENCES `care_department` (`nr`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_reptbl_category` FOREIGN KEY (`rep_category`) REFERENCES `seg_reptbl_category` (`code`) ON DELETE CASCADE ON UPDATE CASCADE
);
