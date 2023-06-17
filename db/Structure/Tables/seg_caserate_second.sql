
DROP TABLE IF EXISTS `seg_caserate_second`;
CREATE TABLE `seg_caserate_second` (
  `code` varchar(15) NOT NULL,
  `description` text NOT NULL,
  `package` double(10,2) NOT NULL,
  `pf` double(10,2) NOT NULL,
  `hf` double(10,2) NOT NULL
);
