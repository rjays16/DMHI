<?php
#EDITED BY VANESSA A. SAREN 02-13-08
#EDITED BY LST 06-29-2008, 08-13-2008, 12-03-2008 - Removed getSuppliesData function
require('./roots.php');
require_once($root_path."classes/fpdf/fpdf.php");
require_once($root_path.'include/inc_environment_global.php');
require_once($root_path.'include/inc_date_format_functions.php');
require_once($root_path.'include/care_api_classes/class_hospital_admin.php');
require_once($root_path.'include/care_api_classes/class_encounter.php');
require_once($root_path.'include/care_api_classes/billing/class_bill_info.php'); //added by jasper 04/08/2013
require_once($root_path.'include/care_api_classes/billing/class_billing.php');
require_once($root_path.'include/care_api_classes/billing/class_billareas.php');

#added by VAN 04-24-2009
require_once($root_path.'include/care_api_classes/class_labservices_transaction.php');

define('GEN_COL01', 4);		 	// in mm.
define('GEN_COL02', 2.8); 	 	// in inches.
define('GEN_COL02_D', 8.25);	 	// in mm.
define('GEN_COL02_D2', 11.75);	// in mm.
define('GEN_COL02_D3', GEN_COL02_D2 + 3.5);	// in mm.

define('COL_MID', 2);

define('COL03_WIDTH', 33.5);
define('COL04_WIDTH', 25.972);
define('COL05_WIDTH', 27.972);
define('COL06_WIDTH', 27.972);

define('FOOTER_COL01', 84);
define('FOOTER_COL02', 84);

define('NAME_LEN', 52);
define('DEPT_LEN', 24);

class BillPDF extends FPDF {
	var $encounter_nr;
    var $bill_ref_nr; //added by jasper 01/04/13
    var $prev_bill_amt; //added by jasper 04/08/2013
	var $ishousecase;
	var $isphic;
	var $death_date; //Added by Jarel 05/24/13

	var $DEFAULT_FONTSIZE;
	var $DEFAULT_FONTTYPE;
	var $DEFAULT_FONTSTYLE;

	var $WBORDER;
	var $ALIGNMENT;
	var $NEWLINE;

	var $reportTitle="";

	var $billType;

	var $Data;
	var $pfDaTa;

	var $totalCharge = array(0, 0, 0, 0, 0, 0, 0, 0, 0);
	var $totalDiscount = array(0, 0, 0, 0, 0, 0, 0, 0, 0);
	var $totalCoverage = array(0, 0, 0, 0, 0, 0, 0, 0, 0);
	var $totalExcess = array(0, 0, 0, 0, 0, 0, 0, 0, 0);

	var $personData = array();

	var $objBill; //Billing object

	var $IsDetailed;
	var $bill_date;

	var $head_name;
	var $head_position;

	var $clerk_name;
    var $clerk_italized;

	var $b_acchist_gathered = FALSE;

    var $brecalc = false;

	/*
	 * Constructor
	 * @param string encounter_nr
	 */

	function BillPDF($enc='', $bill_dt = "0000-00-00 00:00:00", $bill_frmdt = "0000-00-00 00:00:00", $old_bill_nr = '', $bcomp=false, $deathdate) {
		 if(!empty($enc)){
			$this->encounter_nr = $enc;
		 }
         //added by jasper 01/04/13
         if (!empty($old_bill_nr)) {
            $this->bill_ref_nr = $old_bill_nr;
         }
		#added by VAN 02-14-08
		 $this->IsDetailed = $_GET['IsDetailed'];
         $this->brecalc = $bcomp;

		 $pg_size = array($this->in2mm(8.5), $this->in2mm(6.5));                 // Default to long bond paper --- modified by LST - 04.13.2009
		 $this->FPDF("P","mm", $pg_size);
		 $this->AliasNbPages();
		 $this->AddPage("P");

		 $this->DEFAULT_FONTTYPE = "Times";
		 $this->DEFAULT_FONTSIZE = 11;
		 $this->DEFAULT_FONTSTYLE = '';
		 $this->NEWLINE = 1;
		 $this->death_date = $deathdate;

		 //Instantiate billing object
         if ($this->brecalc) {
            $this->objBill = new Billing($this->encounter_nr, $bill_dt, $bill_frmdt, $old_bill_nr, $deathdate);
            $this->objBill->applyDiscounts();
         }
         else
            $this->objBill = unserialize($_SESSION['billobject']['main']);         // modified by LST -- 11.04.2010
		 $this->bill_date = $bill_dt;

		 //get first the confinement type
		 $this->objBill->getConfinementType();

         //added by jasper 03/18/2013
         if (!($this->objBill->isForFinalBilling())) {
            $this->Image('../../gui/img/logos/tentativebill.jpg',30, 50, 150,150);
         }
         //added by jasper 03/18/2013

	}// end of Bill_Pdf

	//Page Header
	#commented by VAN 03-15-08

	function Header() {
		//Display Page title
#----------------------- LST - 06-21-2008
		$objInfo = new Hospital_Admin();
		if ($row = $objInfo->getAllHospitalInfo()) {
			$row['hosp_agency'] = strtoupper($row['hosp_agency']);
			$row['hosp_name']   = strtoupper($row['hosp_name']);
		}
		else {
			$row['hosp_country'] = "Republic of the Philippines";
			$row['hosp_agency']  = "DEPARTMENT OF HEALTH";
			$row['hosp_name']    = "DAVAO MEDIQUEST HOSPITAL INC";
			$row['hosp_addr1']   = "Mc Arthur Highway Lizada Toril Davao City";
		}

		$this->Image('../../gui/img/logos/dmhi_logo.jpg',20,10,20,20);

//		$this->Image($root_path.'gui/img/logos/dmhi_logo.jpg',20,10,20,20);
		$this->SetFont("Times", "B", "10");
		$this->Cell(0, 4, $row['hosp_country'], 0, 1,"C");
		$this->Cell(0, 4, $row['hosp_agency'], 0, 1 , "C");
		$this->Cell(0, 4, $row['hosp_name'], 0, 1, "C");

		$this->SetFont("Times", "", "10");
		$this->Cell(0, 4, $row['hosp_addr1'], 0, 1, "C");
#---------------------- LST  - 06-21-2008

	}// end of Bill_Header function

