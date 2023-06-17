
DROP TABLE IF EXISTS `seg_hcare_confinetype`;
CREATE TABLE `seg_hcare_confinetype` (
  `bsked_id` int(11) unsigned NOT NULL,
  `confinetype_id` smallint(5) unsigned NOT NULL,
  `rateperday` decimal(10,2) DEFAULT '0.00',
  `amountlimit` decimal(10,2) DEFAULT '0.00',
  `dayslimit` int(11) unsigned DEFAULT '0',
  `rateperRVU` decimal(10,2) DEFAULT '0.00',
  `limit_rvubased` decimal(10,2) NOT NULL DEFAULT '0.00',
  `year_dayslimit` int(11) unsigned DEFAULT '0',
  `year_dayslimit_alldeps` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`bsked_id`,`confinetype_id`),
  KEY `FK_seg_hcare_confinetype` (`confinetype_id`),
  CONSTRAINT `FK_seg_hcare_confinetype` FOREIGN KEY (`bsked_id`) REFERENCES `seg_hcare_bsked` (`bsked_id`) ON DELETE CASCADE ON UPDATE CASCADE
);
