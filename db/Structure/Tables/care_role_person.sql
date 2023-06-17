
DROP TABLE IF EXISTS `care_role_person`;
CREATE TABLE `care_role_person` (
  `nr` smallint(5) unsigned NOT NULL,
  `group_nr` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `job_type_nr` int(11) NOT NULL COMMENT 'burn added: Sept 28, 2006; used in care_personell',
  `role` varchar(35) NOT NULL DEFAULT '',
  `name` varchar(35) NOT NULL DEFAULT '',
  `LD_var` varchar(35) NOT NULL DEFAULT '',
  `role_area` enum('D1','D2','D3','D4') DEFAULT NULL,
  `status` varchar(25) NOT NULL DEFAULT '',
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` datetime DEFAULT '0000-00-00 00:00:00' COMMENT 'burn modified: Oct 2, 2006 from timestamp to datetime',
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` datetime DEFAULT '0000-00-00 00:00:00' COMMENT 'burn modified: Oct 2, 2006 from timestamp to datetime',
  PRIMARY KEY (`nr`,`group_nr`)
);
