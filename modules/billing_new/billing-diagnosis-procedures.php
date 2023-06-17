<?php
#created by VAN 06-21-08
# Modified by LST - 03.29.2009 ---- to allow user at billing department to add ICD.
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path."modules/billing_new/ajax/icd_icp.common.php");
require($root_path.'include/inc_environment_global.php');
/**
* CARE2X Integrated Hospital Information System Deployment 2.1 - 2004-10-02
* GNU General Public License
* Copyright 2002,2003,2004,2005 Elpidio Latorilla
* elpidio@care2x.org,
*
* See the file "copy_notice.txt" for the licence notice
*/
define('NO_2LEVEL_CHK',1);
define('LANG_FILE','products.php');
$local_user='ck_prod_db_user';
require_once($root_path.'include/inc_front_chain_lang.php');

//$db->debug=1;

$thisfile=basename(__FILE__);

$cat = "pharma";
$title="Patient Records::History";
$breakfile=$root_path."modules/registration_admission/seg-close-window.php".URL_APPEND."&userck=$userck";
$imgpath=$root_path."pharma/img/";

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
 $smarty->assign('sToolbarTitle',"$title");

 # href for the back button
// $smarty->assign('pbBack',$returnfile);

 # href for the help button
 $smarty->assign('pbHelp',"javascript:gethelp('products_db.php','search','$from','$cat')");

 # href for the close button
 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('sWindowTitle',"$title");

 # Assign Body Onload javascript code
 $smarty->assign('sOnLoadJs','onLoad="preSet();"');
 $smarty->assign('sOnUnLoadJs',"javascript:if (window.parent.myClick) window.parent.myClick(); else window.parent.cClick();");

 require_once($root_path.'include/care_api_classes/class_encounter.php');
 $enc_obj=new Encounter;

 require_once($root_path.'include/care_api_classes/class_personell.php');
 $pers_obj=new Personell;

 require_once($root_path.'include/care_api_classes/class_department.php');
 $dept_obj=new Department;

 require_once($root_path.'include/care_api_classes/class_person.php');
 $person_obj=new Person();

 require_once($root_path.'include/care_api_classes/class_ward.php');
 $ward_obj = new Ward;

 require_once($root_path.'include/care_api_classes/billing/class_ops_new.php');
 $ops_obj = new SegOps();

$pid_transmit = 0;

 $pid = $_GET['pid'];

 $encounter_nr = $_GET['encounter_nr'];
//added by daryl
 if (($pid == 'undifined') || empty($pid) || ($pid == "") || ($pid == 'transmittal')){
	$pid = $person_obj->getpidfrmenc($encounter_nr);
	$pid_transmit = 1;
 }

 $frombilling = $_GET['frombilling'];
 $billDate = $_GET['billDate'];

$encInfo = $enc_obj->getPatientEncounter($encounter_nr);
 $person = $person_obj->getAllInfoArray($pid);
 $hearingTest = $ops_obj->fetchNewbornHearingTestDetails($encounter_nr);

# echo "sql = ".$person_obj->sql;
 extract($person);

 $name = $name_first.' '.$suffix." ".$name_2." ".$name_middle." ".$name_last;

 # Collect javascript code
 ob_start()

?>
<!---------added by VAN----------->
<!-- OLiframeContent(src, width, height) script:
 (include WIDTH with its parameter equal to width, and TEXTPADDING,0, in the overlib call)
-->
<script type="text/javascript" src="<?=$root_path?>js/overlibmws/iframecontentmws.js"></script>

<!-- Core module and plugins:
-->
<script type="text/javascript" src="<?=$root_path?>js/overlibmws/overlibmws.js"></script>
<script type="text/javascript" src="<?=$root_path?>js/overlibmws/overlibmws_draggable.js"></script>
<script type="text/javascript" src="<?=$root_path?>js/overlibmws/overlibmws_filter.js"></script>
<script type="text/javascript" src="<?=$root_path?>js/overlibmws/overlibmws_overtwo.js"></script>
<script type="text/javascript" src="<?=$root_path?>js/overlibmws/overlibmws_scroll.js"></script>
<script type="text/javascript" src="<?=$root_path?>js/overlibmws/overlibmws_shadow.js"></script>
<script type="text/javascript" src="<?=$root_path?>js/overlibmws/overlibmws_modal.js"></script>


<!-- ICD10 ENTRY BLOCK -->
<style type="text/css">
/*margin and padding on body element
	can introduce errors in determining
	element position and are not recommended;
	we turn them off as a foundation for YUI
	CSS treatments. */
body {
	margin:0;
	padding:0;
}
</style>

<script type="text/javascript" src="<?= $root_path ?>js/yui-2.7/yahoo/yahoo.js"></script>
<link rel="stylesheet" type="text/css" href="<?= $root_path ?>js/yui-2.7/fonts/fonts-min.css" />
<link rel="stylesheet" type="text/css" href="<?= $root_path ?>js/yui-2.7/autocomplete/assets/skins/sam/autocomplete.css" />
<link rel="stylesheet" type="text/css" href="<?= $root_path ?>css/seg/dmhi.css" /> <!-- Added by jeff 04-26-18 -->
<script type="text/javascript" src="<?= $root_path ?>js/yui-2.7/yahoo-dom-event/yahoo-dom-event.js"></script>

