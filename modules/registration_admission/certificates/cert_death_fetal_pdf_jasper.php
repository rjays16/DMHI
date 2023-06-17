<?php
//DARYL 07/12/14
//FETAL DEATH CERTIFICATE CONVERT TO JASPER


require_once('roots.php');
require_once($root_path.'include/inc_jasperReporting.php');
require_once($root_path.'include/inc_environment_global.php');

if (isset($_GET['id']) && $_GET['id']){
	$pid = $_GET['id'];
}

include_once($root_path.'include/care_api_classes/class_person.php');
$person_obj=new Person($pid);

require_once($root_path.'include/care_api_classes/class_personell.php');
$pers_obj=new Personell;

include_once($root_path.'include/care_api_classes/class_address.php');
$address_country = new Address('country');
$address_brgy = new Address('barangay');

require_once($root_path.'include/care_api_classes/class_hospital_admin.php');
$objInfo = new Hospital_Admin();


 $data[0]['city']  =  " ";
 $data[0]['registry_no']  =  " ";
 $data[0]['fetus_fname']  =  " ";
 $data[0]['fetus_mname']  = " ";
 $data[0]['fetus_lname']  =  " ";
 $data[0]['fetus_suffix']  =  " ";
 $data[0]['sex_m']  =  " ";
 $data[0]['sex_f']  =   " ";
 $data[0]['sex_u']  =   " ";
 $data[0]['delivery_day']  =  " ";
 $data[0]['delivery_month']  =  " ";
 $data[0]['delivery_year']  =  " ";
 $data[0]['birth_place']  =   " ";
 $data[0]['delivery_type_1']  =   " ";
 $data[0]['delivery_type_2']  =   " ";
 $data[0]['delivery_type_3']  =   " ";
 $data[0]['birth_rank_first']  =   " ";
 $data[0]['birth_rank_second']  =   " ";
 $data[0]['birth_rank_other']  =   " ";
 $data[0]['birth_rank_other2']  =  " ";
 $data[0]['delivery_method_1']  =   " ";
 $data[0]['delivery_method_2']  =   " ";
 $data[0]['delivery_method_2_info']  =   " ";
 $data[0]['order_birth']  =  " ";
 $data[0]['fetus_weight']  = " ";
 	$data[0]['mother_fname']  =  " ";
 	$data[0]['mother_lname']  =  " ";
 	$data[0]['mother_mname']  =  " ";
 	$data[0]['citizen']  =  " ";
 		$data[0]['religion']  =  " ";
 		$data[0]['mother_occupation']  =  " ";
 		$data[0]['mother_age']  =  " ";
 		$data[0]['m_total_alive']  =  " ";
 		$data[0]['m_still_living']  =  " ";
 		$data[0]['m_now_dead']  =  " ";
 	$data[0]['mother_address']  =  " ";
 	$data[0]['father_fname']  =  " ";
 	$data[0]['father_mname']  =  " ";
 	$data[0]['father_lname']  =  " ";
 	$data[0]['father_na']  =  " ";
 	$data[0]['f_citizenship']  =  " ";
 		$data[0]['father_religion']  =  " ";
	 	$data[0]['father_occupation']  = " ";
	 	$data[0]['father_age']  = " ";
	$data[0]['cause1']  = " ";
	$data[0]['cause2']  = " ";
	$data[0]['cause3']  = " ";
	$data[0]['cause4']  =" ";
	$data[0]['cause5']  = " ";
		$data[0]['fetus_died_1']  =  " ";
		$data[0]['fetus_died_2']  =   " ";
		$data[0]['fetus_died_3']  =   " ";
		$data[0]['attendant_1']  =   " ";
		$data[0]['attendant_2']  =   " ";
		$data[0]['attendant_3']  =  " ";
		$data[0]['attendant_4']  =   " ";
		$data[0]['attendant_5']  =  " ";
		$data[0]['attendant_5_other']  =  " ";
		$data[0]['attendant_6']  =   " ";		
		$data[0]['doctor_name']  =  " ";	
		$data[0]['attendant_title']  =  " ";		
		$data[0]['attendant_address']  =  " ";
		$data[0]['attendant_date_sign']  =  " ";		
		$data[0]['corpse_disposal_1']  =   " ";	
		$data[0]['corpse_disposal_2']  =  " ";
		$data[0]['corpse_disposal_3']  =  " ";
		$data[0]['corpse_disposal_text']  =   " ";	
		$data[0]['burial_permit']  =  " ";				
		$data[0]['burial_date_issued']  =  " ";		
		$data[0]['is_autopsy_1']  =    " ";
		$data[0]['is_autopsy_2']  =    " ";
		$data[0]['informant_name']  =   " ";	
		$data[0]['informant_relation']  =  " ";		
		$data[0]['informant_address']  =  " ";	
		$data[0]['informant_date_sign']  = " ";	
		$data[0]['encoder_name']  =  " ";
		$data[0]['encoder_title']  =  " ";	
		$data[0]['encoder_date_sign']  =   " ";		









