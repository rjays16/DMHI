
DROP TABLE IF EXISTS `care_ops301_en`;
CREATE TABLE `care_ops301_en` (
  `code` varchar(12) NOT NULL,
  `description` text NOT NULL,
  `inclusive` text NOT NULL,
  `exclusive` text NOT NULL,
  `notes` text NOT NULL,
  `std_code` char(1) NOT NULL DEFAULT '',
  `sub_level` tinyint(4) NOT NULL DEFAULT '0',
  `remarks` text NOT NULL,
  `term` char(15) DEFAULT NULL,
  `iscommon` char(1) DEFAULT NULL,
  `major` char(1) DEFAULT NULL,
  `rvu` int(4) unsigned NOT NULL,
  `multiplier` decimal(10,4) NOT NULL DEFAULT '0.0000',
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_date` datetime DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(35) DEFAULT NULL,
  `create_date` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`code`)
);
