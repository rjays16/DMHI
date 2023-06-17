
DROP TABLE IF EXISTS `seg_memcategory`;
CREATE TABLE `seg_memcategory` (
  `memcategory_id` int(8) unsigned NOT NULL,
  `memcategory_desc` varchar(100) NOT NULL,
  `memcategory_code` varchar(4) NOT NULL,
  `is_employer_info_required` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`memcategory_id`)
);
