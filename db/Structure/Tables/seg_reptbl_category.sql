
DROP TABLE IF EXISTS `seg_reptbl_category`;
CREATE TABLE `seg_reptbl_category` (
  `code` varchar(10) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`code`)
);
