<?php
    function SaveReferral($hospital, $reason, $enc, $nr) {    
        $objResponse = new xajaxResponse();
        $ref = new Referral();
        $ok = $ref->SaveReason($hospital, $reason, $enc, $nr);
        if($ok){
            $objResponse->alert("Successfully save!");
        }else{
            $objResponse->alert("Failed to save!");
        }
        return $objResponse;
        
    }

    function Getreferral($enc, $nr){
        $objResponse = new xajaxResponse();
        $ref = new Referral();

        $reason ="";
        $hospital ="";

        $result = $ref->Getreferral($enc, $nr);
        if($result){
             while ($row = $result->FetchRow()){
               $reason = $row['reason'];
               $hospital = $row['accredit_nr'];     
             }
             $objResponse->call('SetValue', $reason, $hospital);
        }

        return $objResponse;
        

    }
    function SearchReferral($enc, $nr){
    $objResponse = new xajaxResponse();
    $ref = new Referral();

    $result = $ref->Getreferral($enc, $nr);
     if($result){
        $res = $result->RowCount();
                 $objResponse->Call('CheckEmptyReferral', $res);
        }
        return $objResponse;
    }

    require('./roots.php');
    require($root_path.'include/inc_environment_global.php');        
    require_once($root_path.'include/care_api_classes/class_referral.php');
    require($root_path."modules/billing_new/ajax/billing-referral.common.php");
    $xajax->processRequest();    
?>
