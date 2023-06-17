
DROP TABLE IF EXISTS `seg_type_of_charge`;
CREATE TABLE `seg_type_of_charge` (
  `id` int(11) NOT NULL,
  `charge_name` varchar(50) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `is_excludedfrombilling` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
);
