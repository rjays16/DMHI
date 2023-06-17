
DROP TABLE IF EXISTS `seg_cert_med_driver_comment`;
CREATE TABLE `seg_cert_med_driver_comment` (
  `comment_code` varchar(3) NOT NULL,
  `comment_desc` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`comment_code`)
);
