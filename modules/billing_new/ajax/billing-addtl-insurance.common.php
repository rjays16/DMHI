<?php
require('roots.php');
require_once($root_path.'classes/xajax/xajax.inc.php');
$xajax = new xajax($root_path.'modules/billing_new/ajax/billing-addtl-insurance.server.php');
$xajax->setCharEncoding("ISO-8859-1");

$xajax->registerFunction("getApplicableInsurance");
$xajax->registerFunction("fillInsuranceCbo");
$xajax->registerFunction("SaveAppliedInsurance");
$xajax->registerFunction("getInsuranceInfo");
$xajax->registerFunction("deleteInsurance");
$xajax->registerFunction("getBillAreasApplied");
$xajax->registerFunction("getInsurance");
?>