<script type="text/javascript" src="<?= $root_path ?>js/yui-2.7/connection/connection-min.js"></script>
<script type="text/javascript" src="<?= $root_path ?>js/yui-2.7/animation/animation-min.js"></script>
<script type="text/javascript" src="<?= $root_path ?>js/yui-2.7/datasource/datasource-min.js"></script>
<script type="text/javascript" src="<?= $root_path ?>js/yui-2.7/autocomplete/autocomplete-min.js"></script>
<link rel="stylesheet" href="<?= $root_path ?>js/jquery/themes/seg-ui/jquery.ui.all.css" type="text/css" /> 
<script type='text/javascript' src="<?=$root_path?>js/jquery/jquery-1.8.2.js"></script>
<script type='text/javascript' src="<?=$root_path?>js/jquery/ui/jquery-ui-1.9.1.js"></script>
<script type="text/javascript">
	var $j = jQuery.noConflict();
</script>
<script type="text/javascript" src="js/billing-main-new.js?t=<?=time();?>"></script>
<script type="text/javascript" src="js/billing-diagnosis-procedures.js?t=<?=time()?>"></script>


<!--begin custom header content for this example-->
<style>
    .ui-autocomplete {
    max-height: 100px;
    max-width: 500px;
    overflow-y: auto;
    font-size: 12px;
    /* prevent horizontal scrollbar */
    overflow-x: hidden;
}
/* IE 6 doesn't support max-height
* we use height instead, but this forces the menu to always be this tall
*/
    * html .ui-autocomplete {
    height: 100px;
}
</style>

<script language="javascript" >
preset();
//initAccomPrompt();

function preSet(){
	startAJAXSearch('search',0);
}

var currentPage=0, lastPage=0;
var FIRST_PAGE=1, PREV_PAGE=2, NEXT_PAGE=3, LAST_PAGE=4, SET_PAGE=0;
var AJAXTimerID=0;
var lastSearch="";
var primary = 0;

function startAJAXSearch(searchID, page) {
	var searchEL = $(searchID);
	var encNr = '<?= $encounter_nr; ?>';
	var billFrmDate = '<?= $encInfo["admission_dt"]; ?>';
	var billDate = '<?= $billDate; ?>';
	var pid_transmit = '<?= $pid_transmit; ?>';
	

	encounter_nr = document.getElementById('encounter_nr').value;

	if (searchEL) {
		searchEL.style.color = "#0000ff";
		if (AJAXTimerID) clearTimeout(AJAXTimerID);
		$("ajax-loading").style.display = "";
		$("DiagnosisList-body").style.display = "none";
        xajax_populateProcedureList(encNr,billFrmDate,billDate,pid_transmit);
        AJAXTimerID = setTimeout("xajax_populateDiagnosisList('"+encounter_nr+"','"+searchID+"',"+page+",<?=$frombilling;?>)",100);
		lastSearch = searchEL.value;
	}
}

function endAJAXSearch(searchID) {
	var searchEL = $(searchID);
	if (searchEL) {
		$("ajax-loading").style.display = "none";
		$("DiagnosisList-body").style.display = "";
		searchEL.style.color = "";
	}
}

function clearList(listID) {
	// Search for the source row table element
	var list=$(listID),dRows, dBody;
	if (list) {
		dBody=list.getElementsByTagName("tbody")[0];
		if (dBody) {
			dBody.innerHTML = "";
			return true;	// success
		}
		else return false;	// fail
	}
	else return false;	// fail
}

function addslashes(str) {
	str=str.replace("'","\\'");
	return str;
}

function trimString(objct){
//    alert("inside frunction trimString: objct = '"+objct+"'");
	objct.value.replace(/^\s+|\s+$/g,"");
	objct.value = objct.value.replace(/\s+/g,"");
}

function editAltDesc(id) {
	$("descalt_"+id).style.display = "";
	$("descmain_"+id).style.display = "none";
	$("descalt_"+id).focus();
}

//added by jasper 06/30/2013
function editAltCode(id) {
    $("codealt_"+id).style.display = "";
    $("codemain_"+id).style.display = "none";
    $("codealt_"+id).focus();
}

function cancelAltCode(id) {
    $("codealt_"+id).style.display = "none";
    $("codemain_"+id).style.display = "";
}

function applyAltCode(e, id, code) {
    var characterCode;
    var enc_nr   = $('encounter_nr').value;
    var user_id  = $('create_id').value;

    if (e) {
        if(e && e.which) { //if which property of event object is supported (NN4)
            characterCode = e.which; //character code is contained in NN4's which property
        }
        else {
            characterCode = e.keyCode; //character code is contained in IE's keyCode property
        }
    }
    else
        characterCode = 13;

    if ( (characterCode == 13) || (isESCPressed(e)) ) {
        var altcode = $("codealt_"+id).value;
        if (altcode != '') {
            $("codemain_"+id).innerHTML = '<a style="cursor:pointer" onclick="editAltCode('+id+')">'+altcode+'</a>';

            // At this point, save the encoded alternate description for the ICD code in table ...
            xajax_saveAltCode(enc_nr, code, altcode, user_id);
            //alert(enc_nr + " " +  code + " " + altcode + " " + user_id);
        }
        $("codealt_"+id).style.display = "none";
        $("codemain_"+id).style.display = "";
    }
}
//added by jasper 06/30/2013

