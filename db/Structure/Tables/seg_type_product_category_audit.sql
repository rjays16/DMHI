
DROP TABLE IF EXISTS `seg_type_product_category_audit`;
CREATE TABLE `seg_type_product_category_audit` (
  `audit_id` char(36) NOT NULL,
  `id` char(36) DEFAULT NULL,
  `login_id` varchar(35) DEFAULT NULL,
  `audit_timestamp` datetime DEFAULT NULL,
  `action` enum('create','update','delete','restore') DEFAULT NULL,
  `field_name` varchar(50) NOT NULL,
  `before_value` tinytext,
  `after_value` tinytext,
  PRIMARY KEY (`audit_id`,`field_name`)
);
