
DROP TABLE IF EXISTS `seg_rep_params`;
CREATE TABLE `seg_rep_params` (
  `param_id` varchar(20) NOT NULL,
  `variable` varchar(20) DEFAULT NULL,
  `parameter` varchar(50) DEFAULT NULL,
  `param_type` enum('option','time','date','boolean','checkbox','radio','sql','text','autocomplete','textbox') DEFAULT NULL,
  `choices` text,
  `is_active` tinyint(1) DEFAULT '1',
  `ordering` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`param_id`)
);
