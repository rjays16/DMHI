<?php
require('./roots.php');
require_once($root_path.'include/inc_environment_global.php');
include_once($root_path.'include/inc_date_format_functions.php');
//require($root_path.'include/care_api_classes/class_department.php');
require_once($root_path.'include/care_api_classes/class_radiology.php');

require_once($root_path.'include/care_api_classes/class_paginator.php');
require_once($root_path.'include/care_api_classes/class_globalconfig.php');
//include radio-undone-request common
require_once($root_path.'modules/radiology/ajax/radio-undone-request.common.php');

# Default value for the maximum nr of rows per block displayed, define this to the value you wish
# In normal cases this value is derived from the db table "care_config_global" using the "pagin_insurance_list_max_block_rows" element.
define('MAX_BLOCK_ROWS',30);

function saveScheduledRequest($mode, $batch_nr='', $scheduled_date='', $scheduled_time='', $instructions='', $remarks='',
                                            $sub_dept_nr='', $pmonth='', $pday='', $pyear=''){
    global $root_path, $date_format;

    $objResponse = new xajaxResponse();

    $radio_obj=new SegRadio();
    $ok = true;
    if ($radio_obj->getScheduledRadioRequestInfo($batch_nr,true)){
        $objResponse->addScriptCall("msgPopUp","This request has been scheduled already.");
        $ok = false;
    }

    if ($ok){
        $scheduled_dt = @formatDate2STD($scheduled_date, $date_format);# reformat FROM mm/dd/yyyy TO yyyy-mm-dd
        #commented by VAN 03-26-08
        #$scheduled_dt .= " ".$scheduled_time;
        $serialized_instructions = serialize($instructions);
        $msg = "batch_nr = '".$batch_nr."' \n".
                "scheduled_date = '".$scheduled_date."' \n".
                "scheduled_time = '".$scheduled_time."' \n".
                "scheduled_dt = '".$scheduled_dt."' \n".
                "serialized_instructions = '".$serialized_instructions."' \n".
                "instructions = '".$instructions."' \n".
                "instructions : '".print_r($instructions,true)."' \n".
                "remarks = '".$remarks."' \n".
                " _SESSION['sess_temp_fullname'] = '".$_SESSION['sess_temp_fullname']."'";
#$objResponse->addAlert("radio-undone-request.server.php : saveScheduledRequest :: ".$msg);
        #added by VAN 03-26-08
        if (empty($_SESSION['sess_temp_fullname']))
            $encoder = $_SESSION['sess_user_name'];
        else
            $encoder = $_SESSION['sess_temp_fullname'];

        $ok = $radio_obj->createRadioSchedule($batch_nr, $scheduled_dt, $scheduled_time, $serialized_instructions, $remarks, $encoder);
        if ($ok){
            $objResponse->addScriptCall("resetForm"); # reset the form
            _PopulateRadioScheduledRequests($objResponse, $mode, $sub_dept_nr, $pmonth, $pday, $pyear);
            $objResponse->addScriptCall("msgPopUp","Successfully saved the schedule form.");
        }else{
            $objResponse->addScriptCall("msgPopUp","Failed to save the schedule form.");
        }
    }# end of 'if ($ok)'
    return $objResponse;
}#end of function saveScheduledRequest

