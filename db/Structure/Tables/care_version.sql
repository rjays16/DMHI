
DROP TABLE IF EXISTS `care_version`;
CREATE TABLE `care_version` (
  `name` varchar(20) NOT NULL DEFAULT '',
  `type` varchar(20) NOT NULL DEFAULT '',
  `number` varchar(10) NOT NULL DEFAULT '',
  `build` varchar(5) NOT NULL DEFAULT '',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `time` time NOT NULL DEFAULT '00:00:00',
  `releaser` varchar(30) NOT NULL DEFAULT ''
);
