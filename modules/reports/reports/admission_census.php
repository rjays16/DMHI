<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require_once('./roots.php');
    require_once($root_path.'include/inc_environment_global.php');
    
    include('parameters.php');
    define('PHIC',18);
    #TITLE of the report
    $params->put("hosp_name", mb_strtoupper($hosp_name));
    $params->put("header", $report_title);
    $params->put("department", "");
    $params->put("image_path", $image_path);
    $params->put("hosp_add", $hosp_addr1);
    $params->put("from", $from_date_format);
    $params->put("to", $to_date_format);
    $patient_type = '\'3\',\'4\'';
    
    $sql = "SELECT 
              ce.`encounter_nr`,
              cd.`name_formal`,
              ce.`current_dept_nr`,
              COUNT(ce.`current_dept_nr`) AS total_dept 
            FROM
              care_encounter AS ce 
              INNER JOIN care_department AS cd 
                ON ce.`current_dept_nr` = cd.`nr` 
            WHERE ce.encounter_type IN ($patient_type)
            AND DATE(ce.admission_dt) BETWEEN ".$db->qstr($from_date_format)." AND ".$db->qstr($to_date_format)."
            AND ce.STATUS NOT IN ('deleted','hidden','inactive','void')
            GROUP BY ce.`current_dept_nr` ORDER BY total_dept DESC  ";

    
           
    // echo $sql; 
    // exit();
    $rs = $db->Execute($sql);
    $rs_temp = $db->Execute($sql);

    $rowindex = 0;
    $total_phic = 0;
    $total_non_phic = 0;
    $g_total_temp = 0;
    $g_total = 0;
    $data = array();
    if (is_object($rs)){
        while($row=$rs->FetchRow()){
          $phic_count = 0;
          $non_phic_count = 0;

          while($row_temp=$rs_temp->FetchRow()){
            $g_total_temp += $row_temp['total_dept'];
          }

          $encs = $db->getAll("SELECT encounter_nr FROM care_encounter
                  WHERE encounter_type IN ($patient_type)
                  AND DATE(admission_dt) BETWEEN ".$db->qstr($from_date_format)."
                  AND ".$db->qstr($to_date_format)." AND current_dept_nr = ".$db->qstr($row['current_dept_nr'])."
                  AND STATUS NOT IN ('deleted','hidden','inactive','void') ");

          foreach ($encs as $val_enc) {
            
              $phic = $db->getOne("SELECT hcare_id FROM seg_encounter_insurance WHERE encounter_nr = ".$db->qstr($val_enc['encounter_nr'])."
                                  AND hcare_id = ".$db->qstr(PHIC)." ");
              if($phic){
                $phic_count += 1;
              }else{
                $non_phic_count += 1;
              }
          }

          $total_phic += $phic_count;
          $total_non_phic += $non_phic_count;
          $g_total += $row['total_dept'];
          $data[$rowindex] = array('name_formal' => $row['name_formal'] ,
                                      'total_dept' => $row['total_dept'],
                                      'phic' => $phic_count,
                                      'non_phic' => $non_phic_count,
                                      'percentage' => number_format(($row['total_dept']/$g_total_temp)*100, 2, '.', ',')." %",
                                       );

          $rowindex++;
        }
        $params->put("total_phic", (string)$total_phic );
        $params->put("total_non_phic", (string)$total_non_phic );
        $params->put("g_total", (string)$g_total );
        $params->put("total_percentage", (string)number_format(($g_total_temp/$g_total_temp)*100, 2, '.', ',')." %" );
        // echo $total_non_phic;exit();

    }else{
        $data[0]['name_formal'] = NULL;  
    }       