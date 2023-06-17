<?php
require('./roots.php');
require($root_path.'include/inc_environment_global.php');
require_once($root_path.'modules/billing/ajax/billing-discounts.common.php');
require_once($root_path.'include/care_api_classes/class_discount.php');

function getApplicableDiscounts($s_encnr, $frmdte, $billdte) {
	global $db;
	
	$objResponse = new xajaxResponse();

    $strSQL = "SELECT 0 AS entry_no, 
    					discountid, 
    					discountdesc, 
    					discount, 
    					discount_amnt,
    					hcidiscount,
    					pfdiscount, 
    					billareas_applied,
                 		(SELECT group_concat(distinct benefit_desc 
                 			ORDER BY benefit_desc separator '; ') AS area_desc
                     	FROM seg_hcare_benefits AS shb
                     	WHERE sbd.`billareas_applied` regexp concat(shb.`bill_area`,':')) AS areas, 
						remarks
                FROM seg_billingapplied_discount as sbd
                WHERE encounter_nr = ' $s_encnr.'
                AND str_to_date(entry_dte, '%Y-%m-%d %H:%i:%s') < '$frmdte'
                
                union
              	
              	SElECT entry_no, 
              		discountid, 
              		discountdesc, 
              		discount, 
              		discount_amnt,
              		hcidiscount,
              		pfdiscount, 
              		billareas_applied,
              	   (SElECT group_concat(distinct benefit_desc 
              	   		ORDER BY benefit_desc separator '; ') AS area_desc
                    FROM seg_hcare_benefits AS shb
                    WHERE sbd.`billareas_applied` regexp concat(shb.`bill_area`,':')) AS areas, 
					remarks
               	FROM seg_billingapplied_discount AS sbd
                WHERE encounter_nr = '$s_encnr' AND (str_to_date(entry_dte, '%Y-%m-%d %H:%i:%s') >= '$frmdte'
			  	AND str_to_date(entry_dte, '%Y-%m-%d %H:%i:%s') < '$billdte')";

	$objResponse->addScriptCall("jsClearList", "discount_details");				  
	if ($result = $db->Execute($strSQL)) {
		if ($result->RecordCount()) 
			while ($row = $result->FetchRow()) 				
				$objResponse->addScriptCall("addApplicableDiscount", $s_encnr, $row['entry_no'], $row['discountid'], $row['discountdesc'], $row['billareas_applied'], $row['areas'], $row['remarks'], $row['discount'], $row['discount_amnt'], $row['hcidiscount'], $row['pfdiscount']);
		else
			$objResponse->addScriptCall("addApplicableDiscount", NULL, NULL, '', '', '', '', '', 0, 0);
	} 
	else 
		$objResponse->addScriptCall("addApplicableDiscount", NULL, NULL, '', '', '', '', '', 0, 0);
			
	return $objResponse;
}

function fillDiscountsCbo($s_id = '') {
	global $db;
	
	$objResponse = new xajaxResponse();

	$strSQL = "select * ".
   			  "   from seg_discount ".
   			  "   where (area_used = 'B' or isnull(area_used) or area_used = '') ".
	  		  "      and not is_charity ".
   			  "   order by discountdesc";
			  
	if ($result = $db->Execute($strSQL)) {
		$objResponse->addScriptCall("js_ClearOptions","discount_list");
	
		if ($result->RecordCount()) 
			$objResponse->addScriptCall("js_AddOptions","discount_list","- Select Discount -", '-');
		else 
			$objResponse->addScriptCall("js_AddOptions","discount_list","- No Discounts Available -", '-');
		
		while ($row = $result->FetchRow()) 
				$objResponse->addScriptCall("js_AddOptions","discount_list", $row['discountdesc'], $row['discountid'], ($s_id == $row['discountid']));						
	}
	else 
		$objResponse->addAlert("ERROR: ".$db->ErrorMsg());
	
	return $objResponse;
}

