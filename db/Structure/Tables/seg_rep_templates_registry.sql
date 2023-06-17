
DROP TABLE IF EXISTS `seg_rep_templates_registry`;
CREATE TABLE `seg_rep_templates_registry` (
  `report_id` varchar(50) NOT NULL,
  `rep_group` varchar(100) DEFAULT NULL,
  `rep_name` varchar(100) NOT NULL,
  `rep_description` text,
  `rep_script` varchar(100) NOT NULL,
  `rep_dept_nr` mediumint(8) unsigned DEFAULT NULL,
  `rep_category` varchar(10) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `with_template` tinyint(1) DEFAULT '0',
  `query_in_jasper` tinyint(1) DEFAULT '0',
  `template_name` varchar(50) DEFAULT NULL,
  `exclusive_opd_er` tinyint(1) DEFAULT '0',
  `exclusive_death` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`report_id`),
  KEY `FK_seg_reptbl` (`rep_dept_nr`),
  KEY `FK_seg_reptbl_category` (`rep_category`)
);
