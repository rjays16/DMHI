<?php
/**
* SegHIS - Hospital Information System
* Enhanced by Segworks Technologies Corporation
* Transmittal Letter
*/
#create by daryl
#transmittal jasper reports
define('DEFAULT_HCAREID', 18);
define('DEFAULT_NBPKG_RATE', 1750);
define('DEFAULT_NBPKG_NAME','NEW BORN');//Added By Jarel 12/09/2013
define('WELLBABY', 12); 

error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');

require_once($root_path.'include/inc_jasperReporting.php');
require_once($root_path.'include/inc_environment_global.php');

include_once($root_path."include/care_api_classes/class_hospital_admin.php");
include_once($root_path."include/care_api_classes/class_insurance.php");

require($root_path.'/modules/repgen/themes/dmc/dmc2.php');
global $db;

$objInfo = new Hospital_Admin();
$hosp = $objInfo->getHospitalInfo();
$insurance = new Insurance();



if ($row = $objInfo->getAllHospitalInfo()) {
    $row['hosp_agency'] = strtoupper($row['hosp_agency']);
    $row['hosp_name']   = strtoupper($row['hosp_name']);
    //$row['insurance_name'] = strtoupper(($row['insurance_name']));
}
else {
    $row['hosp_country'] = "Republic of the Philippines";
    $row['hosp_agency']  = "DEPARTMENT OF HEALTH";
    $row['hosp_name']    = "DAVAO MEDICAL CENTER";
    $row['hosp_addr1']   = "JICA Bldg., JP Laurel Avenue, Davao City";
}

if (isset($_GET['nr']) && $_GET['nr']) 
	$transmit_no = $_GET['nr'];
else 
	$transmit_no = "";

if (isset($_GET['trdte']) && $_GET['trdte'])
	$transmit_date = strftime("%B %d, %Y", $_GET['trdte']);
else
	$transmit_date = strftime("%B %d, %Y");

if (isset($_GET['class']) && $_GET['class'])
	$classification = $_GET['class'];
else
	$classification = "";

if (isset($_GET['hcare_id']))
	$hcare_id = $_GET['hcare_id'];


	switch ($hosp["hosp_type"]) {
						case "TH":
							$hosptype = "Tertiary";
							break;

						case "SH":
							$hosptype = "Secondary";
							break;

						default:
							$hosptype = "Level 1";
							break;
					}


				if($caseType){
						$case_type_lbl = "Case Type: ".$caseType." Cases";
					}

$params = array("hosp_country"=>$row['hosp_country'],
                "hosp_agency"=>$row['hosp_agency'],
                "hosp_name"=>$row['hosp_name'],
                "hosp_addr1"=>$row['hosp_addr1'],
                "transmittal_no"=>$transmit_no,
                "classification"=>getClassificationDesc($classification),
                "transmittal_date"=>$transmit_date, 
                "signatory"=>strtoupper($hosp["authrep"]), 
                "signatory_title"=>$hosp["designation"] ,
                "philhealth_accreno"=>$insurance->getAccreditationNo(DEFAULT_HCAREID),
                "hosp_category"=>$hosptype ,
                "authorized_bedcap"=>($hosp["bed_capacity"] == 0) ? " " : $hosp["bed_capacity"] ,
                "phic_empno"=>$insurance->getHospitalEmployerNo(DEFAULT_HCAREID) ,
                "tax_accno"=>$hosp["tax_acctno"],
                "case_type"=>$case_type_lbl,
                "insurance_name"=>getInsuranceName($hcare_id),

               );

