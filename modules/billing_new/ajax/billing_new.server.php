<?php
require('./roots.php');
require($root_path.'include/inc_environment_global.php');
require_once($root_path.'include/inc_date_format_functions.php');
require_once($root_path.'include/care_api_classes/billing/class_billing_new.php');
require_once($root_path.'include/care_api_classes/billing/class_billareas.php');
require_once($root_path.'include/care_api_classes/billing/class_bill_info.php');
require_once($root_path.'include/care_api_classes/billing/class_ops_new.php');
require_once($root_path.'include/care_api_classes/dialysis/class_dialysis.php');
require_once($root_path.'include/care_api_classes/class_ward.php');
require_once($root_path.'include/care_api_classes/class_person.php');
require_once($root_path.'include/care_api_classes/class_encounter.php');
require_once($root_path."include/care_api_classes/class_hospital_admin.php");
require_once($root_path."include/care_api_classes/class_caserate_icd_icp.php");
require_once($root_path.'include/care_api_classes/curl/class_curl.php'); //added by janken
require_once($root_path.'include/care_api_classes/billing/class_transmittal.php');//added by Nick, 2/24/2014
require_once($root_path."include/care_api_classes/class_company.php");

require_once($root_path.'include/care_api_classes/class_labservices_transaction.php');
include_once($root_path.'include/care_api_classes/class_personell.php');
include_once($root_path.'include/care_api_classes/class_department.php');

require_once($root_path.'modules/billing_new/ajax/billing_new.common.php');
require_once($root_path.'include/care_api_classes/inventory/class_inventory.php');

//define('IT_ER','00002408');
//define('IT_IP','00002407');
define("MEDS", "M");
define("SUPPLIES", "S");

function getAdjustedDate($date){
	if (strcmp($date, "0000-00-00 00:00:00") != 0)
			$tmp_dte = $date;
	else
			$tmp_dte = strftime("%Y-%m-%d %H:%M:%S");

	return strftime("%Y-%m-%d %H:%M:%S", strtotime("-1 second", strtotime($tmp_dte)));
}
/*-------------Accommodation Dialog-------------*/
function setWardOptions() {
	$objResponse = new xajaxResponse();

	$objwrd = new Ward();
	$wards = $objwrd->getAllActiveWards();
	if ($wards) {
		$objResponse->call("js_ClearOptions","wardlist");
		$objResponse->call("js_ClearOptions","roomlist");

		if ($wards->RecordCount()) {
			$objResponse->call("js_AddOptions","wardlist","- Select Ward -", 0);
			$objResponse->call("js_AddOptions","roomlist","- Select Room -", 0);

			while ($row = $wards->FetchRow()) {
				$objResponse->call("js_AddOptions", "wardlist", ucwords($row['name']), $row['nr']);
			}
		}
	}
	else
		$objResponse->alert("ERROR: Cannot retrieve available wards!");

	return $objResponse;
}//end setWardOptions

function setWardRooms($ward_nr) {
	$objResponse = new xajaxResponse();

	$objwrd = new Ward();
	$wards = $objwrd->getRoomsData($ward_nr);
	if ($wards) {
		if ($wards->RecordCount()) {
			$objResponse->call("js_ClearOptions","roomlist");
			$objResponse->call("js_AddOptions","roomlist","- Select Room -", 0);
			while ($row = $wards->FetchRow()) {
				$objResponse->call("js_AddOptions", "roomlist", $row['room_nr'], $row['nr']);
			}
		}
	}
	else
		$objResponse->alert("ERROR: Cannot retrieve available rooms!");

	return $objResponse;
}//end setWardRooms

function getAccommodationRate($room_info) {

	$objResponse = new xajaxResponse();
	$objBilling = new Billing();

	$rm_rate = $objBilling->getRoomRate($room_info);
	$objResponse->call('assignRmRate', number_format($rm_rate, 2, '.', ''));

	return $objResponse;
}//end getRoomRate

//added by shandy
//updated by Nick 05-12-2014 - added tooltip
//updated by Nick 05-15-2014 - load all categories for selection
function getMemCategory(&$objResponse, &$objBilling) {
	$sDesc = $objBilling->getMemCategoryDesc();
	$mem_id = $objBilling->getMemCategoryID();
	$hist = $objBilling->getMemCatHist();
	$memCats = $objBilling->getMembershipTypes();
	$objResponse->call('js_ClearOptions','optMemCat');
	foreach ($memCats as $key => $memCat) {
		$desc = htmlentities($memCat['memcategory_desc']);
		$id = htmlentities($memCat['memcategory_id']);
		$objResponse->call('js_AddOptions','optMemCat',$desc,$id);
	}
	$objResponse->call('js_setOption','optMemCat',$mem_id);
	$objResponse->call('assignMemCategDesc', $sDesc,$mem_id);
	$objResponse->call('addTooltip','mcategdesc',$hist['modify_id'],date('Y-m-d h:i A',strtotime($hist['modify_dt'])));
	//$objResponse->alert('test billing server ='. $sDesc);
	return $objResponse;
}

function GetPhicNumber($enc_nr){
	global $db;

	$phic_nr = $db->GetOne("SELECT fn_get_phic_number('".$enc_nr."') AS `phic_nr`");

return $phic_nr;
}
// end by shandy
function saveAccommodation($aFormValues, $bill_dt) {
	$objResponse = new xajaxResponse();
	$objBilling = new Billing();

	$occ_date_from = $aFormValues['datefrom'];
	$occ_date_to = $aFormValues['dateto'];

	if ($aFormValues['encounter_nr'] != '') {
		$tmp_dte = getAdjustedDate($bill_dt);
        $sesID = $_SESSION['sess_user_name'];
        $sesUN = $_SESSION['sess_user_name'];
        $date_from = date('Y-m-d', strtotime($occ_date_from));
        $date_to = date('Y-m-d', strtotime($occ_date_to));
        $ndays = ((abs(strtotime($occ_date_to)-strtotime($occ_date_from)))/(60*60*24));
        if($date_from==$date_to){
        	$ndays = 1;
        }
        $aFormValues['occupydatefrom'] = $date_from;
        $aFormValues['occupydateto'] = $date_to;
        $aFormValues['days'] = $ndays;
        $aFormValues['sessionID'] = $sesID;
        $aFormValues['sessionUN'] = $sesUN;
        $aFormValues['createdate'] = $tmp_dte;

        $saveok = $objBilling->saveAdditionalAccommodation($aFormValues);
        if($saveok){
        	$objResponse->call("jsRecomputeServices");
        }
	}
	return $objResponse;
}//end saveAccommodation

function populateAccommodation($info){
	$objResponse = new xajaxResponse();
	$objBilling = new Billing();
	
	if($objBilling->isInPatient($info['encounter_nr'])){

		$objResponse->call("showAccommodationList", true);
		$objResponse->call("resetAccommDialogForm");

		$rooms = $objBilling->getAccomodationList($info);
		$objResponse->call("showAccommodationList", true);
		$objResponse->call("jsClearList", "body_accListDetails");
		$total_accom_days = 0;
		$sub_total = 0;
		if(is_array($rooms) && (count($rooms) > 0)){
			foreach ($rooms as $rm) {
				$rm->setRoomRate(number_format($rm->getRoomRate(), 2, '.', ','));
				$total_charge = ($rm->getRoomRate() * $rm->getActualDays());
				$sub_total += $total_charge;
				$total_accom_days += $rm->getActualDays();
				$bnew = trim($objBilling->getPrevEncounterNr($details['encounter_nr']));
				$objResponse->call("jsAccommodationList",
								$rm, number_format($total_charge, 2, '.', ','), (strlen($bnew)==0 ? '': 'none'));
			}
			$lstRefNo = $objBilling->getAccommodationRefno($info);
			if (is_array($lstRefNo) && (count($lstRefNo) > 0)) {
				foreach ($lstRefNo as $lsRef) {
					$objResponse->call("jsAddRefNo", "hdAccommodationRef",$lsRef);
				}
			}
		}else{
			$objResponse->call("jsAccommodationList", 0, 0, FALSE);
		}
		$objResponse->call("setAccSubTotal", number_format($sub_total, 2, '.', ','));
	}else{
		$objResponse->call("showAccommodationList", false);
	}
	$info['admit_dte'] = $objBilling->getActualAdmissionDte($info['encounter_nr']);
	$info['t_days'] = $total_accom_days;
	setupNewAccommodationForm($objResponse, $info);		
	return $objResponse;
}//populateAccommodation

function setupNewAccommodationForm(&$objResponse, $info){

	$from_date = empty($info['admit_dte']) ? date('Y-m-d', strtotime($info['bill_frmdte'])) : date('Y-m-d', strtotime($info['admit_dte']));
    $to_date = empty($info['death_date']) ? date('Y-m-d', strtotime($info['bill_dt'])) : date('Y-m-d', strtotime($info['death_date']));

	$from_date = strftime('%B %d, %Y',strtotime($from_date.' +'.$info['t_days'].' days' ));
	$to_date = strftime('%B %d, %Y',strtotime($to_date));
	 
	$objResponse->call("jsSetupAccommodationForm", $from_date, $to_date);

}//setupNewAccommodationForm

function delAccommodation($room_info) {

	$objResponse = new xajaxResponse();
	$objBilling = new Billing();

	$delOk = $objBilling->deleteAccommodation($room_info);
	
	if($delOk){
		
		$objResponse->call("jsRecomputeServices");
	}

	return $objResponse;
}//end delAccommodation

/*--------------end Accommodation Dialog--------*/

/*-------------Misc Services -------------------*/
function chargeMiscService($data,$area){
	$objResponse = new xajaxResponse();
	$objBilling = new Billing();
	if ($data['enc_nr'] != '') {
		//get Adjust charge date by 1 second earlier than billing date ...
		$data['bill_dt'] = getAdjustedDate($data['bill_dt']);
 		$data['sess_user_name'] = $_SESSION['sess_user_name'];
		$data['msc_charge'] = str_replace(",", "", $data['msc_charge']);
		$data['qty'] = number_format($data['qty'], 0, '', '');
		$saveOk = $objBilling->saveMiscServices($data);
		if($saveOk){
			$saveOk1 = $objBilling->saveMiscServicesDetails($data);
		}
		if($saveOk1){
			$objResponse->call('jsRecomputeServices',$area);
		}
	}
	return $objResponse;
}// end of function chargeMiscService

/**
 * edited by Marc Lua 07/22/2014
 * added inventory stock out
 * @param $data
 * @param $area
 * @return xajaxResponse
 */

function chargePharmaSupply($data,$area) {

    $objResponse = new xajaxResponse();
    $objBilling = new Billing();

    if ($data['enc_nr'] != '') {
        // Adjust charge date by 1 second earlier than billing date ...
        $data['bill_dt'] = getAdjustedDate($data['bill_dt']);
        $data['sess_user_name'] = $_SESSION['sess_user_name'];
        $data['msc_charge'] = str_replace(",", "", $data['msc_charge']);
        $data['qty'] = number_format($data['qty'], 0, '', '');
        $saveOk = $objBilling->savePharmaSupply($data);
        $data['refNo'] = $objBilling->getPharmaChrgRefNo($data['bill_frmdte'],$data['enc_nr']);
        if($saveOk) {
            $entryNo = $objBilling->getPharmaSupplyEntryNo($data['refNo'], $data['code']);
            $saveOk1 = $objBilling->savePharmaSupplyDetails($data);
            $data['refNo'] .= '-' . $entryNo;
        }
        if($saveOk1){
            $objResponse->call('jsRecomputeServices',$area);
            $objResponse->call('jsInventoryStockOut',$data);
        }
    }

    return $objResponse;
}// end of function chargeMedorSupply

/**
 * add back to inventory here as Cancellation
 */
function inventoryStockIn($data) {
    $invHelper = new InventoryHelper();
    $invHelper->addStock($data['itemCode'], $data['areaCode'], $data['qty'], '', $data['refNo'], $data['refNo'], CANCEL);
}

/**
 * pharma sale from billing
 */
function inventoryStockOut($data) {
    $invHelper = new InventoryHelper();
    $invHelper->removeStock($data['code'], $data['qty'], $data['area_code'], $data['refNo'], SALE);
}

//commented by kenneth 12/16/2013 
//function not use
// function populateMiscServices($details){
// 	$objResponse = new xajaxResponse();
// 	$objBilling = new Billing();
	
// 	$objResponse->call("jsClearList", "body_hsListDetails");
// 	$objResponse->call("jsClearList", "hdXLORef");

// 	$hspServicesList = $objBilling->getMiscSrvs();
// 	$sub_total = 0;

// 	if (is_array($hspServicesList) && (count($hspServicesList) > 0)) {
// 		foreach ($hspServicesList as $hsValue) {
// 			$servCharge = number_format(($hsValue->getServQty() * $hsValue->getServPrice()), 2, '.', ',');
// 			$totalCharge = ($hsValue->getServQty() * $hsValue->getServPrice());
// 			$sub_total += $totalCharge;
// 			$hsValue->setServPrice(number_format($hsValue->getServPrice(), 2, '.', ','));
// 			$bnew = trim($objBilling->getPrevEncounterNr($details['encounter_nr']));
// 			$objResponse->call("jsHospitalServices", $hsValue, $servCharge, (strlen($bnew)==0 ? '': 'none'));
// 		}
// 		$lstRefNo = $objBilling->getXLOrefno($details);

// 		if (is_array($lstRefNo) && (count($lstRefNo) > 0)) {
// 			foreach ($lstRefNo as $lsRef) {
// 				$objResponse->call("jsAddRefNo", "hdXLORef",$lsRef);
// 			}
// 		}
// 	}else {
// 		$objResponse->call("jsHospitalServices", 0, 0, FALSE);
// 	}

// 	$objResponse->call("setMiscServices", number_format($sub_total, 2, '.', ','));

// 	return $objResponse;
// }//end populateMiscServices

function delMiscService($details) {

	$bSuccess = false;
	$objResponse = new xajaxResponse();
	$objBilling = new Billing();
	$bSuccess = $objBilling->deleteMiscServices($details);
	if ($bSuccess) {
		$objResponse->call("jsRecomputeServices",'xlo');
	}
	return $objResponse;
}//end delMiscService

function delPharmaSupply($details,$area) {
	$bSuccess = false;
	$objResponse = new xajaxResponse();
	$objBilling = new Billing();
	$bSuccess = $objBilling->deletePharmaSupply($details);

	if ($bSuccess) {
		$objResponse->call("jsRecomputeServices",$area);
		$objResponse->call("jsInventoryStockIn",$bSuccess);
	}

	return $objResponse;
}//end delPharmaSupply

/*---------end Misc Services -------------------*/

/*-------------Drugs and Medicines--------------*/
function populateMeds($enc,$bill_dte,$bill_frmdte,$death_date){

	$objBill = setArguments($enc,$bill_dte,$bill_frmdte,$death_date);
	$objResponse = new xajaxResponse();

	$result = $objBill->getMedsList();
	$result_return = $objBill->getCreditNoteUi($enc,MEDS);
	$objResponse->call("jsClearList", 'body_mdListDetails');
	$objResponse->call("jsClearList", 'hdMedRef');
	if($result){
			while($row=$result->FetchRow())
			{
				$details->ref_nr = $row['refno'];
				$details->srv_code = $row['bestellnum'];
				$details->srv_desc = $row['artikelname'];
				$details->srv_generic = $row['generic'];
				$details->flag = $row['flag'];
				$details->qty = $row['req_qty'];
				$details->srv_price = number_format($row['req_price'] / $row['req_qty'], 2, '.', ',');
				$details->itemcharge = number_format($row['req_price'], 2, '.', ',');
				$details->src = $row['source'];
				$details->total = $row['qty']*$row['price'];
				$details->grand_total += $details->total;
				$details->unused = $row['unused_flag'];
				$details->unused_qty = $row['unused_qty'];
				$details->unused_amnt += $row['unused_qty']*$row['price'];
				if ($refno!=$row['refno']) {
					$details->same=false;
				}else{
					$details->same=true;
				}
				$refno=$row['refno'];
				$objResponse->call("jsMedicineList", json_encode($details) ,FALSE);
			}
		}
		$objResponse->call("setUnusedAmount", (($details->unused_amnt) ? $details->unused_amnt : '0' ));
		$objResponse->call("setMedicine", number_format($details->grand_total, 2, '.', ','));
		$objResponse->call("assignValue","save_total_med_charge",$details->grand_total);

		if ($result_return) {

			foreach ($result_return as $value) {
				$details->ref_nr = '';
				$details->srv_code = $value['bestellnum'];
				$details->srv_desc = '( RETURNED ) '.$value['item_name'];
				$details->srv_generic = $value['generic'];
				$details->flag = '';
				$details->qty = $value['qty'];
				$details->srv_price = number_format($value['charge'], 2, '.', ',');
				$details->itemcharge = number_format($value['credit_note'], 2, '.', ',');
				$details->src = '';
				$details->total = '';
				$details->grand_total = '';
				$details->unused = '';
				$details->unused_qty = '';
				$details->unused_amnt = '';
				
				$objResponse->call("jsMedicineList", json_encode($details) ,FALSE);
			}
			
		}

		return $objResponse;
}//end populateMeds

/*---------end Drugs and Medicines--------------*/

/*------------ Miscellaneous Charges------------*/
function populateMiscCharges($details){
	$objResponse = new xajaxResponse();
	$objBilling = new Billing();

	$lstMiscChrg = $objBilling->getMiscChargesList($details);
	$objResponse->call("jsClearList", "body_mscListDetails");
	$objResponse->call("jsClearList", "hdMiscChargesRef");
	$sub_total = 0;

	if (is_array($lstMiscChrg) && (count($lstMiscChrg) > 0)) {
			foreach ($lstMiscChrg as $mscValue) {
				$total_charge = $mscValue->getMiscChrg()*$mscValue->getMiscQty();
				$sub_total += $total_charge;
				$mscValue->setMiscChrg(number_format($mscValue->getMiscChrg(), 2, '.', ','));
				$bnew = trim($objBilling->getPrevEncounterNr($details['encounter_nr']));
				$objResponse->call("jsMiscellaneousList", $mscValue, number_format($total_charge, 2, '.', ','), (strlen($bnew)==0 ? '': 'none'));
			}
			$lstRefNo = $objBilling->getChargeRefno($details);

			if (is_array($lstRefNo) && (count($lstRefNo) > 0)) {
				foreach ($lstRefNo as $lsRef) {
					$objResponse->call("jsAddRefNo", "hdMiscChargesRef",$lsRef);
				}
			}
		}
		else {
				$objResponse->call("jsMiscellaneousList", NULL,0, false);
		}

	$objResponse->call("setMiscCharges",number_format($sub_total, 2, '.', ','));

	return $objResponse;
}//end populateMiscCharges

function delMiscChrg($data){
	global $db;

	$bSuccess = false;
	$objResponse = new xajaxResponse();
	$objBilling = new Billing();

	$delOk = $objBilling->deleteMiscCharge($data);

	
	if ($delOk) {
		$objResponse->call("jsRecomputeServices",'misc');
	}

	return $objResponse;
}//end delMiscChrg

function chargeMiscChrg($data_misc){
	$objResponse = new xajaxResponse();
	$objBilling = new Billing();
	
	if ($data_misc['enc_nr'] != '') {
		//get Adjust charge date by 1 second earlier than billing date ...
		$data_misc['bill_dt'] = getAdjustedDate($data_misc['bill_dt']);
 		$data_misc['sess_user_name'] = $_SESSION['sess_user_name'];
		$data_misc['msc_charge'] = str_replace(",", "", $data_misc['msc_charge']);
		$data_misc['qty'] = number_format($data_misc['qty'], 0, '', '');
		
		$saveOk = $objBilling->CreateMiscCharge($data_misc);

		if($saveOk){
			$saveOk1 = $objBilling->saveMiscCharge($data_misc);
			
		}
		if($saveOk1)
			$objResponse->call('jsRecomputeServices','misc');
	}

	return $objResponse;
}
/*---------end Miscellaneous Charges------------*/

