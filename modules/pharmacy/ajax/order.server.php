<?php
#added by bryan on Sept 18,2008
#

function reset_referenceno() {
	global $db;
	$objResponse = new xajaxResponse();

	$order_obj = new SegOrder("pharma");
	$lastnr = $order_obj->getLastNr(date("Y-m-d"));

	if ($lastnr)
		$objResponse->call("resetRefNo",$lastnr);
	else
		$objResponse->call("resetRefNo","Error!",1);
	return $objResponse;
}

function get_charity_discounts( $nr ) {
	global $db;
	$objResponse = new xajaxResponse();
	$discount= new SegDiscount();
	$ergebnis=$discount->GetEncounterCharityGrants( $nr );
	$objResponse->call("clearCharityDiscounts");
	if ($ergebnis) {
		$rows=$ergebnis->RecordCount();
		while($result=$ergebnis->FetchRow()) {
			$objResponse->call("addCharityDiscount",$result["discountid"],$result["discount"]);
		}
	}
	$objResponse->call("cClick");
	$objResponse->call("refreshDiscount()");
	return $objResponse;
}

function populate_order( $refno, $discountID, $disabled=NULL ) {
	global $db, $config;
	$objResponse = new xajaxResponse();

	$order_obj = new SegOrder("pharma");
	$result = $order_obj->getOrderItemsFullInfo($refno, $discountID);
	$objResponse->call("clearOrder",NULL);
	$rows = 0;
	if ($result) {
		$rows=$result->RecordCount();
		while ($row=$result->FetchRow()) {
			$obj->id = $row["bestellnum"];
			$obj->name = $row["artikelname"];
			$obj->desc= $row["description"];
			$obj->prcCash = $row["cshrpriceppk"];
			$obj->prcCharge = $row["chrgrpriceppk"];
			$obj->prcCashSC = $row["cashscprice"];
			$obj->prcChargeSC = $row["chargescprice"];
			$obj->prcDiscounted = $row["dprice"];
			$obj->isSocialized = $row["is_socialized"];
			$obj->forcePrice = $row["force_price"];
			$obj->qty = $row["quantity"];
			$obj->isConsigned = $row['is_consigned'];
			#$objResponse->alert(print_r($obj,TRUE));
			$existingOrder = true;
			$objResponse->call("appendOrder", NULL, $obj, $disabled, $existingOrder); //updated by mai 07/28/2-14, added existingOrder
		}
		if (!$rows) $objResponse->call("appendOrder",NULL,NULL);
		$objResponse->call("refreshDiscount");
	}
	else {
		if ($config['debug']) {
			$objResponse->alert("SQL error: ",$order_obj->sql);
			# $objResponse->alert($sql);
		}
		else {
			$objResponse->alert("A database error has occurred. Please contact your system administrator...");
		}
	}
	return $objResponse;
}


