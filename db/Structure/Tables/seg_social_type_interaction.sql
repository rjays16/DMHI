
DROP TABLE IF EXISTS `seg_social_type_interaction`;
CREATE TABLE `seg_social_type_interaction` (
  `type_nr` int(11) NOT NULL,
  `type_of_interaction` varchar(50) NOT NULL,
  PRIMARY KEY (`type_nr`)
);
