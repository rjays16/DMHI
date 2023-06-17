<?php
//DARYL 07/12/14
//Cf2 CONVERT TO JASPER
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);

require_once('roots.php');
require_once($root_path.'include/inc_jasperReporting.php');
require_once($root_path.'include/inc_environment_global.php');

include_once($root_path.'include/inc_date_format_functions.php');
include_once($root_path.'include/care_api_classes/class_encounter.php');
include_once($root_path.'include/care_api_classes/class_hospital_admin.php');
include_once($root_path.'include/care_api_classes/class_insurance.php');
include_once($root_path.'include/care_api_classes/billing/class_billing_new.php');
include_once($root_path.'include/care_api_classes/class_referral.php');
include_once($root_path.'include/care_api_classes/class_configuration.php');

define('INFO_INDENT', 10);
define('NEWBORN', '99432');
define('OUT_PATIENT',2);

class cf1_page1				{
var $fontsize_label = 6.5;
	var $fontsize_label2 = 16;
	var $fontsize_label3 = 8;
	var $fontsize_label4 = 6;
	var $fontsize_label5 = 6.5;
	var $fontsize_answer = 10;
	var $fontsize_answer2 = 12;
	var $fontsize_answer_check = 12;
	var $fontsize_answer_check2 = 10;
	var $fontsize_answer_cert = 8.5;
	var $fontsize_answer_table = 9;

	var $fontstyle_label_bold = "";
	var $fontstyle_label_bold_italicized = "BI";
	var $fontstyle_label_italicized = "I";
	var $fontstyle_label_normal = '';
	var $fontstyle_answer = "B";

	var $fontfamily_label = "tahoma";
	var $fontfamily_label2 = "Times";
	var $fontfamily_answer = "freeserif";

	var $totwidth = 200;
	var $rheight = 5;
	var $rheight2 = 2;
	var $rheight3 = 3;
	var $rheight4 = 4;
	var $rheight6 = 6;
	var $rheight7 = 7;

	var $alignRight = "R";
	var $alignCenter = "C";
	var $alignLeft = "L";
	var $alignJustify = "J";

	var $servicedate_no = 0;

	var $withborder = 1;
	var $withoutborder = 0;
	var $borderTopLeftRight = "TLR";
	var $borderBottomLeftRight = "BLR";
	var $borderTopLeft = "TL";
	var $borderTopRight = "TR";
	var $borderTopBottom = "TB";
	var $borderTop = "T";
	var $borderBottom = "B";
	var $borderLeftRight = "LR";
	var $borderTopLeftBottom = "TLB";
	var $borderTopRightBottom = "TRB";

	var $lineAdjustment = 0.5;

	var $nextline = 1;
	var $continueline = 0;

	var $boxheight = 3;
	var $boxwidth = 3;

	var $blockheight = 4;
	var $blockwidth = 4;

	var $inspace = 1;
	var $vspace = 0;
	var $space = 5;

	var $encounter_nr = '';
	var $encounter_type = 0;
	var $hcare_id = 0;
	var $name_first = '';	// patient's first name
	var $name_suffix = ''; 	// patient's suffix
	var $name_middle = ''; 	// patient's middle name
	var $name_last = '';   	// patient's last name
	
	var $final_diagnosis;
	var $room_type;
	var $auth_rep;            // Authorized representative of hospital.
	var $rep_capacity;        // Official capacity of authorized representative.


	var $hospaccnum = 0;      // Accreditation No. of Hospital (part1)
	var $hosp_name = '';		  // Name of hospital (part1)
	var $hosp_address = '';	  // Address of hospital (part1)
	var $is_refer_by_another_hci = 'N'; //3. Was patient referred by another Health Care Institution (HCI)?
	var $another_hci_name = '';
	var $another_hci_address = '';
	var $phil_health_id_num     = ''; //patient's philhealth id (member or if dependent)
	var $is_principal = 0;			//patient if principal holder of philhealt 
	var $confinement_array;   // Array of class Confinement.
	var $date_admitted = '';  		//confinement date admitted
	var $date_discharged = ''; 		//confinement date discharged
	var $time_admitted = '';		//confinement time admitted
	var $time_admitted_ampm = '';		//confinement time admitted
	var $time_discharged_ampm = '';		//confinement time admitted

	var $time_discharged = '';		//confinement time discharged
	var $is_improved = false;		//5. patient disposition
	var $is_expired = false;		//5. patient disposition
	var $date_expired = ''; 		//5. patient disposition
	var $time_expired = '';			//5. patient disposition
	var $is_recovered = false;		//5. patient disposition
	var $is_transferred = false;	//5. patient disposition
	var $trans_hci_name = '';		//5. patient disposition
	var $trans_hci_address = '';	//5. patient disposition
	var $trans_reasons = '';		//5. patient dispositionreasons fo transfer or referral
	var $is_discharge = false;
	var $is_absconded = false;
	var $admission_diagnosis = '';
	var $diagnosis_array;     // Array of class Diagnosis.
	var $rvs_array; 
	var $professional_fee_array;
	
	var $hospserv_array;      // Array of class HospServices.
	
//	var $healthperson_array;  // Array of class Health Personnel.
	var $surgeon_array;       // Array of class Surgeon.
	var $anesth_array;				// Array of Anaesthesiologists.
	var $xray_array;					// Array of class X-Ray Charges
	var $lab_array;           // Array of class Laboratory Charges.
	var $sup_array;           // Array of object supplies and other charges
	var $others_array;        // Array of object other charges.

	var $total_rlo_charges  = 0;    // Total charges of x-ray, lab and others
	var $total_rlo_hospital = 0;    // Total hospital claims in x-ray, lab and others
	var $total_rlo_patient  = 0;    // Total patient claims in x-ray, lab and others

	var $coveredbypkg = false;	// Default flag for coverage ...
    var $pkglimit = 0.00;
    var $issurgical = false;

    var $type_private='';
 	var	$type_nonprivate='';

 	var $disp_code;
 	var $result_code;

