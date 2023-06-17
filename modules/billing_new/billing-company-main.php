<?php
/**
 * Created by Jarel.
 * Date: 7/16/14
 * Time: 9:00 PM
 */
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/inc_environment_global.php');

define('NO_2LEVEL_CHK',1);
define('LANG_FILE','products.php');
$local_user='ck_ic_transaction_user';

require_once($root_path.'include/inc_front_chain_lang.php');
require_once($root_path.'modules/billing_new/ajax/billing-company-list.common.php');

$GLOBAL_CONFIG=array();
# Create global config object
require_once($root_path.'include/care_api_classes/class_globalconfig.php');
require_once($root_path.'include/inc_date_format_functions.php');
#added by janken 12/09/2014 for requiring curl class
require_once $root_path.'include/care_api_classes/curl/class_curl.php';

$glob_obj=new GlobalConfig($GLOBAL_CONFIG);
if($glob_obj->getConfig('date_format')) $date_format=$GLOBAL_CONFIG['date_format'];
$date_format=$GLOBAL_CONFIG['date_format'];
$phpfd=$date_format;
$phpfd=str_replace("dd", "%d", strtolower($phpfd));
$phpfd=str_replace("mm", "%m", strtolower($phpfd));
$phpfd=str_replace("yyyy","%Y", strtolower($phpfd));
$phpfd=str_replace("yy","%y", strtolower($phpfd));

$breakfile=$root_path.'modules/billing/bill-main-menu.php'.URL_APPEND."&userck=$userck";

//$db->debug=1;

# Start Smarty templating here
/**
 * LOAD Smarty
 */

# Note: it is advisable to load this after the inc_front_chain_lang.php so
# that the smarty script can use the user configured template theme
require_once($root_path.'gui/smarty_template/smarty_care.class.php');
$smarty = new smarty_care('common');

// $smarty->assign('bHideTitleBar',TRUE);
// $smarty->assign('bHideCopyright',TRUE);

# Title in the title bar
$smarty->assign('sToolbarTitle',"Billing::Company");

# href for the back button
// $smarty->assign('pbBack',$returnfile);

# href for the help button
# $smarty->assign('pbHelp',"javascript:gethelp('products_db.php','search','$from','$cat')");
$smarty->assign('pbHelp',"javascript:gethelp('seg-ic-transactions-hist.php')");

# href for the close button
$smarty->assign('breakfile',$breakfile);

# Window bar title
$smarty->assign('sWindowTitle',"Company Billing");

# Assign Body Onload javascript code
$smarty->assign('sOnLoadJs','');

$c_type = $_GET['c_type']; //added by maimai 11-25-2014

# Collect javascript code
ob_start()

