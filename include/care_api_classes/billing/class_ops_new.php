<?php
/*
 * Class for updating  `seg_ops_serv`, `seg_ops_servdetails`, and `seg_ops_personell` tables
 * Retrieves data from `care_encounter_op` table.
 * Created by Francis 11-29-13
 * Created for Billing_new
 *
 */
require('./roots.php');
require_once($root_path."include/care_api_classes/class_hospital_admin.php");
require_once($root_path.'include/care_api_classes/class_core.php');
require_once($root_path.'include/care_api_classes/billing/class_billing_new.php');
require_once($root_path.'include/care_api_classes/billing/class_bill_info.php');

define('HOUSE_CASE_PCF', 40);

class SegOps extends Core{


		/**
		* Database table for the requested operation.
		*    - includes refno, encounter
		* @var string
		*/
		var $tb_ops_serv='seg_ops_serv';

		/*
		 * Database table for the details of the operation.
		 *    - includes ops_code, rvu, multiplier
		 * @var string
		 */
		var $tb_ops_servdetails = 'seg_ops_servdetails';

		/*
		 * Database table for the personnel involve in a paticular operation.
		 *    - includes surgeons, assistant surgeons, scrub nurses, rotating nurses
		 * @var string
		 */
		var $tb_ops_personell = 'seg_ops_personell';

		/**
		* Database table for the operation requests.
		* @var string
		*/
		var $tb_encounter_op='care_encounter_op';
		/**
		* SQL query result. Resulting ADODB record object.
		* @var object
		*/
		
		/*--Delete procedures/ICP--*/
		function delProcedure($encounter, $bill_dt, $bill_frmdte, $op_code) {
			global $db;

			$bSuccess = false;

			$strSQL = "select * from seg_misc_ops_details ".
								"   where ops_code = '".$op_code."' and exists (select * from seg_misc_ops as smo where smo.refno = seg_misc_ops_details.refno ".
								"      and smo.encounter_nr in $encounter and smo.chrge_dte >= '".$bill_frmdte."') ".
					      "      and not EXISTS(SELECT * FROM seg_ops_chrgd_accommodation AS soca WHERE soca.ops_refno = seg_misc_ops_details.refno AND
											 soca.ops_entryno = seg_misc_ops_details.entry_no AND soca.ops_code = seg_misc_ops_details.ops_code)
						 and not EXISTS(SELECT * FROM seg_ops_chrg_dr AS socd WHERE socd.ops_refno = seg_misc_ops_details.refno AND
											 socd.ops_entryno = seg_misc_ops_details.entry_no AND socd.ops_code = seg_misc_ops_details.ops_code) ".
								"      and get_lock('smops_lock', 10) ".
								"   order by entry_no desc limit 1";