if ($row = $objInfo->getAllHospitalInfo()) {
		$row['hosp_agency'] = strtoupper($row['hosp_agency']);
		$row['hosp_name']   = strtoupper($row['hosp_name']);
}
else {
		$row['hosp_country'] = "Republic of the Philippines";
		$row['hosp_agency']  = "DEPARTMENT OF HEALTH";
		$row['hosp_name']    = "BUKIDNON PROVINCIAL HOSPITAL - MALAYBALAY";
		$row['hosp_addr1']   = "Malaybalay, Bukidnon";
		$row['mun_name']     = "Malaybalay";
		$row['prov_name']     = "Bukidnon";
		$row['region_name']     = "Region X";
}

if ($pid){
	if (!($basicInfo=$person_obj->BasicDataArray($pid))){
		echo '<em class="warn"> Sorry, the page cannot be displayed!</em>';
		exit();
	}
	extract($basicInfo);
	$brgy_info = $address_brgy->getAddressInfo($brgy_nr,TRUE);
	if($brgy_info){
		$brgy_row = $brgy_info->FetchRow();
	}
}else{
	echo '<em class="warn">Sorry, the page cannot be displayed! <br> Invalid PID!</em>';
	exit();
}

$birthYear = date("Y",strtotime($date_birth));
$birthMonth = date("F",strtotime($date_birth));
$birthDay = date("d",strtotime($date_birth));

include_once($root_path.'include/care_api_classes/class_cert_death_fetal.php');
$obj_fetalDeathCert = new FetalDeathCertificate($pid);

$fetalDeathCertInfo = $obj_fetalDeathCert->getFetalDeathCertRecord($pid);

if ($fetalDeathCertInfo){
	extract($fetalDeathCertInfo);
	$delivery_method_tmp= substr(trim($fetalDeathCertInfo['delivery_method']),0,1);
	$delivery_method_info = substr(trim($fetalDeathCertInfo['delivery_method']),4);
	$attendant_type_tmp = substr(trim($fetalDeathCertInfo['attendant_type']),0,1);
	$attendant_type_others = substr(trim($fetalDeathCertInfo['attendant_type']),4);
	$death_occurrence = substr(trim($fetalDeathCertInfo['death_occurrence']),0,1);
	$corpse_disposal_tmp= substr(trim($fetalDeathCertInfo['corpse_disposal']),0,1);
	$corpse_disposal_others = substr(trim($fetalDeathCertInfo['corpse_disposal']),4);
	$is_autopsy = substr(trim($fetalDeathCertInfo['is_autopsy']),0,1);
	$tmp_death_cause = unserialize($fetalDeathCertInfo['death_cause']);
}

//passing of data

 $data[0]['city']  =  $row['mun_name']." ";
 $data[0]['registry_no']  =  $registry_nr." ";


 // FETUS
 // 1. NAME
 $data[0]['fetus_fname']  =  mb_strtoupper($name_first)." ";
 $data[0]['fetus_mname']  =  mb_strtoupper($name_middle)." ";
 $data[0]['fetus_lname']  =  mb_strtoupper($name_last)." ";
 $data[0]['fetus_suffix']  =  mb_strtoupper($suffix)." ";