?>
    <script type="text/javascript" src="<?=$root_path?>js/overlibmws/iframecontentmws.js"></script>

    <!-- Core module and plugins:-->
    <script type="text/javascript" src="<?=$root_path?>js/jsprototype/prototype.js"></script>
    <script type="text/javascript" src="<?=$root_path?>js/fat/fat.js"></script>
    <link rel="stylesheet" type="text/css" media="all" href="<?= $root_path ?>js/jscalendar/calendar-win2k-cold-1.css" />
    <script type="text/javascript" src="<?=$root_path?>js/jscalendar/calendar.js"></script>
    <script type="text/javascript" src="<?=$root_path?>js/jscalendar/lang/calendar-en.js"></script>
    <script type="text/javascript" src="<?=$root_path?>js/jscalendar/calendar-setup_3.js"></script>
    <script type="text/javascript" src="<?=$root_path?>js/NumberFormat154.js"></script>
    <script type="text/javascript" src="<?=$root_path?>js/datefuncs.js"></script>

    <link rel="stylesheet" href="<?= $root_path ?>js/jquery/themes/seg-ui/jquery.ui.all.css" type="text/css" />
    <script type='text/javascript' src="<?=$root_path?>js/jquery/jquery-1.8.2.js"></script>
    <script type='text/javascript' src="<?=$root_path?>js/jquery/ui/jquery-ui-1.9.1.js"></script>
    <script type="text/javascript">var $j = jQuery.noConflict();</script>

    <script language="javascript" type="text/javascript">
    <!--
    var isComputing=0;

    function startComputing(acctid) {
        doneComputing();
        if (!isComputing) {
            isComputing = 1;

            return overlib('Computing bill of account# '+acctid+'...<br><img src="../../images/ajax_bar.gif">',
                WIDTH,300, TEXTPADDING,5, BORDER,0,
                STICKY, SCROLL, CLOSECLICK, MODAL,
                NOCLOSE, CAPTION,'Computing',
                MIDX,0, MIDY,0,
                STATUS,'Computing');
        }
    }

    function doneComputing(flag) {
        flag = (typeof(flag) == 'undefined') ? 0 : Number(flag);
        if (isComputing) {
            cClick();
            isComputing = 0;
        }
        if (flag) forceSubmit();
    }

    function pSearchClose() {
        cClick();
    }

    function disableNav() {
        with ($('pageFirst')) {
            className = 'segDisabledLink'
            setAttribute('onclick','')
        }
        with ($('pagePrev')) {
            className = 'segDisabledLink'
            setAttribute('onclick','')
        }
        with ($('pageNext')) {
            className = 'segDisabledLink'
            setAttribute('onclick','')
        }
        with ($('pageLast')) {
            className = 'segDisabledLink'
            setAttribute('onclick','')
        }
    }

    var djConfig = { isDebug: true };
    var FIRST_PAGE=1, PREV_PAGE=2, NEXT_PAGE=3, LAST_PAGE=4, SET_PAGE=0;

    function jumpToPage(jumptype, page) {
        var form1 = document.forms[0];

        switch (jumptype) {
            case FIRST_PAGE:
                $('jump').value = 'first';
                break;
            case PREV_PAGE:
                $('jump').value = 'prev';
                break;
            case NEXT_PAGE:
                $('jump').value = 'next';
                break;
            case LAST_PAGE:
                $('jump').value = 'last';
                break;
            case SET_PAGE:
                $('jump').value = page;
                break;
        }

        form1.submit();
    }


    function forceSubmit() {
        var dform = document.forms[0];
        dform.submit();
    }

    function validate() {
        return true;
    }

    function showBilled(chkbox) {
        $('onlybilled').value = (chkbox.checked) ? "1" : "";
        forceSubmit();
    }

    function keepFilters(noption) {
        var filter = '';

        switch (noption) {
            case 0:
                if ($('chkspecific').checked) {
                    var opt = $('selrecord').options[$('selrecord').selectedIndex];
                    filter = $(opt.value).value;
                    xajax_updateFilterOption(0, true);
                    xajax_updateFilterTrackers($('selrecord').value, filter);
                }
                else
                    xajax_updateFilterOption(0, false);
                break;

            case 1:
                if ($('chkdate').checked) {
                    if ($('seldate').value == 'specificdate') {
                        filter = $('specificdate').value;
                    }
//										if ($('seldate').value == 'between') {
//												filter = new Array($('between1').value, $('between2').value);
//										}

                    xajax_updateFilterOption(1, true);
                    xajax_updateFilterTrackers($('seldate').value, filter);
                }
                else
                    xajax_updateFilterOption(1, false);
        }
        clearPageTracker();
    }

    function keepPage() {
        var pg = $('page').value;
        xajax_updatePageTracker(pg);
    }

    function clearPageTracker() {
        xajax_clearPageTracker();
    }

    function generateBill(acctid, iscorpacct) {
        var seldte = $('seldate').value;
        var dteobj, curdte;
        var date1='', date2='';

        if (seldte == 'today') {
            dteobj = new Date();
        }
        else if (seldte == 'specificdate') {
            dteobj = parseDate($('specificdate').value);
        }

        var month = dteobj.getMonth()+1;
        var day =   dteobj.getDate();
        var yr  =   dteobj.getFullYear();
//			curdte = ((month < 10) ? "0" : "")+month+"/"+((day < 10) ? "0" : "")+day+"/"+yr;
        curdte = yr+"-"+((month < 10) ? "0" : "")+month+"-"+((day < 10) ? "0" : "")+day;

        startComputing(acctid);
        xajax_generateBill(acctid, Number(iscorpacct), curdte);
    }


    function computeBillTray(comp_id, cutoff,billnr)
    {
        var c_type = $('c_type').value;

        var pageBilling = '../../modules/billing_new/billing-company-generate-bill.php?comp_id='+comp_id+'&cutoff='+cutoff+'&billnr='+billnr+"&c_type="+c_type;
        dialogBilling = $j('<div></div>')
            .html('<iframe style="border: 0px; " src="' + pageBilling + '" width="100%" height=450px></caiframe>')
            .dialog({
                autoOpen: true,
                modal: true,
                show: 'fade',
                hide: 'fade',
                height: 'auto',
                width: '65%',
                title: 'Company Billing',
                position: 'top',
                close: function(){
                    location.reload(true);
                }
            });

            return false;
    }

    function deleteBill(id) {
        var dform = document.forms[0]
        $('delete').value = id;
        dform.submit();
    }

    function paidBill(billnr){ //added by maimai 11-25-2014
        if(confirm("Are you sure you want to paid bill \""+billnr+"\" ? This transaction cannot be cancelled anymore.")){
            xajax_paidBill(billnr);    
        }
    }

    -->
    </script>

