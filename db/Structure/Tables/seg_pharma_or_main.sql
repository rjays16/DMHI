
DROP TABLE IF EXISTS `seg_pharma_or_main`;
CREATE TABLE `seg_pharma_or_main` (
  `refno` bigint(20) NOT NULL,
  `pharma_refno` varchar(10) DEFAULT NULL,
  `or_main_refno` varchar(10) DEFAULT NULL,
  UNIQUE KEY `refno` (`refno`),
  KEY `FK_seg_pharma_or_main` (`or_main_refno`),
  KEY `FK_seg_pharma_or_main2` (`pharma_refno`),
  CONSTRAINT `FK_seg_pharma_or_main` FOREIGN KEY (`or_main_refno`) REFERENCES `care_encounter_op` (`nr`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_pharma_or_main2` FOREIGN KEY (`pharma_refno`) REFERENCES `seg_pharma_orders` (`refno`) ON DELETE CASCADE ON UPDATE CASCADE
);
