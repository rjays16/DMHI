
DROP TABLE IF EXISTS `seg_hl7_file_received`;
CREATE TABLE `seg_hl7_file_received` (
  `date_received` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `filename` varchar(100) NOT NULL,
  `hl7_msg` text NOT NULL,
  `parse_status` enum('done','pending','deleted') DEFAULT 'pending',
  PRIMARY KEY (`filename`)
);