	//Page footer
	function Footer() {
		//Go to 1.5 cm from bottom
		$this->SetY(-15);
		$this->SetFont('Arial','I',8);
		//Page number
		$this->Cell(0, 10, 'Page '.$this->PageNo().' of {nb}',0,0,'C');
	}

	function ReportFooter() {
				#added by VAN 04-24-2009
				$labObj = new SegLab();

		$this->getBillingHead();
		$this->getBillingClerk($_SESSION['sess_temp_userid'],$_GET['encounter_nr']);

        #italized if no save bill yet
        if ($this->clerk_italized)
            $this->SetFont('Arial','I',8);
        else
		    $this->SetFont($this->fontType, $this->fontStyle, $this->fontSize);

		// Signatories ...
		$this->Ln(4);
		$this->Cell(4, 4, "", "", 0, '');
		$this->Cell(FOOTER_COL01, 4, "", "", 0, '');
		$this->Cell(20, 4, "", "", 0, '');
		$this->Cell(FOOTER_COL02, 4, "Prepared by:", "", 1, '');
		$this->Ln(4);
		$this->Cell(4, 4, "", "", 0, '');
		$this->Cell(FOOTER_COL01, 4, $this->head_name, "", 0, 'C');
		$this->Cell(20, 4, "", "", 0, '');
		$this->Cell(FOOTER_COL02, 4, $this->clerk_name, "", 1, 'C');

		$this->Cell(4, 4, "", "", 0, '');
		$this->Cell(FOOTER_COL01, 4, $this->head_position, "T", 0, 'C');
		$this->Cell(20, 4, "", "", 0, '');
		$this->Cell(FOOTER_COL02, 4, "Billing Clerk", "T", 1, 'C');

		// Confirmation ...
		$this->Ln(4);
		$this->Cell(4, 4, "", "", 0, '');
		$this->Cell(FOOTER_COL01, 4, "", "", 0, '');
		$this->Cell(20, 4, "", "", 0, '');
		$this->Cell(FOOTER_COL02, 4, "Confirmed by:", "", 1, '');

		$this->Ln(5);
		$this->Cell(4, 4, "", "", 0, '');
		$this->Cell(FOOTER_COL01, 4, "", "", 0, '');
		$this->Cell(20, 4, "", "", 0, '');
		$this->Cell(FOOTER_COL02, 4, "Signature over Printed Name/Relationship/Tel.#", "T", 1, 'C');

		$saccom = (!$this->ishousecase) ? strtoupper($this->objBill->getAccommodationDesc()) : "";

		$nypos = $this->GetY();
        $this->SetY(-1 * $this->in2mm(1.66));
		$ntmp = $this->GetY();
		if ($nypos >= $ntmp) $this->AddPage("P");

        $this->SetY(-1 * $this->in2mm(1.66));

		$this->Cell(0, 1, "", "T", 1, 'C');
		$this->Cell(0, 4, $saccom." PATIENT CLEARANCE", "", 1, 'C');
		$this->Ln(1);

		$this->Cell(4, 2, "", "", 0, '');
		$this->Cell(FOOTER_COL01, 4, "CASE #: ".$this->encounter_nr, "", 0, 'C');
		$this->Cell(20, 2, "", "", 0, '');

		$row = $this->personData;
		$name = strtoupper($row['name_last'].",  ".$row['name_first']." ".$row['name_middle']);
		$this->Cell(FOOTER_COL02, 4, "PATIENT: ".$name, "", 1, 'C');

		$this->Ln(2);

		#edited by VAN 04-24-2009
        #change this that not only with borrowed blood but also all patients with blood request will
        #ask for blood bank clearance
		$hasbloodborrowed = 0;
		$labObj->hasBloodRequest($this->encounter_nr);
		if ($labObj->count)
		    $hasbloodborrowed = 1;

		if ($hasbloodborrowed){
		    $this->Cell(4, 4, "", "", 0, '');
		    $this->Cell(FOOTER_COL01*0.325, 4, "BLOOD BANK: ", "", 0, '');
		    $this->Cell(4, 4, "", "", 0, '');
		    $this->Cell(FOOTER_COL01*0.675, 4, str_repeat('_', 30), "", 0, '');

			$pharmaXval =  8;
			$pharmaNextLine =  1;
			$cashierXval =  4;
			$cashierNextLine =  0;
			$nurseXval =  8;
			$nurseNextLine =  1;
			$billingXval = 4;

        }else{
			$pharmaXval =  4;
			$pharmaNextLine =  0;
			$cashierXval =  8;
			$cashierNextLine =  1;
			$nurseXval =  4;
			$nurseNextLine =  0;
			$billingXval = 8;
		}

		$this->Cell($pharmaXval, 4, "", "", 0, '');
		$this->Cell(FOOTER_COL02*0.325, 4, (strpos($saccom, "PAYWARD") === false ? "LINEN: " : "PHARMACY: "), "", 0, '');
		$this->Cell(4, 4, "", "", 0, '');
		$this->Cell(FOOTER_COL01*0.675, 4, str_repeat('_', 30), "", $pharmaNextLine, '');

		if ($hasbloodborrowed)
				$this->Ln(2);

		$this->Cell($cashierXval, 4, "", "", 0, '');
		$this->Cell(FOOTER_COL01*0.325, 4, "CASHIER: ", "", 0, '');
		$this->Cell(4, 4, "", "", 0, '');
		$this->Cell(FOOTER_COL01*0.675, 4, str_repeat('_', 30), "", $cashierNextLine, '');

		if (!$hasbloodborrowed)
		    $this->Ln(2);

		$this->Cell($nurseXval, 4, "", "", 0, '');
		$this->Cell(FOOTER_COL02*0.325, 4, "NURSE ON DUTY: ", "", 0, '');
		$this->Cell(4, 4, "", "", 0, '');
		$this->Cell(FOOTER_COL01*0.675, 4, str_repeat('_', 30), "", $nurseNextLine, '');
	 #----------
		if (!(strpos($saccom, "PAYWARD") === false)) {
			if ($hasbloodborrowed)
								$this->Ln(2);

		}
	}

