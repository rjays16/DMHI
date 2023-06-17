
DROP TABLE IF EXISTS `seg_lab_params`;
CREATE TABLE `seg_lab_params` (
  `param_id` smallint(5) unsigned NOT NULL,
  `service_code` varchar(10) NOT NULL,
  `name` varchar(35) NOT NULL DEFAULT '',
  `id` varchar(10) NOT NULL DEFAULT '',
  `msr_unit` varchar(15) NOT NULL DEFAULT '',
  `median` varchar(20) DEFAULT NULL,
  `hi_bound` varchar(20) DEFAULT NULL,
  `lo_bound` varchar(20) DEFAULT NULL,
  `hi_critical` varchar(20) DEFAULT NULL,
  `lo_critical` varchar(20) DEFAULT NULL,
  `hi_toxic` varchar(20) DEFAULT NULL,
  `lo_toxic` varchar(20) DEFAULT NULL,
  `status` varchar(25) NOT NULL,
  `remarks` text COMMENT 'Added by burn Sept 5 2006',
  `history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_dt` datetime DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_dt` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`param_id`)
);
