<?php
    require('./roots.php');
    require_once($root_path.'include/care_api_classes/class_core.php');

    class OperatingRoom extends Core {

        var $tb_or_request = "seg_or_request";

        var $tb_or_request_details = "seg_or_request_details";

        var $tb_care_person = "care_person";

        var $tb_care_encounter = "care_encounter";

        var $tb_care_department = "care_department";

        var $tb_pre_op_details = "seg_or_pre_op_details";

        var $tb_care_personell = "care_personell";

        var $tb_surgery_team = "seg_or_surgical_team";

        var $tb_post_op_details = "seg_or_post_op_details";

        var $tb_pharma_order = "seg_pharma_orders";

        var $tb_pharma_oorder_details = "seg_pharma_order_items";

        var $tb_pharma_main = "care_pharma_products_main";

        var $tb_anesthesia_use = "seg_or_anesthesia_use";

        var $tb_anesthesia = "seg_or_anesthesia";

        function GetPatientInformation($refno){
            global $db;
            $this->sql = "SELECT cp.`name_first`, 
                                    IF(cp.`name_middle`= NULL, cp.`name_middle`, 'N/A') AS `name_middle`,
                                    cp.`name_last`,
                                    cp.`pid`,
                                    ce.`encounter_nr`,
                                    cp.`age`,
                                    cd.`name_formal` AS department,
                                    sopod.`blood_pressure`,
                                    sopod.`temperature`,
                                    sopod.`pulse`,
                                    sopod.`respiration`  
                            FROM ".$this->tb_or_request." `sor`
                            INNER JOIN ".$this->tb_care_encounter." `ce`
                            ON sor.`encounter_nr` = ce.`encounter_nr`
                            INNER JOIN ".$this->tb_care_person." `cp`
                            ON cp.`pid` = ce.`pid`
                            INNER JOIN ".$this->tb_care_department." `cd`
                            ON cd.`nr` = sor.`dept_nr`
                            INNER JOIN ".$this->tb_pre_op_details." `sopod`
                            ON sor.`or_refno` = sopod.`or_refno`
                            WHERE sor.`or_refno` = ".$db->qstr($refno);
            if($this->result = $db->Execute($this->sql)){
                return $this->result;
            }else{
                return false;
            }
        }

        function getdoctors($refno, $type){
            global $db;
            $DoctorName = "";
            $this->sql = "SELECT cperson.`name_last`,
                               cperson.`name_first`,
                               cperson.`name_middle`
                        FROM ".$this->tb_surgery_team." `sost`
                        INNER JOIN ".$this->tb_care_personell." `cp`
                        ON cp.`nr` = sost.`personell_nr` 
                        INNER JOIN ".$this->tb_care_person." `cperson`
                        ON cp.`pid` = cperson.`pid`
                        WHERE sost.`or_refno` = ".$db->qstr($refno)."
                        AND sost.`role_type` = ".$db->qstr($type);

            if($this->result = $db->Execute($this->sql)){
                while($row = $this->result->FetchRow()){
                    if($row['name_middle'] == ''){
                        $DoctorName .= $row['name_last'].", ".$row['name_first']."<br />";
                    }else{
                        $DoctorName .= $row['name_last'].", ".$row['name_first']." ".$row['name_middle']."<br />"; 
                    }      
                }
                if($DoctorName){
                    return $DoctorName;
                }else{
                    return "N/A";
                }
                
            }else{
                return false;
            }

        }

        function GetOperationDate($refno){
            global $db;
            $this->sql = "SELECT sopod.`operation_start`,
                                sopod.`operation_end`,
                                soprod.`operation_date`
                            FROM ".$this->tb_post_op_details." `sopod`
                            INNER JOIN ".$this->tb_pre_op_details." `soprod`
                            ON sopod.`or_refno` = soprod.`or_refno`
                            WHERE sopod.`or_refno` = ".$db->qstr($refno);
            if($this->result = $db->GetRow($this->sql))
                return $this->result;
            else
                return false;

        }

        function GetDrugs($refno){
            global $db;
            $this->sql = "SELECT SQL_CALC_FOUND_ROWS IF(cppm.`artikelname` <> '', 
                                    cppm.`artikelname`, 
                                    cppm.`generic`) AS name,
                                spoi.`quantity`
                        FROM ".$this->tb_pharma_order." `spo`
                        INNER JOIN ".$this->tb_pharma_oorder_details." `spoi`
                        ON spo.`refno` = spoi.`refno`
                        LEFT JOIN ".$this->tb_pharma_main." `cppm`
                        ON spoi.`bestellnum` = cppm.`bestellnum`
                        WHERE spo.`related_refno` = ".$db->qstr($refno)."
                        AND spoi.`serve_status` = 'S'";
            if($this->result = $db->GetAll($this->sql))
                return $this->result;
            else
                return false;
            

        }

        function GetOperationDetails($refno){
            global $db;
            $this->sql = "SELECT soprod.`pre_op_diagnosis`,
                            sopod.`operation_perform`,
                            sopod.`operation_diagnosis`
                        FROM ".$this->tb_pre_op_details." `soprod`
                        INNER JOIN ".$this->tb_post_op_details." `sopod`
                        ON soprod.`or_refno` = sopod.`or_refno`
                        WHERE soprod.`or_refno` = ".$db->qstr($refno);
            if($this->result = $db->GetRow($this->sql))
                return $this->result;
            else
                return false;
        }

        function GetanastheticDetails($refno){
            global $db;
            $this->sql ="SELECT SQL_CALC_FOUND_ROWS 
                                soa.`anest_name`,
                                soau.`time_begun`,
                                soau.`time_end` 
                        FROM ".$this->tb_anesthesia_use." `soau`
                        INNER JOIN ".$this->tb_anesthesia." `soa`
                        ON soa.`anesth_id` = soau.`anesth_id`
                        WHERE soau.`or_refno` = ".$db->qstr($refno);
            if($this->result = $db->GetAll($this->sql))
                return $this->result;
            else
                return false;
        }
  

        function GetRemarks($refno){
            global $db;

            $this->sql = "SELECT technique_desc
                            FROM ".$this->tb_post_op_details."
                            WHERE or_refno = ".$db->qstr($refno);
            if($this->result = $db->GetOne($this->sql))
                return $this->result;
            else
                return false;
        }
    }//end of function 
?>
