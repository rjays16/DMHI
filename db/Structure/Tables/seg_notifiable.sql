
DROP TABLE IF EXISTS `seg_notifiable`;
CREATE TABLE `seg_notifiable` (
  `id` int(11) NOT NULL,
  `icd_description` varchar(500) DEFAULT NULL,
  `other_name` varchar(500) DEFAULT NULL,
  `from_range_icd` varchar(10) DEFAULT NULL,
  `end_range_icd` varchar(10) DEFAULT NULL,
  `other_icd` varchar(50) DEFAULT NULL,
  `icd_10_label` varchar(50) DEFAULT NULL,
  `not_notifiable` tinyint(1) DEFAULT '0',
  `for_maternal` tinyint(1) DEFAULT '0',
  `group_belong` int(11) DEFAULT NULL,
  `morbidity_tab_index` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
);