	function ReportTitle() {
		$this->Ln(2);
		$this->SetFont($this->fontType, "B", "10");
		$this->Cell(0, 4, $this->reportTitle, 0 , 1, "C");
	}

	function PersonInfo() {
		global $date_format;

		$rowArray = $this->getPersonInfo($this->encounter_nr);
		if (!is_bool($rowArray)) {
			$row = $rowArray->FetchRow();

			$this->personData = $row;

			$name = strtoupper($row['name_last'].",  ".$row['name_first']." ".$row['name_middle']);

			$saddr1 = '';
			$saddr2 = '';
			$saddr3 = '';
			$this->trimAddress($row['street_name'], $row['brgy_name'], $row['mun_name'], $row['prov_name'], $row['zipcode'], $saddr1, $saddr2, $saddr3);

			$billdte       = strftime("%b %d, %Y %I:%M %p", strtotime($this->bill_date));
			if (is_null($row['admission_dt']))
				$admission_dte = strftime("%b %d, %Y %I:%M %p", strtotime($row['encounter_date']));
			else
				$admission_dte = strftime("%b %d, %Y %I:%M %p", strtotime($row['admission_dt']));

// --- Changes made by LST - $this->in2mm(4.8) to $this->in2mm(4.5)

			$this->Ln(4);
			$this->SetFont($this->fontType, $this->fontStyle, $this->fontSize);

            //added by jasper 01/04/13
            //Encounter number
            $this->Cell(20, 4, "Case #", "", 0, 'L');
            $this->Cell(1, 4, ":", "", 0, 'R');
            $this->Cell($this->in2mm(4.4), 4, $this->encounter_nr, "", 0, '');


            //Bill Reference number
            $this->Cell(22.6, 4, "Bill Ref. # ", "", 0, 'L');
            $this->Cell(1, 4, ":", "", 0, 'R');
            $this->Cell(12, 4, $this->bill_ref_nr, "", 1, '');
            //added by jasper 01/04/13

			//HRN
			$this->Cell(20, 4, "HRN ", "", 0, 'L');
			$this->Cell(1, 4, ":", "", 0, 'R');
			$this->Cell($this->in2mm(4.4), 4, $row['pid'], "", 0, '');

			//Date
			$this->Cell(22.6, 4, "Date ", "", 0, '');
			$this->Cell(1, 4, ":", "", 0, 'R');
//			$this->Cell(12, 4, date('m/d/Y'), "", 1, '');
			$this->Cell(12, 4, $billdte, "", 1, '');

			//patient name
			$this->Cell(20, 4, "Name ", "", 0, 'L');
			$this->Cell(1, 4, ":", "", 0, 'R');
			$this->Cell($this->in2mm(4.4),  4, substr($name, 0, NAME_LEN), "", 0, '');

			//Department
			$this->Cell(22.6, 4, "Dept. ", "", 0, '');
			$this->Cell(1, 4, ":", "", 0, 'R');
			$this->Cell(12, 4, substr($row['dept_name'],0,DEPT_LEN), "", 1, '');

			//Address (line 1)
			$this->Cell(20, 4, "Address ", "", 0, '');
			$this->Cell(1, 4, ":", "", 0, 'R');
			$this->Cell($this->in2mm(4.4), 4, strtoupper($saddr1), "", 0, '');

			//Admitted
			$this->Cell(22.6, 4, "Admitted", "", 0, '');
			$this->Cell(1, 4, ":", "", 0, 'R');
			$this->Cell(35, 4, $admission_dte, "", 1, '');

            //Address (line 2)
			$this->Cell(20, 4, "", "", 0, '');
			$this->Cell(1, 4, ":", "", 0, 'R');
			$this->Cell($this->in2mm(4.4), 4, strtoupper($saddr2), "", 0, '');

			//Classification
//			$sClassification = $this->objBill->getClassificationDesc();
			$sMembership = $this->objBill->getMemCategoryDesc();
            //added by jasper 04/24/2013
            $classification = $this->objBill->getClassificationDesc();

//			$this->Cell(22.75, 4, "Classification", "", 0, '');
            //edited by jasper 04/24/2013
			$this->Cell(22.75, 4, (!$this->isphic ? (!$classification ? " " : "Classification") : "Membership"), "", 0, '');
			$this->Cell(1, 4, (!$this->isphic ? (!$classification ? " " : ":") : ":"), "", 0, 'R');
//			$this->Cell(30, 4, ($sClassification == '' ? "No Classification" : $sClassification), "", 1, '');
			$this->Cell(30, 4, ($this->isphic ? ($sMembership == '' ? "Not Specified" : $sMembership) : ($classification ? $classification : "No PHIC")), "", 1, '');

			//Address (line 3)
			if ($saddr3 != '') {
				$this->Cell(20, 4, "", "", 0, '');
				$this->Cell(1, 4, ":", "", 0, 'R');
				$this->Cell($this->in2mm(4.4), 4, strtoupper($saddr3), "", 1, '');
			}

			//Room #
			if ($row['room_no'] == 0) {
                if ($this->brecalc) {
                    $this->objBill->getAccommodationHist(); // set AccommodationHist
                    $this->objBill->getRoomTypeBenefits(); // set Room type Benefits
                    $this->objBill->getConfineBenefits('AC');
                }
                else {
                    $ac = unserialize($_SESSION['billobject']['ac']);
                    if (!($ac instanceof ACBill)) {
                            $var_dump("No accommodation object retrieved!");
                    }
                    $ac->assignBillObject($this->objBill);
                }

				$accArray   = $this->objBill->getAccHist();
				if (!empty($accArray)) {
					$sroom_no   = $accArray[count($accArray)-1]->getRoomNr();
					$sward_name = $accArray[count($accArray)-1]->getTypeDesc();

                    if ($this->ishousecase) {
                        $sward_name = preg_replace("/pay[\s]*ward/i", "Ward", $sward_name);
                    }
				}
				else {
					$sroom_no   = 'None';
					$sward_name = "No Accommodation";
				}

				$this->b_acchist_gathered = TRUE;
			}
			else {
				$sroom_no   = $row['room_no'];
				$sward_name = $row['ward_name'];

                if ($this->ishousecase) {
                    $sward_name = preg_replace("/pay[\s]*ward/i", "Ward", $sward_name);
                }
			}

			$sCaseType = $this->objBill->getCaseTypeDesc();

			$this->Cell(20, 4, "Room #", "", 0, '');
			$this->Cell(1, 4, ":", "", 0, 'R');
			$this->Cell(10, 4, $sroom_no, "", 0, '');

			#Last billing ...
			$lastbilldte = $this->objBill->getActualLastBillDte();
			if ( ($lastbilldte == "0000-00-00 00:00:00") && !$this->objBill->getIsCoveredByPkg() )
				$this->Cell($this->in2mm(4), 4, "( ".$sward_name." )".($sCaseType == '' ? '' : " - ".$sCaseType), "", 1 ,'');
			else {
				$this->Cell($this->in2mm(4), 4, "( ".$sward_name." )".($sCaseType == '' ? '' : " - ".$sCaseType), "", 0 ,'');

                if ( $this->objBill->getIsCoveredByPkg() ) {
                    $this->Cell(23, 4, "Package ", "", 0, '');
                    $this->Cell(1, 4, ":", "", 0, 'R');
                    $this->Cell(35, 4, $this->objBill->getPackageName(), "", 1, '');
                }
                else {
                    $this->Cell(22.6, 4, "Last Billing ", "", 0, '');
                    $this->Cell(1, 4, ":", "", 0, 'R');
                    $this->Cell(12, 4, strftime("%b %d, %Y %I:%M%p", strtotime($lastbilldte)), "", 1, '');
                }
			}
		}
	}//end of PersonInfo