 	//added by daryl
 	var $alter_fname;
 	var $alter_mname;
 	var $alter_lname;
 	var $alter_exname;

 	//added by Nick, 2/17/2014
 	var $icd_arr_desc,$icd_arr_code;
 	var $rvs_arr_desc,$rvs_arr_code,$rvs_arr_date,$rvs_arr_late;

	var $text1,
		$image_path,
		$text_val,
		$another_hci_name1,
		$another_hci_address1;

//variable for disposition
	var $disposition_code,
		$expired_time_date,
		$reason_reffered,
		$accomodation_type;

//variable for special consideration
	var $special_consideration_L0 = '',
		$special_consideration_L1 = '',
		$special_consideration_L2 = '',
		$special_consideration_L3 = '',
		$special_consideration_L0_date = '',
		$special_consideration_L1_date = '',
		$special_consideration_L2_date = '',
		$special_consideration_L3_date = '';

	var $special_consideration_R0 = '',
		$special_consideration_R1 = '',
		$special_consideration_R2 = '',
		$special_consideration_R3 = '',
		$special_consideration_R0_date = '',
		$special_consideration_R1_date = '',
		$special_consideration_R2_date = '',
		$special_consideration_R3_date = '';


	var $newborn_pack="0",
		$isNewborn = "0";

	var $first_case="",$second_case="";

	function checkdependence(){
				global $db;
				$sql = "SELECT d.parent_pid AS parent
									FROM care_encounter e
									LEFT JOIN seg_dependents d
									ON (d.dependent_pid = e.pid)
									WHERE e.encounter_nr = ".$db->qstr($this->encounter_nr);
				if ($result = $db->Execute($sql)) {
						if ($row = $result->FetchRow())
								return $row['parent'];
				}
				return false;
	}

	function getPrincipalNm($pid) {
		global $db;

		$strSQL = "SELECT i.insurance_nr AS IdNum     
					FROM care_person_insurance AS i        
					WHERE i.hcare_id = $this->hcare_id AND i.is_principal = 1 AND p.pid = '$pid'";

		if ($result = $db->Execute($strSQL)) {
			if ($result->RecordCount()) {
				return $result;
			}
		}

		return false;
	}

	function checkDeathInfo(){
		global $db;

		$sql = "SELECT cp.`death_date` AS deathdate, cp.`death_time` AS deathtime
					FROM seg_insurance_member_info AS sim 
						LEFT JOIN care_person AS cp ON cp.`pid` = sim.`pid`
						INNER JOIN care_encounter AS ce ON ce.`pid` = sim.`pid`
					WHERE ce.encounter_nr = ".$db->qstr($this->encounter_nr);


					$death_result = $db->Execute($sql);
			
					return $death_result;
		
	}

	function getRVSCode(){
		global $db;

		$sql = "SELECT smod.ops_code , scrp.description, smod.laterality, smod.op_date
					FROM seg_insurance_member_info AS sim
						INNER JOIN care_encounter AS ce ON ce.pid = sim.pid
						INNER JOIN seg_misc_ops AS smo ON smo.encounter_nr = ce.encounter_nr
						INNER JOIN seg_misc_ops_details AS smod ON smod.refno = smo.refno
						INNER JOIN seg_case_rate_packages AS scrp ON smod.ops_code = scrp.code
					WHERE ce.encounter_nr = ".$db->qstr($this->encounter_nr);


					$rvs_result = $db->Execute($sql);
			
					return $rvs_result;
	}
	# End James

	//added by daryl
	function checkifmember(){
				global $db;
				$sql_1 = "SELECT p.name_last AS LastName, p.name_first AS FirstName, p.name_2 AS SecondName,
						p.name_3 AS ThirdName, p.name_middle AS MiddleName, i.relation AS relation,
						i.insurance_nr AS IdNum, p.street_name AS Street, sb.brgy_name AS Barangay,                        \n
						sg.mun_name AS Municity, sg.zipcode AS Zipcode, sp.prov_name AS Province, p.date_birth,
						p.phone_1_code, p.phone_1_nr, p.cellphone_1_nr, p.email,p.suffix AS Suffix
						FROM care_person AS p
						INNER JOIN care_encounter AS e ON e.pid = p.pid
						INNER JOIN seg_insurance_member_info AS i ON i.pid = p.pid
						LEFT JOIN seg_barangays AS sb ON sb.brgy_nr = p.brgy_nr                   \n
						LEFT JOIN seg_municity AS sg ON sg.mun_nr = sb.mun_nr                     \n
						LEFT JOIN seg_provinces AS sp ON sp.prov_nr = sg.prov_nr
						WHERE i.hcare_id = '$this->hcare_id'  AND e.encounter_nr = ".$db->qstr($this->encounter_nr);

			
				if($result = $db->Execute($sql_1)){
					if($result->RecordCount()){
						return $result;	
					}else {return false;}
				}else {return false;}	
				
		}


	//added by daryl
	function checkifnotmember(){
		global $db;
		$sql_1 = "SELECT e.er_opd_diagnosis AS Admission_diag,e.is_discharged AS isDischarge, e.discharge_date AS dis_dates, e.discharge_time AS dis_times,
						e.admission_dt AS admits_date, sim.insurance_nr AS PINdep, sim.member_fname AS FirstName,
						 sim.member_mname AS MiddleName, sim.member_lname AS LastName, sim.suffix AS Suffix, 
						 sim.birth_date AS Birth_date, sim.relation AS dep_Relation
					FROM care_person AS p
					INNER JOIN care_encounter AS e ON e.pid = p.pid
					INNER JOIN seg_insurance_member_info AS sim ON sim.pid = p.pid
					WHERE sim.hcare_id = '$this->hcare_id'  AND e.encounter_nr =".$db->qstr($this->encounter_nr);


		if($result = $db->Execute($sql_1)){
			if($result->RecordCount()){
				return $result;	
			}else {return false;}
		}else {return false;}	
	}

