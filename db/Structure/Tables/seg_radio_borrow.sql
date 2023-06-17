
DROP TABLE IF EXISTS `seg_radio_borrow`;
CREATE TABLE `seg_radio_borrow` (
  `borrow_nr` int(10) NOT NULL,
  `batch_nr` int(10) unsigned NOT NULL,
  `borrower_id` varchar(12) NOT NULL,
  `date_borrowed` date NOT NULL,
  `time_borrowed` time DEFAULT NULL,
  `releaser_id` int(11) DEFAULT NULL,
  `date_returned` date DEFAULT '0000-00-00',
  `time_returned` time DEFAULT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `remarks` varchar(150) DEFAULT NULL,
  `status` varchar(15) DEFAULT NULL,
  `history` text,
  `modify_id` varchar(50) DEFAULT NULL,
  `modify_dt` datetime DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(50) DEFAULT NULL,
  `create_dt` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`borrow_nr`),
  KEY `FK_seg_radio_id` (`borrower_id`),
  KEY `FK_seg_radio_borrow` (`batch_nr`)
);