function hasTransmittal($enc){
	$objTransmittal = new Transmittal;
	$transmit_info = $objTransmittal->getPatientTrasmittalInfo($enc);
	if(is_array($transmit_info) && count($transmit_info) > 0){
		return 1;
	}else{
		return 0;
	}
}

function populateBill($enc,$pid,$bill_dte,$bill_frmdte,$death_date,$firstCode='',$secondCode='')
{
	$objResponse = new xajaxResponse();
	$objBill = new Billing();

	$objResponse->call("assignValue","save_bill_dte",$bill_dte);
	$objResponse->call("assignValue","save_frmdte",$bill_frmdte);
	$objResponse->call("assignValue","save_encounter_nr",$enc);
	$objResponse->call("assignValue","save_current_year",date("Y"));

	if($death_date ==''){
		$death_date = $objBill->getDeathDate($enc);
	}


	if ($billinfo = $objBill->hasSavedBill($enc)) {
		$details->death_date = $death_date;
		$details->bill_nr = $billinfo['bill_nr'];
		$details->bill_dte = $billinfo['bill_dte'];
		$details->bill_frmdte = $billinfo['bill_frmdte'];
		$details->is_final = $billinfo['is_final'];
        $details->hasTransmittal = hasTransmittal($enc);
		if($details->is_final){
			$objResponse->call('disableUI');
		}

		$objBill->setBillArgs($enc,$billinfo['bill_dte'],$billinfo['bill_frmdte'],$death_date,$billinfo['bill_nr']);
		populateBillHeader(&$objResponse,$details);
		// populatePrevBal(&$objResponse,&$objBill,$pid, $enc, $billinfo['bill_dte'], $billinfo['bill_frmdte']);

	} else {
		$objBill->setBillArgs($enc,$bill_dte,$bill_frmdte,$death_date);
		// populatePrevBal(&$objResponse,&$objBill,$pid, $enc, $bill_dte, $from_date);
	}
	if($death_date!=''){
		$dateto = $death_date;
	}else{
		$dateto = ($details->bill_dte) ? $details->bill_dte: $bill_dte;
	}

	$isNewBorn = $objBill->isNewBorn($enc);

	populateAccomodation(&$objResponse,&$objBill,$dateto,$enc);
	getTotalAppliedDiscounts(&$objResponse,$enc);//added by nick, 1/8/2014
	getDiscounts(&$objResponse,$enc);//added by daryl
	getMemCategory(&$objResponse, &$objBill);
	setHasBloodTrans(&$objResponse,$enc);
	populateCaseRate(&$objResponse,&$objBill,$enc,$firstCode,$secondCode,$isNewBorn);
	//populateMisc(&$objResponse,&$objBill);
	//populateXLO(&$objResponse,&$objBill);
	ProfFees(&$objResponse, &$objBill, $enc);
	//populateMeds(&$objResponse,&$objBill);
	//getBilledOps(&$objResponse, &$objBill);
	GetCompany(&$objResponse, $enc);
	GetAfterExcessDiscountHCI(&$objResponse, $enc);
	ismedico(&$objResponse,$enc);
	$ptype = getPatientType(&$objResponse,&$objBill,$enc);
	isPHS(&$objResponse, &$objBill,$enc);
	isInfirmaryOrDependent(&$objResponse, &$objBill,$enc);//added by Nick, 4/8/2014
	setAdditionalInfos(&$objResponse, &$objBill,$enc,$isNewBorn);//added by Nick, 4/8/2014
	getCaseTypeOption(&$objResponse,&$objBill);
	getOtherCoverage(&$objResponse,&$objBill,$enc);
	getCompanyCoverage(&$objResponse,&$objBill,$enc);
	getCompanyCharges(&$objResponse, &$objBill, $enc);
	$objResponse->call("assignInsurance",GetPhicNumber($enc));


	//added by art dingal 01/07/2014
	$deposit = number_format($objBill->getPreviousPayments()/* + $objBill->getOBAnnexPayment()*/, 2, '.', ',');
	$objResponse->call("getPreviousPayment",  $deposit);
	//end art
	getRemainingDays($objResponse,$objBill,$ptype);
	$objResponse->call("calculateTotals");
	
	$objResponse->call('hideLoading');
	return $objResponse;
}

//updated by Nick 05-15-2014
function getRemainingDays(&$objResponse,&$objBill,$ptype){
	if($ptype != 2){
		$rem = $objBill->fortyFiveDays();//added by art 01/06/2014
		$objResponse->call("showRemainingDays",1,$rem['covered'],$rem['remaining'], $rem['save']);//added by art 01/06/2014
	}
}

//updated by Nick 05-15-2014
function getPatientType(&$objResponse,&$objBill,$enc){
	$ptype = $objBill->getEncounterType($enc);
	if ($objBill->isERPatient($enc) || $ptype == 2){
		$objResponse->call("hideAccomodation",1);
	}else{
		$objResponse->call("hideAccomodation",0);	
	}
	return $ptype;
}

//added by nick, 1/4/2014
function setHasBloodTrans(&$objResponse,$enc){  	
	$labObj = new SegLab();
	$hasbloodborrowed = 0;
	$labObj->hasBloodRequest($enc);
	if ($labObj->count)
	    $hasbloodborrowed = 1;

	$objResponse->call('setHasBloodTrans',$hasbloodborrowed);
}
//end nick

function ismedico(&$objResponse,$enc)
{

$objBill = new Billing();
			if ($objBill->isMedicoLegal($enc)) {
            $objResponse->call("showMedicoLegal",1, ShowMedicoCases($enc)); 
            // $objResponse->alert($enc);
          	} else {
            $objResponse->call("showMedicoLegal",0);
            
        }


}

function ShowMedicoCases($enc){

	global $db;

	$sql_mc = "SELECT fn_get_medico_legal(".$enc.") AS medico_cases";
	$medicoCase = $db->getOne($sql_mc);


return $medicoCase;

}

#added by Nick 2/8/2014
#udpated accomodation days stay output
function getDayDiff($isFirstAd,$to,$from,$bill_date,&$objResponse/*,&$test*/){
	$tmpTo = strtotime($to);
	$tmpFrom = strtotime($from);
	$tmpBill = strtotime($bill_date);

	$tmpTo = strtotime(date('Y-m-d',$tmpTo));
	$tmpFrom = strtotime(date('Y-m-d',$tmpFrom));
	$tmpBill = strtotime(date('Y-m-d',$tmpBill));

	// $test .= " >> to: " . $to . " >> from: " . $from . " >> bill: " . $bill_date . "\n";

	if ($tmpTo != $tmpFrom){
		if($tmpTo <=0){
			$output = round(($tmpBill - $tmpFrom) / 86400);
			if($output == 0)
				return 1;
			else if($output < 0)
				return 0;
			else
				return $output;
		}
		$output = round(($tmpTo - $tmpFrom) / 86400);
		if($output == 0)
			return 1;
		else if($output < 0)
			return 0;
		else
			return $output;
	}else{
		return 0;
	}
}

//added by nick, 3/11/2014
function setAccDetails1(&$details,$row,$isAd,&$objResponse,$test){

	$details->temp_excess = $row['mandatory_excess'] * $row['days_stay'];
	$details->excess += $details->temp_excess;

	if($row['days_stay'] > 0){
		if($isAd){
			$details->encounter = $row['encounter_nr'];
			$details->location_nr = $row['location_nr'];
			$details->type_nr = $row['type_nr'];
			$details->room = $row['room'];
			$details->ward = $row['ward'];
			$details->accommodation_type = $row['accomodation_type'];
			$details->name = $row['name'];
			$details->room_rate = number_format($row['rm_rate'], 2, '.', ',');
			$details->days_stay = $row['days_stay'];
			$details->hours_stay = $row['hrs_stay'];
			$details->datefrom = $row['date_from'];
			$details->dateto = $row['date_to'];
			$details->timefrom = $row['time_from'];
			$details->timeto = $row['time_to'];
			$details->source = $row['source'];
			$details->mandatory_excess = $row['mandatory_excess'];
			$details->total = $row['rm_rate'] * $details->days_stay;
			$details->grand_total += $details->total;
			$objResponse->call("jsAccommodationList", $details, number_format($details->total, 2, '.', ','),false);
		}else{
			$details->encounter = $row['encounter_nr'];
			$details->location_nr = $row['location_nr'];
			$details->type_nr = $row['type_nr'];
			$details->room = $row['room'];
			$details->ward = $row['ward'];
			$details->accommodation_type = $row['accomodation_type'];
			$details->name = $row['name'];
			$details->room_rate = number_format($row['rm_rate'], 2, '.', ',');
			$details->days_stay = $row['days_stay'];
			$details->hours_stay = $row['hrs_stay'];
			$details->datefrom = $row['date_from'];
			$details->dateto = $row['date_to'];
			$details->timefrom = $row['time_from'];
			$details->timeto = $row['time_to'];
			$details->source = $row['source'];
			$details->mandatory_excess = $row['mandatory_excess'];
			$details->total = $row['rm_rate'] * $details->days_stay;
			$details->grand_total += $details->total;
			$objResponse->call("jsAccommodationList", $details, number_format($details->total, 2, '.', ','),false);
		}

	}
}

//updated by nick, 3/11/2014
function populateAccomodation(&$objResponse,&$objBill,$bill_dte,$enc){
		$result = $objBill->getAccomodationList();
		// $objResponse->call('debug',$objBill->sql);

		$arr_accomodations = array();

		$objResponse->call("jsClearList", "body_accListDetails");
		if($result){
			if($result->RecordCount() > 1){
				while($row = $result->FetchRow()){
					array_push($arr_accomodations, $row);
				}
				$is_first_admission_day = true;
				$index = 0;
				foreach ($arr_accomodations as $key => $row){
					if($row['source']=='AD'){
						if($is_first_admission_day){
							$date_to = $row['date_to'] . ' ' . $row['time_to'];
							$tmp_datetime = strtotime($objBill->getCaseDate($enc));
							if($tmp_datetime <= 0){
								$ahead_to = $arr_accomodations[$index+1]['date_from'] . ' ' . $arr_accomodations[$index+1]['time_from'];
								if(!isset($ahead_to)){
									$ahead_to = $bill_dte;
								}
							}
							$date_from = date('Y-m-d h:i:s', $tmp_datetime);
							$diff = getDayDiff($is_first_admission_day,$date_to,$date_from,$bill_dte,$objResponse/*,$test*/);
							$row['days_stay'] = (($diff==0)? 1 : $diff);
					 		$is_first_admission_day = false;
						}else{
							$ahead_to = $arr_accomodations[$index+1]['date_from'] . ' ' . $arr_accomodations[$index+1]['time_from'];
							if(!isset($ahead_to)){
								$ahead_to = $bill_dte;
							}
							$date_from = $row['date_from'] . ' ' . $row['time_from'];
							$diff = getDayDiff($is_first_admission_day,$date_to,$date_from,$bill_dte,$objResponse/*,$test*/);
							$row['days_stay'] = (($diff==0)? 1 : $diff);
						}
						setAccDetails1($details,$row,true,$objResponse,1);
					}else{
						setAccDetails1($details,$row,false,$objResponse,2);
					}
					$index++;
		}
			}else{
				$is_first_admission_day = true;
				while($row = $result->FetchRow()){
					if($row['source']=='AD'){
						$date_to = $row['date_to'] . ' ' . $row['time_to'];
					 	if($is_first_admission_day){
					 		$tmp_datetime = strtotime($objBill->getCaseDate($enc));
							$date_from = date('Y-m-d h:i:s', $tmp_datetime);
							$diff = getDayDiff($is_first_admission_day,$date_to,$date_from,$bill_dte,$objResponse/*,$test*/);
							$row['days_stay'] = (($diff==0)? 1 : $diff);
					 		$is_first_admission_day = false;
		        		}else{
		        			$tmp_datetime = strtotime($row['date_from'] . ' ' . $row['time_from']);
		        			$date_from = date('Y-m-d h:i:s', $tmp_datetime);
		        			$diff = getDayDiff($is_first_admission_day,$date_to,$date_from,$bill_dte,$objResponse/*,$test*/);
		 					$row['days_stay'] = (($diff==0)? 1 : $diff);
		        		}
		        		setAccDetails1($details,$row,true,$objResponse,3);
					}else{
						setAccDetails1($details,$row,false,$objResponse,4);
					}
				}
			}
		}

	
		$objBill->setAccomodationType($details->accommodation_type);
		 
		$objResponse->call("setAccSubTotal", number_format($details->grand_total, 2, '.', ','), $details->excess, false);
		$objResponse->call("assignValue","save_total_acc_charge",$details->grand_total);
		return $objResponse;
}
#end nick

function populateXLO($enc,$bill_dte,$bill_frmdte,$death_date)
{
	$objBill = setArguments($enc,$bill_dte,$bill_frmdte,$death_date);
	$objResponse = new xajaxResponse();

	$result = $objBill->getXLOList();
	$result_return = $objBill->getCreditNoteUi($enc,SUPPLIES);
	$objResponse->call("jsClearList", "body_hsListDetails");
	$objResponse->call("jsClearList", "hdXLORef");
	if($result->RecordCount())
	{
		while($row=$result->FetchRow())
		{	
			$details->ref_nr = $row['refno'];
			$details->datetime = $row['serv_dt']+", "+ $row['serv_tm'];
			$details->srv_code = $row['service_code'];
			$details->srv_desc = $row['service_desc'];
			$details->grp_code = $row['group_code'];
			$details->grp_desc = $row['group_desc'];
			$details->qty = $row['req_qty'];
			$details->srv_price = number_format($row['serv_charge'], 2, '.', ',');
			$details->source_code = $row['source'];
			$details->total = $row['req_qty'] * $row['serv_charge'];
			$details->total_temp = $row['qty'] * $row['serv_charge'];
			$details->grand_total += $details->total_temp;
			if ($refno!=$row['refno']) {
				$details->same=false;
			}else{
				$details->same=true;
			}
			$refno=$row['refno'];
			//$info = serialize($details);
			$objResponse->call("jsHospitalServices",json_encode($details),number_format($details->total, 2, '.', ','),FALSE);

		}
	}
	$objResponse->call("setMiscServices", number_format($details->grand_total, 2, '.', ','));
	$objResponse->call("assignValue","save_total_srv_charge",$details->grand_total);

	if ($result_return) {

			foreach ($result_return as $value) {

				$details->ref_nr = '';
				$details->datetime = '';
				$details->srv_code = $value['bestellnum'];
				$details->srv_desc = '( RETURNED ) '.$value['item_name'];
				$details->grp_code = '';
				$details->grp_desc = '';
				$details->qty = $value['qty'];
				$details->srv_price = number_format($value['charge'], 2, '.', ',');
				$details->source_code = 'SU';
				$details->total = $value['credit_note'];
				$details->grand_total = '1';

				$objResponse->call("jsHospitalServices",json_encode($details),number_format($details->total, 2, '.', ','),FALSE);
		}
	}

	return $objResponse;
}


function populateMisc($enc,$bill_dte,$bill_frmdte,$death_date)
{
	$objBill = setArguments($enc,$bill_dte,$bill_frmdte,$death_date);
	$objResponse = new xajaxResponse();	
	
	$result = $objBill->getMiscList();
	$objResponse->call("jsClearList", "body_mscListDetails");
	if($result)
	{
		while($row=$result->FetchRow())
		{
			$details->refno = $row['refno'];
			$details->code = $row['service_code'];
			$details->name = $row['name'];
			$details->desc = $row['description'];
			$details->qty = $row['qty'];
			$details->chrg = number_format($row['avg_chrg'], 2, '.', ',');
			$details->grand_total += $row['total_chrg'];
			
			/*if($row['service_code']!= IT_IP && $row['service_code']!= IT_ER){
				$details->misc_auto_excess +=  $row['total_chrg'];
			}*/
				
			$objResponse->call("jsMiscellaneousList", json_encode($details), number_format($row['total_chrg'], 2, '.', ','), false);
		}
	}
	else {
		$objResponse->call("jsMiscellaneousList", null, 0, false);
	}
	#$objResponse->call("setMiscAutoExcess",(($details->misc_auto_excess) ? $details->misc_auto_excess : '0'));
	$objResponse->call("setMiscCharges",number_format($details->grand_total, 2, '.', ','));
	$objResponse->call("assignValue","save_total_msc_charge",$details->grand_total);
	return $objResponse;
}


function setDeathDate($pid, $enc ='0', $deathdate='0000-00-00 00:00:00')
{
	global $db;
	$objResponse = new xajaxResponse();
	$objPerson = new Person;
	$ok = $objPerson->setDeathDate($pid,$enc,$deathdate);
	if(!$ok)
	{
		$objResponse->alert($objPerson->sql);
	}
	return $objResponse;
}



/*------------------------------Populates the First and Second Case Rate Package-------------------------*/
/**
 * Updated by Nick 4/22/2014
 * Different case rate(case rates with special package) amount for
 * new born with hearing and non-hearing test
 */
function populateCaseRate(&$objResponse, &$objBill, $encNr,$firstCode,$secondCode,$isNewBorn){
	// $objResponse = new xajaxResponse();
	$ops = new SegOps();
	$encObj = new Encounter();
	$icdIcp = new Icd_Icp();

	$objResponse->call("js_ClearOptions","first_rate");
	$objResponse->call("js_ClearOptions","second_rate");
	$objResponse->call("js_ClearOptions","first_case_desc");
	$objResponse->call("js_ClearOptions","second_case_desc");
	$objResponse->call("js_ClearOptions","first_rate_amount");
	$objResponse->call("js_ClearOptions","second_rate_amount");
	$objResponse->call("setFields");
	
	$diagnosis = $icdIcp->searchIcdIcp($encNr);
	$data['encounter'] = $encNr;
	$hasbillnr = $objBill->getbillnr($data);
	if($hasbillnr){
		$fcase = $objBill->hasSavedPackage($hasbillnr,1);
		$scase = $objBill->hasSavedPackage($hasbillnr,2);

	}
	$hasSec = '';
	$firstCodeExists = '';
	$secondCodeExists = '';
	$selected_first = '';
	$selected_second = '';
	if ($diagnosis) {

		//added by Nick, 4/22/2014
//		$with_hearing = $objBill->isHearingTestAvailed($encNr,$isNewBorn);
//		$objResponse->call('setHasHearingTest', $with_hearing );
		//end nick

		$rows=$diagnosis->RecordCount();
		while($row=$diagnosis->FetchRow()){

			$row['num_sessions'] = ($row['code'] == '96408') ? 1 : $row['num_sessions'];//added by Nick 05-08-2014

			if($row['code']){
				$pkg->spc = 0;
				$result = $objBill->GetPreviousPackage($encNr);
				if ($result) {
					while ($row2 = $result->FetchRow()) {
						if ($row2['code'] == $row['code']) {	
							$pkg->spc = 1;
						}
					}
				}
				$pkg->amntFirst = '';
				$pkg->amntSecond = '';
				$pkg->hf1 = '';
				$pkg->pf1 = '';
				$pkg->hf2 = '';
				$pkg->pf2 = '';
			
				$pkg->amntFirst = ($row['package'] * $row['num_sessions']);
				$pkg->amntSecond = (($row['shf'] + $row['spf']) * $row['num_sessions']);
				$pkg->hf1 = ($row['hf'] * $row['num_sessions']);
				$pkg->pf1 = ($row['pf'] * $row['num_sessions']);
				$pkg->hf2 = ($row['shf'] * $row['num_sessions']);
				$pkg->pf2 = ($row['spf'] * $row['num_sessions']);						

				$pkg->sp_amnt = ($row['sp_package'] * $row['num_sessions']);
				$pkg->sp_hf = ($row['sp_shf'] * $row['num_sessions']);
				$pkg->sp_pf = ($row['sp_spf'] * $row['num_sessions']);
				
				$pkg->code = $row['code'];
				$pkg->desc = $row['description'];
				$pkg->cType = $row['case_type'];
				$pkg->isSecCase = $row['is_allowed_second'];
				$pkg->caserate = $fcall;
				$pkg->laterality = $row['laterality'];
				$pkg->D1 = '';
				$pkg->D3 = '';
				$pkg->D4 = '';
				$pkg->D1_sec = '';
				$pkg->D2_sec = '';
				$pkg->D3_sec = '';
				
				if($row['case_type']=='p'){
					$pkg->D3 = $pkg->pf1 * (0.60);
					$pkg->D4 = $pkg->pf1 * (0.40);
				} else {
					$pkg->D1 = $pkg->pf1;
				}

				if ($row['case_type'] == 'p' && $row['is_allowed_second'] == '1') {
					$pkg->D3_sec = $pkg->pf2 * (0.60);
					$pkg->D4_sec = $pkg->pf2 * (0.40);
				} else {
					$pkg->D1_sec = $pkg->pf2;
				}

				if ($selected_first=='' && $pkg->spc != 1)
					$selected_first  = $pkg->code;

				$withHtest = 0;

				if($selected_second=='' && $pkg->spc != 1 && $pkg->isSecCase == 1) {
					if($selected_first == $pkg->code && $pkg->laterality == 'B')
						$selected_second = $pkg->code;
					elseif($selected_first != $pkg->code ){
						$selected_second = $pkg->code;
					}
					
					if($pkg->sp_amntSecond){
						$withHtest = 1;
					}
				}
					

				$objResponse->call("populateCaseRates",$pkg);
				//if($pkg->isSecCase==1)$hasSec = 1;
			}
			//if($row['code']==$firstCode) $firstCodeExists = 1;
			//if($row['code']==$secondCode) $secondCodeExists = 1;
		}

        if(isset($fcase['package_id']) || isset($scase['package_id'])){
            $firstCode = $fcase['package_id'] ? $fcase['package_id'] : "";
            $secondCode = $scase['package_id'] ? $scase['package_id'] : "";
		}else{
            $firstCode = $selected_first;
            $secondCode = $selected_second;
		}

//        if($scase['sp_package_id'] || $with_hearing >= 0){
//			$withHtest = 1;
//		}

		$objResponse->call("setCaserate",$firstCode,$secondCode);
        $objResponse->call('Procedures.getPatientSpecialProcedures');

	}else{
		$objResponse->call("populateCaseRates",NULL);
	}

	return $objResponse;
}