function SaveAppliedDiscount($aFormValues, $bill_dt = "0000-00-00 00:00:00") {
	global $db;
	$objResponse = new xajaxResponse();
	$bolError = false;	
	
	if(array_key_exists("enc_nr", $aFormValues)) {		
		// Adjust current time by 1 second earlier than cut-off date in billing ...			
		if (strcmp($bill_dt, "0000-00-00 00:00:00") != 0) 
			$tmp_dte = $bill_dt;
		else
			$tmp_dte = strftime("%Y-%m-%d %H:%M:%S");	
		$tmp_dte = strftime("%Y-%m-%d %H:%M:%S", strtotime("-1 second", strtotime($tmp_dte)));		
						
		if (!$bolError) {
			if (($aFormValues['entry_no'] != '0') && ($aFormValues['entry_no'] != '')) {		
				$msg = "successfully Update";				
				$strSQL = "update seg_billingapplied_discount set ".
						  " discountid        = '".$aFormValues['discount_id']."', ".
						  " discountdesc      = '".$aFormValues['discount_desc']."', ".
						  " discount          =  ".( ($aFormValues['discount'] == '') ? '0.0000' : $db->qstr($aFormValues['discount'])).", ".
                          " discount_amnt     =  ".( ($aFormValues['discountamnt'] == '') ? '0.00' :  $db->qstr($aFormValues['discountamnt'])).", ".
                          " hcidiscount     =  ".( ($aFormValues['Hospdiscount'] == '') ? '0.00' :  $db->qstr($aFormValues['Hospdiscount'])).", ".
                          " pfdiscount     =  ".( ($aFormValues['Profdiscount'] == '') ? '0.00' :  $db->qstr($aFormValues['Profdiscount'])).", ".
						  " remarks           = '".$aFormValues['remarks']."', ".
						  " billareas_applied = '".$aFormValues['areas_id']."', ".
						  " modify_id         = '".$_SESSION['sess_user_name']."' ".
						  " where encounter_nr = '".$aFormValues['enc_nr']."' ".
						  " and entry_no    = ".$aFormValues['entry_no'];						  
			}
			else {
				$msg = "successfully Added";
				$strSQL = "INSERT INTO seg_billingapplied_discount 
							(encounter_nr, 
								entry_dte, 
								discountid, 
								discountdesc, 
								discount, 
								discount_amnt,
								hcidiscount,
								pfdiscount,
								remarks, 
								billareas_applied, 
								modify_id, create_id)
						   VALUES 
						   ('".$aFormValues['enc_nr']."', 
						   	'".$tmp_dte."', 
						   	'".$aFormValues['discount_id']."', 
						   	'".$aFormValues['discount_desc']."', 
						   	".(($aFormValues['discount'] == '') ? '0.0000' : $aFormValues['discount']).", 
						   	".(($aFormValues['discountamnt'] == '') ? '0.00' :  $db->qstr($aFormValues['discountamnt'])).",
						   	".(($aFormValues['Hospdiscount'] == '') ? '0.00' :  $db->qstr($aFormValues['Hospdiscount'])).",
						   	".(($aFormValues['Profdiscount'] == '') ? '0.00' :  $db->qstr($aFormValues['Profdiscount'])).",
						   	'".$aFormValues['remarks']."', 
						   	'".$aFormValues['areas_id']."', 
						   	'".$_SESSION['sess_user_name']."', 
						   	'".$_SESSION['sess_user_name']."')";
			}
			
			if ($db->Execute($strSQL)){
				$objResponse->alert($msg);
				$objResponse->addScriptCall("js_getApplicableDiscounts");
			}else 
				$objResponse->alert("ERROR: ".$db->ErrorMsg()."\n".$strSQL);
		}
	}
	
	return $objResponse;
}// end of function SaveAppliedDiscount()

