
DROP TABLE IF EXISTS `seg_or_checklist`;
CREATE TABLE `seg_or_checklist` (
  `checklist_id` int(10) unsigned NOT NULL,
  `checklist_question` text,
  `has_detail` tinyint(1) DEFAULT '0',
  `label_data` varchar(50) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `history` text,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_id` varchar(35) DEFAULT NULL,
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_id` varchar(35) DEFAULT NULL,
  PRIMARY KEY (`checklist_id`)
);
