
DROP TABLE IF EXISTS `seg_or_checklist_preop_data`;
CREATE TABLE `seg_or_checklist_preop_data` (
  `refno` varchar(12) NOT NULL,
  `checklist_id` int(11) NOT NULL,
  `add_detail` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`refno`,`checklist_id`)
);
