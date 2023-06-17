<?php
/*created by mai 06-24-2014*/
/**
* @package care_api
*/

require_once($root_path.'include/care_api_classes/class_core.php');

class Company extends Core {
	/**
	* Table name for company per encounter
	* @var string
	*/
	var $tb_encounter_company='seg_company_allotment';
	/**
	* Table name for header billing company
	* @var string
	*/
	var $tb_billing_h='seg_company_billing_h';
	/**
	* Table name for details billing company
	* @var string
	*/
	var $tb_billing_d='seg_company_billing_d';
	/**
	* Table name for list of companies
	* @var string
	*/
	var $tb_company='seg_company'; # insurance companies
	/**
	* Buffer for sql query results
	* @var mixed adodb record object or boolean
	*/
	var $result;
	/**
	*Table name for charge to company bills
	*@var string
	*/
	var $tb_ledger='seg_company_ledger';
	/**
	* Buffer for row returned by adodb's FetchRow() method
	* @var array
	*/
	var $row;
	/**
	* Universal buffer
	* @var mixed
	*/
	var $buffer;
	/**
	* Sql query string
	* @var string
	*/
	var $sql;
	/**
	* Universal event flag
	* @var boolean
	*/
	var $ok;
	/**
	* table name for laboratory requests
	* @var string
	*/
	var $costcenter_table;
	/**
	* column names for laboratory requests, refno, price charge and grant columns
	* @var string
	*/
	var $costcenter_refnoColumn;
	var $costcenter_chargeColumn;
	var $costcenter_statusColumn;

	var $tb_bill_coverage_area = "seg_billing_company_areas";

	function getCompanyBilling($comp_id){
		global $db;

		$this->sql = "SELECT 
					  h.comp_bill_nr AS bill_nr,
					  h.bill_date AS bill_frmdte,
					  h.bill_date AS bill_dte,
					  c.comp_name AS fullname,
					  h.discount,
					  (SELECT 
					    SUM(d.bill_amount) - h.discount 
					  FROM ".
					    $this->tb_billing_d ." d 
					  WHERE d.comp_bill_nr = h.comp_bill_nr) AS amount_due,
					  IFNULL(
					    (SELECT 
					      SUM(trans_amount) 
					    FROM ".
					      $this->tb_ledger 
					    ." WHERE trans_type = 'PAYMNT' 
					      AND trans_source = 'CSH' 
					      AND comp_bill_nr = h.comp_bill_nr),
					    0
					  ) AS payments 
					FROM ".
					  $this->tb_billing_h ." h 
					  LEFT JOIN seg_company AS c 
					    ON c.comp_id = h.comp_id 
					WHERE h.is_deleted = 0 
					  AND h.comp_id = ".$comp_id 
					." ORDER BY h.bill_date DESC ";

		if($this->result=$db->Execute($this->sql)){
			return $this->result;
		} else{ 
			return false; 
		}
	}

	function checkRequest($encounter_nr){
		global $db;
		$this->sql = "SELECT encounter_nr FROM ".$this->tb_ledger." WHERE encounter_nr = ".$db->qstr($encounter_nr);
		
		if($this->result = $db->Execute($this->sql)){
			$row=$this->result->FetchRow();
     		return $row['encounter_nr'];
		}else{
			return false;
		}
	}

	function calculateSumCostCentersItems($refno){
		global $db;

			$this->sql = "SELECT SUM(".$this->costcenter_chargeColumn.") AS total_charge FROM ".$this->costcenter_table
							 ." WHERE ".$this->costcenter_refnoColumn."=".$db->qstr($refno).$this->costcenter_statusColumn;

		if($this->result=$db->Execute($this->sql)){
			if($row = $this->result->FetchRow()){
				return $row['total_charge'];
			}
		}
		
		return false;
	}

function selectBillingHeader($billNr){
		global $db;

		$this->sql = "SELECT discount, comp_bill_nr FROM seg_company_billing_h WHERE comp_bill_nr = ".$db->qstr($billNr);

		if($this->result = $db->Execute($this->sql)){
			return $this->result;
		}

		return false;
	}
	//modified by EJ 09/01/2014
	function getBilledEmployeesTransmittal($billnr,$case_nrs){
		global $db;

		$billnr = $db->qstr($billnr);
		$this->sql = "SELECT 
						  scl.`encounter_nr`,
						  fn_get_person_name (ce.`pid`) AS name,
					  CASE
						    WHEN ce.encounter_type = 2 
					    THEN 'O' 
					    ELSE 
					    CASE
						      WHEN ce.encounter_type = 3 
						      OR ce.encounter_type = 4 
					      THEN 'I' 
					      ELSE 'ER' 
					    END 
					  END AS encounter_type,
						  ce.`discharge_date`,
						  ce.`discharge_time`,
					  IFNULL((SELECT
						IFNULL(SUM(amount),0)
						FROM seg_billing_company_areas
						WHERE encounter_nr = scl.`encounter_nr`
							AND bill_areas IN ('AC','HS','MS', 'XC','OR')),(SELECT 
						    IFNULL(SUM(trans_amount), 0) 
						  FROM
						    seg_company_ledger 
						  WHERE encounter_nr = scl.`encounter_nr` 
						    AND trans_type = 'CHARGE' 
						    AND trans_source IN ('ACC', 'XLO', 'MED', 'MSC', 'OPR', 'RAD', 'LAB', 'PHA') 
						    AND comp_bill_nr = $billnr)) AS HB,
					      IFNULL((SELECT
							IFNULL(SUM(amount),0)
							FROM seg_billing_company_areas
							WHERE encounter_nr = scl.`encounter_nr`
								AND bill_areas IN ('D1','D2','D3', 'D4')),(SELECT 
						    IFNULL(SUM(trans_amount), 0) 
					      FROM
					        seg_company_ledger 
						  WHERE encounter_nr = scl.`encounter_nr` 
						    AND trans_type = 'CHARGE' 
						    AND trans_source = 'DOC' 
						    AND comp_bill_nr = $billnr)) AS PF,
						  IFNULL(
						    (SELECT 
						      GROUP_CONCAT(cie.description) 
						    FROM
						      care_encounter_diagnosis ced 
						      LEFT JOIN care_icd10_en cie 
						        ON ced.code = cie.diagnosis_code 
						    WHERE ced.encounter_nr = ce.encounter_nr 
						      AND ced.status <> 'deleted'),
						    ''
						  ) AS diagnosis 
						FROM
						  seg_company_ledger scl 
						  LEFT JOIN care_encounter ce 
						    ON ce.`encounter_nr` = scl.`encounter_nr` 
						WHERE scl.`encounter_nr` IN (
						   $case_nrs
					    ) 
						  AND trans_type = 'CHARGE' 
						GROUP BY scl.`encounter_nr` ";

		$this->result=$db->Execute($this->sql);
        if($this->result){
            return $this->result;
        }
        else return false;
	}

