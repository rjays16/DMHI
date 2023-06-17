
DROP TABLE IF EXISTS `seg_or_accomplishment_report`;
CREATE TABLE `seg_or_accomplishment_report` (
  `rep_date` date NOT NULL,
  `human_resource` text,
  `materials_equip` text,
  `phy_environment` text,
  `endorsement` text,
  PRIMARY KEY (`rep_date`)
);
