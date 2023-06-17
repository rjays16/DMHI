
DROP TABLE IF EXISTS `seg_cs_mc`;
CREATE TABLE `seg_cs_mc` (
  `code` varchar(15) NOT NULL,
  `description` text NOT NULL,
  `group` text,
  `package` double(10,2) DEFAULT NULL,
  `hf` double(5,2) DEFAULT NULL,
  `pf` double(5,2) DEFAULT NULL,
  `case_type` varchar(1) DEFAULT NULL,
  `special_case` tinyint(1) DEFAULT NULL,
  `for_infirmaries` tinyint(1) DEFAULT NULL,
  `is_allowed_second` tinyint(1) DEFAULT NULL
);