//added by Nick, 3/1/2014
function updateIcdAltCode(e, id, code){
	var characterCode;
    var enc_nr   = $('encounter_nr').value;
    var user_id  = $('create_id').value;

    if (e) {
        if(e && e.which) {
            characterCode = e.which;
        }
        else {
            characterCode = e.keyCode;
        }
    }
    else
        characterCode = 13;

    if ( (characterCode == 13) || (isESCPressed(e)) ) {
        var altcode = $("codealt_"+id).value;
	    $("codemain_"+id).innerHTML = '<a style="cursor:pointer" onclick="editAltCode('+id+')">'+altcode+'</a>';
	    xajax_updateIcdCode(enc_nr, code, altcode, user_id);
        $("codealt_"+id).style.display = "none";
        $("codemain_"+id).style.display = "";
    }
}

function updateIcdAltDesc(e, id, code) {
	var characterCode;
	var enc_nr   = $('encounter_nr').value;
	var user_id  = $('create_id').value;

	if (e) {
		if(e && e.which) {
			characterCode = e.which;
		}
		else {
			characterCode = e.keyCode;
		}
	}
	else
		characterCode = 13;

	if ( (characterCode == 13) || (isESCPressed(e)) ) {
		var altdesc = $("descalt_"+id).value;
		$("descmain_"+id).innerHTML = '<a style="cursor:pointer" onclick="editAltDesc('+id+')">'+altdesc+'</a>';
		xajax_updateIcdDesc(enc_nr, code, altdesc, user_id);
		$("descalt_"+id).style.display = "none";
		$("descmain_"+id).style.display = "";
	}
}
//end nick

function cancelAltDesc(id) {
	$("descalt_"+id).style.display = "none";
	$("descmain_"+id).style.display = "";
}

function isESCPressed(e) {
	var kC  = (window.event) ?    // MSIE or Firefox?
			 event.keyCode : e.keyCode;
	var Esc = (window.event) ?
			27 : e.DOM_VK_ESCAPE // MSIE : Firefox
	return (kC==Esc);
}

function applyAltDesc(e, id, code) {
	var characterCode;
	var enc_nr   = $('encounter_nr').value;
	var user_id  = $('create_id').value;

	if (e) {
		if(e && e.which) { //if which property of event object is supported (NN4)
			characterCode = e.which; //character code is contained in NN4's which property
		}
		else {
			characterCode = e.keyCode; //character code is contained in IE's keyCode property
		}
	}
	else
		characterCode = 13;

	if ( (characterCode == 13) || (isESCPressed(e)) ) {
		var altdesc = $("descalt_"+id).value;
		if (altdesc != '') {
			$("descmain_"+id).innerHTML = '<a style="cursor:pointer" onclick="editAltDesc('+id+')">'+altdesc+'</a>';

			// At this point, save the encoded alternate description for the ICD code in table ...
			xajax_saveAltDesc(enc_nr, code, altdesc, user_id);
		}
		$("descalt_"+id).style.display = "none";
		$("descmain_"+id).style.display = "";
	}
}

//added by jasper 04/23/2013
function moveUp(obj) {
	var p=$(obj).up(1), prev=p.previous();
	if (prev) {
		p.remove();
	  	prev.up().insertBefore(p, prev);
	}
	else {
	  	return false;
	}
}

function editreason(diagnosis_nr){
	var nr = diagnosis_nr;
	var reason = reason;
	var enc = $('encounter_nr').value;
	var PageReferral = "billing-referral-new.php?enc="+enc+"&diagnosis_nr="+nr;
    var htmlReferral = '<iframe style="border: 0px; " src="' + PageReferral + '" width="120%" height=300px></iframe>';
    var dialogReferral = $j('<div></div>')
                .dialog({
                   autoOpen: false,
                   modal: true,
                   show: 'fade',
                   hide:'fade',
                   height: "auto",
                   width: "40%",
                   resizable: true,
                   draggable: true,
                   title: "Referral",
                   position: "top",
                });
	dialogReferral.html(htmlReferral);
    dialogReferral.dialog('open');
}

function moveDown(obj, x) {
	var p=$(obj).up(1), next=p.next();
	if (next) {
	  	next.remove();
	  	p.up().insertBefore(next, p);
	}
	else {
	  	return false;
	}
}

function updateICD()
{
    var oRows = document.getElementById('DiagnosisList-body').getElementsByTagName('tr');
    var iRowCount = oRows.length;
    var enc_nr = $('encounter_nr').value;
    var icd10_values = new Array(iRowCount);
    for (i=0; i<iRowCount; i++) {
       y = i+1;
       icd10_values[i] = new Array(5)
       icd10_values[i]['code'] = document.getElementById('DiagnosisList-body').rows[i].cells[0].childNodes[0].data;
       icd10_values[i]['alt_code'] = document.getElementById('DiagnosisList-body').rows[i].cells[1].childNodes[0].value;
       icd10_values[i]['diag'] = document.getElementById('DiagnosisList-body').rows[i].cells[2].childNodes[0].value;
       icd10_values[i]['clinician'] = document.getElementById('DiagnosisList-body').rows[i].cells[3].childNodes[0].data;
       icd10_values[i]['entry_no'] = y;
    }
    xajax_updateAltICD(icd10_values, enc_nr);
}
//added by jasper 04/23/2013

