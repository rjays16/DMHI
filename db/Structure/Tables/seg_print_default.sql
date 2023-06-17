
DROP TABLE IF EXISTS `seg_print_default`;
CREATE TABLE `seg_print_default` (
  `ip_address` varchar(15) NOT NULL DEFAULT '',
  `printer_port` varchar(150) NOT NULL DEFAULT '',
  `printer_model` varchar(80) NOT NULL DEFAULT '',
  PRIMARY KEY (`ip_address`)
);
