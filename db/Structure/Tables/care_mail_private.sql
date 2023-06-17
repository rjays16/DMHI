
DROP TABLE IF EXISTS `care_mail_private`;
CREATE TABLE `care_mail_private` (
  `recipient` varchar(60) NOT NULL DEFAULT '',
  `sender` varchar(60) NOT NULL DEFAULT '',
  `sender_ip` varchar(60) NOT NULL DEFAULT '',
  `cc` varchar(255) NOT NULL DEFAULT '',
  `bcc` varchar(255) NOT NULL DEFAULT '',
  `subject` varchar(255) NOT NULL DEFAULT '',
  `body` text NOT NULL,
  `sign` varchar(255) NOT NULL DEFAULT '',
  `ask4ack` tinyint(4) NOT NULL DEFAULT '0',
  `reply2` varchar(255) NOT NULL DEFAULT '',
  `attachment` varchar(255) NOT NULL DEFAULT '',
  `attach_type` varchar(30) NOT NULL DEFAULT '',
  `read_flag` tinyint(4) NOT NULL DEFAULT '0',
  `mailgroup` varchar(60) NOT NULL DEFAULT '',
  `maildir` varchar(60) NOT NULL DEFAULT '',
  `exec_level` tinyint(4) NOT NULL DEFAULT '0',
  `exclude_addr` text NOT NULL,
  `send_dt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `send_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `uid` varchar(255) NOT NULL DEFAULT '',
  KEY `recipient` (`recipient`)
);