	function get_encounter_data(){
				global $db;
			
				$sql_1 = "SELECT e.admission_dt AS admition_date, e.discharge_date AS dis_date, e.discharge_time as dis_time
						FROM care_person AS cp
						INNER JOIN care_encounter AS e ON cp.pid = e.pid
						LEFT JOIN seg_insurance_member_info AS d ON d.pid = cp.pid
						WHERE d.hcare_id = '$this->hcare_id'  AND e.encounter_nr = ".$db->qstr($this->encounter_nr);

					$result3 = $db->Execute($sql_1);
			
					return $result3;
			}

	function getDischargeDiag() {
			global $db;

			$strSQL = "SELECT ci.`description` AS dis_desc, ce.`code` AS dis_code
			 FROM seg_encounter_diagnosis AS ce 
			 LEFT JOIN  care_icd10_en AS ci ON ce.`code` = ci.`diagnosis_code` 
			 LEFT JOIN care_encounter AS i ON ce.`encounter_nr` = i.`encounter_nr` 
				WHERE ce.encounter_nr = ".$db->qstr($this->encounter_nr);

			
					$result4 = $db->Execute($strSQL);
			
					return $result4;
		}

	function getdisposition()
	{
		global $db;
		$strSQL="SELECT ser.`result_code` , sed.`disp_code`
				FROM  seg_encounter_disposition AS sed 
				INNER JOIN seg_encounter_result as ser
					ON ser.encounter_nr = sed.encounter_nr
				WHERE sed.encounter_nr = ".$db->qstr($this->encounter_nr);
		
		if($result = $db->Execute($strSQL)){
			if($result->RecordCount()){
				while ($row = $result->FetchRow()) {
					$this->disp_code = $row['disp_code'];
					$this->result_code =  $row['result_code'];
				}
			}else {return false;}
		}else {return false;}	
	}



	function if_altername()
	{
		global $db;

		$strSQL = "SELECT * 
					from seg_cf2_altername 
					where encounter_nr =" . $db->qstr($this->encounter_nr);

		if($result = $db->Execute($strSQL)){
			if($result->RecordCount()){
				while ($row = $result->FetchRow()) {
					$this->alter_fname = $row['name_first'];
					$this->alter_mname =  $row['name_middle'];
					$this->alter_lname =  $row['name_last'];
					$this->alter_exname =  $row['suffix'];
				}
				return 1;
			}else {return 0;}
		}else {return $strSQL;}	
		

		
	}

	function check_is_death()
	{
		global $db;
		$strSQL="SELECT cp.`death_date` AS deathdate, cp.`death_time` AS deathtime
		 		FROM care_encounter AS ce 
		 		INNER JOIN care_person AS cp ON cp.`pid` = ce.`pid`
				WHERE ce.encounter_nr = ".$db->qstr($this->encounter_nr);

		if($result = $db->Execute($strSQL)){
			if($result->RecordCount()){
				return $result;
			}else {return false;}
		}else {return false;}
		
	}

	function get_special_con($skey)
	{
			global $db;
		$strSQL="SELECT smod.`ops_code`  , scrp.`description`, smod.`laterality`, smod.`op_date`, smod.`special_dates`
	FROM seg_insurance_member_info AS sim
	LEFT JOIN care_person AS cp ON cp.`pid` = sim.`pid`
	INNER JOIN care_encounter AS ce ON ce.`pid` = sim.`pid`
	INNER JOIN seg_misc_ops AS smo ON smo.`encounter_nr` = ce.`encounter_nr`
	INNER JOIN seg_misc_ops_details AS smod ON smod.`refno` = smo.`refno`
	INNER JOIN seg_case_rate_packages AS scrp ON smod.`ops_code` = scrp.`code`
	 WHERE ce.encounter_nr = ".$db->qstr($this->encounter_nr)." AND scrp.`description` REGEXP '".$skey."'";


					$result8 = $db->Execute($strSQL);
			
					return $result8;
	}


	function get_doctor_info()
	{
		global $db;
		$strSQL="SELECT sbe.`bill_dte`, fn_get_personell_lastname_first2(sbp.`dr_nr`) as doc_name, sbp.`dr_charge`, sbp.`dr_claim`,
				(SELECT accreditation_nr from seg_dr_accreditation as sda where sda.dr_nr = sbp.dr_nr and sda.hcare_id = '$this->hcare_id') as acc_no
				FROM seg_billing_encounter AS sbe
				INNER JOIN seg_billing_pf AS sbp ON sbe.`bill_nr` = sbp.`bill_nr`
				WHERE sbe.is_final = '1' AND sbe.is_deleted IS NULL
				AND sbe.`encounter_nr` = ".$db->qstr($this->encounter_nr);
		//echo($strSQL);
		if($result = $db->Execute($strSQL)){
			if($result->RecordCount()){
				return $result;
			}else {return false;}
		}else {return false;}	
	}

