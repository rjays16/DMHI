
DROP TABLE IF EXISTS `seg_radio_findings_code`;
CREATE TABLE `seg_radio_findings_code` (
  `id` int(11) NOT NULL,
  `department_nr` int(10) unsigned NOT NULL,
  `codename` varchar(100) DEFAULT NULL,
  `description` text,
  `impression` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `history` text,
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_dt` datetime DEFAULT NULL,
  `create_id` varchar(35) DEFAULT NULL,
  `create_dt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
);