/*----------------------Operating Room Accomodation Charges---------------------------------------*/

function getBilledOps($enc,$bill_dte,$bill_frmdte,$death_date) {

	$objBill = setArguments($enc,$bill_dte,$bill_frmdte,$death_date);
	$objResponse = new xajaxResponse();

	$objBill->getOpBenefits();    // Get summary of operations and corresponding insurance coverage.

	$opsBenefitsArray = $objBill->hsp_ops_benefits;
	$totalOpsCharge   = $objBill->getTotalOpCharge();
	$objBill->initOpsConfineCoverage();

	$objResponse->call("jsClearList", "body_opsListDetails");

	if (is_array($opsBenefitsArray) && (count($opsBenefitsArray) > 0)) {
            $i = 0;
			foreach ($opsBenefitsArray as $key=>$value) {
					$n_rvu   = number_format($value->op_rvu, 0, '', ',');
					$n_mult  = number_format($value->op_multiplier, 2, '.', ',');
					$n_total = number_format($value->getOpCharge(), 2, '.', ',');
					$op_code = $value->op_code;
                    if ($objBill->is_coveredbypkg) {
                        if ($i==0) {
                            $i++;
                            // $objBilling->getConfineBenefits('OR');
                        }
                    }
                    // else {
                    //     $objBilling->getConfineBenefits('OR', $value->getOpCodePerformed());
                    // }

					$opAcc->rm_nr       = substr($op_code, 3); // Discard 'OR-'
					$opAcc->nrvu        = $n_rvu;
					$opAcc->nmultiplier = $n_mult;
					$opAcc->nchrg       = $n_total;
					$opAcc->desc 		= $value->op_desc;

					$objResponse->call("addORAccCharge", $opAcc);
			}
	}
	else
			$objResponse->call("addORAccCharge", NULL);

    // if (!$objBilling->isCharity() && $objBilling->iswithSCDiscount()) {
    //     $ndiscount = $objBilling->getBillAreaDiscount('OR');
    // } else if (!$objBilling->isPHIC() && !$objBilling->isMedicoLegal()) { //added by jasper 05/21/2013 FIX FOR AUTOMATIC EXCESS FOR PHIC PATIENTS
    //     $ndiscount = $objBilling->getBillAreaDiscount('OR');
    // } else {
    //     $ndiscount = 0.00;
    // }
    // if ($objBilling->isPHIC()) {
    //     if ($totalOpsCharge - $ndiscount <= $totalOpsCoverage) {
    //         $totalOpsCoverage = $totalOpsCharge - $ndiscount;
    //     }
    // }

	$totalOpsCoverage = $objBill->ops_confine_coverage;
	$objResponse->call("showOpsTotals", number_format($totalOpsCharge, 2, '.', ','));
	$objResponse->call("assignValue","save_total_ops_charge",$totalOpsCharge);
	return $objResponse;
    // $excess = number_format(($totalOpsCharge - $ndiscount - $totalOpsCoverage), 2, '.', ',');
	// $objResponse->call("showOpsTotals", number_format($totalOpsCharge, 2, '.', ','), number_format($ndiscount, 2, '.', ','), number_format($totalOpsCoverage, 2, '.', ','), $excess);
}

function getOPCharge($enc_nr, $bill_dt, $nrvu, $casetyp) {
	global $db;

	$ncharge = 0;

	$strSQL = "select fn_getORCharge('{$enc_nr}', date('{$bill_dt}'), {$nrvu}, {$casetyp}) as opcharge";
	if ($result = $db->Execute($strSQL)) {
		if ($result->RecordCount()) {
			if ($row = $result->FetchRow()) {
				$ncharge = $row["opcharge"];
			}
		}
	}

	return $ncharge;
}

function setORWardOptions() {
		$objResponse = new xajaxResponse();
		$objwrd = new Ward();
		$wards = $objwrd->getAllOpWards();
		if ($wards) {
				$objResponse->call("js_ClearOptions","opwardlist");
				$objResponse->call("js_ClearOptions","orlist");

				if ($wards->RecordCount()) {
						$objResponse->call("js_AddOptions","opwardlist","- Select O.R. Ward -", 0);
						$objResponse->call("js_AddOptions","orlist","- Select Operating Room -", 0);

						while ($row = $wards->FetchRow()) {
								$objResponse->call("js_AddOptions", "opwardlist", $row['name'], $row['nr']);
						}
				}
		}
		else
				$objResponse->alert("ERROR: Cannot retrieve available O.R. wards!");

		return $objResponse;
}

function setORWardRooms($ward_nr) {
		$objResponse = new xajaxResponse();

		$objwrd = new Ward();
		$wards = $objwrd->getRoomsData($ward_nr);
		if ($wards) {
				if ($wards->RecordCount()) {
						$objResponse->call("js_ClearOptions","orlist");
						$objResponse->call("js_AddOptions","orlist","- Select Operating Room -", 0);
						while ($row = $wards->FetchRow()) {
								$objResponse->call("js_AddOptions", "orlist", $row['room_nr'], $row['nr']);
						}
				}
		}
		else
				$objResponse->alert("ERROR: Cannot retrieve available rooms!");

		return $objResponse;
}

function populateAppliedOpsList($senc_nr){

    $objResponse = new xajaxResponse();
    $Ops = new SegOps;

    $procInfo = $Ops->SearchAppliedOP($senc_nr);
    $objResponse->call("clearAppliedProcedureList");
    if ($procInfo) {
				$rows=$procInfo->RecordCount();
				while($result=$procInfo->FetchRow()) {
						$description_short = $result["description"];
						if (strlen($description_short)>50)
								$description_short = substr(trim($result["description"]),0,50)."...";
						// $bselected = $result["bselected"];

						$details->code = $result["ops_code"];
						$details->description = trim($description_short);
						$details->descriptionFull = trim($result["description"]);
						$details->opdate = (is_null($result["op_date"])) ? '00/00/0000' : strftime("%m/%d/%Y", strtotime($result["op_date"]));
						$details->rvu = $result["rvu"];
						$details->multiplier = $result["multiplier"];
						// $details->bselected = $bselected;
						$details->entry_no = $result["entry_no"];
						$details->refno = $result["refno"];
						$details->groupcode = $result["group_code"];
						// $details->b_dr = (strcmp($b_drchrg, 'true') == 0 ? 1 : 0);

						$objResponse->call("addAppliedOPtoList", $details);
				}#end of while
		}#end of if

	if (!$rows) $objResponse->addScriptCall("addAppliedOPtoList", NULL);

	// $objResponse->alert($senc_nr);	

	return $objResponse;

}

function updateRVUTotal($pDetails) {
	$objResponse = new xajaxResponse();
	$ops = new SegOps();
	$hospObj = new Hospital_Admin();
	$icdIcp = new Icd_Icp();

	if ($ops->isHouseCase($pDetails['encNr'])){
		$nPCF = HOUSE_CASE_PCF;
        $cType = 2;
	}else{
		$nPCF = $hospObj->getDefinedPCF();
		$cType = 1;
	}

    $diagnosis = $icdIcp->searchIcdIcp($pDetails['encNr']);
    if ($diagnosis) {
    	while($result=$diagnosis->FetchRow()){
    		if($pDetails['opsCode']==$result['code']){
    			$num_sessions = ($result['special_case'] == 1) ? 1 : $result['num_sessions'];
    			$additional = $icdIcp->getOpsAdditional($pDetails['opsCode']);

    		}
    	}
    }      

	$ncharge = $ops->getOPCharge($pDetails['encNr'], $pDetails['billdate'], $pDetails['nrvu'],$cType);
	$objResponse->call("applyRVUandMult", $pDetails['nrvu'], $nPCF, (($ncharge + $additional) * $num_sessions));
	
	// $foo = $pDetails['$cType'];
	// $objResponse->alert($nPCF);
	return $objResponse;
}

function saveORAccommodation($aFormValues, $bill_dt, $opDetails='') {
		global $db;
		$ops = new SegOps();

		$err_msg = '';

		$objResponse = new xajaxResponse();

		$s_enc_nr = $aFormValues['opacc_enc_nr'];
		$bSuccess = true;

		if ($s_enc_nr != '') {
				if (strcmp($bill_dt, "0000-00-00 00:00:00") != 0)
						$tmp_dte = $bill_dt;
				else
						$tmp_dte = strftime("%Y-%m-%d %H:%M:%S");

				$tmpbill_dte = $tmp_dte;
				$tmp_dte = strftime("%Y-%m-%d %H:%M:%S", strtotime("-1 second", strtotime($tmp_dte)));

				$opAcc->w_nr        = $aFormValues['opw_nr'];
				$opAcc->rm_nr       = $aFormValues['opr_nr'];
				$opAcc->nrvu        = $aFormValues['total_rvu'];
				$opAcc->nmultiplier = $aFormValues['multiplier'];
				$opAcc->nchrg       = str_replace(",", "", $aFormValues['oprm_chrg']);
				$opAcc->frm_dte 	= $aFormValues['frm_dte'];
				$opAcc->desc 		= '';

				$objBilling = unserialize($_SESSION['billobject']['main']);
				$db->StartTrans();

				$opAcc->refno = $ops->getOpAccommodationRefNo($opAcc->frm_dte, $s_enc_nr);
				if ($opAcc->refno == '') {
						$strSQL = "insert into seg_opaccommodation (chrge_dte, encounter_nr, modify_id, create_id, create_dt) ".
											"   values ('".$tmp_dte."', '".$s_enc_nr."', '".$_SESSION['sess_user_name']."', '".$_SESSION['sess_user_name']."', ".
											"          '".$tmp_dte."')";
						if ($db->Execute($strSQL))
								$opAcc->refno = $ops->getOpAccommodationRefNo($opAcc->frm_dte, $s_enc_nr);
						else {
								$bSuccess = false;
								$err_msg = $db->ErrorMsg();
						}
				}

				$n = 0;
				if ($bSuccess) {
						$strSQL = "insert into seg_opaccommodation_details (refno, room_nr, group_nr, charge, modify_id, create_id, create_dt) ".
											"   values ('".$opAcc->refno."', ".$opAcc->rm_nr.", ".$opAcc->w_nr.", ".$opAcc->nchrg.", '".$_SESSION['sess_user_name']."', '".$_SESSION['sess_user_name']."', ".
											"          '".$tmp_dte."')";
						if ($db->Execute($strSQL)) {
								$n = $ops->getMaxNoFromOPAccomDetails($opAcc->refno);
								$bSuccess = ($n > 0);
						}
						else {
								$bSuccess = false;
								$err_msg = $db->ErrorMsg();
						}
				}

				if ($bSuccess) {
						// $opsarr = explode("#",$ops);

						foreach ($opDetails as $k => $row) {
								$v = explode(";", $row);

								$strSQL = "insert into seg_ops_chrgd_accommodation (refno, entry_no, ops_refno, ops_entryno, ops_code, rvu, multiplier) ".
													"   value ('".$opAcc->refno."', ".$n.", '".$v[0]."', ".$v[1].", '".$v[2]."', ".$v[3].", ".$v[4].")";

								if (!$db->Execute($strSQL)) {
										$bSuccess = false;
										$err_msg = $db->ErrorMsg();
										break;
								}
						}
				}

				if ($bSuccess) {
					$db->CompleteTrans();
					//$objResponse->call("addORAccCharge",$opAcc);
					$objResponse->call("jsRecomputeServices","op");
				}
				else{
					$db->FailTrans();
					$objResponse->alert("ERROR: ".$err_msg);
				}
				// $objResponse->call("foobar",$opAcc);
						
		}
		return $objResponse;
}


function delOpAccommodation($enc_nr, $bill_dt, $bill_frmdte, $rm_nr, $idRow) {
		global $db;

		$bSuccess = false;
		$objResponse = new xajaxResponse();

		$parent_encnr = getParentEncounterNr($enc_nr);
		if ($parent_encnr != '')
				$encounter = "('$parent_encnr','$enc_nr')";
		else
				$encounter = "('$enc_nr')";

		// $rm_nr = substr($op_code, 3); // Discard 'OR-'
	  	$strSQL = "select * from seg_opaccommodation_details ".
							"   where room_nr = ".$rm_nr." and exists (select * from seg_opaccommodation as so where so.refno = seg_opaccommodation_details.refno ".
							"      and so.encounter_nr in $encounter and so.chrge_dte >= '".$bill_frmdte."') ".
							"      and get_lock('sopd_lock', 10) ".
							"   order by entry_no desc limit 1";
	 	$rs = $db->Execute($strSQL);
	 	if ($rs) {
				$db->StartTrans();
				$row = $rs->FetchRow();
				if ($row) {
						$refno = $row['refno'];
						$entryno = $row['entry_no'];

						$db->LogSQL();

						$strSQL = "delete from seg_opaccommodation_details where room_nr = $rm_nr and entry_no = $entryno and refno = '$refno'";
						$bSuccess = $db->Execute($strSQL);

						$strSQL = "select RELEASE_LOCK('sopd_lock')";
						$db->Execute($strSQL);

						if ($bSuccess) {

								$dcount = 0;
								$strSQL = "select count(*) dcount from seg_opaccommodation_details where refno = '$refno'";
 								$rs = $db->Execute($strSQL);
 								if ($rs) {
 										$row = $rs->FetchRow();
 										$dcount = ($row) ? $row['dcount'] : 0;
 										if ($dcount == 0) {
												$strSQL = "delete from seg_opaccommodation WHERE refno = '$refno'";
												$db->Execute($strSQL);
										}
								}
						}
						else
							$err_msg = $db->ErrorMsg();

						$db->LogSQL(false);

				}
		}
		else
				$err_msg = $db->ErrorMsg();

		if ($bSuccess) {
			$db->CompleteTrans();
			//$objResponse->call("delORAccCharge",$idRow);
			$objResponse->call("jsRecomputeServices",'op');
		}
		else{
			$db->FailTrans();
			$objResponse->alert("ERROR: ".$err_msg);
		}

		return $objResponse;
}

/*-------end-------------Operating Room Accomodation Charges----------------end---------------------*/

function getParentEncounterNr($enc_nr) {
	global $db;

	$parent_encnr = '';
	$strSQL = "select parent_encounter_nr
							from care_encounter
							where encounter_nr = '$enc_nr'";
	if ($result = $db->Execute($strSQL)) {
		if ($result->RecordCount()) {
			$row = $result->FetchRow();
			$parent_encnr = $row['parent_encounter_nr'];
		}
	}
	return $parent_encnr;
}

/*-------------------------For Add Doctors------------------------------*/

