
DROP TABLE IF EXISTS `seg_confinementtype_room_rate`;
CREATE TABLE `seg_confinementtype_room_rate` (
  `id` smallint(5) unsigned NOT NULL,
  `confinetype_id` smallint(5) NOT NULL,
  `service_ward_roomrate` double(10,2) DEFAULT NULL,
  `annex_roomrate` double(10,2) DEFAULT NULL,
  `payward_roomrate` double(10,2) DEFAULT NULL,
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
);