<?php

$sTemp = ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript',$sTemp);

$xajax->printJavascript($root_path.'classes/xajax_0.5');

# Buffer page output
include($root_path."include/care_api_classes/class_company.php");

$objcompany = new Company();

if (!$_POST["applied"] || !isset($_POST["applied"]) || $_POST["applied"] == '') {
    if (isset($_SESSION["filteroption"])) {
//				if (isset($_SESSION["filteroption"][0])) $_REQUEST["chkinsurance"] = strcmp($_SESSION["filteroption"][0], 'true') == 0;
        if (isset($_SESSION["filteroption"][0])) $_REQUEST["chkspecific"] = strcmp($_SESSION["filteroption"][0], 'true') == 0;
        if (isset($_SESSION["filteroption"][1])) $_REQUEST["chkdate"] = strcmp($_SESSION["filteroption"][1], 'true') == 0;
    }

    if (isset($_SESSION["filtertype"])) {
        switch (strtolower($_SESSION["filtertype"])) {
//						case "insurance":
//								$_REQUEST["insurance"] = $_SESSION["filter"][0];
//								$_REQUEST["hcare_name"] = $_SESSION["filter"][1];
//								break;
            case "account_name":
            case "account_no":
                $_REQUEST["selrecord"] = $_SESSION["filtertype"];
                $_REQUEST[strtolower($_SESSION["filtertype"])] = $_SESSION["filter"];

                break;

            default:
                $_REQUEST["seldate"] = $_SESSION["filtertype"];
//								if (is_array($_SESSION["filter"])) {
//										$_REQUEST["between1"] = $_SESSION["filter"][0];
//										$_REQUEST["between2"] = $_SESSION["filter"][1];
//								}
//								else
                if ($_SESSION["filter"] != "")
                    $_REQUEST["specificdate"] = $_SESSION["filter"];
        }
    }
    else {
        if (is_null($_SESSION["filteroption"])) {
            $_REQUEST['chkdate'] = true;
        }
        $_REQUEST["seldate"] = "today";
    }
}

if (isset($_SESSION["current_page"])) {
    $_REQUEST['page'] = $_SESSION["current_page"];
}

if ($_POST['delete']) {
//		if ($objcompany->deleteLastBill($_POST['delete'], $_SESSION['sess_user_name'])) {
    if ($objcompany->deleteBill($_POST['delete'])) {
         //added by janken 12/09/2014
        $curl_obj = new Rest_Curl();

        if(ENABLE_FIS){
            $curl_obj->inpatientDeleteBillEntry($_POST['delete']);
        }
        
        $sWarning = 'Posted bill deleted!';
    }
    else {
        $sWarning = 'Error in bill deletion: '.$db->ErrorMsg();
    }
}

if (isset($_REQUEST['onlybilled']) && $_REQUEST['onlybilled'] == '1')
    $title_sufx = 'billed account(s)';
else
    $title_sufx = 'account(s) with unbilled transactions';