function ProfFees(&$objResponse, &$objBilling, $enc){
		
		$objBilling->getProfFeesList();
		$objBilling->getProfFeesBenefits();

		$hsp_pfs_benefits = $objBilling->getPFBenefits(); //role area
		$ndiscount = 0;
		$proffees_list = $objBilling->proffees_list;
		$objResponse->call("jsClearList", "body_docRoleArea");
		$objResponse->call("jsClearList", "doc-coverage");

		$objResponse->call("jsClearList", "doc-coverage-disc");


		$objBilling->setPFCoverage(0);
		$objBilling->setPFDiscount(0);
		$prevrole_area = '';
		$d1 = 0;
		$d2 = 0;
		$d3 = 0;
		$d4 = 0;

		if(is_array($hsp_pfs_benefits) && (count($hsp_pfs_benefits) > 0)) {
				foreach($hsp_pfs_benefits as $key=> $value) {
						if ($value->role_area == $prevrole_area) continue;
						$prevrole_area = $value->role_area;
						reset($proffees_list);
						$objBilling->initProfFeesCoverage($value->role_area);
						$totalcharge2 = $objBilling->getTotalPFCharge($value->role_area);
						$totalCharge = number_format($objBilling->getTotalPFCharge($value->role_area), 2, '.', ',');
						$coverage    = number_format($objBilling->pfs_confine_coverage[$value->role_area], 2, '.', ',');

						$objResponse->call("jsDoctorsFees", "body_docRoleArea", $value->role_area, $value->role_desc, $totalCharge, $coverage);
						$objResponse->call("assignValue", "save_dr_nr", $value->dr_nr);
						$objResponse->call("assignValue", "save_role_area", $value->role_area);
						$objResponse->call("assignValue", "save_dr_charge", $totalCharge);
						// $objResponse->call("assignValue", "save_dr_nr", $value->dr_nr);

						//Display list of doctors in every role area
						$tr ='';
						if (!empty($proffees_list) && is_array($proffees_list)) {
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
                              								if ($objBilling->getIsCoveredByPkg()) break;
														}
													}
												}

												$drName = $profValue->dr_first." ".$profValue->dr_mid.(substr($profValue->dr_mid, strlen($profValue->dr_mid)-1,1) == '.' ? " " : ". ").$profValue->dr_last;
												$drCharge = number_format($profValue->dr_charge, 2, '.', ',');

                                                $totalPF += $profValue->dr_charge;

												if(isDoctorsExists($objBilling->getCurrentEncounterNr(), $profValue->dr_nr, $profValue->getRoleNo())){

														$xtd = "<td align=\"center\" style=\"border-right:solid #999999 thin; border-top:solid #999999 thin\" width=\"5%\"><img src=\"../../images/btn_delitem.gif\" class=\"imgdelete\" style=\"cursor:pointer;\" onclick=\"initMsgDialog(".$profValue->dr_nr.",".$profValue->getRoleNo().")\"></td><td style=\"border-right:solid #999999 thin; border-top:solid #999999 thin\" width=\"75%\">".$drName."</td>";
												}else{
														$xtd = "<td align=\"center\" style=\"border-right:solid #999999 thin; border-top:solid #999999 thin\" width=\"5%\"><img src=\"../../images/btn_delitem.gif\" class=\"imgdelete\" style=\"cursor:pointer;\" onclick=\"initMsgDialog2(".$profValue->dr_nr.",".$profValue->getRoleNo().")\"></td><td style=\"border-right:solid #999999 thin; border-top:solid #999999 thin\" width=\"75%\">".$drName."</td>";
												}

												$xtd .= "<td style=\"border-top:solid #999999 thin\" width=\"20%\" align=\"right\">".$drCharge."</td>";

												$tr .=  "<tr id=\"dr".$profValue->dr_nr."-".$profValue->getRoleNo()."\">".$xtd."</tr>";
												$objResponse->assign($value->role_area, "innerHTML", $tr);
												$objResponse->assign("coverage_".$value->role_area, "innerHTML", number_format($objBilling->pfs_confine_coverage[$value->role_area], 2, '.', ','));
												
												$details->dr_nr = $profValue->dr_nr;
												$details->area = $value->role_area;
												$details->charge = $profValue->dr_charge;
												$details->totalCharge = $totalCharge;
												$details->totalCharge2 = $totalcharge2;
												//$objResponse->call("assignDrDetails",$details);
												switch ($value->role_area) {
													case 'D1':
															$d1 += $profValue->dr_charge;
														break;

													case 'D2':
															$d2 += $profValue->dr_charge;
														break;

													case 'D3':
															$d3 += $profValue->dr_charge;
														break;

													case 'D4':
															$d4 += $profValue->dr_charge;
														break;
													
													default:
														# code...
														break;
												}

												
												$details->enc = $enc;
												$details->name = $drName;
												populateAdjCoverageDoctor(&$objResponse, &$objBilling, $details);
												populateAdjCoverageDoctor_disc(&$objResponse, &$objBilling, $details);
												
										}
								} // end foreach proffees_list
						}

				}//1st foreach

		}
		else
				$objResponse->call("jsDoctorsFees", "body_docRoleArea", NULL, '', 0, 0);


		$nPFCharge = round($totalPF,4);
        $totalExcess = number_format($nPFCharge - round($ndiscount,4) - $totalPFCoverage, 2, '.', ',');

        $PF->d1 = $d1;
		$PF->d2 = $d2;
		$PF->d3 = $d3;
		$PF->d4 = $d4;
		$PF->pfCharge = $nPFCharge;
		$PF->pfDiscount = $ndiscount;
        $objResponse->call("showPFTotals",$PF);
        $objResponse->call("assignValue","save_total_doc_charge",$nPFCharge);
        $objResponse->call('calculateDetails');
        if($nPFCharge==0){
        	$objResponse->assign('pfDiscount','innerHTML',number_format($nPFCharge,2,'.',','));
			$objResponse->assign('pfHC','innerHTML',number_format($nPFCharge,2,'.',','));
        }

        return $objResponse;

}// end of function ProfFees

function rmPrivateDr($encounter_nr, $dr_nr, $role_nr, $bill_dt){
		global $db;
		$objResponse = new xajaxResponse();

		$db->LogSQL();

		$sql = "DELETE FROM seg_encounter_privy_dr ".
					 "WHERE dr_nr = ".$dr_nr." ".
					 "   and encounter_nr = '".$encounter_nr."' ".
					 "   and dr_role_type_nr = $role_nr ".
					 " order by entry_no desc limit 1";

		if($db->Execute($sql)){
				$objResponse->call("populateBill");
		}else{
				$objResponse->alert("Deleting Doctor failed");
		}

		$db->LogSQL(false);

		return $objResponse;
}// end of function rmPrivateDr

function rmDr($encounter_nr, $dr_nr, $role_nr, $bill_dt){
		global $db;
		$objResponse = new xajaxResponse();

		$db->LogSQL();

		$strSQL = "DELETE FROM seg_encounter_dr_mgt ".
							"   WHERE attending_dr_nr = ".$dr_nr." ".
							"      AND encounter_nr = '".$encounter_nr."'";
		if($db->Execute($strSQL)){
			// $_SESSION['computeflag'] = true;
			// $_SESSION['pf_done'] = false;
			// $objResponse->call("toggleBillAreaStatus", 'PF', 1);
			$objResponse->call("populateBill");
		}else{
			$objResponse->alert("Deletion of attending doctor failed!\nSQL = ".$strSQL);
		}

		$db->LogSQL(false);

		return $objResponse;
}// end of function rmDr

function isDoctorsExists($enc_nr, $dr_nr='', $roletype_nr){
		global $db;

		$sql = "SELECT * FROM seg_encounter_privy_dr WHERE encounter_nr = '$enc_nr' and dr_nr = $dr_nr and dr_role_type_nr = $roletype_nr";
		if($result = $db->Execute($sql)){
				#$objResponse->alert("sqll - " .$sql);
				if($result->RecordCount() >= 1){
						return true;
				}
		}else{
				return false;
		}
}// end of function isDoctorsExists()

function setDoctors($admit_inpatient=0, $dept_nr=0, $personell_nr=0) {
	$pers_obj=new Personell;
	$objbillinfo = new BillInfo();
	$objResponse = new xajaxResponse();

	if ($dept_nr)
			$rs=$pers_obj->getDoctorByDept($dept_nr, $admit_inpatient);
	else
			$rs=$pers_obj->getDoctors($admit_inpatient);

	if ($rs) {
			$objResponse->call("js_ClearOptions","doclist");
			if ($pers_obj->count > 0){
					$objResponse->call("js_AddOptions","doclist","-Select a Doctor-",0);
			}else{
					if ($dept_nr)
						$objResponse->call("js_AddOptions","doclist","-No Doctor Available-",0);
					else
						$objResponse->call("js_AddOptions","doclist","-Select a Doctor-",0);
					}

			while ($result=$rs->FetchRow()) {
					$doctor_name = $objbillinfo->concatname($result["name_last"], $result["name_first"], $result["name_middle"]);
					$doctor_name = ucwords(strtolower($doctor_name));
                    $objResponse->call("js_AddOptions","doclist",$doctor_name . " [" . $result["personell_nr"] . "]", $result["personell_nr"]);
			}
	}
	else {
			$objResponse->alert("setDoctors : Error retrieving Doctors information...");
	}

	return $objResponse;
}// end of function setDoctors()

function setRoleArea($jobType){
		global $db;
		$objResponse = new xajaxResponse();

		$sql = "SELECT crp.`nr`,
					    crp.`job_type_nr`,
						crp.`role`,
						crp.`name`,
						crp.`role_area` 
				FROM care_role_person as crp
				WHERE role_area like '%d%'
				AND job_type_nr = '".$jobType."'";
		if($result = $db->Execute($sql)){
				if($result->RecordCount()){
						$objResponse->call("js_ClearOptions","rolearea");
						$objResponse->call("js_AddOptions","rolearea","-Select a Role area-",0);
						while($row = $result->FetchRow()){
								$objResponse->call("js_AddOptions", "rolearea", $row['name'], $row['nr']);
						}
				}
		}else{
				$objResponse->alert("setRoleArea : Error retrieving role person information...");
		}

		return $objResponse;
}

function setOptionRoleLevel() {
		global $db;
		$objResponse = new xajaxResponse();

		$sql = "SELECT * ".
						"\n  FROM seg_role_tier";

		if($result = $db->Execute($sql)){
				if($result->RecordCount()){
						$objResponse->call("js_ClearOptions", "role_level");
						$objResponse->call("js_AddOptions", "role_level", "-Select Level-",0);
						while($row = $result->FetchRow()){
								$objResponse->call("js_AddOptions", "role_level", $row['tier_desc'], $row['tier_nr']);
						}
				}
		}else{
				$objResponse->alert("setOptionRoleLevel : Error retrieving levels of role ...");
		}
		return $objResponse;
}

function processPrivateDrCharge($aFormValues, $bill_dt = "0000-00-00 00:00:00", $ops='') {
		global $db;
		$objResponse = new xajaxResponse();
		$objBilling = new Billing();
		$bolError = false;
		$err_msg = '';
		$bSuccess = true;

		if ($aFormValues['enc'] != '') {
				if($aFormValues['enc'] == '' && $aFormValues['dr_nr'] == ''){
						$objResponse->alert("Please select a  doctor");
						$bolError = true;
				}else{
						// Adjust current time by 1 second earlier than cut-off date in billing ...
						if (strcmp($bill_dt, "0000-00-00 00:00:00") != 0)
								$tmp_dte = $bill_dt;
						else
								$tmp_dte = strftime("%Y-%m-%d %H:%M:%S");

						$tmpbill_dte = $tmp_dte;
						$tmp_dte = strftime("%Y-%m-%d %H:%M:%S", strtotime("-1 second", strtotime($tmp_dte)));
						// $objBilling = unserialize($_SESSION['billobject']['main']);
				}
				if($aFormValues['role_nr'] == ''){
						$objResponse->alert("Please specify doctors role.");
						$bolError = true;
				}

				$n = 0;
				if(!$bolError){
						$nCharge = str_replace(",", "", $aFormValues['charge']);

						$db->StartTrans();

						$aFormValues['ndays'] = (($aFormValues['ndays'] == "") ? 0 : $aFormValues['ndays']);

						$sql = "insert into seg_encounter_privy_dr (encounter_nr, dr_nr, dr_role_type_nr, dr_level, days_attended, dr_charge, is_excluded, create_id, create_dt) " .
								 "   values ('".$aFormValues['enc']."', ".$aFormValues['dr_nr'].", ".$aFormValues['role_nr'].", ".$aFormValues['role_level'][0].", ".$aFormValues['ndays'].", ".$nCharge.", ".
												 "            ".$aFormValues['excluded'].", '".$_SESSION['sess_user_name']."', '". $tmp_dte ."')";

						if ($db->Execute($sql)) {
								$n = getMaxNoFromPrivyDr($aFormValues['enc'], $aFormValues['dr_nr'], $aFormValues['role_nr']);
								$bSuccess = ($n > 0);
						}
						else {
								$bSuccess = false;
								$err_msg = $db->ErrorMsg().' '.$sql;
						}

						if($bSuccess) {
								if (!empty($ops)) {
										foreach ($ops as $k => $row) {
												$v = explode(";", $row);
												$sqltmp = array();
												$sqltmp[0] = ($aFormValues['tier_nr'] != 0) ? 'role_type_level, ' : '';
												$sqltmp[1] = ($aFormValues['tier_nr'] != 0) ? $aFormValues['tier_nr'].", " : '';

                        
                        if (is_object($objBilling)) {
                          $tmpmult = $objBilling->getHouseCasePCF($aFormValues['enc'],$bill_dt);
                          if ($tmpmult != 0) $v[4] = $tmpmult;
                        }

												$strSQL = "insert into seg_ops_chrg_dr (encounter_nr, dr_nr, dr_role_type_nr, entry_no, ".$sqltmp[0]."ops_refno, ops_entryno, ops_code, rvu, multiplier) ".
																	"   value ('".$aFormValues['enc']."', ".$aFormValues['dr_nr'].", ".$aFormValues['role_nr'].", ".$n.", ".$sqltmp[1]."'".$v[0]."', ".$v[1].", ".
																	"          '".$v[2]."', ".$v[3].", ".$v[4].")";

												if (!$db->Execute($strSQL)) {
														$bSuccess = false;
														$err_msg = $db->ErrorMsg().' '.$sql;
														break;
												}
										}
								}
						}
						else {
								$bSuccess = false;
								$err_msg = $db->ErrorMsg();
						}

						if ($bSuccess) {
							$db->CompleteTrans();
							$objResponse->alert("New doctor successfully added in the list!");
							$objResponse->call("populateBill");
						}
						else{
							$db->FailTrans();
							$objResponse->alert("ERROR: ".$err_msg);
						}
				}
		}

		return $objResponse;
}// end of function ProcessPrivateDrCharge()

function getMaxNoFromPrivyDr($enc_nr, $drnr, $roletypenr) {
	global $db;

	$n = 0;
	$strSQL = "select ifnull(max(entry_no), 0) as latest_no ".
						"   from seg_encounter_privy_dr as sod ".
						"   where encounter_nr = '".$enc_nr."' ".
						"      and dr_nr = ".$drnr.
						"      and dr_role_type_nr = ".$roletypenr;

	if ($result = $db->Execute($strSQL)) {
			if ($result->RecordCount()) {
					while ($row = $result->FetchRow())
							$n = $row['latest_no'];
			}
	}

	return($n);
}

function getDrRole($role_nr){
	global $db;
	$objResponse = new xajaxResponse();

	$strSQL = "SELECT role_area
					FROM care_role_person
					WHERE nr = ".$db->qstr($role_nr);

	if ($result = $db->Execute($strSQL)) {
		if ($result->RecordCount()) {
			if ($row = $result->FetchRow()) {
				$objResponse->call("drRole",$row['role_area']);
			}
		}else{return false;}
	}else{return false;}

	return $objResponse;
}


function delDoctors($enc){
	$objResponse = new xajaxResponse();
	$objBilling = new Billing();

	$delDoc = $objBilling->delEncDoctors($enc);

	//$objResponse->call("selectCaseRate",$caseNum);
	//$objResponse->call("populateBill");
	
	return $objResponse;
}

/*---------end-------------For Add Doctors--------------end-------------*/

function populateBillHeader(&$objResponse,$details)
{
	$details->fbill_dte = strftime("%b %d, %Y %I:%M%p", strtotime($details->bill_dte));
	$details->fbill_frmdte = strftime("%b %d, %Y %I:%M%p", strtotime($details->bill_frmdte));
	$details->fdeath_date = (($details->death_date != '') ? strftime("%b %d, %Y %I:%M%p", strtotime($details->death_date)): strftime("%b %d, %Y %I:%M%p", strtotime(date('Y-m-d'))));
	$objResponse->call('billingHeader',$details);
	return $objResponse;
}

function toggleMGH($data, $bsetMGH) {
	$objResponse = new xajaxResponse();
	$objBilling = new Billing();

	$enc_nr = $data['encounter'];


	if (strcmp($data['billdate'], "0000-00-00 00:00:00") != 0)
		$mgh_date = "'".strftime("%Y-%m-%d %H:%M:%S", strtotime($data['mgh_date']))."'";
	else
		$mgh_date = "NULL";


	$SaveOK=$objBilling->toggleMGH($enc_nr, $mgh_date, $bsetMGH);
	if (!$SaveOK) {
		$objResponse->alert($db->ErrorMsg());
	}
	return $objResponse;
}


function saveThisBilling($data, $final, $details, $com_details, $process_type, $isNewBorn) {
	$objResponse = new xajaxResponse();
	$objBilling = new Billing();
	$objCompany = new Company();
	$curl_obj = new Rest_Curl;

	$user = $_SESSION['sess_user_name'];
    $data['billdatefrom']=strftime("%Y-%m-%d %H:%M:%S", strtotime($data['billdatefrom']));

    global $db;

	$hasbillnr = $objBilling->getbillnr($data);

	$prevencounter = $objBilling->getPrevEncounter($data['encounter']);
	$confinment = $objBilling->getConfinementType();
	
	//$prevpayment = $objBilling->getPreviousPayments();

	if ($hasbillnr){
		$bill_nr = $hasbillnr;
		$savebill = $objBilling->updatebill($data, $hasbillnr, $final);
		// $nbDischarged = $objBilling->dischargeWellBaby($data['encounter'],$isNewBorn);//added by Nick, 4/23/2014
	}else{
		$bill_nr = $objBilling->getNewBillingNr();
		$savebill = $objBilling->savebill($data, $bill_nr, $final);
		// $nbDischarged = $objBilling->dischargeWellBaby($data['encounter'],$isNewBorn);//added by Nick, 4/23/2014
	}
	if(!empty($details)){
		foreach($details as $key => $value){
			$values = explode("_",$value);
	        $insert .= "('".$bill_nr."','".$values[0]."','".$values[1]."'),";
		}
		$insert = substr($insert, 0, -1);
	}else{
		$insert = "('".$bill_nr."','0','NONE')";
	}

	// Added by James 1/7/2014
	if($savebill){
		$saveDetails = $objBilling->saveRefNo(&$insert);
	}// End If

	//Added by Jarel 08/03/2014 For Company Saving
    $ok_com = true;
    if(!empty($com_details)){
        foreach($com_details as $key => $value){
            $ArrInsert[]= "((SELECT uuid()),
                            NOW(),
                            ".$db->qstr($value['comp_id']).",
                            ".$db->qstr($value['enc']).",
                            'CHARGE',
                           ".$db->qstr($value['area']).",
                            '0',
                            NOW(),
                            ".$db->qstr($value['amount']).")";
        }

        $ArrStr =implode(",",$ArrInsert);

        $ok_del = $objCompany->deleteCompanyLedgerDetails($data['encounter']);
        if($ok_del)
            $ok_com = $objCompany->saveCompanyDetails($ArrStr);
        else
            $ok_com = false;
    }
    //End Company Saving

    $hasSC = $objBilling->HasAppliedSCBilling($data['encounter']);

		//added by ken to update previous balance 6/20/2014 modified by ken 7/8/2014
		$objBilling->SaveDiscount($bill_nr, $data['totaldiscounthf'], $data['totaldiscountpf']);
		    //Added By Jarel For FIS Integration

		if($hasSC){
			$objBilling->SaveDiscountSC($data, $hasSC);
		}
		
        $ok_fis = true;
		if($final == '1' && (ENABLE_FIS)){
			// $objBilling->saveBillingPrevBalance($bill_nr, $data);

			#updated by janken 10/13/2014 for filtering the date only greater than august will record the prev balance 
			// if(strtotime($data['billdatefrom']) >= strtotime("2014-08-01 00:00:00"))
			// 	$objBilling->savePrevBalance($bill_nr, $data);
			// $objBilling->checkBilling($bill_nr
			
				$curl_obj->checkCustomer('', $data['pid']);
		        $curl_obj->customerAddEncounter($data['encounter'], $data['billdatefrom'], $data['pid']); // will still check on fis if encounter already exists
				
				$result_acc = $curl_obj->inpatientAccItem($data['save_total_acc_charge'], $data['billdate'], $data['pid'], $bill_nr, $data['encounter']);
				$result_charge = $curl_obj->inpatientChargeItems($data['encounter'], $data['billdate'], $data['pid'], $bill_nr, $data['billdatefrom'], $data['save_total_ops_charge']);
		
                $result_lab = $curl_obj->inpatientLabItem($data['encounter'], true, $bill_nr, $data['billdate']);
                $result_radiology = $curl_obj->inpatientRadioItem($data['encounter'], true, $bill_nr, $data['billdate']);
                $result_pharmacy = $curl_obj->inpatientPharmaItem($data['encounter'], true, $bill_nr, $data['billdate']);
                $result_misc = $curl_obj->inpatientMiscRequest($data['encounter'], true, $bill_nr, $data['billdate'], $data['encounter'], $data['billdatefrom'], $data['pid']);

				$result_cov = $curl_obj->inpatientCoverageItems($data['billdate'], $data['pid'], $bill_nr, $data['encounter']);	
                $result_dep = $curl_obj->inpatientDeposits($data['encounter'], true, $bill_nr, $data['billdate']); // REVERSE THE DEPOSIT IF THERE IS ANY
                //$result_discount = $curl_obj->inpatientDiscount($data['billdate'], $data['pid'], $bill_nr, $data['encounter']);
				$result_discount = $curl_obj->inpatientDiscount($data['billdate'], $data['pid'], $bill_nr, $data['encounter']);
				// $result_doc = $curl_obj->inpatientPF($data['billdate'], $data['pid'], $bill_nr, $data['encounter']);
				
                /*Commented on 12-29-15*/
                // $result_debit_adjustment = $curl_obj->inpatientDebitAdjustment($data['billdate'], $data['pid'], $bill_nr, $data['encounter']);
		
                if($result_acc && $result_charge && $result_cov && $result_discount && $result_lab && $result_radiology && $result_pharmacy && $result_misc && $result_dep/* && $result_debit_adjustment*/&& ($result_doc || is_null($result_doc))){
					$ok_fis = true;
				}else{
					$ok_fis = false;
				}

		}
	//End FIS Integration

	if($savebill && $saveDetails && $ok_fis && $ok_com){
        $db->CommitTrans();

        $objResponse->call('hideLoading');
		$objBilling->checkBilling($bill_nr);
		//end by ken
		$objResponse->alert("Successfully Saved!");
		//added by borj 2014-06-01
		$objResponse->call("disabled_button",$final);
		$objResponse->call("setBillNr",$bill_nr);
		$objResponse->call("showSoa");
		
	}else{
			$objResponse->call('hideLoading');

			$ok = $objBilling->trapError($bill_nr);
			$objResponse->alert("Failed to save Billing!");
			$objResponse->call('console.log',$objBilling->getError());//added by Nick, 4/23/2014
	}

		return $objResponse;
	}

	//added by Nick, 12/27/2013
	function setBillNr($data){
		$objResponse = new xajaxResponse();
		$objBilling = new Billing();

		$bill_nr = $objBilling->getbillnr($data);
		$objResponse->call("setBillNr",$bill_nr);
		$objResponse->call("showSoa");
		return $objResponse;
	}
	//end Nick

	//added by janken 11/06/2014
	function setBillNrOld($data){
		$objResponse = new xajaxResponse();
		$objBilling = new Billing();

		$bill_nr = $objBilling->getbillnr($data);
		$objResponse->call("setBillNr",$bill_nr);
		$objResponse->call("showSoaOld");
		return $objResponse;
	}
	//end Nick

	//added by ken 1/4/2013
	function checkInsurance($enc){
		$objResponse = new xajaxResponse();
		$objcheck = new Billing();

		$result = $objcheck->checkInsuranceRequest($enc);
		
		if($result->RecordCount() != 0){
			$objResponse->call("packageDisplay",'PHIC');
		}
		else{
			$objResponse->call("packageDisplay",'Other');
		}
		return $objResponse;
	}


	//added by poliam 01/04/2014
	function classification($enc, $bill_dte, $bill_from){
		$objResponse = new xajaxResponse();
		$objBilling = new Billing();

		
		$prevenc = trim($objBilling->getPrevEncounter($enc));
		$IsCharity = $objBilling->AccommodationType($enc, $bill_dte, $bill_from, $prevenc);
		$IsEr = $objBilling->isERPatient($enc);
		$classification = $objBilling->Classification($enc, $bill_dte, $IsCharity, $IsEr, $prevenc);
		$objResponse->call("ClassificationHeader",$classification);

		return $objResponse;
	}

	function confinment($enc){
		$objResponse = new xajaxResponse();
		$objBilling = new billing();

		$confinment = $objBilling->isDialysisPatient($enc);
		$objResponse->call("ConfinmentHeader",!$confinment);

		return $objResponse;
	}	

	function getConfineTypeOption($enc,$bill_date){
		$objResponse = new xajaxResponse();
		$objBilling = new billing();
		global $db;

		$sql = "select casetype_id as id, casetype_desc as typedesc from seg_type_case";

		if($result = $db->Execute($sql)){
				if($result->RecordCount()){
						$objResponse->call("js_ClearOptions", "confineTypeOption");
						$objResponse->call("js_AddOptions","confineTypeOption", "- Select Confinement Type -", 0);
						while($row = $result->FetchRow()){
								$objResponse->call("js_AddOptions","confineTypeOption", $row['typedesc'], $row['id']);
						}
						$prevenc = trim($objBilling->getPrevEncounter($enc));
						$nConfinementId = $objBilling->getCaseTypeID($enc, $bill_date, $prevenc);
						if($nConfinementId){
								$objResponse->call("js_setOption", "confineTypeOption", $nConfinementId);
						}else{
								// $objResponse->alert("No confinement type set as of indicated bill date!");
								$objResponse->call("js_setOption", "confineTypeOption", "- Select Confinement Type -");
						}
				}else{
						//$objResponse->alert("ERROR: No confinement types found");
				}
		}

		return $objResponse;
} 
		