	//added by EJ gwapo 09/01/2014
	function getUnBilledEmployeesTransmittal($compid, $case_nrs){
		global $db;

		$compid = $db->qstr($compid);
		$this->sql = "SELECT 
						  scl.`encounter_nr`,
						  fn_get_person_name (ce.`pid`) AS name,
						  CASE
						    WHEN ce.encounter_type = 2 
						    THEN 'O' 
						    ELSE 
						    CASE
						      WHEN ce.encounter_type = 3 
						      OR ce.encounter_type = 4 
						      THEN 'I' 
						      ELSE 'ER' 
						    END 
						  END AS encounter_type,
						  ce.`discharge_date`,
						  ce.`discharge_time`,
						  IFNULL((SELECT
						IFNULL(SUM(amount),0)
						FROM seg_billing_company_areas
						WHERE encounter_nr = scl.`encounter_nr`
							AND bill_areas IN ('AC','HS','MS', 'XC','OR')),(SELECT 
						    IFNULL(SUM(trans_amount), 0) 
					  FROM
						    seg_company_ledger 
						  WHERE encounter_nr = scl.`encounter_nr` 
					    AND trans_type = 'CHARGE' 
						    AND trans_source IN ('ACC', 'XLO', 'MED', 'MSC', 'OPR', 'RAD', 'LAB', 'PHA') 
						    AND (
						      comp_bill_nr IS NULL 
						      OR comp_bill_nr IN 
						      (SELECT 
						        comp_bill_nr 
						      FROM
						        seg_company_billing_h 
						      WHERE is_deleted = 1)
						    ))) AS HB,
					    IFNULL((SELECT
							IFNULL(SUM(amount),0)
							FROM seg_billing_company_areas
							WHERE encounter_nr = scl.`encounter_nr`
								AND bill_areas IN ('D1','D2','D3', 'D4')),(SELECT 
						    IFNULL(SUM(trans_amount), 0) 
					    FROM
					      seg_company_ledger 
						  WHERE encounter_nr = scl.`encounter_nr` 
						    AND trans_type = 'CHARGE' 
						    AND trans_source = 'DOC' 
						   AND (
						      comp_bill_nr IS NULL 
						      OR comp_bill_nr IN 
						      (SELECT 
						        comp_bill_nr 
						      FROM
						        seg_company_billing_h 
						      WHERE is_deleted = 1)
						    ))) AS PF,
						  IFNULL(
					  (SELECT 
						      GROUP_CONCAT(cie.description) 
					  FROM
					    care_encounter_diagnosis ced 
						      LEFT JOIN care_icd10_en cie 
					      ON ced.code = cie.diagnosis_code 
						    WHERE ced.encounter_nr = ce.encounter_nr 
						      AND ced.status <> 'deleted'),
						    ''
						  ) AS diagnosis 
					FROM
						  seg_company_ledger scl 
						  LEFT JOIN care_encounter ce 
						    ON ce.`encounter_nr` = scl.`encounter_nr` 
						WHERE scl.`encounter_nr` IN (
						    $case_nrs
						  ) 
						  AND trans_type = 'CHARGE' 
						GROUP BY scl.`encounter_nr` ";

		$this->result=$db->Execute($this->sql);
        if($this->result){
            return $this->result;
        }
        else return false;
	}

	function deleteChargetoCompanyTransaction($refno, $trans_source){
		global $db;
		
		$this->sql= "DELETE FROM ".$this->tb_ledger." WHERE trans_refno=".$db->qstr($refno).
						" AND trans_source=".$db->qstr($trans_source);
		if($db->Execute($this->sql)){
			return true;
		}else{
			return false;
		}
	}

	function identifyCostCenterTableColumns($trans_source){
		switch($trans_source){
			case 'LAB':
				$this->costcenter_table = "seg_lab_servdetails";
				$this->costcenter_refnoColumn = "refno";
				$this->costcenter_chargeColumn = "price_cash";
				$this->costcenter_statusColumn = " AND status != 'deleted'";
				break;
			case 'RAD':
				$this->costcenter_table = "care_test_request_radio";
				$this->costcenter_refnoColumn = "refno";
				$this->costcenter_chargeColumn = "price_cash";
				$this->costcenter_statusColumn = " AND status != 'deleted'";
				break;
			case 'PHA':
				$this->costcenter_table = "seg_pharma_orders";
				$this->costcenter_refnoColumn = "refno";
				$this->costcenter_chargeColumn = "amount_due";
				$this->costcenter_statusColumn = "";
				break;
			case 'MSC':
				$this->costcenter_table = "seg_misc_service_details";
				$this->costcenter_refnoColumn = "refno";
				$this->costcenter_chargeColumn = "chrg_amnt * quantity";
				$this->costcenter_statusColumn = "";
				break;
			}	
	}

	function selectExistingRefNo($trans_refno, $trans_source){
		global $db;
		$this->sql = "SELECT encounter_nr FROM ".$this->tb_ledger." WHERE trans_refno=".$db->qstr($trans_refno).
						" AND trans_source=".$db->qstr($trans_source);

		if($this->result=$db->Execute($this->sql)){
			if($row=$this->result->FetchRow()){
				return $row['encounter_nr'];
			}
		}else{
			return false;
		}
	}

