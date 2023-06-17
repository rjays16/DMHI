
DROP TABLE IF EXISTS `chargeable_department_area`;
CREATE TABLE `chargeable_department_area` (
  `department_id` int(11) NOT NULL,
  `chargeable_area` varchar(15) NOT NULL,
  PRIMARY KEY (`department_id`,`chargeable_area`)
);
