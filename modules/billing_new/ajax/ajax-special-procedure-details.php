<?php
require('./roots.php');
require($root_path.'include/inc_environment_global.php');
require($root_path.'include/care_api_classes/class_caserate_icd_icp.php');

switch($_REQUEST['action']){
    case "get_special_procedures":
        getSpecialProcedures();
        break;
}

function getSpecialProcedures(){
    $specialProcedures = new Icd_Icp($_REQUEST);
    $response = $specialProcedures->getPatientSpecialProcedures(getRequestData('encounter_nr'));
    $data['sql'] = $specialProcedures->sql;
    $data['response'] = $response;
    $data['newborn'] = $specialProcedures->newborn;
    echo json_encode($data);
}

function getRequestData($key){
    if(isset($_REQUEST[$key])){
        return $_REQUEST[$key];
    }else{
        return null;
    }
}