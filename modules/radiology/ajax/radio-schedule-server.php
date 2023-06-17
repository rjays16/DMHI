<?php
require('./roots.php');
require($root_path.'include/inc_environment_global.php');
require($root_path.'modules/radiology/ajax/radio-schedule-common.php');



require_once($root_path.'include/care_api_classes/class_paginator.php');
require_once($root_path.'include/care_api_classes/class_globalconfig.php');
include_once($root_path.'include/inc_date_format_functions.php');

include_once($root_path.'include/care_api_classes/class_department.php');
#$dept_obj=new Department;
include_once($root_path.'include/care_api_classes/class_personell.php');
#$pers_obj=new Personell;
require_once($root_path.'include/care_api_classes/class_radiology.php');
#$objService = new SegRadio;

require_once($root_path.'include/care_api_classes/class_tabview.php');
require($root_path.'include/care_api_classes/class_discount.php');

#added by VAN 06-03-2013
require_once($root_path.'include/care_api_classes/class_encounter.php');

#added by ken 10/10/2014 for requiring class curl
require_once($root_path.'include/care_api_classes/curl/class_curl.php');


//added by daryl
        //sort by time
function compare_time($a, $b) {
    if ($a['request_time'] == $b['request_time']) return 0;
    return ($a['request_time'] > $b['request_time']) ? -1 : 1;
}
//end by daryl

