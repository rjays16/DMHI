
DROP TABLE IF EXISTS `seg_death_room_rate`;
CREATE TABLE `seg_death_room_rate` (
  `id` smallint(5) NOT NULL,
  `rate` double(10,4) NOT NULL,
  `modify_id` varchar(20) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(20) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
);
