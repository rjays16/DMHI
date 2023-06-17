<?php
require('./roots.php');
require($root_path.'include/inc_environment_global.php');
require_once($root_path.'modules/billing_new/ajax/billing-addtl-insurance.common.php');
require_once($root_path.'include/care_api_classes/class_discount.php');
require_once($root_path.'include/care_api_classes/billing/class_billing_new.php');

function getApplicableInsurance($enc) {

	$objResponse = new xajaxResponse();
	$insurance = new Billing();
 	
 	$list = $insurance->getInsuranceInfo($enc);

	$objResponse->addScriptCall("jsClearList", "insurance_details");

	if ($list) {
		if ($list->RecordCount()) {
			while ($row = $list->FetchRow()) 				
				$objResponse->addScriptCall("addApplicableInsurance", $row['hcare_id'], $row['firm_id'], $row['acc_coverage'], $row['xlo_coverage'], $row['med_coverage'], $row['ops_coverage'], $row['msc_coverage'], $row['doc_coverage']);
		}
		else
			$objResponse->addScriptCall("addApplicableInsurance", '', '', 0, 0, 0, 0, 0, 0);
	} 
	else 
		$objResponse->addScriptCall("addApplicableInsurance", '', '', 0, 0, 0, 0, 0, 0);
			
	return $objResponse;
}

function fillInsuranceCbo($id, $enc) {
	global $db;
	$objResponse = new xajaxResponse();
	$insurance = new Billing();

	$result = $insurance->getInsuranceRequest($id, $enc);

    if($result){
        $objResponse->addScriptCall("js_ClearOptions", "insurance_list");
        if($result->RecordCount())
         	$objResponse->addScriptCall("js_AddOptions","insurance_list", "- Select Insurance -", "-");
        else
        	$objResponse->addScriptCall("js_AddOptions","insurance_list", "- No Insurance Available -", "-");
        while($row=$result->FetchRow()) 
            $objResponse->addScriptCall("js_AddOptions","insurance_list", $row["firm_id"],$row["hcare_id"], ($id == $row['hcare_id']));
    }
    else
    	$objResponse->addAlert("ERROR: ".$db->ErrorMsg());
	
	return $objResponse;
}

function SaveAppliedInsurance($data) {
	global $db;
	$objResponse = new xajaxResponse();
	$insurance = new Billing();

	$insurance_total = $insurance->verify_insurance($data, '1');

	if($insurance_total){
		while($row = $insurance_total->FetchRow()){
			$data['accEx'] = $data['accEx'] - $row['acc'];
			$data['xloEx'] = $data['xloEx'] - $row['xlo'];
			$data['medEx'] = $data['medEx'] - $row['med'];
			$data['orEx'] = $data['orEx'] - $row['ops'];
			$data['mscEx'] = $data['mscEx'] - $row['msc'];
			$data['pfEx'] = $data['pfEx'] - $row['doc'];
			$totalExcess = $data['hfEx'] + $data['pfEx'];
			$ins_total = $data['accommodation'] + $data['xlso'] + $data['meds'] + $data['or'] + $data['misc'] + $data['doctors'];

			if($data['accommodation']>$data['accEx']){
				$objResponse->addAlert("Accommodation entry exceeded the total accommodation excess.");
				$objResponse->addScriptCall("focusId", 'accommodation');
			}
			else if($data['xlso']>$data['xloEx']){
				$objResponse->addAlert("XLSO entry exceeded the total xlso excess.");
				$objResponse->addScriptCall("focusId", 'xlso');
			}
			else if($data['meds']>$data['medEx']){
				$objResponse->addAlert("Drugs and Meds entry exceeded the total meds excess.");
				$objResponse->addScriptCall("focusId", 'meds');
			}
			else if($data['or']>$data['orEx']){
				$objResponse->addAlert("OR/DR entry exceeded the total OR excess.");
				$objResponse->addScriptCall("focusId", 'or');
			}
			else if($data['misc']>$data['mscEx']){
				$objResponse->addAlert("Miscellaneous entry exceeded the total miscellaneous excess.");
				$objResponse->addScriptCall("focusId", 'misc');
			}
			else if($data['doctors']>$data['pfEx']){
				$objResponse->addAlert("Doctor entry exceeded the total doctor excess.");
				$objResponse->addScriptCall("focusId", 'doctors');
			}
			else if($totalExcess<$ins_total){
				$double_chk = $insurance->verify_insurance($data, '0');

				if($double_chk->RecordCount()){
					while($double_row = $double_chk->FetchRow()){
						$double_ins = $double_row['acc_coverage'] + $double_row['xlo_coverage'] +  $double_row['med_coverage'] +
										$double_row['ops_coverage'] + $double_row['misc_coverage'];
						$data['hfEx'] = $data['hfEx'] - $double_ins;
						if($data['hfEx']<$ins_total){
							$objResponse->addAlert("Total Insurance amount exceeded the hospital bill amount.");
							$objResponse->addScriptCall("clearFields");
						}
						else
							saveAdditionalInsurance(&$data);
					}
				}
				else{
					$objResponse->addAlert("Total Insurance amount exceeded the hospital bill amount.");
					$objResponse->addScriptCall("clearFields");
				}
			}
			else{
				saveAdditionalInsurance(&$data);
			}
		}
	}
	else{
		$objResponse->addAlert("Failed to Save! ERROR: ".$db->ErrorMsg());
	}
	$objResponse->addScriptCall("js_getApplicableInsurance");
	return $objResponse;
}// end of function SaveAppliedInsurance()

function saveAdditionalInsurance(&$data){
	global $db;

	$objResponse = new xajaxResponse();
	$insurance = new Billing();

	$check_insurance = $insurance->verify_insurance($data, '0');

	if(!$check_insurance->RecordCount()){
		$result = $insurance->save_Additional_Insurance($data);
		if($result)
			$objResponse->alert('Successfully Save!');
		else
			$objResponse->addAlert("Failed to Save! ERROR: ".$db->ErrorMsg());
	}
	else{
		$result = $insurance->update_Additional_Insurance($data);
		if($result){
			$objResponse->alert('Successfully Save!');
		}
		else
			$objResponse->addAlert("Failed to Save! ERROR: ".$db->ErrorMsg());
	}
	$objResponse->addScriptCall("js_getApplicableInsurance");
	return $objResponse;
}

function getInsuranceInfo($enc, $id) {
	global $db;
	$objResponse = new xajaxResponse();
	$insurance = new Billing();

	$info = $insurance->getInsuranceInfo($enc, $id);

	if ($info) {			
		if ($info->RecordCount()) {
			if ($row = $info->FetchRow()) 
				$objResponse->addScriptCall("js_showInsuranceInfo", $row['hcare_id'], $row['firm_id'], $row['acc_coverage'], $row['xlo_coverage'], $row['med_coverage'], $row['ops_coverage'], $row['msc_coverage'], $row['doc_coverage']);
			else
				$objResponse->addScriptCall("js_showInsuranceInfo", '', '', 0, 0, 0, 0, 0, 0);
		}
	}
	else
		$objResponse->alert("ERROR: ".$db->ErrorMsg());

	return $objResponse;
}

function deleteInsurance($enc, $id) {
	global $db;
	$objResponse = new xajaxResponse();
	$insurance = new Billing();

	$result = $insurance->deleteInsurance($enc, $id);
	if ($result) 
		$objResponse->addScriptCall("js_getApplicableInsurance");
	else
		$objResponse->alert("ERROR: ".$db->ErrorMsg());

	return $objResponse;
}

$xajax->processRequests();
?>
