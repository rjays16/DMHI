
DROP TABLE IF EXISTS `seg_assigned_ornos`;
CREATE TABLE `seg_assigned_ornos` (
  `login_id` varchar(35) NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `is_locked` tinyint(1) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `or_from` varchar(15) NOT NULL,
  `or_to` varchar(15) NOT NULL,
  `create_id` varchar(35) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL
);
