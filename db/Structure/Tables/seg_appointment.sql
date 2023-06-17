
DROP TABLE IF EXISTS `seg_appointment`;
CREATE TABLE `seg_appointment` (
  `id` int(11) NOT NULL,
  `apptdate` date DEFAULT '0000-00-00',
  `appttime` time DEFAULT '00:00:00',
  `client` varchar(100) DEFAULT NULL,
  `purpose` varchar(200) DEFAULT NULL,
  `place` varchar(100) DEFAULT NULL,
  `dr_nr` int(11) NOT NULL,
  `create_id` varchar(35) DEFAULT NULL,
  `create_dt` datetime DEFAULT NULL,
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_dt` datetime DEFAULT NULL,
  `history` text,
  PRIMARY KEY (`id`)
);
