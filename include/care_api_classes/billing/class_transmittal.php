<?php
/*
 * @package care_api
 * Class of Transmittal.
 *
 * Created: January 17, 2009 (LST)
 * Modified: January 17, 2009 (LST)
 */
require_once($root_path.'include/care_api_classes/class_core.php');

class Transmittal extends Core {
    /**
    * Table name
    * @var string
    */
    var $tb_hdr = 'seg_transmittal'; # transmittal header table
        /**
    * Table name
    * @var string
    */
    var $tb_details = 'seg_transmittal_details'; # transmittal details table
    /*
    * @var String
    */
    var $transmit_no;
    /*
    * @var Datetime
    */
    var $transmit_dte;
    /*
    * @var Integer
    */
    var $hcare_id;
    /*
    * @var String
    */
    var $remarks;
    /*
    * @var String
    */
    var $old_trnsmit_no;
    /*
    * @var String
    */
    var $user_name;
    /*
    * @var Array of String (encounter nos.)
    */
    var $encounters;
    /*
    * @var Array of Double (patient claims)
    */
    var $patient_claims;

    function setTransmitNo($no) {
        $this->transmit_no = $no;
    }

    function setTransmitDte($dte) {
        $this->transmit_dte = $dte;
    }

    function setInsuranceID($id) {
        $this->hcare_id = $id;
    }

    function setRemarks($srem) {
        $this->remarks = $srem;
    }

    function setUser($user) {
        $this->user_name = $user;
    }

    function setOldTransmitNo($no) {
        $this->old_trnsmit_no = $no;
    }

    function setEncountersWithClaim($cases) {
        $this->encounters = $cases;
    }

    function setPatientClaims($pclaims) {
        $this->patient_claims = $pclaims;
    }

    function getErrorMsg() {
        global $db;

        $this->db_error_msg = $db->ErrorMsg();
        $this->error_msg = $this->LastErrorMsg();
        if ($this->error_msg == "") $this->error_msg = $this->db_error_msg;

        return $this->error_msg;
    }

