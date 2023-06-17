<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require_once('./roots.php');
    require_once($root_path.'include/inc_environment_global.php');
    
    include('parameters.php');

    define('EMERGENCY_DEPT_NR',228);

    #TITLE of the report
    $params->put("hospital_name", mb_strtoupper($hosp_name));
    $params->put("header", $report_title);
    $params->put("department", '');
    $params->put("image_path", $image_path);
    
    $patient_type = '2';
    $patient_type_emergncy = '1';
    $patient_type_emergncy_admitted = '3';
    
     $sql_view_cases = "INSERT INTO seg_report_cases_census
                            SELECT e.pid, COUNT(*) AS no_encounters
                            FROM care_encounter e
                            WHERE e.STATUS NOT IN ('deleted','hidden','inactive','void')
                            AND e.encounter_type IN ($patient_type) 
                            AND DATE(e.encounter_date) BETWEEN ".$db->qstr($from_date_format)." AND ".$db->qstr($to_date_format)." 
                            GROUP BY e.pid"; 
                        
     $ok_cases = $db->Execute("TRUNCATE seg_report_cases_census");                
     if ($ok_cases)
        $ok_cases = $db->Execute($sql_view_cases);
        
    #no of patients
    $sql_no_patient = "SELECT COUNT(*) AS no_patients FROM seg_report_cases_census";                          
    $no_patients = $db->GetOne($sql_no_patient);
    
    #no of patients admitted from opd
    $sql_no_patient_ipdopd = "SELECT COUNT(*) AS no_patient_ipdopd
                                FROM care_person p
                                INNER JOIN care_encounter e ON e.pid=p.pid
                                LEFT JOIN care_department AS d 
                                ON d.nr=IF(e.current_dept_nr,e.current_dept_nr,e.consulting_dept_nr)
                                LEFT JOIN seg_report_cases_census c ON c.pid=e.pid
                                WHERE e.STATUS NOT IN ('deleted','hidden','inactive','void')
                                AND e.encounter_type IN (4) 
                                AND DATE(e.encounter_date) BETWEEN ".$db->qstr($from_date_format)." AND ".$db->qstr($to_date_format);
    $no_patient_ipdopd = $db->GetOne($sql_no_patient_ipdopd);                            
    
    $sql_no_weekdays = "SELECT 5 * (DATEDIFF(".$db->qstr($to_date_format).", ".$db->qstr($from_date_format).") DIV 7) + 
                        MID('0123444401233334012222340111123400012345001234550', 7 * WEEKDAY(".$db->qstr($from_date_format).") + WEEKDAY(".$db->qstr($to_date_format).") + 1, 1) AS no_weekdays";                                
    $no_weekdays = $db->GetOne($sql_no_weekdays);
    
    $sql = "SELECT  d.name_formal AS Type_Of_Service, 
            SUM(CASE WHEN c.no_encounters <= 1 THEN 1 ELSE 0 END) AS new_patient,
            SUM(CASE WHEN c.no_encounters > 1 THEN 1 ELSE 0 END) AS revisit            
            FROM care_encounter e
            INNER JOIN care_person p ON p.pid=e.pid
            LEFT JOIN care_department AS d 
              ON d.nr=IF(e.current_dept_nr,e.current_dept_nr,e.consulting_dept_nr)
            LEFT JOIN seg_report_cases_census c ON c.pid=e.pid
            WHERE e.STATUS NOT IN ('deleted','hidden','inactive','void')
            AND e.encounter_type IN ($patient_type) 
            AND DATE(e.encounter_date) BETWEEN ".$db->qstr($from_date_format)." AND ".$db->qstr($to_date_format)." 
            GROUP BY d.name_formal
            ORDER BY d.name_formal";
    
    #echo $sql; 
    #exit();
    $rs = $db->Execute($sql);
    
    $rowindex = 0;
    $data = array();
    if (is_object($rs)){
        while($row=$rs->FetchRow()){
            
            $total = (int) $row['new_patient'] + (int) $row['revisit'];
            $grand_total += $total;
            $data[$rowindex] = array('rowindex' => $rowindex+1,
                          'Type_Of_Service' => $row['Type_Of_Service'], 
                          'new' => (int) $row['new_patient'],
                          'revisit' => (int) $row['revisit'],
                          'total' => (int) $total,
                          );
            
           
            $rowindex++;
        }  
          #$grand_total = (int) $grand_total;
          $params->put("grand_total", (int) $grand_total);
          $params->put("no_weekdays", (int) $no_weekdays);
          $params->put("no_holidays", (int) $no_holidays);
          $params->put("no_patients", (int) $no_patients);
          $params->put("no_patient_ipdopd", (int) $no_patient_ipdopd);
    }else{
        $data[0]['id'] = NULL; 
    }  



    $opd_patient = "SELECT count(e.pid) AS opd_patient  
                        FROM care_department AS d
                        INNER JOIN care_encounter AS e ON IF(e.current_dept_nr,e.current_dept_nr,e.consulting_dept_nr)=d.nr
                        LEFT JOIN seg_encounter_result AS sr ON sr.encounter_nr = e.encounter_nr
                        LEFT JOIN seg_encounter_disposition AS sd ON sd.encounter_nr = e.encounter_nr
                        INNER JOIN care_person AS p ON p.pid = e.pid 
                        WHERE e.STATUS NOT IN ('deleted','hidden','inactive','void')
                        AND e.discharge_date IS NOT NULL
                        AND e.encounter_type IN ($patient_type)
                        AND DATE(e.discharge_date) BETWEEN ".$db->qstr($from_date_format)." AND ".$db->qstr($to_date_format)."
                        GROUP BY e.pid
                        ";

    $pedia = "SELECT sum(CASE WHEN $age_bdate <= 13) THEN 1 ELSE 0 END) AS pedia_opd  
                        FROM care_department AS d
                        INNER JOIN care_encounter AS e ON IF(e.current_dept_nr,e.current_dept_nr,e.consulting_dept_nr)=d.nr
                        LEFT JOIN seg_encounter_result AS sr ON sr.encounter_nr = e.encounter_nr
                        LEFT JOIN seg_encounter_disposition AS sd ON sd.encounter_nr = e.encounter_nr
                        INNER JOIN care_person AS p ON p.pid = e.pid 
                        WHERE e.STATUS NOT IN ('deleted','hidden','inactive','void')
                        AND e.discharge_date IS NOT NULL
                        AND e.encounter_type IN ($patient_type)
                        AND DATE(e.discharge_date) BETWEEN ".$db->qstr($from_date_format)." AND ".$db->qstr($to_date_format)."
                       
                        ";

    $adult = "SELECT sum(CASE WHEN $age_bdate >= 14) THEN 1 ELSE 0 END) AS adult_opd  
                        FROM care_department AS d
                        INNER JOIN care_encounter AS e ON IF(e.current_dept_nr,e.current_dept_nr,e.consulting_dept_nr)=d.nr
                        LEFT JOIN seg_encounter_result AS sr ON sr.encounter_nr = e.encounter_nr
                        LEFT JOIN seg_encounter_disposition AS sd ON sd.encounter_nr = e.encounter_nr
                        INNER JOIN care_person AS p ON p.pid = e.pid 
                        WHERE e.STATUS NOT IN ('deleted','hidden','inactive','void')
                        AND e.discharge_date IS NOT NULL
                        AND e.encounter_type IN ($patient_type)
                        AND DATE(e.discharge_date) BETWEEN ".$db->qstr($from_date_format)." AND ".$db->qstr($to_date_format)."
                       
                        ";


    $medical = "SELECT sum(CASE WHEN d.nr=241 OR d.nr=212 THEN 1 ELSE 0 END) AS medical
                        FROM care_department AS d
                        INNER JOIN care_encounter AS e ON IF(e.current_dept_nr,e.current_dept_nr,e.consulting_dept_nr)=d.nr
                        LEFT JOIN seg_encounter_result AS sr ON sr.encounter_nr = e.encounter_nr
                        LEFT JOIN seg_encounter_disposition AS sd ON sd.encounter_nr = e.encounter_nr
                        INNER JOIN care_person AS p ON p.pid = e.pid 
                        WHERE e.STATUS NOT IN ('deleted','hidden','inactive','void')
                        AND e.discharge_date IS NOT NULL
                        AND e.encounter_type IN ($patient_type)
                        AND DATE(e.discharge_date) BETWEEN ".$db->qstr($from_date_format)." AND ".$db->qstr($to_date_format)."
                       
                        ";



     $surgical_opd = "SELECT
                        count(e.pid) AS surgical_opd
                        FROM care_encounter_procedure AS ed 
                        INNER JOIN care_encounter AS e ON  e.encounter_nr=ed.encounter_nr
                        LEFT JOIN seg_encounter_result AS sr ON sr.encounter_nr = e.encounter_nr
                        LEFT JOIN seg_encounter_disposition AS sd ON sd.encounter_nr = e.encounter_nr
                        INNER JOIN care_person AS p ON p.pid = e.pid 
                        WHERE e.STATUS NOT IN ('deleted','hidden','inactive','void')
                        AND e.discharge_date IS NOT NULL
                        AND e.encounter_type IN ($patient_type)
                        AND DATE(e.discharge_date) BETWEEN ".$db->qstr($from_date_format)." AND ".$db->qstr($to_date_format)." 
                        ";


    $non_surgical_opd = "SELECT
                        count(e.pid) AS non_surgical_opd
                        FROM care_encounter_diagnosis AS ed 
                        INNER JOIN care_encounter AS e ON  e.encounter_nr=ed.encounter_nr
                        LEFT JOIN seg_encounter_result AS sr ON sr.encounter_nr = e.encounter_nr
                        LEFT JOIN seg_encounter_disposition AS sd ON sd.encounter_nr = e.encounter_nr
                        INNER JOIN care_person AS p ON p.pid = e.pid 
                        WHERE e.STATUS NOT IN ('deleted','hidden','inactive','void')
                        AND e.discharge_date IS NOT NULL
                        AND e.encounter_type IN ($patient_type)
                        AND DATE(e.discharge_date) BETWEEN ".$db->qstr($from_date_format)." AND ".$db->qstr($to_date_format)." ";


    $antenatal = "SELECT
                        count(e.pid) AS antenatal
                        FROM care_encounter_diagnosis AS ed
                        INNER JOIN care_icd10_en AS c ON c.diagnosis_code = ed.code  
                        INNER JOIN care_encounter AS e ON  e.encounter_nr=ed.encounter_nr
                        LEFT JOIN seg_encounter_result AS sr ON sr.encounter_nr = e.encounter_nr
                        LEFT JOIN seg_encounter_disposition AS sd ON sd.encounter_nr = e.encounter_nr
                        INNER JOIN care_person AS p ON p.pid = e.pid 
                        WHERE e.STATUS NOT IN ('deleted','hidden','inactive','void')
                        AND e.discharge_date IS NOT NULL
                        AND e.encounter_type IN ($patient_type)
                        AND c.`description` LIKE '%antenatal%'
                        AND DATE(e.discharge_date) BETWEEN ".$db->qstr($from_date_format)." AND ".$db->qstr($to_date_format)." 
                        ";


    $antenatal = "SELECT
                        count(e.pid) AS antenatal
                        FROM care_encounter_diagnosis AS ed
                        INNER JOIN care_icd10_en AS c ON c.diagnosis_code = ed.code  
                        INNER JOIN care_encounter AS e ON  e.encounter_nr=ed.encounter_nr
                        LEFT JOIN seg_encounter_result AS sr ON sr.encounter_nr = e.encounter_nr
                        LEFT JOIN seg_encounter_disposition AS sd ON sd.encounter_nr = e.encounter_nr
                        INNER JOIN care_person AS p ON p.pid = e.pid 
                        WHERE e.STATUS NOT IN ('deleted','hidden','inactive','void')
                        AND e.discharge_date IS NOT NULL
                        AND e.encounter_type IN ($patient_type)
                        AND c.`description` LIKE '%antenatal%'
                        AND DATE(e.discharge_date) BETWEEN ".$db->qstr($from_date_format)." AND ".$db->qstr($to_date_format)." 
                        ";


    $total_number_of_emergency_visit = "SELECT
                                        COUNT(e.encounter_nr) AS emrgcy_vst
                                        FROM care_encounter as e
                                        INNER JOIN care_person AS cp ON cp.pid = e.pid
                                        WHERE e.STATUS NOT IN ('deleted','hidden','inactive','void')
                                        AND e.discharge_date IS NOT NULL
                                        AND e.encounter_type IN ($patient_type_emergncy)
                                        AND DATE(e.discharge_date) BETWEEN ".$db->qstr($from_date_format)." AND ".$db->qstr($to_date_format)." 
                                        ";

    $total_number_of_emergency_visit_pedia = "SELECT
                                        sum(CASE WHEN $age_bdate <= 13) THEN 1 ELSE 0 END) AS pedia_emrgncy  
                                        FROM care_encounter as e
                                        INNER JOIN care_person AS p ON p.pid = e.pid
                                        WHERE e.STATUS NOT IN ('deleted','hidden','inactive','void')
                                        AND e.discharge_date IS NOT NULL
                                        AND e.encounter_type IN ($patient_type_emergncy)
                                        AND DATE(e.discharge_date) BETWEEN ".$db->qstr($from_date_format)." AND ".$db->qstr($to_date_format)." 
                                        ";

    $total_number_of_emergency_visit_adult = "SELECT
                                        sum(CASE WHEN $age_bdate >= 14) THEN 1 ELSE 0 END) AS adult_emrgncy  
                                        FROM care_encounter as e
                                        INNER JOIN care_person AS p ON p.pid = e.pid
                                        WHERE e.STATUS NOT IN ('deleted','hidden','inactive','void')
                                        AND e.discharge_date IS NOT NULL
                                        AND e.encounter_type IN ($patient_type_emergncy)
                                        AND DATE(e.discharge_date) BETWEEN ".$db->qstr($from_date_format)." AND ".$db->qstr($to_date_format)." 
                                        ";


    $total_number_of_emergency_visit_admitted = "SELECT
                                        COUNT(e.`encounter_nr`) AS total_emgnc_admitted
                                        FROM care_encounter as e
                                        INNER JOIN care_person AS p ON p.pid = e.pid
                                        WHERE e.STATUS NOT IN ('deleted','hidden','inactive','void')
                                        AND e.discharge_date IS NOT NULL
                                        AND e.encounter_type IN ($patient_type_emergncy_admitted)
                                        AND e.encounter_class_nr = 1 AND e.encounter_status = ''
                                        AND DATE(e.discharge_date) BETWEEN ".$db->qstr($from_date_format)." AND ".$db->qstr($to_date_format)." 
                                        ";


    $total_number_radio_serv = "SELECT 
                                        COUNT(e.`encounter_nr`) AS radio_serv
                                        FROM care_encounter AS e 
                                        INNER JOIN care_person AS p ON p.pid = e.pid 
                                        LEFT JOIN seg_radio_serv AS srv ON e.`encounter_nr` = srv.`encounter_nr`
                                        LEFT JOIN care_test_request_radio AS ctr ON srv.`refno` = ctr.`refno`
                                        WHERE e.STATUS NOT IN ('deleted','hidden','inactive','void') 
                                        AND e.discharge_date IS NOT NULL 
                                        AND ctr.`is_served` = 1
                                        AND DATE(e.discharge_date) BETWEEN ".$db->qstr($from_date_format)." AND ".$db->qstr($to_date_format)." 
                                        ";

    $total_number_lab_serv = "SELECT 
                                        COUNT(e.`encounter_nr`) AS lab_serv
                                        FROM care_encounter AS e 
                                        INNER JOIN care_person AS p ON p.pid = e.pid 
                                        LEFT JOIN seg_lab_serv AS lsrv ON e.`encounter_nr` = lsrv.`encounter_nr`
                                        LEFT JOIN seg_lab_servdetails AS lsrvd ON lsrv.`refno` = lsrvd.`refno`
                                        WHERE e.STATUS NOT IN ('deleted','hidden','inactive','void') 
                                        AND e.discharge_date IS NOT NULL 
                                        AND lsrvd.`is_served` = 1
                                        AND DATE(e.discharge_date) BETWEEN ".$db->qstr($from_date_format)." AND ".$db->qstr($to_date_format)." 
                                        ";

    $total_number_confirm_dengue = "SELECT COUNT(e.`encounter_nr`) AS total_c_dengue
                                        FROM care_encounter_diagnosis AS ed 
                                        INNER JOIN care_icd10_en AS c 
                                          ON c.diagnosis_code = ed.code 
                                        INNER JOIN care_encounter AS e 
                                          ON e.encounter_nr = ed.encounter_nr 
                                        INNER JOIN care_person AS p 
                                          ON p.pid = e.pid 
                                        LEFT JOIN seg_encounter_result AS sr 
                                          ON sr.encounter_nr = ed.encounter_nr 
                                        LEFT JOIN seg_encounter_disposition AS sd 
                                          ON sd.encounter_nr = ed.encounter_nr 
                                        WHERE e.STATUS NOT IN ('deleted','hidden','inactive','void')
                                        AND c.`description` LIKE '%dengue%'
                                        AND e.discharge_date IS NOT NULL 
                                        AND DATE(e.discharge_date) BETWEEN ".$db->qstr($from_date_format)." AND ".$db->qstr($to_date_format)." 
                                        ";


    $total_number_tb = "SELECT COUNT(e.`encounter_nr`) AS total_tb
                                        FROM care_encounter_diagnosis AS ed 
                                        INNER JOIN care_icd10_en AS c 
                                          ON c.diagnosis_code = ed.code 
                                        INNER JOIN care_encounter AS e 
                                          ON e.encounter_nr = ed.encounter_nr 
                                        INNER JOIN care_person AS p 
                                          ON p.pid = e.pid 
                                        LEFT JOIN seg_encounter_result AS sr 
                                          ON sr.encounter_nr = ed.encounter_nr 
                                        LEFT JOIN seg_encounter_disposition AS sd 
                                          ON sd.encounter_nr = ed.encounter_nr 
                                        WHERE e.STATUS NOT IN ('deleted','hidden','inactive','void')
                                        AND c.`description` LIKE '%tuberculosis%'
                                        AND e.discharge_date IS NOT NULL 
                                        AND DATE(e.discharge_date) BETWEEN ".$db->qstr($from_date_format)." AND ".$db->qstr($to_date_format)." 
                                        ";

    // echo $total_number_of_emergency_visit_admitted;exit();

    $total_new_patient = 0;
    $total_revisit = 0;
    $data = array();

    $rs = $db->Execute($opd_patient);
    
    $data[0]['for_output'] = 1;

    if (is_object($rs)){
        if ($rs->_numOfRows == 0) {
            $total_new_patient = 0;
            $total_revisit = 0;
        }else{
            while($row=$rs->FetchRow()){
                if ($row['opd_patient'] == 1 ) {
                    $total_new_patient = $total_new_patient+=$row['opd_patient'];
                }else{
                    $total_revisit = $total_revisit+=$row['opd_patient'];
                }
            }  
        }
    }

    //OPD
    $adult = $db->GetOne($adult);
    $pedia = $db->GetOne($pedia);
    $medical = $db->GetOne($medical);
    $surgical_opd = $db->GetOne($surgical_opd);
    $non_surgical_opd = $db->GetOne($non_surgical_opd);
    $antenatal = $db->GetOne($antenatal);
    //Emergency
    $total_number_of_emergency_visit = $db->GetOne($total_number_of_emergency_visit);
    $total_number_of_emergency_visit_pedia = $db->GetOne($total_number_of_emergency_visit_pedia);
    $total_number_of_emergency_visit_adult = $db->GetOne($total_number_of_emergency_visit_adult);
    $total_number_of_emergency_visit_admitted = $db->GetOne($total_number_of_emergency_visit_admitted);
    //RAD and LAB
    $total_number_radio_serv = $db->GetOne($total_number_radio_serv);
    $total_number_lab_serv = $db->GetOne($total_number_lab_serv);
    //DISEASES
    $total_number_confirm_dengue = $db->GetOne($total_number_confirm_dengue);
    $total_number_tb = $db->GetOne($total_number_tb);

    if (!$adult && !$pedia && !$medical) {
        $adult = 0;
        $pedia = 0;
        $medical = 0;
    }
    if(!$pedia){
        $pedia = 0;
    }
    if(!$adult){
        $adult = 0;
    }
    if(!$medical){
        $medical = 0;
    }
    if(!$surgical_opd){
        $surgical_opd = 0;
    }
    if(!$non_surgical_opd){
        $non_surgical_opd = 0;
    }
    if(!$antenatal){
        $antenatal = 0;
    }
    if(!$postnatal){
        $postnatal = 0;
    }

    // emergency
    if(!$total_number_of_emergency_visit){
        $total_number_of_emergency_visit = 0;
    }
    if(!$total_number_of_emergency_visit_pedia){
        $total_number_of_emergency_visit_pedia = 0;
    }
    if(!$total_number_of_emergency_visit_adult){
        $total_number_of_emergency_visit_adult = 0;
    }
    if(!$total_number_of_emergency_visit_admitted){
        $total_number_of_emergency_visit_admitted = 0;
    }

    // radio and lab
    if(!$total_number_radio_serv){
        $total_number_radio_serv = 0;
    }
    if(!$total_number_lab_serv){
        $total_number_lab_serv = 0;
    }

    //diseases 
    if(!$total_number_confirm_dengue){
        $total_number_confirm_dengue = 0;
    }
    if(!$total_number_tb){
        $total_number_tb = 0;
    }
    //OPD
    $params->put("total_new_patient",strval($total_new_patient));
    $params->put("total_revisit",strval($total_revisit));
    $params->put("adult",strval($adult));
    $params->put("pedia",strval($pedia));
    $params->put("medical",strval($medical));
    $params->put("surgical_opd",strval($surgical_opd));
    $params->put("non_surgical_opd",strval($non_surgical_opd));
    $params->put("antenatal",strval($antenatal));
    $params->put("postnatal",strval($postnatal));
    //EMERGENCY
    $params->put("total_number_of_emergency_visit",strval($total_number_of_emergency_visit));
    $params->put("total_number_of_emergency_visit_pedia",strval($total_number_of_emergency_visit_pedia));
    $params->put("total_number_of_emergency_visit_adult",strval($total_number_of_emergency_visit_adult));
    $params->put("total_number_of_emergency_visit_admitted",strval($total_number_of_emergency_visit_admitted));
    //RADIO AND LAB
    $params->put("total_number_radio_serv",strval($total_number_radio_serv));
    $params->put("total_number_lab_serv",strval($total_number_lab_serv));
    //DISEASES
    $params->put("outreach_home",strval(0));
    $params->put("immunization",strval(0));
    $params->put("total_number_confirm_dengue",strval($total_number_confirm_dengue));
    $params->put("total_number_tb",strval($total_number_tb));