if ($_REQUEST['chkdate']) {
    switch(strtolower($_REQUEST["seldate"])) {
        case "today":
            $search_title = "Today's $title_sufx";
            $filters['DATETODAY'] = "";
            $cutoff = date("Y-m-d");
            break;

        case "specificdate":
            $search_title = "$title_sufx On " . date("F j, Y",strtotime($_REQUEST["specificdate"]));
            $dDate = date("Y-m-d",strtotime($_REQUEST["specificdate"]));
            $filters['DATE'] = $dDate;
            $cutoff = $dDate;
            break;
        default:
            $cutoff = date("Y-m-d");
            break;

    }
}

if ($_REQUEST['chkspecific']) {
    switch(strtolower($_REQUEST["selrecord"])) {
        case "account_name":
            $search_title = "Unbilled account(s) with name having ".$_REQUEST['account_name'];
            $filters["account_name"] = $_REQUEST["account_name"];
            break;
        case "account_no":
            $search_title = "Unbilled account(s) with account no. having ".$_REQUEST['account_no'];
            $filters["account_no"] = $_REQUEST["account_no"];
            break;
    }
}

$filters['c_type'] = $c_type; //added by maimai 11-25-2014

$current_page = $_REQUEST['page'];
if (!$current_page) $current_page = 0;
$list_rows = 15;
switch (strtolower($_REQUEST['jump'])) {
    case 'last':
        $current_page = $_REQUEST['lastpage'];
        break;
    case 'prev':
        if ($current_page > 0) $current_page--;
        break;
    case 'next':
        if ($current_page < $_REQUEST['lastpage']) $current_page++;
        break;
    case 'first':
        $current_page=0;
        break;
}

$_SESSION["current_page"] = $current_page;
$result = $objcompany->getOutstandingCompanyAccounts($filters, $list_rows * $current_page, $list_rows, (isset($_REQUEST['onlybilled']) && $_REQUEST['onlybilled'] == '1'));
$rows = "";
$last_page = 0;
$count=0;
if ($result) {
    $rows_found = $objcompany->FoundRows();
    if ($rows_found) {
        $last_page = floor($rows_found / $list_rows);
        $first_item = $current_page * $list_rows + 1;
        $last_item = ($current_page+1) * $list_rows;
        if ($last_item > $rows_found) $last_item = $rows_found;
        $nav_caption = "Showing ".number_format($first_item)."-".number_format($last_item)." out of ".number_format($rows_found)." record(s)";
    }

    while ($row = $result->FetchRow()) {
        if (!$records_found) $records_found = TRUE;

        $bill_nr = $row['comp_bill_nr'];
        $acct_id = $row["comp_id"];
        $acct_short = $row["comp_name"];
        $acct_name =  $row["comp_full_name"];
        $acct_unbilled = $row["unbilled"];
        $acct_billed = $row["billed"];
        $is_corpacct = 1;
        $flag = $row['flag'];
        $first_th = "Account ID";

        if (isset($_REQUEST['onlybilled']) && $_REQUEST['onlybilled'] == '1') {
            if($acct_billed != 0)
            {
                $header = "Billed";
                $first_th = "Bill Number";
                $rows .= "<tr class=\"$class\">
					<td width=\"15%\">
				    <input type=\"hidden\" type=\"text\" size=\"30\" value=\"\"/>"
                    .$bill_nr."
					</td>
					<td width=\"10%\">".$acct_short."</td>
				    <td width=\"36%\">".$acct_name."</td>
					<td width=\"9%\" align=\"center\" id=\"acct_".$acct_id."\">".$acct_billed."</td>
					<td width=\"3%\" align=\"center\">";
            }
        }
        else {
            if($acct_unbilled != 0)
            {
                $header = "Unbilled";
                $first_th = "Account ID";
                $rows .= "<tr class=\"$class\">
												<td width=\"15%\">
													 <input type=\"hidden\" type=\"text\" size=\"30\" value=\"\"/>"
                                                    .$acct_id."
												</td>
												<td width=\"10%\">".$acct_short."</td>
												<td width=\"36%\">".$acct_name."</td>
												<td width=\"9%\" align=\"center\" id=\"acct_".$acct_id."\">".$acct_unbilled."</td>
												<td width=\"3%\" align=\"center\">";
            }
        }


        if (isset($_REQUEST['onlybilled']) && $_REQUEST['onlybilled'] == '1') {
            if($acct_billed != 0)
            {
                $rows .= "<a title=\"View Bill!\" href=\"#\">
							    <img class=\"segSimulatedLink\" src=\"".$root_path."images/cashier_view.gif\" border=\"0\" align=\"absmiddle\" onclick=\"computeBillTray('".$acct_id."','".$cutoff."','".$bill_nr."');\" />
						 </a>";
                if($flag==''){

                    if($c_type == 'person'){
                         $rows .="<a title=\"Paid Bill\" href=\"#\">
                                    <img class=\"segSimulatedLink\" src=\"".$root_path."images/cashier_edit_3.gif\" border=\"0\" align=\"absmiddle\" onclick=\"paidBill('".$bill_nr."');\" />
                             </a>";
                     }

                    $rows .="<a title=\"Delete Bill!\" href=\"#\">
							    <img class=\"segSimulatedLink\" src=\"".$root_path."images/cashier_delete.gif\" border=\"0\" align=\"absmiddle\" onclick=\"deleteBill('".$bill_nr."');\" />
						 </a>";
                }

            }
        }
        else {
            if($acct_unbilled != 0)
            {
                $rows .= "		<a title=\"Compute bill!\" href=\"javascript:void(0)\">
													<img class=\"segSimulatedLink\" src=\"".$root_path."images/cashier_reports.gif\" border=\"0\" align=\"absmiddle\" onclick=\"computeBillTray('".$acct_id."','".$cutoff."','".$bill_nr."');\" />
												</a>
										 </td>";
            }
        }
        $count++;
    }
}

