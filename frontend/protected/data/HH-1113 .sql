ALTER TABLE `seg_misc_ops_details`
  CHANGE `sticker_no` `filter_card_no` VARCHAR (20) CHARSET latin1 COLLATE latin1_swedish_ci NULL,
  ADD COLUMN `registry_card_no` VARCHAR (20) NULL AFTER `filter_card_no`,
  ADD COLUMN `nhst_result` ENUM (''PASS'', ''REFER'', ''NOT APPLICABLE'') NULL AFTER `registry_card_no` ;

  /*99460*/
  INSERT INTO `seg_case_rate_special`
  (
  `sp_package_id`,
  `sp_package`,
  `sp_hf`,
  `sp_pf`,
  `sp_shf`,
  `sp_spf`
  )
  VALUES
  (
    '99460',
    '2750.0000',
    '2250.0000',
    '500.0000',
    '2250.0000',
    '500.0000'
  )

  ALTER TABLE `seg_caserate_hearing_test`
    DROP FOREIGN KEY `seg_caserate_hearing_test_ibfk_1` ;

  ALTER TABLE `seg_caserate_hearing_test`
    ADD CONSTRAINT `seg_caserate_hearing_test_ibfk_1` FOREIGN KEY (`encounter_nr`) REFERENCES `care_encounter` (`encounter_nr`) ON UPDATE CASCADE ON DELETE CASCADE ;

  ALTER TABLE `seg_caserate_hearing_test`
    ADD COLUMN `package_id` VARCHAR (25) NOT NULL AFTER `is_availed` ;

  ALTER TABLE `seg_case_rate_packages`
    ADD COLUMN `is_for_newborn` TINYINT (1) DEFAULT 0 NULL AFTER `is_allowed_second` ;

/**code is 9940**/

  UPDATE
    `seg_case_rate_packages`
  SET
    `is_for_newborn` = '1'
  WHERE `code` = '99460' ;
