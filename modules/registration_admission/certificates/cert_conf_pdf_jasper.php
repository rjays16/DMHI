<?php
//created by daryl 07/02/14
//certificate of confinement converted to jasper
require_once('roots.php');
require_once($root_path.'include/inc_jasperReporting.php');
require_once($root_path.'include/inc_environment_global.php');
include_once($root_path.'include/inc_date_format_functions.php');

require_once($root_path.'/include/care_api_classes/class_drg.php');
$objDRG= new DRG;

include_once($root_path.'include/care_api_classes/class_encounter.php');
$enc_obj=new Encounter;

include_once($root_path.'include/care_api_classes/class_cert_med.php');

include_once($root_path.'include/care_api_classes/class_ward.php');
$ward_obj=new Ward;

include_once($root_path.'include/care_api_classes/class_personell.php');
$pers_obj=new Personell;

require_once($root_path.'include/care_api_classes/class_hospital_admin.php');
$objInfo = new Hospital_Admin();


if($_GET['id']){
	if(!($encInfo = $enc_obj->getEncounterInfo($_GET['id']))){
		echo '<em class="warn"> sorry byt the page cannot be displayed!</em>';
		exit();
	}
	extract($encInfo);
}else{
	echo '<em class="warn">Sorry but the page cannot be displayed! <br> Invalid Case Number!</em>';
	exit();
}

	$image_path = '/srv/tomcat/webapps/JavaBridge/resource/images/';

	$params = array("image_path"=> $image_path,
               );

	
//added by daryl
if ($encInfo['age'] == "0 month"){

$birthday = new DateTime($encInfo['date_birth']);
$diff = $birthday->diff(new DateTime());
$months = $diff->format('%m') + 12 * $diff->format('%y');

$sage_ = $months." months";
}
else{
$sage_ = $encInfo['age'];


}

$obj_medCert = new MedCertificate($encounter_nr);
$confCertInfo = $obj_medCert->getConfCertRecord($encounter_nr);
#echo "sql = ".$enc_obj->sql;

$wardName = $ward_obj->WardName($encInfo['current_ward_nr']);

if ($row = $objInfo->getAllHospitalInfo()) {
		$row['hosp_agency'] = strtoupper($row['hosp_agency']);
		$row['hosp_name']   = strtoupper($row['hosp_name']);
	}
	else {
		$row['hosp_country'] = "Republic of the Philippines";
		$row['hosp_agency']  = "DEPARTMENT OF HEALTH";
		$row['hosp_name']    = "Davao Medical Center";
		$row['hosp_addr1']   = "JICA Bldg. JP Laurel Bajada, Davao City";
	}
  

  if($confCertInfo["modify_dt"]!=NULL){
	$date_created = date("m/d/Y",strtotime($confCertInfo["modify_dt"]));
}elseif($confCertInfo["create_dt"]!=NULL){
	$date_created = date("m/d/Y",strtotime($confCertInfo["create_dt"]));
}else
	$date_created = @formatDate2Local(date('Y-m-d'),$date_format);

//Content text
$sex = ($sex == "m")? "MALE":"FEMALE";
#$address = trim($street_name).", ".trim($brgy_name).", ".trim($mun_name)." ".trim($zipcode)." ".trim($prov_name);
if (trim($brgy_name)=='NOT PROVIDED')
	$brgy_name = "";
else
	$brgy_name = trim($brgy_name).", ";

if (trim($mun_name)=='NOT PROVIDED')
	$mun_name = "";

$address = trim($street_name).", ".$brgy_name.trim($mun_name)." ".trim($prov_name);


if (($encounter_type==1)||($encounter_type==2)){
	$fromDate= "".@formatDate2Local($er_opd_datetime,$date_format);
	$name_doctor = $er_opd_admitting_physician_name;
}else{
	$fromDate= "".@formatDate2Local($admission_dt,$date_format);
	$name_doctor = $attending_physician_name;
}

$name_doctor = $confCertInfo['attending_doctor'];

$toDate= "".@formatDate2Local($discharge_dt,$date_format);

if (empty($name_doctor))
	$name_doctor = "_____________________";

if (empty($wardName)){
	#$wardName = "_____________________";
	$wardName = " ";
}else{
	$wardName = ' at '.$wardName.' ward';
}

$fullname = utf8_decode(trim(stripslashes(strtoupper($name_last)))).", ".utf8_decode(trim(stripslashes(strtoupper($name_first)))).' '.utf8_decode(trim(stripslashes(strtoupper($name_middle))));


if ($confCertInfo['is_doc_sig']){
	$docInfo = $pers_obj->getPersonellInfo($confCertInfo['dr_nr']);
	$dr_middleInitial = "";
	if (trim($docInfo['name_middle'])!=""){
		$thisMI=split(" ",$docInfo['name_middle']);
		foreach($thisMI as $value){
			if (!trim($value)=="")
				$dr_middleInitial .= $value[0];
		}
			if (trim($dr_middleInitial)!="")
			$dr_middleInitial = " ".$dr_middleInitial.".";
	}
	$name_doctor = trim($docInfo['name_first'])." ".trim($docInfo['name_2'])." ".trim($dr_middleInitial)." ".trim($docInfo['name_last']);

}
	//passing of data
        $data[0]['hrn']  =  $encInfo['pid'];
        $data[0]['caseno']  =  $encounter_nr;
        $data[0]['date']  =  $date_created;
        $data[0]['name']  =  $fullname;
        $data[0]['age']  = $sage_.' old';
        $data[0]['sex']  =  stripslashes(strtoupper($sex));
        $data[0]['civil_status']  =  ', '.mb_strtoupper($civil_status);
        $data[0]['address']  =  utf8_decode(trim(stripslashes(strtoupper($address))));
        $data[0]['admittedate']  =  date("F d, Y",strtotime($fromDate));
        $data[0]['diagnosis']  =  strtoupper($confCertInfo['admitting_diagnosis']);
        $data[0]['operation']  = '  '. mb_strtoupper($confCertInfo['operation_performed']);
        $data[0]['remarks']  =   '  '.mb_strtoupper($confCertInfo['purpose']);
        $data[0]['requested']  =  '  '. ucwords(mb_strtolower(utf8_decode($confCertInfo['requested_by'])));
        $data[0]['relationtopatient']  = '  '.ucwords(mb_strtolower($confCertInfo['relation_to_patient']));
        $data[0]['attendingphysician']  = mb_strtoupper($name_doctor);
        $data[0]['lic']  = $confCertInfo['lic_no'];
        $data[0]['ptr']  = $confCertInfo['ptr_no'];
        $data[0]['preparedby']  =  strtoupper($confCertInfo['prepared_by']);

         // $data[0]['name'] = '&#09;This is to certify that <b>'.utf8_decode($fullname).
         //            ' , '.$sage_.' old'.', '.stripslashes(strtoupper($sex)).' '.
         //            ', '.mb_strtoupper($civil_status).'</b>  and a resident of <b>'.utf8_decode(trim(stripslashes(strtoupper($address)))).
         //            '</b> was examined, treated <b>'. date("F d, Y",strtotime($fromDate)).
         //            '</b>up to the present with the following finds / diagnosis';

showReport('certificateconfinement',$params,$data,"PDF");


?>