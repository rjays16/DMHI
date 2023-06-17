
DROP TABLE IF EXISTS `seg_type_charge`;
CREATE TABLE `seg_type_charge` (
  `id` varchar(10) NOT NULL,
  `charge_name` varchar(25) DEFAULT NULL,
  `description` tinytext,
  `ordering` tinyint(1) DEFAULT NULL,
  `is_excludedfrombilling` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
);
