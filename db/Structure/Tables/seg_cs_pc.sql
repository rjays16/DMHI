
DROP TABLE IF EXISTS `seg_cs_pc`;
CREATE TABLE `seg_cs_pc` (
  `code` varchar(10) NOT NULL,
  `description` text NOT NULL,
  `group` text,
  `package` double(10,2) NOT NULL,
  `hf` double(5,2) NOT NULL,
  `pf` double(5,2) NOT NULL,
  `case_type` varchar(1) DEFAULT NULL,
  `special_case` tinyint(1) DEFAULT NULL,
  `for_infirmaries` tinyint(1) DEFAULT NULL,
  `is_allowed_second` tinyint(1) DEFAULT NULL
);
