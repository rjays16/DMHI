
DROP TABLE IF EXISTS `seg_industrial_cert_med`;
CREATE TABLE `seg_industrial_cert_med` (
  `cert_nr` int(12) NOT NULL,
  `refno` varchar(12) NOT NULL,
  `remarks` text,
  `dr_nr_med` varchar(12) DEFAULT NULL,
  `history` text,
  `status` varchar(35) DEFAULT NULL,
  `modify_id` tinytext,
  `modify_dt` datetime DEFAULT NULL,
  `create_id` tinytext,
  `create_dt` datetime DEFAULT NULL,
  `medcert_date` date DEFAULT NULL,
  `transaction_date` date DEFAULT NULL,
  `with_medical` tinyint(1) DEFAULT NULL,
  `with_dental` tinyint(1) DEFAULT NULL,
  `dr_nr_dental` varchar(12) DEFAULT NULL,
  `clinic_num` varchar(12) DEFAULT NULL,
  `medical_findings` text,
  `dental_findings` text,
  PRIMARY KEY (`cert_nr`),
  KEY `FK_seg_industrial_cert_med` (`refno`),
  CONSTRAINT `FK_seg_industrial_cert_med` FOREIGN KEY (`refno`) REFERENCES `seg_industrial_transaction` (`refno`) ON DELETE CASCADE ON UPDATE CASCADE
);