function setConfinementType($enc, $type, $classify_id, $create_id, $bill_dte = "0000-00-00 00:00:00"){
        global $db;
        $objResponse = new xajaxResponse();

    //Insert new data to seg_encounter_confinement
        if (strcmp($bill_dte, "0000-00-00 00:00:00") == 0) {
        $modify_dt = date('Y-m-d H:i:s');
        $create_dt = date('Y-m-d H:i:s');
        }
        else {
        $modify_dt = $bill_dte;
        $create_dt = date('Y-m-d H:i:s');
        }
    $modify_dt = strftime("%Y-%m-%d %H:%M:%S", strtotime("-1 second", strtotime($modify_dt)));
//						$create_dt = strftime("%Y-%m-%d %H:%M:%S", strtotime("-1 second", strtotime($create_dt)));
    $create_dt = strftime("%Y-%m-%d %H:%M:%S", strtotime($create_dt));

    if ($type != '0') {
        $fldarray = array('encounter_nr' => $db->qstr($enc),
                        'casetype_id'  => $db->qstr($type),
                        'modify_id'    => $db->qstr($modify_id),
                        'modify_dt'    => $db->qstr($modify_dt),
                'create_id'    => $db->qstr($create_id),
                        'create_dt'    => $db->qstr($create_dt),
                        'is_deleted'    => $db->qstr(0) //added by jasper 05/10/2013
               );
      //edited by jasper 05/10/2013 - TO INCLUDE IS_DELETED FIELD. ONE CASE TYPE EVERY ENCOUNTER ONLY EXCEPT DELETED
      $bsuccess = $db->Replace('seg_encounter_case', $fldarray, array('encounter_nr', 'is_deleted'));
       
        if($bsuccess){
          $objResponse->alert("Confinement type is successfully saved!");
      }else
      	$objResponse->alert("Confinement type is NOT saved!");
    }
    else {
      // Logically delete the set encounter case types later than current bill date ...
      //edited by jasper 05/09/2013 - FROM >= CHANGED TO <=
      $strSQL = "UPDATE seg_encounter_case SET
                    is_deleted = 1,
                    modify_id = '".$modify_id."',
                    modify_dt = NOW()
                 	WHERE
                    str_to_date(modify_dt, '%Y-%m-%d %H:%i:%s') <= '" . $bill_dte ."'
					          AND encounter_nr = '".$enc."' and !is_deleted";
      //$objResponse->alert($strSQL);
      if ($db->Execute($strSQL)) {
          $strSQL = "SELECT ROW_COUNT() rcount";
          if ( $result = $db->Execute($strSQL) ) {
            if ( $row = $result->FetchRow() ) {
              if ($row['rcount']) $objResponse->alert($row['rcount']." case types set at a later date deleted!");
            }
          }
      }
    }

		return $objResponse;
} 
//ended by poliam 01/05/2014

//Added by borj 2014-4-01
	function clearBilling() {
		$objResponse = new xajaxResponse();
		$formatted_bill_dte = strftime("%b %d, %Y %I:%M%p");
		$objResponse->call("clearBillingHeaderInfo", date("Y-m-d H:i:s"), $formatted_bill_dte);
		return $objResponse;
}

function showBilling($sbill_nr) {
		$objResponse = new xajaxResponse();

		$objbillinfo = new BillInfo();
		$result = $objbillinfo->getBillingHeaderInfo($sbill_nr);
		if ($result) {
				if ($row = $result->FetchRow()) {
						$spatient_name = $objbillinfo->concatname($row["name_last"], $row["name_first"], $row["name_middle"]);

						$addr = $row["street_name"];
						if ($row["brgy_name"])
								$addr.=", ".$row["brgy_name"];
						if ($row["mun_name"])
								$addr.=", ".$row["mun_name"];
						if ($row["prov_name"])
								$addr.=", ".$row["prov_name"];
						if ($row["zipcode"])
								$addr.=" ".$row["zipcode"];
       
						$admission_dt       = strftime("%b %d, %Y %I:%M%p", strtotime($row["admission_date"]));
						$formatted_bill_dte = strftime("%b %d, %Y %I:%M%p", strtotime($row["bill_dte"]));

                        if(strcmp($row["deathdate"], "0000-00-00 00:00:00") != 0){
                            $formatted_ddte = strftime("%b %d, %Y %I:%M%p", strtotime($row["deathdate"]));
                            $ddate = $row["deathdate"];
        }else{
                            $formatted_ddte = '';
                            $ddate = '';
                        }
                        $objResponse->call("assignBillingHeaderInfo", $row["bill_nr"], $row["encounter_nr"], $row["pid"], $row["bill_dte"], $row["bill_frmdte"], $formatted_bill_dte, $admission_dt, $spatient_name, $addr, $formatted_ddte, $ddate);
                        //$objResponse->alert( $row["encounter_nr"] . " " . $row["bill_nr"]);
				}
        }

        return $objResponse;
}

function deleteBilling($sbill_nr, $enc_nr) {
		$objResponse = new xajaxResponse();
		$objbill = new BillInfo();
        //$objResponse->alert($objbill->deleteBillInfo($sbill_nr, $enc_nr));
		if ($objbill->deleteBillInfo($sbill_nr, $enc_nr)) {
			if($objbill->deletetempdiscount($sbill_nr)){
				// $objbill->deletePrevBal($sbill_nr, $enc_nr);
				$sWarning = 'Billing successfully deleted!';
			}
			clearBilling();
			//$objResponse->call("closeSaveIndicator"); //removed by jasper 04/04/2013
            //added by jasper 04/04/2013
            $objResponse->call("js_NewBilling");
        }
		else
				$sWarning = 'ERROR: '.$objbill->getErrorMsg();

		$objResponse->alert($sWarning);
        return $objResponse;
}

function isPHS(&$objResponse, &$objBill, $enc)
{
	$objResponse->call("assigPHS",$objBill->checkIfPHS($enc));
}

function getCaseTypeOption(&$objResponse, &$objBill){
	global $db;
	$sql = "select confinetype_id as id , confinetypedesc as typedesc\n
				 from seg_type_confinement\n
				 where is_deleted = 0";

		if($result = $db->Execute($sql)){
				if($result->RecordCount()){
						$objResponse->call("js_ClearOptions", "caseTypeOption");
						$objResponse->call("js_AddOptions","caseTypeOption", "- Select Case Type -", 0);
						while($row = $result->FetchRow()){
								$objResponse->call("js_AddOptions","caseTypeOption", $row['typedesc'], $row['id']);
						}
						
						$confinementId = $objBill->getConfinementType();
						$caseTypeHist = $objBill->getCaseTypeHist();
						$objResponse->call('addTooltip','caseTypeOption',$caseTypeHist[0],date('Y-m-d h:i A',strtotime($caseTypeHist[1])));

						if($confinementId){
								$objResponse->call("js_setOption", "caseTypeOption", $objBill->getConfinementType());
						}else{
								$objResponse->call("js_setOption", "caseTypeOption", "A");
						}

						$objResponse->call('disableCaseTypeOptions');

				}
		}

} // end of function getCaseTypeOption


function setCaseType($enc, $type, $bill_dte = "0000-00-00 00:00:00"){
        global $db;
        $objResponse = new xajaxResponse();

        $classify_id = $_SESSION['sess_user_name'];
        //Insert new data to seg_encounter_confinement
        if (strcmp($bill_dte, "0000-00-00 00:00:00") == 0) {
                $classify_dte = date('Y-m-d H:i:s');
                $create_time = date('Y-m-d H:i:s');
        }
        else {
                $classify_dte = $bill_dte;
                $create_time  = date('Y-m-d H:i:s');
        }
        $classify_dte = strftime("%Y-%m-%d %H:%M:%S", strtotime("-1 second", strtotime($classify_dte)));
        $create_time = strftime("%Y-%m-%d %H:%M:%S", strtotime($create_time));

        #Added by Jarel 06/17/2013
        $fldarray = array('encounter_nr' => $db->qstr($enc),
                'confinetype_id'  => $db->qstr($type),
                'classify_id'    => $db->qstr($classify_id),
                'classify_dte'    => $db->qstr($classify_dte),
                'create_id'    => $db->qstr($classify_id),
                'create_time'    => $db->qstr($create_time)
               );

        $bsuccess = $db->Replace('seg_encounter_confinement', $fldarray, array('encounter_nr', 'confinetype_id'));
       
        if($bsuccess){
                $objResponse->alert("Case type is successfully saved!");
                $objResponse->call("js_Recalculate");
        }else{
                $objResponse->alert("Case type is NOT saved!" . $db->ErrorMsg());
        }

        return $objResponse;
}

//added by Nick, 1/9/2014
function getTotalAppliedDiscounts(&$objResponse,$enc){
	global $db;

	$sql = "SELECT SUM(discount) AS total_discount 
			FROM seg_billingapplied_discount 
			WHERE discountid = 'SC'
			AND encounter_nr = ".$db->qstr($enc);

	$rs = $db->Execute($sql);
	if($rs){
		if($rs->RecordCount()>0){
			$row = $rs->FetchRow();
			$objResponse->call("setTotalDiscounts",$row['total_discount']);
		}else{
			$objResponse->call("setTotalDiscounts",0);
		}
	}else{
		$objResponse->call("setTotalDiscounts",0);
	}
}


/**
* Created By Jarel
* Created On 02/12/2014
* Save Outside Medicine and XLO amount
* @param string meds
* @param string xlo
* @return mixed objResponse
*/
function saveOutMedsXLO($enc,$meds,$xlo){
	global $db;

    $objResponse = new xajaxResponse();

    $user = $_SESSION['sess_temp_userid'];
    $create_time = strftime("%Y-%m-%d %H:%M:%S");

    $fldarray = array('encounter_nr' => $db->qstr($enc),
            'total_xlo'  => $db->qstr($xlo),
            'total_meds'    => $db->qstr($meds),
            'create_id'    => $db->qstr($user),
            'create_dt'    => $db->qstr($create_time),
            'modify_id'		=> $db->qstr($user)
           );

    $bsuccess = $db->Replace('seg_encounter_reimbursed', $fldarray, array('encounter_nr'));
   
    if($bsuccess){
            $objResponse->alert("Successfully Saved!");
    }else{
            $objResponse->alert("Failed To Saved!");
    }

    return $objResponse;
}


/**
* Created By Jarel
* Created On 02/12/2014
* Fetch saved data in seg_encounter_reimbursed
* @param string meds
* @param string xlo
* @return mixed objResponse
*/
function getOutMedsXLO($enc){
	global $db;

    $objResponse = new xajaxResponse();

    $sql ="SELECT total_meds, total_xlo 
    	   FROM seg_encounter_reimbursed
    	   WHERE encounter_nr =  ".$db->qstr($enc);
   
	$rs = $db->Execute($sql);
	if($rs){
		if($rs->RecordCount()>0){
			$row = $rs->FetchRow();
			$objResponse->assign("meds_total",'value',number_format($row['total_meds'],2,'.',','));
			$objResponse->assign("xlo_total",'value',number_format($row['total_xlo'],2,'.',','));
		}else{
			$objResponse->assign("meds_total",'value',0);
			$objResponse->assign("xlo_total",'value',0);
		}
	}else{
		$objResponse->assign("meds_total",'value',0);
		$objResponse->assign("xlo_total",'value',0);
	}
	return $objResponse; 
}

//added by Nick, 4/8/2014
function isInfirmaryOrDependent(&$objResponse, &$objBill, $enc){
	$objResponse->call('set_isInfirmaryOrDependent',$objBill->isInfirmaryOrDependent($enc));
}

//added by Nick, 4/8/2014
function setAdditionalInfos(&$objResponse, &$objBill, $enc, $isNewBorn){
	$objResponse->call('setIsInfirmaryOrDependent',$objBill->isInfirmaryOrDependent($enc));
	$objResponse->call('setIsNewBorn',$isNewBorn);//added by Nick, 4/21/2014
}

//added by Nick, 4/22/2014
function updateHearingTest($enc,$value){
	$objResponse = new xajaxResponse();
	$objBilling = new Billing();
	$rs = $objBilling->updateHearingTest($enc,$value);
	if(!$rs){
		$objResponse->alert("Failed to update Hearing test!");
		$objResponse->call('console.log',$objBilling->error_msg);
	}
	return $objResponse;
}

function setArguments($enc,$bill_dte,$bill_frmdte,$death_date)
{
	$objBill = new Billing();

	if($death_date ==''){
		$death_date = $objBill->getDeathDate($enc);
	}

	
	if ($billinfo = $objBill->hasSavedBill($enc)) 
	{
		$objBill->setBillArgs($enc,$billinfo['bill_dte'],$billinfo['bill_frmdte'],$death_date,$billinfo['bill_nr']);
	} 
	else 
	{
		$objBill->setBillArgs($enc,$bill_dte,$bill_frmdte,$death_date);
	}

	return $objBill;
}



