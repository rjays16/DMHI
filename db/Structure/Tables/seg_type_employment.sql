
DROP TABLE IF EXISTS `seg_type_employment`;
CREATE TABLE `seg_type_employment` (
  `type_employment_nr` int(2) unsigned NOT NULL,
  `type_employment_name` varchar(50) DEFAULT '',
  PRIMARY KEY (`type_employment_nr`)
);
