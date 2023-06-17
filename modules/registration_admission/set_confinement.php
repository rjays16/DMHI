<?php
$encounter_nr = $_GET['encounter_nr'];
$pid = $_GET['pid'];
$MScov = $_POST['MScov'];
$Labcov = $_POST['LabCov'];
$Radcov = $_POST['Radcov'];
$Allcoverage =$_POST['Allcoverage'];
$encounter_nr2 = $_POST['encounter_nr'];
$pid2 = $_POST['pid'];

require('./roots.php');
require($root_path.'include/inc_environment_global.php');
require_once($root_path.'include/inc_date_format_functions.php');
require_once($root_path.'include/care_api_classes/class_person.php');
$person=new Person;
require_once("doctor-dept.common.php");

if ($xajax) {
$xajax->printJavascript($root_path.'classes/xajax');
		
}
// $empty_confinement = 1;

$mode = $_POST['mode'];
$_pid = $_POST['pid'];
$_enc = $_POST['encounter_nr'];
$con_id = $_POST['confineTypeOption'];



if ($mode == "save"){
$person->saveconfinement($pid,$encounter_nr,$con_id);
}

$if_ok=$person->confinement_if_exist($pid,$encounter_nr);

	if (empty($if_ok )){
	$empty_confinement = 1;
	}
	else{
	$empty_confinement = 0;
	}

?>
<body onload="preset();">

<form method="POST" action="set_confinement.php">
 <table border=0 cellpadding=1 cellspacing=1 align="center" >
	<tr class="adm_item">
		<td bgcolor="C0D5BF"><FONT color="#000066" font-style='arial'>Confinement Type:</td></tr>
		<tr>
		<td><select id="confineTypeOption" name="confineTypeOption" style="font:bold 12px Arial">
		<option >- Select Confinement Type -</option></select></td></tr>
		<!-- <td><input color="#000066" type='text' id="Allcoverage" name='Allcoverage' value="<?=$all_amount?>"></input></td> -->
		<input type="hidden" name="encounter_nr" id="encounter_nr" value="<?=$encounter_nr?>"></input>
		<input type="hidden" name="pid" id="pid" value="<?=$pid?>"></input>
	</tr>

	<tr>
		<?php if ($empty_confinement == 1) {?>
		<td colspan='2' align="center"><input  height="23" width="72" type="image" src="../../gui/img/control/default/en/en_savedisc.gif"></td>
		<?php }else{ ?>
		<td colspan='2' align="center"><input  height="23" width="72" type="image" src="../../gui/img/control/default/en/en_update.gif"></td>
		<?php } ?>
	</tr>
</table>	
		<input type="hidden" name="mode" id="mode" value="save"></input>
</form>


<input type="hidden" name="isMScov" id="isMScov" value="<?=$MScov?>"></input>
<input type="hidden" name="isLabcov" id="isLabcov" value="<?=$Labcov?>"></input>
<input type="hidden" name="isRadcov" id="isRadcov" value="<?=$Radcov?>"></input>
<input type="hidden" name="encounter_nr" id="encounter" value="<?=$encounter_nr2?>"></input>
<input type="hidden" name="pid" id="pid" value="<?=$pid2?>"></input>
<input type="hidden" name="isAllcoverage" id="isAllcoverage" value="<?=$all_amount?>"></input>

<script  language="javascript">

function js_setOption(tagId, value){
    // $(tagId).value = value;
    document.getElementById(tagId).value = value;
    // alert(value);
}// end of function js_setOption


function js_ClearOptions(tagId){

   	document.getElementById(tagId).options.length = 0;
}

function js_AddOptions(tagId, text, value){

    var o = new Option(text, value); 
	o.innerHTML = text; 
	document.getElementById(tagId).appendChild(o);
}

function preset(){
var enc = document.getElementById('encounter_nr').value;
var pid = document.getElementById('pid').value;
setconf(enc,pid);
}

function setconf(enc,pid){
xajax_getConfineTypeOption(enc,pid);
}
</script>