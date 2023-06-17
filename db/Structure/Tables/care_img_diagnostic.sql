
DROP TABLE IF EXISTS `care_img_diagnostic`;
CREATE TABLE `care_img_diagnostic` (
  `nr` bigint(20) NOT NULL,
  `pid` int(11) NOT NULL DEFAULT '0',
  `encounter_nr` int(11) NOT NULL DEFAULT '0',
  `doc_ref_ids` varchar(255) DEFAULT NULL,
  `img_type` varchar(10) NOT NULL DEFAULT '',
  `max_nr` tinyint(2) DEFAULT '0',
  `upload_date` date NOT NULL DEFAULT '0000-00-00',
  `cancel_date` date NOT NULL DEFAULT '0000-00-00',
  `cancel_by` varchar(35) DEFAULT NULL,
  `notes` text,
  `status` varchar(25) NOT NULL DEFAULT '',
  `history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`nr`),
  KEY `encounter_nr` (`pid`)
);
