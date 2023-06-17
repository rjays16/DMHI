
DROP TABLE IF EXISTS `seg_cmap_allotments`;
CREATE TABLE `seg_cmap_allotments` (
  `id` char(36) NOT NULL,
  `cmap_account` smallint(5) unsigned NOT NULL,
  `allotment_date` date DEFAULT NULL,
  `amount` decimal(18,4) DEFAULT NULL,
  `amount_word` tinytext,
  `remarks` tinytext,
  `history` tinytext,
  `create_id` varchar(35) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_seg_cmap_allotments_creator` (`create_id`),
  KEY `FK_seg_cmap_allotments_modifier` (`modify_id`),
  KEY `FK_seg_cmap_allotments` (`cmap_account`),
  CONSTRAINT `FK_seg_cmap_allotments` FOREIGN KEY (`cmap_account`) REFERENCES `seg_cmap_accounts` (`account_nr`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_cmap_allotments_creator` FOREIGN KEY (`create_id`) REFERENCES `care_users` (`login_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_cmap_allotments_modifier` FOREIGN KEY (`modify_id`) REFERENCES `care_users` (`login_id`) ON DELETE SET NULL ON UPDATE CASCADE
);
