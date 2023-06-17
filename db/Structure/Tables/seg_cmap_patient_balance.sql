
DROP TABLE IF EXISTS `seg_cmap_patient_balance`;
CREATE TABLE `seg_cmap_patient_balance` (
  `pid` varchar(12) NOT NULL,
  `account_nr` int(10) unsigned NOT NULL,
  `running_balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `create_id` varchar(35) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modify_id` varchar(35) NOT NULL,
  `modify_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`pid`,`account_nr`),
  KEY `FK_seg_cmap_patient_balance_account` (`account_nr`),
  CONSTRAINT `FK_seg_cmap_patient_balance` FOREIGN KEY (`pid`) REFERENCES `care_person` (`pid`) ON DELETE CASCADE ON UPDATE CASCADE
);