// 2. SEX
	if ($sex=='m')
 		$data[0]['sex_m']  =  "X";
	if ($sex=='f')
 		$data[0]['sex_f']  =  "X";
	if ($sex=='u')
 		$data[0]['sex_u']  =  "X";


 // 3. DATE OF DELIVERY
$arrayMonth = array ("JANUARY","FEBRUARY","MARCH","APRIL","MAY","JUNE","JULY","AUGUST","SEPTEMBER","OCTOBER","NOVEMBER","DECEMBER");

 // $data[0]['delivery_day']  =  $birthDay;
 // $data[0]['delivery_month']  =  $birthMonth;
 // $data[0]['delivery_year']  =  $birthYear;

 $data[0]['delivery_day']  =  $birthDay."   ".mb_strtoupper($birthMonth)."   " . $birthYear;

 // 4. PLACE OF DELIVERY
	if ($birth_place_basic)
		$birth_place = mb_strtoupper(trim($birth_place_basic)).", ";
	else
		$birth_place = trim($row['hosp_name']);

 $data[0]['birth_place']  =  mb_strtoupper($birth_place)." ";

 // 5a. TYPE OF DELIVERY
	if ($birth_type=="1")
 	$data[0]['delivery_type_1']  =  "X";
	if ($birth_type=="2")
 	$data[0]['delivery_type_2']  =  "X";
	if (($birth_type!="")&&($birth_type!="1")&&($birth_type!="2"))
 	$data[0]['delivery_type_3']  =  "X";


// 5b. IF MULTIPLE DELIVERY, FETUS WAS
$birth_rank = " ";
	if ($birth_rank == 'first')
 	$data[0]['birth_rank_first']  =  "X";
	if ($birth_rank == 'second')
 	$data[0]['birth_rank_second']  =  "X";
	else{
 	$data[0]['birth_rank_other']  =  "X";
 	$data[0]['birth_rank_other2']  =  $birth_rank;
	}

