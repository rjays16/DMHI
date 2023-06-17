
DROP TABLE IF EXISTS `seg_cmap_nca`;
CREATE TABLE `seg_cmap_nca` (
  `id` char(36) NOT NULL,
  `nca_no` varchar(15) NOT NULL,
  `nca_date` date NOT NULL,
  `mds_subaccount_no` varchar(15) NOT NULL,
  `gsb_branch` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `NewIndex1` (`nca_no`),
  CONSTRAINT `FK_seg_cmap_nca` FOREIGN KEY (`id`) REFERENCES `seg_cmap_allotments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);
