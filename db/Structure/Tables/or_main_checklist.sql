
DROP TABLE IF EXISTS `or_main_checklist`;
CREATE TABLE `or_main_checklist` (
  `checklist_id` int(10) unsigned NOT NULL,
  `checklist_question` text,
  `history` text,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_id` varchar(35) DEFAULT NULL,
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_id` varchar(35) DEFAULT NULL,
  PRIMARY KEY (`checklist_id`)
);
