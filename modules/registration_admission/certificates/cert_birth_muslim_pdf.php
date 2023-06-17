<?php
require_once('roots.php');
require_once($root_path.'include/inc_jasperReporting.php');
require_once($root_path.'include/inc_environment_global.php');

include_once($root_path.'include/inc_date_format_functions.php');

if (isset($_GET['id']) && $_GET['id']){
	$pid = $_GET['id'];
}

include_once($root_path.'include/care_api_classes/class_person.php');
$person_obj=new Person($pid);

require_once($root_path.'include/care_api_classes/class_personell.php');
$pers_obj=new Personell;

require_once($root_path.'include/care_api_classes/class_hospital_admin.php');
$objInfo = new Hospital_Admin();

    if ($row = $objInfo->getAllHospitalInfo()) {            
        $row['hosp_agency'] = strtoupper($row['hosp_agency']);
        $row['hosp_name']   = strtoupper($row['hosp_name']);
    }
    else {
        $row['hosp_country'] = "Republic of the Philippines";
        $row['hosp_agency']  = "DEPARTMENT OF HEALTH";
        $row['hosp_name']    = "BUKIDNON PROVINCIAL HOSPITAL - MALAYBALAY";
        $row['hosp_addr1']   = "Malaybalay, Bukidnon";        
    }

include_once($root_path.'include/care_api_classes/class_address.php');
$address_country = new Address('country');


if ($pid){

	if (!($basicInfo=$person_obj->getAllInfoArray($pid))){
		#echo $person_obj->sql;
		echo '<em class="warn"> sorry byt the page cannot be displayed!</em>';
		exit();
	}
	
	extract($basicInfo);
}else{
	echo '<em class="warn">Sorry but the page cannot be displayed! <br> Invalid HRN!</em>';
	exit();
}

$birthYear = intval(substr($date_birth, 0, 4)); 
$birthMonth = intval(substr($date_birth, 5, 7)); 
$birthDay = intval(substr($date_birth, 8, 10)); 

include_once($root_path.'include/care_api_classes/class_cert_birth.php');
$obj_birthCert = new BirthCertificate($pid);


$wsign = $_GET['wsign'];
#echo 's = '.$wsign;

$birthCertInfo = $obj_birthCert->getBirthCertRecord($pid);

if ($birthCertInfo){
	extract($birthCertInfo);
	#$marriage_type = substr($parent_marriage_info, 0, 1); 
	#$parent_marriage_info_tmp = substr($parent_marriage_info, 4); 
	$attendant_type = substr(trim($birthCertInfo['attendant_type']),0,1);
	$attendant_type_others = substr(trim($birthCertInfo['attendant_type']),4);
}


//DATE OF BIRTH
	$arrayMonth = array ("","January","February","March","April","May","June","July","August","September","October","November","December");
	$birthMonthName = $arrayMonth[$birthMonth];

	$f_rs_ethnic = $person_obj->getEthnic_orig("nr = '$f_ethnic'");
	$father_ethnic = $f_rs_ethnic->FetchRow();
	#$pdf->Text($x+115, $y+21, strtoupper($father_ethnic['name']));	
	if ($f_ethnic!=1)
		$f_ethnic = strtoupper($father_ethnic['name']);
	else
		$f_ethnic = "";	



//MOTHER
	$m_rs_ethnic = $person_obj->getEthnic_orig("nr = '$m_ethnic'");
	$mother_ethnic = $m_rs_ethnic->FetchRow();
	#$pdf->Text($x+20, $y+22, strtoupper($mother_ethnic['name']));	
	
	if ($m_ethnic!=1)
		$m_ethnic = strtoupper($mother_ethnic['name']);
	else
		$m_ethnic = "";	
		

	
//INFORMANT
	if (($informant_date_sign!='0000-00-00') && ($informant_date_sign!="")){
		$tempYear = intval(substr($informant_date_sign, 0, 4)); 
		$tempMonth = intval(substr($informant_date_sign, 5, 7)); 
		$tempDay = intval(substr($informant_date_sign, 8, 10)); 
		$informant_date_sign =$tempDay." ".$arrayMonth[$tempMonth]." ".$tempYear;
	}else{
		$informant_date_sign = '';
	}
	
	if ($wsign){
		$perrec = '';
	}else{
		$informant_date_sign = '';
		$perrec = 'as per record';
	}
	
	
    $params = array("prov_name"=>  $row['prov_name'],
    				"mun_name"=>  $row['mun_name'],
    				"registry_nr"=>  $registry_nr,
    				"name_full"=>  strtoupper($name_last.' '.$suffix."    ".$name_first."    ".$name_middle),
    				"name_first"=>  strtoupper($name_middle),
    				"name_first"=>  strtoupper($name_last),
    				"date_birth"=>  $birthDay."   ".$birthMonthName."   ".$birthYear,
    				"f_ethnic"=>  $f_ethnic,
    				"m_ethnic"=>  $m_ethnic,
    				"perrec"=>  $perrec,
    				"informant_name"=>  strtoupper($informant_name),
    				"informant_relation"=> $informant_relation,
    				"informant_address"=> strtoupper($informant_address),
    				"informant_date_sign"=> strtoupper($informant_date_sign),

               );

    $data['data'] = '';



showReport('cert_birth_muslim',$params,$data,"PDF");


?>