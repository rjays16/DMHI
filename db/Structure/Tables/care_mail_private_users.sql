
DROP TABLE IF EXISTS `care_mail_private_users`;
CREATE TABLE `care_mail_private_users` (
  `user_name` varchar(60) NOT NULL DEFAULT '',
  `email` varchar(60) NOT NULL DEFAULT '',
  `alias` varchar(60) NOT NULL DEFAULT '',
  `pw` varchar(255) NOT NULL DEFAULT '',
  `inbox` longtext NOT NULL,
  `sent` longtext NOT NULL,
  `drafts` longtext NOT NULL,
  `trash` longtext NOT NULL,
  `lastcheck` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lock_flag` tinyint(4) NOT NULL DEFAULT '0',
  `addr_book` text NOT NULL,
  `addr_quick` tinytext NOT NULL,
  `secret_q` tinytext NOT NULL,
  `secret_q_ans` tinytext NOT NULL,
  `public` tinyint(4) NOT NULL DEFAULT '0',
  `sig` tinytext NOT NULL,
  `append_sig` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`email`)
);