function addDiagnosisToList(listID, diagnosis_nr, code, description, doctor, bAddedByBilling, altdesc, isprimary, altcode,frmbilling) {
	var list=$(listID), dRows, dBody, rowSrc;
	var i, stmp;
	var frombilling = 1;
	var rate_type = 1;
	if(isprimary == '1') primary = '1';
	if (typeof(bAddedByBilling) == 'undefined') bAddedByBilling = false;
	if (typeof(altdesc) == 'undefined') altdesc = '';
	if (list) {
		dBody=list.getElementsByTagName("tbody")[0];
		dRows=dBody.getElementsByTagName("tr");

		var rows = document.getElementsByName('rows[]');
		if (rows.length == 0) {
			clearList(list);
		}

		if(!altcode)altcode=code;

		if(frmbilling == '0'){
			rmvbutton = 'style="display:none"';
		}else
			rmvbutton = 'style="cursor:pointer"';
			
		if (code) {
			if(code =='P00001' || code =='P00000'){
				alt = (dRows.length%2)+1;
				create_id = $('create_id').value;
				stmp = (bAddedByBilling) ? '<img '+rmvbutton+' title="Remove!" src="../../images/btn_delitem.gif" border="0" onclick="rmvcode('+diagnosis_nr+', \''+isprimary+'\', \''+create_id+'\', \''+code+'\', \''+rate_type+'\');"/>&nbsp;&nbsp;' : '&nbsp;';
				stmp += '<img '+rmvbutton+' title="view/update reason" src="../../images/billing_view_red.gif" border="0" onclick="editreason('+diagnosis_nr+')"/>';
				stmp += '<img '+rmvbutton+' title="Move Up!" src="../../images/cashier_up.gif" border="0" onclick="moveUp(this)"/>';//added by Nick, 4/15/2014
				stmp += '<img '+rmvbutton+' title="Move Down!" src="../../images/cashier_down.gif" border="0" onclick="moveDown(this)"/>';//added by Nick, 4/15/2014
			}else{
				alt = (dRows.length%2)+1;
				create_id = $('create_id').value;
				stmp = (bAddedByBilling) ? '<img '+rmvbutton+' title="Remove!" src="../../images/btn_delitem.gif" border="0" onclick="rmvcode('+diagnosis_nr+', \''+isprimary+'\', \''+create_id+'\', \''+code+'\', \''+rate_type+'\');"/>&nbsp;&nbsp;' : '&nbsp;';
				stmp += '<img '+rmvbutton+' title="Move Up!" src="../../images/cashier_up.gif" border="0" onclick="moveUp(this)"/>';//added by Nick, 4/15/2014
				stmp += '<img '+rmvbutton+' title="Move Down!" src="../../images/cashier_down.gif" border="0" onclick="moveDown(this)"/>';//added by Nick, 4/15/2014
			}
			

			if (frombilling==1){	
				if(!altcode)altcode=code;
				rowSrc = '<tr class="wardlistrow'+alt+'" id="row'+addslashes(diagnosis_nr)+'">'+
								'<input type="hidden" name="rows[]" id="index_'+addslashes(diagnosis_nr)+'" value="'+addslashes(diagnosis_nr)+'" />'+
								'<td align="center">'+code+'</td>'+
                                '<td onclick="editAltCode('+addslashes(diagnosis_nr)+')"><input style="width:95%;display:none;" type="text" id="codealt_'+addslashes(diagnosis_nr)+'" value="'+altcode+'" onFocus="this.select();" onblur="cancelAltCode(\''+addslashes(diagnosis_nr)+'\');" onkeyup="updateIcdAltCode(event,\''+addslashes(diagnosis_nr)+'\', \''+code+'\');">'+
                                    '<span id="codemain_'+addslashes(diagnosis_nr)+'"><a style="cursor:pointer" >'+altcode+'</a></span></td>'+
								'<td onclick="editAltDesc('+addslashes(diagnosis_nr)+')"><input style="width:95%;display:none;" type="text" id="descalt_'+addslashes(diagnosis_nr)+'" value="'+description+'" onFocus="this.select();" onblur="cancelAltDesc(\''+addslashes(diagnosis_nr)+'\');" onkeyup="updateIcdAltDesc(event,\''+addslashes(diagnosis_nr)+'\', \''+code+'\');">'+
									'<span id="descmain_'+addslashes(diagnosis_nr)+'"><a style="cursor:pointer" >'+description+'</a></span></td>'+
								'<td>'+((doctor == '') ? '&nbsp;' : doctor)+'</td><td>'+((isprimary == '1') ? 'Primary' : 'Secondary')+'</td><td>'+ stmp +'</td>'+
								
					 '</tr>';
			}else{
				rowSrc = '<tr class="wardlistrow'+alt+'" id="row'+addslashes(diagnosis_nr)+'">'+
								'<input type="hidden" name="rows[]" id="index_'+addslashes(diagnosis_nr)+'" value="'+addslashes(diagnosis_nr)+'" />'+
								'<td align="center">'+code+'</td>'+
								'<td>'+description+'</td>'+
								'<td>'+doctor+'</td>'+

					 '</tr>';
			}

		}
		else {
			rowSrc = '<tr><td colspan="7">No diagnosis history available ...</td></tr>';
		}

		dBody.innerHTML += rowSrc;
		// alert(diagnosis_nr);

	}
}

function reclassRows(list,startIndex) {
	if (list) {
		var dBody=list.getElementsByTagName("tbody")[0];
		if (dBody) {
			var dRows = dBody.getElementsByTagName("tr");
			if (dRows) {
				for (i=startIndex;i<dRows.length;i++) {
					dRows[i].className = "wardlistrow"+(i%2+1);
				}
			}
		}
	}
}

