
DROP TABLE IF EXISTS `seg_lab_result_fetchtracker`;
CREATE TABLE `seg_lab_result_fetchtracker` (
  `order_no` varchar(12) NOT NULL DEFAULT '',
  `order_dt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`order_no`)
);
