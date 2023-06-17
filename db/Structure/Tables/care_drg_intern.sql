
DROP TABLE IF EXISTS `care_drg_intern`;
CREATE TABLE `care_drg_intern` (
  `nr` int(11) NOT NULL,
  `code` varchar(12) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `synonyms` text NOT NULL,
  `notes` text,
  `std_code` char(1) NOT NULL DEFAULT '',
  `sub_level` tinyint(1) NOT NULL DEFAULT '0',
  `parent_code` varchar(12) NOT NULL DEFAULT '',
  `status` varchar(25) NOT NULL DEFAULT '',
  `history` text NOT NULL,
  `modify_id` varchar(25) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(25) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`nr`),
  KEY `nr` (`nr`),
  KEY `code` (`code`)
);