/**
* Created By Jarel
* Created On 04/12/2014
* Populate data in adjusted coverage (Doctor's Area)
* @param object objResponse
* @param object objBilling
* @param object details
* @return mixed objResponse
*/
function populateAdjCoverageDoctor(&$objResponse, &$objBilling, $details)
{

	$th = '';
	$td  = '';
	$footer = '';
	global $db;
	$objCompany = new Company();
	$billinfo = $objBilling->hasSavedBill($details->enc);
	$refno = (($billinfo['bill_nr']) ? $billinfo['bill_nr'] : 'T'.$details->enc);
	
	$discount = $objBilling->getTotalAppliedDiscounts($details->enc);
	
	$result = $objBilling->getPerHCareCoverage($details->enc);

	    //Add Company UI Jarel
    $result_com = $objCompany->getCompanyDetailsByEnc($details->enc);

    # Added by Jeff @ 04-16-2018
	$pers_obj = new Personell;
	$enc_obj = new Encounter();
	// $eclaims_accreditation_enable = Config::model()->get('eclaims_accreditation_switch');
	# END Jeff ---
	
    if($result_com){
        if($result_com->RecordCount()){
            while($row_com = $result_com->FetchRow()){
                $th .= 	"<th width=\"2%\" nowrap=\"nowrap\" > </th>
                             <th width=\"10%\" nowrap=\"nowrap\">Company</th>";
                $result_com1 = $objCompany->getOtherCompanyDetails($details->enc,$details->area,$details->dr_nr);
                if($result_com1){
                    if($result_com1->RecordCount()){
                        while($row_com1 = $result_com1->FetchRow()){
                             // $check1 = "<input id=\"apply_".$row_com['comp_id']."_".$details->dr_nr."_".$details->area."\"
	                        //             class=\"segInput\" type=\"checkbox\" ".(($row_com1['amount']) ? 'checked="checked"' : '' )."
	                        //             itemcode=\"".$details->area."\" hcareid=\"".$row_com['comp_id']."\" dr_nr=\"".$details->dr_nr."\"
	                        //             onclick=\"applyCheckDetailsDR(this)\">";
	                        $check1 = '';

                            $com_limit = (($row_com1['amount_limit'])? 'NO LIMIT' : number_format($row_com1['amount_limit'],2,'.',''));
                            $company_total += $row_com1['amount'];

                            $inputs_com = "<input id=\"coverage_".$row_com['comp_id']."_".$details->dr_nr."_".$details->area."\" dr_nr=\"".$details->dr_nr."\"
                                                name=\"coverage_".$row_com['comp_id']."_".$details->dr_nr."_".$details->area."\" class=\"segInput numeric calc-excess save-com-doc\"
                                                    type=\"text\" style=\"width:100%; text-align:right\" onfocus=\"this.select()\"
                                                    onchange=\"if(!checkNegation(this)){calculateDetails();}\" value=\"".number_format($row_com1['amount'],2,'.','')."\"
                                                    itemcode=\"".$details->area."\" comp_area=\"".$com_area[$key]."\" refsource=\"1\" company_id=\"".$row_com['comp_id']."\">" ;

                            $td .= "<td class=\"centerLeft\" width=\"2%\">$check1</td>
                                        <td class=\"centerLeft\" width=\"10%\">$inputs_com</td>";

                            $footer .= "<input type=\"hidden\" id=\"doc_com\" company_id=\"".$row_com['comp_id']."\"
                                class=\"saving-com-details\" name=\"doc_com\" comp_area=\"DOC\" value=\"0.00\"/>
                                <th class=\"rightAlign\"></th>
                                <th class=\"rightAlign\" id=\"company_total_doc\"><span id=\"company_doc_total\">0.00</span></th>";
                        }
                    }else{
                        // $check1 = "<input id=\"apply_".$row_com['comp_id']."_".$details->dr_nr."_".$details->area."\"
                        //             class=\"segInput\" type=\"checkbox\" ".(($row_com1['amount']) ? 'checked="checked"' : '' )."
                        //             itemcode=\"".$details->area."\" hcareid=\"".$row_com['comp_id']."\" dr_nr=\"".$details->dr_nr."\"
                        //             onclick=\"applyCheckDetailsDR(this)\">";
                        $check1 = '';

                        $com_limit = (($row_com1['amount_limit'])? 'NO LIMIT' : number_format($row_com1['amount_limit'],2,'.',''));
                        $company_total += $row_com1['amount'];

                        $inputs_com = "<input id=\"coverage_".$row_com['comp_id']."_".$details->dr_nr."_".$details->area."\" dr_nr=\"".$details->dr_nr."\"
                                                name=\"coverage_".$row_com['comp_id']."_".$details->dr_nr."_".$details->area."\" class=\"segInput numeric calc-excess save-com-doc\"
                                                    type=\"text\" style=\"width:100%; text-align:right\" onfocus=\"this.select()\"
                                                    onchange=\"if(!checkNegation(this)){calculateDetails();}\" value=\"".number_format($row_com1['amount'],2,'.','')."\"
                                                    itemcode=\"".$details->area."\" comp_area=\"".$com_area[$key]."\" refsource=\"1\" company_id=\"".$row_com['comp_id']."\">" ;

                        $td .= "<td class=\"centerLeft\" width=\"2%\">$check1</td>
                                        <td class=\"centerLeft\" width=\"10%\">$inputs_com</td>";

                        $footer .= "<input type=\"hidden\" id=\"doc_com\" company_id=\"".$row_com['comp_id']."\"
                                class=\"saving-com-details\" name=\"doc_com\" comp_area=\"DOC\" value=\"0.00\"/>
                                <th class=\"rightAlign\"></th>
                                <th class=\"rightAlign\" id=\"company_total_doc\"><span id=\"company_doc_total\">0.00</span></th>";
                    }
                }else{
                    // $check1 = "<input id=\"apply_".$row_com['comp_id']."_".$details->dr_nr."_".$details->area."\"
                        //             class=\"segInput\" type=\"checkbox\" ".(($row_com1['amount']) ? 'checked="checked"' : '' )."
                        //             itemcode=\"".$details->area."\" hcareid=\"".$row_com['comp_id']."\" dr_nr=\"".$details->dr_nr."\"
                        //             onclick=\"applyCheckDetailsDR(this)\">";
                    $check1 = '';
                    
                    $com_limit = (($row_com1['amount_limit'])? 'NO LIMIT' : number_format($row_com1['amount_limit'],2,'.',''));
                    $company_total += $row_com1['amount'];

                    $inputs_com = "<input id=\"coverage_".$row_com['comp_id']."_".$details->dr_nr."_".$details->area."\" dr_nr=\"".$details->dr_nr."\"
                                                name=\"coverage_".$row_com['comp_id']."_".$details->dr_nr."_".$details->area."\" class=\"segInput numeric calc-excess save-com-doc\"
                                                    type=\"text\" style=\"width:100%; text-align:right\" onfocus=\"this.select()\"
                                                    onchange=\"if(!checkNegation(this)){calculateDetails();}\" value=\"".number_format($row_com1['amount'],2,'.','')."\"
                                                    itemcode=\"".$details->area."\" comp_area=\"".$com_area[$key]."\" refsource=\"1\" company_id=\"".$row_com['comp_id']."\">" ;

                    $td .= "<td class=\"centerLeft\" width=\"2%\">$check1</td>
                                        <td class=\"centerLeft\" width=\"10%\">$inputs_com</td>";

                    $footer .= "<input type=\"hidden\" id=\"doc_com\" company_id=\"".$row_com['comp_id']."\"
                                class=\"saving-com-details\" name=\"doc_com\" comp_area=\"DOC\" value=\"0.00\"/>
                                <th class=\"rightAlign\"></th>
                                <th class=\"rightAlign\" id=\"company_total_doc\"><span id=\"company_doc_total\">0.00</span></th>";
                }
            }
        }
    }
    //End Company

	$getpfotherdiscount = $objBilling->pfdiscount($details->enc);

		
	if ($result->RecordCount()) {
		while ($row = $result->FetchRow()) {
			$th .= 	"<th width=\"2%\" nowrap=\"nowrap\" ></th>
					 <th width=\"10%\" nowrap=\"nowrap\">".ucwords($row['firm_id'])."</th>";

			# Added by Jeff @ 04-16-2018
			$isPhic = $enc_obj->isPHIC($details->enc);
			$checkDrAccre = $pers_obj->get_Doctor_Accreditation($details->dr_nr);
			$errorMsg = "";
			$valid = 1;
			$datenow = date("Y-m-d");
			if ($checkDrAccre) {
				if (is_object($checkDrAccre)) {
                    while ($drRow = $checkDrAccre->FetchRow()) {
                    	if ($drRow['accreditation_nr']=='') {
                    		$valid = 0;
                            $errorMsg = "The doctor is not accredited! Please provide the accreditation number.";
                        }elseif (($drRow['accreditation_start']) && ($drRow['accreditation_end'])){
                            $drAccreStart = date("Y-m-d",strtotime($drRow['accreditation_start']));
                            $drAccreEnd = date("Y-m-d",strtotime($drRow['accreditation_end']));
                            if ($datenow < $drAccreStart  || $datenow > $drAccreEnd) {
                                $valid = 0;
                                $errorMsg = "The doctor is not accredited! Validity of accreditation ".$drRow['seg_dr_accreditation']." has already expired! Validity Period : ".date("m/d/Y",strtotime($drAccreStart))." to ".date("m/d/Y",strtotime($drAccreEnd));
                            }
                        }elseif (($drRow['accreditation_start']==NULL) || ($drRow['accreditation_end']==NULL)) {
                            $valid = 0;
                            $errorMsg = "The doctor is not accredited! Please provide the validity of accreditation.";
                        }
                    }
                }
			}else{
				$valid = 0;
                $errorMsg = "The doctor is not accredited! Please provide the accreditation number.";
			}
			# END by Jeff ---

			$result1 = $objBilling->getDoctorCoverageDetails($refno, $row['hcare_id'], $details->dr_nr, $details->area);
			$id = $row['hcare_id']."_".$details->dr_nr."_".$details->area; 
			if ($result1->RecordCount()) {
				while ($row1 = $result1->FetchRow()) {
					// Trapping of not accredited doctors by PhilHealth.
					if($row['hcare_id'] == '18'){
						if ($valid == 1) {
						$check = "<input id=\"apply_".$row['hcare_id']."_".$details->dr_nr."_".$details->area."\" 
									class=\"segInput\" type=\"checkbox\" ".(($row1['dr_claim']) ? 'checked="checked"' : '' )."
									onclick=\"applyAllCoverage('".$row['hcare_id']."','".$details->dr_nr."','".$details->area."')\">";
						}else
						{
							$check = "<input id=\"apply_".$row['hcare_id']."_".$details->dr_nr."_".$details->area."\" 
									class=\"segInput\" type=\"checkbox\" ".(($row1['dr_claim']) ? 'checked="checked"' : '' )."
									onclick=\"checkDrAccreditation('".$id."',".$valid.",'".$errorMsg."');\">";
						}
						
					}
					else{
						$check = '';
					}
					$inputs = "<input id=\"coverage_".$row['hcare_id']."_".$details->dr_nr."_".$details->area."\" class=\"segInput numeric calc-excess calc-hcare save-pf-details\"
								name=\"coverage_".$row['hcare_id']."_".$details->dr_nr."_".$details->area."\" 
							   	type=\"text\" style=\"width:100%; text-align:right\" onfocus=\"this.select()\" 
							   	onchange=\"if(!checkNegation(this)){calculateDetails();}\" value=\"".number_format($row1['dr_claim'],2,'.','')."\" 
							    itemcode=\"".$details->area."\" refsource=\"1\" hcareid=\"".$row['hcare_id']."\">" ;
					
					$dr_claim += $row1['dr_claim'];	
					
					$td .= "<td class=\"centerLeft\" width=\"2%\">$check</td>
						    <td class=\"centerLeft\" width=\"10%\">$inputs</td>";
					$footer .= "<th width=\"2%\" nowrap=\"nowrap\" ></th>
								<th class=\"rightAlign\" id=\"total_".$row['hcare_id']."\">0.00</th>";
				}

			}else{
				// Trapping of not accredited doctors by PhilHealth.
				if($row['hcare_id'] == '18'){
					if ($valid == 1) {
					$check = "<input id=\"apply_".$row['hcare_id']."_".$details->dr_nr."_".$details->area."\" 
									class=\"segInput\" type=\"checkbox\" onclick=\"applyAllCoverage('".$row['hcare_id']."','".$details->dr_nr."','".$details->area."')\">";
					}else
					{
						$check = "<input id=\"apply_".$row['hcare_id']."_".$details->dr_nr."_".$details->area."\" 
									class=\"segInput\" type=\"checkbox\" ".(($row1['dr_claim']) ? 'checked="checked"' : '' )."
									onclick=\"checkDrAccreditation('".$id."',".$valid.",'".$errorMsg."');\">";
					}
				}
				else{
					$check = '';
				}
				$inputs = "<input id=\"coverage_".$row['hcare_id']."_".$details->dr_nr."_".$details->area."\" 
							name=\"coverage_".$row['hcare_id']."_".$details->dr_nr."_".$details->area."\" class=\"segInput numeric calc-excess calc-hcare save-pf-details\" 
							   	type=\"text\" style=\"width:100%; text-align:right\" onfocus=\"this.select()\" 
							   	onchange=\"if(!checkNegation(this)){calculateDetails();}\" value=\"0.00\" 
							    itemcode=\"".$details->area."\" refsource=\"1\" hcareid=\"".$row['hcare_id']."\">" ;

				$td .= "<td class=\"centerLeft\" width=\"2%\">$check</td>
						<td class=\"centerLeft\" width=\"10%\">$inputs</td>";

				$footer .= "<th width=\"2%\" nowrap=\"nowrap\" ></th>
							<th class=\"rightAlign\" id=\"total_".$row['hcare_id']."\">0.00</th>";
			}
		}
	}


	$isphic = $objBilling->checkInsuranceRequest($details->enc);
	if( ($isphic->RecordCount() == 0) && ($objBilling->getAccomodationType() == '1') && (!$objBilling->isMedicoLegal($details->enc)) ){
		$doc_discount = $details->charge;
	} elseif( ($isphic->RecordCount() == 0) && ($objBilling->getAccomodationType() == '1') && ($objBilling->isMedicoLegal($details->enc)) && ($discount) ){
		$doc_discount = ($details->charge * $discount) ;
	}elseif ( ($isphic->RecordCount() == 0 ) && ($objBilling->getAccomodationType() == '2') && ($discount) ) {
	 	$doc_discount = ($details->charge * $discount) ;
	} elseif ( ($isphic->RecordCount() != 0 ) && ($discount) ){
		$doc_discount = ($details->charge * $discount);
	}else{
		$doc_discount = ($details->charge * $discount);
	}
	//edited by daryl
	$ttotal = $details->totalCharge2;
	$amt = $objBilling->GetafterExcessDiscount($details->enc, $area='pf');
	$sdoc_discount = $doc_discount;
	$objBilling->setPFCoverage($dr_claim + $company_total);
	$objBilling->setPFTotal($ttotal);
	$objBilling->pfotherdiscount($amt);
	$objBilling->setPFDiscount($sdoc_discount);

	
	$tr_header = "<tr>
					<input type=\"hidden\"  id=\"adj_refno\" name=\"adj_refno\" value=\"".$refno."\"/>
					<th width=\"*\">Billable Areas</th>
	                <th width=\"12%\" nowrap=\"nowrap\">Total Charge</th>
	                <th width=\"12%\" nowrap=\"nowrap\">Senior Citizen</th>
	                $th
	                <th width=\"12%\" nowrap=\"nowrap\">Excess</th>
	              </tr>";

	$tr_details ="<tr>
					<input type=\"hidden\" class=\"calc-actual save-pf-details\" id=\"doc_charge_".$details->dr_nr."_".$details->area."\" name=\"doc_charge_".$details->dr_nr."_".$details->area."\" value=\"".$details->charge."\"/>
					<input type=\"hidden\" class=\"calc-discount calc-excess\" id=\"doc_discount_".$details->dr_nr."_".$details->area."\" name=\"doc_discount_".$details->dr_nr."_".$details->area."\" value=\"".$sdoc_discount."\" area=\"".$details->area."\"/>
                    <td style=\"font:bold 12px Arial;\">".strtoupper($details->name)."</td>
                    <td class=\"rightAlign\" style=\"font:bold 12px Arial; color:#008000\">".number_format($details->charge, 2, '.' ,',')."</td>
                    <td class=\"rightAlign\" style=\"font:bold 12px Arial; color:#008000\">".number_format($sdoc_discount, 2, '.' ,',')."</td>
                    $td
                    <td class=\"rightAlign calc-total-excess\" style=\"font:bold 12px Arial; \" id=\"total-excess-".$details->dr_nr."-".$details->area."\"></td>
	              </tr>";

	$tr_footer ="<tr>
                    <th style=\"font:bold 12px Arial;\">Totals</th>
                    <th class=\"rightAlign\" style=\"font:bold 12px Arial;\" id=\"total-charges\"></th>
                    <th class=\"rightAlign\" style=\"font:bold 12px Arial;\" id=\"total-discount\"></th>
                    $footer
                    <th class=\"rightAlign\" style=\"font:bold 12px Arial;\" id=\"total-excess\"></th>
	              </tr>";
	//$objResponse->alert($objBilling->getPFDiscount());
	$objResponse->assign('coverage-header', "innerHTML", $tr_header);
	$objResponse->assign('coverage-footer', "innerHTML", $tr_footer);
	$objResponse->assign('pfDiscount','innerHTML',number_format($objBilling->getPFDiscount(),2,'.',','));
	$objResponse->assign('pfHC','innerHTML',number_format($objBilling->getPFCoverage(),2,'.',','));
	$objResponse->call('jsDoctorsCoverage','doc-coverage', $tr_details);
	$objResponse->call('set_pfdiscount', $doc_discount);
}




/**
* Created By Jarel
* Created On 04/12/2014
* Populate data in adjusted coverage (Doctor's Area)
* @param object objResponse
* @param object objBilling
* @param object details
* @return mixed objResponse
*/
function populateAdjCoverageDoctor_disc(&$objResponse, &$objBilling, $details)
{

	$th = '';
	$td  = '';
	$footer = '';
	global $db;
	$objCompany = new Company();
	$billinfo = $objBilling->hasSavedBill($details->enc);
	$refno = (($billinfo['bill_nr']) ? $billinfo['bill_nr'] : 'T'.$details->enc);
	
	$discount = $objBilling->getTotalAppliedDiscounts($details->enc);
	$getpfotherdiscount = $objBilling->pfdiscount($details->enc);
	
	$result = $objBilling->getOtherHDiscountID($details->enc);
	if ($result) {

		for ($i=0; $i < count($result); $i++) { 
			if($result[$i]['discountid'] != "SC"){
				if($result[$i]['discountdesc']){

					$th .= 	"<th width=\"10%\" nowrap=\"nowrap\">".ucwords($result[$i]['discountdesc'])."</th>";

					$result1 = $objBilling->getOtherDiscounts($refno,$result[$i]['discountid'], $details->area, $details->dr_nr);

						if ($result1->RecordCount()) {
							while ($row1 = $result1->FetchRow()) {
								$inputs = "<input id=\"discount_".$result[$i]['discountid']."_".$details->dr_nr."_".$details->area."\" 
											name=\"discount_".$result[$i]['discountid']."_".$details->dr_nr."_".$details->area."\" class=\"segInput numeric calc-excess-PF calc-hcare-PF save-pf-discount\" 
										   	type=\"text\" style=\"width:100%; text-align:right\" onfocus=\"this.select()\" 
										   	onchange=\"if(!checkNegation(this)){calculateDetails();}\"  value=\"".number_format($row1['amnt_discount'],2,'.','')."\" 
										    itemcode=\"".$details->area."\" refsource=\"1\" discID=\"".$result[$i]['discountid']."\">" ;

								$dr_discount += $row1['amnt_discount'];	

								$td .=  " <td class=\"centerLeft\" width=\"10%\">$inputs</td>";
								$footer .= "<th class=\"rightAlign\" id=\"totaldisc_".$result[$i]['discountid']."\">0.00</th>";
							} 

						}else{
						   $inputs = "<input id=\"discount_".$result[$i]['discountid']."_".$details->dr_nr."_".$details->area."\" 
							name=\"discount_".$result[$i]['discountid']."_".$details->dr_nr."_".$details->area."\" class=\"segInput numeric calc-excess-PF calc-hcare-PF save-pf-discount\" 
							   	type=\"text\" style=\"width:100%; text-align:right\" onfocus=\"this.select()\" 
							   	onchange=\"if(!checkNegation(this)){calculateDetails();}\" value=\"0.00\" 
							    itemcode=\"".$details->area."\" refsource=\"1\" discID=\"".$result[$i]['discountid']."\">" ;

							$td .= "<td class=\"centerLeft\" width=\"10%\">$inputs</td>";

							$footer .= "<th class=\"rightAlign\" id=\"totaldisc_".$result[$i]['discountid']."\">0.00</th>";
						}

				}
			}
		}
	}

			
	$isphic = $objBilling->checkInsuranceRequest($details->enc);
	if( ($isphic->RecordCount() == 0) && ($objBilling->getAccomodationType() == '1') && (!$objBilling->isMedicoLegal($details->enc)) ){
		$doc_discount = $details->charge;
	} elseif( ($isphic->RecordCount() == 0) && ($objBilling->getAccomodationType() == '1') && ($objBilling->isMedicoLegal($details->enc)) && ($discount) ){
		$doc_discount = ($details->charge * $discount) ;
	}elseif ( ($isphic->RecordCount() == 0 ) && ($objBilling->getAccomodationType() == '2') && ($discount) ) {
	 	$doc_discount = ($details->charge * $discount) ;
	} elseif ( ($isphic->RecordCount() != 0 ) && ($discount) ){
		$doc_discount = ($details->charge * $discount);
	}else{
		$doc_discount = ($details->charge * $discount);
	}
	//edited by daryl
	$ttotal = $details->totalCharge2;
	$amt = $objBilling->GetafterExcessDiscount($details->enc, $area='pf');
	$sdoc_discount = $doc_discount;
	$objBilling->setPFCoverage($dr_claim);
	$objBilling->setPFTotal($ttotal);
	// $objBilling->pfotherdiscount($dr_discount);
	$objBilling->setPFDiscount($dr_discount);

	
	$tr_header = "<tr>
					<input type=\"hidden\"  id=\"adj_refno_pf\" name=\"adj_refno_pf\" value=\"".$refno."\"/>
					<th width=\"*\">Billable Areas</th>
	                <th width=\"12%\" nowrap=\"nowrap\">Total Charge</th>
	                <th width=\"12%\" nowrap=\"nowrap\">Senior Citizen</th>
	                $th
	                <th width=\"12%\" nowrap=\"nowrap\">Excess</th>
	              </tr>";

	$tr_details ="<tr>
					<input type=\"hidden\" class=\"calc-actual-PF save-pf-discount\" id=\"doc_charge_".$details->dr_nr."_".$details->area."\" name=\"doc_charge_".$details->dr_nr."_".$details->area."\" value=\"".$details->charge."\"/>
					<input type=\"hidden\" class=\"calc-discount-PF calc-excess-PF\" id=\"doc_discount_".$details->dr_nr."_".$details->area."\" name=\"doc_discount_".$details->dr_nr."_".$details->area."\" value=\"".$sdoc_discount."\" area=\"".$details->area."\"/>
                    <td style=\"font:bold 12px Arial;\">".strtoupper($details->name)."</td>
                    <td class=\"rightAlign\" style=\"font:bold 12px Arial; color:#008000\">".number_format($details->charge, 2, '.' ,',')."</td>
                    <td class=\"rightAlign\" style=\"font:bold 12px Arial; color:#008000\">".number_format($sdoc_discount, 2, '.' ,',')."</td>
                    $td
                    <td class=\"rightAlign calc-total-excess\" style=\"font:bold 12px Arial; \" id=\"total-excessdisc-".$details->dr_nr."-".$details->area."\"></td>
	              </tr>";

	$tr_footer ="<tr>
                    <th style=\"font:bold 12px Arial;\">Totals</th>
                    <th class=\"rightAlign\" style=\"font:bold 12px Arial;\" id=\"total-charges-PF\"></th>
                    <th class=\"rightAlign\" style=\"font:bold 12px Arial;\" id=\"total-discount-PF\"></th>
                    $footer
                    <th class=\"rightAlign\" style=\"font:bold 12px Arial;\" id=\"total-excessdisc-PF\"></th>
	              </tr>";
	//$objResponse->alert($objBilling->getPFDiscount());

	$objResponse->assign('coverage-header-disc', "innerHTML", $tr_header);
	$objResponse->assign('coverage-footer-disc', "innerHTML", $tr_footer);
	$objResponse->assign('pfDiscount','innerHTML',number_format($objBilling->getPFDiscount(),2,'.',','));
	$objResponse->assign('pfHC','innerHTML',number_format($objBilling->getPFCoverage(),2,'.',','));
	$objResponse->call('jsDoctorsCoverage','doc-coverage-disc', $tr_details);
	$objResponse->call('set_pfdiscount', $doc_discount);
}