	function PrintData(){
		$this->Ln(5);

		// Accommodation
		if (!$this->objBill->isERPatient()) $this->getAccommodationData();
		$this->getHospitalServiceData();   // Hospital services ( Laboratory & radiology)
		$this->getMedicinesData();         // Medicines
//		$this->getSuppliesData();          // Supplies
		$this->getOpsCharges();			   // Operation/Procedures
		$this->getMiscellaneousCharges();  // Miscellaneous Charges

	}// end of function PrintData

	function getPFDiscount($pfarea, $npf, $nclaim) {
		global $db;

		$n_discount = 0.00;
		$n_prevdiscount = 0.00;

		$area_array = array('AC', 'D1', 'D2', 'D3', 'D4');
        //edited by jasper 04/16/2013    -CONDITION SHOULD BE THE SAME WITH FUNCTION getBillAreaDiscount IN class_billing.php
		//if ($this->objBill->isCharity() && (in_array($pfarea, $area_array))) {
          if ($this->objBill->isCharity() && !$this->objBill->isMedicoLegal() && !$this->objBill->isPHIC() && (in_array($pfarea, $area_array))) {
			switch ($pfarea) {
				case 'D1':
				case 'D2':
				case 'D3':
				case 'D4':
					$n_discount = $npf - $nclaim;
                    break;
			}
		}
		else {
			$strSQL = "select fn_get_bill_discount('". $this->encounter_nr. "', '". $pfarea ."', '".$this->bill_date."') as discount";
			if ($result = $db->Execute($strSQL)) {
				if ($result->RecordCount()) {
					$row = $result->FetchRow();
					if (!is_null($row['discount'])) {
						$n_discount = $row['discount'];
					}
				}
			}

			// .... get discount rate applied to bill area of encounter while at ER, if there is one.
			if ($this->objBill->prev_encounter_nr != '') {
				$strSQL = "select fn_get_bill_discount('". $this->objBill->prev_encounter_nr. "', '". $pfarea ."', '".$this->bill_date."') as discount";
				if ($result = $db->Execute($strSQL)) {
					if ($result->RecordCount()) {
						$row = $result->FetchRow();
						if (!is_null($row['discount'])) {
							$n_prevdiscount = $row['discount'];
						}
					}
				}
			}

			$n_discount = ($n_discount > $n_prevdiscount) ? $n_discount : $n_prevdiscount;      // Return the highest discount applied.
			switch ($pfarea) {
				case 'D1':
				case 'D2':
				case 'D3':
				case 'D4':
					$n_discount *= $npf;
					break;
			}
		}
		return round($n_discount, 2);
	}

