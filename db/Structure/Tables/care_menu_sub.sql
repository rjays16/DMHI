
DROP TABLE IF EXISTS `care_menu_sub`;
CREATE TABLE `care_menu_sub` (
  `s_nr` int(11) NOT NULL DEFAULT '0',
  `s_sort_nr` int(11) NOT NULL DEFAULT '0',
  `s_main_nr` int(11) NOT NULL DEFAULT '0',
  `s_ebene` int(11) NOT NULL DEFAULT '0',
  `s_name` varchar(100) NOT NULL DEFAULT '',
  `s_LD_var` varchar(100) NOT NULL DEFAULT '',
  `s_url` varchar(100) NOT NULL DEFAULT '',
  `s_url_ext` varchar(100) NOT NULL DEFAULT '',
  `s_image` varchar(100) NOT NULL DEFAULT '',
  `s_open_image` varchar(100) NOT NULL DEFAULT '',
  `s_is_visible` varchar(100) NOT NULL DEFAULT '',
  `s_hide_by` varchar(100) NOT NULL DEFAULT '',
  `s_status` varchar(100) NOT NULL DEFAULT '',
  `s_modify_id` varchar(100) NOT NULL DEFAULT '',
  `s_modify_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
);