/**
* Created By Jarel
* Created On 04/12/2014
* Save Doctors Coverage
* @param array data
* @return mixed objResponse
*/
function saveDoctorCoverage($data)
{
	global $db;
	$objResponse = new xajaxResponse();
	$objBilling = new Billing();

	foreach ($data as $key => $value) {
		$str = explode("_",$key);
		if($key == 'refno')
			$refno = $value;

		if(strtolower($str[1])=="charge"){
			$doc_nr =  $str[2];
			$charge = $value;
		}elseif(strtolower($str[0])=="coverage" && $value!=0){
			$insert .= "('".$refno."','".$str[1]."','".$doc_nr."','".$str[3]."','".$charge."','".$value."'),";
		}
		
	}
	$insert = substr($insert, 0,-1);

	$objBilling->clearDoctorCoverage($refno);
	// print_r($data)
	if($insert != '')
		$ok = $objBilling->saveDoctorCoverage($insert);
	else
		$ok = true;

	if($ok){
		$objResponse->alert("Successfully saved");
	}else{
		$objResponse->alert("Failed to saved!");
	}

	return $objResponse;
}

//added by Nick 05-12-2014
function updateOpDate($op_date, $refno, $ops_code, $entry_no){
	$objResponse = new xajaxResponse();
	$objBilling = new Billing();

	$rs = $objBilling->updateOpDate(date('Y-m-d',strtotime($op_date)), $refno, $ops_code, $entry_no);
	if($rs){
		$objResponse->alert("Procedure date updated");
	}else{
		$objResponse->alert("Failed updating procedure date");
	}
	return $objResponse;
}

//added by Nick 05-15-2014
function updateMemCat($enc,$memCat){
	$objResponse = new xajaxResponse();
	$objBilling = new Billing();
	$rs = $objBilling->setMemberCategory($enc,$memCat);
	if($rs){
		$objResponse->alert('Member Category updated successfully');
		$objResponse->call('Dlg','dlgMemCat','close');
		$objResponse->call('populateBill');
		$objResponse->assign('optMemCat','innerHTML','');
	}else{
		$objResponse->alert('Failed updating Member Category');
		$objResponse->call('Dlg','dlgMemCat','close');
	}

	return $objResponse;
}

//added by ken 6/16/2014
function populateAdjCoverage($enc, $acc, $xlo, $med, $misc, $ops){
	$objResponse = new xajaxResponse();
	$objBilling = new Billing();
	$objCompany = new Company();

	$array = array(0=>'Accomodation', 1=>'XLO', 2=>'Drugs and Meds', 3=>'Operating/Delivery Room', 4=>'Miscellaneous');

	$array1 = array(0=>$acc, 1=>$xlo, 2=>$med, 3=>$ops, 4=>$misc);

	$array2 = array(0=>'acc', 1=>'xlo', 2=>'meds', 3=>'or', 4=>'misc');

	$objResponse->assign('hf-coverage-header', "innerHTML", "<tr></tr>");
	$objResponse->assign('hf-coverage-footer', "innerHTML", "<tr></tr>");
	$objResponse->assign('hf-hci-coverage', "innerHTML", "<tr></tr>");

	for($x = 0; $x <= 4; $x++){
		$th = '';
		$td = '';
		$footer = '';
		
		  //Add Company UI Jarel
	    $result_com = $objCompany->getCompanyDetailsByEnc($enc);
	    $company_amount = $objCompany->GetCompanyAmount($enc, $array2[$x]);
	    $companyCoverage = $objCompany->GetAllAmountCoverage($enc);
	    if($result_com){
	        if($result_com->RecordCount()){
	            while($row_com = $result_com->FetchRow()){
	                $th .= 	"<th width=\"2%\" nowrap=\"nowrap\" > </th>
	                             <th width=\"10%\" nowrap=\"nowrap\">Company</th>";
	 

	                        $inputs_com = "<input id=\"company_".$row_com['comp_id']."_".$enc."_".$array2[$x]."\" 
	                                                name=\"company_".$row_com['comp_id']."_".$enc."_".$array2[$x]."\" class=\"segInput numeric calc-excess-1 save-com-hci\"
	                                                    type=\"text\" style=\"width:100%; text-align:right\" onfocus=\"this.select()\"
	                                                    onchange=\"if(!checkHMONegation(this)){calculateHFDetails(".$enc.");}\" value=\"".number_format($company_amount,2,'.','')."\"
	                                                    itemcode=\"".$enc."\" comp_area=\"".$com_area[$key]."\" refsource=\"1\" company_id=\"".$row_com['comp_id']."\">" ;

	                        $td .= "<td class=\"centerLeft\" width=\"2%\">$check1</td>
	                                        <td class=\"centerLeft\" width=\"10%\">$inputs_com</td>";

	                        $footer .= "<input type=\"hidden\" id=\"doc_com\" company_id=\"".$row_com['comp_id']."\"
	                                class=\"saving-com-details\" name=\"doc_com\" comp_area=\"HCI\" value=\"0.00\"/>
	                                <th class=\"rightAlign\"></th>
	                                <th class=\"rightAlign\" id=\"company_total_hci\">".number_format($companyCoverage,2,'.','')."</th>";
	          
	            }
	        }
	    }
    //End Company
	    	$result = $objBilling->getOtherHCareCoverage($enc);
		if ($result->RecordCount()) {
			while ($row = $result->FetchRow()) {
				$th .= 	"<th width=\"10%\" nowrap=\"nowrap\">".ucwords($row['firm_id'])."</th>";

					$inputs = "<input id=\"coverage_".$row['hcare_id']."_".$enc."_".$array2[$x]."\" 
								name=\"coverage_".$row['hcare_id']."_".$enc."_".$array2[$x]."\" class=\"segInput numeric calc-excess-1 calc-hcare-1\" 
								   	type=\"text\" style=\"width:100%; text-align:right\" onfocus=\"this.select()\" 
								   	onchange=\"if(!checkHMONegation(this)){calculateHFDetails(".$enc.");}\" value=\"0.00\" 
								    itemcode=\"".$enc."\" refsource=\"1\" hcareid=\"".$row['hcare_id']."\">" ;

					$td .= "<td class=\"centerLeft\" width=\"10%\">$inputs</td>";

					$footer .= "<th class=\"rightAlign\" id=\"hf_total_".$row['hcare_id']."\">0.00</th>";
			}
		}

		$result2 = $objBilling->getOtherHDiscountID($enc);

		if($result2){
			for ($i=0; $i < count($result2) ; $i++) { 
				if($result2[$i]['discountdesc']){
					if($result2[$i]['discountid'] == "SC"){

						$get_SC_Val = $objBilling->get_SC_Val($array1[$x],$enc);
						$th .= 	"<th width=\"10%\" nowrap=\"nowrap\">".ucwords($result2[$i]['discountdesc'])."</th>";

						$inputs2 =  " <span  class=\"calc-sc-disc-1 \" 
											width=\"*\"  style=\"font:bold 12px Arial; color:#008000\" text-align:right\" itemcode_disc=\"".$enc."\" 
											refsource=\"1\" id=\"discount_".$result2[$i]['discountid']."_".$enc."_".$array2[$x]."\" name=\"discount_".$result2[$i]['discountid']."_".$enc."_".$array2[$x]."\" >
											".number_format($get_SC_Val, 2, '.' ,',')."
											</span>";

						$td .= "<td class=\"rightAlign\" width=\"10%\" >$inputs2</td>";

						$footer .= "<th class=\"rightAlign\" id=\"HCI_total_".$result2[$i]['discountid']."\">0.00</th>";
					}
				}
			}
		}

		$tr_details ="<tr>
						<input type=\"hidden\" class=\"calc-actual-1\" id=\"".$array2[$x]."_charge_".$enc."\" name=\"".$array2[$x]."_charge_".$enc."\" value=\"".$array1[$x]."\"/>
	                    <td style=\"font:bold 12px Arial;\">".strtoupper($array[$x])."</td>
	                    <td class=\"rightAlign\" style=\"font:bold 12px Arial; color:#008000\">".number_format($array1[$x], 2, '.' ,',')."</td>
	                    $td
	                    <td class=\"rightAlign calc-total-excess-1\" style=\"font:bold 12px Arial; \" id=\"total-excess-".$enc."-".$array2[$x]."\"></td>
		              </tr>";
		$objResponse->call('jsDoctorsCoverage','hf-hci-coverage', $tr_details);
	}

	$tr_header = "<tr>
					<input type=\"hidden\"  id=\"adj_enc\" name=\"adj_enc\" value=\"".$enc."\"/>
					<th width=\"*\">Billable Areas</th>
	                <th width=\"12%\" nowrap=\"nowrap\">Total Charge</th>
	                $th
	                <th width=\"12%\" nowrap=\"nowrap\">Excess</th>
	              </tr>";

	$tr_footer ="<tr>
                    <th style=\"font:bold 12px Arial;\">Totals</th>
                    <th class=\"rightAlign\" style=\"font:bold 12px Arial;\" id=\"total-charges-hf\"></th>
                    $footer
                    <th class=\"rightAlign\" style=\"font:bold 12px Arial;\" id=\"total-excess-hf\"></th>
	              </tr>";

	$objResponse->call('callHMOValue', $enc);
	$objResponse->assign('hf-coverage-header', "innerHTML", $tr_header);
	$objResponse->assign('hf-coverage-footer', "innerHTML", $tr_footer);
	$objResponse->call('calculateHFDetails',$enc);

	return $objResponse;
}

#added by daryl
#10/05/2014
#get discount and return discount in HCI
function jscalculateHFDetails_less_disc($enc){
	$objResponse = new xajaxResponse();
	$objBilling = new Billing();
	$objCompany = new Company();

	$insurance_result = $objBilling->GetInsuranceAmount($enc);
	
	$insurance_phic_total = 0;
	$insurance_phic_acc = 0;
	$insurance_phic_xlo = 0;
	$insurance_phic_meds = 0;
	$insurance_phic_or = 0;
	$insurance_phic_misc = 0;
	$insurance_hmo_total = 0;
	$insurance_hmo_acc = 0;
	$insurance_hmo_xlo = 0;
	$insurance_hmo_meds = 0;
	$insurance_hmo_or = 0;
	$insurance_hmo_misc = 0;
	$insurance_details = array();
	
	if($insurance_result){
		for ($i=0; $i <count($insurance_result) ; $i++) { 
			if($insurance_result[$i]['hcare_id']== 18){
				switch (strtolower($insurance_result[$i]['service_type'])) {
					case 'acc':
						$insurance_phic_acc = $insurance_result[$i]['amount'];
						break;
					case 'xlo':
						$insurance_phic_xlo = $insurance_result[$i]['amount'];
						break;
					case 'meds':
						$insurance_phic_meds = $insurance_result[$i]['amount'];
						break;
					case 'or':
						$insurance_phic_or = $insurance_result[$i]['amount'];
						break;
					case 'misc':
						$insurance_phic_misc = $insurance_result[$i]['amount'];
						break;
					default:
						
						break;
				}

				$insurance_phic_total = $insurance_phic_total + $insurance_result[$i]['amount'];
			
			}else{
				switch (strtolower($insurance_result[$i]['service_type'])){
					case 'acc':
						$insurance_hmo_acc = $insurance_result[$i]['amount'];
						break;
					case 'xlo':
						$insurance_hmo_xlo = $insurance_result[$i]['amount'];
						break;
					case 'meds':
						$insurance_hmo_meds = $insurance_result[$i]['amount'];
						break;
					case 'or':
						$insurance_hmo_or = $insurance_result[$i]['amount'];
						break;
					case 'misc':
						$insurance_hmo_misc = $insurance_result[$i]['amount'];
						break;
					default:
						
						break;
				}

				$insurance_hmo_total = $insurance_hmo_total + $insurance_result[$i]['amount'];
			
			}
		}
		$insurance_details = array('phic_acc'=>$insurance_phic_acc,'phic_xlo'=>$insurance_phic_xlo,'phic_meds'=>$insurance_phic_meds,'phic_or'=>$insurance_phic_or,'phic_misc'=>$insurance_phic_misc,'phic_total'=>$insurance_phic_total,
								'hmo_acc'=>$insurance_hmo_acc,'hmo_xlo'=>$insurance_hmo_xlo,'hmo_meds'=>$insurance_hmo_meds,'hmo_or'=>$insurance_hmo_or,'hmo_misc'=>$insurance_hmo_misc,'hmo_total'=>$insurance_hmo_total);
	}else{
		$insurance_details = array('phic_acc'=>$insurance_phic_acc,'phic_xlo'=>$insurance_phic_xlo,'phic_meds'=>$insurance_phic_meds,'phic_or'=>$insurance_phic_or,'phic_misc'=>$insurance_phic_misc,'phic_total'=>$insurance_phic_total,
								'hmo_acc'=>$insurance_hmo_acc,'hmo_xlo'=>$insurance_hmo_xlo,'hmo_meds'=>$insurance_hmo_meds,'hmo_or'=>$insurance_hmo_or,'hmo_misc'=>$insurance_hmo_misc,'hmo_total'=>$insurance_hmo_total);
	}

	$company_result = $objCompany->GetAmmountHospitalCoverage($enc);
	$comp_total = 0;
	$comp_acc = 0;
	$comp_xlo =0;
	$comp_meds = 0;
	$comp_or = 0;
	$comp_misc = 0;
	$companydetails = array();

	if($company_result){
		for ($i=0; $i <count($company_result) ; $i++) { 
			switch (strtolower($company_result[$i]['bill_areas'])) {
				case 'ac':
					$comp_acc = $company_result[$i]['amount'];
					break;
				case 'HS':
					$comp_xlo = $company_result[$i]['amount'];
					break;
				case 'MS':
					$comp_meds = $company_result[$i]['amount'];
					break;
				case 'OR':
					$comp_or = $company_result[$i]['amount'];
					break;
				case 'XC':
					$comp_misc = $company_result[$i]['amount'];
					break;
				default:
					
					break;
			}
			$comp_total = $comp_total + $company_result[$i]['amount'];	
		}
		$companydetails = array('acc'=>$comp_acc,'xlo'=>$comp_xlo,'meds'=>$comp_meds,
									'or'=>$comp_or,'misc'=>$comp_misc,'total'=>$comp_total);
	}else{
		$companydetails = array('acc'=>$comp_acc,'xlo'=>$comp_xlo,'meds'=>$comp_meds,
									'or'=>$comp_or,'misc'=>$comp_misc,'total'=>$comp_total);
	}
	$result = $objBilling->getOtherHDiscountID($enc);
	
	if($result){
		for ($i=0; $i < count($result) ; $i++) { 
			if($result[$i]['discountid'] == "SC"){
				$discountid_sc = $result[$i]['discountid'];
				$discount_sc = $result[$i]['discount'];
			}else{
				$discountid = $result[$i]['discountid'];
				$hosp_acc = $result[$i]['hosp_acc'];
				$hosp_xlo = $result[$i]['hosp_xlo'];
				$hosp_meds = $result[$i]['hosp_meds'];
				$hosp_ops = $result[$i]['hosp_ops'];
				$hosp_misc = $result[$i]['hosp_misc'];
			}
		}

		$objResponse->call('calculateHFDetails_less_disc',$discountid,$hosp_acc,$hosp_xlo,$hosp_meds,$hosp_ops,$hosp_misc,$discountid_sc,$discount_sc,$companydetails,$insurance_details);
	}else{
		$objResponse->call('calculateHFDetails_less_disc',"","","","","","","","",$companydetails,$insurance_details);
	}

	return $objResponse;
}
//ended bydaryl

//added by daryl
//get health insurance data 
//11/4/2014
function get_phic($enc){
	$objResponse = new xajaxResponse();
	$objBilling = new Billing();
	$array2 = array(0=>'acc', 1=>'xlo', 2=>'meds', 3=>'or', 4=>'misc');
	$acc_phic = 0;
	$xlo_phic = 0;
	$meds_phic = 0;
	$or_phic = 0;
	$misc_phic = 0;
	$acc_hmo = 0;
	$xlo_hmo = 0;
	$meds_hmo = 0;
	$or_hmo = 0;
	$misc_hmo = 0;
	for($x = 0; $x <= 4; $x++){
	$result_phic = $objBilling->if_pat_phic($enc,$array2[$x]);
	$result_hmo = $objBilling->if_pat_hmo($enc,$array2[$x]);
				switch ($result_phic['service_type']) {
					case 'acc':
						$acc_phic = $result_phic['amount'];
						break;
					
					case 'xlo':
						$xlo_phic = $result_phic['amount'];
						break;

					case 'meds':
						$meds_phic = $result_phic['amount'];
						break;

					case 'or':
						$or_phic = $result_phic['amount'];
						break;

					case 'misc':
						$misc_phic = $result_phic['amount'];
						break;

					default:
						// $acc_phic = 0;
						// $xlo_phic = 0;
						// $meds_phic = 0;
						// $or_phic = 0;
						// $misc_phic = 0;
						break;
				}

			switch ($result_hmo['service_type']) {
				case 'acc':
					$acc_hmo = $result_hmo['amount'];
					break;
				
				case 'xlo':
					$xlo_hmo = $result_hmo['amount'];
					break;

				case 'meds':
					$meds_hmo = $result_hmo['amount'];
					break;

				case 'or':
					$or_hmo = $result_hmo['amount'];
					break;

				case 'misc':
					$misc_hmo = $result_hmo['amount'];
					break;

				default:
					// $acc_hmo = 0;
					// $xlo_hmo = 0;
					// $meds_hmo = 0;
					// $or_hmo = 0;
					// $misc_hmo = 0;
					break;
			}

	}
	$total_acc = $acc_phic + $acc_hmo;
	$total_xlo = $xlo_phic + $xlo_hmo;
	$total_meds = $meds_phic + $meds_hmo;
	$total_or = $or_phic + $or_hmo;
	$total_misc = $misc_phic + $misc_hmo;

	$objResponse->call('calculateHFDetails_disc2',$total_acc,$total_xlo,$total_meds,$total_or,$total_misc);
	return $objResponse;

}
#ended by daryl