	function Professional_Fee() {
        if ($this->brecalc) {
            $this->objBill->getProfFeesList();
            $this->objBill->getProfFeesBenefits();
        }
        else {
            $pf = unserialize($_SESSION['billobject']['pf']);
            if (!($pf instanceof PFBill)) {
                    $var_dump("No PF object retrieved!");
            }
            $pf->assignBillObject($this->objBill);
        }

		$hsp_pfs_benefits = $this->objBill->getPFBenefits(); #role area

		$ndiscount = 0;
		$proffees_list = $this->objBill->proffees_list;
		$prevrole_area = '';
		if(is_array($hsp_pfs_benefits) && (count($hsp_pfs_benefits) > 0)) {
			$pfs_confine_coverage_tmp = array();
			$pfs_confine_benefits_tmp = array();

			foreach($hsp_pfs_benefits as $key=> $value) {
				if ($value->role_area == $prevrole_area) continue;
				$prevrole_area = $value->role_area;

				$totalCharge = $this->objBill->getTotalPFCharge($value->role_area);

				reset($proffees_list);
                if ($this->brecalc) {
                    $this->objBill->initProfFeesCoverage($value->role_area);
                }


				$role_desc = substr($value->role_desc, 23, strlen($value->role_desc));


				$bShow = count($proffees_list) > 1;

				$ndays = 0;
				$nrvu  = 0;
				$area_pf = 0;
				$area = $value->role_area;
                $coverage_sum = 0.00;

					$this->objBill->getPerHCareCoverage();

					// Save the computed confinement pf coverage ...
					$pfs_confine_coverage_tmp[$area] = $this->objBill->pfs_confine_coverage[$area];
					$pfs_confine_benefits_tmp[$area] = $this->objBill->pfs_confine_benefits[$area];

					$this->objBill->getPerDrPFandClaims($area);

					// Restore the computed confinement pf coverage ...
					$this->objBill->pfs_confine_coverage[$area] = $pfs_confine_coverage_tmp[$area];
					$this->objBill->pfs_confine_benefits[$area] = $pfs_confine_benefits_tmp[$area];

					#Display list of doctors in every role area
					foreach($proffees_list as $key=>$profValue){
						if($value->role_area == $profValue->role_area) {
                            if ($this->brecalc) {
                                $opcodes = $profValue->getOpCodes();
                                if ($opcodes != '') {
                                   $opcodes = explode(";", $opcodes);
                                }
                                if (is_array($opcodes)) {
                                    foreach($opcodes as $v) {
                                        $i = strpos($v, '-');
                                        if (!($i === false)) {
                                            $code = substr($v, 0, $i);
                                            if (!$profValue->getIsExcludedFlag()) {
                                                $this->objBill->getConfineBenefits($value->role_area, $profValue->getDrNr(), $profValue->getRoleLevel(), false, 0, $code);
                                            }
                                            if ($this->objBill->getIsCoveredByPkg() && !$profValue->getIsExcludedFlag()) break;
                                        }
                                    }
                                }
                                else
                                    if (!$profValue->getIsExcludedFlag()) {
                                        $this->objBill->getConfineBenefits($value->role_area, $profValue->getDrNr(), $profValue->getRoleLevel());
                                    }
                            }

							$drName = $profValue->dr_first." ".$profValue->dr_mid.". ".$profValue->dr_last;
							$drCharge = $profValue->dr_charge;

							$claim = $this->getDrClaim($profValue->getDrNr(), $value->role_area, $this->objBill->pf_claims);
							if ($bShow) {
								$npfdiscount = $this->getPFDiscount($value->role_area, $drCharge, $claim);
							}

              $coverage = (!$profValue->getIsExcludedFlag()) ? $claim : 0.00;

							$coverage_sum += $coverage;
                        }
					} # end foreach proffees_list

					$ndiscount = $this->objBill->getBillAreaDiscount($value->role_area);

				$this->totalCharge[PF_AREA] += $totalCharge;
				$this->totalDiscount[PF_AREA] += $ndiscount;
				$this->totalCoverage[PF_AREA] += $coverage_sum;
				$this->totalExcess[PF_AREA] += ($totalCharge-$ndiscount-$coverage_sum);

			}#1st foreach
		}else{

		}

	}#end of function Professional_Fee

	function getDrClaim($dr_nr, $role_area, $drclaims) {
		$claim = 0;
		foreach($drclaims as $k=>$v) {
			if (($v->getDrNr() == $dr_nr) && ($v->getRoleArea() == $role_area)) {
				$claim = $v->getDrClaim();
			}
		}
		return $claim;
	}

	function Sub_Total(){
		$totalcharge = 0;
		foreach($this->totalCharge as $key=>$v) {
			if ($key != PF_AREA)
				$totalcharge += $v;
		}

		# Discount ...
		$t_discount = 0;
		foreach($this->totalDiscount as $key=>$v) {
			if ($key != PF_AREA)
				$t_discount += $v;
		}

		$totalcoverage = 0;
		foreach($this->totalCoverage as $key=>$v) {
			if ($key != PF_AREA)
				$totalcoverage += $v;
		}

		$totalexcess = 0;
		foreach($this->totalExcess as $key=>$v) {
			if ($key != PF_AREA)
				$totalexcess += $v;
		}


	}//end of function Sub_Total

	function DisplayData(){
		$this->SetFont("Times", "", "11");
		$this->Ln(4);
		$this->Cell($this->in2mm(GEN_COL02), 4, "T O T A L", "", 0, '');
		$totalExcess = 0;

		$deposit = $this->objBill->getPreviousPayments();
		$totalOBpayments = $this->objBill->getOBAnnexPayment();
        $deposit += $totalOBpayments;

		foreach($this->totalExcess as $v)
			$totalExcess += round($v, 0);

		$total = $totalExcess - $deposit;

		$totalDiscount=$this->objBill->getTotalDiscount();
		$totalDue = $total - $totalDiscount ;

		$this->Cell(COL06_WIDTH, 4, number_format($total, 2, '.', ','), "", 1, 'R');

		if ($this->objBill->isSponsoredMember() || $this->objBill->checkIfPHS() || $this->objBill->isHSM()) {
			$this->SetFont("Times", "", "11");
			$this->Ln(4);
			if($this->objBill->isHSM()) {
            	$label = "HOSPITAL SPONSORED MEMBER";
            } elseif ($this->objBill->isSponsoredMember()) {
            	$label = "SPONSORED - NO BALANCE BILLING";
            } else {
            	$label = "INFIRMARY DISCOUNT";
            }
			$this->Cell($this->in2mm(GEN_COL02), 4, $label, "", 0, '');
			$this->Cell(COL06_WIDTH, 4, number_format($total, 2, '.', ','), "", 1, 'R');
			$this->SetFont("Times", "B", "11");
			$this->Ln(4);
			$this->Cell($this->in2mm(GEN_COL02), 4, "Total Amount Due", "", 0, '');
			$this->Cell(COL06_WIDTH, 4, "0.00", "", 1, 'R');
		}else{
			$this->SetFont("Times", "", "11");
			$this->Ln(4);
			$this->Cell($this->in2mm(GEN_COL02), 4, "Less Classification Discount", "", 0, '');
			$this->Cell(COL06_WIDTH, 4, number_format($totalDiscount, 2, '.', ','), "", 1, 'R');
			$this->SetFont("Times", "B", "11");
			$this->Ln(4);
			$this->Cell($this->in2mm(GEN_COL02), 4, "Total Amount Due", "", 0, '');
			$this->Cell(COL06_WIDTH, 4, number_format($totalDue, 2, '.', ','), "", 1, 'R');
        }

        

		$this->Ln(16);
		$this->SetFont("Times", "B", "11");
		$this->Cell($this->in2mm(GEN_COL02), 4, "Social Worker: ".$_SESSION['sess_user_name'], "", 0, '');
	}

