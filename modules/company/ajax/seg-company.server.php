<?php

require('./roots.php');
require($root_path.'include/inc_environment_global.php');
require_once($root_path.'modules/company/ajax/seg-company.common.php');
require_once($root_path.'include/care_api_classes/class_company.php');

function searchCompany($key){
	$comp_obj = new Company();
	$objResponse = new xajaxResponse();

	$result = $comp_obj->getCompany($key);
//$objResponse->alert($comp_obj->sql);
	if($result){
		while($row = $result->FetchRow()){
			$details->name = $row['comp_full_name'];
			$details->id = $row['comp_id'];
			$details->add = $row['comp_add'];
			$details->phone_nr = $row['comp_phone_nr'];
			$objResponse->call('appendComp',$details);
		}		
	}else{
		$objResponse->alert("Unable to fetch list of Company or Employee");
	}

	return $objResponse;
}

function saveCompany($details){
	$comp_obj = new Company();
	$objResponse = new xajaxResponse();

	$result = $comp_obj->addCompany($details);
	$objResponse->call('response', $result, "added");

	return $objResponse;
}

function updateCompany($details){
	$comp_obj = new Company();
	$objResponse = new xajaxResponse();

	$result = $comp_obj->updateCompany($details);
	$objResponse->call('response', $result, "updated");

	return $objResponse;
}

function getData($comp_id){
	$comp_obj = new Company();
	$objResponse = new xajaxResponse();

	$result = $comp_obj->getCompanyDetails($comp_id);
	if($result){
		while($row = $result->FetchRow()){
			$details->comp_full_name = $row['comp_full_name'];
			$details->comp_name = $row['comp_name'];
			$details->comp_add = $row['comp_add'];
			$details->comp_email_add = $row['comp_email_add'];
			$details->comp_phone_nr = $row['comp_phone_nr'];
			$details->pres = $row['comp_ceo'];
			$details->hr = $row['comp_hr'];
			$objResponse->call('setData',$details);
		}		
	}else{
		$objResponse->alert("Unable to fetch list of Company or Employee");
	}

	return $objResponse;
}

$xajax->processRequest();

?>