//populate HCI discount
function populateHFdiscount($enc, $acc, $xlo, $med, $misc, $ops){
	$objResponse = new xajaxResponse();
	$objBilling = new Billing();

	$array = array(0=>'Accomodation', 1=>'XLO', 2=>'Drugs and Meds', 3=>'Operating/Delivery Room', 4=>'Miscellaneous');
	$array1 = array(0=>$acc, 1=>$xlo, 2=>$med, 3=>$ops, 4=>$misc);
	
	$array2 = array(0=>'acc', 1=>'xlo', 2=>'meds', 3=>'or', 4=>'misc');
	$ifsc = "noSC";

	$objResponse->assign('hf-discount-header', "innerHTML", "<tr></tr>");
	$objResponse->assign('hf-discount-footer', "innerHTML", "<tr></tr>");
	$objResponse->assign('hf-hci-discount', "innerHTML", "<tr></tr>");

	for($x = 0; $x <= 4; $x++){
		$th = '';
		$td = '';
		$footer = '';
		$result = $objBilling->getOtherHDiscountID($enc);
		
		if ($result) {
			for ($i=0; $i < count($result); $i++) { 
				if($result[$i]['discountid']){
					
					$th .= 	"<th width=\"*\" nowrap=\"nowrap\">".ucwords($result[$i]['discountdesc'])."</th>";

					if($result[$i]['discountid'] != "SC"){
						$inputs = "<input id=\"discount_".$result[$i]['discountid']."_".$enc."_".$array2[$x]."\" class=\"segInput numeric calc-excess-disc-1 calc-discountpf-1\" 
									name=\"discount_".$result[$i]['discountid']."_".$enc."_".$array2[$x]."\" 
									type=\"text\" style=\"width:100%; text-align:right\" onfocus=\"this.select()\" 
									value=\"0.00\" onchange=\"checkDiscountLimit("."'discount_".$result[$i]['discountid']."_".$enc."_".$array2[$x]."',this);\"
									itemcode_disc=\"".$enc."\" refsource=\"1\" disc_id=\"".$result[$i]['discountid']."\">" ;
					}else{
						$ifsc = "withSC";
			
						$get_SC_Val = $objBilling->get_SC_Val($array1[$x],$enc);

						$inputs =  " <span  class=\"calc-sc-disc-1 \"
										width=\"*\"  style=\"font:bold 12px Arial; color:#008000 ; text-align:right\" itemcode_disc=\"".$enc."\" 
										refsource=\"1\" id=\"discount_".$result[$i]['discountid']."_".$enc."_".$array2[$x]."\" name=\"discount_".$result[$i]['discountid']."_".$enc."_".$array2[$x]."\" >
										".number_format($get_SC_Val, 2, '.' ,',')."
										</span>";
					}

						$td .= "<td class=\"centerLeft\" align=\"center\" width=\"10%\">$inputs</td>";

						$footer .= "<th class=\"rightAlign\" id=\"HCI_totaldisc_".$result[$i]['discountid']."\">0.00</th>";
				}
			}
		}	

		$tr_details ="<tr>
						<input type=\"hidden\" class=\"calc-actual-disc-1\" id=\"".$array2[$x]."_charge_".$enc."_".$ifsc."\" name=\"".$array2[$x]."_charge_".$enc."_".$ifsc."\" value=\"".$array1[$x]."\"/>
	                    <td style=\"font:bold 12px Arial;\">".strtoupper($array[$x])."</td>
	                    <td class=\"rightAlign\" style=\"font:bold 12px Arial; color:#008000\">".number_format($array1[$x], 2, '.' ,',')."</td>
	                    $td
	                    <td class=\"rightAlign calc-total-excess-disc-1\" style=\"font:bold 12px Arial; \" id=\"total-excess-disc-".$enc."-".$array2[$x]."\"></td>
		              </tr>";
		$objResponse->call('jsDoctorsCoverage','hf-hci-discount', $tr_details);
	}

	$tr_header = "<tr>
					<input type=\"hidden\"  id=\"adj_enc\" name=\"adj_enc\" value=\"".$enc."\"/>
					<th width=\"*\">Billable Areas</th>
	                <th width=\"12%\" nowrap=\"nowrap\">Total Charge</th>
	                $th
	                <th width=\"12%\" nowrap=\"nowrap\">Excess</th>
	              </tr>";

	$tr_footer ="<tr>
                    <th style=\"font:bold 12px Arial;\">Totals</th>
                    <th class=\"rightAlign\" style=\"font:bold 12px Arial;\" id=\"total-charges-disc-hf\"></th>
                    $footer
                    <th class=\"rightAlign\" style=\"font:bold 12px Arial;\" id=\"total-excess-disc-hf\"></th>
	              </tr>";

	$objResponse->call('callHCIDiscount', $enc);
	$objResponse->assign('hf-discount-header', "innerHTML", $tr_header);
	$objResponse->assign('hf-discount-footer', "innerHTML", $tr_footer);
	$objResponse->call('calculateHFDetails_disc');

	return $objResponse;
}
//ended by daryl

//added by daryl
//savingof HCI Discount
function saveHospitalDiscount($data)
{
	global $db;
	$objResponse = new xajaxResponse();
	$objBilling = new Billing();

	foreach ($data as $key => $value) {
		$str = explode("_",$key);

		if($key == 'enc')
			$enc = $value;

		if(strtolower($str[3])=="acc"){
			$acc_amount = $value;
		}
		if(strtolower($str[3])=="xlo"){
			$xlo_amount = $value;
		}
		if(strtolower($str[3])=="meds"){
			$meds_amount = $value;
		}
		if(strtolower($str[3])=="or"){
			$or_amount = $value;
		}
		if(strtolower($str[3])=="misc"){
			$misc_amount = $value;
		}
		if($str[1] != ""){
			$discount_id = $str[1];
		}
	}


	$ok = $objBilling->saveHospitalDiscount($enc,$discount_id,$acc_amount,$xlo_amount,$meds_amount,$or_amount,$misc_amount);
	if($ok){
		$objResponse->alert("Successfully saved");
	}else{
		$objResponse->alert("Failed to saved!");
	}

	return $objResponse;
}
//ended by daryl

//saving of Doctors Discount
function savedoctorDiscount($data)
{
	global $db;
	$objResponse = new xajaxResponse();
	$objBilling = new Billing();

	foreach ($data as $key => $value) {
		$str = explode("_",$key);

		if($key == 'enc')
			$enc = $value;
		if(strtolower($str[3])=="profdiscount"){
			$prof_amount = $value;
		}
		if($str[1] != ""){
			$discount_id = $str[1];
		}
	}

	$ok = $objBilling->saveProofDiscount($enc,$discount_id,$prof_amount);
	if($ok){
		$objResponse->alert("Successfully saved");
	}else{
		$objResponse->alert("Failed to saved!");
	}

	return $objResponse;
}
//ended by daryl

#added by daryl
#10/16/2014
#to save discount in PF
function saveDiscountPF($data)
{
    global $db;
	$objResponse = new xajaxResponse();
	$objBilling = new Billing();

	foreach ($data as $key => $value) {
		$str = explode("_",$key);
		if($key == 'refno')
			$refno = $value;
		if(strtolower($str[0])=="discount"){
			$disc_amnt = $value;
			$discountid = $str[1];
			$insert .= "('".$refno."','".$str[3]."','".$str[2]."','".$value."','".$sc_discount."','".$disc_amnt."','".$discountid."'),";
		}
	}

	$insert = substr($insert, 0,-1);
	$ok = $objBilling->clearOtherDiscount($refno,'doc');
	
	if($ok && $insert != ''){
		$ok = $objBilling->saveOtherDiscount($insert);
		if(!$ok)
			$objResponse->alert("Failed to saved PF Discount Details!");
		else
			$objResponse->alert("Successfully saved PF Discount Details!");
	}
	return $objResponse; 
   
}
//ended by daryl

//---------------------------------------------
//---------------------------------------------
//---------------------------------------------
function saveHospitalCoverage($data)
{
	global $db;
	$objResponse = new xajaxResponse();
	$objBilling = new Billing();
	$objCompany = new Company();

	foreach ($data as $key => $value) {
		$str = explode("_",$key);
		if($key == 'enc')
			$enc = $value;

		if(strtolower($str[1])=="charge"){
			$charge = $value;
		}elseif(strtolower($str[0])=="coverage" && $value!=0){
			$insert .= "('".$enc."','".$str[1]."','".$str[3]."','".$value."'),";
		}elseif(strtolower($str[0])=="company" && $value!=0){
			if(strtolower($str[3])=="acc"){
				$bill_area = "AC";
			}elseif(strtolower($str[3])=="xlo"){
				$bill_area = "HS";
			}elseif(strtolower($str[3])=="meds"){
				$bill_area = "MS";
			}elseif(strtolower($str[3])=="or"){
				$bill_area = "OR";
			}elseif(strtolower($str[3])=="misc"){
				$bill_area = "XC";
			}

			$InsertCompany .= "('".$enc."','".$bill_area."','".$value."'),";
			 
		}
		
	}
	$insert = substr($insert, 0,-1);
	$InsertCompany = substr($InsertCompany, 0,-1);

	$objBilling->clearHospitalCoverage($enc);
	$objCompany->ClearCompanyCoverage($enc);
	
	$ok = $objBilling->saveHospitalCoverage($insert);
	if($ok){
		if($InsertCompany){
			$ok2 = $objCompany->SaveCompanyCoverage($InsertCompany);
			if($ok2){
				$objResponse->alert("Successfully saved");
			}else{
				$objResponse->alert("Failed to saved!");
			}
		}
		else
			$objResponse->alert("Successfully saved");
	}else{
		if($InsertCompany){
			$ok2 = $objCompany->SaveCompanyCoverage($InsertCompany);
			if($ok2){
				$objResponse->alert("Successfully saved");
			}else{
				$objResponse->alert("Failed to saved!");
			}
		}
		else
			$objResponse->alert("Successfully saved");
	}

	return $objResponse;
}

function getOtherCoverage(&$objResponse, &$objBilling, $enc){

	$result = $objBilling->getOtherCoverage($enc);

	if($result)
		$objResponse->call('setOtherCoverage', $result);

	return $objResponse;

}

function getCompanyCoverage(&$objResponse, &$objBilling, $enc){

	$result = $objBilling->getCompanyCoverage($enc);

	if($result)
		$objResponse->call('setOtherCoverage', $result);

	return $objResponse;

}

function populateHMOValue($enc){
	$objResponse = new xajaxResponse();
	$objBilling = new Billing();

	$result = $objBilling->getHMOValue($enc);
	if ($result->RecordCount()) {
		while ($row = $result->FetchRow()) {
			$objResponse->call('setHMOValue',$row['hcare_id'], $row['encounter_nr'], $row['service_type'], $row['amount']);
		}
	}
	return $objResponse;
}

//added by daryl
//populate HCI discount and assign the HCI discount
function populateHCIdiscount($enc){
	$objResponse = new xajaxResponse();
	$objBilling = new Billing();

	$result = $objBilling->getOtherHDiscountID($enc);
	if ($result){
		for ($i=0; $i < count($result); $i++) { 
			$total = $result[$i]['hosp_acc'] +
					$result[$i]['hosp_xlo'] +
					$result[$i]['hosp_meds'] +
					$result[$i]['hosp_ops'] +
					$result[$i]['hosp_misc'];

			$CostCenterArray = array(0=>'acc', 1=>'xlo', 2=>'meds', 3=>'or', 4=>'misc');
			$CostCenterArrayValue = array(0=>$result[$i]['hosp_acc'],
										  1=>$result[$i]['hosp_xlo'],
										  2=>$result[$i]['hosp_meds'],
										  3=>$result[$i]['hosp_ops'],
										  4=>$result[$i]['hosp_misc']);
			for ($x=0; $x < count($CostCenterArray); $x++) { 
				if($result[$i]['discountid'] == "SC"){
					$objResponse->call('setHCIDiscount', 
										$result[$i]['discountid'], 
										$result[$i]['encounter_nr'], 
										$result[$i]['discount'], 
										$CostCenterArrayValue[$x], 
										$CostCenterArray[$x]);
				}else{
					$objResponse->call('setHCIDiscount',
										$result[$i]['discountid'],
										$result[$i]['encounter_nr'],
										$total,
										$CostCenterArrayValue[$x],
										$CostCenterArray[$x]);
				}
			}


		}
	}
			$objResponse->call('calculateHFDetails_disc');
		
	
	return $objResponse;
}
//ended by daryl

function getDiscounts(&$objResponse,$enc){
	global $db;

	$sql = "SELECT SUM(hcidiscount) as hcidiscount  
			FROM seg_billingapplied_discount 
			WHERE encounter_nr = ".$db->qstr($enc);
	$rs = $db->Execute($sql);
	if($rs){
		if($rs->RecordCount()>0){
			$row = $rs->FetchRow();
				if ($row['hcidiscount'] == "")
					$hcidiscount = 0;
				else
					$hcidiscount = $row['hcidiscount'];

				$objResponse->call("set_hcidiscount",$hcidiscount);
			// $objResponse->call("set_pfdiscount",$row['pfdiscount']);
		}else{
				$objResponse->call("set_hcidiscount",0);
			// $objResponse->call("set_pfdiscount",0);
		}
	}else{
		$objResponse->call("set_hcidiscount",0);
			// $objResponse->call("set_pfdiscount",0);
	}
}
function populatePrevBal(&$objResponse, &$objBilling, $pid, $enc, $bill_dte, $frmdte){
	// $objResponse = new xajaxResponse();
	// $objBilling	= new Billing();
	
	$check_enc = $objBilling->getLastEnc($enc, $pid);
	
	$prev_bal = $objBilling->getPrevBal($pid,$enc,$frmdte,$bill_dte);
	$objResponse->call('printPrevBal', $prev_bal);

	return $objResponse;
}

function GetAfterExcessDiscountHCI(&$objResponse,$enc){
	$objBilling = new billing();

	$amt = $objBilling->GetafterExcessDiscount($enc, $area='hci');
	
	$objResponse->call('getexcessdiscount', $amt, $area='hci');

	return $objResponse;
}

//added by ken 6/26/2014 for pf data to integrate in fis
function getPFforFIS($billdate, $pid, $bill_nr, $enc, $frm_dte){
	$objBill = new Billing();
	$curl_obj = new Rest_Curl();

	$objBill->setBillArgs($enc,$billdate,$frm_dte,'',$bill_nr);
	$data = array();
	$index = 0;
	$objBill->getProfFeesList();
    $objBill->getProfFeesBenefits();
    $hsp_pfs_benefits = $objBill->getPFBenefits();
    $proffees_list = $objBill->proffees_list;
    

    if(is_array($hsp_pfs_benefits) && (count($hsp_pfs_benefits) > 0)) {
	    foreach($hsp_pfs_benefits as $key=> $value) {
	        if ($value->role_area == $prevrole_area) continue;
	        $prevrole_area = $value->role_area;
	        reset($proffees_list);
	        $objBill->initProfFeesCoverage($value->role_area);
	        $totalCharge = number_format($objBill->getTotalPFCharge($value->role_area), 2);
	        $coverage    = number_format($objBill->pfs_confine_coverage[$value->role_area], 2, '.', ',');
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
	                              if ($objBill->getIsCoveredByPkg()) break;
	                        }#if
	                       }#foreach
	                   }#if

	                $drName = $profValue->dr_first." ".$profValue->dr_mid.(substr($profValue->dr_mid, strlen($profValue->dr_mid)-1,1) == '.' ? " " : ". ").$profValue->dr_last;
	                $drCharge = number_format($profValue->dr_charge, 2, '.', ',');
	                $totalPF += $profValue->dr_charge;

	                $data[$index] = array("dr_charge"=>$profValue->dr_charge,
	                                      "role_area"=>$value->role_area,
	                                      "role_desc"=>$value->role_desc,
	                                      "total_charge"=>$objBill->getTotalPFCharge($value->role_area),
	                                      "coverage"=>number_format($objBill->pfs_confine_coverage[$value->role_area], 2, '.', ','),
	                                      "drName"=>$drName,
	                                      "dr_nr" => $profValue->dr_nr
	                                     );
	                $index++;
	            }#if
	        }#foreach
	    }#foreach
	    return $curl_obj->inpatientPF($billdate, $pid, $bill_nr, $data);
	}

    
    return $objResponse;
}


/**
 * Created By Jarel
 * Created On 08/02/2014
 * Save Company Details in seg_billing_company_areas
 * @param object details
 * @param object area
 * @return mixed objResponse
 */
function saveComdetails($data,$area='hci')
{
    global $db;
    $objResponse = new xajaxResponse();
    $objCompany = new Company();
    foreach($data as $key => $value){
        $enc = $value['enc'];
        if($value['amount']>0)
            $insert[]= "(".$db->qstr($value['enc']).",".$db->qstr($value['itemcode']).", ".$db->qstr($value['dr_nr']).", ".$db->qstr($value['amount'])." )";
    }

    $insertStr =implode(",",$insert);
    $okdel = $objCompany->deleteComDetails($enc,$area);

    if($okdel && !empty($insertStr)){
        $ok = $objCompany->saveComDetails($insertStr);
        if(!$ok)
            $objResponse->alert("Failed Saving Company Details");
    }

    return $objResponse;
}

function GetCompany(&$objResponse, $enc){
	$objCompany = new Company();

	$result = $objCompany->GetAmmountHospitalCoverage($enc, $area ="UI");
	$CompanyHospital = 0;
	$CompanyProf = 0;
	$area = array('AC','HS','MS','OR','XC');

	for ($i=0; $i < count($result); $i++) { 
		if($result[$i]['bill_areas'] == 'AC' || $result[$i]['bill_areas'] == 'HS' ||
			$result[$i]['bill_areas'] == 'MS' || $result[$i]['bill_areas'] == 'OR' ||
			$result[$i]['bill_areas'] == 'XC'){
			$CompanyHospital = $CompanyHospital + $result[$i]['amount'];
		}else{
		
			$CompanyProf = $CompanyProf + $result[$i]['amount'];
		}
	}

	$objResponse->call('CompanySetCoverageUi',$CompanyHospital, $CompanyProf);

	return $objResponse;
}

#added by janken 10/23/2014 - getting the company charges in cost centers
function getCompanyCharges(&$objResponse, &$objBilling, $enc){
	$result = $objBilling->getCompanyCharges($enc);

	if ($result) {
		foreach($result AS $row) {
			$objResponse->call('setCompanyCharges',$row['comp_id'], $row['trans_amount'], $row['trans_source']);
		}
	}
	return $objResponse;
}

$xajax->processRequest();
?>
