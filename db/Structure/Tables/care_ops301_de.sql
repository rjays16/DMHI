
DROP TABLE IF EXISTS `care_ops301_de`;
CREATE TABLE `care_ops301_de` (
  `code` varchar(12) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `inclusive` text NOT NULL,
  `exclusive` text NOT NULL,
  `notes` text NOT NULL,
  `std_code` char(1) NOT NULL DEFAULT '',
  `sub_level` tinyint(4) NOT NULL DEFAULT '0',
  `remarks` text NOT NULL,
  KEY `code` (`code`)
);
