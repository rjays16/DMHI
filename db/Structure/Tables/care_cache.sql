
DROP TABLE IF EXISTS `care_cache`;
CREATE TABLE `care_cache` (
  `id` varchar(125) NOT NULL DEFAULT '',
  `ctext` text,
  `cbinary` blob,
  `tstamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);
