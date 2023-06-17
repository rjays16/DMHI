<?php
/**
 * Created by Jarel
 * Date: 7/18/14
 * Time: 10:52 AM
 */
require('./roots.php');
require($root_path.'include/inc_environment_global.php');
require_once($root_path."modules/billing_new/ajax/billing-company-list.common.php");
require_once($root_path."include/care_api_classes/class_company.php");
#added by janken 12/09/2014 for requiring curl class
require_once $root_path.'include/care_api_classes/curl/class_curl.php';
//require_once($root_path."include/care_api_classes/industrial_clinic/class_ic_transactions.php");

function updateFilterOption($noption, $bchecked)
{
    $objResponse = new xajaxResponse();

    $_SESSION["filteroption"][$noption] = $bchecked;

    return $objResponse;
}

function updateFilterTrackers($sfiltertype, $ofilter)
{
    $objResponse = new xajaxResponse();

    $_SESSION["filtertype"] = $sfiltertype;
    $_SESSION["filter"] = $ofilter;

    return $objResponse;
}

function updatePageTracker($npage)
{
    $objResponse = new xajaxResponse();
    $_SESSION["current_page"] = $npage;

    return $objResponse;
}

function clearFilterTrackers()
{
    $objResponse = new xajaxResponse();

    unset($_SESSION["filtertype"]);
    unset($_SESSION["filter"]);

    return $objResponse;
}

function clearPageTracker()
{
    $objResponse = new xajaxResponse();
    unset($_SESSION["current_page"]);
    return $objResponse;
}

function paidBill($billnr){ //maimai
    $objResponse = new xajaxResponse();
    $objCompany = new Company();

    if($objCompany->paidBill($billnr)){
        $objResponse->alert("Succesfully paid bill!");
    }else{
        $objResponse->alert("Error in updating bill.");
    }
}

function saveCompanyBill($data,$details)
{
    global $db;
    //$db->debug = true;
    $objCompany = new Company();
    $objResponse = new xajaxResponse();
    //added by janken 12/09/2014
    $curl_obj = new Rest_Curl();
    
    $insert = array();
    $mode = 0;
    if($data['billnr']==''){
        $mode = 1;
        $data['billnr']= $objCompany->getNewBillingNr();
    }


    $db->BeginTrans();
    foreach($details as $key => $value){
        $insert[]= "((SELECT uuid()),".$db->qstr($data['billnr']).",".$db->qstr($value['enc']).", ".$db->qstr($value['amount'])." )";
    }

    $insertStr =implode(",",$insert);

    if($mode){
        $ok = $objCompany->saveBillingHeader($data);
    }else{
        $ok = $objCompany->updateBillingHeader($data);
        $objCompany->clearBillingDetails($data['billnr']);
    }


    if($ok && ($insertStr)){
        $ok1 = $objCompany->saveBillingDetails($insertStr);
        foreach($details as $key => $value){
            $objCompany->setFlagLedger($data['comp_id'],$value['enc'],$data['billnr']);
        }

        if($ok1){
            $db->CommitTrans();
            $curl_obj->companyCharge($data['billnr']);
            $objResponse->alert("Successfully Save Bill.");
            $objResponse->assign("billnr",'value',$data['billnr']);
        }else{
            $db->RollbackTrans();
            $objResponse->alert("Failed Saving Bill!");
        }
    }else{
        $db->RollbackTrans();
        $objResponse->alert("Failed Saving Bill!");
    }


    return $objResponse;
}


$xajax->processRequest();
?>