   /**
    * @internal     Saves the transmittal header info in seg_transmittal and encounter nos. billed in seg_transmittal_details.
    * @access       public
    * @author       Bong S. Trazo
    * @package      modules
    * @subpackage   billing
    * @global       db - database object, $_SESSION['sess_user_name'], $_SESSION['cases']
    *
    * @param        trnsmit_no, trnsmit_dte, hcare_id, remarks
    * @return       boolean TRUE if successful, FALSE otherwise.
    */
    function saveTransmittal() {
        global $db;

        $s_errmsg = '';
        $bSuccess = false;

        //$objResponse->alert($trnsmit_no);
        if ($this->transmit_no != '') {
            $this->startTrans();

            if ($this->old_trnsmit_no == '') {
                $strSQL = "insert into $this->tb_hdr (transmit_no, transmit_dte, hcare_id, remarks, create_id, modify_id, create_dt, modify_dt)
                              values('$this->transmit_no', '$this->transmit_dte', $this->hcare_id, '$this->remarks', '$this->user_name',
                                     '$this->user_name', now(), now())";
                $bSuccess = $db->Execute($strSQL);
            }
            else {
                $strSQL = "delete from $this->tb_details where transmit_no = '$this->old_trnsmit_no'";
                $bSuccess = $db->Execute($strSQL);

                if ($bSuccess) {
                    $strSQL = "update $this->tb_hdr set
                                  transmit_no  = '$this->transmit_no',
                                  transmit_dte = '$this->transmit_dte',
                                  hcare_id     = $this->hcare_id,
                                  remarks      = '$this->remarks',
                                  modify_id    = '$this->user_name',
                                  modify_dt    = now()
                                  where transmit_no = '$this->old_trnsmit_no'";
                    $bSuccess = $db->Execute($strSQL);
                }
                else
                    $s_errmsg = $this->getErrorMsg();
            }

            if ($bSuccess) {
                if (is_array($this->encounters) && (count($this->encounters) > 0)) {
                    $i = 0;
                    foreach ($this->encounters as $k=>$v) {
                        $pclaim = $this->patient_claims[$i++];
                        $strSQL = "insert into seg_transmittal_details (transmit_no, encounter_nr, patient_claim)
                                      values ('$this->transmit_no', '$v', $pclaim)";
                        $bSuccess = $db->Execute($strSQL);
                        if (!$bSuccess) {
                            $s_errmsg = $this->getErrorMsg();
                            break;
                        }
                    }
                }
                else {
                    $s_errmsg = "System cannot save transmittal without billing to be transmitted!";
                    $bSuccess = false;
                }
            }
            else
                $s_errmsg = $this->getErrorMsg();

            if (!$bSuccess) $this->failTrans();
            $this->completeTrans();
        }
        else
            $s_errmsg = "No valid transmittal control no.!";

        $this->error_msg = $s_errmsg;

        return $bSuccess;
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

    /**
    * @internal     Return the transmittal header info.
    * @access       public
    * @author       Bong S. Trazo
    * @package      modules
    * @subpackage   billing
    *
    * @param        stransmit_no -- transmittal control no.
    * @return       recordset if successful, FALSE otherwise.
    */
    function getTransmittalHeaderInfo($stransmit_no) {
        global $db;

        $this->sql = "select h.*, ci.name, ci.addr_mail \n
                         from $this->tb_hdr as h inner join care_insurance_firm as ci \n
                            on h.hcare_id = ci.hcare_id \n
                         where transmit_no = '$stransmit_no'";
        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount())
                return $this->result->FetchRow();
            else
                return false;
        }
        else
            return false;
    }

    /**
    * @internal     Return the transmittal details.
    * @access       public
    * @author       Bong S. Trazo
    * @package      modules
    * @subpackage   billing
    *
    * @param        stransmit_no -- transmittal control no.
    * @return       recordset if successful, FALSE otherwise.
    */
    function getTransmittalDetailsInfo($stransmit_no) {
        global $db;

        $this->sql = "select * \n
                         from $this->tb_details \n
                         where transmit_no = '$stransmit_no'";
        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount())
                return $this->result;
            else
                return false;
        }
        else
            return false;
    }

   /**
    * @internal     Return the recordset of transmittals given the filter.
    * @access       public
    * @author       Bong S. Trazo
    * @package      modules
    * @subpackage   billing
    *
    * @param        filters, offset, rowcount
    * @return       recordset if successful, FALSE otherwise.
    */
    function getTransmittalDetails($filters, $offset=0, $rowcount=15) {
        global $db;

        if (!$offset) $offset = 0;
        if (!$rowcount) $rowcount = 15;

                $filter_err = '';

        if (is_array($filters)) {
            foreach ($filters as $i=>$v) {
                switch (strtolower($i)) {
                    case 'transmittal_no':
                        $phFilters[] = 'h.transmit_no = ' . $db->qstr($v);
                        break;
                    case 'datetoday':
                        $phFilters[] = 'DATE(transmit_dte)=DATE(NOW())';
                    break;
                    case 'datethisweek':
                        $phFilters[] = 'YEAR(transmit_dte)=YEAR(NOW()) AND WEEK(transmit_dte)=WEEK(NOW())';
                    break;
                    break;
                    case 'datethismonth':
                        $phFilters[] = 'YEAR(transmit_dte)=YEAR(NOW()) AND MONTH(transmit_dte)=MONTH(NOW())';
                    break;
                    case 'date':
                        $phFilters[] = "DATE(transmit_dte)='$v'";
                    break;
                    case 'datebetween':
                        $phFilters[] = "transmit_dte>='".$v[0]."' AND transmit_dte<='".$v[1]."'";
                    break;
                    case 'name':
                                            if (strpos($v, ",") === false) {
                                                $phFilters[] = "cp.name_last like '".trim($v)."%'";
                                                if ( (trim($v) == '') || (strlen(trim($v)) < 3) ) $filter_err = "Specify at least 3 characters in patient's family name!";
                                            }
                                            else {
                                                $tmp = explode(",", $v);
                                                $phFilters[] = "cp.name_last like '".trim($tmp[0])."%'";
                                                $phFilters[] = "cp.name_first like '".trim($tmp[1])."%'";

                                                if ( (trim($tmp[0]) == '') || (strlen(trim($tmp[0])) < 3) )
                                                    $filter_err = "Specify at least 3 characters in patient's family name!";
                                                else
                                                    if ( (trim($tmp[1]) == '') || (strlen(trim($tmp[1])) < 2) ) $filter_err = "Specify at least 2 characters in patient's first name!";
                                            }
//                        $phFilters[] = "concat(cp.name_last, (case when isnull(cp.name_first) or cp.name_first = '' then (case when isnull(cp.name_middle) or cp.name_middle = '' then '' else ', ' end) else ', ' + cp.name_first end), (case when isnull(cp.name_middle) or cp.name_middle = '' then '' else ' ' + cp.name_middle end)) REGEXP '[[:<:]]".substr($db->qstr($v),1);
                        break;
                    case 'case_no':
                        $phFilters[] = "ce.encounter_nr REGEXP ".$db->qstr($v);
                    break;
                    case 'insurance':
                        $phFilters[] = "h.hcare_id = ".$v;
                    break;
                }
            }
        }

                if ($filter_err != '') {
                    $this->error_msg = $filter_err;
                    return false;
                }

        $phWhere=implode(") AND (",$phFilters);
        if ($phWhere) $phWhere = "($phWhere)";
        else $phWhere = "1";

        $this->getTransmitDetailsCount($phWhere);       // Get count of transmittals in filter.

        $this->sql = "select ce.pid, h.hcare_id, h.transmit_no, transmit_dte, cp.name_last, cp.name_first, cp.name_middle, d.encounter_nr, \n
                         (select sum(total_acc_coverage + total_med_coverage + total_sup_coverage + total_srv_coverage + total_ops_coverage + total_d1_coverage + total_d2_coverage + total_d3_coverage + total_d4_coverage + total_msc_coverage) as tclaim \n
                             from seg_billing_coverage as sbc inner join seg_billing_encounter as sbe on \n
                                sbc.bill_nr = sbe.bill_nr where sbe.encounter_nr = d.encounter_nr and sbc.hcare_id = h.hcare_id and sbe.is_deleted IS NULL) as claim, \n
                         (select insurance_nr from care_person_insurance as cpi where cpi.pid = ce.pid and cpi.hcare_id = h.hcare_id) as policy_no, \n
                         concat(date_format((case when admission_dt is null or admission_dt = '' then encounter_date else admission_dt end), '%b %e, %Y %l:%i%p'), ' to ', (case when ce.discharge_date is null or ce.discharge_date = '' then 'present' else date_format(str_to_date(ce.modify_time, '%Y-%m-%d %H:%i:%s'), '%b %e, %Y %l:%i%p') end)) as confine_period, \n
                         d.is_rejected \n
                         from ((seg_transmittal as h inner join seg_transmittal_details as d on h.transmit_no = d.transmit_no) \n
                            inner join care_encounter as ce on d.encounter_nr = ce.encounter_nr) inner join care_person as cp on ce.pid = cp.pid \n".
                     "   where ($phWhere) ".
                     " GROUP BY transmit_no ASC ".
                     "   order by h.transmit_dte asc ".

                     "   limit $offset, $rowcount";
        if ($this->result = $db->Execute($this->sql))
            return $this->result;
        else
            return false;
    }

    function getTransmitDetailsCount($phWhere) {
                global $db;

                $this->rec_count = 0;
        $strSQL = "select count(*) reccount   \n
                      from ((seg_transmittal as h inner join seg_transmittal_details as d on h.transmit_no = d.transmit_no) \n
                           inner join care_encounter as ce on d.encounter_nr = ce.encounter_nr) inner join care_person as cp on ce.pid = cp.pid \n".
                  "   where ($phWhere)";
        if ($rs = $db->Execute($strSQL)) {
                $row = $rs->FetchRow();
                $this->rec_count = $row['reccount'];
        }
    }

    function getTransmittalDetailsCount() {
                return $this->rec_count;
    }

   /**
    * @internal     Mark a particular claim in the transmittal as rejected.
    * @access       public
    * @author       Bong S. Trazo
    * @package      modules
    * @subpackage   billing
    *
    * @param        transmit_no, enc_nr
    * @return       boolean TRUE if successful, FALSE otherwise.
    */
    function toggleReject($transmit_no, $enc_nr, $breject = false) {
        $this->sql = "update $this->tb_details set is_rejected = ".($breject ? 1 : 0)." \n
                         where transmit_no = '$transmit_no' and encounter_nr = '$enc_nr'";
        return $this->Transact($this->sql);
    }

   /**
    * @internal     Queries if transmittal is rejected or not.
    * @access       public
    * @author       Bong S. Trazo
    * @package      modules
    * @subpackage   billing
    *
    * @param        transmit_no, enc_nr
    * @return       boolean TRUE if rejected, FALSE otherwise.
    */
    function isRejected($transmit_no, $enc_nr) {
        global $db;

        $is_rejected = false;

        $this->sql = "select is_rejected \n
                         from $this->tb_details \n
                         where transmit_no = '$transmit_no' and encounter_nr = '$enc_nr'";
        if ($result = $db->Execute($this->sql)) {
            if ($result->RecordCount()) {
                if ($row = $result->FetchRow()) {
                    $is_rejected = ($row['is_rejected'] != 0);
                }
            }
        }

        return $is_rejected;
    }

   /**
    * @internal     Indicates whether patient is principal member or not.
    * @access       public
    * @author       Bong S. Trazo
    * @package      modules
    * @subpackage   billing
    *
    * @param        pid of patient, health care insurance id
    * @return       boolean -- true if principal member.
    */
    function isPersonPrincipal($s_pid, $n_hcareid) {
        global $db;

        $bPrincipal = false;

        $strSQL = "select is_principal ".
                  "   from care_person_insurance as cpi ".
                  "   where pid = '$s_pid' and hcare_id = $n_hcareid";

        if ($result = $db->Execute($strSQL))
            if ($result->RecordCount())
                while ($row = $result->FetchRow()) {
                    if ($row['is_principal'])
                        $bPrincipal = true;
                    else
                        $bPrincipal = false;
                }

        return($bPrincipal);
    }

   /**
    * @internal     Returns the pid of principal member of insurance.
    * @access       public
    * @author       Bong S. Trazo
    * @package      modules
    * @subpackage   billing
    *
    * @param        pid of patient, health care insurance id
    * @return       pid of principal member.
    */
    function getPrincipalPIDofHCare($s_pid, $nhcareid) {
        global $db;

        $sprincipal_pid = "";

        $strSQL = "select pid ".
                  "   from care_person_insurance as cpi0 ".
                  "   where exists (select * from care_person_insurance as cpi1 ".
                  "                    where cpi1.pid = '". $s_pid ."' and cpi1.hcare_id = ". $nhcareid ." ".
                  "                       and cpi1.pid <> cpi0.pid and cpi1.hcare_id = cpi0.hcare_id ".
                  "      and cpi1.insurance_nr = cpi0.insurance_nr) ".
                  "      and cpi0.is_principal <> 0";

        if ($result = $db->Execute($strSQL)) {
            if ($result->RecordCount()) {
                while ($row = $result->FetchRow())
                    $sprincipal_pid = $row['pid'];
            }
        }

        return($sprincipal_pid);
    }

    //added by Nick, 2/24/2014
    /**
     * Gets the patient trasmittal info
     * @param  string $enc Encounter Number
     * @return array       Returns array of details
     */
    var $data;
    #edited by daryl
   function getPatientTrasmittalInfo($enc,$hcare_id){
        global $db;
        $sql = "SELECT *,st.`transmit_dte` 
        FROM `seg_transmittal_details`  AS stds
        INNER JOIN seg_transmittal AS st ON stds.`transmit_no` = st.`transmit_no`
         WHERE encounter_nr = ? and hcare_id = ?";
        $rs = $db->Execute($sql,array($enc,$hcare_id));
        if($rs){
            if($rs->RecordCount() > 0){
                $rows = $rs->GetRows();
                return $rows;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    //end nick
}
?>
