<?php
//created by daryl 07/02/14
//certificate of confinement converted to jasper
require_once('roots.php');
require_once($root_path.'include/inc_jasperReporting.php');
require_once($root_path.'include/inc_environment_global.php');
include_once($root_path.'include/inc_date_format_functions.php');

require_once($root_path.'/include/care_api_classes/class_operating_room.php');
$objOP= new OperatingRoom;
include_once($root_path.'include/care_api_classes/class_hospital_admin.php');
$objInfo = new Hospital_Admin();
if($_GET['refno']){
	$refno = $_GET['refno'];
}

if ($row = $objInfo->getAllHospitalInfo()) {
		$hosp_name = strtoupper($row['hosp_name']);
		$hosp_address = strtoupper($row['hosp_addr1']);
		if($hosp_address){
			$hosp_address = $hosp_address;
		}else{
			$hosp_address = strtoupper($row['hosp_addr2']);
		}
		

}else{
		$hosp_name = strtoupper("Davao Mediquest Hospital Incorporated");
		$hosp_address = strtoupper("Lizada St. Toril Davao City");
}



if($refno){
	$infoResult = $objOP->GetPatientInformation($refno);
	while($row = $infoResult->FetchRow()){
		$FirstName = $row['name_first'];
		$LastName = $row['name_last'];
		$MiddleName = $row['name_middle'];
		$pid = $row['pid'];
		$encounter_nr = $row['encounter_nr'];
		$age = $row['age'];
		$Department = $row['department'];
		$Blood_pressure = $row['blood_pressure'];
		$Temperature = $row['temperature'];
		$Pulse = $row['pulse'];
		$Respiration = $row['respiration'];
	}
}
//surgeons
$surgeon = $objOP->getdoctors($refno, 'S');
//assitant surgeon
$AssitantSurgeon = $objOP->getdoctors($refno, 'AS');
//anesthesiologist
$Anesthesiologist = $objOP->getdoctors($refno, 'A');
//circulating Nurse
$CirculatingNurse = $objOP->getdoctors($refno, 'CN');
//Scrub Nurse
$ScrubNurse = $objOP->getdoctors($refno, 'SN');
//weight
$weight = "";
//anesthetic
$anesthetic = "";
//operation Dates
$OperationDates = $objOP->GetOperationDate($refno);

$operationDate = date('M d, Y', strtotime($OperationDates[2]));
$operationStart = date('M d h:i:s a', strtotime($OperationDates[0]));
$operationEnd = date('M d h:i:s a', strtotime($OperationDates[1]));


//get drugs
$drugs = $objOP->GetDrugs($refno);
$total = $objOP->FoundRows();
for ($i=0; $i <= $total ; $i++) { 
	$DrugMeds .= $drugs[$i][1]." ".$drugs[$i][0]."<br />";
}

$OperationDetails = $objOP->GetOperationDetails($refno);

$anastheticDetails = $objOP->GetanastheticDetails($refno);
$total = $objOP->FoundRows();
for ($i=0; $i <= $total ; $i++) { 
	$anesthesia .=$anastheticDetails[$i][0]."<br />";
	$anesthesiaBegun .=$anastheticDetails[$i][1]."<br />";
	$anesthesiaEnd .=$anastheticDetails[$i][2]."<br />";
}

$remarks = $objOP->GetRemarks($refno);


$params = array("first_name"=>$FirstName,
				"last_name"=>$LastName,
				"middle_name"=>$MiddleName,
				"age"=>$age,
				"hrn"=>$pid,
				"dept"=>$Department,
				"bp"=>$Blood_pressure,
				"temp"=>$Temperature,
				"pulse"=>$Pulse,
				"weight"=>$weight,
				"date_of_op"=>$operationDate,
				"respiration"=>$Respiration,
				"hosp_name"=>$hosp_name,
				"hosp_addr"=>$hosp_address);


$druguser = array('try1', 'try2');
$countingdrugs = 2;
$data[0] = '';
$data[0]['surgery'] =$surgeon;
$data[0]['asst_surgeon'] =$AssitantSurgeon;
$data[0]['anes'] = $Anesthesiologist;
$data[0]['circ_nurse'] = $CirculatingNurse;
$data[0]['scrub_nurse'] = $ScrubNurse;
$data[0]['anesthesia'] = $anesthesia;
$data[0]['anesthetic'] = $anesthetic;
$data[0]['start_time'] = $anesthesiaBegun;
$data[0]['end_time'] = $anesthesiaEnd;
$data[0]['drugs'] = $DrugMeds;
$data[0]['calc_fluid'] = "";
$data[0]['blood_replace'] = "";	
$data[0]['blood_loss'] = "";
$data[0]['sutures'] = "";
$data[0]['pre_op_diag'] = $OperationDetails[0];
$data[0]['op_performed'] = $OperationDetails[1];
$data[0]['op_started'] = $operationStart;
$data[0]['op_diag'] = $OperationDetails[2];
$data[0]['remarks'] = "";
$data[0]['op_finished'] = $operationEnd;
$data[0]['remarks'] = $remarks;




showReport('OperationMemo',$params,$data,"PDF");


?>