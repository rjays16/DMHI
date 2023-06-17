
DROP TABLE IF EXISTS `seg_industrial_vitalsigns`;
CREATE TABLE `seg_industrial_vitalsigns` (
  `exam_nr` int(5) DEFAULT NULL,
  `systole` int(5) DEFAULT NULL,
  `diastole` int(5) DEFAULT NULL,
  `cardiac_rate` int(5) DEFAULT NULL,
  `resp_rate` int(5) DEFAULT NULL,
  `temperature` int(5) DEFAULT NULL,
  `weight` int(5) DEFAULT NULL,
  `height` int(5) DEFAULT NULL,
  `bmi` int(5) DEFAULT NULL
);
