
DROP TABLE IF EXISTS `seg_dosages_audit`;
CREATE TABLE `seg_dosages_audit` (
  `audit_id` char(36) NOT NULL,
  `id` char(36) NOT NULL,
  `login_id` varchar(35) NOT NULL,
  `audit_timestamp` datetime NOT NULL,
  `action` enum('create','update','delete','restore') DEFAULT NULL,
  `field_name` varchar(50) NOT NULL,
  `before_value` tinytext,
  `after_value` tinytext,
  PRIMARY KEY (`audit_id`,`field_name`),
  KEY `FK_seg_dosages_audit` (`id`),
  CONSTRAINT `FK_seg_dosages_audit` FOREIGN KEY (`id`) REFERENCES `seg_dosages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);