// MOTHER
 // 5c. METHOD OF DELIVERY
	if ($delivery_method == '1')
 	$data[0]['delivery_method_1']  =  "X";
	else{
 	$data[0]['delivery_method_2']  =  "X";
 	$data[0]['delivery_method_2_info']  =  $delivery_method_info;
	}

	// 5d.BIRTH ORDER
 	$data[0]['order_birth']  =  $birth_order." ";

 // 5e. WEIGHT OF FETUS
 	$data[0]['fetus_weight']  =  $birth_weight." ";


 	// 6. MAIDEN NAME (MOTHER)
 	$data[0]['mother_fname']  =  mb_strtoupper($m_name_first)." ";
 	$data[0]['mother_lname']  =  mb_strtoupper($m_name_last)." ";
 	$data[0]['mother_mname']  =  mb_strtoupper($m_name_middle)." ";
	
	 // 7.CITIZENSHIP (MOTHER)
	if ($m_citizenship=='PH')
		$m_citizenship = "FILIPINO";
 	$data[0]['citizen']  =  mb_strtoupper($m_citizenship)." ";


 // 8. RELIGION (MOTHER)
	$religion_obj = $obj_fetalDeathCert->getMReligion($m_religion);
	if ($religion_obj['religion_name']=="Not Applicable")
		$religion_obj['religion_name'] = "";

 		$data[0]['religion']  =  mb_strtoupper($religion_obj['religion_name'])." ";

 // 9. OCCUPATION (MOTHER)
		$occupation_obj = $obj_fetalDeathCert->getMOccupation($m_occupation);
	if ($occupation_obj['occupation_name']=="Not Applicable" || $occupation_obj['occupation_name']=="None")
		$occupation_obj['occupation_name'] = "";

 		$data[0]['mother_occupation']  =  mb_strtoupper($occupation_obj['occupation_name'])." ";

 // 10. AGE AT THE TIME OF THIS BIRTH (MOTHER)
 		$data[0]['mother_age']  =  $m_age." ";

 // 11a. Total number of children born alive
 		$data[0]['m_total_alive']  =  $m_total_alive." ";

 // 11b. Number of children still living
 		$data[0]['m_still_living']  =  $m_still_living." ";

 // 11c. Number of children born alive but are now dead
 		$data[0]['m_now_dead']  =  $m_now_dead." ";



 // 12. RESIDENCE (MOTHER)
	$m_address = $m_residence_basic;
	#echo "s= ".$m_residence_basic;
	$brgy = $address_country->getMunicityByBrgy($m_residence_brgy);
	$mun = $address_country->getProvinceByBrgy($m_residence_mun);
	$prov = $address_country->getProvinceInfo($m_residence_prov);

	if ($m_address){
		if ($brgy_name!="NOT PROVIDED")
			$street_name = trim($m_address).", ";
		else
			$street_name = trim($m_address).", ";
	}else
		$street_name = "";



	if ((!($brgy['brgy_name'])) || ($brgy['brgy_name']=="NOT PROVIDED"))
		$brgy_name = "";
	else
		$brgy_name  = trim($brgy['brgy_name']).", ";

	if ((!($mun['mun_name'])) || ($mun['mun_name']=="NOT PROVIDED"))
		$mun_name = "";
	else{
		if ($brgy_name)
			$mun_name = trim($mun['mun_name']);
		#else
			#$mun_name = $mun_name;
	}

	if ((!($prov['prov_name'])) || ($prov['prov_name']=="NOT PROVIDED"))
		$prov_name = "";
	else
		$prov_name = trim($prov['prov_name']);

	if(stristr(trim($mun_name), 'city') === FALSE){
		if ((!empty($mun_name))&&(!empty($prov_name))){
			if ($prov_name!="NOT PROVIDED")
				$prov_name = ", ".trim($prov_name);
			else
				$prov_name = "";
		}else{
			#$province = trim($prov_name);
			$prov_name = "";
		}
	}else
		$prov_name = " ";

	$m_address = $street_name.$brgy_name.$mun_name.$prov_name;
 	$data[0]['mother_address']  =  $m_now_dead." ";

// FATHER
 //13. NAME
	if ((($f_name_first=="N/A") || ($f_name_first=="n/a"))&&(($f_name_middle=="N/A") || ($f_name_middle=="n/a"))&&(($f_name_last=="N/A") || ($f_name_last=="n/a"))){
		$pdf->SetXY($x+70, $y+108);
 		$data[0]['father_na']  =  "N/A";
	}else{
		#$pdf->SetXY($x+30, $y+108);
 	$data[0]['father_fname']  =  mb_strtoupper($f_name_first)." ";
 	$data[0]['father_mname']  =  mb_strtoupper($f_name_middle)." ";
 	$data[0]['father_lname']  =  mb_strtoupper($f_name_last)." ";
	}


 //14.CITIZENSHIP (FATHER)
	if ($f_citizenship=='PH')
		$f_citizenship = "FILIPINO";

	if (($f_citizenship=="n/a")||($f_citizenship=="N/A")||((($f_name_first=="N/A") || ($f_name_first=="n/a"))&&(($f_name_middle=="N/A") || ($f_name_middle=="n/a"))&&(($f_name_last=="N/A") || ($f_name_last=="n/a"))))
		$f_citizenship = "";
 	$data[0]['f_citizenship']  =  mb_strtoupper($f_citizenship)." ";

 //15. RELIGION (FATHER)
		$religion_obj = $obj_fetalDeathCert->getFReligion($f_religion);
	if ($religion_obj['religion_name']=="Not Applicable")
		$religion_obj['religion_name'] = "";
 		$data[0]['father_religion']  =  mb_strtoupper($religion_obj['religion_name'])." ";

 //16. OCCUPATION (FATHER)
		$occupation_obj = $obj_fetalDeathCert->getFOccupation($f_occupation);
	if ($occupation_obj['occupation_name']=="Not Applicable" || $occupation_obj['occupation_name']=="None")
		$occupation_obj['occupation_name'] = "";
	 	$data[0]['father_occupation']  = mb_strtoupper($occupation_obj['occupation_name'])." ";


 //17. AGE AT THE TIME OF THIS BIRTH (FATHER)
	if ($f_age==0)
		$f_age = "";
	 	$data[0]['father_age']  = $f_age." ";
	

