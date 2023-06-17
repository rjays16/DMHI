
DROP TABLE IF EXISTS `seg_industrial_package`;
CREATE TABLE `seg_industrial_package` (
  `package_id` char(36) NOT NULL,
  `package_desc` varchar(80) NOT NULL,
  PRIMARY KEY (`package_id`,`package_desc`)
);
