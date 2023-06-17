
DROP TABLE IF EXISTS `seg_gui_mgr`;
CREATE TABLE `seg_gui_mgr` (
  `nr` smallint(6) NOT NULL,
  `ref_source` char(2) DEFAULT NULL,
  `section` varchar(35) DEFAULT NULL,
  `no_rows` smallint(2) NOT NULL DEFAULT '0',
  `no_cols` smallint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`nr`)
);
