
DROP TABLE IF EXISTS `seg_industrial_company`;
CREATE TABLE `seg_industrial_company` (
  `company_id` varchar(12) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `short_id` varchar(20) DEFAULT NULL,
  `address` varchar(500) DEFAULT NULL,
  `contact_no` varchar(20) DEFAULT NULL,
  `president` varchar(50) DEFAULT NULL,
  `hr_manager` varchar(50) DEFAULT NULL,
  `hosp_acct_no` varchar(20) DEFAULT NULL,
  `status` varchar(35) DEFAULT NULL,
  `history` text,
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_dt` datetime DEFAULT NULL,
  `create_id` varchar(35) DEFAULT NULL,
  `create_dt` datetime DEFAULT NULL,
  PRIMARY KEY (`company_id`)
);