if (isset($_GET['fromdte']) && $_GET['fromdte']) {
	$from_date = strftime("%B %d, %Y", $_GET['fromdte']);
	$frmdte = strftime("%Y-%m-%d", $_GET['fromdte']);
}
else
	$from_date = strftime("%B %d, %Y");


  $ii = 0;
				if ($transmit_no != '') {
					$strSQL = "select cp.pid, h.hcare_id, t.encounter_nr, cpi.insurance_nr, is_principal, memcategory_desc, cp.name_last, cp.name_first, cp.name_middle, date_format((case when admission_dt is null or admission_dt = '' then encounter_date else admission_dt end), '%b %e, %Y %l:%i%p') as date_admission, \n
													 date_format(str_to_date(ce.mgh_setdte, '%Y-%m-%d %H:%i:%s'), '%b %e, %Y %l:%i%p') as date_discharge, acc_coverage, \n
													 med_coverage, xlo_coverage, hci_coverage, or_fee, pf_visit, surgeon_coverage, anesth_coverage, patient_claim \n
												from (((((seg_transmittal as h inner join seg_transmittal_details as d on h.transmit_no = d.transmit_no) \n
													 inner join care_encounter as ce on d.encounter_nr = ce.encounter_nr) inner join care_person as cp on ce.pid = cp.pid) \n
													 inner join care_person_insurance as cpi on cpi.pid = ce.pid and cpi.hcare_id = h.hcare_id) \n
													 inner join (select encounter_nr, hcare_id, sum(total_acc_coverage) as acc_coverage,sum(total_med_coverage) as med_coverage, sum(total_srv_coverage + total_msc_coverage) as xlo_coverage, sum(total_services_coverage) as hci_coverage, \n
																					sum(total_ops_coverage) as or_fee, sum(total_d1_coverage + total_d2_coverage) as pf_visit, sum(total_d3_coverage) as surgeon_coverage, sum(total_d4_coverage) as anesth_coverage \n
																					from seg_billing_coverage as sbc inner join seg_billing_encounter as sbe on (sbc.bill_nr = sbe.bill_nr AND sbe.is_deleted IS NULL) \n
																					group by encounter_nr, hcare_id) as t on \n
															t.encounter_nr = d.encounter_nr and t.hcare_id = h.hcare_id) \n
													 left join (seg_encounter_memcategory as sem inner join seg_memcategory as sm on sem.memcategory_id = sm.memcategory_id) \n
															on sem.encounter_nr = d.encounter_nr \n
												where h.transmit_no = '$transmit_no' ".(($classification == '') || ($classification == '0') ? '' : "and sem.memcategory_id = $classification")." \n
												order by cp.name_last, cp.name_first, cp.name_middle";
				}
				else {
					$strSQL = "select DATE(h.transmit_dte) as transmitdate, cp.pid, h.hcare_id, t.encounter_nr, cpi.insurance_nr, is_principal, memcategory_desc, cp.name_last, cp.name_first, cp.name_middle, date_format((case when admission_dt is null or admission_dt = '' then encounter_date else admission_dt end), '%b %e, %Y %l:%i%p') as date_admission, \n
													 date_format(str_to_date(ce.mgh_setdte, '%Y-%m-%d %H:%i:%s'), '%b %e, %Y %l:%i%p') as date_discharge, acc_coverage, \n
													 med_coverage, xlo_coverage, or_fee, pf_visit, surgeon_coverage, anesth_coverage, patient_claim \n
												from (((((seg_transmittal as h inner join seg_transmittal_details as d on h.transmit_no = d.transmit_no) \n
													 inner join care_encounter as ce on d.encounter_nr = ce.encounter_nr) inner join care_person as cp on ce.pid = cp.pid) \n
													 inner join care_person_insurance as cpi on cpi.pid = ce.pid and cpi.hcare_id = h.hcare_id) \n
													 inner join (select encounter_nr, hcare_id, sum(total_acc_coverage) as acc_coverage,sum(total_med_coverage) as med_coverage, sum(total_srv_coverage + total_msc_coverage) as xlo_coverage, \n
																					sum(total_ops_coverage) as or_fee, sum(total_d1_coverage + total_d2_coverage) as pf_visit, sum(total_d3_coverage) as surgeon_coverage, sum(total_d4_coverage) as anesth_coverage \n
																					from seg_billing_coverage as sbc inner join seg_billing_encounter as sbe on (sbc.bill_nr = sbe.bill_nr AND sbe.is_deleted IS NULL) \n
																					group by encounter_nr, hcare_id) as t on \n
															t.encounter_nr = d.encounter_nr and t.hcare_id = h.hcare_id) \n
													 left join (seg_encounter_memcategory as sem inner join seg_memcategory as sm on sem.memcategory_id = sm.memcategory_id) \n
															on sem.encounter_nr = d.encounter_nr \n
												where DATE(h.transmit_dte) >= DATE('".$frmdte."') and DATE(h.transmit_dte) <= DATE('".date('Y-m-d',strtotime($this->todte))."')
												order by DATE(h.transmit_dte), cp.name_last, cp.name_first, cp.name_middle";
				}
				// echo $strSQL; exit();
				$result=$db->Execute($strSQL);
				$count = $result->RecordCount();
				$db->SetFetchMode(ADODB_FETCH_ASSOC);
				if ($result) {
						$Data=array();
						if($caseType){

							if($caseType=="Surgical"){
								$ct = '1';
								$type = 'p';
							}

							if($caseType=="Medical"){
								$ct = '0';
								$type = 'm';
							}

						while ($row=$result->FetchRow()) {

								$encNr = $row["encounter_nr"];
								
								$ctSQL ="SELECT pid , case_type , hf, pf, outside FROM 
							        (
							        SELECT
							          ce.`pid`,
							          p.`case_type`,
							          SUM(IF(sbc.`rate_type`='1' , p.`hf`, p.`shf`)) AS  hf,
							          SUM(IF(sbc.`rate_type`='1' , p.`pf`, p.`spf`)) AS pf, 
							          SUM(IFNULL(ser.`total_meds`,0) + IFNULL(ser.`total_xlo`,0)) AS outside
							        FROM
							          care_encounter ce 
							          INNER JOIN seg_billing_encounter sbe 
							            ON ce.`encounter_nr` = sbe.`encounter_nr` 
							          INNER JOIN seg_billing_caserate sbc 
							            ON sbe.`bill_nr` = sbc.`bill_nr` 
							            AND sbe.`is_deleted` IS NULL 
							            AND sbe.`is_final` = '1' 
							          INNER JOIN seg_case_rate_packages p 
							            ON p.`code` = sbc.`package_id`
							          LEFT JOIN seg_encounter_reimbursed ser
										ON ser.`encounter_nr` = sbe.`encounter_nr` 
							        WHERE ce.encounter_nr = ".$db->qstr($encNr)."\n
							        GROUP BY ce.`encounter_nr`
							         ) AS t WHERE t.case_type = ".$db->qstr($type);
								$ctResult = $db->Execute($ctSQL);
								$ctCount = $ctResult->RecordCount();

								if($ctCount){

								$patient =concatname((is_null($row['name_last']) ? '' : $row['name_last']),
																						 (is_null($row['name_first']) ? '' : $row['name_first']),
																						 (is_null($row['name_middle']) ? '' : $row['name_middle']));

								if ($row["is_principal"] == 0)
										$member = getPrincipalHolder($row["pid"], $row["hcare_id"], $row["insurance_nr"]);
								else
										$member = $patient;

								#Do the Work around for transmittal Letter Billed Using ACR NEED TO REFACTOR
								if($pkg1 = $ctResult->FetchRow()){
									$total_2 = (is_null($pkg1['pf']) ? '' : $pkg1['pf'] );
									$total_1 = (is_null($pkg1['hf']) ? '' : $pkg1['hf'] );
									$outside = $pkg1['outside']; 
								}

								if($total_1=='')
								{
									$total_1 = (is_null($row["acc_coverage"]) ? 0 : $row["acc_coverage"]) +
														 (is_null($row["med_coverage"]) ? 0 : $row["med_coverage"]) +
														 (is_null($row["xlo_coverage"]) ? 0 : $row["xlo_coverage"]) +
														 (is_null($row["or_fee"]) ? 0 : $row["or_fee"]) + 
														 (is_null($row["hci_coverage"]) ? 0 : $row["hci_coverage"]) ;

										
									$total_2 = (is_null($row["pf_visit"]) ? 0 : $row["pf_visit"]) +
														 (is_null($row["surgeon_coverage"]) ? 0 : $row["surgeon_coverage"]) +
														 (is_null($row["anesth_coverage"]) ? 0 : $row["anesth_coverage"]);

									$outside = 0; 
								}
								

							
									if ($transmit_no != '')
											  $data[$ii] = array('insurance_nr'=>$row["insurance_nr"],
									                              'patient'=>strtoupper($patient),
									                              'member'=>utf8_decode(strtoupper($member)),
									                              'date_admission'=>$row["date_admission"],
									                              'date_discharge'=>$row["date_discharge"],
									                              'shosp_charges'=>number_format($total_1, 2, '.',','),
									                              'sprof_fee'=>number_format($total_2, 2, '.',','),
									                              'sgrand_total'=>number_format($total_1 + $total_2, 2, '.',','),
									                              'hosp_gtotal'=>number_format($hosp_gtotal, 2, '.',','),
									                              'pf_gtotal'=>number_format($pf_gtotal, 2, '.',','),
									                              'grand_gtotal'=>number_format($grand_gtotal, 2, '.',','),
									                              'final_diag'=>$diag,
									                              'count_num'=>$ii+1,
									                              'prepared_by'=>strtoupper($_SESSION['sess_user_name'])
									                              );
										// $this->Data[]=array(
										// 		$row["insurance_nr"],
										// 		strtoupper($patient),							// made uppercase .... per request by billing .... 06.30.2010 by LST
										// 		strtoupper($member),
										// 		$row["date_admission"],
										// 		$row["date_discharge"],
										// 		number_format($total_1, 2, '.',','),
										// 		number_format($total_2, 2, '.',','),
										// 		number_format($total_1 + $total_2, 2, '.',','),
										// 		number_format($outside, 2, '.',',')
										// );
									else
										// $this->Data[]=array(
										// 		$row["transmitdate"],
										// 		$row["insurance_nr"],
										// 		strtoupper($patient),
										// 		strtoupper($member),
										// 		$row["date_admission"],
										// 		$row["date_discharge"],
										// 		number_format($total_1, 2, '.',','),
										// 		number_format($total_2, 2, '.',','),
										// 		number_format($total_1 + $total_2, 2, '.',','),
										// 		number_format($outside, 2, '.',',')
										// );
										  $data[$ii] = array('insurance_nr'=>$row["insurance_nr"],
									                              'patient'=>strtoupper($patient),
									                              'member'=>utf8_decode(strtoupper($member)),
									                              'date_admission'=>$row["date_admission"],
									                              'date_discharge'=>$row["date_discharge"],
									                              'shosp_charges'=>number_format($total_1, 2, '.',','),
									                              'sprof_fee'=>number_format($total_2, 2, '.',','),
									                              'sgrand_total'=>number_format($total_1 + $total_2, 2, '.',','),
									                              'hosp_gtotal'=>number_format($hosp_gtotal, 2, '.',','),
									                              'pf_gtotal'=>number_format($pf_gtotal, 2, '.',','),
									                              'grand_gtotal'=>number_format($grand_gtotal, 2, '.',','),
									                              'final_diag'=>$diag,
									                              'count_num'=>$ii+1,
									                              'prepared_by'=>strtoupper($_SESSION['sess_user_name'])
									                              );
								}
						}
				

						}else{
							while ($row=$result->FetchRow()) {
								$encNr = $row["encounter_nr"];
									
									$sqlPkg2 =	"SELECT pid , case_type , hf, pf, outside FROM 
										        (
										        SELECT
										          ce.`pid`,
										          p.`case_type`,
										          SUM(IF(sbc.`rate_type`='1' , p.`hf`, p.`shf`)) AS  hf,
										          SUM(IF(sbc.`rate_type`='1' , p.`pf`, p.`spf`)) AS pf,
										          SUM(IFNULL(ser.`total_meds`,0) + IFNULL(ser.`total_xlo`,0)) AS outside
										        FROM
										          care_encounter ce 
										          INNER JOIN seg_billing_encounter sbe 
										            ON ce.`encounter_nr` = sbe.`encounter_nr` 
										          INNER JOIN seg_billing_caserate sbc 
										            ON sbe.`bill_nr` = sbc.`bill_nr` 
										            AND sbe.`is_deleted` IS NULL 
										            AND sbe.`is_final` = '1' 
										          INNER JOIN seg_case_rate_packages p 
										            ON p.`code` = sbc.`package_id`
										          LEFT JOIN seg_encounter_reimbursed ser
													ON ser.`encounter_nr` = sbe.`encounter_nr` 
										        WHERE ce.encounter_nr = ".$db->qstr($encNr)."\n
										        GROUP BY ce.`encounter_nr`
										         ) AS t ";
								
									$flag = false;
							        if ($result2 = $db->Execute($sqlPkg2)) {
							            if ($result2->RecordCount()) {
							                if ($row1 = $result2->FetchRow()) {
							                    $total_2 = (is_null($row1['pf']) ? '' : $row1['pf'] );
							                    $total_1 =(is_null($row1['hf']) ? '' : $row1['hf'] );
							                    $outside = $row1['outside'] ;
							                    $flag = true;
							                }
							            }
							        }

								$patient = concatname((is_null($row['name_last']) ? '' : $row['name_last']),
																						 (is_null($row['name_first']) ? '' : $row['name_first']),
																						 (is_null($row['name_middle']) ? '' : $row['name_middle']));

								if ($row["is_principal"] == 0)
										$member = getPrincipalHolder($row["pid"], $row["hcare_id"], $row["insurance_nr"]);
								else
										$member = $patient;

								// if(!$flag){
									$total_1 = (is_null($row["acc_coverage"]) ? 0 : $row["acc_coverage"]) +
														 (is_null($row["med_coverage"]) ? 0 : $row["med_coverage"]) +
														 (is_null($row["xlo_coverage"]) ? 0 : $row["xlo_coverage"]) +
														 (is_null($row["or_fee"]) ? 0 : $row["or_fee"])+ 
														 (is_null($row["hci_coverage"]) ? 0 : $row["hci_coverage"]) ;


									$total_2 = (is_null($row["pf_visit"]) ? 0 : $row["pf_visit"]) +
											   (is_null($row["surgeon_coverage"]) ? 0 : $row["surgeon_coverage"]) +
												(is_null($row["anesth_coverage"]) ? 0 : $row["anesth_coverage"]);
									$outside = 0;
								
								// }

	
									
						
									if ($transmit_no != ''){
										$diag = getdiagnosis($encNr);
										 $hosp_gtotal += $total_1;
										 $pf_gtotal += $total_2;
										 $grand_gtotal += $total_1 + $total_2;

										 // echo number_format($total_1, 2, '.',',');
										  $data[$ii] = array('insurance_nr'=>$row["insurance_nr"],
									                              'patient'=>strtoupper($patient),
									                              'member'=>utf8_decode(strtoupper($member)),
									                              'date_admission'=>$row["date_admission"],
									                              'date_discharge'=>$row["date_discharge"],
									                              'shosp_charges'=>number_format($total_1, 2, '.',','),
									                              'sprof_fee'=>number_format($total_2, 2, '.',','),
									                              'sgrand_total'=>number_format($total_1 + $total_2, 2, '.',','),
									                              'hosp_gtotal'=>number_format($hosp_gtotal, 2, '.',','),
									                              'pf_gtotal'=>number_format($pf_gtotal, 2, '.',','),
									                              'grand_gtotal'=>number_format($grand_gtotal, 2, '.',','),
									                              'final_diag'=>$diag,
									                              'count_num'=>$ii+1,
									                              'prepared_by'=>strtoupper($_SESSION['sess_user_name'])


							                             );
									}

										     

									else
										  $data[$i] = array('insurance_nr'=>$row["insurance_nr"],
									                              'patient'=>strtoupper($patient),
									                              'member'=>utf8_decode(strtoupper($member)),
									                              'date_admission'=>$row["date_admission"],
									                              'date_discharge'=>$row["date_discharge"]
									                              // 'hosp_charges'=>number_format($total_1, 2, '.',','),
									                              // 'prof_fee'=>number_format($total_2, 2, '.',','),
									                              // 'grand_total'=>number_format($total_1 + $total_2, 2, '.',',')
							                             );
							
										   $ii++;  
								}
						}	
						
						
				}
				else
						echo $db->ErrorMsg();
		

