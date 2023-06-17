
DROP TABLE IF EXISTS `seg_hcare_roomtype`;
CREATE TABLE `seg_hcare_roomtype` (
  `bsked_id` int(11) unsigned NOT NULL,
  `roomtype_nr` smallint(5) unsigned NOT NULL,
  `rateperday` decimal(10,2) DEFAULT '0.00',
  `amountlimit` decimal(10,2) DEFAULT '0.00',
  `dayslimit` int(11) unsigned DEFAULT '0',
  `rateperRVU` decimal(10,2) DEFAULT '0.00',
  `year_dayslimit` int(11) unsigned DEFAULT '0',
  `year_dayslimit_alldeps` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`bsked_id`,`roomtype_nr`),
  KEY `FK_seg_hcare_roomtype_3` (`roomtype_nr`),
  CONSTRAINT `FK_seg_hcare_roomtype` FOREIGN KEY (`bsked_id`) REFERENCES `seg_hcare_bsked` (`bsked_id`) ON DELETE CASCADE ON UPDATE CASCADE
);