//18. DATE AND PLACE OF MARRIAGE OF PARENTS
	if (($parent_marriage_date!='0000-00-00') && (!empty($parent_marriage_date))){
		#if ($parent_marriage_date){
		if (($parent_marriage_place)||($parent_marriage_place!='N/A'))
			$parent_marriage_info_tmp = date("F d, Y",strtotime($parent_marriage_date))." at ".$parent_marriage_place;
		else
			$parent_marriage_info_tmp = date("F d, Y",strtotime($parent_marriage_date));
	}else{

		$parent_marriage_info_tmp = " ";
	}
	$data[0]['parent_marriage_info_tmp']  = mb_strtoupper($parent_marriage_info_tmp)." ";

// MEDICAL CERTIFICATE --- --- --- --- ---
// 19. CAUSES OF FETAL DEATH
	$data[0]['cause1']  = mb_strtoupper($tmp_death_cause['cause1'])." ";
	$data[0]['cause2']  = mb_strtoupper($tmp_death_cause['cause2'])." ";
	$data[0]['cause3']  = mb_strtoupper($tmp_death_cause['cause3'])." ";
	$data[0]['cause4']  = mb_strtoupper($tmp_death_cause['cause4'])." ";
	$data[0]['cause5']  = mb_strtoupper($tmp_death_cause['cause5'])." ";

	 // 20. FETUS DIED

	if ($death_occurrence=="1")
		$data[0]['fetus_died_1']  =  "X";
	if ($death_occurrence=="2")
		$data[0]['fetus_died_2']  =  "X";
	if ($death_occurrence=="3")
		$data[0]['fetus_died_3']  =  "X";
	
	 // 21. LENGTH OF PREGNANCY
	$data[0]['pregnancy_length']  = $pregnancy_length." ";

	// 22a. ATTENDANT
	if ($attendant_type=='1')
		$data[0]['attendant_1']  =  "X";
	if ($attendant_type=='2')
		$data[0]['attendant_2']  =  "X";
	if ($attendant_type=='3')
		$data[0]['attendant_3']  =  "X";
	if ($attendant_type=='4')
		$data[0]['attendant_4']  =  "X";		
	if ($attendant_type=='5'){
		$data[0]['attendant_5']  =  "X";		
		$attendant_type_others = "other";
		$data[0]['attendant_5_other']  =  $attendant_type_others." ";
	}
	if ($attendant_type=='6')
		$data[0]['attendant_6']  =  "X";		
		


 //22b. CERTIFICATION
	#if (($death_time !='00:00:00') && ($death_time!=""))
		if ($death_time!="")
		$death_time = convert24HourTo12HourLocal($death_time);
	else
		$death_time = '';
	if (($attendant_date_sign!='0000-00-00') && ($attendant_date_sign!="")){
		$tempYear = date("Y",strtotime($attendant_date_sign));
		$tempMonth = date("F",strtotime($attendant_date_sign));
		$tempDay = date("d",strtotime($attendant_date_sign));

		$attendant_date_sign =$tempDay." ".$tempMonth." ".$tempYear;
	}else{
		$attendant_date_sign = '';
	}


		$doctor = $pers_obj->get_Person_name($attendant_name);

		$middleInitial = "";
		if (trim($doctor['name_middle'])!=""){
			$thisMI=split(" ",$doctor['name_middle']);
			foreach($thisMI as $value){
				if (!trim($value)=="")
				$middleInitial .= $value[0];
			}
			if (trim($middleInitial)!="")
			$middleInitial .= ". ";
		}
		$doctor_name = $doctor["name_first"]." ".$doctor["name_2"]." ".$middleInitial.$doctor["name_last"];
		if (!empty($attendant_name))
			#$doctor_name = "Dr. ".ucwords(mb_strtolower($doctor_name));
			$doctor_name = mb_strtoupper($doctor_name).", MD";

		
		//$attendant_address = substr_replace(trim($attendant_address)," ",20,1);
		
		$data[0]['doctor_name']  =  mb_strtoupper($doctor_name)." ";		
		$data[0]['attendant_title']  =  mb_strtoupper($attendant_title)." ";		
		//$data[0]['attendant_address']  =  $attendant_address;		
		$data[0]['attendant_date_sign']  =  mb_strtoupper($attendant_date_sign)." ";		

	
 // 23. CORPSE DISPOSAL

	if ($corpse_disposal_tmp=='1')
		$data[0]['corpse_disposal_1']  =  "X";		
	if ($corpse_disposal_tmp=='2')
		$data[0]['corpse_disposal_2']  =  "X";	
	if ($corpse_disposal_tmp=='3'){
		$data[0]['corpse_disposal_3']  =  "X";		
		$corpse_disposal_others = "0";
		$data[0]['corpse_disposal_text']  =  $corpse_disposal_others;		
	}

 // 24. BURIAL/CREMATION PERMIT
 #$burial_permit = '000000';
		$data[0]['burial_permit']  =  mb_strtoupper($burial_permit)." ";		

	if ($burial_date_issued = "0000-00-00")
		$burial_date_issued = "";
		$data[0]['burial_date_issued']  =  mb_strtoupper($burial_date_issued)." ";		


 // 25. AUTOPSY

	if ($is_autopsy=='1')
		$data[0]['is_autopsy_1']  =   "X";		
	if ($is_autopsy=='2')
		$data[0]['is_autopsy_2']  =   "X";		


 // 26. NAME AND ADDRESS OF CEMETERY OR CREMATORY
 #$cemetery_name_address = 'cemetery';
		$data[0]['cemetery_name_address']  =   mb_strtoupper($cemetery_name_address)." ";		



 // 27. INFORMANT
	if (($informant_date_sign!='0000-00-00') && ($informant_date_sign!="")){
		$tempYear = date("Y",strtotime($informant_date_sign));
		$tempMonth = date("F",strtotime($informant_date_sign));
		$tempDay = date("d",strtotime($informant_date_sign));

		$informant_date_sign =$tempDay." ".$tempMonth." ".$tempYear;
	}else{
		$informant_date_sign = '';
	}
		$data[0]['informant_name']  =   mb_strtoupper($informant_name)." ";		
		$data[0]['informant_relation']  =   mb_strtoupper($informant_relation)." ";		
		$data[0]['informant_address']  =   mb_strtoupper($informant_address)." ";		
		$data[0]['informant_date_sign']  =   mb_strtoupper($informant_date_sign)." ";		

 // 28. PREPARED BY
	if (($encoder_date_sign!='0000-00-00') && ($encoder_date_sign!="")){
		$tempYear = date("Y",strtotime($encoder_date_sign));
		$tempMonth = date("F",strtotime($encoder_date_sign));
		$tempDay = date("d",strtotime($encoder_date_sign));

		$encoder_date_sign =$tempDay." ".$tempMonth." ".$tempYear;
	}else{
		$encoder_date_sign = '';
	}
		$data[0]['encoder_name']  =   mb_strtoupper($encoder_name)." ";		
		$data[0]['encoder_title']  =  mb_strtoupper($encoder_title)." ";		
		$data[0]['encoder_date_sign']  =   mb_strtoupper($encoder_date_sign)." ";		


// function seg_ucwords($str) {
// 	$words = preg_split("/([\s,.-]+)/", mb_strtolower($str), -1, PREG_SPLIT_DELIM_CAPTURE);
// 	$words = @array_map('ucwords',$words);
// 	return implode($words);
// }


showReport('death_fetal_cert',$params,$data,"PDF");


?>