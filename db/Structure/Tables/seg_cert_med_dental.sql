
DROP TABLE IF EXISTS `seg_cert_med_dental`;
CREATE TABLE `seg_cert_med_dental` (
  `encounter_nr` varchar(12) DEFAULT NULL,
  `dentist_diagnosis` text,
  `ent_diagnosis` text,
  `oph_diagnosis` text,
  `physician_diagnosis` text,
  `dentist_nr` int(11) DEFAULT NULL,
  `ent_nr` int(11) DEFAULT NULL,
  `oph_nr` int(11) DEFAULT NULL,
  `physician_nr` int(11) DEFAULT NULL,
  `history` text,
  `modify_id` varchar(50) DEFAULT NULL,
  `modify_dt` datetime DEFAULT NULL,
  `create_id` varchar(50) DEFAULT NULL,
  `create_dt` datetime DEFAULT NULL
);
