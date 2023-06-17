
DROP TABLE IF EXISTS `seg_pay_credit_cards`;
CREATE TABLE `seg_pay_credit_cards` (
  `or_no` varchar(12) NOT NULL,
  `card_no` varchar(12) NOT NULL,
  `issuing_bank` varchar(200) NOT NULL,
  `card_brand` varchar(50) NOT NULL,
  `cardholder_name` varchar(200) NOT NULL,
  `expiry_date` date NOT NULL,
  `security_code` varchar(12) NOT NULL,
  `amount` decimal(10,4) DEFAULT NULL,
  PRIMARY KEY (`or_no`),
  CONSTRAINT `FK_seg_pay_credit_cards` FOREIGN KEY (`or_no`) REFERENCES `seg_pay` (`or_no`) ON DELETE CASCADE ON UPDATE CASCADE
);
