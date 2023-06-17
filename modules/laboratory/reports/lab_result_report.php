<?php
require_once('roots.php');
require_once($root_path.'include/inc_jasperReporting.php');
require_once($root_path.'include/inc_environment_global.php');
require_once($root_path.'include/care_api_classes/class_person.php');
require_once($root_path.'include/care_api_classes/class_encounter.php');
require_once($root_path.'include/care_api_classes/class_lab_results.php');
require_once($root_path.'include/care_api_classes/class_department.php');
require_once($root_path.'include/care_api_classes/class_personell.php');
require_once($root_path.'include/care_api_classes/class_ward.php');


$refno = $_POST["refno"] ? $_POST["refno"]:$_GET["refno"];
$pid = isset($_POST['pid']) ? $_POST['pid'] : $_GET['pid'];
$service_code = $_POST["service_code"] ? $_POST["service_code"]:$_GET["service_code"];
$group_id = $_POST["group_id"] ? $_POST["group_id"] : $_GET["group_id"];

$lab_results = new Lab_Results();
$ward_obj = new Ward;
$pers_obj= new Personell;
$seg_person = new Person($pid);

$person_info = $lab_results->get_patient_data($refno, $group_id);
if(!$person_info)
	$person_info = $lab_results->get_patient_walkin($refno, $group_id);
$result = $pers_obj->getPersonellInfo($person_info['request_doctor']);
if (trim($result["name_middle"]))
	 $dot  = ".";

$doctor = trim($result["name_first"])." ".substr(trim($result["name_middle"]),0,1).$dot." ".trim($result["name_last"]);
$doctor = htmlspecialchars(mb_strtoupper($doctor));
$doctor = trim($doctor);
if(!empty($doctor))
	$doctor = "DR. ".$doctor;

if($person_info['current_ward_nr']){
	$ward = $ward_obj->getWardInfo($person_info['current_ward_nr']);
	$location = strtoupper(strtolower(stripslashes($ward['name'])))." Rm # : ".$person_info['current_room_nr'];
}
else
	$location = "WALK-IN";

$middle_initial = (strnatcasecmp($person_info['name_middle'][0], $person_info['name_middle'][1]) == 0) ? ucwords(substr($person_info['name_middle'], 0, 2)) : strtoupper($person_info['name_middle'][0]);
$person_name = ucwords($person_info['name_last']) . ', ' . ucwords($person_info['name_first']) . ' ' . $middle_initial;
if($person_info['sex'] == 'm'){
	$gender = 'Male';
}
else if($person_info['sex'] == 'f'){
	$gender = 'Female';
}
else
	$gender = 'Unknown';

