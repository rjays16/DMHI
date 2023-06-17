
DROP TABLE IF EXISTS `seg_rep_template_params`;
CREATE TABLE `seg_rep_template_params` (
  `report_id` varchar(50) NOT NULL,
  `param_id` varchar(20) NOT NULL,
  PRIMARY KEY (`report_id`,`param_id`),
  KEY `FK_seg_rep_template_parameter` (`param_id`),
  CONSTRAINT `FK_seg_rep_template` FOREIGN KEY (`report_id`) REFERENCES `seg_rep_templates_registry` (`report_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_rep_template_parameter` FOREIGN KEY (`param_id`) REFERENCES `seg_rep_params` (`param_id`) ON DELETE NO ACTION ON UPDATE CASCADE
);
