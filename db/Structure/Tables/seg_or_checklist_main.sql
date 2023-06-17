
DROP TABLE IF EXISTS `seg_or_checklist_main`;
CREATE TABLE `seg_or_checklist_main` (
  `source_area` int(11) NOT NULL,
  `checklist_id` int(11) NOT NULL,
  `is_mandatory` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`source_area`,`checklist_id`)
);
