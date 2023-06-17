
DROP TABLE IF EXISTS `seg_radio_index_finding`;
CREATE TABLE `seg_radio_index_finding` (
  `Batch_nr` int(10) DEFAULT NULL,
  `Finding_nr` int(10) DEFAULT NULL,
  `level_01` int(10) DEFAULT NULL,
  `level_02` int(10) DEFAULT NULL,
  `level_03` varchar(10) DEFAULT NULL,
  `level_04` varchar(10) DEFAULT NULL,
  KEY `level1` (`level_01`),
  KEY `level2` (`level_02`),
  KEY `level4` (`level_04`),
  KEY `level3` (`level_03`)
);