function getDiscountInfo($enc_nr, $entry_no) {
	global $db;
	
	$objResponse = new xajaxResponse();
	
	$strSQL = "select sdb.*, ".
			  "   (select group_concat(distinct benefit_desc order by benefit_desc separator '\n') as area_desc ".
			  "       from seg_hcare_benefits as shb where sdb.billareas_applied regexp concat(shb.bill_area,':')) as areas ".		
	          "   from seg_billingapplied_discount as sdb ".
			  "   where sdb.encounter_nr = '".$enc_nr."' and sdb.entry_no = ".$entry_no;
	if ($result = $db->Execute($strSQL)) {			
		if ($result->RecordCount()) {
			if ($row = $result->FetchRow()) 
				$objResponse->addScriptCall("js_showDiscountInfo", $row['discountid'], $row['discountdesc'], $row['remarks'], $row['billareas_applied'], $row['areas'], $row['discount'],$row['hcidiscount'],$row['pfdiscount']);
			else
				$objResponse->addScriptCall("js_showDiscountInfo", '', '', '', '', '', 0);
		}
	}
	else
		$objResponse->alert("ERROR: ".$db->ErrorMsg());

	return $objResponse;
}

function deleteDiscount($enc_nr, $entry_no) {
	global $db;
	
	$objResponse = new xajaxResponse();
	
	$strSQL = "delete from seg_billingapplied_discount ".
			  "   where encounter_nr = '".$enc_nr."' and entry_no = ".$entry_no;
	if ($db->Execute($strSQL)) 
		$objResponse->addScriptCall("js_getApplicableDiscounts");
	else
		$objResponse->alert("ERROR: ".$db->ErrorMsg());

	return $objResponse;
}

function getBillAreasApplied($sdiscount_id) {
	global $db;

	$objd  = new SegDiscount();		
	$areas = $objd->getBillAreas($sdiscount_id);
	
	$areas_desc = '';
	
	$objResponse = new xajaxResponse();
	
	if ($areas) {
		$strSQL = "select group_concat(distinct benefit_desc order by benefit_desc separator '\n') as area_desc ".
   				  "   from seg_hcare_benefits as shb where '".$areas."' regexp concat(shb.bill_area,':')";
		if ($result = $db->Execute($strSQL)) {
			if ($result->RecordCount()) {
				if ($row = $result->FetchRow()) $areas_desc = $row['area_desc'];
			}
		}
	}
	else
		$areas = '';
	
	$objResponse->addScriptCall("js_showBillAreas", $areas, $areas_desc);
	
	return $objResponse;	
}

function getDiscount($sdiscount_id) {
	global $db;
	
	$objResponse = new xajaxResponse();	

	$objd   = new SegDiscount();		
	$n_rate = $objd->getDiscount($sdiscount_id);
	if ($n_rate) {
		$objResponse->addScriptCall("js_showDiscount", number_format($n_rate, 4, '.', ''));
	}
	
	return $objResponse;
}

function validateDC($value,$enc) {
	global $db;
	
	$objResponse = new xajaxResponse();	
	$objd   = new SegDiscount();
	if ($value == "SC"){
		$val = "SC";
	}else{
		$val = $value;
	}		
	$sDC_id = $objd->Validate_Discount($enc,$val);
	$DC_id = explode("/", $sDC_id);

	$scounter = count($DC_id);
	if ($scounter > 0)
	$counter = $scounter - 1;

	if ($counter == 1){
		$objResponse->alert("one entry is allowed");	
	
		$objResponse->addScriptCall("js_resetOption");

	}

	// for ($i=0; $i < $counter; $i++) { 
	// 	// $objResponse->alert($DC_id[$i]);

	// 	if ($value == $DC_id[$i]){
	// 	$objResponse->alert($value." is already ");		
	// 	}

	// }
	
	return $objResponse;
}

#added by janken 11/05/2014
function deleteHCICoverage($data){
	$objResponse = new xajaxResponse();	
	$disc_obj = new SegDiscount();

	$disc_obj->DeleteHCI($data['enc_nr']);

	return $objResponse;
}

$xajax->processRequests();
?>
