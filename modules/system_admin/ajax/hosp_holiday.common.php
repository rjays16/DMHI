<?php
#added by daryl
#create a common for ajax hospital holiday
#10/20/14

	require('./roots.php');
	require_once($root_path.'classes/xajax/xajax.inc.php');
	$xajax = new xajax($root_path."modules/system_admin/ajax/hosp_holiday.server.php");	
	$xajax->setCharEncoding("iso-8859-1");

    $xajax->registerFunction("deleteHospHoliday");
    	
	$xajax->processRequests();
?>