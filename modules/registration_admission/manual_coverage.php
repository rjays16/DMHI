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
//toggle for all cost center or 1 for all cost center
$setmorethanone = '0';
if($setmorethanone){

$check_lab_amount ="";
$check_meds_amount = "";
$check_rad_amount = "";
$manualamount=$person->checkforManual($encounter_nr,$pid);
  if ($row = $manualamount->FetchRow()) {  
         $check_meds_amount = $row["med_amount"];
         $check_lab_amount = $row['lab_amount'];
         $check_rad_amount = $row['rad_amount'];
   }


if ($check_meds_amount == '' && $check_rad_amount =='' && $check_lab_amount==''){
	if($encounter_nr2&&$pid2)
	{
	 	$ok = $person->SaveManual($MScov, $Labcov, $Radcov, $encounter_nr2, $pid, $check='one');
		echo "Successfully Saved!!";
		$manualamount=$person->checkforManual($encounter_nr,$pid);
		  
		  if ($row = $manualamount->FetchRow()) 
		  {  
		         $check_meds_amount = $row["med_amount"];
		         $check_lab_amount = $row['lab_amount'];
		         $check_rad_amount = $row['rad_amount'];
		   }

	}

}else if($MScov != ''|| $Labcov != ''|| $Radcov != '') {
		if($check_meds_amount != $MScov || $check_lab_amount != $Labcov || $check_rad_amount != $Radcov){
			$ok = $person->updateforManual($MScov, $Labcov, $Radcov, $encounter_nr2, $pid2);
			$manualamount=$person->checkforManual($encounter_nr,$pid);
			echo "Successfully updated!";
			if ($row = $manualamount->FetchRow()) {  
		         $check_meds_amount = $row["med_amount"];
		         $check_lab_amount = $row['lab_amount'];
		         $check_rad_amount = $row['rad_amount'];
		   }
		}
}else{

}
   

if ($check_meds_amount || $check_rad_amount || $check_lab_amount){
	$med = $check_meds_amount;
	$Labcov = $check_lab_amount;
	$Radcov = $check_rad_amount;
}

?>
<form method="POST" action="manual_coverage.php">
 <table border=0 cellpadding=1 cellspacing=1 align="center" >
	<tr class="adm_item">
		<td bgcolor="C0D5BF"><FONT color="#000066" font-style='arial'>Meds and Supply Coverage</td>
		<td><input color="#000066" type='text' id="MScov" name='MScov' value="<?=$med?>"></input></td>
	</tr>
	<tr class="adm_item">
		<td bgcolor="C0D5BF"><FONT color="#000066" font-style='arial'>Laboratory Coverage</td>
		<td><input color="#000066" type='text' id="Labcov" name='LabCov' value="<?=$Labcov?>"></input></td>
	</tr>
	<tr class="adm_item">
		<td bgcolor="C0D5BF"><FONT color="#000066" font-style='arial'>Radiology Coverage</td>
		<td><input color="#000066" type='text' id="Radcov" name='Radcov' value="<?=$Radcov?>"></input></td>
		<input type="hidden" name="encounter_nr" id="encounter_nr" value="<?=$encounter_nr?>"></input>
		<input type="hidden" name="pid" id="pid" value="<?=$pid?>"></input>
	</tr>

	<tr>
		<td colspan='2' align="center"><input  height="23" width="72" type="image" src="../../gui/img/control/default/en/en_savedisc.gif"></td>
	</tr>
</table>
</form>

<?
}else{

$check_lab_amount ="";
$manualamount=$person->checkforManual($encounter_nr,$pid);
  if ($row = $manualamount->FetchRow()) {  
         $all_amount = $row["max_coverage"];
   }
if($all_amount==''){
	if($encounter_nr2&&$pid2)
	{
		$ok = $person->SaveManual($MScov='0', $Labcov='0', $Radcov='0', $Allcoverage, $encounter_nr2, $pid, $check =='all');
		echo "Successfully Saved!!";
		$manualamount=$person->checkforManual($encounter_nr,$pid);
		if ($row = $manualamount->FetchRow()) {  
		   $all_amount = $row["max_coverage"];
		}
	}

}else if ($Allcoverage != '' ){
	if($Allcoverage != $all_amount){
		$ok = $person->updateforManual($MScov='0', $Labcov='0', $Radcov='0', $Allcoverage, $encounter_nr2, $pid2);
		echo "Successfully updated!";
		$manualamount=$person->checkforManual($encounter_nr,$pid);
		if ($row = $manualamount->FetchRow()) {  
		   $all_amount = $row["max_coverage"];
		}
	}

}
?>

<form method="POST" action="manual_coverage.php">
 <table border=0 cellpadding=1 cellspacing=1 align="center" >
	<tr class="adm_item">
		<td bgcolor="C0D5BF"><FONT color="#000066" font-style='arial'>Phic Coverage</td>
		<td><input color="#000066" type='text' id="Allcoverage" name='Allcoverage' value="<?=$all_amount?>"></input></td>
		<input type="hidden" name="encounter_nr" id="encounter_nr" value="<?=$encounter_nr?>"></input>
		<input type="hidden" name="pid" id="pid" value="<?=$pid?>"></input>
	</tr>

	<tr>
		<td colspan='2' align="center"><input  height="23" width="72" type="image" src="../../gui/img/control/default/en/en_savedisc.gif"></td>
	</tr>
</table>
</form>

<? }

?>

<input type="hidden" name="isMScov" id="isMScov" value="<?=$MScov?>"></input>
<input type="hidden" name="isLabcov" id="isLabcov" value="<?=$Labcov?>"></input>
<input type="hidden" name="isRadcov" id="isRadcov" value="<?=$Radcov?>"></input>
<input type="hidden" name="encounter_nr" id="encounter" value="<?=$encounter_nr2?>"></input>
<input type="hidden" name="pid" id="pid" value="<?=$pid2?>"></input>
<input type="hidden" name="isAllcoverage" id="isAllcoverage" value="<?=$all_amount?>"></input>