	function Totals(){

		$totalActualCharge = 0;
		$t_discount        = 0;
		$totalMedicare     = 0;
		$totalExcess       = 0;

		foreach($this->totalCharge as $v)
			$totalActualCharge += round($v, 2);

		foreach($this->totalDiscount as $v)
			$t_discount += round($v, 2);

		foreach($this->totalCoverage as $v)
			$totalMedicare += round($v, 2);

		$totalExcess = $totalActualCharge - $t_discount - $totalMedicare;

        $prevbill_amt = $this->PreviousBill($this->encounter_nr, $this->bill_ref_nr);
		$this->Less($totalExcess);
	}//end of function Totals()

    //added by jasper 04/08/2013
    function PreviousBill ($enc_nr, $bill_nr) {
        //echo $enc_nr . "//" . $bill_nr;
        $objbillinfo = new BillInfo();
        $tot_prevbill_amt = 0;
        $result = $objbillinfo->getPreviousBillAmt($enc_nr, $bill_nr);
        //echo $result;
        if ($result) {
            while ($row = $result->FetchRow()) {
                $n_bill = 0;
                if (!empty($row["total_charge"])) $n_bill = $row["total_charge"];
                if (!empty($row["total_coverage"])) $n_bill -= $row["total_coverage"];
                if (!empty($row["total_computed_discount"])) $n_bill -= $row["total_computed_discount"];
                if (!empty($row["total_discount"]) && ($n_bill > 0)) $n_bill -= ($n_bill * $row["total_discount"]);
                $tot_prevbill_amt += $n_bill;
            }
        }
        //echo $enc_nr . "//" . $bill_nr . "//" . $tot_prevbill_amt;
        $this->prev_bill_amt = $tot_prevbill_amt;
    }
    //added by jasper 04/08/2013

	function Less($totalExcess){
		# partial payment
		$deposit = $this->objBill->getPreviousPayments();

		//discounts
		$totalDiscount = 0;
        //added by jasper 03/27/2013
        //NO BALANCE BILLING OR PHS (Infirmary Discount)
        if ($this->objBill->isSponsoredMember() || $this->objBill->checkIfPHS()) {

            $netExcess = ($totalExcess + $this->prev_bill_amt) - (round($deposit, 0) + round($totalDiscount, 0) + round($this->prev_bill_amt, 0));
            $netcharges  = 0.00;
        } else {
            $netcharges = ($totalExcess + $this->prev_bill_amt) - (round($deposit, 0) + round($totalDiscount, 0));
        }

		$netcharges = $totalExcess - (round($deposit, 0) + round($totalDiscount, 0));
	}

	function getMedicinesData(){
        if ($this->brecalc) {
            $this->objBill->getMedicineBenefits();
            $this->objBill->getConfineBenefits('MS', 'M');
        }
        else {
            $md = unserialize($_SESSION['billobject']['md']);
            if (!($md instanceof MDBill)) {
                    $var_dump("No drugs and meds object retrieved!");
            }
            $md->assignBillObject($this->objBill);
        }

		$totalMedConfineCoverage = $this->objBill->getAppliedMedsCoverage();
		$medBenefitsArray = $this->objBill->getMedConfineBenefits();
		$ndiscount        = $this->objBill->getBillAreaDiscount('MS','M');

		$n = 0;

		if (is_array($medBenefitsArray)) {
			$bShow = count($medBenefitsArray) > 1;

			foreach($medBenefitsArray as $key=>$value) {
				$acPrice = $value->item_charge;
				$price = $value->item_price;

				if ($this->IsDetailed) {

					$stmp = ($value->getItemQty() > 1 ? "s" : "");
				}
			}
		}

		$TotalMedCharge = $this->objBill->getTotalMedCharge();
		$totalExcess = $TotalMedCharge - $ndiscount - $totalMedConfineCoverage;

		$this->totalCharge[MD_AREA] = $TotalMedCharge;
		$this->totalDiscount[MD_AREA] = $ndiscount;
		$this->totalCoverage[MD_AREA] = $totalMedConfineCoverage;
		$this->totalExcess[MD_AREA] = $totalExcess;

	}//end of function getMedicinesData

	function getHospitalServiceData() {
        if ($this->brecalc) {
            $this->objBill->getServiceBenefits();
            $this->objBill->getConfineBenefits('HS');
        }
        else {
            $hs = unserialize($_SESSION['billobject']['hs']);
            if (!($hs instanceof HSBill)) {
                    $var_dump("No hospital services object retrieved!");
            }
            $hs->assignBillObject($this->objBill);
        }

		$total_labchrg   = 0;
		$total_radchrg   = 0;
		$total_otherchrg = 0;

		$hsp_services = $this->objBill->getSrvBenefits();

        $totalServConfineCoverage = $this->objBill->getAppliedHSCoverage();
		$totalServCharge = $this->objBill->getTotalSrvCharge();
		$ndiscount       = $this->objBill->getBillAreaDiscount('HS');

		if (is_array($hsp_services) && (count($hsp_services) > 0)) {
			foreach ($hsp_services as $key=>$hsValue) {
				$servPrice  = $hsValue->getServPrice();
				$servCharge = $hsValue->getServQty() * $hsValue->getServPrice();

				if ($hsValue->getServProvider()=='LB') {
					$total_labchrg += $servCharge;
				}
			}

			reset($hsp_services);


			foreach ($hsp_services as $key=>$hsValue) {
				$servPrice  = $hsValue->getServPrice();
				$servCharge = $hsValue->getServQty() * $hsValue->getServPrice();

				if ($hsValue->getServProvider()=='RD') {
					$total_radchrg += $servCharge;
				}
			}

			reset($hsp_services);

			foreach ($hsp_services as $key=>$hsValue) {
				$servPrice  = $hsValue->getServPrice();
				$servCharge = $hsValue->getServQty() * $hsValue->getServPrice();

				if (($hsValue->getServProvider() == 'OA') || ($hsValue->getServProvider() == 'SU') || ($hsValue->getServProvider() == 'MS')) {
					$total_otherchrg += $servCharge;
				}
			}



			if (isset($_GET['fix']))
				$totalServConfineCoverage = $totalServCharge;

			$excess = $totalServCharge - $ndiscount - $totalServConfineCoverage;


		}
		else {

		}


		$this->totalCharge[HS_AREA]   = $totalServCharge;
		$this->totalDiscount[HS_AREA] = $ndiscount;
		$this->totalCoverage[HS_AREA] = $totalServConfineCoverage;
		$this->totalExcess[HS_AREA]   = $excess;

	}// end of function LaboratoryData

