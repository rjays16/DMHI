<?php
	require('./roots.php');
	#commented out bry bryan on Sept 15, 2008
	/*require_once($root_path.'classes/xajax/xajax.inc.php');*/
	
	#added by bryan on Sept 15, 2008
	require_once($root_path.'classes/xajax_0.5/xajax_core/xajax.inc.php');
	$xajax = new xajax($root_path."modules/pharmacy/ajax/order.server.php");
	
	$xajax->setCharEncoding("ISO-8859-1");
  # $xajax->configure('debug',true); 
	$xajax->register(XAJAX_FUNCTION, "reset_referenceno");
	$xajax->register(XAJAX_FUNCTION, "add_item");
	$xajax->register(XAJAX_FUNCTION, "populate_order");
	$xajax->register(XAJAX_FUNCTION, "get_charity_discounts");
	$xajax->register(XAJAX_FUNCTION, "populateOrderList");
   	$xajax->register(XAJAX_FUNCTION, "deleteOrder"); 
   	$xajax->register(XAJAX_FUNCTION, "updatePHICCoverage"); 
  	$xajax->register(XAJAX_FUNCTION, "getChargeCompanyBalance"); //added by mai 07-15-2014
   	$xajax->register(XAJAX_FUNCTION, "updateCoverage"); 
   	$xajax->register(XAJAX_FUNCTION, "updatePhicExceed");
   	$xajax->register(XAJAX_FUNCTION, 'changePrice'); //added by janken 1/13/2015


