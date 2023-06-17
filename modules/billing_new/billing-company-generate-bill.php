<?php
# ============================================
#
# Added by Jarel 3/4/2014
# For Generating Bill - Company
#
# ============================================

error_reporting(E_COMPILE_ERROR | E_CORE_ERROR | E_ERROR);
require('./roots.php');
require($root_path.'include/inc_environment_global.php');

define('NO_2LEVEL_CHK',1);
$local_user='ck_pflege_user';

require_once($root_path.'include/inc_front_chain_lang.php');
require_once($root_path.'include/care_api_classes/class_company.php');
require_once($root_path.'gui/smarty_template/smarty_care.class.php');
require_once($root_path."modules/billing_new/ajax/billing-company-list.common.php");
require_once($root_path.'include/inc_date_format_functions.php');

# Import Classes here ====================================================

$ObjCompany = new Company();

# ========================================================================

global $db;

$smarty = new Smarty_Care('common');
$smarty->assign('sToolbarTitle',"Company Billing :: Generate Bill");
$smarty->assign('sWindowTitle',"Company Billing :: Generate Bill");

$breakfile = 'javascript:window.parent.cClick();';
$smarty->assign('breakfile', $breakfile);

ob_start();
?>
    <script type="text/javascript" src="<?=$root_path?>js/jsprototype/prototype.js"></script>
    <link rel="stylesheet" type="text/css" media="all" href="<?=$root_path?>js/jscalendar/calendar-win2k-cold-1.css">
    <script type="text/javascript" src="<?=$root_path?>js/jscalendar/calendar.js"></script>
    <script type="text/javascript" src="<?=$root_path?>js/jscalendar/calendar-setup_3.js"></script>
    <script type="text/javascript" src="<?=$root_path?>js/jscalendar/lang/calendar-en.js"></script>
    <script type="text/javascript" src="<?=$root_path?>js/NumberFormat154.js"></script>

    <link rel="stylesheet" href="<?= $root_path ?>js/jquery/themes/seg-ui/jquery.ui.all.css" type="text/css" />
    <script type='text/javascript' src="<?=$root_path?>js/jquery/jquery-1.8.2.js"></script>
    <script type='text/javascript' src="<?=$root_path?>js/jquery/ui/jquery-ui-1.9.1.js"></script>
    <script type="text/javascript" src="<?=$root_path?>js/jquery/jquery.datetimepicker/jquery-ui-timepicker-addon.js"></script>
    <script type="text/javascript" src="<?=$root_path?>js/jquery/jquery.datetimepicker/jquery-ui-sliderAccess.js"></script>
    <script type="text/javascript">var $j = jQuery.noConflict();</script>

    <script type="text/javascript" src="<?=$root_path?>js/overlibmws/iframecontentmws.js"></script>
    <script type="text/javascript" src="<?=$root_path?>js/overlibmws/overlibmws.js"></script>
    <script type="text/javascript" src="<?=$root_path?>js/overlibmws/overlibmws_draggable.js"></script>
    <script type="text/javascript" src="<?=$root_path?>js/overlibmws/overlibmws_filter.js"></script>
    <script type="text/javascript" src="<?=$root_path?>js/overlibmws/overlibmws_overtwo.js"></script>
    <script type="text/javascript" src="<?=$root_path?>js/overlibmws/overlibmws_scroll.js"></script>
    <script type="text/javascript" src="<?=$root_path?>js/overlibmws/overlibmws_shadow.js"></script>
    <script type="text/javascript" src="<?=$root_path?>js/overlibmws/overlibmws_modal.js"></script>

    <script type="text/javascript" src="js/billing-company-generate.js"></script>
<?

# Put your conditions here =============================================

# Assign values
$by_date = $_GET['by_date'];

if($by_date == 1)
{
    $cutoff = DATE("Y-m-d", strtotime($_GET['sel_date']));
}
else
{
    $cutoff = "Today";
}

$comp_id = $_GET['comp_id'];
$cutoff = (($_GET['cutoff'])? $_GET['cutoff']:strftime("%Y-%m-%d"));
$billnr = $_GET['billnr'];
//$db->debug =true;

# Get Unbilled Employees
if(!$billnr)
    $result = $ObjCompany->getUnbilledEmployees($comp_id, $cutoff);
else
    $result = $ObjCompany->getbilledEmployees($billnr);

