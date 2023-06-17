
DROP TABLE IF EXISTS `seg_triage_category`;
CREATE TABLE `seg_triage_category` (
  `category_id` int(11) NOT NULL,
  `roman_id` varchar(5) DEFAULT NULL,
  `category` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`category_id`)
);
