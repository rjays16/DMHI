
DROP TABLE IF EXISTS `seg_blood_received_sample_h`;
CREATE TABLE `seg_blood_received_sample_h` (
  `refno` varchar(12) NOT NULL,
  `receiver_id` varchar(60) DEFAULT NULL,
  `received_date` datetime DEFAULT NULL,
  `status` enum('complete','lack','none') DEFAULT NULL,
  PRIMARY KEY (`refno`),
  CONSTRAINT `FK_seg_blood_received_sample_h` FOREIGN KEY (`refno`) REFERENCES `seg_lab_serv` (`refno`) ON DELETE NO ACTION ON UPDATE CASCADE
);