function add_item( $discountID, $items, $qty, $prc, $consigned ) {
	global $db;
	$dbtable='care_pharma_products_main';
	$prctable = 'seg_pharma_prices';
	$objResponse = new xajaxResponse();

	# Later: Put this in a Class
	if (!is_array($items)) $item = array($items);
	if (!is_array($qty)) $qty = array($qty);
	if (!is_array($prc)) $prc = array($prc);
	if (!is_array($consigned)) $prc = array($consigned);

	foreach ($items as $i=>$item) {

		$sql="SELECT a.*,\n".
			"IFNULL((SELECT d1.price FROM seg_service_discounts AS d1 WHERE d1.service_code=a.bestellnum AND d1.service_area='PH' AND d1.discountid='SC'),b.cshrpriceppk*(1-IFNULL((SELECT discount FROM seg_discount WHERE discountid='SC'),0.2))) AS cashscprice,\n".
			"IFNULL((SELECT d1.price FROM seg_service_discounts AS d1 WHERE d1.service_code=a.bestellnum AND d1.service_area='PH' AND d1.discountid='SC'),b.cshrpriceppk*(1-IFNULL((SELECT discount FROM seg_discount WHERE discountid='SC'),0.2))) AS chargescprice,\n".
			"IFNULL(b.ppriceppk,0) AS ppriceppk,\n".
			"IFNULL(b.chrgrpriceppk,0) AS chrgrpriceppk,\n".
			"IF(a.is_socialized,\n".
				"IFNULL((SELECT d2.price FROM seg_service_discounts AS d2 WHERE d2.service_code=a.bestellnum AND d2.service_area='PH' AND d2.discountid='$discountID'),b.cshrpriceppk),\n".
				"cshrpriceppk) AS dprice,\n".
			"IFNULL(b.cshrpriceppk,0) AS cshrpriceppk\n".
			"FROM care_pharma_products_main AS a\n".
			"LEFT JOIN seg_pharma_prices AS b ON a.bestellnum=b.bestellnum\n".
			"WHERE a.bestellnum = '$item'";
		$ergebnis=$db->Execute($sql);

#			$objResponse->alert(print_r($qty,true));
		if ($ergebnis) {
			$rows=$ergebnis->RecordCount();
			$objResponse->call("clearOrder",NULL);
			while($result=$ergebnis->FetchRow()) {
				$obj = (object) 'details';
				$obj->id = $result["bestellnum"];
				$obj->name = $result["artikelname"];
				$obj->desc= $result["description"];
				$obj->prcCash = $result["cshrpriceppk"];
				$obj->prcCharge = $result["chrgrpriceppk"];
				$obj->prcCashSC = $result["cashscprice"];
				$obj->prcChargeSC = $result["chargescprice"];
				$obj->prcDiscounted = $result["dprice"];
				$obj->isSocialized = $result["is_socialized"];
				$obj->forcePrice = $prc[$i];
				$obj->qty = $qty[$i];
				$obj->isConsigned = $consigned[$i];
				$objResponse->call("appendOrder", NULL, $obj);
			}
		}
		else {
			if (defined('__DEBUG_MODE'))
				$objResponse->call("display",$sql);
			else
				$objResponse->alert("A database error has occurred. Please contact your system administrator...");
		}
	}
	return $objResponse;
}