	#added by VAN 02-13-08

	function getRoomTypeAttachedInfo($type_nr, $src, $accHistArray, &$typeDesc, &$sRooms) {
		$sDesc  = '';
		$sRooms = '';
		foreach ($accHistArray as $key => $accHist) {
			if (($accHist->type_nr == $type_nr) && ($accHist->getSource() == $src)) {
				if ($sDesc == '') $sDesc = $accHist->getTypeDesc();
				$pos = strpos($sRooms, $accHist->getRoomNr());
				if ($pos === false) {
					if ($sRooms != '') $sRooms .= ', ';
					$sRooms .= $accHist->getRoomNr();
				}
			}
		}
		$typeDesc = $sDesc;
	}

	function getAccommodationData() {
        if ($this->brecalc && !$this->b_acchist_gathered) {
            $this->objBill->getAccommodationHist(); // set AccommodationHist
            $this->objBill->getRoomTypeBenefits(); // set Room type Benefits
            $this->objBill->getConfineBenefits('AC');
        }
        else {
            $ac = unserialize($_SESSION['billobject']['ac']);
            if (!($ac instanceof ACBill)) {
                    $var_dump("No accommodation object retrieved!");
            }
            $ac->assignBillObject($this->objBill);
        }

		$accHistArray= $this->objBill->getAccHist(); //get accommodation object
		$accBenefitsArray = $this->objBill->getRmTypeBenefits(); //get accommodation benefits coverage
		$total_confine_coverage = $this->objBill->getAccConfineCoverage();
		$ndiscount       = $this->objBill->getBillAreaDiscount('AC');

		#Display Accommodation arguments
		$total = 0;

		if (is_array($accBenefitsArray) && (count($accBenefitsArray) > 0)) {
			foreach ($accBenefitsArray as $key => $accBen) {
				$total_charge = sprintf('%01.2f', $accBen->getActualCharge()); //Actual Price
				$days_count = $accBen->days_count;
				$excess_hr = $accBen->excess_hours;

				$total += $total_charge;

				$this->getRoomTypeAttachedInfo($accBen->type_nr, $accBen->getSource(), $accHistArray, $type_desc, $sRooms);

				if ($days_count>1)
					$daylabel = 'days';
				else
					$daylabel = 'day';

				if ($excess_hr>1)
					$timelabel = 'hrs';
				else
					$timelabel = 'hr';

                if ($this->ishousecase) {
                    $type_desc = preg_replace("/pay[\s]*ward/i", "Ward", $type_desc);
                }

			}
		}

		$excess = $total - $ndiscount - $total_confine_coverage;

		$this->totalCharge[AC_AREA] = $total;
		$this->totalDiscount[AC_AREA] = $ndiscount;
		$this->totalCoverage[AC_AREA] = $total_confine_coverage;
		$this->totalExcess[AC_AREA] = $excess;
	}// end of function getAccommodationData

	function getOpsCharges() {

        if ($this->brecalc) {
             $this->objBill->getOpBenefits();
             $this->objBill->getConfineBenefits('OR');
        }
         else {
            $op = unserialize($_SESSION['billobject']['op']);
            if (!($op instanceof OPBill)) {
                    $var_dump("No operating room object retrieved!");
            }
            $op->assignBillObject($this->objBill);
        }

		$hspOpsList       = $this->objBill->getOpsConfineBenefits();
		$totalOpsCharge   = $this->objBill->getTotalOpCharge();
		$ndiscount        = $this->objBill->getBillAreaDiscount('OR');

		if (is_array($hspOpsList) && (count($hspOpsList) > 0)) {
			$bShow = count($hspOpsList) > 1;

			foreach ($hspOpsList as $key => $opsValue) {
				$opsCharge  = number_format($opsValue->getOpCharge(), 2, '.', ',');

			}
		}

		$totalOpsCoverage = $this->objBill->getOpsConfineCoverage();
		$totalExcess = $totalOpsCharge - $ndiscount - $totalOpsCoverage;

		$this->totalCharge[OP_AREA]   = $totalOpsCharge;
		$this->totalDiscount[OP_AREA] = $ndiscount;
		$this->totalCoverage[OP_AREA] = $totalOpsCoverage;
		$this->totalExcess[OP_AREA]   = $totalExcess;
	}

	function getMiscellaneousCharges() {
        if ($this->brecalc) {
            $this->objBill->getMiscellaneousBenefits();
            $this->objBill->getConfineBenefits('XC');
        }
        else {
            $xc = unserialize($_SESSION['billobject']['xc']);
            if (!($xc instanceof XCBill)) {
                    $var_dump("No miscellaneous charges object retrieved!");
            }
            $xc->assignBillObject($this->objBill);
        }

		$hspMscList = $this->objBill->getMiscBenefits(); //listing

		$totalMscConfineCoverage = $this->objBill->getMscConfineCoverage();
		$totalMscCharge          = $this->objBill->getTotalMscCharge();
		$ndiscount               = $this->objBill->getBillAreaDiscount('XC');

		$n = 0;

		if (is_array($hspMscList) && (count($hspMscList) > 0)) {
			$bShow = count($hspMscList) > 1;

			foreach ($hspMscList as $key => $mscValue) {
				$mscCharge  = number_format($mscValue->getTotalMiscChrg(), 2, '.', ',');
			}
		}

		$totalExcess = $totalMscCharge - $ndiscount - $totalMscConfineCoverage;

		$this->totalCharge[XC_AREA]   = $totalMscCharge;
		$this->totalDiscount[XC_AREA] = $ndiscount;
		$this->totalCoverage[XC_AREA] = $totalMscConfineCoverage;
		$this->totalExcess[XC_AREA]   = $totalExcess;
	}

