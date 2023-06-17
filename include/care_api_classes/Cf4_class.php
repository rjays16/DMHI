<?php
require_once('./roots.php');
require_once($root_path.'include/inc_environment_global.php');

class Cf4_class
{
    public $enc_no;
    public function __construct($encounter_nr)
    {
        $this->enc_no = $encounter_nr;
    }

    public function doctorsAction()
    {
        global $db;
        $rowindex = 0;
        $detail_1 = 1;
        $doc_action_sql = "SELECT
                              sccitw.date_action,
                              sccitw.doctor_action
                            FROM
                              seg_cf4_course_in_the_ward AS sccitw
                            WHERE sccitw.encounter_nr =".$this->enc_no."
                            AND sccitw.is_deleted != 1
                            ORDER BY sccitw.date_action ASC";
        $doc_action_res = $db->Execute($doc_action_sql);
//        var_dump($doc_action_res);die;
        while ($row = $doc_action_res->FetchRow()){
            $data[$rowindex] = array(
                'requestDate' => date('m-d-Y', strtotime($row['date_action'])),
                'remarks' => $row['doctor_action'],
                'detail_1' => $detail_1
            );
            $rowindex++;
            $detail_1++;
        }
        return $data;
    }

    public function  medicine()
    {
        global $db;
        $rowindex = 1;
        $med_sql = "SELECT 
                      scm.drug_code,
                      scm.generic,
                      scm.cost,
                      scm.frequency,
                      scm.quantity,
                      scm.is_pndf,
                      scm.route 
                    FROM
                      seg_cf4_medicine AS scm 
                    WHERE scm.encounter_nr = ".$this->enc_no."
                      AND scm.is_deleted != 1
                    ORDER BY scm.created_at ASC";
        $med_res = $db->Execute($med_sql);
        while ($row = $med_res->FetchRow()){
            if($row['is_pndf'] == 1){
                $drug_code = $row['drug_code'];
                $code_sql = "SELECT
                    spm.form_code,
                    spm.strength_code
                FROM
                    seg_phil_medicine AS spm
                WHERE spm.drug_code ='$drug_code'";;
                $code_res = $db->Execute($code_sql);
                while ($code_row = $code_res->FetchRow()){
                    $form_code = $code_row['form_code'];
                    $strength_code = $code_row['strength_code'];

                    $form_sql = "SELECT
                  spmf.form_desc
                FROM
                  seg_phil_medicine_form AS spmf
                WHERE spmf.form_code ='$form_code'";
                    $form_res = $db->Execute($form_sql);
                    while ($form_row = $form_res->FetchRow()){
                        $form_desc = $form_row['form_desc'];
                    }

                    $strength_sql = "SELECT
                  spms.strength_desc
                FROM
                  seg_phil_medicine_strength AS spms
                WHERE spms.strength_code = '$strength_code'";
                    $strength_res = $db->Execute($strength_sql);
                    while ($strength_row = $strength_res->FetchRow()){
                        $strength_desc = $strength_row['strength_desc'];
                    }
                }

                $data[$rowindex] = array(
                    'generic_name' => $row['generic'],
                    'total_cost' => $row['cost'],
                    'quantity' => $row['quantity'],
                    'route' => $row['route'],
                    'frequency' => $row['frequency'],
                    'strength_desc' => $strength_desc,
                    'form_desc' => $form_desc,
                );
                $rowindex++;
            }else{
                $data[$rowindex] = array(
                    'generic_name' => $row['generic'],
                    'total_cost' => $row['cost'],
                    'quantity' => $row['quantity'],
                    'route' => $row['route'],
                    'frequency' => $row['frequency'],
                    'strength_desc' => "NONE",
                    'form_desc' => "NONE",
                );
                $rowindex++;
            }

        }

        return $data;
    }
    public function getMisc()
    {
        global $db;
        $misc = "";
        $misc_sql = "SELECT 
            smod.`description`
            FROM
              seg_misc_ops AS smo 
              LEFT JOIN seg_misc_ops_details AS smod 
                ON smo.`refno` = smod.`refno` 
            WHERE smo.`modify_dt` IN 
              (SELECT 
                MAX(smo.`modify_dt`) 
              FROM
                seg_misc_ops AS smo)
                AND smo.`encounter_nr` = '$this->enc_no'";
        $misc_res = $db->Execute($misc_sql);
        while ($row = $misc_res->FetchRow()){
            if ($misc){
                $misc .= '<br>' .$row['description'];
            }else{
                $misc = $row['description'];
            }
        }
        return $misc;
    }

    public function getFinalDiagnosis() {
        global $db;
        $final = "SELECT 
                        ced.`code`, 
                        (CASE WHEN sed.description IS NOT NULL AND sed.`description` != '' THEN sed.description ELSE cie.description END) description, 
                        ced.`type_nr`  
                      FROM (care_encounter_diagnosis ced INNER JOIN care_icd10_en cie ON ced.code = cie.`diagnosis_code`)
                      LEFT JOIN seg_encounter_diagnosis sed ON ced.`encounter_nr` = sed.`encounter_nr` 
                           AND ced.code = sed.code
                      WHERE ced.encounter_nr = '$this->enc_no' 
                        AND ced.type_nr = 1 
                        AND ced.`status` != 'deleted'
                      ORDER BY sed.create_time DESC 
                      LIMIT 1";
        if ($row=$db->Execute($final)) {
            return $row->FetchRow();
        } else {
            return false;
        }
    }
    public function getOtherDiagnosis($code)
    {
        global $db;
        $other_sql = "SELECT 
                          description 
                        FROM
                          seg_encounter_diagnosis 
                        WHERE encounter_nr = '$this->enc_no' 
                          AND code != '$code'
                          AND is_deleted = 0 ";
        if ($result=$db->Execute($other_sql)) {
            return $result;
        } else {
            return false;
        }
    }

    public function forHeader()
    {
        $rowindex = 1;
        $data[$rowindex] = array(
            'for_header' => $rowindex
        );
        return $data;
    }
}
