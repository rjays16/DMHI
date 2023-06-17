<?php

function populateRequestListByRefNo($refno=0,$ref_source='LB',$fromSS=0, $discount=0, $discountid=''){
		global $db;
		$objResponse = new xajaxResponse();
		$srvObj=new SegLab();

		if (!$discount)
			$discount = 0;

		#$objResponse->alert('refno = '.$ref_source);
		$rs = $srvObj->getAllLabInfoByRefNo($refno, $ref_source,$fromSS, $discount, $discountid);

		#$objResponse->alert('sql = '.$srvObj->sql);

		if ($rs){
			while($result=$rs->FetchRow()) {
				$name = $result["name"];
				if (strlen($name)>40)
					$name = substr($result["name"],0,40)."...";

				if (empty($result['every_hour']))
					$result['every_hour'] = '';
				
                $is_serial = 0;
                $change_icon = 0;
                #check if there is serial in the request and already have a previous check-in
                $serial_row = $srvObj->getSerialCountInfo($refno, $result['service_code']);
                #$objResponse->alert($srvObj->sql);
                if ($rs){
                    $is_serial = 1;
                    #$objResponse->alert('serv = '.$serial_row['nth_takes']);
                    if ($serial_row['no_takes']){
                        $change_icon = 1;   
                    }else{
                        $change_icon = 0;   
                    }    
                }
                
                #get the list of child test
                if ($result['is_profile']){
                    $sql_child = "SELECT fn_get_labtest_child_code(".$db->qstr($result["service_code"]).") AS childtest";
                    $child_test = $db->GetOne($sql_child);
                    #$objResponse->alert($child_test);
                }
                 
                #$objResponse->alert('dsprice = '.$result['is_forward']." - ".$result['is_monitor']." - ".$result['every_hour']);
				#$objResponse->alert('name = '.$result['every_hour']);
	
				if($result['date_request']=='1970-01-01 08:00:00'||$result['date_request']=='0000-00-00 00:00:00'||$result['date_request']==null){
					$result['date_request'] = "";
				}
				else{
					$result['date_request'] = date("M. j, Y",strtotime($result['date_request']))." ".date("g:i A",strtotime($result['date_request']));
				}
				
				$objResponse->call("initialRequestList",$result['service_code'],$result['group_code'],
											$name, stripslashes($result['clinical_info']), $result['request_doctor'],
											$result['request_doctor_name'], $result['is_in_house'], $result['price_cash_orig'],
											$result['price_charge'],$result['hasPaid'],$result['is_socialized'],
											$result['approved_by_head'],$result['remarks'],$result['quantity'],number_format($result['discounted_price'], 2, '.', ''),$result['request_dept'],$result['request_flag'],
											$result['is_forward'],$result['is_monitor'],$result['every_hour'], $result['in_lis'], $result['oservice_code'], $result['ipdservice_code'],
                                            $is_serial, $change_icon, $result['is_package'],$result['is_profile'], $child_test, $result['date_request']);
			}
		}else{
			$objResponse->call("emptyIntialRequestList");
		}
		$objResponse->call("refreshDiscount");
		return $objResponse;
}# end of function populateRequestListByRefNo

function existSegOverrideAmount($ref_no){
		global $db;

		if (!$ref_no)
			return FALSE;

		$sql="SELECT *	FROM seg_override_amount
					WHERE ref_no='".$ref_no."' AND ref_source='LD'";

		if ($buf=$db->Execute($sql)){
			if($buf->RecordCount()) {
				return TRUE;
			}else { return FALSE; }
		}else { return FALSE; }
	}#end of function existSegCharityAmount

#added by VAN 08-11-2010
function updateRequest($usr, $pw, $refno, $discount_given){
	 global $db, $HTTP_SESSION_VARS;
	 $objResponse = new xajaxResponse();
	 $user= new Access($usr,$pw);

	 if($user->isKnown()&&$user->hasValidPassword()&&$user->isNotLocked()){

			if ($HTTP_SESSION_VARS['sess_user_personell_nr'])
				$personnel_nr = $HTTP_SESSION_VARS['sess_user_personell_nr'];
			elseif ($HTTP_SESSION_VARS['sess_temp_personell_nr'])
				$personnel_nr = $HTTP_SESSION_VARS['sess_temp_personell_nr'];

			$grand_dte =  date('Y-m-d H:i:s');
			$ref_source = 'LD';

			/*if (existSegOverrideAmount($refno)){
				$sql="UPDATE seg_override_amount
						SET grant_dte=NOW(), personnel_nr=".$personnel_nr.", amount=".$discount_given."
						WHERE ref_no='".$refno."' AND ref_source='".$ref_source."'";
			}else{*/
				$sql = "INSERT INTO seg_override_amount (ref_no, ref_source, grant_dte, personnel_nr, amount) ".
					 "\n VALUES('".$refno."', '".$ref_source."', '".$grand_dte."', '".$personnel_nr."' , '".$discount_given."' )";
			#}

			#$db->StartTrans();
			$ok = $db->Execute($sql);
			#$objResponse->alert($sql);
			if ($ok){
				#$db->CommitTrans();
				$objResponse->alert('Request has been successfully granted');
				$objResponse->call('submitform');
			}else{
				#$db->RollbackTrans();
				$objResponse->alert('Saving Data failed');
			}

	 }else{
		 $objResponse->alert('Your login or password is wrong');
	 }

	 return $objResponse;
}

