
DROP TABLE IF EXISTS `seg_delete_log`;
CREATE TABLE `seg_delete_log` (
  `delete_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `delete_desc` text NOT NULL,
  `delete_id` varchar(35) NOT NULL,
  PRIMARY KEY (`delete_dt`)
);