function updateScheduledRequest($mode, $batch_nr='', $scheduled_date='', $service_date='', $scheduled_time='', $instructions='', $remarks='',
                                            $sub_dept_nr='', $pmonth='', $pday='', $pyear=''){
    global $root_path, $date_format;

    $objResponse = new xajaxResponse();
    $radio_obj=new SegRadio();

  $batch_walk_ = $radio_obj->selectwalkin_batch_nr($batch_nr);
   $batch_walk = $batch_walk_->Fetchrow();
   if (($batch_walk['ifwalk']) == 1){
    $walk = 1;
   }

    if ($radio_obj->getScheduledRadioRequestInfo($batch_nr,true,'','',$walk)){
        $ok = true;
    }else{
        $objResponse->addScriptCall("msgPopUp","Failed to update the schedule form. \n Close this window and try again.");
        $ok = false;
        $objResponse->addAlert($radio_obj->sql);
    }

    if ($ok){
        $msg = "";
        if($service_date){
            $service_dt = @formatDate2STD($service_date, $date_format);# reformat FROM mm/dd/yyyy TO yyyy-mm-dd
            if ($radio_obj->updateRadioRequestServiceDate($batch_nr, $service_dt)){
                $msg .= "Successfully updated the Date of Service. \n";
            }else{
                $msg .= "Failed to update the Date of Service. \n";
            }
        }
        $scheduled_dt = @formatDate2STD($scheduled_date, $date_format);# reformat FROM mm/dd/yyyy TO yyyy-mm-dd
        #commented by VAN 03-26-08
        #$scheduled_dt .= " ".$scheduled_time;
        $serialized_instructions = serialize($instructions);

        #added by VAN 03-26-08
        if (empty($_SESSION['sess_temp_fullname']))
            $encoder = $_SESSION['sess_user_name'];
        else
            $encoder = $_SESSION['sess_temp_fullname'];

        $ok = $radio_obj->updateRadioSchedule($batch_nr, $scheduled_dt, $scheduled_time, $service_dt, $serialized_instructions, $remarks, $encoder);

        if ($ok){
            $objResponse->addScriptCall("resetForm"); # reset the form
            _PopulateRadioScheduledRequests($objResponse, $mode, $sub_dept_nr, $pmonth, $pday, $pyear);
            $msg .= "Successfully updated the schedule form. \n";
        }else{
            $msg .= "Failed to update the schedule form. \n";
        }
        $objResponse->addScriptCall("msgPopUp",$msg);
    }# end of 'if ($ok)'
    return $objResponse;
}#end of function updateScheduledRequest


function deleteScheduledRadioRequest($mode, $batch_nr='',$sub_dept_nr='', $pmonth='', $pday='', $pyear=''){
    $objResponse = new xajaxResponse();
    $radio_obj = new SegRadio;

    if($radio_obj->deleteRadioSchedule($batch_nr)){
        _PopulateRadioScheduledRequests($objResponse, $mode, $sub_dept_nr, $pmonth, $pday, $pyear);
        $objResponse->addScriptCall("msgPopUp","Successfully deleted!");
    }else{
        $objResponse->addScriptCall("msgPopUp","Failed to delete!");
    }
    return $objResponse;
}# end of function deleteScheduledRadioRequest


function PopulateRadioScheduledRequests($mode, $sub_dept_nr, $pmonth, $pday, $pyear){
    $objResponse = new xajaxResponse();
#$objResponse->addAlert("radio-undone-request.server.php : PopulateRadioScheduledRequests :: mode = '".$mode."'");
    _PopulateRadioScheduledRequests($objResponse, $mode, $sub_dept_nr, $pmonth, $pday, $pyear);
    return $objResponse;
}#end of function PopulateRadioScheduledRequests

    /*
     * access private
    */
