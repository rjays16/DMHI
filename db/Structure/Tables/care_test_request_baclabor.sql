
DROP TABLE IF EXISTS `care_test_request_baclabor`;
CREATE TABLE `care_test_request_baclabor` (
  `batch_nr` int(11) NOT NULL,
  `encounter_nr` int(11) unsigned NOT NULL DEFAULT '0',
  `dept_nr` smallint(5) unsigned NOT NULL DEFAULT '0',
  `material` text NOT NULL,
  `test_type` text NOT NULL,
  `material_note` tinytext NOT NULL,
  `diagnosis_note` tinytext NOT NULL,
  `immune_supp` tinyint(4) NOT NULL DEFAULT '0',
  `send_date` date NOT NULL DEFAULT '0000-00-00',
  `sample_date` date NOT NULL DEFAULT '0000-00-00',
  `status` varchar(10) NOT NULL DEFAULT '',
  `history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`batch_nr`),
  KEY `send_date` (`send_date`)
);
