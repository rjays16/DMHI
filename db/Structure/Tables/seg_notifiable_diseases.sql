
DROP TABLE IF EXISTS `seg_notifiable_diseases`;
CREATE TABLE `seg_notifiable_diseases` (
  `code_illness` varchar(12) NOT NULL,
  `illness_name` varchar(200) DEFAULT NULL,
  `description` text,
  `category_code` varchar(10) DEFAULT NULL,
  `ordering` tinyint(1) DEFAULT NULL,
  `status` varchar(15) DEFAULT NULL,
  `history` tinytext,
  `create_id` varchar(35) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL,
  PRIMARY KEY (`code_illness`),
  KEY `FK_seg_notifiable_diseases` (`category_code`),
  CONSTRAINT `FK_seg_notifiable_diseases` FOREIGN KEY (`category_code`) REFERENCES `seg_notifiable_category` (`category_code`) ON DELETE NO ACTION ON UPDATE CASCADE
);
