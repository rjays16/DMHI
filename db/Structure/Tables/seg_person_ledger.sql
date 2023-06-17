
DROP TABLE IF EXISTS `seg_person_ledger`;
CREATE TABLE `seg_person_ledger` (
  `pid` varchar(12) NOT NULL,
  `balance` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` varchar(35) NOT NULL,
  PRIMARY KEY (`pid`),
  CONSTRAINT `FK_seg_person_ledger_person` FOREIGN KEY (`pid`) REFERENCES `care_person` (`pid`) ON DELETE CASCADE ON UPDATE CASCADE
);