#added by bryan on Sept 18,2008
function populateOrderList($page_num=0, $max_rows=10, $sort_obj=NULL, $args=NULL) {
	global $config;

	$objResponse = new xajaxResponse();
	$oclass = new SegOrder();
	$selpayor = "";
	$seldate = "";
	$selarea = "";
	$selpayor = $args["selpayor"];
	$seldate = $args["seldate"];
	$selarea = $args["selarea"];

	$filters = array();
	if($selpayor!="") {
		switch(strtolower($args["selpayor"])) {
			case "name":
				$filters["NAME"] = $args["name"];
			break;
			case "pid":
				$filters["PID"] = $args["pid"];
			break;
			case "patient":
				$filters["PATIENT"] = $args["patientname"];
			break;
			case "inpatient":
				$filters["INPATIENT"] = $args["inpatientname"];
			break;
		}
	}

	if($args["seldate"]!="") {
		switch(strtolower($args["seldate"])) {
			case "today":
				$search_title = "Today's Active Requests";
				$filters['DATETODAY'] = "";
			break;
			case "thisweek":
				$search_title = "This Week's Active Requests";
				$filters['DATETHISWEEK'] = "";
			break;
			case "thismonth":
				$search_title = "This Month's Active Requests";
				$filters['DATETHISMONTH'] = "";
			break;
			case "specificdate":
				$search_title = "Active Requests On " . date("F j, Y",strtotime($args["specificdate"]));
				$dDate = date("Y-m-d",strtotime($args["specificdate"]));
				$filters['DATE'] = $dDate;
			break;
			case "between":
				$search_title = "Active Requests From " . date("F j, Y",strtotime($args["between1"])) . " To " . date("F j, Y",strtotime($args["between2"]));
				$dDate1 = date("Y-m-d",strtotime($args["between1"]));
				$dDate2 = date("Y-m-d",strtotime($args["between2"]));
				$filters['DATEBETWEEN'] = array($dDate1,$dDate2);
			break;
		}
	}

	if ($args["selarea"]!="") {
		$filters["AREA"] = $args["selarea"];
	}

	$offset = $page_num * $max_rows;
	$sortColumns = array('orderdate','refno','name_last','','is_urgent','area_full');
	$sort = array();
	if (is_array($sort_obj)) {
		foreach ($sort_obj as $i=>$v) {
			$col = $sortColumns[$i] ? $sortColumns[$i] : "orderdate";
			if ((int)$v < 0) $sort[] = "$col DESC";
			elseif ((int)$v > 0) $sort[] = "$col ASC";
		}
	}
	if ($sort) $sort_sql = implode(',', $sort);
	else $sort_sql = 'orderdate DESC';

	$result=$oclass->getActiveOrders($filters, $offset, $list_rows, $sort_sql);
//	if ($_SESSION['sess_temp_userid'] === 'admin') {
//		$objResponse->alert($oclass->sql);
//	}

	if($result) {
		$found_rows = $oclass->FoundRows();
		$last_page = ceil($found_rows/$max_rows)-1;
		if ($page_num > $last_page) $page_num=$last_page;

		if($data_size=$result->RecordCount()) {
			$temp=0;
			$i=0;
			$objResponse->contextAssign('currentPage', $page_num);
			$objResponse->contextAssign('lastPage', $last_page);
			$objResponse->contextAssign('maxRows', $max_rows);
			$objResponse->contextAssign('listSize', $found_rows);

			$DATA = array();
			while($row = $result->FetchRow()) {

				$urgency = $row["is_urgent"]?"Urgent":"Normal";
				$name = strtoupper($row["name"]);
				if (!$name) $name='<i styl	e="font-weight:normal">No name</i>';
				$class = (($count%2)==0)?"":"alt";

				//$items = explode("\n",$row["items"]);
				//$items = implode(", ",$items);
				//'stock_date','stock_nr','ward_name','items','encoder','area_full',

				$items_result = explode("\n",$row["items"]);
				$items = array();
				$served = 0;
				$is_paid = 0;
				$is_lingap = 0;
				$is_cmap = 0;
				$is_charity = 0;
				foreach ( $items_result as $j=>$v ) {
//          if (substr($v,0,1)=='S') $served=1;
//          $items[$j] = substr($v,2);
					$item_parse = explode("\t", $v);
					switch(strtolower($item_parse[0])) {
						case 'paid':
							$is_paid=1;
						break;
						case 'lingap':
							$is_lingap=1;
						break;
						case 'cmap':
							$is_cmap=1;
						break;
						case 'charity':
							$is_charity=1;
						break;
					}
					if (strtoupper($item_parse[1])=='S')
						$served=1;
					$items[$j] = $item_parse[2];
				}
				$items = implode(", ",$items);

				// determine FLAG
				$flag = '';
				if ($is_lingap)
					$flag = 'LINGAP';
				if ($is_cmap)
					$flag = 'CMAP';
				if ($is_charity)
					$flag = 'CHARITY';
				if ($is_paid)
					$flag = 'PAID';

				$DATA[$i]['orderdate'] = nl2br(date("Y-m-d\nh:ia",strtotime($row['orderdate'])));
				$DATA[$i]['refno'] = $row['refno'];
				$DATA[$i]['name'] = $name;
				$DATA[$i]['items'] = $items;
				$DATA[$i]['is_cash'] = $row['is_cash'];
				$DATA[$i]['urgency'] = $urgency;
				$DATA[$i]['area_full'] = $row['area_full'];
				$DATA[$i]['paid'] = $is_paid;
				$DATA[$i]['lingap'] = $is_lingap;
				$DATA[$i]['cmap'] = $is_cmap;
				$DATA[$i]['charity'] = $is_charity;
				$DATA[$i]['flag'] = $flag;
				$DATA[$i]['served'] = $served;
				$DATA[$i]['FLAG'] = 1;
				$DATA[$i]['count_total_items'] = $row['count_total_items'];
				$DATA[$i]['count_served_items'] = $row['count_served_items'];
				$i++;

			} //end while
			if (!$_REQUEST['selpayor']) $_REQUEST['selpayor']='name';

			$objResponse->contextAssign('dataSize', $data_size);
			$objResponse->contextAssign('listData', $DATA);
		}
		else {
			$objResponse->contextAssign('dataSize', 0);
			$objResponse->contextAssign('listData', NULL);
			#commented by daryl
			// if ($config['debug'])
			// 	$objResponse->alert("SQL empty result: ".$oclass->sql);
		}

	} else {
		// error
			#commented by daryl
			// if ($config['debug'])
			// 	$objResponse->alert('SQL error: '.$oclass->sql);
			// else {
			// 	$objResponse->alert("A database error has occurred. Please contact your system administrator...");
			// }

		$objResponse->contextAssign('dataSize', -1);
		$objResponse->contextAssign('listData', NULL);
	}

	$objResponse->script('this.fetchDone()');
	return $objResponse;
}

