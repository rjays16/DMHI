<?php
	require('./roots.php');
	require_once($root_path.'classes/xajax/xajax.inc.php');
	$xajax = new xajax($root_path."modules/billing_new/ajax/icd_icp.server.php");
	$xajax->setCharEncoding("ISO-8859-1");
	$xajax->registerFunction("populateDiagnosisList");
    $xajax->registerFunction("addCode");  
    $xajax->registerFunction("rmvCode"); 
    $xajax->registerFunction("saveAltDesc");    
    $xajax->registerFunction("updateAltICD"); //added by jasper 04/24/2013
    $xajax->registerFunction("saveAltCode"); //added by jasper 06/30/2013

    $xajax->registerFunction("addProcedure");
    $xajax->registerFunction("populateProcedureList");
    $xajax->registerFunction("deleteProcedure");
    $xajax->registerFunction("updateIcdCode"); //added by Nick, 3/1/2014
    $xajax->registerFunction("updateIcdDesc"); //added by Nick, 3/1/2014
    $xajax->registerFunction("updateIcpDesc"); //added by Nick, 3/1/2014
    $xajax->registerFunction("updateIcdSequence"); //added by Nick, 4/15/2014
    $xajax->registerFunction("UpdateReason");

    $xajax->registerFunction("getbillcaserate");#added by daryl
    $xajax->registerFunction("getbillcaserate_ICP");#added by daryl
    $xajax->registerFunction("deleteProcedure_seg");#added by daryl

    $xajax->registerFunction("saveDiagnosisEnc");#added by jeff
    $xajax->registerFunction("saveFilterCardNumber");
    $xajax->registerFunction("updateCheckupDate"); # added by Jan Chris Ogel 01-26-2021 for update pre-natal checkup
    $xajax->registerFunction("updateNewbornPackage"); //added by Juna, August 27 2021
?>