// exit();


		function getClassificationDesc($classification) {
			global $db;

				if (($classification != '') && ($classification != '0')) {
					$strSQL = "select memcategory_desc from seg_memcategory
												where memcategory_id = $classification";
					$sDesc = '';
					if ($result=$db->Execute($strSQL)) {
							$db->SetFetchMode(ADODB_FETCH_ASSOC);
							 if ($row = $result->FetchRow()) {
									$sDesc = (is_null($row["memcategory_desc"])) ? "" : $row["memcategory_desc"];
							 }
					}
				}
				else
					$sDesc = "ALL MEMBER CLASSIFICATIONS";
				return $sDesc;
		}

function getInsuranceName($hcare_id){
	global $db;

	return $db->GetOne("SELECT firm.`name` FROM care_insurance_firm firm WHERE firm.`hcare_id` = ".$db->qstr($hcare_id) );
}

	function concatname($slast, $sfirst, $smid) {
				$stmp = "";

				if (!empty($slast)) $stmp .= $slast;
				if (!empty($sfirst)) {
						if (!empty($stmp)) $stmp .= ", ";
						$stmp .= $sfirst;
				}
				if (!empty($smid)) {
						if (!empty($stmp)) $stmp .= " ";
						$stmp .= $smid;
				}
				return($stmp);
		}

