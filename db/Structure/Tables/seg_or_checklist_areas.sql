
DROP TABLE IF EXISTS `seg_or_checklist_areas`;
CREATE TABLE `seg_or_checklist_areas` (
  `id` int(11) NOT NULL,
  `or_area` varchar(20) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
);
