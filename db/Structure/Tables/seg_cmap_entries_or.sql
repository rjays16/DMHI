
DROP TABLE IF EXISTS `seg_cmap_entries_or`;
CREATE TABLE `seg_cmap_entries_or` (
  `id` char(36) NOT NULL,
  `control_nr` varchar(10) NOT NULL,
  `ref_no` varchar(12) NOT NULL,
  `pid` varchar(35) DEFAULT NULL,
  `walkin_pid` varchar(36) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `service_code` varchar(15) NOT NULL,
  `service_name` varchar(100) NOT NULL,
  `quantity` decimal(10,4) NOT NULL DEFAULT '1.0000',
  `amount` decimal(10,4) NOT NULL DEFAULT '0.0000',
  PRIMARY KEY (`id`),
  KEY `FK_seg_cmap_entry_details` (`id`)
);
