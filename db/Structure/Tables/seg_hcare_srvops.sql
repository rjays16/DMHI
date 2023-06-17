
DROP TABLE IF EXISTS `seg_hcare_srvops`;
CREATE TABLE `seg_hcare_srvops` (
  `bsked_id` int(11) unsigned NOT NULL,
  `code` varchar(12) NOT NULL DEFAULT '',
  `provider` enum('LB','RD','OR','OA') NOT NULL COMMENT 'LB-Lab;RD-Radiology;OR-Operating Room;OA-Other Areas',
  `amountlimit` decimal(10,2) NOT NULL DEFAULT '0.00',
  `maxRVU` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`bsked_id`,`code`,`provider`),
  CONSTRAINT `FK_seg_hcare_srvops` FOREIGN KEY (`bsked_id`) REFERENCES `seg_hcare_bsked` (`bsked_id`) ON DELETE CASCADE ON UPDATE CASCADE
);