#-------added by VAN 03-26-08
function populateScheduledList($sElem, $tbId, $searchkey,$page){
    global $db;
        $glob_obj = new GlobalConfig($GLOBAL_CONFIG);
        $glob_obj->getConfig('pagin_patient_search_max_block_rows');
        $maxRows = $GLOBAL_CONFIG['pagin_patient_search_max_block_rows'];

        $objResponse = new xajaxResponse();
        $srv = new SegRadio();
        $enc_obj=new Encounter;
        
        $offset = $page * $maxRows;

        $searchkey = utf8_decode($searchkey);

        if ($searchkey==NULL)
          $searchkey = 'now';

        #get dept
        $sub_dept_nr = substr($tbId,4);
#added by daryl
            $ergebnis=$srv->SearchSelect($searchkey, $sub_dept_nr,$maxRows,$offset);
            $total_x = $srv->FoundRows();
            $ergebnis_walk=$srv->SearchSelect_walkin($searchkey, $sub_dept_nr,$maxRows,$offset);
            $total_y = $srv->FoundRows();
            $total = $total_x+$total_y;
        
        $lastPage = floor($total/$maxRows);
       
        if ((floor($total%10))==0)
            $lastPage = $lastPage-1;

        if ($page > $lastPage) $page=$lastPage;
        $rows=0;

        $objResponse->addScriptCall("setPagination",$page,$lastPage,$maxRows,$total);
        $objResponse->addScriptCall("clearList",$tbId);
        $rows = array();
        $all_results = array();
        if(($ergebnis) || ($ergebnis_walk)) {
        if ($ergebnis) {
            while($result=$ergebnis->FetchRow()) {
                    $all_results[] = $result;
                    $rows[]=$ergebnis->RecordCount();
            }
        }
            if ($ergebnis_walk){
                while($results = $ergebnis_walk->FetchRow())
                {
                    $all_results[] = $results;
                    $rows[]=$ergebnis_walk->RecordCount();
                }
            }
            usort($all_results, 'compare_time');   

            foreach ($all_results as $result) {
                # code...
                if ($result["pid"]!=" ")
                    $name = trim($result["name_first"])." ".trim($result["name_middle"])." ".trim($result["name_last"])." ".trim($result["suffix"]);
                else
                    $name = trim($result["ordername"]);

                if (!empty($result['modify_id'])){
                    $scheduled_by = trim($result['modify_id']);
                }else{
                    $scheduled_by = trim($result['create_id']);
                }

                #added by VAN 06-17-08
                #$sked_time = date("h:i A",strtotime(trim($result["scheduled_time"])));

                #added by VAN 07-08-08
                if (trim($result["scheduled_dt"]))
                    $sked_date = date("m/d/Y",strtotime(trim($result["scheduled_dt"])));
                else
                    #$sked_date = date("m/d/Y");
                    $sked_date = date("m/d/Y",strtotime(trim($result["request_date"])));

                if (trim($result["scheduled_time"]))
                    $sked_time = date("h:i A",strtotime(trim($result["scheduled_time"])));
                else
                    #$sked_time = date("h:i A");
                    $sked_time = date("h:i A",strtotime(trim($result["request_time"])));

                if (empty($scheduled_by)){
                    if (!empty($result['encoder'])){
                        $scheduled_by = trim($result['encoder']);
                    }else{
                        $scheduled_by = trim($result['encoder2']);
                    }
                }
                #-----------------

                #$objResponse->addAlert("type = ".$result['encounter_type']);

                if ($result['encounter_type']==1)
                    $pat_type = "ERPx";
                elseif ($result['encounter_type']==2)
                    $pat_type = "OPDPx";
                elseif (($result['encounter_type']==3)||($result['encounter_type']==4))
                    $pat_type = "INPx";
                #--------------------
                #$objResponse->addAlert("type = ".$result["batchnum"]);
                #$objResponse->addAlert("refno, name, code, sked_date = ".trim($result["batch_nr"]).", ".$name.", ".trim($result["service_code"]).", ".trim($result["scheduled_dt"]).", ".trim($result["scheduled_time"]));
                #refnum
                #$objResponse->addScriptCall("addPerson","RequestList",trim($result["batch_nr"]),$name,trim($result["service_code"]),trim($result["serv_name"]),trim($result["scheduled_dt"]),$sked_time,trim($result["name_formal"]),trim($result["rid"]),$scheduled_by, trim($result["skstatus"]),trim($result["dept_short_name"]),$pat_type);
                $disabled_icon = 0;
                if (($result["is_cash"]==1) && ($result["hasPaid"]==0))
                    $disabled_icon = 1;

                #get encounter info
                $bill = (object) 'bill';
                $billinfo = $enc_obj->hasSavedBilling($result['encounter_nr']);
                if ($billinfo){
                    $bill->bill_nr = $billinfo['bill_nr'];
                    $bill->hasfinal_bill = $billinfo['is_final'];
                    $bill->is_maygohome = $result['is_maygohome'];
                    $bill->is_cash = $result['is_cash'];
                }    
                    

                //added by daryl
                    $row['is_cash'] = $result['is_cash'];
                    $row["refno"]   = $result["refno"];
                    $row['pid']    = $result["pid"];
                            // $objResponse->alert($row["is_cash"]);

                     if (($result['is_cash']==0)&&(!$result['charge_name'])){
                   
                            $or_no="Charge";
                            $paid = 0;
                     }else{
                                $sql = "SELECT c.charge_name, d.*
                                                    FROM care_test_request_radio AS d
                                                    LEFT JOIN seg_type_charge AS c ON c.id=d.request_flag
                                                    WHERE refno='".trim($row["refno"])."'
                                                    AND status NOT IN ('deleted','hidden','inactive','void')
                                                    AND request_flag IS NOT NULL ORDER BY ordering LIMIT 1";
                            // $objResponse->alert($sql);

                                 $res=$db->Execute($sql);
                                 $rows=$res->RecordCount();
                                 $result_paid = $res->FetchRow();
                                 $or_no = '';

                                 if ($rows==0){
                                        $paid = 0;
                                 }else{
                                         if ($row["is_cash"]==1)
                                         $paid = 1;
                                         else
                                             $paid = 0;

                                         if ($result_paid["request_flag"]=='paid'){
                                                $sql_paid = "SELECT pr.or_no, pr.ref_no,pr.service_code
                                                                                    FROM seg_pay_request AS pr
                                                                                    INNER JOIN seg_pay AS p ON p.or_no=pr.or_no AND p.pid='".$row['pid']."'
                                                                                    WHERE pr.ref_source = 'RD' AND pr.ref_no = '".trim($row["refno"])."'
                                                                                    AND (ISNULL(p.cancel_date) OR p.cancel_date='0000-00-00 00:00:00') LIMIT 1";
                                                        $rs_paid = $db->Execute($sql_paid);
                                                        if ($rs_paid){
                                                                $result2 = $rs_paid->FetchRow();
                                                                $or_no = $result2['or_no'];
                                                        }
                                                        #added by VAN 06-03-2011
                                                        #for temp workaround
                                                        if (!$or_no){
                                                            $sql_manual = "SELECT * FROM seg_payment_workaround WHERE service_area='RD' AND refno='".trim($row["refno"])."' AND is_deleted=0";
                                                            $res_manual=$db->Execute($sql_manual);
                                                            $row_manual_count=$res_manual->RecordCount();
                                                            $row_manual = $res_manual->FetchRow();
            
                                                            $or_no = $row_manual['control_no'];
            
                                                        }
            
                                         }elseif ($result_paid["request_flag"]=='charity'){
                                                $sql_paid = "SELECT pr.grant_no AS or_no, pr.ref_no,pr.service_code
                                                                                    FROM seg_granted_request AS pr
                                                                                    WHERE pr.ref_source = 'RD' AND pr.ref_no = '".trim($row["refno"])."'
                                                                                    LIMIT 1";
                                                $rs_paid = $db->Execute($sql_paid);
                                                if ($rs_paid){
                                                        $result2 = $rs_paid->FetchRow();
                                                        $or_no = 'CLASS D';
                                                }
                                         }elseif (($result_paid["request_flag"]!=NULL)||($result_paid["request_flag"]!="")){
                                             if ($withOR)
                                                    $or_no = $off_rec;
            else    
                                                    $or_no = $result_paid["charge_name"];
                                         }
                                }
                     }
                //ended by daryl

                $objResponse->addScriptCall("addPerson",$tbId, trim($result["refnum"]),$result["batchnum"],ucwords(strtolower($name)),trim($result["service_code"]),trim($result["serv_name"]),$sked_date,$sked_time,trim($result["name_formal"]),trim($result["rid"]),$scheduled_by, trim($result["skstatus"]),trim($result["dept_short_name"]),$pat_type, $result["is_served"], $disabled_icon, $bill, $or_no);
            }
        }
        // if (!$rows) $objResponse->addScriptCall("addPerson",$tbId,NULL);
        if ($sElem) {
            $objResponse->addScriptCall("endAJAXSearch",$sElem);
        }

        return $objResponse;
}