	function isNewBorn(){
		global $db;
		$strSQL = $db->Prepare("SELECT package_id 
					FROM seg_billing_encounter sbe
					INNER JOIN seg_billing_caserate sbc
						ON sbe.bill_nr=sbc.bill_nr
					WHERE sbe.is_final = 1 
					AND sbe.is_deleted IS NULL
					AND encounter_nr = ?");

		if($result = $db->Execute($strSQL,$this->encounter_nr)){
			if($result->RecordCount()){
				while ($row = $result->FetchRow()) {
					 if($row['package_id']==NEWBORN)
					 	return true;
				}
			}else{
				return false;
			}
		}else{
			return false;
		}
	}


	function get_doctor_info2()
	{
		$data = array();
			$index = 0;

			$this->objBill->getProfFeesList();
			$this->objBill->getProfFeesBenefits();
			$hsp_pfs_benefits = $this->objBill->getPFBenefits();
			$proffees_list = $this->objBill->proffees_list;

			foreach($hsp_pfs_benefits as $key=> $value) {
				if ($value->role_area == $prevrole_area) continue;
				$prevrole_area = $value->role_area;
				reset($proffees_list);
				$this->objBill->initProfFeesCoverage($value->role_area);
				$totalCharge = number_format($this->objBill->getTotalPFCharge($value->role_area), 2);
				$coverage    = number_format($this->objBill->pfs_confine_coverage[$value->role_area], 2, '.', ',');
				$tr ='';
				foreach($proffees_list as $key=>$profValue){
					if($value->role_area == $profValue->role_area) {
						$opcodes = $profValue->getOpCodes();
						if ($opcodes != '') {
							$opcodes = explode(";", $opcodes);
						}
						if (is_array($opcodes)) {
							foreach($opcodes as $v) {
								$i = strpos($v, '-');
								if (!($i === false)) {
									$code = substr($v, 0, $i);
		                              if ($this->objBill->getIsCoveredByPkg()) break;
								}#if
							}#foreach
						}#if

						$drName = $profValue->dr_first." ".$profValue->dr_mid.(substr($profValue->dr_mid, strlen($profValue->dr_mid)-1,1) == '.' ? " " : ". ").$profValue->dr_last;
						$drCharge = number_format($profValue->dr_charge, 2, '.', ',');
						$totalPF += $profValue->dr_charge;

						$data[$index] = array("dr_charge"=>$profValue->dr_charge,
							                  "role_area"=>$value->role_area,
									          "role_desc"=>$value->role_desc,
									          "total_charge"=>$this->objBill->getTotalPFCharge($value->role_area),
									          "coverage"=>number_format($this->objBill->pfs_confine_coverage[$value->role_area], 2, '.', ','),
									          "drName"=>$drName,
									          "dr_nr" => $profValue->dr_nr
								  	         );
						$index++;
					}#if
				}#foreach
			}#foreach
	}

	function getCaseRate($type){
		global $db;
		$strSQL = "SELECT package_id 
					FROM seg_billing_encounter sbe
					INNER JOIN seg_billing_caserate sbc
						ON sbe.bill_nr=sbc.bill_nr
					WHERE sbe.is_final = 1 
					AND sbe.is_deleted IS NULL
					AND sbc.rate_type = ".$db->qstr($type)."
					AND encounter_nr = ".$db->qstr($this->encounter_nr);

		if($result = $db->Execute($strSQL)){
			if($result->RecordCount()){
				while ($row = $result->FetchRow()) {
					return $row['package_id'];
				}
			}else {return false;}
		}else {return false;}	
	}


	function getTotalAppliedDiscounts(){
        global $db;

        $sql = "SELECT SUM(discount) AS total_discount FROM seg_billingapplied_discount 
                WHERE encounter_nr = ".$db->qstr($this->encounter_nr);

        $rs = $db->Execute($sql);
             if($rs){
            if($rs->RecordCount()>0){
                $row = $rs->FetchRow();
                return $row['total_discount'];
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

 	function setData($enc)	{
		$series_number = "";
		global $root_path, $db;
		$this->encounter_nr = $enc;
			$enc_obj=new Encounter;
			$encInfo=$enc_obj->getEncounterInfo($this->encounter_nr);

		if ($series_number == ""){
			$series_number = '            ';
		}else{
		$series_number =  $series_number;
		}	
		$config_obj = new Configuration;
		$image_patch_db = $config_obj->GetImagePatch($field = "image_directory");

		$series_no = str_split($series_number);
		$series_len = strlen($series_number);
		$this->text1  =  "PLEASE WRITE IN CAPITAL <b>LETTERS</b> AND <b>CHECK</b> THE APPROPRIATE BOXES";
		$this->image_path = $image_patch_db."/";
		$objinsurance = new Insurance();

		//accreditaion number
		$this->hospaccnum = $objinsurance->getAccreditationNo($_GET['id']);

		/*PART I - HEALTH CARE INSTITUTION (HCI) INFORMATION */
		$objInfo = new Hospital_Admin();
		if ($row = $objInfo->getAllHospitalInfo()) {
				$this->hosp_name = strtoupper($row['hosp_name']);
				$this->hosp_address = strtoupper($row['hosp_addr1']);
				$this->hosp_address1 = strtoupper($row['hosp_addr2']);
		}else{
				$this->hosp_name = strtoupper("Davao Mediquest Hospital Incorporated");
				$this->hosp_address = strtoupper("Lizada St. Toril Davao City");
		}

		$result_altername =	$this->if_altername();

	if ($result_altername == 1){
		$fname_patient = strtoupper($this->alter_fname);
		$mname_patient = strtoupper($this->alter_mname);
		$lname_patient = strtoupper($this->alter_lname);
		$suffix_patient = strtoupper($this->alter_exname);
	}else{

		$fname_patient = strtoupper($encInfo['name_first']);
		$mname_patient = strtoupper($encInfo['name_middle']);
		$lname_patient = strtoupper($encInfo['name_last']);
		$suffix_patient = strtoupper($encInfo['suffix']);
	}

		$admission_diag_ = $encInfo['er_opd_diagnosis']; 
		$isDischarge_ = $encInfo['is_discharged'];
		$birth_patient =$encInfo['date_birth'];
		$dis_dates_ = $encInfo['discharge_dt'];
		$admits_date_ = $encInfo['admission_dt'];
		$encounter_date = $encInfo['encounter_date'];

		
		$this->admission_diagnosis= $admission_diag_;
		$this->name_last = strtoupper(trim($lname_patient)) ;
		$this->name_first = strtoupper(trim($fname_patient)) ;
		$this->name_suffix = strtoupper(trim($suffix_patient));
		$this->name_middle = strtoupper(trim($mname_patient)) ;

		if ($isDischarge_ == 1)
		{
			$this->date_discharged = date("m-d-Y", strtotime($dis_dates_));
			$this->time_discharged = date("h:i:s", strtotime($dis_dates_));
			$this->time_discharged_ampm = date("a", strtotime($dis_dates_));
		}
		else
		{
			$this->date_discharged = "        ";
			$this->time_discharged = "     ";
			$this->time_discharged_ampm = "  ";
		}

		if ($admits_date_ != "")
		{
		$this->date_admitted = date("m-d-Y", strtotime($admits_date_));
		$this->time_admitted = date("h:i:s", strtotime($admits_date_));
		$this->time_admitted_ampm = date("a", strtotime($admits_date_));
		}
		else
		{
		$this->date_admitted = date("m-d-Y", strtotime($encounter_date));
		$this->time_admitted = date("h:i:s", strtotime($encounter_date));
		$this->time_admitted_ampm = date("a", strtotime($encounter_date));
		}
	}#end function setdata

	function formatSpecialDates($strdate){
		$strdate = trim($strdate,',');
		$dates = explode(',', $strdate);
		$output = '';
		foreach ($dates as $key => $value) {
			$output .= (strtotime($value) >= 0 && strtotime($value) != '') ? date('m-d-Y',strtotime($value)) . ',' : '';
		}
		$output = trim($output,',');
		return $output;
	}

 	function addSpecialConsiderations(){
 		// $spcl_considerations = array('8. Speacial Considerations:', 
 		// 									'',
 		// 									'a. For the following repetitive procedures, check box that applies and enumerate the procedure/session dates [mm-dd-yyyy]. For chemotherapy, see guidelines.',
			// 		'',
			// 		 'b. For Z-Benefit Package', 
			// 		'',
			// 		'c .For MCP Package (enumerate four dates [mm-dd-yyyy] of pre-natal check-ups)',
			// 		'',
			// 		'd. For TB DOTS Package',
			// 		 '',
			// 		 'e. For Animal Bite Package (write the dates [mm-dd-yyyy] when the following doses of vaccine were given)',
			// 		 '',
			// 		 'f. for Newborn Care Package',
			// 		 '',
			// 		  'g. For Outpatient HIV/AIDS Treatment Package');



		$spcl_considerationsA = array('Hemodialysis', 'Peritoneal Dialysis', 'Radiotherapy (LINAC)', 'Radiotherapy (COBALT)');

	$spcl_considerationsAA = array('Blood transfusion','Brachytherapy', 'Chemotherapy', 'Debridement');


		// $spcl_considerationsB = array('Z-Benefit Package Code:');
		// $formatdate = array('month', 'day', 'year');
		// $spcl_considerationsD = array('Intensive Phase', 'Maintenance Phase');
		// $spcl_considerationsE = array('Essential Newborn Care',
		// 							 'Newborn Hearing Screening Test',
		// 							  'Newborn Screening Test',
		// 							   'For Essential Newborn Care,',									  
		// 	'Immediate dying of newborn',
		// 	'Timely cord clamping',
		// 	'Weighing of the newborn',
		// 	'BCG vaccination',
		// 	 'Hepatitis B vaccination', 
		// 	 'Early skin-to-skin contact',
		// 	 'Eye prophylaxis',
		// 	 'Vitamin K administration',
		// 	 'Non-separation of mother/baby for early breastfeeding initiation');
		// $spcl_considerationsF = 'Laboratory Number:';
		// $ColWidthDate = array(9.5,13,15.5,10);
		// $ColWidthSpcl = array(50,10,30);




		for ($ii = 0; $ii<4; $ii++)
		{

		$result8_A[$ii]=$this->get_special_con($spcl_considerationsA[$ii]);
		$result8_B[$ii]=$this->get_special_con($spcl_considerationsAA[$ii]);

		$result8_AA = $result8_A[$ii]->FetchRow();
		$RSdate_A[$ii] = $this->formatSpecialDates($result8_AA['special_dates']);

		$result8_BB = $result8_B[$ii]->FetchRow();
		$RSdate_B[$ii] = $this->formatSpecialDates($result8_BB['special_dates']);

		$rcountRS8_A[$ii]=$result8_A[$ii]->RecordCount();
		$rcountRS8_B[$ii]=$result8_B[$ii]->RecordCount();

		if ($rcountRS8_A[$ii] > 0)
		{
			$RS8value_A[$ii] = 1;
		}

			// echo $rcountRS8[$ii];
		if ($rcountRS8_B[$ii] > 0)
		{
			$RS8value_B[$ii] = 1;
		}


		}



		$repetitive_procedures = array(
			'Hemodialysis' => array( $RS8value_A[0], $RSdate_A[0]),// 'true/false', 'concatenated dates'
			'Peritoneal Dialysis' => array($RS8value_A[1], $RSdate_A[1]),
			'Radiotherapy (LINAC)' => array($RS8value_A[2], $RSdate_A[2]), 
			'Radiotherapy (COBALT)' => array($RS8value_A[3], $RSdate_A[3])

			); 

			$repetitive_procedures2 = array(
			
			'Blood transfusion' => array($RS8value_B[0], $RSdate_B[0]), 
			'Brachytherapy' => array($RS8value_B[1], $RSdate_B[1]),
			'Chemotherapy' => array($RS8value_B[2], $RSdate_B[2]),
			'Debridement' => array($RS8value_B[3], $RSdate_B[3])

			); 

		$z_code = '';
		$z_tranche = '';
		$prenatal_dates = array();
		$is_intesive_phase = ''; //default false , put "/" if true
		$is_maintenance_phase = '';
		$is_essential_newborn = '';
		$is_newborn_screening = '';
		$is_newborn_hearing = '';
		$is_immediate_dying = '';
		$is_vitaminK = '';
		$is_bcg = '';
		$is_hepa = '';
		$filter_no = '';
		$lab_no = '';

//variable for mcp package
		 $mcppack1="";
		  $mcppack2="";
		   $mcppack3="";
		    $mcppack4="";

		    //for bite
		    $bite_day1="";
		    $bite_day3="";
		    $bite_day7="";
		    $bite_rig="";
		    $bite_others="";



		for($row=1;$row<14;$row++){
		
		// echo  $spcl_considerations[$row];
			// $this->Cell($this->rheight6, $this->rheight2, $spcl_considerations[$row],$this->withoutborder, $this->continueline, $this->alignRight);
			$row++;
			
		

			// $this->Cell($this->GetStringWidth($spcl_considerations[$row]), $this->rheight2, $spcl_considerations[$row],$this->withoutborder, $nxtLine, $this->alignCenter);
			switch ($row) {
				case 2:

					for($i=0;$i<4;$i++){
						
						$check_value = $repetitive_procedures[$spcl_considerationsA[$i]][0] ? ''.$i.'' :'';
						$date_value = $repetitive_procedures[$spcl_considerationsA[$i]][1] ;
			
						$check_value2 = $repetitive_procedures2[$spcl_considerationsAA[$i]][0] ? ''.$i.'' :'';
						$date_value2 = $repetitive_procedures2[$spcl_considerationsAA[$i]][1] ;

						if ($check_value == "0"){
							$this->special_consideration_L0 = "1";
							$this->special_consideration_L0_date = $date_value;
						}

						if ($check_value == "1"){
							$this->special_consideration_L1 = "1";
							$this->special_consideration_L1_date = $date_value;
						}

						if ($check_value == "2"){
							$this->special_consideration_L2 = "1";
							$this->special_consideration_L2_date = $date_value;
						}

						if ($check_value == "3"){
							$this->special_consideration_L3 = "1";
							$this->special_consideration_L3_date = $date_value;
						}

						if ($check_value2 == "0"){
							$this->special_consideration_R0 = "1";
							$this->special_consideration_R0_date = $date_value2;
						}
					
						if ($check_value2 == "1"){
							$this->special_consideration_R1 = "1";
							$this->special_consideration_R1_date = $date_value2;
						}

						if ($check_value2 == "2"){
							$this->special_consideration_R2 = "1";
							$this->special_consideration_R2_date = $date_value2;
						}
		
						if ($check_value2 == "3"){
							$this->special_consideration_R3 = "1";
							$this->special_consideration_R3_date = $date_value2;
						}
					}
					break;

				default:
					# code...
					break;
			}
			
		}
		// echo $this->special_consideration_L0;exit();
 	}

 	function getreferral(){
 		$objReferral = new Referral();

 		$referral = $objReferral->getreferralDetails($this->encounter_nr);
 		if($referral){
 			while($row = $referral->FetchRow()){
 				if($row['code'] == 'P00001' || $row['code'] == 'P00000'){
 					$this->disposition_code = "T";
 					$this->trans_hci_name=$row['hospital'];
					$this->reason_reffered=$row['reason'];
 				}
 			}
 		}
 	}
function getreferred(){
	$objenc = new Encounter();

	$refered = $objenc->getEncounterInfo($this->encounter_nr);
	if($refered['referrer_institution'] || $refered['referrer_institution'] != "" ){
			$this->is_refer_by_another_hci = 'Y';
			$this->another_hci_name1 = strtoupper($refered['referrer_institution']);
			$this->another_hci_address1 = strtoupper($refered['referrer_notes']);
	}
}

	function addPatientDisposition(){
	
		$this->getdisposition();

		$objBilling = new Billing();
		$row = $objBilling->getDeathDate($this->encounter_nr);

		$date = explode(' ', $row);
		$get_is_death_time = $date[1];
		$get_is_death_date = $date[0];
				

if ($get_is_death_date == ""){
		
	if($this->encounter_type == OUT_PATIENT){
		$this->disposition_code = "I";
	}else if ($this->result_code == 5 || $this->result_code == 6 || $this->result_code == 1 || $this->result_code == 2){
		if($this->result_code == 5 || $this->result_code == 1)
			$this->disposition_code = "R";
		elseif ($this->result_code == 6 || $this->result_code == 2) 
			$this->disposition_code = "I";
	}else{
	if ($this->disp_code != 7 || $this->disp_code != 2) {
		if ($this->disp_code == 9 || $this->disp_code == 4)
			$this->disposition_code = "H";
		elseif ($this->disp_code == 8 || $this->disp_code == 3) 
			$this->disposition_code = "T";
		elseif ($this->disp_code == 10 || $this->disp_code == 5) 
			$this->disposition_code = "A";
		}
	}
} else {
		$this->date_expired = date("m-d-Y", strtotime($get_is_death_date));
		$time_expired = date("h:i:s", strtotime($get_is_death_time));
		$this->time_expired = substr($time_expired, 0, 5);
		$this->time_expired_ampm = date("a", strtotime($get_is_death_time));
		$this->disposition_code = "E";
}	

if ($this->disposition_code == "E" ){
	//add expired date
		$date_value = empty($this->date_expired)? '          ': $this->date_expired;
		$time_value = empty($this->time_expired)? '     ': $this->time_expired;
		$ampm_value = empty($this->time_expired_ampm)? ' ': $this->time_expired_ampm;
		$this->expired_time_date=$date_value." ".$time_value." ".$ampm_value;
}else{
	$date_value = empty($this->date_expired)? '          ': $this->date_expired;
	$time_value = empty($this->time_expired)? '     ': $this->time_expired;
	$ampm_value = empty($this->time_expired_ampm)? ' ': $this->time_expired_ampm;
	$this->expired_time_date=$date_value." ".$time_value." ".$ampm_value;
}

if ($this->disposition_code == "T" ){
$this->trans_hci_name=" ";
$this->trans_hci_address=" ";
$this->reason_reffered=" ";
}

	if($this->Charity){
		$this->accomodation_type = 'SERVICE';
	}else{	
		$this->accomodation_type = 'PRIVATE';
	}

}
	function addPhilHealthBenefits(){

		if ($this->getCaseRate(1)){
			$this->first_case = $this->getCaseRate(1);
		}

		if ($this->getCaseRate(2)){
				$this->second_case = $this->getCaseRate(2);
		}

 	}



 //added by Nick 06-03-2014
    function addSpecialConsiderations2(){
        $objBilling = new Billing();

        $isNewborn = $this->isNewBorn();
        $hasHearingTest = $objBilling->isHearingTestAvailed($this->encounter_nr,$isNewborn);

       if ($isNewborn == "1"){
       	$this->isNewborn = "1";

       		 if($hasHearingTest){
		       	$this->newborn_pack = "2";
		       }else{
		       	$this->newborn_pack = "3";
		       }
       }

      

    }//end function

	function addPart1and2(){

	 	//patient disposition label
		$this->addPatientDisposition();

		// //special considerations
		$this->addSpecialConsiderations();
        $this->addSpecialConsiderations2();

		// //philhealth benefits
		$this->addPhilHealthBenefits();

		//add referral
		$this->getreferral();
		$this->getreferred();
	}

	function parameter(){
// echo $this->date_admitted." ".substr($this->time_admitted, 0, 5)." ".$this->time_admitted_ampm;exit();
		$params = array("image_path"=> $this->image_path,
						"text1"=>$this->text1,
						"pan"=>$this->hospaccnum."   ",
						"hci_name"=>$this->hosp_name,
						"building_name"=>$this->hosp_address,
						"city"=>$this->hosp_address1,
						"patient_name_last"=> $this->name_last,
						"patient_name_first"=>$this->name_first,
						"patient_suffix"=>$this->name_suffix,
						"patient_name_middle"=>$this->name_middle,
						"another_HCI"=>$this->is_refer_by_another_hci,
						"referred_by_hci"=>$this->another_hci_name1,
						"referred_by_building_name"=>$this->another_hci_address1,
						"date_admitted"=>$this->date_admitted." ".substr($this->time_admitted, 0, 5)." ".$this->time_admitted_ampm,
						"date_discharged"=>$this->date_discharged." ".substr($this->time_discharged, 0, 5)." ".$this->time_discharged_ampm,
						"patient_disposition"=>$this->disposition_code,
						"date_expired"=>$this->expired_time_date,
						"referred_to_hci"=>$this->trans_hci_name,
						"referred_to_building_name"=>$this->trans_hci_address,
						"referr_to_reason"=>$this->reason_reffered,
						"accomodation_type"=>$this->accomodation_type,
						"admission_diagnosis"=>$this->admission_diagnosis." ",
						"special_consideration_L0"=>$this->special_consideration_L0,
  						"special_consideration_L1"=>$this->special_consideration_L1,
  						"special_consideration_L2"=>$this->special_consideration_L2,
  						"special_consideration_L3"=>$this->special_consideration_L3,
  						"special_consideration_L0_date"=>$this->special_consideration_L0_date,
  						"special_consideration_L1_date"=>$this->special_consideration_L1_date,
  						"special_consideration_L2_date"=>$this->special_consideration_L2_date,
  						"special_consideration_L3_date"=>$this->special_consideration_L3_date,
  						"special_consideration_R0"=>$this->special_consideration_R0,
  						"special_consideration_R1"=>$this->special_consideration_R1,
  						"special_consideration_R2"=>$this->special_consideration_R2,
  						"special_consideration_R3"=>$this->special_consideration_R3,
  						"special_consideration_R0_date"=>$this->special_consideration_R0_date,
  						"special_consideration_R1_date"=>$this->special_consideration_R1_date,
  						"special_consideration_R2_date"=>$this->special_consideration_R2_date,
  						"special_consideration_R3_date"=>$this->special_consideration_R3_date,
  						"case_rate_1"=>$this->first_case,
  						"case_rate_2"=>$this->second_case,
  						"newborn_pack"=>$this->newborn_pack,
  						"is_newborn"=>$this->isNewborn
               );
		return $params;
	}#end function parameter

}#end class cf1_page1

#added by Nick, 2/17/2014
class ICD_ICP{

	var $encounter_nr;

	function __construct($enc){
		$this->encounter_nr = $enc;
	}

	function getIcd(){
		global $db;
		$data = array();
							
		$sql = $db->Prepare("SELECT DISTINCT sed.`create_time` as create_date, IF(sed.code_alt IS NOT NULL,sed.code_alt,sed.`code`) code, sed.description 
							FROM seg_encounter_diagnosis sed 
							INNER JOIN seg_case_rate_packages scrp 
							ON sed.`code` = scrp.`code` 
							WHERE sed.is_deleted=0 
							AND scrp.`case_type` = 'm'  
							AND sed.encounter_nr=".$db->qstr($this->encounter_nr)."
							UNION
							SELECT DISTINCT sed.`create_time` as create_date, IF(sed.code_alt IS NOT NULL,sed.code_alt,sed.`code`) CODE, sed.description 
							FROM seg_encounter_diagnosis sed 
							INNER JOIN care_icd10_en icden 
							ON sed.`code` = icden.`diagnosis_code`
							WHERE sed.is_deleted=0 
							AND sed.encounter_nr=".$db->qstr($this->encounter_nr)."
							ORDER BY create_date ASC");
	// echo $sql;exit();
	
		$rs = $db->Execute($sql);
		if($rs){
			if($rs->RecordCount() > 0){
				while($row = $rs->FetchRow()){
					array_push($data, $row);
				}
			}else{
				$data = false;
			}
		}else{
			$data = false;
		}
		return $data;
	}

	function getRvs(){
		global $db;
		$data = array();
		$sql = 				"SELECT smo.`modify_dt` AS create_date,
							  smod.`ops_code`,
							  IF(
							    smod.`description` IS NOT NULL,
							    smod.`description`,
							    (SELECT p.description FROM seg_case_rate_packages p WHERE p.code = smod.ops_code)
							  ) AS description,
							  smod.`laterality`,
							  smod.`op_date` 
							FROM
							  seg_misc_ops AS smo 
							  INNER JOIN seg_misc_ops_details AS smod 
							    ON smod.`refno` = smo.`refno` 
							WHERE smo.`encounter_nr` = ".$db->qstr($this->encounter_nr)."
							UNION 
							SELECT sed.`create_time` AS create_date,
							  IF(
							    sed.code_alt IS NOT NULL,
							    sed.code_alt,
							    sed.`code`
							  ) ops_code,
							  sed.description,
							   '' AS laterality,
							 sed.`rvs_date` AS op_date
							FROM
							  seg_encounter_diagnosis sed 
							  INNER JOIN seg_case_rate_packages scrp 
							    ON sed.`code` = scrp.`code` 
							WHERE sed.is_deleted = 0 
							  AND scrp.`case_type` = 'p' 
							  AND sed.encounter_nr = ".$db->qstr($this->encounter_nr)."
							  ORDER BY create_date ASC";
	// echo $sql;exit();
		$rs = $db->Execute($sql);
		if($rs){
			if($rs->RecordCount() > 0){
				while($row = $rs->FetchRow()){
					array_push($data, $row);
				}
			}else{
				array_push($data, $row);
			}
		}else{
			array_push($data, $row);
		}

		return $data;
	}
}
#end Nick

header("Content-type: text/html; charset=utf-8");

#added by Nick 2/18/2014
$obj_ICD_ICP = new ICD_ICP($_GET['encounter_nr']);

$icd = $obj_ICD_ICP->getIcd();
$rvs = $obj_ICD_ICP->getRvs();

$icd_description = array();
$icd_codes = array();

$rvs_description = array();
$rvs_code = array();
$rvs_create_date = array();
$laterality = array();

$data_array = array();
$diagnosis = array();
#edited by daryl
foreach ($icd as $key => $value) {
	if(trim($value['description']) != ""){
		$icd_description = $value['description'];
	}
	$icd_codes = $value['code'];
	$diagnosis_split = explode("\n",wordwrap($icd_description, 20, "\n"));

	$temp_code = '';
	foreach($diagnosis_split AS $key => $value){
		if($temp_code == $icd_codes)
			$icd_codes = '';
		$diagnosis[] = array($icd_codes, $value);
		$temp_code = $icd_codes;
	}
	$diagnosis[] = array('', '');

}

foreach ($rvs as $key => $value2) {
	$desc_split = explode("\n",wordwrap($value2['description'], 20, "\n"));
	$temp_code = '';
	foreach($desc_split AS $key => $val){
		if($temp_code == $value2['ops_code']){
			$data_array[] = array($val, '', '', '');
		}
		else{
			$temp_code = $value2['ops_code'];
			$data_array[] = array($val, $value2['ops_code'], $value2['op_date'], $value2['laterality']);
		}		
	}
	$data_array[] = array('', '', '', '');	
}

 $i = 0;
 $x = 0;
 $letter = "a";
 $roman = array('i','ii','iii','iv','v', 'vi', 'vii', 'viii', 'ix', 'x');
 $diagnosis_count = count($diagnosis);
 $procedure_count = count($data_array);

if($diagnosis_count > $procedure_count){
	for ($i=$diagnosis_count; $i < 23 ; $i++) { 
		$diagnosis[] = array('', '');
	}
}else{
	for ($i=$procedure_count; $i < 23 ; $i++) { 
			$data_array[] = array('', '', '', '');	
		}
}

if($diagnosis_count > $procedure_count){
	$temp_code = '';

	foreach($diagnosis AS $key => $value){

		if($value[0]){

			if($temp_code != $value[0]){
				$co_letter = $letter++.".";
			}
			$temp_code = $value[0];

		}
		else{
			$co_letter = '';
			$co_roman ='';
		}

				if($data_array[$key][1]){
					if($temp_code != $data_array[$key][1] && $temp_code != ""){
					$x++;
					}

					$temp_code = $data_array[$key][1];
				}

				if($data_array[$key][1] == '' ){
					$co_roman = '';
				}else{
					$co_roman = numberToRoman($x);
				}


		$data[$i] = array('diagnosis'=>strtoupper($value[1]),
  						'icd_code'=> $value[0],
  						'letter'=>$co_letter,
  						'roman'=>strtolower($co_roman),
  						'rel_procedure'=>strtoupper($data_array[$key][0]),
  						'rvs_code'=>$data_array[$key][1],
  						'create_dt'=>$data_array[$key][2],
  						'laterality'=>$data_array[$key][3]
                             );

		$i++;
	 	// $x++;
	 	$total_count += $i;

	}
}
else{
	$temp_code = '';

	foreach($data_array AS $key => $value){
		if($diagnosis[$key][0]){

			if($temp_code != $diagnosis[$key][0]){
				$co_letter = $letter++.".";
				// $co_roman = $x++;
			}
			$temp_code = $diagnosis[$key][0];

		}
		else{
			$co_letter = '';
			$co_roman = '';
		}


			if($value[1]){
					$x++;
					$temp_code = $value[1];
				}

				if($value[1] == ''){
					$co_roman = '';
				}else{
					$co_roman = numberToRoman($x);
				}

		$data[$i] = array('diagnosis'=>strtoupper($diagnosis[$key][1]),
  						'icd_code'=> $diagnosis[$key][0],
  						'letter'=>$co_letter,
  						'roman'=>strtolower($co_roman),
  						'rel_procedure'=>strtoupper($value[0]),
  						'rvs_code'=>$value[1],
  						'create_dt'=>$value[2],
  						'laterality'=>$value[3]
                             );

		$i++;
	 	// $x++;
	 	$total_count += $i;
	}
}
// // echo $total_count ;exit();
// for ($ii=$total_count; $ii < 200; $ii++) { 
// 	$data[$ii] = array('diagnosis'=>'aaa',
//   						'icd_code'=> 'bbbb',
//   						'letter'=>'cccc',
//   						'roman'=>'dddd',
//   						'rel_procedure'=>'eee',
//   						'rvs_code'=>'ffff',
//   						'create_dt'=>'gggg',
//   						'laterality'=>'hhhhh'
//                              );
// }
	


function numberToRoman($num) 
 {
     $n = intval($num);
     $result = '';
 
     $lookup = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400,
     'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40,
     'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
 
     foreach ($lookup as $roman => $value) 
     {
         $matches = intval($n / $value);
 
         $result .= str_repeat($roman, $matches);
 
         $n = $n % $value;
     }
 
     return $result;
 }

$cf2 = new cf1_page1();

	$cf2->setData($_GET['encounter_nr']);
	$cf2->addPart1and2();
	$params = $cf2->parameter();

 showReport('CF2_page1',$params,$data,"PDF");



?>