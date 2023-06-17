
DROP TABLE IF EXISTS `seg_hcare_products`;
CREATE TABLE `seg_hcare_products` (
  `bsked_id` int(11) unsigned NOT NULL,
  `bestellnum` varchar(25) NOT NULL DEFAULT '',
  `amountlimit` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`bsked_id`,`bestellnum`),
  CONSTRAINT `FK_seg_hcare_products` FOREIGN KEY (`bsked_id`) REFERENCES `seg_hcare_bsked` (`bsked_id`) ON DELETE CASCADE ON UPDATE CASCADE
);
