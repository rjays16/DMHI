
DROP TABLE IF EXISTS `seg_hcare_benefits`;
CREATE TABLE `seg_hcare_benefits` (
  `benefit_id` int(11) unsigned NOT NULL,
  `benefit_desc` varchar(100) NOT NULL,
  `bill_area` enum('AC','MS','HS','OR','D1','D2','D3','D4','XC') NOT NULL COMMENT 'D1-Gen.Practitioner;D2-Specialist;D3-Surgeon;D4-Anesthesiologist',
  `is_withlevel` tinyint(1) NOT NULL DEFAULT '0',
  `is_overall` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`benefit_id`),
  KEY `bill_area` (`bill_area`)
);
