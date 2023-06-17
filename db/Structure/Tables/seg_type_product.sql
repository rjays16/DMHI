
DROP TABLE IF EXISTS `seg_type_product`;
CREATE TABLE `seg_type_product` (
  `nr` int(10) unsigned NOT NULL,
  `type_code` varchar(4) NOT NULL,
  `name` varchar(80) NOT NULL DEFAULT '',
  `description` varchar(150) NOT NULL DEFAULT '',
  `prod_class` enum('M','S','E','NS','B','HS','OS','DS','LS','RS') NOT NULL DEFAULT 'M',
  `is_inactive` tinyint(1) NOT NULL,
  `is_withexpiry` tinyint(1) NOT NULL,
  `is_withserial` tinyint(1) NOT NULL,
  `modify_id` varchar(35) NOT NULL,
  `modify_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL,
  `create_dt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`nr`)
);