if (!$rows) {
    $records_found = FALSE;
    $rows .= '        <tr><td colspan="8">No unbilled accounts found ...</td></tr>';
}

ob_start();
?>
    <form action="<?= $thisfile.URL_APPEND."&target=list&clear_ck_sid=".$clear_ck_sid.$src_link."&c_type=".$_GET['c_type'] ?>" method="post" name="suchform" onSubmit="return validate()">
    <div style="margin:5px;font-weight:bold;color:#660000"><?= $sWarning ?></div>
    <div style="width:70%">
        <table width="100%" border="0" style="font-size: 12px; margin-top:5px" cellspacing="2" cellpadding="2">
            <tbody>
            <tr>
                <td align="left" class="jedPanelHeader" ><strong>Search options</strong></td>
            </tr>
            <tr>
                <td nowrap="nowrap" align="left" class="jedPanel">
                    <table width="100%" border="0" cellpadding="2" cellspacing="0">
                        <tr>
                            <td width="50" align="right">
                                <input type="checkbox" id="chkspecific" name="chkspecific" onclick="selrecordOnChange(); keepFilters(0);" <?= ($_REQUEST['chkspecific'] ? 'checked' : '') ?>/>
                            </td>
                            <td width="5%" align="right" nowrap="nowrap">Account Name/ID:</td>
                            <td>
                                <script language="javascript" type="text/javascript">
                                    <!--
                                    function selrecordOnChange() {
                                        var optSelected = $('selrecord').options[$('selrecord').selectedIndex];
                                        var spans = document.getElementsByName('selrecordoptions');

                                        for (var i=0; i<spans.length; i++) {
                                            if (optSelected) {
                                                if (spans[i].getAttribute("segOption") == optSelected.value) {
                                                    spans[i].style.display = $('chkspecific').checked ? "" : "none";
                                                }
                                                else
                                                    spans[i].style.display = "none";
                                            }
                                        }

                                        disableNav()
                                    }
                                    -->
                                </script>
                                <select class="jedInput" name="selrecord" id="selrecord" onchange="selrecordOnChange(); keepFilters(0);"/>
                                <option value="account_name" <?= $_REQUEST["selrecord"]=="account_name" ? 'selected="selected"' : '' ?>>Account Name</option>
                                <option value="account_no" <?= $_REQUEST["selrecord"]=="account_no" ? 'selected="selected"' : '' ?>>Account No.</option>
                                </select>
                           </td>
                            <td>
																<span name="selrecordoptions" segOption="account_name" <?= ($_REQUEST["selrecord"]=="account_name") && $_REQUEST['chkspecific'] ? '' : 'style="display:none"' ?>>
																		<input class="jedInput" name="account_name" id="account_name" onblur="keepFilters(0);" type="text" size="30" value="<?= $_REQUEST['account_name'] ?>"/>
																		<input type="hidden" name="account_name_old" value="<?= $_REQUEST['account_name'] ?>" />
																</span>
																<span name="selrecordoptions" segOption="account_no" <?= ($_REQUEST["selrecord"]=="account_no") && $_REQUEST['chkspecific'] ? '' : 'style="display:none"' ?>>
																		<input class="jedInput" name="account_no" id="account_no" onblur="keepFilters(0);" type="text" size="30" value="<?= $_REQUEST['account_no'] ?>"/>
																</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="5%" align="right"><input type="checkbox" id="chkdate" name="chkdate" <?= ($_REQUEST['chkdate'] ? 'checked' : '') ?> onclick="seldateOnChange();keepFilters(1);"/></td>
                            <td width="15%" nowrap="nowrap" align="left">Cut-off Date:</td>
                            <td width="20%" align="left">
                                <script language="javascript" type="text/javascript">
                                    <!--
                                    function seldateOnChange() {
                                        var filter = '';

                                        var optSelected = $('seldate').options[$('seldate').selectedIndex]
                                        var spans = document.getElementsByName('seldateoptions')
//				$('btnPrint').style.display = "none";
                                        for (var i=0; i<spans.length; i++) {
                                            if (optSelected) {
                                                if (spans[i].getAttribute("segOption") == optSelected.value) {
                                                    spans[i].style.display = $('chkdate').checked ? "" : "none";

                                                    if (optSelected.value == "specificdate")
                                                        filter = $(optSelected.value).value
//										else {
//												filter = new Array($('between1').value, $('between2').value);
//												$('btnPrint').style.display = "";
//										}
                                                }
                                                else
                                                    spans[i].style.display = "none"
                                            }
                                        }

                                        disableNav()
                                    }
                                    -->
                                </script>
                                <select class="jedInput" id="seldate" name="seldate" onchange="seldateOnChange(); keepFilters(1);">
                                    <option value="today" <?= $_REQUEST["seldate"]=="today" ? 'selected="selected"' : '' ?>>Today</option>
                                    <option value="specificdate" <?= $_REQUEST["seldate"]=="specificdate" ? 'selected="selected"' : '' ?>>Specific date</option>
                                </select>
                            </td>
                            <td>
																<span name="seldateoptions" segOption="specificdate" <?= ($_REQUEST["seldate"]=="specificdate") && $_REQUEST['chkdate'] ? '' : 'style="display:none"' ?>>
																		<input onchange="keepFilters(1);" class="jedInput" name="specificdate" id="specificdate" type="text" size="8" value="<?= $_REQUEST['specificdate'] ?>"/>
																		<img src="<?= $root_path ?>gui/img/common/default/show-calendar.gif" id="tg_specificdate" align="absmiddle" style="cursor:pointer"  />
																		<script type="text/javascript">
                                                                            Calendar.setup ({
                                                                                inputField : "specificdate", ifFormat : "<?= $phpfd ?>", showsTime : false, button : "tg_specificdate", singleClick : true, step : 1
                                                                            });
                                                                        </script>
																</span>
                            </td>
                        </tr>
                        <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                        <tr>
                            <td></td>
                            <td>
                                <input type="submit" style="cursor:pointer" value="Search"  class="jedButton"/>&nbsp;
                                <!--																<input type="button" id="btnPrint" style="cursor:pointer; display:none" value="Print Summary Report of Transmittals"  class="jedButton" onclick="printTransmittalRep();"/>-->
                            </td>
                            <td colspan="2" align="left">
                                <input style="valign:bottom" type="checkbox" id="chkbilled" name="chkbilled" <?= (isset($_REQUEST['onlybilled']) && $_REQUEST['onlybilled'] == '1') ? 'checked' : '' ?> onclick="showBilled(this);"/>
                                <span style="valign:top">Billed Accounts</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div style="width:72%">
        <table width="98%" class="segContentPaneHeader" style="margin-top:10px">
            <tr><td>
                    <h1>
                        Search result:
                        <?php
                        echo $search_title;  ?></h1></td>
            </tr>
        </table>
        <div class="segContentPane">
            <table id="" class="jedList" width="98%" border="0" cellpadding="0" cellspacing="0">
                <thead>
                <tr class="nav">
                    <th colspan="9">
                        <div id="pageFirst" class="<?= ($current_page > 0) ? 'segSimulatedLink' : 'segDisabledLink' ?>" style="float:left" onclick="jumpToPage(FIRST_PAGE)">
                            <img title="First" src="<?= $root_path ?>images/start.gif" border="0" align="absmiddle"/>
                            <span title="First">First</span>
                        </div>
                        <div id="pagePrev" class="<?= ($current_page > 0) ? 'segSimulatedLink' : 'segDisabledLink' ?>" style="float:left" onclick="jumpToPage(PREV_PAGE)">
                            <img title="Previous" src="<?= $root_path ?>images/previous.gif" border="0" align="absmiddle"/>
                            <span title="Previous">Previous</span>
                        </div>
                        <div id="pageShow" style="float:left; margin-left:10px">
                            <span><?= $nav_caption ?></span>
                        </div>
                        <div id="pageLast" class="<?= ($current_page < $last_page) ? 'segSimulatedLink' : 'segDisabledLink' ?>" style="float:right" onclick="jumpToPage(LAST_PAGE)">
                            <span title="Last">Last</span>
                            <img title="Last" src="<?= $root_path ?>images/end.gif" border="0" align="absmiddle"/>
                        </div>
                        <div id="pageNext" class="<?= ($current_page < $last_page) ? 'segSimulatedLink' : 'segDisabledLink' ?>" style="float:right" onclick="jumpToPage(NEXT_PAGE)">
                            <span title="Next">Next</span>
                            <img title="Next" src="<?= $root_path ?>images/next.gif" border="0" align="absmiddle"/>
                        </div>
                    </th>
                </tr>
                <tr>
                    <th width="8%"><?= $first_th ?></th>
                    <th width="15%">Short Name</th>
                    <th width="36%">Account Name</th>
                    <th width="9%"><?= $header ?></th>
                    <th width="6%" colspan="2">Options</th>
                </tr>
                </thead>
                <tbody>
                <?= $rows ?>
                </tbody>
            </table>
            <br />
        </div>
    </div>

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

    <input type="hidden" name="sid" value="<?php echo $sid ?>">
    <input type="hidden" name="lang" value="<?php echo $lang ?>">
    <input type="hidden" name="userck" value="<?php echo $userck ?>">
    <input type="hidden" name="cat" value="<?php echo $cat?>">
    <input type="hidden" name="userck" value="<?php echo $userck?>">
    <input type="hidden" name="dstamp" value="<?php echo  str_replace("_",".",date(Y_m_d))?>">
    <input type="hidden" name="tstamp" value="<?php echo  str_replace("_",".",date(H_i))?>">
    <input type="hidden" name="lockflag" value="<?php echo  $lockflag?>">

    <input type="hidden" id="delete" name="delete" value="" />
    <input type="hidden" id="iscorpacct" name="iscorpacct" value="" />
    <input type="hidden" id="onlybilled" name="onlybilled" value="" />
    <input type="hidden" id="page" name="page" value="<?= $current_page ?>" />
    <input type="hidden" id="lastpage" name="lastpage"  value="<?= $last_page ?>" />
    <input type="hidden" id="jump" name="jump">
    <input type="hidden" id="applied" name="applied" value="1">
    <input type="hidden" id="root_path" name="root_path" value="<?php echo $root_path ?>" />
    <input type="hidden" id="seg_URL_APPEND" name="seg_URL_APPEND" value="<?=URL_APPEND?>"  />
    <input type="hidden" id="fill_up" name="fill_up" value="">
    <input type="hidden" id="c_type" name="c_type" value="<?=$c_type?>">
    </form>
<?php

$sTemp = ob_get_contents();
ob_end_clean();

# Assign the form template to mainframe
// $smarty->assign('bgcolor',"class=\"yui-skin-sam\"");
$smarty->assign('sMainFrameBlockData',$sTemp);

/**
 * show Template
 */
$smarty->display('common/mainframe.tpl');
?>