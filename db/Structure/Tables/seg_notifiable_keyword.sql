
DROP TABLE IF EXISTS `seg_notifiable_keyword`;
CREATE TABLE `seg_notifiable_keyword` (
  `code_illness` varchar(12) NOT NULL,
  `template_id` varchar(12) NOT NULL,
  PRIMARY KEY (`code_illness`,`template_id`),
  KEY `FK_seg_notifiable_keyword_template` (`template_id`),
  CONSTRAINT `FK_seg_notifiable_keyword` FOREIGN KEY (`code_illness`) REFERENCES `seg_notifiable_diseases` (`code_illness`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_notifiable_keyword_template` FOREIGN KEY (`template_id`) REFERENCES `keywords_catalog` (`template_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
);