			    $rs = $db->Execute($strSQL);
			    if ($rs) {
					$db->StartTrans();
					$row = $rs->FetchRow();
					if ($row) {
						$refno = $row['refno'];
						$entryno = $row['entry_no'];

						$strSQL = "delete from seg_misc_ops_details where refno = '$refno' and entry_no = $entryno and ops_code = '$op_code'";
						$bSuccess = $db->Execute($strSQL);

						$strSQL = "select RELEASE_LOCK('smops_lock')";
						$db->Execute($strSQL);

						if ($bSuccess) {
								// Delete this header if already without details ...
								$dcount = 0;
								$strSQL = "select count(*) dcount from seg_misc_ops_details where refno = '$refno'";
		 					  $rs = $db->Execute($strSQL);
		 					  if ($rs) {
									$row = $rs->FetchRow();
									$dcount = ($row) ? $row['dcount'] : 0;
									if ($dcount == 0) {
											$strSQL = "delete from seg_misc_ops where refno = '$refno'";
											$bSuccess = $db->Execute($strSQL);
									}
		 					  }
						}


						if($bSuccess) {
							$db->CompleteTrans();
							return TRUE;

						}else{
							$db->FailTrans();
							return FALSE;
						}
					}
	 	 			}else{ return FALSE;};
		}//end of delProcedure function

		// Added by James 1/6/2014
		// function delProcedure($encounter, $refno, $op_code) {
		// 	global $db;

		// 	$sql = "DELETE FROM seg_misc_ops WHERE refno='".$refno."'";
		// 	$sql2 = "DELETE FROM seg_misc_ops_details WHERE refno='".$refno."' AND ops_code='".$op_code."'";
	
		// 	//$rs = $db->Execute($sql);
		// 	$rs2 = $db->Execute($sql2);

		// 	if($rs && $rs2){
		// 		return TRUE;
		// 	}else{
		// 		return FALSE;
		// 	}
		// }


		/*-----------------Add Procedures/ICP-------------------------------*/
		//added by Francis 11-27-2013
		function addProcedure($procData) {
			global $db;
			extract($procData);
			$bSuccess = true;

			if($encNr != ''){

				$db->StartTrans();

				$refno = $this->getMiscOpRefNo($billDate, $encNr);

				if ($refno == '') {
					$strSQL = "insert into seg_misc_ops (chrge_dte, encounter_nr, modify_id, create_id, create_dt) ".
										"   values ('".$billDate."', '".$encNr."', '".$user."', '".$user."', ".
										"          '".$billDate."')";
					if ($db->Execute($strSQL))
							$refno = $this->getMiscOpRefNo($billDate, $encNr);
					else
							$bSuccess = false;
				}

				if($bSuccess){
					$op_charge = str_replace(",", "", $charge);
					$strSQL = "insert into seg_misc_ops_details (refno, ops_code, rvu, multiplier, chrg_amnt, op_date, laterality, num_sessions, special_dates, description, checkup_date1, checkup_date2, checkup_date3, checkup_date4, filter_card_no, registry_card_no, nhst_result) ".
								"   values ('".$refno."', '".$code."', ".$rvu.", ".$multiplier.", ".$op_charge.", '".$opDate."', '".$laterality."', '".$num_sessions."', '".$special_dates."', ".$db->qstr($desc).",
								  '".$checkup_date1."', '".$checkup_date2."', '".$checkup_date3."', '".$checkup_date4."', '".$filter_card_no."', '".$registry_card_no."', '".$nhst_result."')";
					$bSuccess = $db->Execute($strSQL);
				}

				if($bSuccess) {
					$db->CompleteTrans();
					return TRUE;
				}else{
					$db->FailTrans();
					return FALSE;
				}

			}else{return FALSE;}			

		}
		/*---------end-----Add Procedures/ICP-----------end-----------------*/

		/*------------------------Get Procedure Refno-------------------------*/
		//added by Francis 11-27-2013
		function getMiscOpRefNo($bill_frmdte, $enc_nr){
		global $db;

			$srefno = '';
			$strSQL = "select refno ".
								"   from seg_misc_ops ".
								"   where str_to_date(chrge_dte, '%Y-%m-%d %H:%i:%s') >= '".$bill_frmdte."' ".
								"      and encounter_nr = '".$enc_nr."' ".
								"   order by chrge_dte limit 1";

			if ($result = $db->Execute($strSQL)) {
					if ($result->RecordCount()) {
							while ($row = $result->FetchRow())
									$srefno = $row['refno'];
					}
			}

			return($srefno);
		}
		/*----------end-----------Get Procedure Refno------------end----------*/



		function SearchCurrentOP($enc_nr, $bill_frmdte, $bill_dt,$maxcount=100,$offset=0, $b_all = false){
				global $db;

				if(empty($maxcount)) $maxcount=100;
				if(empty($offset)) $offset=0;

		if ($b_all)
			$this->sql = "select refno, entry_no, ops_code as code, op_count, description, t.rvu, multiplier, op_charge, group_code, provider, op_date
							from
							(select od.refno, 0 as entry_no, od.ops_code, sum(od.rvu) as rvu, max(od.multiplier) as multiplier, sum(od.rvu * od.multiplier) as op_charge, group_code, 'OR' as provider,
									 (SELECT MAX(ceo.op_date) AS op_date
										FROM seg_ops_serv AS sos INNER JOIN care_encounter_op AS ceo ON sos.refno = ceo.refno
										WHERE sos.refno = os.refno) as op_date,
								 (SELECT COUNT(ops_code) AS op_count FROM seg_ops_servdetails AS od2 WHERE od2.ops_code = od.ops_code AND od2.refno = od.refno) AS op_count
								 from seg_ops_serv as os inner join seg_ops_servdetails as od on os.refno = od.refno
								 where encounter_nr = '". $enc_nr. "' and is_cash = 0 and upper(trim(os.status)) <> 'DELETED'
									and (str_to_date(concat(date_format(os.request_date, '%Y-%m-%d'), ' ', date_format(os.request_time, '%H:%i:%s')), '%Y-%m-%d %H:%i:%s') >= '". $bill_frmdte ."'
									and str_to_date(concat(date_format(os.request_date, '%Y-%m-%d'), ' ', date_format(os.request_time, '%H:%i:%s')), '%Y-%m-%d %H:%i:%s') < '". $bill_dt ."')
								 group by ops_code
							 union
							 select md.refno, md.entry_no, md.ops_code, sum(md.rvu) as rvu, max(md.multiplier) as multiplier, sum(chrg_amnt) as chrg_amnt, group_code, 'OA' as provider, md.op_date,
								(SELECT COUNT(ops_code) AS op_count FROM seg_misc_ops_details AS md2 WHERE md2.ops_code = md.ops_code AND md2.refno = md.refno) AS op_count
								from seg_misc_ops as mo inner join seg_misc_ops_details as md on mo.refno = md.refno
								where encounter_nr = '". $enc_nr. "' and (str_to_date(mo.chrge_dte, '%Y-%m-%d %H:%i:%s') >= '". $bill_frmdte ."'
									 and str_to_date(mo.chrge_dte, '%Y-%m-%d %H:%i:%s') < '". $bill_dt ."')
								group by ops_code) as t inner join seg_ops_rvs as om on t.ops_code = om.code
							order by description LIMIT $offset, $maxcount";
		else
			$this->sql = "select refno, entry_no, ops_code as code, op_count, description, max(t.rvu) as rvu, max(multiplier) as multiplier, max(op_charge) as op_charge, group_code, provider, max(op_date) as op_date,description AS alt_desc
							from
							(select od.refno, 0 as entry_no, od.ops_code, od.rvu, od.multiplier, (od.rvu * od.multiplier) as op_charge, group_code, 'OR' as provider,
									 (SELECT MAX(ceo.op_date) AS op_date
										FROM seg_ops_serv AS sos INNER JOIN care_encounter_op AS ceo ON sos.refno = ceo.refno
										WHERE sos.refno = os.refno) as op_date,
								 (SELECT COUNT(ops_code) AS op_count FROM seg_ops_servdetails AS od2 WHERE od2.ops_code = od.ops_code AND od2.refno = od.refno) AS op_count
								 from seg_ops_serv as os inner join seg_ops_servdetails as od on os.refno = od.refno
								 where encounter_nr = '". $enc_nr. "' and is_cash = 0 and upper(trim(os.status)) <> 'DELETED'
									and (str_to_date(concat(date_format(os.request_date, '%Y-%m-%d'), ' ', date_format(os.request_time, '%H:%i:%s')), '%Y-%m-%d %H:%i:%s') >= '". $bill_frmdte ."'
									and str_to_date(concat(date_format(os.request_date, '%Y-%m-%d'), ' ', date_format(os.request_time, '%H:%i:%s')), '%Y-%m-%d %H:%i:%s') < '". $bill_dt ."')
									and group_code <> ''
							 union
							 select md.refno, md.entry_no, md.ops_code, md.rvu, md.multiplier, chrg_amnt, group_code, 'OA' as provider, md.op_date,
								(SELECT COUNT(ops_code) AS op_count FROM seg_misc_ops_details AS md2 WHERE md2.ops_code = md.ops_code AND md2.refno = md.refno) AS op_count
								from seg_misc_ops as mo inner join seg_misc_ops_details as md on mo.refno = md.refno
								where encounter_nr = '". $enc_nr. "' and (str_to_date(mo.chrge_dte, '%Y-%m-%d %H:%i:%s') >= '". $bill_frmdte ."'
									 and str_to_date(mo.chrge_dte, '%Y-%m-%d %H:%i:%s') < '". $bill_dt ."') and group_code <> ''
							 order by rvu desc) as t inner join seg_ops_rvs as om on t.ops_code = om.code
							group by group_code
							union
							select t.refno, t.entry_no, t.ops_code, op_count, om.description, t.rvu, t.multiplier, op_charge, t.group_code, provider, t.op_date, smod.description AS alt_desc 
							from
							(select od.refno, 0 as entry_no, od.ops_code, sum(od.rvu) as rvu, max(od.multiplier) as multiplier, sum(od.rvu * od.multiplier) as op_charge, group_code, 'OR' as provider,
									 (SELECT MAX(ceo.op_date) AS op_date
										FROM seg_ops_serv AS sos INNER JOIN care_encounter_op AS ceo ON sos.refno = ceo.refno
										WHERE sos.refno = os.refno) as op_date,
								 (SELECT COUNT(ops_code) AS op_count FROM seg_ops_servdetails AS od2 WHERE od2.ops_code = od.ops_code AND od2.refno = od.refno) AS op_count
								 from seg_ops_serv as os inner join seg_ops_servdetails as od on os.refno = od.refno
								 where encounter_nr = '". $enc_nr. "' and is_cash = 0 and upper(trim(os.status)) <> 'DELETED'
									and (str_to_date(concat(date_format(os.request_date, '%Y-%m-%d'), ' ', date_format(os.request_time, '%H:%i:%s')), '%Y-%m-%d %H:%i:%s') >= '". $bill_frmdte ."'
									and str_to_date(concat(date_format(os.request_date, '%Y-%m-%d'), ' ', date_format(os.request_time, '%H:%i:%s')), '%Y-%m-%d %H:%i:%s') < '". $bill_dt ."')
									and group_code = ''
								 group by ops_code
							 union
							 select md.refno, md.entry_no, md.ops_code, sum(md.rvu) as rvu, max(md.multiplier) as multiplier, sum(chrg_amnt) as chrg_amnt, group_code, 'OA' as provider, md.op_date,
								(SELECT COUNT(ops_code) AS op_count FROM seg_misc_ops_details AS md2 WHERE md2.ops_code = md.ops_code AND md2.refno = md.refno) AS op_count
								from seg_misc_ops as mo inner join seg_misc_ops_details as md on mo.refno = md.refno
								where encounter_nr = '". $enc_nr. "' and (str_to_date(mo.chrge_dte, '%Y-%m-%d %H:%i:%s') >= '". $bill_frmdte ."'
									 and str_to_date(mo.chrge_dte, '%Y-%m-%d %H:%i:%s') < '". $bill_dt ."') and group_code = ''
								group by ops_code) as t inner join seg_ops_rvs as om on t.ops_code = om.code
							
							INNER JOIN seg_misc_ops_details AS smod ON  t.ops_code  =  smod.ops_code AND t.refno = smod.refno

							order by description LIMIT $offset, $maxcount";

				if($this->res['ssl']=$db->Execute($this->sql)){
						if($this->rec_count=$this->res['ssl']->RecordCount()) {
								return $this->res['ssl'];
						}else{return FALSE;}
				}else{return FALSE;}
		}

		function SearchCurrentOPVersion2($enc_nr, $bill_frmdte, $bill_dt,$maxcount=100,$offset=0, $b_all = false){
			global $db;
	
			$this->sql = "SELECT smod.`refno`, smod.`entry_no`, smod.`ops_code` AS code,
								'1' AS op_count, sor.`description`, smod.`rvu`,
								smod.`multiplier`, (smod.rvu * smod.`multiplier`) AS op_charge, smod.`group_code`,
								'OA' AS provider, smod.`op_date`, smod.`description` AS alt_desc, smod.`filter_card_no` AS filter_card_no
							FROM seg_misc_ops `smo`
							INNER JOIN seg_misc_ops_details `smod`
							ON smod.`refno` = smo.`refno`
							INNER JOIN seg_ops_rvs `sor`
							ON sor.`code` = smod.`ops_code`
							WHERE smo.`encounter_nr` = ".$db->qstr($enc_nr)."
							AND smo.`chrge_dte` >= ".$db->qstr($bill_frmdte)."
						UNION
							SELECT smod.`refno`, smod.`entry_no`, smod.`ops_code` AS code,
								'1' AS op_count, sor.`description`, smod.`rvu`,
								smod.`multiplier`, (smod.rvu * smod.`multiplier`) AS op_charge,
								smod.`group_code`, 'OR' AS provider, ceo.`op_date`,
								smod.`description` AS alt_desc, smod.`filter_card_no` AS filter_card_no
							FROM seg_misc_ops `smo`
							INNER JOIN seg_misc_ops_details `smod`
							ON smo.`refno` = smod.`refno`
							INNER JOIN care_encounter_op `ceo`
							ON ceo.`refno` = smo.`refno`
							INNER JOIN seg_ops_rvs `sor`
							ON smod.`ops_code` = sor.`code`
							WHERE smo.`encounter_nr` = ".$db->qstr($enc_nr)."
							AND smo.`chrge_dte` >= ".$db->qstr($bill_frmdte);
			// var_dump($this->sql);die;
			if($this->res['ssl']=$db->Execute($this->sql)){
				if($this->rec_count=$this->res['ssl']->RecordCount()) {
						return $this->res['ssl'];
				}else{return FALSE;}
		}else{return FALSE;}
	
		}

		function SearchAppliedOP($enc_nr='',$searchkey='',$maxcount=100,$offset=0,$b_drchrg=0, $dr_nr=0, $b_all=0){
				global $db;

				if(empty($maxcount)) $maxcount=100;
				if(empty($offset)) $offset=0;
				if(empty($b_drchrg)) $b_drchrg = 0;

				# convert * and ? to % and &
				$searchkey=strtr($searchkey,'*?','%_');
				$searchkey=trim($searchkey);
				$searchkey = str_replace("^","'",$searchkey);
				$keyword=addslashes($searchkey);

				if ($b_drchrg == 1) {
					if ($b_all) {
					 $this->sql = "select refno, ops_code,
											 (select description from seg_ops_rvs as t3
														 where t3.code = t.ops_code
															and description like '%".$keyword."%') as description, op_date,
												t.rvu as rvu, multiplier, group_code, entry_no,
											 (select ifnull(count(*), 0) as count from seg_ops_chrg_dr as soca
												 where soca.ops_refno = t.refno and
													soca.ops_code = t.ops_code
													and dr_nr = ".$dr_nr.") as bselected
									from
									(select sosd.refno, sosd.ops_code, ifnull(soca.rvu, sosd.rvu) as rvu, ifnull(soca.multiplier, sosd.multiplier) as multiplier, group_code, 0 as entry_no,
											if(soca.ops_refno is null, 0, 1) as bselected,
									   (SELECT MAX(ceo.op_date) op_date
									       FROM care_encounter_op ceo
									       WHERE ceo.refno = sos.refno) op_date
										 from ((seg_ops_serv as sos inner join seg_ops_servdetails as sosd on sos.refno = sosd.refno)
											inner join seg_ops_rvs as sor on sosd.ops_code = sor.code)
											left join seg_ops_chrg_dr as soca on soca.ops_refno = sosd.refno and soca.ops_code = sosd.ops_code and ops_entryno = 0 and dr_nr = ".$dr_nr."
										 where sos.encounter_nr = '".$enc_nr."' and
											(sor.description like '%".$keyword."%' or
											 sosd.ops_code like '%".$keyword."%')
									 union
									select smod.refno, smod.ops_code, ifnull(soca.rvu, smod.rvu) as rvu, ifnull(soca.multiplier,smod.multiplier) as multiplier, group_code, smod.entry_no,
											if(soca.ops_refno is null, 0, 1) as bselected, smod.op_date
										 from ((seg_misc_ops as smo inner join seg_misc_ops_details as smod on smo.refno = smod.refno)
											inner join seg_ops_rvs as sor on smod.ops_code = sor.code)
											left join seg_ops_chrg_dr as soca on soca.ops_refno = smod.refno and soca.ops_code = smod.ops_code and ops_entryno = smod.entry_no and dr_nr = ".$dr_nr."
										 where smo.encounter_nr = '".$enc_nr."' and
											(sor.description like '%".$keyword."%' or
											 smod.ops_code like '%".$keyword."%')) as t
									order by description";
					}
					else {
					 $this->sql = "select refno, ops_code,
											 (select description from seg_ops_rvs as t3
														 where t3.code = t.ops_code
															and description like '%".$keyword."%') as description, op_date,
											 max(t.rvu) as rvu, max(multiplier) as multiplier, group_code, entry_no,
											 (select ifnull(count(*), 0) as count from seg_ops_chrg_dr as soca
												 where soca.ops_refno = t.refno and
													soca.ops_code = t.ops_code
													and dr_nr = ".$dr_nr.") as bselected
									from
									(select sosd.refno, sosd.ops_code, ifnull(soca.rvu, sosd.rvu) as rvu, ifnull(soca.multiplier, sosd.multiplier) as multiplier, group_code, 0 as entry_no,
											 if(soca.ops_refno is null, 0, 1) as bselected,
										   (SELECT MAX(ceo.op_date) op_date
										       FROM care_encounter_op ceo
										       WHERE ceo.refno = sos.refno) op_date
										 from (seg_ops_serv as sos inner join seg_ops_servdetails as sosd on sos.refno = sosd.refno)
											left join seg_ops_chrg_dr as soca on soca.ops_refno = sosd.refno and soca.ops_code = sosd.ops_code and ops_entryno = 0 and dr_nr = ".$dr_nr."
										 where sos.encounter_nr = '".$enc_nr."' and
											sosd.ops_code like '%".$keyword."%'
									 union
									select smod.refno, smod.ops_code, ifnull(soca.rvu, smod.rvu) as rvu, ifnull(soca.multiplier,smod.multiplier) as multiplier, group_code, smod.entry_no,
											if(soca.ops_refno is null, 0, 1) as bselected, smod.op_date
										 from (seg_misc_ops as smo inner join seg_misc_ops_details as smod on smo.refno = smod.refno)
											left join seg_ops_chrg_dr as soca on soca.ops_refno = smod.refno and soca.ops_code = smod.ops_code and ops_entryno = smod.entry_no and dr_nr = ".$dr_nr."
										 where smo.encounter_nr = '".$enc_nr."' and
											smod.ops_code like '%".$keyword."%' order by rvu desc) as t
									group by group_code having group_code <> ''
									union
									select refno, ops_code, t.description, op_date, rvu, multiplier, group_code, entry_no, bselected
									from
									(select sosd.refno, sosd.ops_code, sor.description, ifnull(soca.rvu, sosd.rvu) as rvu, ifnull(soca.multiplier, sosd.multiplier) as multiplier, group_code, 0 as entry_no,
											 if(soca.ops_refno is null, 0, 1) as bselected,
										   (SELECT MAX(ceo.op_date) op_date
										       FROM care_encounter_op ceo
										       WHERE ceo.refno = sos.refno) op_date
										 from ((seg_ops_serv as sos inner join seg_ops_servdetails as sosd on sos.refno = sosd.refno)
											inner join seg_ops_rvs as sor on sosd.ops_code = sor.code)
											left join seg_ops_chrg_dr as soca on soca.ops_refno = sosd.refno and soca.ops_code = sosd.ops_code and ops_entryno = 0 and dr_nr = ".$dr_nr."
										 where sos.encounter_nr = '".$enc_nr."' and
											(sor.description like '%".$keyword."%' or
											 sosd.ops_code like '%".$keyword."%')
									 union
									select smod.refno, smod.ops_code, sor.description, ifnull(soca.rvu, smod.rvu) as rvu, ifnull(soca.multiplier,smod.multiplier) as multiplier, group_code, smod.entry_no,
											if(soca.ops_refno is null, 0, 1) as bselected, smod.op_date
										 from ((seg_misc_ops as smo inner join seg_misc_ops_details as smod on smo.refno = smod.refno)
											inner join seg_ops_rvs as sor on smod.ops_code = sor.code)
											left join seg_ops_chrg_dr as soca on soca.ops_refno = smod.refno and soca.ops_code = smod.ops_code and ops_entryno = smod.entry_no and dr_nr = ".$dr_nr."
										 where smo.encounter_nr = '".$enc_nr."' and
											(sor.description like '%".$keyword."%' or
											 smod.ops_code like '%".$keyword."%')
										 order by description) as t
									where group_code = '' order by description";
					}
				}
				else {
					if ($b_all) {
						$this->sql = "select refno, ops_code,
												 (select description from seg_ops_rvs as t3
															 where t3.code = t.ops_code
																and description like '%".$keyword."%') as description, op_date,
													t.rvu as rvu, multiplier, group_code, entry_no,
												 (select ifnull(count(*), 0) as count from seg_ops_chrgd_accommodation as soca
													 where soca.ops_refno = t.refno and
														soca.ops_code = t.ops_code) as bselected
										from ".
										 "(select sosd.refno, sosd.ops_code, t.description, sosd.rvu, sosd.multiplier, group_code, 0 as entry_no, ".
															 "   (select ifnull(count(*), 0) as count from seg_ops_chrgd_accommodation as soca ".
															 "       where soca.ops_refno = sosd.refno and soca.ops_code = sosd.ops_code and ops_entryno = 0) as bselected, ".
															 "   (SELECT MAX(ceo.op_date) op_date
															       FROM care_encounter_op ceo
															       WHERE ceo.refno = sos.refno) op_date ".
															 "   from (seg_ops_serv as sos inner join seg_ops_servdetails as sosd on sos.refno = sosd.refno) ".
															 "   inner join seg_ops_rvs as sor on sosd.ops_code = sor.code ".
															 "   where sos.encounter_nr = '".$enc_nr."' and ".
															 "      (sor.description like '%".$keyword."%' or ".
															 "       sosd.ops_code like '%".$keyword."%') ".
															 " union ".
										 "select smod.refno, smod.ops_code, sor.description, smod.rvu, smod.multiplier, group_code, smod.entry_no, ".
															 "      (select ifnull(count(*), 0) as count from seg_ops_chrgd_accommodation as soca ".
															 "          where soca.ops_refno = smod.refno and soca.ops_code = smod.ops_code and ops_entryno = smod.entry_no) as bselected, ".
															 "       smod.op_date ".
															 "   from (seg_misc_ops as smo inner join seg_misc_ops_details as smod on smo.refno = smod.refno) ".
															 "   inner join seg_ops_rvs as sor on smod.ops_code = sor.code ".
															 "   where smo.encounter_nr = '".$enc_nr."' and ".
															 "      (sor.description like '%".$keyword."%' or ".
															 "       smod.ops_code like '%".$keyword."%') ".
										 "   order by description) as t
										 order by description";
					}
					else {
						$this->sql = "select refno, ops_code,
												 (select description from seg_ops_rvs as t3
															 where t3.code = t.ops_code
																and description like '%".$keyword."%') as description, op_date,
												 max(t.rvu) as rvu, max(multiplier) as multiplier, group_code, entry_no,
												 (select ifnull(count(*), 0) as count from seg_ops_chrgd_accommodation as soca
													 where soca.ops_refno = t.refno and
														soca.ops_code = t.ops_code) as bselected
										from
										(select sosd.refno, sosd.ops_code, sosd.rvu, sosd.multiplier, group_code, 0 as entry_no,
										   (SELECT MAX(ceo.op_date) op_date
										       FROM care_encounter_op ceo
										       WHERE ceo.refno = sos.refno) op_date
											 from seg_ops_serv as sos inner join seg_ops_servdetails as sosd on sos.refno = sosd.refno
											 where sos.encounter_nr = '".$enc_nr."' and
												sosd.ops_code like '%".$keyword."%'
										 union
										select smod.refno, smod.ops_code, smod.rvu, smod.multiplier, group_code, smod.entry_no, smod.op_date
											 from seg_misc_ops as smo inner join seg_misc_ops_details as smod on smo.refno = smod.refno
											 where smo.encounter_nr = '".$enc_nr."' and
												smod.ops_code like '%".$keyword."%' order by rvu desc) as t
										group by group_code having group_code <> ''
										union
										select refno, ops_code, t.description, op_date, rvu, multiplier, group_code, entry_no, bselected
										from ".
										 "(select sosd.refno, sosd.ops_code, sor.description, sosd.rvu, sosd.multiplier, group_code, 0 as entry_no, ".
															 "   (select ifnull(count(*), 0) as count from seg_ops_chrgd_accommodation as soca ".
															 "       where soca.ops_refno = sosd.refno and soca.ops_code = sosd.ops_code and ops_entryno = 0) as bselected, ".
															 "   (SELECT MAX(ceo.op_date) op_date
																       FROM care_encounter_op ceo
																       WHERE ceo.refno = sos.refno) op_date ".
															 "   from (seg_ops_serv as sos inner join seg_ops_servdetails as sosd on sos.refno = sosd.refno) ".
															 "   inner join seg_ops_rvs as sor on sosd.ops_code = sor.code ".
															 "   where sos.encounter_nr = '".$enc_nr."' and ".
															 "      (sor.description like '%".$keyword."%' or ".
															 "       sosd.ops_code like '%".$keyword."%') ".
															 " union ".
										 "select smod.refno, smod.ops_code, sor.description, smod.rvu, smod.multiplier, group_code, smod.entry_no, ".
															 "      (select ifnull(count(*), 0) as count from seg_ops_chrgd_accommodation as soca ".
															 "          where soca.ops_refno = smod.refno and soca.ops_code = smod.ops_code and ops_entryno = smod.entry_no) as bselected, ".
															 "      smod.op_date ".
															 "   from (seg_misc_ops as smo inner join seg_misc_ops_details as smod on smo.refno = smod.refno) ".
															 "   inner join seg_ops_rvs as sor on smod.ops_code = sor.code ".
															 "   where smo.encounter_nr = '".$enc_nr."' and ".
															 "      (sor.description like '%".$keyword."%' or ".
															 "       smod.ops_code like '%".$keyword."%') ".
										 "   order by description) as t
											where group_code = '' order by description";
					}
				}

				if($this->res['ssl']=$db->SelectLimit($this->sql,$maxcount,$offset)){
						if($this->res['ssl']->RecordCount()) {   // fix for Bugzilla bug 68
								return $this->res['ssl'];
						}else{return false;}
				}else{return false;}
		}


		function getOPCharge($enc_nr, $bill_dt, $nrvu, $casetyp="") {
			global $db;

			$ncharge = 0;

			$strSQL = "SELECT fn_getORCharge('{$enc_nr}', date('{$bill_dt}'), {$nrvu}, {$casetyp}) as opcharge";
			if ($result = $db->Execute($strSQL)) {
				if ($result->RecordCount()) {
					if ($row = $result->FetchRow()) {
						$ncharge = $row["opcharge"];
					}
				}
			}

			return $ncharge;
		}

		function isHouseCase($enc_nr) {
			global $db;

			$case = '';
			$sql = "select st.casetype_desc from seg_encounter_case sc
										inner join seg_type_case st on sc.casetype_id = st.casetype_id ".
						 "   where encounter_nr = '".$enc_nr."' ".
						 "   order by sc.modify_dt desc limit 1";

			if($result = $db->Execute($sql)){
					if($result->RecordCount()){
							if ($row = $result->FetchRow()) {
								$case = $row['casetype_desc'];
							}
					}
			}

			return !(strpos($case, 'HOUSE') === false);
		}

		function getOpAccommodationRefNo($bill_frmdte, $enc_nr) {
			global $db;

			$srefno = '';
			$strSQL = "select refno ".
								"   from seg_opaccommodation ".
								"   where str_to_date(chrge_dte, '%Y-%m-%d %H:%i:%s') >= '".$bill_frmdte."' ".
								"      and encounter_nr = '".$enc_nr."' ".
								"   order by chrge_dte limit 1";

			if ($result = $db->Execute($strSQL)) {
					if ($result->RecordCount()) {
							while ($row = $result->FetchRow())
									$srefno = $row['refno'];
					}
			}

			return($srefno);
		}

		function getMaxNoFromOPAccomDetails($refno) {
			global $db;

			$n = 0;
			$strSQL = "select ifnull(max(entry_no), 0) as latest_no ".
								"   from seg_opaccommodation_details as sod ".
								"   where refno = '".$refno."'";

			if ($result = $db->Execute($strSQL)) {
					if ($result->RecordCount()) {
							while ($row = $result->FetchRow())
									$n = $row['latest_no'];
					}
			}

			return($n);
		}

        function fetchNewbornHearingTestDetails($encounter_nr) {
        global $db;

        $this->sql = "SELECT  
                      smod.`filter_card_no`,
                      smod.`registry_card_no`,
                      smod.`nhst_result`,
                      smo.`refno`
                    FROM
                      seg_misc_ops smo 
                      INNER JOIN seg_misc_ops_details smod 
                        ON smo.`refno` = smod.`refno` 
                    WHERE smo.`encounter_nr` = ".$db->qstr($encounter_nr)."
                    AND smo.`refno` = smod.`refno`
                    AND smod.ops_code = 99460";

            return $db->GetRow($this->sql);
        }

