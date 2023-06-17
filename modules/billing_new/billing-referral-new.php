<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'modules/billing_new/ajax/billing-referral.common.php');
require($root_path.'include/inc_environment_global.php');

define('LANG_FILE','lab.php');
define('NO_2LEVEL_CHK',1);
$local_user='ck_prod_db_user';
require_once($root_path.'include/inc_front_chain_lang.php');
$thisfile=basename(__FILE__);
$title = "More Drugs & Medicines or Supplies";
$breakfile="";

$enc = $_GET['enc'];
$referral_nr = $_GET['referral_nr'];

# Start Smarty templating here
 /**
 * LOAD Smarty
 */

 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

 $smarty->assign('bHideTitleBar',TRUE);
 $smarty->assign('bHideCopyright',TRUE);

 # Title in the title bar
 $smarty->assign('sToolbarTitle',"$title $LDLabDb $LDSearch");

 # href for the back button
// $smarty->assign('pbBack',$returnfile);

 # href for the help button
 $smarty->assign('pbHelp',"javascript:gethelp('submenu1.php','$LDLab')");

 # href for the close button
 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('sWindowTitle',"$title");

 # Assign Body Onload javascript code
 $smarty->assign('sOnLoadJs','onLoad="GetReferral();"');

 # Collect javascript code
 ob_start()

?>
<script language="javascript" >
<!--

// -->
</script>
<script type="text/javascript" src="<?=$root_path?>js/jsprototype/prototype.js"></script>
<script type="text/javascript" src="<?=$root_path?>js/NumberFormat154.js"></script>
<script type="text/javascript" src="<?=$root_path?>js/gen_routines.js"></script>
<script type="text/javascript" src="<?=$root_path?>modules/billing_new/js/billing-referral-new.js?t=<?=time()?>"></script>
<?php
$xajax->printJavascript($root_path.'classes/xajax_0.5');
$sTemp = ob_get_contents();
ob_end_clean();

$smarty->append('JavaScript',$sTemp);

# Buffer page output

ob_start();

?>
    <span id="ajax_display"></span> 
     <table style="font:bold 12px Arial; background-color:#e5e5e5;">
        <tr style="font:bold 12px Arial; color: #2d2d2d">
            <td>Hosp. acred #.:</td>
            <td style="float:left;">
                <input id="ReferredHospital" name="ReferredHospital" size="42" class="segInput" type="text" style="font: bold 12px Arial;"/>
            </td>                         
        </tr>
        <tr style="font:bold 12px Arial; color: #2d2d2d">
            <td>Reason/s:</td>
            <td style="float:left;">
                <textarea class="segInput" id="Reason" name="Reason" rows="4" cols="51"></textarea>
            <td>
        </tr>
        <tr style="font:bold 12px Arial; color: #2d2d2d">
            <td colspan='2' align="center">
                <input type="button" onclick="SaveReferral()" value="Save">
                <input type="button" onclick="ClearReferral()" value="Clear" style="margin-left: 10px;">
            </td>
        </tr>
    </table>


    <input type="hidden" name="sid" value="<?php echo $sid?>">
    <input type="hidden" name="lang" value="<?php echo $lang?>">
    <input type="hidden" name="cat" value="<?php echo $cat?>">
    <input type="hidden" name="userck" value="<?php echo $userck ?>">
    <input type="hidden" name="encounter_nr" id="encounter_nr" value="<?echo $enc ?>">
    <input type="hidden" name="referral_nr" id="referral_nr" value="<?echo $referral_nr ?>">
    <input type="hidden" name="mode" value="search">
    <input type="hidden" id="area_code" name="area_code" value="" />
<?php

# Workaround to force display of results  form
$bShowThisForm = TRUE;

# If smarty object is not available create one
if(!isset($smarty)){
    /**
 * LOAD Smarty
 * param 2 = FALSE = dont initialize
 * param 3 = FALSE = show no copyright
 * param 4 = FALSE = load no javascript code
 */
    include_once($root_path.'gui/smarty_template/smarty_care.class.php');
    $smarty = new smarty_care('common',FALSE,FALSE,FALSE);
    
    # Set a flag to display this page as standalone
    $bShowThisForm=TRUE;
}

?>

<form action="<?php echo $breakfile?>" method="post">
    <input type="hidden" name="sid" value="<?php echo $sid ?>">
    <input type="hidden" name="lang" value="<?php echo $lang ?>">
    <input type="hidden" name="userck" value="<?php echo $userck ?>">
</form>
<?php if ($from=="multiple")
echo '
<form name=backbut onSubmit="return false">
<input type="hidden" name="sid" value="'.$sid.'">
<input type="hidden" name="lang" value="'.$lang.'">
<input type="hidden" name="userck" value="'.$userck.'">
</form>
';
?>
</div>
<?php

$sTemp = ob_get_contents();
ob_end_clean();

# Assign the form template to mainframe

 $smarty->assign('sMainFrameBlockData',$sTemp);

 /**
 * show Template
 */
 $smarty->display('common/mainframe.tpl');
?>
