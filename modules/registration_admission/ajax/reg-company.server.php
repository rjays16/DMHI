<?php
/*created by mai 06-24-2014*/
    require('./roots.php');
    require($root_path.'include/inc_environment_global.php');
    require($root_path."modules/registration_admission/ajax/reg-company.common.php");
    require_once($root_path.'include/care_api_classes/class_globalconfig.php');
    require_once($root_path.'include/care_api_classes/class_encounter.php');
    require_once($root_path.'include/care_api_classes/class_person.php');
    require_once($root_path.'include/care_api_classes/class_company.php');

    function populateCompany($sElem,$keyword, $encounter_nr, $page) {
        global $db;
        $glob_obj = new GlobalConfig($GLOBAL_CONFIG);
        $glob_obj->getConfig('pagin_insurance_search_max_block_rows');
        $maxRows = $GLOBAL_CONFIG['pagin_insurance_search_max_block_rows'];

        $objResponse = new xajaxResponse();
        $insObj=new Company;
        $offset = $page * $maxRows;

        $searchkey = utf8_decode($searchkey);
        $total_srv = $insObj->countSearchSelect($keyword,$maxRows,$offset);
        $total = $insObj->count;
        $lastPage = floor($total/$maxRows);

        if ((floor($total%10))==0)
            $lastPage = $lastPage-1;

        if ($page > $lastPage) $page=$lastPage;
        $ergebnis=$insObj->SearchSelect($keyword,$maxRows,$offset);
        
        $rows=0;

        $objResponse->call("setPagination",$page,$lastPage,$maxRows,$total);
        $objResponse->call("clearList","product-list");
        if ($ergebnis) {
            $rows=$ergebnis->RecordCount();
            while($result=$ergebnis->FetchRow()) {
                if($encounter_nr != "false"){
                    $patient_Insinfo = $insObj->getPatientCompanyInfo($encounter_nr, trim($result["comp_id"]));
                    $objResponse->call("addProductToList","product-list",trim($result["comp_id"]),trim($result["comp_full_name"]),
                                        trim($result["comp_name"]),$patient_Insinfo["encounter_nr"], $patient_Insinfo["allotment_limit"],
                                        $patient_Insinfo["remarks"]);
                }else{ //search cashier
                    $objResponse->call("addProductToList","product-list",trim($result["comp_id"]),trim($result["comp_full_name"]),
                                        trim($result["comp_name"]), $result["comp_add"]);
                }
            }#end of while
        } #end of if

        if (!$rows) $objResponse->call("addProductToList","product-list",NULL);
        if ($sElem) {
            $objResponse->call("endAJAXSearch",$sElem);
        }

        return $objResponse;
    }

    function populateDiscount($sElem,$keyword, $encounter_nr, $page) {
        global $db;
        $glob_obj = new GlobalConfig($GLOBAL_CONFIG);
        $glob_obj->getConfig('pagin_insurance_search_max_block_rows');
        $maxRows = $GLOBAL_CONFIG['pagin_insurance_search_max_block_rows'];

        $objResponse = new xajaxResponse();
        $insObj=new Company;
        $offset = $page * $maxRows;

        $searchkey = utf8_decode($searchkey);
        $total_srv = $insObj->countSearchSelectDiscount($keyword,$maxRows,$offset);
        $total = $insObj->count;
        $lastPage = floor($total/$maxRows);

        if ((floor($total%10))==0)
            $lastPage = $lastPage-1;

        if ($page > $lastPage) $page=$lastPage;
        $ergebnis=$insObj->SearchSelectDiscount($keyword,$maxRows,$offset);
        
        $rows=0;

        $objResponse->call("setPagination",$page,$lastPage,$maxRows,$total);
        $objResponse->call("clearList","product-list");
        if ($ergebnis) {
            $rows=$ergebnis->RecordCount();
            while($result=$ergebnis->FetchRow()) {
                if($encounter_nr != "false"){
                    $patient_Insinfo = $insObj->getPatientDiscountInfo($encounter_nr, trim($result["discountid"]));
                    $objResponse->call("addProductToList","product-list",trim($result["discountid"]),trim($result["discountdesc"]),
                                        trim($result["discountdesc"]),$patient_Insinfo["encounter_nr"], $patient_Insinfo["amount"],
                                        $patient_Insinfo["discountdesc"]);
                }else{ //search cashier
                    $objResponse->call("addProductToList","product-list",trim($result["discountid"]),trim($result["discountdesc"]),
                                        trim($result["discountdesc"]), '');
                }
            }#end of while
        } #end of if

        if (!$rows) $objResponse->call("addProductToList","product-list",NULL);
        if ($sElem) {
            $objResponse->call("endAJAXSearch",$sElem);
        }

        return $objResponse;
    }

    $xajax->processRequest();