function deleteScheduledRadioRequest($refno){
        global $db;
        $srv = new SegRadio;
        $objResponse = new xajaxResponse();

        if ($srv->deleteRadioSchedule($refno)) {
            $objResponse->addScriptCall("removeSkedRequest",$refno);
            $objResponse->addAlert("The scheduled request is successfully deleted.");
        }else{
            $objResponse->addAlert("The scheduled request is failed deleted.");
        }
        #$objResponse->addAlert("sql = ".$srv->sql);
        return $objResponse;
    }

#--------------------------------------

#added by VAN 08-14-2012
function savedServedPatient($batch_nr, $refno, $service_code, $is_served, $rad_tech=0, $served_date='', $served_time=''){
    global $db, $HTTP_SESSION_VARS;

    $objResponse = new xajaxResponse();
    $srv = new SegRadio;
    $curl_obj = new Rest_Curl;

    if ($is_served){
        #$date_served = date("Y-m-d H:i:s");
        $date = $served_date.' '.$served_time;
        $date_served = date("Y-m-d H:i:s", strtotime($date));
        $rad_tech  = $rad_tech;
    }else{
        $date_served = '0000-00-00 00:00:00';
        $rad_tech = 0;
    }

    $save = $srv->ServedRadioRequest($batch_nr, $refno, $service_code, $is_served, $date_served, $rad_tech);
    #$objResponse->alert("sql = ".$srv->sql);

    if ($save){
    	#added by ken 10/10/2014 for sending the data in FIS from serving the radiology request
        //if(ENABLE_FIS){
           // $curl_obj->inpatientRadioItem($refno);
       // }

        if ($is_served)
            $objResponse->addScriptCall("closeWindow");
        else
            $objResponse->addScriptCall("ReloadWindow");
    }

    return $objResponse;

}

$xajax->processRequests();
?>