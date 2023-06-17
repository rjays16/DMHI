
DROP TABLE IF EXISTS `seg_notifiable_category`;
CREATE TABLE `seg_notifiable_category` (
  `category_code` varchar(5) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`category_code`)
);