function removeAddedICD(id, isprimary) {
	var destTable, destRows;
	var table = $('DiagnosisList');
	var rmvRow=document.getElementById("row"+id);
	if(isprimary == '1')
		primary = '0';
	if (table && rmvRow) {
		var rndx = rmvRow.rowIndex-1;
		table.deleteRow(rmvRow.rowIndex);
		if (!document.getElementsByName("rows[]") || document.getElementsByName("rows[]").length <= 0)
			addDiagnosisToList(table, null);
		reclassRows(table,rndx);
	}
	else
		alert(table+' and '+rmvRow);
}

//added by VAN 03-07-08
function setPagination(pageno, lastpage, pagen, total) {
	currentPage=parseInt(pageno);
	lastPage=parseInt(lastpage);
	firstRec = (parseInt(pageno)*pagen)+1;

	if (currentPage==lastPage)
		lastRec = total;
	else
		lastRec = (parseInt(pageno)+1)*pagen;

	if (parseInt(total)==0)
		$("pageShow").innerHTML = '<span>Showing '+(lastRec)+'-'+(lastRec)+' out of '+(parseInt(total))+' record(s).</span>';
	else
		$("pageShow").innerHTML = '<span>Showing '+(firstRec)+'-'+(lastRec)+' out of '+(parseInt(total))+' record(s).</span>';

	$("pageFirst").className = (currentPage>0 && lastPage>0 && total>10) ? "segSimulatedLink" : "segDisabledLink";
	$("pagePrev").className = (currentPage>0 && lastPage>0 && total>10) ? "segSimulatedLink" : "segDisabledLink";
	$("pageNext").className = (currentPage<lastPage && total>10) ? "segSimulatedLink" : "segDisabledLink";
	$("pageLast").className = (currentPage<lastPage && total>10) ? "segSimulatedLink" : "segDisabledLink";
}

function jumpToPage(el, jumpType, set) {
	if (el.className=="segDisabledLink") return false;
	if (lastPage==0) return false;

	switch(jumpType) {
		case FIRST_PAGE:
			if (currentPage==0) return false;
			startAJAXSearch('search',0);
		break;
		case PREV_PAGE:
			if (currentPage==0) return false;
			startAJAXSearch('search',parseInt(currentPage)-1);
		break;
		case NEXT_PAGE:
			if (currentPage >= lastPage) return false;
			startAJAXSearch('search',parseInt(currentPage)+1);
		break;
		case LAST_PAGE:
			if (currentPage >= lastPage) return false;
			startAJAXSearch('search',parseInt(lastPage));
		break;
	}
}

function addCode(create_id,mp) {
	var icd_code = $('icdCode').value;

	var enc_nr   = $('encounter_nr').value;
	var enc_type = $('encounter_type').value;
	var discharge_dt = $('discharge_dt').value;
	var date_d = ((discharge_dt == '') || (discharge_dt == '0000-00-00 00:00:00')) ? $('admission_dt').value : discharge_dt;
	var dr_nr = $('current_dr_nr').value;
	var isprimary = $('is_primary').checked ? 1 : 0;
	var icdDesc = $('icdDesc').value;

	if(isprimary == '1' && primary != '1')
		primary = isprimary;
	else{
		isprimary = '0';
		$('is_primary').checked = 0;
	}
	xajax_addCode(enc_nr, enc_type, date_d, icd_code, dr_nr, create_id, isprimary, mp, icdDesc);
}

function clearICDFields() {
	$('icdCode').value = '';
	$('icdDesc').value = '';
	$('is_primary').attr('checked', false);
}

//added by Nick, 4/15/2014
function updateSequence(){
	var encounter_nr = $('encounter_nr').value;
	var rows = $j('#DiagnosisList-body tr');
	var icd_list = new Array(rows.length);
	for(i=0; i<= rows.length - 1; i++){
		icd_list[i] = $('DiagnosisList-body').rows[i].cells[0].innerHTML;
	}
	xajax_updateIcdSequence(encounter_nr,icd_list);
}

// -->
</script>
<script type="text/javascript" src="<?=$root_path?>js/NumberFormat154.js"></script>
<script type="text/javascript" src="<?=$root_path?>js/jsprototype/prototype.js"></script>
<script type="text/javascript" src="<?=$root_path?>modules/billing_new/js/ICDCodeParticulars.js"></script>
<?php
$xajax->printJavascript($root_path.'classes/xajax');
$sTemp = ob_get_contents();
ob_end_clean();

$smarty->append('JavaScript',$sTemp);

# Buffer page output

ob_start();