//added by daryl 09/03/2014
function SearchCurrentOP_trans($enc_nr, $bill_frmdte, $bill_dt,$maxcount=100,$offset=0, $b_all = false){
				global $db;

				if(empty($maxcount)) $maxcount=100;
				if(empty($offset)) $offset=0;


			$this->sql = "SELECT refno, entry_no, ops_code as code, op_count, description, max(t.rvu) as rvu, max(multiplier) as multiplier, max(op_charge) as op_charge, group_code, provider, max(op_date) as op_date,description AS alt_desc
							from
							(select od.refno, 0 as entry_no, od.ops_code, od.rvu, od.multiplier, (od.rvu * od.multiplier) as op_charge, group_code, 'OR' as provider,
									 (SELECT MAX(ceo.op_date) AS op_date
										FROM seg_ops_serv AS sos INNER JOIN care_encounter_op AS ceo ON sos.refno = ceo.refno
										WHERE sos.refno = os.refno) as op_date,
								 (SELECT COUNT(ops_code) AS op_count FROM seg_ops_servdetails AS od2 WHERE od2.ops_code = od.ops_code AND od2.refno = od.refno) AS op_count
								 from seg_ops_serv as os inner join seg_ops_servdetails as od on os.refno = od.refno
								 where encounter_nr = '". $enc_nr. "' and is_cash = 0 and upper(trim(os.status)) <> 'DELETED'
								 and group_code <> ''
							 union
							 select md.refno, md.entry_no, md.ops_code, md.rvu, md.multiplier, chrg_amnt, group_code, 'OA' as provider, md.op_date,
								(SELECT COUNT(ops_code) AS op_count FROM seg_misc_ops_details AS md2 WHERE md2.ops_code = md.ops_code AND md2.refno = md.refno) AS op_count
								from seg_misc_ops as mo inner join seg_misc_ops_details as md on mo.refno = md.refno
								where encounter_nr = '". $enc_nr. "' and group_code <> ''
							 order by rvu desc) as t inner join seg_ops_rvs as om on t.ops_code = om.code
							group by group_code
							union
							select t.refno, t.entry_no, t.ops_code, op_count, om.description, t.rvu, t.multiplier, op_charge, t.group_code, provider, t.op_date, smod.description AS alt_desc 
							from
							(select od.refno, 0 as entry_no, od.ops_code, sum(od.rvu) as rvu, max(od.multiplier) as multiplier, sum(od.rvu * od.multiplier) as op_charge, group_code, 'OR' as provider,
									 (SELECT MAX(ceo.op_date) AS op_date
										FROM seg_ops_serv AS sos INNER JOIN care_encounter_op AS ceo ON sos.refno = ceo.refno
										WHERE sos.refno = os.refno) as op_date,
								 (SELECT COUNT(ops_code) AS op_count FROM seg_ops_servdetails AS od2 WHERE od2.ops_code = od.ops_code AND od2.refno = od.refno) AS op_count
								 from seg_ops_serv as os inner join seg_ops_servdetails as od on os.refno = od.refno
								 where encounter_nr = '". $enc_nr. "' and is_cash = 0 and upper(trim(os.status)) <> 'DELETED'
									and group_code = ''
								 group by ops_code
							 union
							 select md.refno, md.entry_no, md.ops_code, sum(md.rvu) as rvu, max(md.multiplier) as multiplier, sum(chrg_amnt) as chrg_amnt, group_code, 'OA' as provider, md.op_date,
								(SELECT COUNT(ops_code) AS op_count FROM seg_misc_ops_details AS md2 WHERE md2.ops_code = md.ops_code AND md2.refno = md.refno) AS op_count
								from seg_misc_ops as mo inner join seg_misc_ops_details as md on mo.refno = md.refno
								where encounter_nr = '". $enc_nr. "' 
									 and group_code = ''
								group by ops_code) as t inner join seg_ops_rvs as om on t.ops_code = om.code
							
							INNER JOIN seg_misc_ops_details AS smod ON  t.ops_code  =  smod.ops_code AND t.refno = smod.refno

							order by description LIMIT $offset, $maxcount";

				if($this->res['ssl']=$db->Execute($this->sql)){
						if($this->rec_count=$this->res['ssl']->RecordCount()) {
								return $this->res['ssl'];
						}else{return FALSE;}
				}else{return FALSE;}
		}

function SearchCurrentOP_seg($enc_nr) {
			global $db;

	$this->sql = "SELECT sed.`code` AS diag_code,
							  sed.`description` AS diagnosis_description,
							  sed.type_nr AS type_nr,
							  sed.`code_alt` AS code_alt,
							  sed.diagnosis_nr AS diagnosis_nr,
							  sed.`frmbilling`,
							  sed.`reason`,
							  sed.`entry_no`,
							  sed.`rvs_date`
							 FROM 
							seg_encounter_diagnosis sed 
							INNER JOIN
							care_encounter_procedure cep 
							ON sed.`code` = cep.`code`
							 WHERE cep.`encounter_nr` = '".$enc_nr."' 
							 AND cep.status <> 'deleted' 
							 AND sed.`is_deleted` = '0'";

		if($this->res['ssl']=$db->Execute($this->sql)){
						if($this->rec_count=$this->res['ssl']->RecordCount()) {
								return $this->res['ssl'];
						}else{return FALSE;}
				}else{return FALSE;}
}
		//ended by daryl
				
}  # end class SegOps
?>