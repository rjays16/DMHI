
DROP TABLE IF EXISTS `seg_blood_hact`;
CREATE TABLE `seg_blood_hact` (
  `pid` varchar(12) NOT NULL,
  `history` text,
  `status` enum('hact','normal') DEFAULT 'hact',
  `create_id` varchar(60) DEFAULT NULL,
  `create_tm` datetime DEFAULT NULL,
  `modify_id` varchar(60) DEFAULT NULL,
  `modify_tm` datetime DEFAULT NULL,
  PRIMARY KEY (`pid`)
);