function getPrincipalHolder($s_pid, $nhcareid, $ins_nr) {
				global $db;

				$sprincipal = "";
				$pinsure_obj = new PersonInsurance($s_pid);
				$row = $pinsure_obj->is_member_info_editable($s_pid, $nhcareid, $ins_nr);
				if (!$row) {
						$strSQL = "select cp.pid, cp.name_last, cp.name_first, cp.name_middle \n
													from care_person_insurance as cpi0 inner join care_person as cp on cpi0.pid = cp.pid \n
													where exists (select * from care_person_insurance as cpi1 \n
																					 where cpi1.pid = '$s_pid' and cpi1.hcare_id = $nhcareid \n
																							and cpi1.pid <> cpi0.pid and cpi1.hcare_id = cpi0.hcare_id \n
														 and cpi1.insurance_nr = cpi0.insurance_nr) \n
														 and cpi0.is_principal <> 0";

						if ($result = $db->Execute($strSQL)) {
								if ($result->RecordCount()) {
										while ($row = $result->FetchRow())
												$sprincipal = concatname((is_null($row['name_last']) ? '' : $row['name_last']),
																												(is_null($row['name_first']) ? '' : $row['name_first']),
																												(is_null($row['name_middle']) ? '' : $row['name_middle']));
								}
						}
				}
				else {
						$sprincipal =concatname((is_null($row['last_name']) ? '' : $row['last_name']),
																						(is_null($row['first_name']) ? '' : $row['first_name']),
																						(is_null($row['middle_name']) ? '' : $row['middle_name']));
				}

				return($sprincipal);
		}


#edited by daryl
		//query to get the philhealt claim
		function getdiagnosis($enc) {
			global $db;
			$senc = $db->qstr($enc);
			
					$strSQL = "SELECT b.`package_id` FROM seg_billing_encounter a 
								INNER JOIN seg_billing_caserate b
								ON a.`bill_nr` = b.`bill_nr`
								WHERE a.`is_deleted` IS NULL 
								AND a.`is_final` = '1'
								AND a.`encounter_nr` = $senc";

					if ($result=$db->Execute($strSQL)) {
							$db->SetFetchMode(ADODB_FETCH_ASSOC);
									while ($row = $result->FetchRow()){
									$sDesc .=  $row["package_id"]." , ";
							 }
					}
				
				else
					$sDesc = "";
				return $sDesc;
		}


if ($hcare_id == PHIC_ID)
	$template = 'TransmittalLetter';
else
	$template = 'TransmittalLetter_hmo';


showReport($template, $params, $data, "PDF");

?>
