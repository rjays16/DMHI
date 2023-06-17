
DROP TABLE IF EXISTS `seg_work_status`;
CREATE TABLE `seg_work_status` (
  `work_status_nr` int(2) unsigned NOT NULL,
  `work_status_name` varchar(30) DEFAULT '',
  PRIMARY KEY (`work_status_nr`)
);
