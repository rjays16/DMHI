CREATE ALGORITHM=UNDEFINED VIEW `dates` AS select (curdate() - interval `hisdb_dmhi`.`numbers`.`number` day) AS `date` from `numbers`;
