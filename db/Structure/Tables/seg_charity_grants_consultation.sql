
DROP TABLE IF EXISTS `seg_charity_grants_consultation`;
CREATE TABLE `seg_charity_grants_consultation` (
  `pid` varchar(12) NOT NULL,
  `grant_dte` datetime NOT NULL,
  `sw_nr` int(11) NOT NULL,
  `status` enum('valid','expired','cancelled','deleted') DEFAULT 'valid',
  PRIMARY KEY (`pid`,`grant_dte`)
);