?>
<div style="display:block; border:1px solid #8cadc0; overflow-y:hidden;overflow-x:hidden; height:650px; width:99%; background-color:#e5e5e5">
	<table border="1" class="segPanel" cellspacing="2" cellpadding="2" width="99%" align="center">
		<tr>
			<td width="20%">Hospital Number</td>
			<td><strong><?=$pid?></strong></td>
		</tr>
		<tr>
			<td>Patient's Name</td>
			<td><strong><?=$name?></strong></td>
		</tr>
		<tr>
			<td>Case Number</td>
			<td><strong><?=$encounter_nr?></strong></td>
		</tr>
		<!-- Mod by jeff 04-25-18 button for save diagnoisis -->
		<tr>
			<td>Admitting Diagnosis</td>
				<td>
					<textarea style="width:100%; overflow-x:hidden;" id="encDiagnosis" name="encDiagnosis" wrap="physical" cols="53" rows="3" ><?=$encInfo['er_opd_diagnosis']?></textarea>
					<input type="button" name="saveDiagnosis" id="saveDiagnosis" class="dmhi-btn-m" value="SAVE" onclick="saveDiagnosis();" title="Save/Update Diagnosis">
				</td>
		</tr>
	</table>
	<?php
		if ($frombilling){
	?>
	<table width="98%">
		<tr>
		<td width="3%">ICD:</td>
		<td width="15%" nowrap="nowrap" align="left">
				<input type="text" size="10" value="" id="icdCode" name="icdCode" />
		</td>
		<td width="40%" nowrap="nowrap" align="left">
				<input type="text" size="40" value="" id="icdDesc" name="icdDesc" />
		</td>
		<td style="vertical-align:middle;" width="13%">
			<div style="vertical-align:middle;"><input type="checkbox" id="is_primary" name="is_primary" value=""><span style="vertical-align:top;">Primary?</span></div>
		</td>
		<td width="27%">
			<input id="btnAddIcdCode" type="button" value="ADD" onclick="if (checkICDSpecific() && (document.getElementById('icdCode').value!='')){ addCode('<?= $_SESSION['sess_user_name'] ?>','icd'); }" class="dmhi-btn-s">
			<input id="btnUpdateSequence" type="button" value="Update Sequence" onclick="updateSequence()" class="dmhi-btn-s"> <!-- added by Nick, 4/15/2014 -->
		</td>
        <!-- <td width="8%">
            <input id="btnUpdate" style="cursor:pointer" height="10" type="button" value="Update Sequence" onclick="updateICD()" style="width:100%">
        </td> -->
        </tr>
	</table>
	<?php } ?>
	<div style="display:block; border:1px solid #8cadc0; overflow-y:hidden; overflow-x:hidden; width:98%; background-color:#e5e5e5">
	<table class="segList" width="100%" border="0" cellpadding="0" cellspacing="0">
		<thead>
			<tr class="nav">
			<th colspan="9">
				<div id="pageFirst" class="segDisabledLink" style="float:left" onclick="jumpToPage(this,FIRST_PAGE)">
					<img title="First" src="<?= $root_path ?>images/start.gif" border="0" align="absmiddle"/>
					<span title="First">First</span>
				</div>
				<div id="pagePrev" class="segDisabledLink" style="float:left" onclick="jumpToPage(this,PREV_PAGE)">
					<img title="Previous" src="<?= $root_path ?>images/previous.gif" border="0" align="absmiddle"/>
					<span title="Previous">Previous</span>
				</div>
				<div id="pageShow" style="float:left; margin-left:10px">
					<span></span>
				</div>
				<div id="pageLast" class="segDisabledLink" style="float:right" onclick="jumpToPage(this,LAST_PAGE)">
					<span title="Last">Last</span>
					<img title="Last" src="<?= $root_path ?>images/end.gif" border="0" align="absmiddle"/>
				</div>
				<div id="pageNext" class="segDisabledLink" style="float:right" onclick="jumpToPage(this,NEXT_PAGE)">
					<span title="Next">Next</span>
					<img title="Next" src="<?= $root_path ?>images/next.gif" border="0" align="absmiddle"/>
				</div>
				<input id="search" name="search" type="hidden" />
			</th>
		</tr>
		</thead>
	</table>
	</div>

	<div style="display:block; border:1px solid #8cadc0; overflow-y:scroll;overflow-x:hidden; height:180px; width:98%; background-color:#e5e5e5">
		<table id="DiagnosisList" class="segList" width="100%" border="0" cellpadding="0" cellspacing="0" style="overflow:auto">
			<thead>
				<tr>
					<th width="10%" align="left">ICD Code</th>
					<th width='10%' align='left'>Alt. ICD </th>
					<th width="40%" align="left">Description</th>
					<th width="10" align="left">Clinician</th>
					<th width="10%" align="center">Type</th>
					<th width="2%" align="center"></th>
				</tr>
			</thead>
			<tbody id="DiagnosisList-body">
			</tbody>
		</table>
		<img id="ajax-loading" src="<?= $root_path ?>images/loading6.gif" align="absmiddle" border="0" style="display:none"/>
	</div>

	<?php
		if ($frombilling){
	?>
	<table width="98%">
		<tr>
		<td width="3%">ICP:</td>
		<td width="15%" nowrap="nowrap" align="left">
				<input type="text" size="10" value="" id="icpCode" name="icpCode" />
		</td>
		<td width="*" nowrap="nowrap" align="left">
				<input type="text" size="60" value="" id="icpDesc" name="icpDesc" />
				<input type="hidden" name="rvu" id="rvu" value="" />
				<input type="hidden" name="multiplier" id="multiplier" value="" />
				<input type="hidden" name="laterality" id="laterality" value="" />
				<input type="hidden" name="is_special" id="is_special" value="" />
                <input type="hidden" name="is_for_newborn" id="is_for_newborn" value="" />
				<input type="hidden" name="num_sess" id="num_sess" value="1" />
				<input type="hidden" name="for_infirmaries" id="for_infirmaries" value="" />
		</td>
		<!-- <td style="vertical-align:middle;" width="13%">
			<div style="vertical-align:middle;"><input type="checkbox" id="is_primary_icp" name="is_primary_icp" value=""><span style="vertical-align:top;">Primary?</span></div>
		</td> -->
		<td align="left">
			<input id="btnAddIcpCode" type="button" value="ADD" class="dmhi-btn-s">
		</td>
        <!-- <td width="8%">
            <input id="btnUpdate" style="cursor:pointer" height="10" type="button" value="Update Sequence" onclick="updateICD()" style="width:100%">
        </td> -->
        </tr>
	</table>
	<?php } ?>
	<div style="display:block; border:1px solid #8cadc0; overflow-y:hidden; overflow-x:hidden; width:98%; background-color:#e5e5e5">
	<table class="segList" width="100%" border="0" cellpadding="0" cellspacing="0">
		<thead>
			<tr class="nav">
			<th colspan="9">
				<div id="pageFirst" class="segDisabledLink" style="float:left" onclick="jumpToPage(this,FIRST_PAGE)">
					<img title="First" src="<?= $root_path ?>images/start.gif" border="0" align="absmiddle"/>
					<span title="First">First</span>
				</div>
				<div id="pagePrev" class="segDisabledLink" style="float:left" onclick="jumpToPage(this,PREV_PAGE)">
					<img title="Previous" src="<?= $root_path ?>images/previous.gif" border="0" align="absmiddle"/>
					<span title="Previous">Previous</span>
				</div>
				<div id="pageShow" style="float:left; margin-left:10px">
					<span></span>
				</div>
				<div id="pageLast" class="segDisabledLink" style="float:right" onclick="jumpToPage(this,LAST_PAGE)">
					<span title="Last">Last</span>
					<img title="Last" src="<?= $root_path ?>images/end.gif" border="0" align="absmiddle"/>
				</div>
				<div id="pageNext" class="segDisabledLink" style="float:right" onclick="jumpToPage(this,NEXT_PAGE)">
					<span title="Next">Next</span>
					<img title="Next" src="<?= $root_path ?>images/next.gif" border="0" align="absmiddle"/>
				</div>
				<input id="search" name="search" type="hidden" />
			</th>
		</tr>
		</thead>
	</table>
	</div>

	<div style="display:block; border:1px solid #8cadc0; overflow-y:scroll;overflow-x:hidden; height:180px; width:98%; background-color:#e5e5e5">
		<table id="ProcedureList" class="segList" width="100%" border="0" cellpadding="0" cellspacing="0" style="overflow:auto">
			<thead>
				<tr id="">
					<th width="10%" align="left">&nbsp;&nbsp;Code </th>
					<th width="*" align="left">Name/Description</th>
					<th width="5%" align="center">Count</th>
					<th width="5%" align="center">Date</th>
					<th width="10%" align="center">RVU</th>
					<th width="8%" align="center">Multiplier</th>
					<th width="10%" align="center">Charge</th>
					<th width="4%"></th>
				</tr>
			</thead>
			<tbody id="ProcedureList-body">
			</tbody>
		</table>
		<img id="ajax-loading" src="<?= $root_path ?>images/loading6.gif" align="absmiddle" border="0" style="display:none"/>
	</div>

	<div id="opDateBox" style="display:none">
		<div class="bd">
			<form id="fopdate" method="post" action="document.location.href">       
				<table width="100%" class="segPanel">
					<tr><td>
						<table width="100%" border="0">
							<tbody id="opDate-body">
							</tbody>
						</table>
					</td></tr>
				</table>
				<input type="hidden" id="op_code" name="op_code" value="" />         
			</form>
		</div>
        <form id="fopdate_mcp" method="post" action="document.location.href">
            <table width="100%" class="segPanel">
                <tr><td>
                        <table width="100%" border="0">
                            <tbody id="checkup_date_body">
                            </tbody>
                        </table>
                    </td></tr>
            </table>
            <input type="hidden" id="checkup_date1" name="checkup_date1" value="" />
            <input type="hidden" id="checkup_date2" name="checkup_date2" value="" />
            <input type="hidden" id="checkup_date3" name="checkup_date3" value="" />
            <input type="hidden" id="checkup_date4" name="checkup_date4" value="" />
        </form>

    </div>

    <div id="newbornPackage" style="display:none">
        <table id="newborn" class="newbornCarePackage" style="margin-left:50px; margin-top:0px;" width="95%" border="0">
            <tr>
                <td height="35" colspan="3"><strong>f. Newborn Care Package</strong>
                </td>
            </tr>
            <tr>
                <td width="456" rowspan="2">
                    <p>
                        <strong>     Essential Newborn
                            Care</strong></p>
                    <form class="check_signs" id="form_signs" name="form_signs" method="post" action="">
                        <p><strong>Tick Applicable Boxes</strong>
                            <label>
                                <input type="checkbox" name="check_all_" id="check_all" class="checkAll" checked="checked" disabled/>
                                Check All</label>
                        </p>
                        <label>
                            <input type="checkbox" name="check_all[]" id="check_all_0" class="checkall" value="Immediate drying of newborn, etc." checked="checked" disabled/>
                            Immediate drying of newborn, etc.</label>
                        <br />
                        <label>
                            <input type="checkbox" name="check_all[]" id="check_all_1" class="checkall" value="Early skin-to-skin contac" checked="checked" disabled/>
                            Early skin-to-skin contact</label>
                        <br />
                        <label>
                            <input type="checkbox" name="check_all[]" id="check_all_2" class="checkall" value="Timely cord clamping" checked="checked" disabled/>
                            Timely cord clamping</label>
                        <br />
                        <label>
                            <input type="checkbox" name="check_all[]" id="check_all_3" class="checkall" value="Eye prophylaxis" checked="checked" disabled/>
                            Eye prophylaxis</label>
                        <br />
                        <label>
                            <input type="checkbox" name="check_all[]" id="check_all_4" class="checkall" value="Weighing of the newborn" checked="checked" disabled/>
                            Weighing of the newborn</label>
                        <br />
                        <label>
                            <input type="checkbox" name="check_all[]" id="check_all_5" class="checkall" value="Vitamin K administration" checked="checked" disabled/>
                            Vitamin K administration</label>
                        <br />
                        <label>
                            <input type="checkbox" name="check_all[]" id="check_all_6" class="checkall" value="BCG vaccination" checked="checked" disabled/>
                            BCG vaccination</label>
                        <br />
                        <label>
                            <input type="checkbox" name="check_all[]" id="check_all_7" class="checkall" value="Non-seperation of mother/baby for early breastfeeding initiation" checked="checked" disabled/>
                            Non-seperation of mother/baby for early breastfeeding initiation</label>
                        <br />
                        <label>
                            <input type="checkbox" name="check_all[]" id="check_all_8" class="checkall" value="Hepatitis B vaccination" checked="checked" disabled/>
                            Hepatitis B vaccination</label>
                    </form></td>
                <td width="384"><p>
                        <input type="checkbox" style="margin-left:10px;" checked disabled/>
                        <strong> Newborn Screening Test</strong></p>
                    <p><strong style="margin-left:15px;">With O.R Attached?</strong>
                        <input name="check_or" type="checkbox" id="check_or" checked disabled/>
                        <label for="check_or"><strong>Yes</strong></label>
                    </p>
                    <p><strong style="margin-left:15px;">Filter Card No.:
                            <label for="filter_card_no"></label>
                            <input type="text" name="filter_card_no" id="filter_card_no" size="25px;" value= "<?=$hearingTest['filter_card_no']?>"/>
                        </strong></p></td>
            </tr>
            <tr>
                <td><p>
                        <input type="checkbox" name="newborn_hearing" id="newborn_hearing" onclick="disable_enableHearing(this)" style="margin-left:10px;"/>
                        <strong> Newborn Hearing Test</strong></p>
                    <p><strong style="margin-left:15px;">With O.R Attached?</strong>
                        <input type="checkbox" name="check_attached" id="check_attached" checked="checked" disabled="disabled"/>
                        <label for="check_attached"><strong>Yes</strong></label>
                    </p>
                    <p><strong style="margin-left:15px;">NHST Registry Card No.:
                            <label for="registry_card_no"></label>
                            <input type="text" name="registry_card_no" id="registry_card_no" disabled="disabled" size="15px;" value= "<?=$hearingTest['registry_card_no']?>"/>
                        </strong></p>
                    <p><strong style="margin-left:90px;">NHST Result:
                            <label for="nhst_result"></label>
                            <?php
                            $select = "<select name=\"nhst_result\" id=\"nhst_result\" disabled=\"disabled\" style=\"width:100px;\">";
                            $nhst_result = array("NOT APPLICABLE", "PASS", "REFER");