	function saveChargetoCompanyTransaction(&$data){
		global $db;
		extract($data);
			
			switch($trans_type){
				case 'CHARGE':
					$this->identifyCostCenterTableColumns($trans_source);
					$trans_amount=$this->calculateSumCostCentersItems($refno);
					break;
				case 'CRMEMO':
					$trans_amount = 0 - $trans_amount;
					break;
			}

			if($trans_amount){
				if(!$this->selectExistingRefNo($refno, $trans_source)){
					// if($grant=="company"){
						$this->sql= "INSERT INTO ".$this->tb_ledger." (
									  post_id,
									  post_dt,
									  comp_id,
									  encounter_nr,
									  trans_type,
									  trans_source,
									  trans_refno,
									  trans_refdt,
									  trans_amount
									) 
									  	SELECT uuid(),
									    NOW(), ".
									    $db->qstr($comp_id).",".
									    $db->qstr($encounter_nr).",".
									    $db->qstr($trans_type).",".
									    $db->qstr($trans_source).",".
									    $db->qstr($refno).",".
									    "NOW(),".
									    $db->qstr($trans_amount)."
									 ";
					// }
				}else{
					// if($grant=="company"){
						$this->sql= "UPDATE $this->tb_ledger set comp_id=".$db->qstr($comp_id).", trans_amount=".$db->qstr($trans_amount). 
									"WHERE encounter_nr=".$db->qstr($encounter_nr)."AND trans_refno=".$db->qstr($refno).
									" AND trans_source=".$db->qstr($trans_source);
					// }else{
					// 	if($this->deleteChargetoCompanyTransaction($refno, $trans_source)){
					// 		return true;
					// 	}
					// }
				}
				
				if($db->Execute($this->sql)){
			    	return true;
			    }else{
			    	return false;
			   	}
			}else{
				return false;
			}
	}

	function hasChargeToCompany($enc_nr, $trans_source, $refno){
		global $db;

		$this->sql ="SELECT trans_amount FROM ".$this->tb_ledger."  WHERE encounter_nr=".$db->qstr($enc_nr).
						" AND trans_source=".$db->qstr($trans_source)." AND trans_refno=".$db->qstr($refno);

		if($this->result=$db->Execute($this->sql)){
			if($row=$this->result->Fetchrow()){
				return $row['trans_amount'];
			}
		}else{
			return false;
		}
	}

	function getchargeBalance($enc_nr, $trans_source='', $refno=''){
		global $db;
		
		$enc_nr = $db->qstr($enc_nr);
		$trans_source = $db->qstr($trans_source);
		$trans_refno = $db->qstr($refno);

		$this->sql = "SELECT 
					  (
					    CASE
					      WHEN (
					        allotment_limit IS NULL 
					        OR allotment_limit = 0
					      ) 
					      THEN 'NO LIMIT' 
					      ELSE (
					        allotment_limit - (
					          (SELECT 
					            (
					              CASE
					                WHEN SUM(trans_amount) IS NULL 
					                THEN 0 
					                ELSE SUM(trans_amount) 
					              END
					            ) AS SUM
					          FROM ".
					            $this->tb_ledger
					          ." WHERE encounter_nr = ".$enc_nr 
					            ." AND trans_type = 'CHARGE' 
					            AND post_id NOT IN 
					            (SELECT 
					              post_id 
					            FROM ".
					             $this->tb_ledger  
					            ." WHERE encounter_nr = ".$enc_nr 
					              ." AND trans_source = ".$trans_source 
					              ." AND trans_refno = ".$trans_refno.")) + 
					          (SELECT 
					            CASE
					              WHEN (SUM(trans_amount) IS NULL) 
					              THEN 0 
					              ELSE SUM(trans_amount) 
					            END 
					          FROM ".
					           $this->tb_ledger  
					          ." WHERE trans_type = 'CRMEMO' 
					            AND encounter_nr = ".$enc_nr.")
					        )
					      ) 
					    END
					  ) AS allotment_limit 
					FROM ".
					 $this->tb_encounter_company 
					." WHERE encounter_nr = ".$enc_nr;

		if($this->result = $db->Execute($this->sql)){
			if($row = $this->result->FetchRow()){
				return $row['allotment_limit'];
			}
		}

		return false;
	}

	function forCostCenterInfo($enc_nr){
		global $db;
		$this->sql ="SELECT sca.comp_id, sc.comp_name FROM ".$this->tb_encounter_company." sca 
						LEFT JOIN ".$this->tb_company." sc ON sc.comp_id = sca.comp_id
						 WHERE sca.encounter_nr = ".$db->qstr($enc_nr);

		if($this->result=$db->Execute($this->sql)){
			if ($this->result->RecordCount()) {
				return $this->result;
			}
		}else{
			return false;
		}
	}

	function removeCharge($enc_nr){
		global $db;
		$this->sql = "DELETE FROM ".$this->tb_encounter_company." WHERE encounter_nr = ".$db->qstr($enc_nr);
		if($db->Execute($this->sql)){
			return true;
		}else{
			return false;
		}
	}

    function selectCompany($enc_nr){
    	global $db;
    	$this->sql = "SELECT comp_id FROM ".$this->tb_encounter_company." WHERE encounter_nr = ".$enc_nr;
   		
    	if($this->result = $db->Execute($this->sql)){
    		if($row=$this->result->FetchRow()){
     		return $row['comp_id'];
    		}
    	}

    	return false;
    }

    function getCompanyName($enc_nr){
    	global $db;
    	$comp_id = $this->selectCompany($enc_nr);
    	
    	if($comp_id){
    		$this->sql = "SELECT comp_full_name FROM ".$this->tb_company ." WHERE comp_id = ".$db->qstr($comp_id);
    	
	    	if($this->result = $db->Execute($this->sql)){
	    		if($row=$this->result->FetchRow()){
    				return $row['comp_full_name'];
    			}
    		}
	    }
    	
    	return false;
    }

    //added by EJ 08/29/2014 
    function getCompanyFullName($comp_id) {
		global $db;

		$comp_id = $db->qstr($comp_id);
		$this->sql = "SELECT comp_full_name FROM seg_company WHERE comp_id = $comp_id";

		if($this->result=$db->Execute($this->sql)) {
			if($row=$this->result->FetchRow()){
    				return $row['comp_full_name'];
    			}
		} else { return false; }
	}

	//added by EJ 09/02/2014 
    function getSignatoryData($position) {
		global $db;

		$position = $db->qstr($position);
		$this->sql = "SELECT 
					  fn_get_person_name (cp.pid) AS name,
					  sg.`signatory_position` AS position
					FROM
					  seg_signatory sg 
					  LEFT JOIN care_personell AS cp 
					    ON sg.personell_nr = cp.pid 
					WHERE signatory_position = $position";

		$this->result=$db->Execute($this->sql);
        if($this->result){
            return $this->result;
        }
        else return false;
	}

    function saveChargeCompany($params){
    	global $db, $HTTP_SESSION_VARS;
    	$enc_nr = $db->qstr(@$params['encounter_nr']);
    	$comp_id = $db->qstr(@$params['comp_id']);
    	$max_amount = $db->qstr(str_replace(",","",@$params['max_amount']));
    	$remarks = $db->qstr(@$params['remarks']);
    	$userid = $db->qstr($_SESSION['sess_temp_userid']);

    	if($this->selectCompany($enc_nr)){
    		$this->sql = "UPDATE ".
						  	$this->tb_encounter_company 
						." SET
							  comp_id =".$comp_id.",".
							  "allotment_limit = ".$max_amount.",".
							  "remarks = ".$remarks.",".
							  "modify_id = ".$userid.",".
							  "modify_dt =NOW() 
						WHERE encounter_nr = ".$enc_nr;
    	}

    	else{
    		$this->sql = "INSERT INTO ".$this->tb_encounter_company." (
						  encounter_nr,
						  comp_id,
						  allotment_limit,
						  remarks,
						  create_id,
						  create_dt
						) 
						VALUES
						 (".
						    $enc_nr.",".
						    $comp_id.",".
						    $max_amount.",".
						    $remarks.",".
						    $userid.",".
						    "NOW()
						  )";
    	}

    	if($db->Execute($this->sql)){
    		return true;
    	}else{
    		return false;
    	}
    }

	function getPatientCompanyInfo($enc_nr, $comp_id){
	global $db;

		$this->sql ="SELECT comp_id, encounter_nr, allotment_limit, remarks FROM ".$this->tb_encounter_company."
					  WHERE encounter_nr=".$db->qstr($enc_nr)." AND comp_id=".$db->qstr($comp_id);
		if ($this->result=$db->Execute($this->sql)) {
			if($this->count=$this->result->RecordCount()){
				 return $this->result->FetchRow();
			}
		}
		
		return FALSE;
	}
	
	function countSearchSelect($searchkey='',$maxcount=100,$offset=0) {
		global $db, $sql_LIKE, $root_path, $date_format;
		if(empty($maxcount)) $maxcount=100;
		if(empty($offset)) $offset=0;

		$searchkey=strtr($searchkey,'*?','%_');
		$searchkey=trim($searchkey);

		$searchkey = str_replace("^","'",$searchkey);
		$keyword=addslashes($searchkey);

		$this->sql = "SELECT comp_id, comp_full_name, comp_name FROM ".$this->tb_company."
						 WHERE (comp_full_name LIKE '".$keyword."%'
						 OR comp_name LIKE '".$keyword."%')
						 ORDER BY comp_name";

		if ($this->result=$db->Execute($this->sql)) {
			if ($this->count=$this->result->RecordCount()) {
				return $this->result;
			}
			else{return FALSE;}
		}else{return FALSE;}
	}

	function SearchSelect($searchkey='',$maxcount=100,$offset=0){
		global $db, $sql_LIKE, $root_path, $date_format;
		if(empty($maxcount)) $maxcount=100;
		if(empty($offset)) $offset=0;

		$searchkey=strtr($searchkey,'*?','%_');
		$searchkey=trim($searchkey);
	
		$searchkey = str_replace("^","'",$searchkey);
		$keyword=addslashes($searchkey);

		$this->sql = "SELECT comp_id, comp_full_name, comp_name, comp_add FROM ".$this->tb_company."
						WHERE (comp_full_name LIKE '".$keyword."%'
						OR comp_name LIKE '".$keyword."%')
						ORDER BY comp_name";

		if($this->res['ssl']=$db->SelectLimit($this->sql,$maxcount,$offset)){
			if($this->rec_count=$this->res['ssl']->RecordCount()) {
				return $this->res['ssl'];
			}else{return false;}
		}else{return false;}
	}


    function getOutstandingCompanyAccounts($filters, $offset=0, $rowcount=15, $bbilled=false)
    {
        global $db;

        if (!$offset) $offset = 0;
        if (!$rowcount) $rowcount = 15;

        $comFilters = array();

        if (is_array($filters)) {
            foreach ($filters as $i=>$v) {
                $db->qstr($v);
                switch (strtolower($i)) {
                    case 'datetoday':
                        $comFilters[] = "DATE(ce.`discharge_date`)<= DATE(NOW())";
                        break;
                    case 'date':
                        $comFilters[] = "DATE(ce.`discharge_date`)<= DATE($v)";
                        break;
                    case 'account_name':
                        $comFilters[] = "sc.`comp_name` LIKE '$v%'";
                        break;
                    case 'account_no':
                        $comFilters[] = "sc.`comp_id` = $v";
                        break;
                }
            }
        }

        if (empty($comFilters)) $comFilters = "DATE(ce.`discharge_date`)<= DATE(NOW())";

        $Where=implode(")\n AND (",$comFilters);
        if ($Where) $Where = "($Where)";

        if(!$bbilled){
            $this->sql = "SELECT
                          COUNT(DISTINCT (ce.`encounter_nr`)) unbilled,
                          sc.`comp_id`,
                          sc.`comp_name`,
                          sc.`comp_full_name`
                        FROM
                          care_encounter ce
                          INNER JOIN `seg_company_ledger` scl
                            ON ce.`encounter_nr` = scl.`encounter_nr`
                            AND scl.`trans_type` = 'CHARGE'
                          INNER JOIN `seg_company` sc
                            ON sc.`comp_id` = scl.`comp_id`
                        WHERE ce.`is_discharged`
                          AND NOT EXISTS
                          (SELECT
                            *
                          FROM
                            `seg_company_billing_d` scbd
                            INNER JOIN `seg_company_billing_h` scbh
                              ON scbd.`comp_bill_nr` = scbh.`comp_bill_nr`
                              AND scbh.`is_deleted` <> '1'
                          WHERE scbd.`encounter_nr` = ce.`encounter_nr`
                            AND scbd.`is_deleted` <> '1')
                           AND {$Where}
                        GROUP BY scl.`comp_id` LIMIT $offset, $rowcount";
        }else{
            $this->sql = "SELECT
                              COUNT(DISTINCT (ce.`encounter_nr`)) billed,
                              scbh.comp_bill_nr,
                              sc.`comp_id`,
                              sc.`comp_name`,
                              sc.`comp_full_name`,
                              (SELECT or_no FROM seg_pay_request
                               WHERE service_code = scbh.comp_bill_nr AND ref_source = 'COM') flag
                            FROM
                              care_encounter ce
                              INNER JOIN `seg_company_billing_d` scbd
                                ON ce.encounter_nr = scbd.encounter_nr
                              INNER JOIN seg_company_billing_h scbh
                                ON scbd.comp_bill_nr = scbh.comp_bill_nr
                                AND scbh.is_deleted <> '1'
                              inner join `seg_company` sc
                                ON sc.`comp_id` = scbh.`comp_id`
                            WHERE {$Where}
                            GROUP BY sc.`comp_id`,scbh.comp_bill_nr LIMIT $offset, $rowcount ";
        }

        #echo $this->sql;
        $this->result=$db->Execute($this->sql);
        if($this->result){
            return $this->result;
        }
        else return false;
    }


    function getUnbilledEmployees($comp_id, $date)
    {
        global $db;
        $data = array($date,$comp_id);

        $this->sql = $db->Prepare("SELECT
                                      ce.`discharge_date`,
                                      ce.`encounter_nr`,
                                      `fn_get_person_name`(ce.pid) `name`,
                                      sum(ifnull(sbca.`amount`,0)) `amount`
                                    FROM
                                      care_encounter ce
                                      INNER JOIN `seg_billing_company_areas` sbca
                                        ON ce.`encounter_nr` = sbca.`encounter_nr`
                                    WHERE ce.`is_discharged`
                                      AND NOT EXISTS
                                      (SELECT
                                        *
                                      FROM
                                        `seg_company_billing_d` scbd
                                        INNER JOIN `seg_company_billing_h` scbh
                                          ON scbd.`comp_bill_nr` = scbh.`comp_bill_nr`
                                          AND scbh.`is_deleted` <> '1'
                                      WHERE scbd.`encounter_nr` = ce.`encounter_nr`
                                        AND scbd.`is_deleted` <> '1')
                                      AND (
                                        DATE(ce.`discharge_date`) <= DATE(?)
                                      ) AND (scl.comp_id = ?)
                                    GROUP BY ce.`encounter_nr` ");
		
		$this->result=$db->Execute($this->sql,$data);
        if($this->result){
        	return $this->result;
        }
        else{

	        $this->sql = $db->Prepare("SELECT
	                                      ce.`discharge_date`,
	                                      ce.`encounter_nr`,
	                                      `fn_get_person_name`(ce.pid) `name`,
	                                      sum(ifnull(scl.`trans_amount`,0)) `amount`
	                                    FROM
	                                      care_encounter ce
	                                      INNER JOIN `seg_company_ledger` scl
	                                        ON ce.`encounter_nr` = scl.`encounter_nr`
	                                        AND scl.`trans_type` = 'CHARGE'
	                                    WHERE ce.`is_discharged`
	                                      AND NOT EXISTS
	                                      (SELECT
	                                        *
	                                      FROM
	                                        `seg_company_billing_d` scbd
	                                        INNER JOIN `seg_company_billing_h` scbh
	                                          ON scbd.`comp_bill_nr` = scbh.`comp_bill_nr`
	                                          AND scbh.`is_deleted` <> '1'
	                                      WHERE scbd.`encounter_nr` = ce.`encounter_nr`
	                                        AND scbd.`is_deleted` <> '1')
	                                      AND (
	                                        DATE(ce.`discharge_date`) <= DATE(?)
	                                      ) AND (scl.comp_id = ?)
	                                    GROUP BY scl.`encounter_nr` ");

	        $this->result=$db->Execute($this->sql,$data);
	        if($this->result){
	            return $this->result;
	        }
	        else return false;
	    }
    }


    function getbilledEmployees($billnr)
    {
        global $db;
        $this->sql = $db->Prepare(" SELECT
                                      ce.`discharge_date`,
                                      ce.`encounter_nr`,
                                      `fn_get_person_name` (ce.pid) `name`,
                                      scbh.discount,
                                      scbd.`bill_amount` as amount,
                                      (SELECT or_no FROM seg_pay_request
                                          WHERE service_code = scbh.comp_bill_nr AND ref_source = 'COM') flag
                                    FROM
                                      care_encounter ce
                                      INNER JOIN `seg_company_billing_d` scbd
                                        ON ce.encounter_nr = scbd.encounter_nr
                                      INNER JOIN seg_company_billing_h scbh
                                        ON scbd.comp_bill_nr = scbh.comp_bill_nr
                                    WHERE scbh.comp_bill_nr = ?
                                    GROUP BY ce.`encounter_nr` ");
        //echo $this->sql
        $this->result=$db->Execute($this->sql,$billnr);
        if($this->result){
            return $this->result;
        }
        else return false;
    }


    function getCompanyDetails($comp_id)
    {
        global $db;
        $this->sql =$db->Prepare("SELECT
                                      sc.*
                                    FROM
                                      `seg_company` sc
                                    WHERE sc.`comp_id` = ?  ");

        $this->result=$db->Execute($this->sql,$comp_id);
        if($this->result){
            return $this->result;
        }
        else return false;
    }

    function getNewBillingNr()
    {
        global $db;

        $s_bill_nr = "";

        $this->sql = $db->Prepare("select fn_get_new_company_billing_nr() as bill_nr");
        if ($result = $db->Execute($this->sql)) {
            if ($result->RecordCount()) {
                while ($row = $result->FetchRow())
                    $s_bill_nr = $row['bill_nr'];
            }
        }

        return($s_bill_nr);
    }


    function saveBillingHeader(&$data)
    {
        global $db;
        extract($data);
        $details=array($billnr, $billdate, $comp_id, $discount, $_SESSION['sess_temp_userid'], 'NOW()');
        $this->sql = $db->Prepare("INSERT INTO $this->tb_billing_h
                                    (comp_bill_nr,
                                     bill_date,
                                     comp_id,
                                     discount,
                                     create_id,
                                     create_dt)
                                   VALUES
                                    (?,?,?,?,?,?)");

        if($db->Execute($this->sql,$details)){
            return true;
        }else{
            return false;
        }
    }


    function updateBillingHeader($data)
    {
        global $db;
        extract($data);
        $details=array($billdate, $comp_id, $discount, $_SESSION['sess_temp_userid'],$billnr);
        $this->sql = $db->Prepare("UPDATE $this->tb_billing_h
                                    SET bill_date = ?,
                                     comp_id = ?,
                                     discount = ?,
                                     modify_id = ?
                                   WHERE comp_bill_nr = ?");

        if($db->Execute($this->sql,$details)){
            return true;
        }else{
            return false;
        }

    }

    function saveBillingDetails($details)
    {
        global $db;
        $this->sql = "INSERT INTO $this->tb_billing_d
                        (id,
                         comp_bill_nr,
                         encounter_nr,
                         bill_amount)
                       VALUES $details";

        if($db->Execute($this->sql)){
            return true;
        }else{
            return false;
        }
    }


    function clearBillingDetails($bill_nr)
    {
        global $db;
        $this->sql = "DELETE FROM $this->tb_billing_d WHERE comp_bill_nr =".$db->qstr($bill_nr);

        if($db->Execute($this->sql)){
            return true;
        }else{
            return false;
        }
    }

    function setFlagLedger($comp_id,$enc,$bill_nr)
    {
        global $db;
        $this->sql = "UPDATE $this->tb_ledger
                        SET comp_bill_nr =".$db->qstr($bill_nr)."
                      WHERE comp_id =".$db->qstr($comp_id)."
                        AND encounter_nr=".$db->qstr($enc);

        if($db->Execute($this->sql)){
            return true;
        }else{
            return false;
        }
    }


    function deleteBill($billnr)
    {
        global $db;
        $this->sql = "UPDATE $this->tb_billing_h  SET is_deleted = '1' WHERE comp_bill_nr = ".$db->qstr($billnr);

        if($db->Execute($this->sql)){
            return true;
        }else{
            return false;
        }
    }


    function getOtherCompanyDetails($encounter,$area,$dr_nr=0){
        global $db;
        $data = array($encounter, $area, $dr_nr);

        $this->sql = $db->Prepare("SELECT amount
                FROM seg_billing_company_areas
                WHERE encounter_nr =  ?
                AND bill_areas = ?
                AND dr_nr = ?");

        if($result=$db->Execute($this->sql,$data)) {
            return $result;
        } else { return false; }
    }


    function getCompanyDetailsByEnc($enc)
    {
        global $db;
        $this->sql =$db->Prepare("SELECT
                                      sc.`comp_id`,
                                      sc.`comp_full_name`,
                                      sc.`comp_name`,
                                      sca.`allotment_limit` amount_limit
                                    FROM
                                      `seg_company_allotment` sca
                                      INNER JOIN `seg_company` sc
                                      ON sc.`comp_id` = sca.`comp_id`
                                    WHERE sca.`encounter_nr` = ?");

        $this->result=$db->Execute($this->sql,$enc);
        if($this->result){
            return $this->result;
        }
        else return false;
    }


    function saveComDetails($details)
    {
        global $db;
        $this->sql = "INSERT INTO seg_billing_company_areas
                        (encounter_nr,
                         bill_areas,
                         dr_nr,
                         amount)
                       VALUES $details";

        if($db->Execute($this->sql)){
            return true;
        }else{
            return false;
        }
    }

    function deleteComDetails($enc,$area)
    {
        global $db;
        if($area=='hci'){
            $bill_area = " bill_areas NOT IN ('D1','D2','D3','D4') ";
        }else{
            $bill_area = " bill_areas IN ('D1','D2','D3','D4') ";
        }

        $this->sql = $db->Prepare("DELETE FROM seg_billing_company_areas WHERE $bill_area AND encounter_nr = ? ");

        if($db->Execute($this->sql,$enc)){
            return true;
        }else{
            return false;
        }
    }

    function saveCompanyDetails($details)
    {
        global $db;
        $this->sql= "INSERT INTO seg_company_ledger (
                                      post_id,
									  post_dt,
									  comp_id,
									  encounter_nr,
									  trans_type,
									  trans_source,
									  trans_refno,
									  trans_refdt,
									  trans_amount
									)VALUES $details";

        if($db->Execute($this->sql)){
            return true;
        }else{
            return false;
        }
    }

    function deleteCompanyLedgerDetails($encounter)
    {
        global $db;
        $this->sql = "DELETE FROM seg_company_ledger WHERE trans_type ='CHARGE' AND comp_bill_nr IS NULL AND encounter_nr=".$db->qstr($encounter);
        if($db->Execute($this->sql)){
            return true;
        }else{
            return false;
        }
    }

   function paidBill($billnr){
    	global $db;

    	$this->sql = "UPDATE seg_company_billing_h SET is_paid_employee = 'paid' WHERE comp_bill_nr = ".$db->qstr($billnr);

    	if($db->Execute($this->sql)){
    		return true;
    	}

    	return false;
    }

    function CheckHasCompany($encounter_nr){
    	global $db;
    	$this->sql = "SELECT b.`comp_full_name`
    				FROM ".$this->tb_encounter_company." AS a
    				INNER JOIN ".$this->tb_company." AS b
    				ON a.`comp_id` = b.`comp_id`
    				WHERE a.`encounter_nr` = ".$db->qstr($encounter_nr)."
    				LIMIT 1";
    	if($result = $db->Execute($this->sql)){
    		while ($row = $result->FetchRow()){
    			if($row['comp_full_name']){
    				return $row['comp_full_name'];
    			}else{
    				return false;
    			}
    		}
    	}else{
    		return false;
    	}
    }

    function CheckHasCompanyrefno($ref){
    	global $db;
    	$this->sql = "SELECT b.`comp_full_name`
    					FROM ".$this->tb_encounter_company." AS a
    					LEFT JOIN ".$this->tb_company." AS b
    					ON a.`comp_id` = b.`comp_id`
    					LEFT JOIN `seg_pharma_orders` c
						ON a.`encounter_nr` = c.`encounter_nr`
						WHERE c.`refno` = ".$ref."
						LIMIT 1";
		if($result = $db->Execute($this->sql)){
    		while ($row = $result->FetchRow()){
    			if($row['comp_full_name']){
    				return $row['comp_full_name'];
    			}else{
    				return false;
    			}
    		}
    	}else{
    		return false;
    	}

    }

   function CheckHasCompanyPid($pid){
   		global $db;
   		    	$this->sql = "SELECT b.`comp_full_name`
    					FROM ".$this->tb_encounter_company." AS a
    					LEFT JOIN ".$this->tb_company." AS b
    					ON a.`comp_id` = b.`comp_id`
    					LEFT JOIN `care_encounter` c
						ON a.`encounter_nr` = c.`encounter_nr`
						WHERE c.`pid` = ".$pid."
						LIMIT 1";
		if($result = $db->Execute($this->sql)){
    		while ($row = $result->FetchRow()){
    			if($row['comp_full_name']){
    				return $row['comp_full_name'];
    			}else{
    				return false;
    			}
    		}
    	}else{
    		return false;
    	}
   }

   function ClearCompanyCoverage($enc){
   		global $db;

   		$this->sql = "DELETE FROM ".$this->tb_bill_coverage_area."
   						WHERE encounter_nr = ".$db->qstr($enc)."
   						AND bill_areas NOT IN ('D1','D2','D3','D4')";
   		if($this->result = $db->Execute($this->sql)){
   			return TRUE;
   		}else{
   			return FALSE;
   		}
   }

   function SaveCompanyCoverage($values){
   		global $db;

   		$this->sql = "INSERT INTO ".$this->tb_bill_coverage_area."
   						(encounter_nr,bill_areas,amount)
   					VALUES ".$values;
   		if($this->result = $db->Execute($this->sql))
   			return true;
   		else
   			return false;
   }

   function GetCompanyAmount($enc, $area){
   		global $db;
   		
   		switch (strtolower($area)) {
   			case 'acc':
   				$bill_areas = "AC";
   				break;
   			case 'xlo':
   				$bill_areas = "HS";
   				break;
   			case 'meds':
   				$bill_areas = "MS";
   				break;
   			case 'or':
   				$bill_areas = "OR";
   				break;
   			case 'misc':
   				$bill_areas = "XC";
   				break;
   			default:
   				$bill_areas = "";
   				break;
   		}

   		$this->sql = "SELECT amount
   					FROM ".$this->tb_bill_coverage_area."
   					WHERE encounter_nr = ".$db->qstr($enc)."
   					AND bill_areas = ".$db->qstr($bill_areas);

   		if($this->result = $db->GetOne($this->sql))
   			return $this->result;
   		else
   			return false;
   }

   function GetAmmountHospitalCoverage($enc, $area=NULL){
   		global $db;

   		if($area == "UI"){
   			$where = "";
   		}else{
   			$where = "AND bill_areas IN ('AC','HS','MS','OR','XC')";
   		}
   		

   		$this->sql = "SELECT amount, 
   					 bill_areas
   					 FROM ".$this->tb_bill_coverage_area."
   					 WHERE encounter_nr = ".$db->qstr($enc)."
   					 ".$where;

   		if($this->result = $db->GetAll($this->sql))
   			return $this->result;
   		else
   			return false;
   }

   function GetAllAmountCoverage($enc, $area=NULL){
   		global $db;

   		if($area == "PF"){
   			$where = "AND bill_areas NOT IN ('AC','HS','MS','OR','XC')";
   		}else{
   			$where = "AND bill_areas IN ('AC','HS','MS','OR','XC')";
   		}
   		

   		$this->sql = "SELECT sum(amount)
   					FROM ".$this->tb_bill_coverage_area."
   					WHERE encounter_nr = ".$db->qstr($enc)."
   					".$where;

   		if($this->result = $db->GetOne($this->sql))
   			return $this->result;
   		else
   			return false;

   }

   #added by janken 11/24/2014 for getting the discount companies
  	function getPatientDiscountInfo($enc_nr, $disc_id){
		global $db;

		$this->sql ="SELECT discountid, encounter_nr, (hcidiscount + pfdiscount + hosp_acc + hosp_xlo
						+ hosp_meds + hosp_ops + hosp_misc + pf_discount) AS amount, discountdesc FROM seg_billingapplied_discount
					  WHERE encounter_nr=".$db->qstr($enc_nr)." AND discountid=".$db->qstr($disc_id);
		if ($this->result=$db->Execute($this->sql)) {
			if($this->count=$this->result->RecordCount()){
				 return $this->result->FetchRow();
			}
		}
			
		return FALSE;
	}

	function SearchSelectDiscount($searchkey='',$maxcount=100,$offset=0){
		global $db, $sql_LIKE, $root_path, $date_format;
		if(empty($maxcount)) $maxcount=100;
		if(empty($offset)) $offset=0;

		$searchkey=strtr($searchkey,'*?','%_');
		$searchkey=trim($searchkey);
	
		$searchkey = str_replace("^","'",$searchkey);
		$keyword=addslashes($searchkey);

		$this->sql = "SELECT discountid, discountdesc FROM seg_discount
						WHERE (discountdesc LIKE '".$keyword."%'
						OR discountdesc LIKE '".$keyword."%')
						ORDER BY discountdesc";

		if($this->res['ssl']=$db->SelectLimit($this->sql,$maxcount,$offset)){
			if($this->rec_count=$this->res['ssl']->RecordCount()) {
				return $this->res['ssl'];
			}else{return false;}
		}else{return false;}
	}

	function countSearchSelectDiscount($searchkey='',$maxcount=100,$offset=0) {
		global $db, $sql_LIKE, $root_path, $date_format;
		if(empty($maxcount)) $maxcount=100;
		if(empty($offset)) $offset=0;

		$searchkey=strtr($searchkey,'*?','%_');
		$searchkey=trim($searchkey);

		$searchkey = str_replace("^","'",$searchkey);
		$keyword=addslashes($searchkey);

		$this->sql = "SELECT discountid, discountdesc FROM seg_discount
						WHERE (discountdesc LIKE '".$keyword."%'
						OR discountdesc LIKE '".$keyword."%')
						ORDER BY discountdesc";

		if ($this->result=$db->Execute($this->sql)) {
			if ($this->count=$this->result->RecordCount()) {
				return $this->result;
			}
			else{return FALSE;}
		}else{return FALSE;}
	}
	#end by janken 

function getCompany($details){
    	global $db;

    	$where = "";
    	
    	if($details['keyword']){
    		$where[] = " comp_full_name LIKE '".$details['keyword']."%'";
    	}

    	// if($details['type']){
    	// 	$where[] = " c_type = ".$db->qstr($details['type']);
    	// }

    	if($where){
    		$where = " WHERE ".implode("AND", $where);
    	}

    	$this->sql = "SELECT comp_id, comp_full_name, comp_name, comp_add, comp_phone_nr FROM seg_company ".$where." ORDER BY comp_name";
    	
    	if($this->result = $db->Execute($this->sql)){
    		return $this->result;
    	}

    	return false;
    }

    function addCompany($details){
    	global $db;

    	$this->sql = "INSERT INTO seg_company (comp_full_name, comp_name, comp_add, comp_email_add, 
    												comp_phone_nr, comp_ceo, comp_hr, create_id, 
    												create_time, modify_id, modify_time)
						VALUES (".$db->qstr($details['comp_full_name']).","
							.$db->qstr($details['comp_name']).","
							.$db->qstr($details['comp_add']).","
							.$db->qstr($details['comp_email_add']).","
							.$db->qstr($details['comp_phone_nr']).","
							.$db->qstr($details['comp_ceo']).","
							.$db->qstr($details['comp_hr']).","
							.$db->qstr($_SESSION['sess_temp_userid']).",NOW(),"
							.$db->qstr($_SESSION['sess_temp_userid']).", NOW()"
							.")";

		if($db->Execute($this->sql)){
			return true;
		}

		return false;
    }

    function updateCompany($details){
    	global $db;

    	$this->sql = "UPDATE seg_company SET ".
    					"comp_full_name = ".$db->qstr($details['comp_full_name']).",".
    					"comp_name = ".$db->qstr($details['comp_name']).",".
    					"comp_add = ".$db->qstr($details['comp_add']).",".
    					"comp_email_add = ".$db->qstr($details['comp_email_add']).",".
    					"comp_phone_nr = ".$db->qstr($details['comp_phone_nr']).",".
    					"comp_ceo = ".$db->qstr($details['comp_ceo']).",".
    					"comp_hr = ".$db->qstr($details['comp_hr']).",".
    					"modify_time = NOW(), ".
    					"modify_id = ".$db->qstr($_SESSION['sess_temp_userid']).
    					" WHERE comp_id = ".$db->qstr($details['comp_id']);

    	if($db->Execute($this->sql)){
    		return true;
    	}

    	return false;
    }

    function getCompList($type=''){
    	global $db;
    	$where = '';

    	// if($type){
    	// 	switch($type){
    	// 		case 'person':
    	// 			$where = " WHERE c_type = 'person'";
    	// 		break;

    	// 		case 'company':
    	// 			$where = " WHERE c_type = 'company'";
    	// 		break;
    	// 	}
    	// }

    	$this->sql = "SELECT comp_id, comp_name, comp_add FROM ".$this->tb_company.$where." ORDER BY comp_name ASC";
 
    	if($this->result = $db->Execute($this->sql)){
    		return $this->result;
    	}

    	return false;
    }

    function getPatientList($type='', $comp_id=0, $date_from, $date_to, $has_fb){
    	global $db;

    	$where = "";
    	// if($type){
    	// 	switch($type){
    	// 		case 'person':
    	// 			$where .= " AND sc.c_type = 'person'";
    	// 			break;
    	// 		case 'company':
    	// 			$where .= " AND sc.c_type = 'company'";
    	// 	}
    	// }

    	if($comp_id){
    		$where .= " AND sc.comp_id = ".$db->qstr($comp_id);
    	}

    	if($has_fb == 'true'){
    		$where .= " AND sbe.is_final = 1";
    	}else{
    		$where .= " AND (sbe.is_final <> 1 OR sbe.is_final IS NULL)";
    	}

    	$this->sql = "SELECT 
					  ce.encounter_date,
					  ce.encounter_nr,
					  fn_get_person_lastname_first (ce.pid) AS name,
					  IF(ce.encounter_type = 2, 'OPD', 'IPD') AS encounter_type,
					  sc.comp_full_name as comp_name,
					  IFNULL(sbe.is_final, 0) is_final 
					FROM
					  seg_company_allotment sca 
					  LEFT JOIN care_encounter ce 
					    ON ce.encounter_nr = sca.encounter_nr 
					  LEFT JOIN seg_company sc 
					    ON sc.comp_id = sca.comp_id 
					  LEFT JOIN seg_billing_encounter sbe 
					    ON (
					      sbe.encounter_nr = sca.encounter_nr 
					      AND (
					        sbe.is_deleted IS NULL 
					        OR sbe.is_deleted <> 1
					      )
					    ) 
					  WHERE DATE(ce.encounter_date) BETWEEN DATE(".$db->qstr($date_from).") 
					  			AND DATE(".$db->qstr($date_to).") ".$where.
					  			" ORDER BY DATE(ce.encounter_date),
					  				fn_get_person_lastname_first (ce.pid) ASC ";
					  			
		if($this->result = $db->Execute($this->sql)){
			return $this->result;
		}

		return false;
    }
}
