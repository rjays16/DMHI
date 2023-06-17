
DROP TABLE IF EXISTS `care_type_immunization`;
CREATE TABLE `care_type_immunization` (
  `nr` smallint(5) unsigned NOT NULL,
  `type` varchar(20) NOT NULL DEFAULT '',
  `name` varchar(20) NOT NULL DEFAULT '',
  `LD_var` varchar(35) NOT NULL DEFAULT '',
  `period` smallint(6) DEFAULT '0',
  `tolerance` smallint(3) DEFAULT '0',
  `dosage` text,
  `medicine` text NOT NULL,
  `titer` text,
  `note` text,
  `application` tinyint(3) DEFAULT '0',
  `status` varchar(25) NOT NULL DEFAULT 'normal',
  `history` text,
  `use_frequency` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'burn added: August 23, 2006',
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`nr`)
);
