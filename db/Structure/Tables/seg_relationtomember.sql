
DROP TABLE IF EXISTS `seg_relationtomember`;
CREATE TABLE `seg_relationtomember` (
  `relation_code` char(1) NOT NULL,
  `relation_desc` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`relation_code`)
);