$code = $lab_results->getServiceName1($refno, $service_code, $group_id);
$i = 0; $formname = ''; $grp_name = ''; $medtech = 'try'; $pathologist = '';
$result = $lab_results->checkResult($refno, $service_code);
    if($result){
        while($row = $result->FetchRow()){
        	$normal_value = $row['SI_lo_normal'].''.$row['CU_lo_normal'].' - '.$row['SI_hi_normal'].''.$row['CU_hi_normal'].' '.$row['SI_unit'].''.$row['CU_unit'];
			if($formname == $row['form_name']){
				if($row['param_grp_name']){
					if($grp_name == $row['param_grp_name']){
						if($row['SI_unit'] || $row['CU_unit'])
				        	$data[$i] = array('date' => date("m/d/Y",strtotime($row["service_date"])),
				        						'param_name' => $row['name'],
				        						'normal_value' => $normal_value,
				        						'result_value' => $row['result_value'].' '.$row['unit'],
				        						'medtech' => $row['medtech'],
			        							'pathologist' => $row['pathologist']);
				        else
				        	$data[$i] = array('date' => date("m/d/Y",strtotime($row["service_date"])),
				        						'param_name' => $row['name'],
				        						'result_value' => $row['result_value'].' '.$row['unit'],
				        						'medtech' => $row['medtech'],
			        							'pathologist' => $row['pathologist']);
			        }
			        else{
			        	if($row['SI_unit'] || $row['CU_unit'])
				        	$data[$i] = array('date' => date("m/d/Y",strtotime($row["service_date"])),
				        						'group_name' => $row['param_grp_name'],
				        						'param_name' => $row['name'],
				        						'normal_value' => $normal_value,
				        						'result_value' => $row['result_value'].' '.$row['unit'],
				        						'medtech' => $row['medtech'],
			        							'pathologist' => $row['pathologist']);
				        else
				        	$data[$i] = array('date' => date("m/d/Y",strtotime($row["service_date"])),
				        						'group_name' => $row['param_grp_name'],
				        						'param_name' => $row['name'],
				        						'result_value' => $row['result_value'].' '.$row['unit'],
				        						'medtech' => $row['medtech'],
			        							'pathologist' => $row['pathologist']);
			        }
			        $grp_name = $row['param_grp_name'];
		        }
		        else{
		        	if($row['SI_unit'] || $row['CU_unit'])
				        $data[$i] = array('date' => date("m/d/Y",strtotime($row["service_date"])),
				        					'param_name1' => $row['name'],
				    						'normal_value1' => $normal_value,
				       						'result_value1' => $row['result_value'].' '.$row['unit'],
				       						'medtech' => $row['medtech'],
			        						'pathologist' => $row['pathologist']);
				    else
				       	$data[$i] = array('date' => date("m/d/Y",strtotime($row["service_date"])),
				       						'param_name1' => $row['name'],
				       						'result_value1' => $row['result_value'].' '.$row['unit'],
				       						'medtech' => $row['medtech'],
			        						'pathologist' => $row['pathologist']);
		        }
	        }
	        else{
	        	if($row['param_grp_name']){
	        		if($grp_name == $row['param_grp_name']){
	        			if($row['SI_unit'] || $row['CU_unit'])
							$data[$i] = array('date' => date("m/d/Y",strtotime($row["service_date"])),
												'form_name' => $row['form_name'],
				        						'param_name' => $row['name'],
				        						'normal_value' => $normal_value,
				        						'result_value' => $row['result_value'].' '.$row['unit'],
				        						'medtech' => $row['medtech'],
			        							'pathologist' => $row['pathologist']);
						else
							$data[$i] = array('date' => date("m/d/Y",strtotime($row["service_date"])),
												'form_name' => $row['form_name'],
				        						'param_name' => $row['name'],
				        						'result_value' => $row['result_value'].' '.$row['unit'],
				        						'medtech' => $row['medtech'],
			        							'pathologist' => $row['pathologist']);
					}
					else{
						if($row['SI_unit'] || $row['CU_unit'])
							$data[$i] = array('date' => date("m/d/Y",strtotime($row["service_date"])),
												'form_name' => $row['form_name'],
												'group_name' => $row['param_grp_name'],
				        						'param_name' => $row['name'],
				        						'normal_value' => $normal_value,
				        						'result_value' => $row['result_value'].' '.$row['unit'],
				        						'medtech' => $row['medtech'],
			        							'pathologist' => $row
			        											['pathologist']);
						else
							$data[$i] = array('date' => date("m/d/Y",strtotime($row["service_date"])),
												'form_name' => $row['form_name'],
												'group_name' => $row['param_grp_name'],
				        						'param_name' => $row['name'],
				        						'result_value' => $row['result_value'].' '.$row['unit'],
				        						'medtech' => $row['medtech'],
			        							'pathologist' => $row['pathologist']);
					}
					$grp_name = $row['param_grp_name'];
				}
		        else
		        	if($row['SI_unit'] || $row['CU_unit'])
			      		$data[$i] = array('date' => date("m/d/Y",strtotime($row["service_date"])),
			      							'form_name' => $row['form_name'],
			        						'param_name1' => $row['name'],
			        						'normal_value1' => $normal_value,
			        						'result_value1' => $row['result_value'].' '.$row['unit'],
			        						'medtech' => $row['medtech'],
			        						'pathologist' => $row['pathologist']);
			        else
				        $data[$i] = array('date' => date("m/d/Y",strtotime($row["service_date"])),
				        					'form_name' => $row['form_name'],
				        					'param_name1' => $row['name'],
				        					'result_value1' => $row['result_value'].' '.$row['unit'],
				        					'medtech' => $row['medtech'],
				        					'pathologist' => $row['pathologist']);  		  
	        }      	
			$i++;
			$formname = $row['form_name'];

         }
     
     }

        $data[0]['service'] =  $code;
		$data[0]['name'] =  $person_name;
		$data[0]['age'] =  ($person_info['age'])?$person_info['age']:'Unknown';
		$data[0]['sex'] =  $gender;
		$data[0]['hrn'] =  $person_info['pid'];
		$data[0]['room'] =  $location;
		$data[0]['physician'] =  $doctor;


//---end----------code
showReport('lab_manual_result',$params,$data,"PDF");


?>