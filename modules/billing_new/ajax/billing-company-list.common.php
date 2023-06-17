<?php
/**
 * Created by Jarel.
 * Date: 7/18/14
 * Time: 10:49 AM
 */
require('./roots.php');
require_once($root_path.'classes/xajax_0.5/xajax_core/xajax.inc.php');
$xajax = new xajax($root_path.'modules/billing_new/ajax/billing-company-list.server.php');

$xajax->setCharEncoding("ISO-8859-1");
$xajax->register(XAJAX_FUNCTION, "updateFilterOption");
$xajax->register(XAJAX_FUNCTION, "updateFilterTrackers");
$xajax->register(XAJAX_FUNCTION, "updatePageTracker");
$xajax->register(XAJAX_FUNCTION, "clearFilterTrackers");
$xajax->register(XAJAX_FUNCTION, "clearPageTracker");
$xajax->register(XAJAX_FUNCTION, "saveCompanyBill");
$xajax->register(XAJAX_FUNCTION, "paidBill"); //maimai
?>