
DROP TABLE IF EXISTS `seg_audit_trail`;
CREATE TABLE `seg_audit_trail` (
  `ID` varchar(50) NOT NULL,
  `date_changed` datetime NOT NULL COMMENT 'Date and Time of changes',
  `Action_type` enum('insert','update','delete') DEFAULT NULL COMMENT 'Action Type',
  `login` varchar(25) DEFAULT NULL COMMENT 'User who made changes',
  `table_name` varchar(50) DEFAULT NULL COMMENT 'Table affected',
  `field_c` text COMMENT 'Field affected',
  `old_value` text COMMENT 'Old value of the field',
  `new_value` text COMMENT 'New value of the field',
  `pk_field` varchar(30) NOT NULL,
  `pk_value` varchar(20) DEFAULT NULL COMMENT 'Primary key of the field',
  `is_visible` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`ID`)
);