$total_amount=0;
$discount=0;
$net_amount=0;
if($result){
    while ($row = $result->FetchRow())
    {

        $transdate = $row['discharge_date'];
        $employeename = $row['name'];
        $amount = number_format($row['amount'],2,'.',',');
        $enc = $row['encounter_nr'];
        $total_amount += $row['amount'];
        $discount = $row['discount'];
        $disable = '';
        if($row['flag'])
            $disable = "disabled";

        $srcRows .= '<tr>
						<td width="20%">'.$transdate.'</td>
						<td width="20%">'.$enc.'</td>
						<td width="40%">'.$employeename.'</td>
						<td width="40%">'.$amount.'</td>
						<td align="center">
						    <input class="checkall" id="select-employee-'.$enc.'" name="select-employee-'.$enc.'" type="hidden"
						    value="'.$amount.'" encounter_nr="'.$enc.'"  />
							<input '.$disable.' class="generate-bill" id="check-select-employee-'.$enc.'" type="checkbox"
							 onchange="calculateSub('.$enc.');getCaseNumbers('.$enc.');" style="valign:bottom"/>
						</td>
					</tr>';
    }
}

//added by EJ 09/02/2014
if (!$billnr) {
    $bill_nr = 0;
}
else 
    $bill_nr = $_GET['billnr'];

$previewButton = '<button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary"
                   onclick="printReport('.$comp_id.','.strtotime($cutoff).','.$bill_nr.');"
					   style="font:bold 12px Arial; cursor:pointer" role="button">
							<span class="ui-button-icon-primary ui-icon ui-icon-print"></span>
					  		<span class="ui-button-text">Preview</span>
					  </button><input type = "hidden" value="'.$root_path.'" id="root_path"/>';



global $allowedarea;
$allowedarea = array('_a_2_BillGen');
if (validarea($_SESSION['sess_permission'],1)) {

    $generateBillButton = '<button '.$disable.' title="Generate Bill" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary"
								   id="btnSave" name="btnSave"
								   style="font:bold 12px Arial; cursor:pointer" role="button">
										<span class="ui-button-icon-primary ui-icon ui-icon-disk"></span>
								  		<span class="ui-button-text">Generate Bill</span>
								  </button>';
} else {
    $generateBillButton = '<button '.$disable.' title="No Permission" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary"
								   id="btnSave" name="btnSave"
								   style="font:bold 12px Arial; cursor:pointer" role="button" disabled>
										<span class="ui-button-icon-primary ui-icon ui-icon-disk"></span>
								  		<span class="ui-button-text">Generate Bill</span>
								  </button>';

}

# ======================================================================

$xajax->printJavascript($root_path.'classes/xajax_0.5');

$sTemp = ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript',$sTemp);

# assign your elements here ============================================
$comp_name = $db->GetOne("SELECT comp_full_name FROM seg_company WHERE comp_id=".$db->qstr($comp_id));
$smarty->assign('sAgency',$comp_name);
$smarty->assign('sCutOff',date("F j, Y",strtotime($cutoff)));
$smarty->assign('sListRows',$srcRows);

$total_amount = 0;
$smarty->assign('sSubTotal',number_format($total_amount,2,'.',','));
$smarty->assign('sDiscount',number_format($discount,2,'.',','));
$smarty->assign('sNetTotal',number_format($total_amount - $discount,2,'.',','));

$curTme  = strftime("%Y-%m-%d %H:%M:%S");
$curDate = strftime("%b %d, %Y %I:%M%p", strtotime($curTme));
if ($_GET['billdate']){
    $smarty->assign('sDate', '<input class="segInput" id="billdate_display" name="billdate_display" type="text" size="16" value="'.$curDate.'" style="font:bold 12px Arial; float;left;" >
						  <input class="jedInput" name="billdate" id="billdate" type="hidden" value="'.($submitted ? strftime("%Y-%m-%d %H:%M:%S", strtotime($bill_date)) : $curTme).'" style="font:bold 12px Arial">');
}else{
    $smarty->assign('sDate', '<input class="segInput" id="billdate_display" name="billdate_display" type="text" size="16" value="'.$curDate.'" style="font:bold 12px Arial; float;left;" >
						  <input class="jedInput" name="billdate" id="billdate" type="hidden" value="'.($submitted ? strftime("%Y-%m-%d %H:%M:%S", strtotime($_POST['billdate'])) : $curTme).'" style="font:bold 12px Arial">');
}

# Buttons (Preview, Generate Bill)
$smarty->assign('sPreview',$previewButton);
$smarty->assign('sGenerateBill',$generateBillButton);
$smarty->assign('sDisable',$disable);

$smarty->assign('sHiddenInputs','<input type="hidden" id="comp_id" name="comp_id" value="'.$_GET['comp_id'].'" />
                                 <input type="hidden" id="billnr" name="billnr" value="'.$billnr.'" />');

# ======================================================================

$smarty->assign('bHideTitleBar',TRUE);
$smarty->assign('bHideCopyright',TRUE);
$smarty->assign('sMainBlockIncludeFile','billing_new/generate-bill.tpl'); //Assign the industrial_clinic template to the frameset
$smarty->display('common/mainframe.tpl'); //Display the contents of the frame

?>