	function getPersonInfo($encounter=''){
		global $db;

		if(!empty($encounter)){
			$this->encounter_nr = $encounter;
		}

		$sql = "SELECT ce.*, cp.name_first, cp.name_middle, cp.name_last,
							cp.date_birth,
						sb.brgy_name, cp.street_name, sm.mun_name, sm.zipcode,
						sp.prov_name, sr.region_name, sr.region_desc,  cd.id, cd.name_formal as dept_name,
						ce.current_room_nr as room_no,cw.ward_id, cw.name as ward_name
					FROM (care_encounter AS ce
						INNER JOIN care_person AS cp ON ce.pid = cp.pid)
							left JOIN seg_barangays AS sb ON cp.brgy_nr = sb.brgy_nr
							left JOIN seg_municity AS sm ON cp.mun_nr = sm.mun_nr
									left JOIN seg_provinces AS sp ON sm.prov_nr = sp.prov_nr
									left JOIN seg_regions AS sr ON sp.region_nr = sr.region_nr
							left JOIN care_department AS cd ON cd.nr = ce.current_dept_nr
							left JOIN care_ward AS cw ON ce.current_ward_nr = cw.nr
					WHERE ce.encounter_nr ='".$this->encounter_nr."'";

		if($this->personData = $db->Execute($sql)){
			if($this->personData->RecordCount()){
				return $this->personData;
			}else{
				return FALSE;
			}
		}else{
			echo 'SQL - '.$sql;
		}
	}// end of getPersonInfo

	function ReportOut(){
		$this->Output();
	}

	function trimAddress($street, $brgy, $mun, $prov, $zipcode, &$s_addr1, &$s_addr2, &$s_addr3){
		$address = trim($street);
		$address1 = (!empty($address) && !empty($brgy)) ?  trim($address.", ".$brgy) : trim($address." ".$brgy);
		$s_addr1 = $address1;

//		$address2 =  (!empty($address1) && !empty($mun)) ? trim($address1.", ".$mun) : trim($address1." ".$mun);
		$address2 = trim($mun);
		$address3 =  (!empty($address2) && !empty($zipcode))? trim($address2." ".$zipcode) : $address2." ";

		$address4 = (!empty($address3) && !empty($prov))? trim($address3.", ".$prov) : trim($address3." ".$prov);
		$s_addr2  = $address4;
		$s_addr3  = '';

//		return $address4;
	}// end of  function trimAddress

	function setEncounter_nr($encounter){
		$this->encounter_nr = $encounter;
	}

	/*function setObjBilling(){
		$this->objBill = new Billing($this->encounter_nr);
	}*/

	function setFontSize($size){
		$this->DEFAULT_FONTSIZE = $size;
	}

	function setFontType($type){
		$this->DEFAULT_FONTTYPE = $type;
	}

	function setFontStyle($style){
		$this->DEFAULT_FONTSTYLE = $style;
	}

	function setBorder($border){
		$this->WBORDER = $border;
	}

	function setAlignment($alignment){
		$this->ALIGNMENT = $alignment;
	}

	function setNewLine($newline){
		$this->NEWLINE = $newline;
	}

	function setReportTitle($title){
		$this->reportTitle = $title;
	}

	function in2mm($inches){
//		return $inches * (0.35/(1/72));
				return $inches * 25.4;
	}
 
 }//end of class Bill_Pdf

# ----------------------------------------------------------------------------------------

if(isset($_GET['pid']) && $_GET['pid']) $pid = $_GET['pid'];
if(isset($_GET['encounter_nr']) && $_GET['encounter_nr']) $encounter_nr = $_GET['encounter_nr'];

# --- Added by LST 03102008 -- to make bill date consistent with bill date in browser window ...
if (isset($_GET['from_dt']) && $_GET['from_dt'])
	$frm_dte = strftime("%Y-%m-%d %H:%M:%S", $_GET['from_dt']);
else
	$frm_dte = "0000-00-00 00:00:00";

if (isset($_GET['bill_dt']) && $_GET['bill_dt'])
//	$bill_dte = $_GET['bill_dt'];
	$bill_dte = strftime("%Y-%m-%d %H:%M:%S", $_GET['bill_dt']);
else
	$bill_dte = "0000-00-00 00:00:00";

if (isset($_GET['nr']))
		$old_bill_nr = $_GET['nr'];
else
		$old_bill_nr = '';

//Instantiate BillPDF class
$pdfBill =  new BillPDF($encounter_nr, $bill_dte, $frm_dte, $old_bill_nr, (isset($_GET['rcalc']) && ($_GET['rcalc'] == '1')), $_GET['deathdate']);

$encobj = new Encounter();
$pdfBill->isphic = $encobj->isPHIC($encounter_nr);
$pdfBill->ishousecase = $encobj->isHouseCase($encounter_nr);

$pdfBill->objBill->getAccommodationType();

$s_accommodation = strtoupper($pdfBill->objBill->getAccommodationDesc());
$pdfBill->setReportTitle(($pdfBill->IsDetailed ? "DETAILED " : "")."STATEMENT OF ACCOUNT".($s_accommodation == '' ? " - NO ACCOMMODATION" : ($pdfBill->ishousecase ? "" : " - ".$s_accommodation)));
$pdfBill->ReportTitle();

//print patient informatin
$pdfBill->PersonInfo();

//print data
$pdfBill->PrintData();
$pdfBill->Sub_Total();
$pdfBill->Professional_Fee();

$pdfBill->Totals();
$pdfBill->DisplayData();
//print to pdf format
$pdfBill->ReportOut();
?>
