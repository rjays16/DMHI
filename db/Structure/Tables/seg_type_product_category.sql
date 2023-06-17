
DROP TABLE IF EXISTS `seg_type_product_category`;
CREATE TABLE `seg_type_product_category` (
  `id` bigint(20) NOT NULL,
  `description` varchar(20) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `create_id` varchar(25) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `modify_id` varchar(25) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
);