function deleteOrder($refno) {
	global $db;
	$objResponse = new xajaxResponse();
	$oclass = new SegOrder();
	$comp_obj = new Company();

	if ($oclass->deleteOrder($refno)) {
#    if (true) {
		/*added by mai ^_^ 07-16-2014*/
       if ($comp_obj->deleteChargetoCompanyTransaction($refno, 'PHA')){ //delete ledger charged to company
		$objResponse->call('prepareDelete',$refno);
        }else{
            $objResponse->call('lateAlert',"Unable to delete the request from company ledger.",1000);
        }
       /*end added by mai*/
	}
	else {
		$objResponse->call('lateAlert',$db->ErrorMsg(), 1000);
	}
	return $objResponse;
}

function updatePHICCoverage($enc_nr) {
	$objResponse = new xajaxResponse();
	if ($enc_nr) {
		$bill_date = strftime("%Y-%m-%d %H:%M:%S");
		$bc = new Billing($enc_nr, $bill_date);
		$bc->getConfinementType();
		#$bc->getMedicineBenefits();
		#$meds = $bc->getMedConfineBenefits();
		$bc->getConfineBenefits('MS','M');
		$confine = $bc->med_confine_benefits;

		$amount = 0;
		foreach ($confine as $v) {
			if ($v->hcare_id == 18) {
				$amount = $v->hcare_amountlimit;
			}
		}

		$objResponse->assign('phic_cov','innerHTML', number_format($amount,2));
		//$objResponse->script('alert($("phic_cov").innerHTML)');
	}
	else
		$objResponse->assign('phic_cov','innerHTML', 'None');
	return $objResponse;
}

