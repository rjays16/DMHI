<?php 
/**
 * @author : jeff 05/15/2018
 * Eclaims Status Report E-Claims Side.
 */

error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require_once('roots.php');

require_once($root_path.'include/inc_environment_global.php');
include_once($root_path.'include/care_api_classes/class_globalconfig.php');
require_once($root_path.'include/inc_jasperReporting.php');
require_once $root_path.'include/care_api_classes/class_hospital_admin.php';

	$dateFrom = $_GET['dateFrom'];
	$dateTo = $_GET['dateTo'];
	$userId = $_GET['user'];

	$from_date = date("Y-m-d H:i:s", strtotime($dateFrom));
	$to_date = date("Y-m-d H:i:s",strtotime($dateTo));
	// var_dump($dateFrom);die;	
	$hospObj = new GlobalConfig();
	$hospResult = $hospObj->getEclaimsStatusInfo();
	$hospData = explode("::",$hospResult);

	// SQL query for generation of report data
	global $db;
	$sql = "SELECT 
			  trans.transmit_no AS transmitNo,
			  trans.transmit_dte AS transmitDate,
			  claim.encounter_nr AS encounter_nr,
			  claim.claim_series_lhio AS series,
			  cp.name_first AS firstName,
			  cp.name_last AS lastName,
			  IFNULL(
			    ce.admission_dt,
			    ce.encounter_date
			  ) AS admissionDate,
			  IFNULL(
			    ce.discharge_date,
			    sbe.bill_dte
			  ) AS dischargeDate,
			  sbc.package_id AS packageId,
			  sbc.rate_type AS rateType,
			  IFNULL(stat.status,'PENDING') AS stats
			FROM
			  seg_transmittal AS trans 
			  LEFT JOIN seg_eclaims_claim AS claim 
			    ON trans.transmit_no = claim.transmit_no 
			  LEFT JOIN seg_eclaims_claim_status AS stat 
			    ON claim.id = stat.id 
			  LEFT JOIN care_encounter AS ce 
			    ON ce.encounter_nr = claim.encounter_nr 
			  LEFT JOIN care_person AS cp 
			    ON cp.pid = ce.pid 
			  LEFT JOIN seg_billing_encounter AS sbe 
			    ON sbe.encounter_nr = ce.encounter_nr
			  LEFT JOIN seg_billing_caserate AS sbc 
			    ON sbc.bill_nr = sbe.bill_nr 
			WHERE trans.transmit_dte 
				BETWEEN " . $db->qstr($from_date) . " 
				AND "      . $db->qstr($to_date). " 
				AND ce.`is_discharged` = '1'
				AND sbe.`is_deleted` IS NULL
			ORDER BY trans.transmit_dte DESC";

	$rs = $db->Execute($sql);
	$num_rows = $db->GetOne("SELECT FOUND_ROWS()");
	$data = array();
	$i = 0;
// echo $sql; exit();
// var_dump($sql);die;
	// Functions container in jrxml
	if (is_object($rs)) {
		if ($rs->RecordCount()) {
			while ($row = $rs->FetchRow()) {

				$data[$i] = array(
			                'num' => $i+1,
			                'transmittalNo' => $row['transmitNo'],
			                'transmittalDate' => $row['transmitDate'],
			                'encounterNo' => $row['encounter_nr'],
			                'claimSeries' => $row['series'],
			                'patientName' => $row['lastName'] .", ". $row['firstName'],
			                'admissionDate' => $row['admissionDate'],
			                'dischargeDate' => $row['dischargeDate'],
			                'case1' => $row['packageId'],
			                'case2' => ($row['rateType'] == '1')? 'FIRST' : 'SECOND' ,
			                'status' => $row['stats'],
	            	);
	            $i++;
			}
		}else{
			$data[0]['transmittalNo'] = "No data.";
		}
	}else{
		$data[0]['transmittalNo'] = "No data.";
	}

	// Parameters in jrxml
	$baseDir = dirname(dirname(dirname(dirname(__FILE__)))).'/';
	$logo_path = $baseDir.'gui/img/logos/dmhi_logo.jpg';
	// var_dump($logo_path);die;
	$params = array(
		'hospitalName' => $hospData[0],
		'hospitalAddress' => $hospData[1],
		'reportTitle' => $hospData[2],
		'dateFrom' => $from_date,
		'dateTo' => $to_date,
		'user' => $userId,
		'noOfRecords' => $num_rows,
		'dmhi_logo' => $logo_path
	);
	
	showReport('eclaims_status_report',$params,$data,'PDF'); //<-- Method for calling report generator using required paramaters