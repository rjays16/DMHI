
DROP TABLE IF EXISTS `seg_upload_error`;
CREATE TABLE `seg_upload_error` (
  `entry_no` bigint(20) unsigned NOT NULL,
  `sql_error` mediumtext NOT NULL,
  `post_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`entry_no`)
);
