<?php

require('./roots.php');
require($root_path.'include/inc_environment_global.php');
require_once($root_path.'include/care_api_classes/class_person.php');

$person = new Person;
$params = $_GET;

$pid = $params['pid'];
if (empty($pid)) {
    header($_SERVER["SERVER_PROTOCOL"]." 400 Required information missing");
    exit;
}
$person->preloadPersonInfo($pid);
$bd = null;
if (!empty($params['pMemberBirthDate'])) {
    $dt = str_replace('-', '/', $params['pMemberBirthDate']);
    $bd = date('Y-m-d', strtotime($dt));
}

#Mod by jeff 4-10-18
$values = array(
    'pid' => $pid,
    'class_nr' => @$params['class_nr'],
    'encounter_nr' => @$params['encounter_nr'],
    'hcare_id' => @$params['provider'],
    'insurance_nr' => @$params['pPIN'],
    'member_lname' => @$params['pMemberLastName'],
    'member_fname' => @$params['pMemberFirstName'],
    'member_mname' => @$params['pMemberMiddleName'],
    'suffix' => @$params['pMemberSuffix'],
    'sex' => @$params['pMemberGender'],
    'birth_date' => $bd,
    'street_name' => $person->StreetName(), 
    'brgy_nr' => $person->getValue('brgy_nr'),
    'mun_nr' => $person->getValue('mun_nr'),
    'member_type' => $params['pMembershipType'],
    'relation' => $params['pPatientIs'],
    'employer_no' => $params['pPEN'],
    'employer_name' => $params['pEmployerName'],
);

if ($person->saveInsuranceMembershipInfo($values)) {
    // success . . .
} else {
    header($_SERVER["SERVER_PROTOCOL"]." 500 Saving failed");
    echo $db->ErrorMsg();
}