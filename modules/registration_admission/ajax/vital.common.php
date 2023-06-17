<?php
    require('./roots.php');
    
    require_once($root_path.'classes/xajax_0.5/xajax_core/xajax.inc.php');
    $xajax = new xajax($root_path."modules/registration_admission/ajax/vital.server.php");
    
    $xajax->setCharEncoding("ISO-8859-1");
    #$xajax->configure('debug',true); 
    $xajax->register(XAJAX_FUNCTION, "populateVitalSigns");
    $xajax->register(XAJAX_FUNCTION, "deleteVitaltoList"); 
?>