function _PopulateRadioScheduledRequests(&$objResponse, $mode, $sub_dept_nr, $pmonth, $pday, $pyear){
//  $objResponse = new xajaxResponse();
    $radio_obj = new SegRadio;

    $recordScheduledObj = $radio_obj->getScheduledRadioRequestInfo('','',$sub_dept_nr,date("m/d/Y",mktime(0, 0, 0, $pmonth, $pday, $pyear)));
    //$objResponse->alert($radio_obj->sql);
    $objResponse->addScriptCall("clearScheduledList",$mode);

    if (is_object($recordScheduledObj)){
        $myCount=1;
        while($scheduledHistory=$recordScheduledObj->FetchRow()){
            $batch_nr = $scheduledHistory['batch_nr'];
            $service_code = $scheduledHistory['service_code'];
            $rid = $scheduledHistory['rid'];
            $status = $scheduledHistory['status'];

                # FORMATTING of Scheduled Date
            $scheduled_dt = $scheduledHistory['scheduled_dt'];
            #if (($scheduled_dt!='0000-00-00 00:00:00')  && ($scheduled_dt!=""))
            if (($scheduled_dt!='0000-00-00')  && ($scheduled_dt!=""))
                #$scheduled_time = @formatDate2Local($scheduled_dt,$date_format,'',true); # return time ONLY
                $scheduled_time =  date("h:i A",strtotime($scheduledHistory['scheduled_time']));
            else
                $scheduled_time='';

            $patient_name=$scheduledHistory['name_last'].', '.$scheduledHistory['name_first'].' '.$scheduledHistory['suffix'];
            if (!empty($scheduledHistory['name_middle'])){
                #$patient_name .= ' <font style="font-style:italic; color:#FF0000">'.$scheduledHistory['name_middle'].'</font>';
                $patient_name .= ' '.$scheduledHistory['name_middle'];
            }

            if (!empty($scheduledHistory['modify_id'])){
                $scheduled_by = trim($scheduledHistory['modify_id']);
            }else{
                $scheduled_by = trim($scheduledHistory['create_id']);
            }

            $temp_instructions = unserialize($scheduledHistory['instructions']);
            $instructions='';
            if (is_array($temp_instructions) && !empty($temp_instructions)){
                foreach($temp_instructions as $value){
                    if (substr($value,0,1)=='0'){
                        $instructions .= substr($value,2)."<br>";
                    }else{
                        $instruction_info = $radio_obj->getRadioInstructionsInfo($sub_dept_nr,$value);
                        if($instruction_info){
                            $instructions .= $instruction_info['instruction']."<br>";
                        }
                    }
                }
            }
    #       $instructions="'".$instructions."'";
            $toolTipTextHandler = ' onMouseOver="return overlib($(\'toolTipText'.$myCount.'\').value, CAPTION,\'Instructions\',  '.
                                '  TEXTPADDING, 8, CAPTIONPADDING, 4, TEXTFONTCLASS, \'oltxt\', CAPTIONFONTCLASS, \'olcap\', '.
                                '  WIDTH, 250,FGCLASS,\'olfgjustify\',FGCOLOR, \'#bbddff\');" onmouseout="nd();"';

            $option_edit = '<img name="edit'.$myCount.'" id="edit'.$myCount.'" '.$editImg.'>';
            $option_delete ='<img name="delete'.$myCount.'" id="delete'.$myCount.'" '.$deleteImg.' onClick="deleteScheduledRequest('.$batch_nr.','.$myCount.');"> ';

            $objResponse->addScriptCall("jsAddScheduledRequest",$mode,$myCount,$instructions,$batch_nr,
                                $scheduled_time,$service_code,$rid, $patient_name,$scheduled_by,$status);

            $myCount++;
        }#end of while loop
    }else{
        #EMPTY
        $objResponse->addScriptCall("jsRadioNoFoundScheduledRequest",$mode);
    }
//  return $objResponse;
}#end of function _PopulateRadioScheduledRequests


function PopulateRadioUnscheduledRequest($sElem, $searchkey,$sub_dept_nr, $pgx, $thisfile, $rpath, $oitem, $odir ){
    global $root_path;
    $objResponse = new xajaxResponse();

#$objResponse->addAlert("radio-undone-request.server.php : PopulateRadioUndoneRequest :: sElem = '".$sElem."'");

    //Display table header
    ColHeaderRadioUnscheduledRequest($objResponse, $sub_dept_nr, $oitem, $odir);

    //Paginate & display list of radiology undone request
    PaginateRadioUndoneRequestList($objResponse, $searchkey, $sub_dept_nr, $pgx, $thisfile, $rpath, $odir, $oitem, 'unscheduled');
    if ($sElem) {
        $objResponse->addScriptCall("endAJAXSearch",$sElem);
    }
    return $objResponse;
}//end of PopulateRadioUnscheduledRequest

function ColHeaderRadioUnscheduledRequest(&$objResponse, $sub_dept_nr, $oitem, $odir){
#   $objResponse = new xajaxResponse();
    global $root_path;
    #$append = '&status='.$status.'&target='.$target.'&user_origin='.$user_origin.'&dept_nr'.$dept_nr;
    $class= 'adm_list_titlebar';

    $th  = "<thead><tr><th colspan=\"14\" id=\"mainHead".$sub_dept_nr."\">";
    $th .= "</th></tr></thead>";

    $thead  = "<thead><tr style='font:bold 11.5px Arial; color:#000000'>";
    $thead .= "<td width=\"2%\" class=\"".$class."\"><b>No.</b></td> \n";
    $thead .= makeSortLink('Ref. No.',$class,'batch_nr',$oitem,$odir,$sub_dept_nr,'8%');
    $thead .= makeSortLink('Batch No.',$class,'batch_nr',$oitem,$odir,$sub_dept_nr,'8%');
    $thead .= makeSortLink('Service Code',$class,'service_code',$oitem,$odir,$sub_dept_nr,'15%');
    $thead .= makeSortLink('Date Requested',$class,'request_date',$oitem,$odir,$sub_dept_nr,'8%','style="font-size:11px" nowrap="nowrap"');
    $thead .= makeSortLink('RID',$class,'rid',$oitem,$odir,$sub_dept_nr,'8%');
    $thead .= makeSortLink('Lastname',$class,'name_last',$oitem,$odir,$sub_dept_nr);
    $thead .= makeSortLink('Firstname',$class,'name_first',$oitem,$odir,$sub_dept_nr);
    $thead .= makeSortLink('Patient Type',$class,'encounter_type',$oitem,$odir,$sub_dept_nr,'8%');
    #$thead .= makeSortLink('Birthdate',$class,'date_birth',$oitem,$odir,$sub_dept_nr,'8%');
    $thead .= makeSortLink('Priority',$class,'is_urgent',$oitem,$odir,$sub_dept_nr,'5%');
    $thead .= "<td class=\"".$class."\" width=\"1%\" align=\"center\"></td>";
    $thead .= "</tr></thead> \n";

    $thead1 = $th.$thead;
    $tbodyHTML = "<tbody id='person-list-body'></tbody>";
#$objResponse->addAlert("thead1 : \n".$thead1." tbodyHTML : \n".$tbodyHTML." prevNextTR : \n".$prevNextTR);
    $objResponse->addAssign('person-list',"innerHTML", $thead1.$tbodyHTML);
#$objResponse->addAlert("ColHeaderRadioUnscheduledRequest ::");
#   return $objResponse;
}#end of function ColHeaderRadioUnscheduledRequest


