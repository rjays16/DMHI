<?php
require('./roots.php');
require($root_path.'include/inc_environment_global.php');
require_once($root_path.'include/care_api_classes/class_company.php');

$company= new Company;
$params = $_GET;

$enc_nr = $params['encounter_nr'];
if (empty($enc_nr)) {
    header($_SERVER["SERVER_PROTOCOL"]." 400 Required information missing");
    exit;
}

$values = array(
    'comp_name' => @$params['comp_name'],
    'comp_id' => @$params['comp_id'],
    'encounter_nr' => @$params['encounter_nr'],
    'max_amount' => @$params['max_amount'],
    'remarks' => @$params['remarks']
);

if ($company->saveChargeCompany($values)) {
    // success
} else {
    header($_SERVER["SERVER_PROTOCOL"]." 500 Saving failed");
    echo $db->ErrorMsg();
}