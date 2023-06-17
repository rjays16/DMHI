
DROP TABLE IF EXISTS `seg_dependents`;
CREATE TABLE `seg_dependents` (
  `parent_pid` varchar(12) NOT NULL,
  `dependent_pid` varchar(12) NOT NULL,
  `relationship` varchar(25) DEFAULT NULL,
  `status` enum('cancelled','deleted','expired','member') DEFAULT NULL,
  `history` text,
  `modify_id` varchar(50) DEFAULT NULL,
  `modify_dt` datetime DEFAULT NULL,
  `create_id` varchar(50) DEFAULT NULL,
  `create_dt` datetime DEFAULT NULL,
  PRIMARY KEY (`parent_pid`,`dependent_pid`),
  KEY `FK_seg_dependents` (`relationship`),
  KEY `FK_seg_dependents_dependentpid` (`dependent_pid`)
);
