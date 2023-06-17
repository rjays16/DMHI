
DROP TABLE IF EXISTS `seg_industrial_comp_emp`;
CREATE TABLE `seg_industrial_comp_emp` (
  `company_id` varchar(12) NOT NULL,
  `pid` varchar(12) NOT NULL,
  `employee_id` varchar(12) DEFAULT NULL,
  `position` varchar(50) DEFAULT NULL,
  `job_status` enum('regular','contractual','job_order','consultant','student','other') DEFAULT NULL,
  `status` varchar(35) DEFAULT NULL,
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_dt` datetime DEFAULT NULL,
  `create_id` varchar(35) DEFAULT NULL,
  `create_dt` datetime DEFAULT NULL,
  PRIMARY KEY (`company_id`,`pid`),
  KEY `FK_seg_industrial_comp_emp_pid` (`pid`),
  CONSTRAINT `FK_seg_industrial_comp_emp` FOREIGN KEY (`company_id`) REFERENCES `seg_industrial_company` (`company_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_industrial_comp_emp_pid` FOREIGN KEY (`pid`) REFERENCES `care_person` (`pid`) ON UPDATE CASCADE
);