function updateCoverage($enc_nr, $type) {
	global $db;

	$objResponse = new xajaxResponse();
	$amount = 0;

	//$objResponse->alert($type);
	//$objResponse->alert($enc_nr);
	if ($enc_nr) {
		if ($type=='PHIC') {
			$bill_date = strftime("%Y-%m-%d %H:%M:%S");
			$bc = new Billing($enc_nr, $bill_date);
			$bc->getConfinementType();
			$amount = 0;

			define('__HCARE_ID__',18);
			
			$bc->getConfineBenefits('MS','M', 0, true);
            $confine = $bc->med_confine_benefits;
            $amount = 0;
 			
 			$setmorethanone = '0';
            if($setmorethanone){
            	$total_coverage = $bc->getActualMedCoverage(__HCARE_ID__);
            	$HasManualCoverage=$bc->GetManualPhicCoverage($enc_nr, $area='pharma');
            	
            	if($HasManualCoverage){
            		$total_benefits = $HasManualCoverage;
            	}else{
	            	foreach ($confine as $v) {
	                if ($v->hcare_id == __HCARE_ID__) {
	                    $total_benefits = $v->hcare_amountlimit;
	                }
	            }
        	}
            }else{
            	$HasManualCoverage=$bc->GetManualPhicCoverageAll($enc_nr);        
	            
	            if($HasManualCoverage){
	            	$total_coverageLR = $bc->getActualSrvCoverage(__HCARE_ID__);
	            	$total_coverageM = $bc->getActualMedCoverage(__HCARE_ID__);
	            	$total_coverage = $total_coverageLR + $total_coverageM;
	            	$total_benefits = $HasManualCoverage;
	            }else{
	            	$total_coverage = $bc->getActualMedCoverage(__HCARE_ID__);
		            foreach ($confine as $v) {
		                if ($v->hcare_id == __HCARE_ID__) {
		                    $total_benefits = $v->hcare_amountlimit;
		                }
		            }
	        	}
            }
        	
			$objResponse->assign('coverage','value', (float)$total_benefits - (float)$total_coverage);
			$objResponse->call('refreshTotal');
		}
		elseif ($type=='LINGAP') {
			$lc = new SegLingapPatient();
			$pid = $db->GetOne("SELECT pid FROM care_encounter WHERE encounter_nr=".$db->qstr($enc_nr));
			$amount = $lc->getBalance($pid);
			#$objResponse->assign('cov_amount','innerHTML', number_format($amount,2));
			$objResponse->assign('coverage','value', $amount);
			$objResponse->call('refreshTotal');
		}
		elseif ($type=='CMAP') {
			$amount = 0;
			#$objResponse->assign('cov_amount','innerHTML', number_format($amount,2));

			$pc = new SegCMAPPatient();
			$pid = $db->GetOne("SELECT pid FROM care_encounter WHERE encounter_nr=".$db->qstr($enc_nr));
			$amount = $pc->getBalance($pid);

			$objResponse->assign('coverage','value', $amount);
			$objResponse->call('refreshTotal');
		}
		else {
			$objResponse->assign('cov_type','innerHTML', '');
			$objResponse->assign('cov_amount','innerHTML', '');
			$objResponse->assign('coverage','value', -1);
			$objResponse->call('refreshTotal');
		}

		//$objResponse->script('alert($("phic_cov").innerHTML)');
	}
	else
		$objResponse->assign('cov_amount','innerHTML', '');
	return $objResponse;
}
/*added by mai 07-18-2014*/
function getChargeCompanyBalance($encounter_nr, $trans_source, $refno){ /*added by mai 07-15-2014*/
	$company = new Company();
	$objResponse = new xajaxResponse();

	if($refno){
		$balance=$company->getchargeBalance($encounter_nr, $trans_source, $refno);
	}else{
		$balance=$company->getchargeBalance($encounter_nr, $trans_source);
	}
	
	if($balance){
		$objResponse->assign('charge_comp_balance', 'value', $balance);
		$objResponse->call('chargeToCompany');
	}else{
		$objResponse->assign('charge_comp_balance', 'value', 0);
	}

	return $objResponse;

}
/*end added by mai*/
function updatePhicExceed($enc, $check, $pid){
	$objResponse = new xajaxResponse();
	$obj_enc = new Encounter();

	$ok = $obj_enc->IfPhicExceed($enc, $check, $pid);
	if($ok){
		if($check == 1)
			$objResponse->alert("Phic Exceed!");
		else
			$objResponse->alert("Phic not yet Exceed!");
		
	}else{
		$objResponse->alert($obj_enc->sql);
	}
	return $objResponse;

}

//added by janken 1/22/2015 changing the price of charge to cash and vice versa
function changePrice($id, $refno, $trans){
    $objResponse = new xajaxResponse();
    $ord_obj = new SegOrder();

    $value = $ord_obj->getPrice($id, $refno, $trans);

    if($value)
        $objResponse->call('changePrices', $value, $id);

    return $objResponse;
}

require('./roots.php');
require($root_path.'include/inc_environment_global.php');
require($root_path.'include/care_api_classes/class_discount.php');
require($root_path.'include/care_api_classes/class_order.php');
require_once($root_path."include/care_api_classes/billing/class_billing.php");
require_once($root_path."include/care_api_classes/sponsor/class_lingap_patient.php");
require_once($root_path."include/care_api_classes/sponsor/class_cmap_patient.php");
require_once($root_path."include/care_api_classes/class_encounter.php");
require_once($root_path.'modules/pharmacy/ajax/order.common.php');
require_once($root_path."include/care_api_classes/class_company.php"); /*added by mai 07-15-2014*/

$xajax->processRequest();