//                            echo "<pre>"; print_r($nhst_result); echo "</pre>";s die();
                            foreach ($nhst_result  as $key => $value) {
                                $selected = '';
                                if($value == $hearingTest['nhst_result'])
                                    $selected = 'selected';
                                $select .= "<option value='".$value."' $selected>".$value."</option>";
                            }
                            $select .= "</select>";

                            echo $select;
                            ?>
<!--                            <select name="nhst_result" id="nhst_result" disabled="disabled" style="width:100px;">-->
<!--                                <option>NOT APPLICABLE</option>-->
<!--                                <option>PASS</option>-->
<!--                                <option>REFER</option>-->
<!--                            </select>-->
                        </strong></p>
                </td>
            </tr>
        </table>
    </div>

	<input type="hidden" name="sid" value="<?php echo $sid?>">
	<input type="hidden" name="lang" value="<?php echo $lang?>">
	<input type="hidden" name="cat" value="<?php echo $cat?>">
	<input type="hidden" name="userck" value="<?php echo $userck ?>">
	<input type="hidden" name="mode" value="search">
	<input type="hidden" name="encounter_nr" id="encounter_nr" value="<?=$encounter_nr?>"/>
	<input type="hidden" name="billdate" id="billdate" value="<?=$billDate?>"/>
	<input type="hidden" name="encounter_type" id="encounter_type" value="<?= $encInfo["encounter_type"] ?>">
	<input type="hidden" name="admission_dt" id="admission_dt" value="<?= $encInfo["admission_dt"] ?>">
	<input type="hidden" name="discharge_dt" id="discharge_dt" value="<?= strftime("%Y-%m-%d", strtotime($encInfo["discharge_date"])). ' '.strftime("%H:%M:%S",  strtotime($encInfo["discharge_time"])) ?>">
	<input type="hidden" name="current_dr_nr" id="current_dr_nr" value="<?= $encInfo["current_att_dr_nr"] ?>">
	<input type="hidden" name="gender" id="gender" value="<?= $encInfo["sex"] ?>">
	<input type="hidden" name="create_id" id="create_id" value="<?= $_SESSION['sess_user_name'] ?>"/>
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
$smarty->assign('class',"class=\"yui-skin-sam\"");        // Added by LST -- 03.29.2009
$smarty->assign('sMainFrameBlockData',$sTemp);

/**
* show Template
*/
$smarty->display('common/mainframe.tpl');
?>
