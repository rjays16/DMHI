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

if ($company->removeCharge($enc_nr)) {
    // success
} else {
    header($_SERVER["SERVER_PROTOCOL"]." 500 Removing failed");
    echo $db->ErrorMsg();
}