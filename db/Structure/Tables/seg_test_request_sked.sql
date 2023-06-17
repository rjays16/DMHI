
DROP TABLE IF EXISTS `seg_test_request_sked`;
CREATE TABLE `seg_test_request_sked` (
  `batch_nr` int(11) NOT NULL COMMENT 'batch_nr from care_test_request_radio',
  `personell_nr` int(11) NOT NULL COMMENT 'personell_nr from care_personell_assignment',
  `status` varchar(10) NOT NULL COMMENT 'status from care_test_request_radio',
  `trace` text NOT NULL,
  `history` text,
  `modify_id` varchar(35) NOT NULL,
  `modify_dt` datetime DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(35) NOT NULL,
  `create_dt` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`batch_nr`,`personell_nr`,`status`)
);
