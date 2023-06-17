
DROP TABLE IF EXISTS `seg_gui_mgr_details`;
CREATE TABLE `seg_gui_mgr_details` (
  `nr` smallint(6) NOT NULL,
  `service_code` varchar(35) NOT NULL,
  `header_data` varchar(100) NOT NULL,
  `row_order_no` smallint(2) NOT NULL DEFAULT '0',
  `col_order_no` smallint(2) NOT NULL DEFAULT '0',
  `name_type` char(1) NOT NULL,
  PRIMARY KEY (`nr`,`service_code`,`header_data`),
  CONSTRAINT `FK_seg_gui_mgr_details` FOREIGN KEY (`nr`) REFERENCES `seg_gui_mgr` (`nr`) ON DELETE CASCADE ON UPDATE CASCADE
);