function PopulateRadioUndoneRequest($tbId, $searchkey,$sub_dept_nr, $pgx, $thisfile, $rpath, $mode, $oitem, $odir ){
    global $root_path;
    $objResponse = new xajaxResponse();

#   $objResponse->addAlert("radio-undone-request.server.php : PopulateRadioUndoneRequest :: ");

    //Display table header
    ColHeaderRadioUndoneRequest($objResponse,$tbId, $searchkey,$sub_dept_nr,$pgx, $thisfile, $rpath,$mode,$oitem,$odir);

    //Paginate & display list of radiology undone request
    PaginateRadioUndoneRequestList($objResponse, $searchkey, $sub_dept_nr, $pgx, $thisfile, $rpath, $odir, $oitem);



    return $objResponse;
}//end of PopulateRadioUndoneRequest

#added by daryl sort time
function sorttime($a, $b) {
    if ($a['request_time'] == $b['request_time']) return 0;
    return ($a['request_time'] > $b['request_time']) ? -1 : 1;
}

#function PopulateRadioRequest(&$objResponse,$tbodyId,$searchkey,$sub_dept_nr,$pgx, $thisfile, $rpath,$odir='ASC',$oitem='create_dt'){
function PaginateRadioUndoneRequestList(&$objResponse,$searchkey,$sub_dept_nr,$pgx, $thisfile, $rpath,$odir='ASC',$oitem='create_dt',$search_type=''){
    global $date_format,  $db;
    $radio_obj=new SegRadio();

    #Initialize variables for search..
    #Instantiate paginator  //$_SESSION['sess_searchkey']
    $pagen=new Paginator($pgx,$thisfile,$searchkey,$rpath, $oitem, $odir);

    $glob_obj=new GlobalConfig($GLOBAL_CONFIG);
    #Get the max nr of rows from global config
    $glob_obj->getConfig('pagin_patient_search_max_block_rows');
    # Last resort, use the default defined at the start of this page
    if(empty($GLOBAL_CONFIG['pagin_patient_search_max_block_rows'])) $pagen->setMaxCount(MAX_BLOCK_ROWS);
        else $pagen->setMaxCount($GLOBAL_CONFIG['pagin_patient_search_max_block_rows']);

    #added by VAN 07-31-08
    $searchkey = utf8_decode($searchkey);
    $searchkey = str_replace("^","'",$searchkey);
    $searchkey=addslashes($searchkey);
    #-------------

#   if(($mode == 'search' || $mode == 'paginate')&&!empty($searchkey)){
        # Convert other wildcards
        $searchkey=strtr($searchkey,'*?','%_');
#   }


//  $encounter=& $radio_obj->searchLimitBasicInfoRadioPending($searchkey,$sub_dept_nr,$pagen->MaxCount(),$pgx,$oitem,$odir);
        #added by daryl
    $ergebnis = &$radio_obj->searchLimitBasicInfoRadioPending($search_type,$searchkey,$sub_dept_nr,$pagen->MaxCount(), $pgx, $oitem, $odir);
    $ergebnis_walk = &$radio_obj->searchBasicInfoRadioPending_walkin($search_type,$searchkey,$sub_dept_nr,$pagen->MaxCount(), $pgx, $oitem, $odir);
   // $objResponse->addAlert(&$radio_obj->sql);

    //count all records
    $linecount=$radio_obj->LastRecordCount();
    $pagen->setTotalBlockCount($linecount);

    if(isset($totalcount)&&$totalcount){
        $pagen->setTotalDataCount($totalcount);
    }else{
        @$radio_obj->_searchBasicInfoRadioPending($search_type,$searchkey,$sub_dept_nr);
        $totalcount=$radio_obj->LastRecordCount();
        $pagen->setTotalDataCount($totalcount);
    }
    $pagen->setSortItem($oitem);
    $pagen->setSortDirection($odir);

    $LDSearchFound = "The search found <font color=red><b>~nr~</b></font> relevant data.";
    if ($linecount)
        $textResult = '<hr width=80% align=left>'.str_replace("~nr~",$totalcount,$LDSearchFound).' Showing '.$pagen->BlockStartNr().' to '.$pagen->BlockEndNr().'.';
#       echo '<hr width=80% align=left>'.str_replace("~nr~",$totalcount,$LDSearchFound).' '.$LDShowing.' '.$pagen->BlockStartNr().' '.$LDTo.' '.$pagen->BlockEndNr().'.';
    else
        $textResult = '<hr width=80% align=left>'.str_replace('~nr~','0',$LDSearchFound);
#       echo str_replace('~nr~','0',$LDSearchFound);
    $objResponse->addAssign('textResult',"innerHTML", $textResult);

    $my_count=$pagen->BlockStartNr();
    #added by daryl
    $all_result = array();
    if (($ergebnis) || ($ergebnis_walk)){
            if ($ergebnis){
                while ($result = $ergebnis->FetchRow()){
                    $all_result[] = $result;
                }
            }
            if ($ergebnis_walk){
                while($results = $ergebnis_walk->FetchRow()){
                    $all_result[] = $results;
                }
            }
    usort($all_result,'sorttime');
        $borrow_details = '';
        $is_borrowed =0;

        $strData="";
        foreach ($all_result as $rowRequest){

            switch($rowRequest['sex']){
                case 'f': $gender = '<img src="../../gui/img/common/default/spf.gif" >'; break;
                case 'm': $gender = '<img src="../../gui/img/common/default/spm.gif">'; break;
                default: $gender = '&nbsp;'; break;
            }
            switch($rowRequest['is_urgent']){
                case '0': $priority = '<span style="font:bold 11px Arial; color:#003366">Normal</span>'; break;
                case '1': $priority = '<span style="font:bold 11px Arial; color:red">Urgent</span>'; break;
                default: $priority = '&nbsp;'; break;
            }
            if (trim($rowRequest['date_birth'])!=''){
                $date_birth = @formatDate2Local($rowRequest['date_birth'],$date_format);
                $bdateMonth = substr($date_birth,0,2);
                $bdateDay = substr($date_birth,3,2);
                $bdateYear = substr($date_birth,6,4);
                if (!checkdate($bdateMonth, $bdateDay, $bdateYear)){
                    # invalid birthdate
                    $date_birth = '';
                }
            }else {
                $date_birth = '';
            }
            if (trim($rowRequest['request_date'])!=''){
                $date_request = @formatDate2Local($rowRequest['request_date'],$date_format);
            }else {
                $date_request = '';
            }

            $lname = htmlentities($rowRequest['name_last']);
            $fname = htmlentities($rowRequest['name_first']).' '.htmlentities($rowRequest['suffix']);
            #$sub_dept_name = htmlentities($rowRequest['sub_dept_id']);
            #edited by VAN 06-28-08
            $sub_dept_name = htmlentities($rowRequest['name_short']);

            $rs = $radio_obj->getBorrowedInfo($rowRequest['batch_nr']);
            if ($rs){
                    $row2= $rs->FetchRow();

                    if ($row2["is_borrowed"]==1) {
                            if((string)$row2["remarks"]=="")
                            $strRemarks="None";
                            else
                                $strRemarks=$row2["remarks"];
                            $borrower_name=$row2['borrower'];
                            if($borrower_name=="")
                                $borrower_name=$row2['borrower_name'];

                            $borrow_details = 'Borrower         : '.mb_strtoupper($borrower_name).' <br>
                                                                 Date Borrowed : '.date('m/d/Y',strtotime($row2['date_borrowed'])).'<br>
                                                                 Time Borrowed : '.date('h:i A',strtotime($row2['time_borrowed'])).
                                                                 "</br>\nRemarks: ".$strRemarks;
                            $is_borrowed = 1;
                    }else{
                            $borrow_details = 'Still Available';
                            $is_borrowed = 0;
                    }
            }else{
                     $borrow_details = 'Still Available';
                     $is_borrowed = 0;
            }

                //added by daryl
                    $row['is_cash'] = $rowRequest['is_cash'];
                    $row["refno"]   = $rowRequest["refno"];
                    $row['pid']    = $rowRequest["pid"];
                            // $objResponse->alert($row["is_cash"]);

                     if (($rowRequest['is_cash']==0)&&(!$rowRequest['request_flag'])){
                   
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


            if ($search_type=='unscheduled'){
            #jsRadioUnscheduledRequest(No,batchNo,dateRq,rid,lName,fName,bDate,rPriority)
                $objResponse->addScriptCall("jsRadioUnscheduledRequest",$my_count,$rowRequest['batch_nr'],$rowRequest['refno'],
                        $rowRequest['service_code'],$date_request,$rowRequest['rid'],$lname,$fname,$date_birth, $priority, $rowRequest['encounter_type'],$or_no);
            }else{
                $objResponse->addScriptCall("jsRadioRequest",$sub_dept_nr,$my_count,$rowRequest['batch_nr'],$rowRequest['refno'],
                $date_request,$sub_dept_name,$rowRequest['pid'],$rowRequest['rid'],$gender,
                $lname,$fname,$date_birth, $rowRequest['status'],$priority, $rowRequest['encounter_type'], $rowRequest['service_code'], $is_borrowed, $borrow_details, $rowRequest['is_served'], $rowRequest['request_flag'],$or_no);
            }
            $my_count++;
#           jsRadioRequest(tbodyId,No,batchNo,dateRq,sub_dept_name,pid,lName,fName,bDate,rStatus)
        }# end of while loop
    }else{ # else of if-stmt 'if ($ergebnis)'
            if ($search_type=='unscheduled'){
                $objResponse->addScriptCall("jsRadioNoFoundUnscheduledRequest");
            }else{
                $objResponse->addScriptCall("jsRadioNoFoundRequest",$sub_dept_nr);
            }
    }# end of else-stmt 'if ($ergebnis)'

        # Previous and Next button generation
    $nextIndex = $pagen->nextIndex();
    $prevIndex = $pagen->prevIndex();
#$objResponse->addAlert("PaginateRadioUndoneRequestList : \nnextIndex='".$nextIndex."'; \nprevIndex='".$prevIndex."' \npagen->csx=".$pagen->csx."' \npagen->max_nr=".$pagen->max_nr);
    $pageFirstOffset = 0;
    $pagePrevOffset = $prevIndex;
    $pageNextOffset = $nextIndex;
    $pageLastOffset = $totalcount-($totalcount%$pagen->MaxCount());
    if ($pagen->csx){
        $pageFirstClass = "segSimulatedLink";
        $pageFirstOnClick = " setPgx($pageFirstOffset); jsSortHandler('$oitem','$oitem','$odir','$sub_dept_nr'); ";
        $pagePrevClass = "segSimulatedLink";
        $pagePrevOnClick = " setPgx($pagePrevOffset); jsSortHandler('$oitem','$oitem','$odir','$sub_dept_nr'); ";
    }else{
        $pageFirstClass = "segDisabledLink";
        $pagePrevClass = "segDisabledLink";
    }
    if ($nextIndex){
        $pageNextClass = "segSimulatedLink";
        $pageNextOnClick = " setPgx($pageNextOffset); jsSortHandler('$oitem','$oitem','$odir','$sub_dept_nr'); ";
        $pageLastClass = "segSimulatedLink";
        $pageLastOnClick = " setPgx($pageLastOffset); jsSortHandler('$oitem','$oitem','$odir','$sub_dept_nr'); ";
    }else{
        $pageNextClass = "segDisabledLink";
        $pageNextOffset = $pageLastOffset;
        $pageLastClass = "segDisabledLink";
    }

    if ($search_type=='unscheduled'){
        $title ="List of Unscheduled Requests";
    }else{
        $title ="List of Pending Requests";
    }

    $img ='                                     <div id="pageFirst" class="'.$pageFirstClass.'" style="float:left" onclick="'.$pageFirstOnClick.'"> '.
            '                                           <img title="First" src="../../images/start.gif" border="0" align="absmiddle"/> '.
            '                                           <span title="First">First</span> '.
            '                                       </div> '.
            '                                       <div id="pagePrev" class="'.$pagePrevClass.'" style="float:left" onclick="'.$pagePrevOnClick.'"> '.
            '                                           <img title="Previous" src="../../images/previous.gif" border="0" align="absmiddle"/> '.
            '                                           <span title="Previous">Previous</span> '.
            '                                       </div> '.
            '                                       <div id="pageShow" style="float:left; margin-left:10px; text-align:center"> '.
            '                                           <span>'.$title.'</span> '.
            '                                       </div> '.
            '                                       <div id="pageLast" class="'.$pageLastClass.'" style="float:right" onclick="'.$pageLastOnClick.'"> '.
            '                                           <span title="Last">Last</span> '.
            '                                           <img title="Last" src="../../images/end.gif" border="0" align="absmiddle"/> '.
            '                                       </div> '.
            '                                       <div id="pageNext" class="'.$pageNextClass.'" style="float:right" onclick="'.$pageNextOnClick.'"> '.
            '                                           <span title="Next">Next</span> '.
            '                                           <img title="Next" src="../../images/next.gif" border="0" align="absmiddle"/> '.
            '                                       </div> ';
    $objResponse->addAssign("mainHead".$sub_dept_nr,"innerHTML", $img);
#$objResponse->addAlert("PaginateRadioUndoneRequestList : \n$img");
//  return $objResponse;
}

function makeSortLink($txt='SORT',$class='',$item,$oitem,$odir='ASC',$sub_dept_nr='',$width='',$optional=''){

    if($item==$oitem){
        if($odir=='ASC'){
            $img = '<img src="../../gui/img/common/default/arrow_red_up_sm.gif">';
        }else{
            $img = '<img src="../../gui/img/common/default/arrow_red_dwn_sm.gif">';
        }
    }else{
        $img='&nbsp;';
    }

    if($odir=='ASC') $dir='DESC';
    else $dir='ASC';
    $td = "<td class=\"".$class."\" width=\"".$width."\" align=\"center\" onClick=\"jsSortHandler('$item','$oitem','$dir','$sub_dept_nr');\" $optional>".$img."<b>".$txt."</b></td> \n";
    return $td;
}

function ColHeaderRadioUndoneRequest(&$objResponse, $tbId, $searchkey,$sub_dept_nr,$pgx, $thisfile, $rpath,$mode,$oitem,$odir){
#   $objResponse = new xajaxResponse();
    global $root_path;
    #$append = '&status='.$status.'&target='.$target.'&user_origin='.$user_origin.'&dept_nr'.$dept_nr;
    $class= 'adm_list_titlebar';

    $th  = "<thead><tr><th colspan=\"15\" id=\"mainHead".$sub_dept_nr."\">";
    $th .= "</th></tr></thead>";

    $thead  = "<thead><tr style='font:bold 11.5px Arial; color:#000000'>";
    $thead .= "<td width=\"2%\" class=\"".$class."\"><b>No.</b></td> \n";
    $thead .= makeSortLink('Ref. No.',$class,'batch_nr',$oitem,$odir,$sub_dept_nr,'8%');
    $thead .= makeSortLink('Batch No.',$class,'batch_nr',$oitem,$odir,$sub_dept_nr,'8%');
    $thead .= makeSortLink('Date Requested',$class,'request_date',$oitem,$odir,$sub_dept_nr,'11%');
    $thead .= makeSortLink('Deparment',$class,'sub_dept_name',$oitem,$odir,$sub_dept_nr);
    $thead .= makeSortLink('Exam',$class,'service_code',$oitem,$odir,$sub_dept_nr);
    $thead .= makeSortLink('RID',$class,'rid',$oitem,$odir,$sub_dept_nr,'8%');
    $thead .= makeSortLink('OR No.',$class,'or_no',$oitem,$odir,$sub_dept_nr,'8%');
    $thead .= makeSortLink('Sex',$class,'sex',$oitem,$odir,$sub_dept_nr,'1%');
    $thead .= makeSortLink('Family Name',$class,'name_last',$oitem,$odir,$sub_dept_nr);
    $thead .= makeSortLink('Name',$class,'name_first',$oitem,$odir,$sub_dept_nr);
    $thead .= makeSortLink('Patient Type',$class,'encounter_type',$oitem,$odir,$sub_dept_nr,'5%');
    #$thead .= makeSortLink('Birthdate',$class,'date_birth',$oitem,$odir,$sub_dept_nr,'8%');
    $thead .= makeSortLink('Status',$class,'status',$oitem,$odir,$sub_dept_nr,'8%');
    $thead .= makeSortLink('Priority',$class,'is_urgent',$oitem,$odir,$sub_dept_nr,'5%');
    $thead .= "<td class=\"".$class."\" align=\"center\"><b>Findings</b></td>";
    $thead .= "</tr></thead> \n";

    $thead1 = $th.$thead;
    $tbodyHTML = "<tbody id='TBodytab".$sub_dept_nr."'></tbody>";
#   $objResponse->addAlert("thead1 : \n".$thead1." tbodyHTML : \n".$tbodyHTML." prevNextTR : \n".$prevNextTR);
    $objResponse->addAssign($tbId,"innerHTML", $thead1.$tbodyHTML);

#   return $objResponse;
}#end of function ColHeaderRadioUndoneRequest

#added by VAN 07-09-08
function saveProcessRequest($mode, $batch_nr='', $sizes){
    global $root_path, $date_format, $_SESSION;

    $objResponse = new xajaxResponse();

    $radio_obj=new SegRadio();

    $list_sizes = array();
    foreach ($sizes as $i=>$v) {
        if ($v) $list_sizes[] = $v;
    }

    #$objResponse->addAlert(print_r($list_sizes,true));

    if ($list_sizes!=NULL){
        #$srvObj->clearDiscounts($data['refno']);
        $radio_obj->clearRadioProcess($batch_nr);
        #$srvObj->addDiscounts($data['refno'],$bulk);
    #   $ok = $radio_obj->createRadioProcess($batch_nr, $list_sizes);
            $ok = $radio_obj->createRadioProcess($batch_nr, $list_sizes);
        #   $objResponse->addAlert($radio_obj->sql);
    }


    if ($ok){
        $objResponse->addScriptCall("msgPopUp","Successfully processed the request.");
    }else{
        $objResponse->addScriptCall("msgPopUp","Failed to processed the request.");
    }

    return $objResponse;
}#end of function saveScheduledRequest

function updateProcessRequest($mode, $batch_nr='', $sizes){
    global $root_path, $date_format, $_SESSION;

    $objResponse = new xajaxResponse();
    $radio_obj=new SegRadio();

    if ($radio_obj->getScheduledRadioRequestInfo($batch_nr,true)){
        $ok = true;
    }else{
        $objResponse->addScriptCall("msgPopUp","Failed to update the schedule form. \n Close this window and try again.");
        $ok = false;
    }

    if ($ok){
        $msg = "";
        if($service_date){
            $service_dt = @formatDate2STD($service_date, $date_format);# reformat FROM mm/dd/yyyy TO yyyy-mm-dd
            if ($radio_obj->updateRadioRequestServiceDate($batch_nr, $service_dt)){
                $msg .= "Successfully updated the Date of Service. \n";
            }else{
                $msg .= "Failed to update the Date of Service. \n";
            }
        }
        $scheduled_dt = @formatDate2STD($scheduled_date, $date_format);# reformat FROM mm/dd/yyyy TO yyyy-mm-dd
        #commented by VAN 03-26-08
        #$scheduled_dt .= " ".$scheduled_time;
        $serialized_instructions = serialize($instructions);
        #added by VAN 03-26-08
        if (empty($_SESSION['sess_temp_fullname']))
            $encoder = $_SESSION['sess_user_name'];
        else
            $encoder = $_SESSION['sess_temp_fullname'];

        $ok = $radio_obj->updateRadioSchedule($batch_nr, $scheduled_dt, $scheduled_time, $service_dt, $serialized_instructions, $remarks, $encoder);

        if ($ok){
            $objResponse->addScriptCall("resetForm"); # reset the form
            _PopulateRadioScheduledRequests($objResponse, $mode, $sub_dept_nr, $pmonth, $pday, $pyear);
            $msg .= "Successfully updated the schedule form. \n";
        }else{
            $msg .= "Failed to update the schedule form. \n";
        }
        $objResponse->addScriptCall("msgPopUp",$msg);
    }# end of 'if ($ok)'
    return $objResponse;
}#end of function updateScheduledRequest

#-------------------------

$xajax->processRequest();
?>