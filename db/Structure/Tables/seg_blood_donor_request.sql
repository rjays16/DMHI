
DROP TABLE IF EXISTS `seg_blood_donor_request`;
CREATE TABLE `seg_blood_donor_request` (
  `refno` varchar(12) DEFAULT NULL,
  `donor_id` varchar(12) DEFAULT NULL,
  `donor_relationship` varchar(35) DEFAULT NULL
);