function checkAccess($usr, $pw){
	 global $db, $HTTP_SESSION_VARS;
	 $objResponse = new xajaxResponse();
	 $user= new Access($usr,$pw);

	 if($user->isKnown()&&$user->hasValidPassword()&&$user->isNotLocked()){
		 $objResponse->call("print_true");
	 }else{
		 $objResponse->call("print_false");
	 }

	 return $objResponse;
}

	function checkTestERLab($service_code){
		global $db;
		$objResponse = new xajaxResponse();
		$srv=new SegSpecialLab();

		$row_service_code = $srv->get_TestAllowedER($service_code);
		#$objResponse->alert($srv->sql);
		#$objResponse->alert($row_service_code['service_code']);

		#allowed in ER LAB
		$service_code = trim($row_service_code['service_code']);

		if (!empty($service_code)){
			$objResponse->call("enableButtonClear",0);
		}else{
			$objResponse->call("enableButtonClear",1);
		}

		return $objResponse;
	}
    
    #added by VAS 03-23-2012
    function updateCoverage($enc_nr, $type, $nr=null) {
        global $db;

        $objResponse = new xajaxResponse();
        $amount = 0;    
        
        if ($enc_nr) {
            if ($type=='phic') {
                $bill_date = strftime("%Y-%m-%d %H:%M:%S");
                
                $bc = new Billing($enc_nr, $bill_date);

                $bc->getConfinementType();
                $amount = 0;

                define('__HCARE_ID__',18);

                

                $setmorethanone =='0';
                if($setmorethanone){
                	$total_coverage = $bc->getActualSrvCoverage(__HCARE_ID__);
                	$HasManualCoverage=$bc->GetManualPhicCoverage($enc_nr, $area='lab');
		            
		            if($HasManualCoverage){
		            	$total_benefits = $HasManualCoverage;
		            }else{
		                $total_benefits = $bc->getConfineBenefits('HS', NULL, 0, TRUE);
		            }
		            
		            if ($nr){
	                    $query = "SELECT SUM(quantity*price_charge) FROM seg_lab_servdetails WHERE refno=".$db->qstr($nr);   
	                    $covered = (float) $db->GetOne($query);
                	}
                }else{
                	$HasManualCoverage=$bc->GetManualPhicCoverageAll($enc_nr);
		            
		            if($HasManualCoverage){
		                $total_coverageLR = $bc->getActualSrvCoverage(__HCARE_ID__);
		                $total_coverageM = $bc->getActualMedCoverage(__HCARE_ID__);
		                $total_coverage = $total_coverageLR + $total_coverageM;
		                $total_benefits = $HasManualCoverage;
		            }else{
		                $total_coverage = $bc->getActualSrvCoverage(__HCARE_ID__);
		                $total_benefits = $bc->getConfineBenefits('HS', NULL, 0, TRUE);
		            }
		            #edited by daryl
		             if ($nr){
	                    $query = "SELECT SUM(quantity*price_charge) 
	                    			FROM seg_lab_servdetails 
	                    			WHERE refno=".$db->qstr($nr)." and is_forward = 1 and is_served = 1";   
	                    $covered = (float) $db->GetOne($query);
                	}
                }
      
                // $covered = 0;
                
               
                
                $objResponse->assign('coverage','value', (float)$total_benefits-(float)$total_coverage+$covered);
                $objResponse->call('refreshTotal');
            }elseif ($type=='LINGAP') {
                $lc = new SegLingapPatient();
                $pid = $db->GetOne("SELECT pid FROM care_encounter WHERE encounter_nr=".$db->qstr($enc_nr));
                $amount = $lc->getBalance($pid);
                $objResponse->assign('coverage','value', $amount);
                $objResponse->call('refreshTotal');
            }
            elseif ($type=='CMAP') {
                $amount = 0;
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
    function updatePhicExceed($enc, $pid, $check){
    	$objResponse = new xajaxResponse();
		$obj_enc = new Encounter();
		$ok = $obj_enc->IfPhicExceed($enc, $check, $pid);
			if($ok){
				if($check == 1){
					$objResponse->alert("Phic Exceed!");
				}else{
					$objResponse->alert("Phic not yet Exceed!");
				}
			}else{
				$objResponse->alert($obj_enc->sql);
			}
		return $objResponse;
    }

//added by janken 1/21/2015 changing the price of charge to cash and vice versa
function changePrice($id, $refno, $trans){
	$objResponse = new xajaxResponse();
	$srvObj=new SegLab();

	$value = $srvObj->getPrice($id, $refno, $trans);

	if($value)
		$objResponse->call('changePrices', $value, $id);

	return $objResponse;
}
    
require('./roots.php');
require($root_path.'include/inc_environment_global.php');
require($root_path.'modules/laboratory/ajax/lab-request-new.common.php');

require_once($root_path.'include/care_api_classes/class_labservices_transaction.php');
require_once($root_path.'include/care_api_classes/class_access.php');
require_once($root_path.'include/care_api_classes/class_special_lab.php');

#added by VAS 03-23-2012
require_once($root_path."include/care_api_classes/billing/class_billing.php");
require_once($root_path."include/care_api_classes/sponsor/class_lingap_patient.php");
require_once($root_path."include/care_api_classes/sponsor/class_cmap_patient.php");
require_once($root_path.'include/care_api_classes/class_encounter.php');
require_once($root_path."include/care_api_classes/class_company.php"); /*added by mai 07-15-2014*/


$xajax->processRequest();
?>