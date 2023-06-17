<?
/**
*Created by mai
*Created on 09-15-2014
*/

require('./roots.php');
require($root_path.'include/inc_environment_global.php');

define('NO_2LEVEL_CHK',1);
$local_user='ck_pflege_user';

require_once($root_path.'include/inc_front_chain_lang.php');
require_once($root_path.'gui/smarty_template/smarty_care.class.php');
require_once($root_path.'modules/company/ajax/seg-company.common.php');

$smarty = new Smarty_Care('common');

$breakfile = 'javascript:window.parent.cClick();';
$smarty->assign('breakfile', $breakfile);
ob_start();

$comp_id = $_GET['comp_id'];

?>

<script type="text/javascript" src="<?=$root_path?>js/jsprototype/prototype.js"></script>
<link rel="stylesheet" href="<?= $root_path ?>js/jquery/themes/seg-ui/jquery.ui.all.css" type="text/css" />
<script type="text/javascript" src="<?=$root_path?>js/jquery/jquery.js"></script>
<script type="text/javascript" src="<?=$root_path?>js/jquery/ui/jquery-ui.js"></script>
<script type="text/javascript" src="<?=$root_path?>js/jquery/jquery-1.8.2.js"></script>
<script type="text/javascript" src="js/comp-form.js"></script>
<script>var $J = jQuery.noConflict();</script>
<?
$xajax->printJavascript($root_path.'classes/xajax_0.5');
$sTemp = ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript',$sTemp);

if($comp_id){
	$smarty->assign('sButton', '<button class="jedButton" onclick="saveComp(\'update\');" style="cursor:pointer"><img src="'.$root_path.'/gui/img/common/default/add.png">Update</button>');
}else{
	$smarty->assign('sButton', '<button class="jedButton" onclick="saveComp(\'save\');" style="cursor:pointer"><img src="'.$root_path.'/gui/img/common/default/add.png">Save</button>');
}
?>

<input type="hidden" id="comp_id" value="<?=$comp_id?>">

<?
$smarty->assign('bHideTitleBar',TRUE);
$smarty->assign('bHideCopyright',TRUE);
$smarty->assign('sMainBlockIncludeFile','company/comp-form.tpl');
$smarty->display('common/mainframe.tpl');
?>