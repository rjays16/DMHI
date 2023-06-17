
DROP TABLE IF EXISTS `seg_pid_memcategory`;
CREATE TABLE `seg_pid_memcategory` (
  `pid` varchar(12) NOT NULL,
  `memcategory_id` int(8) unsigned NOT NULL,
  PRIMARY KEY (`pid`),
  KEY `memcategory_id` (`memcategory_id`),
  CONSTRAINT `FK_seg_pid_memcategory` FOREIGN KEY (`pid`) REFERENCES `care_person` (`pid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_pid_memcategory_2` FOREIGN KEY (`memcategory_id`) REFERENCES `seg_memcategory` (`memcategory_id`) ON UPDATE CASCADE
);
