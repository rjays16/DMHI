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
require_once($root_path.'include/care_api_classes/billing/class_billing_new.php');
require_once($root_path.'include/care_api_classes/billing/class_billareas.php');

require_once($root_path.'include/care_api_classes/billing/class_ops_new.php');
require_once($root_path.'include/care_api_classes/dialysis/class_dialysis.php');
require_once($root_path.'include/care_api_classes/class_ward.php');
require_once($root_path.'include/care_api_classes/class_person.php');
require_once($root_path."include/care_api_classes/class_caserate_icd_icp.php");

include_once($root_path.'include/care_api_classes/class_personell.php');
include_once($root_path.'include/care_api_classes/class_department.php');

#added by VAN 04-24-2009
require_once($root_path.'include/care_api_classes/class_labservices_transaction.php');

define('GEN_COL01', 4);         // in mm.
define('GEN_COL02', 2);         // in inches.
define('GEN_COL02_D', 8.25);        // in mm.
define('GEN_COL02_D2', 11.75);  // in mm.
define('GEN_COL02_D3', GEN_COL02_D2 + 3.5); // in mm.

define('COL_MID', 2);

define('COL03_WIDTH', 28);
define('COL04_WIDTH', 24);
define('COL05_WIDTH', 31);
define('COL06_WIDTH', 26);
define('COL07_WIDTH', 24);

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
    var $icdcode; // added by Jan Chris 10/18/2018
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

    #added by JAN CHRIS 12/02/2019
    var $soa_caserate = array();
    var $soa_diagnosis = array();
    var $soa_rvs = array();

    var $objBill; //Billing object

    var $IsDetailed;
    var $bill_date;

    var $head_name;
    var $head_position;

    var $clerk_name;
    var $clerk_italized;

    var $b_acchist_gathered = FALSE;

    var $brecalc = false;

    var $bill_nr;
    var $hdata;
    /*
     * Constructor
     * @param string encounter_nr
     */
#added by daryl
    var $results_insurance = array();
    var $result_firmname;
    var $result_acc;
    var $result_med;
    var $result_msc;
    var $result_ops;
    var $result_serv;
    var $result_srv;
    var $result_sup;
    var $result_total;
    var $result_totalPF;
    var $countss = 0;

    // var $d1_total_discount;
    // var $d2_total_discount;
    // var $d3_total_discount;
    // var $d4_total_discount;

    var $d1_total_excess;
    var $d2_total_excess;
    var $d3_total_excess;
    var $d4_total_excess;

    var $pf_total_total_discount;
    var $pf_total_total_excess;
  
    var $hosp_total_excess;
    var $hosp_total_discount;

    var $total_total_discount;
    var $total_total_excess;
    var $temp_d2_excess;

    var $result_totalPF_d1_total;
    var $result_totalPF_d2_total;
    var $result_totalPF_d3_total;
    var $result_totalPF_d4_total;
    var $result_totalPF_total_total;

    var $result_totalPF_d1;
    var $result_totalPF_d2;
    var $result_totalPF_d3;
    var $result_totalPF_d4;
    var $sum_pf = 0;

    var $d1_totalDiscount;
    var $d2_totalDiscount;
    var $d3_totalDiscount;
    var $d4_totalDiscount;
    var $total_totalDiscount;

    var $total_HCI_PF;

    var $temp_excess;

    var $exess_all_d1;
    var $exess_all_d2;
    var $exess_all_d3;
    var $exess_all_d4;

    var $exess_all = 0;

    var $total_pf_charge = 0;
    var $total_phic_pf = 0;
    var $total_pf_excess = 0;
    var $total_dr_cover_d1 = 0;
    var $deposit_amount = 0;
    var $copay_amount = 0;

    var $total_pf_coverage_phic = 0;

    var $global_dr_name = "";
    var $global_dr_nr = "";
    var $global_dr_area = "";

    var $global_sc_discount = 0;
    var $if_pat_phic = 0;
    var $if_pat_hmo = 0;

    //added by janken 12/29/2014
    var $company_charges = 0;




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

         $pg_size = array($this->in2mm(8.5), $this->in2mm(13));                 // Default to long bond paper --- modified by LST - 04.13.2009
         //modified by ken change default to short according to dmhi billing section
         $this->FPDF("P","mm", $pg_size);
         $this->AliasNbPages();
         $this->AddPage("P");
//       $this->SetTopMargin(1);

         $this->DEFAULT_FONTTYPE = "Times";
         $this->DEFAULT_FONTSIZE = 11;
         $this->DEFAULT_FONTSTYLE = '';
         $this->NEWLINE = 1;
         $this->death_date = $deathdate;

         //Instantiate billing object
         if ($this->brecalc) {
            $this->objBill = new Billing();
            $this->objBill->setBillArgs($this->encounter_nr, $bill_dt, $bill_frmdt,$deathdate,$old_bill_nr);
            //$this->objBill->applyDiscounts(); //TODO1
         }
         else{
            $this->objBill = new Billing();
            $this->objBill->setBillArgs($this->encounter_nr, $bill_dt, $bill_frmdt,$deathdate,$old_bill_nr);
         }
         $this->bill_date = $bill_dt;

         //get first the confinement type
         $this->objBill->getConfinementType();

         //added by jasper 03/18/2013
         if (!($this->objBill->isFinal())) {
            $this->Image('../../gui/img/logos/tentativebill.jpg',30, 50, 150,150);
         }
         //added by jasper 03/18/2013
         $data['encounter'] = $this->encounter_nr;
         $this->bill_nr = $this->objBill->getbillnr($data);

        $this->hdata = $this->getTotals();//added by Nick, 1/4/2014
        // echo json_encode($this->hdata);
        // exit();
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
            $row['hosp_name']    = "DAVAO MEDICAL CENTER";
            $row['hosp_addr1']   = "JICA Bldg., JP Laurel Avenue, Davao City";

        }

        $this->Image('../../gui/img/logos/dmhi_logo.jpg',20,10,20,20);

//      $this->Image($root_path.'gui/img/logos/dmc_logo.jpg',20,10,20,20);
        $this->SetFont("Times", "B", "10");
        $this->Cell(0, 4, $row['hosp_country'], 0, 1,"C");
        $this->Cell(0, 4, $row['hosp_agency'], 0, 1 , "C");
        $this->Cell(0, 4, $row['hosp_name'], 0, 1, "C");

        $this->SetFont("Times", "", "10");
        $this->Cell(0, 4, $row['hosp_addr1'], 0, 1, "C");
        $this->SetFont("Times", "", "9");
        $this->Cell(0, 4, 'Telephone # 291 - 0229 Fax # 291 - 0230', 0, 1, "C");
        $this->Ln(7);
#---------------------- LST  - 06-21-2008

    }// end of Bill_Header function

    //Page footer
    function Footer() {
        //Go to 1.5 cm from bottom
        $this->SetY(-15);
        $this->SetFont('Times','I',8);
        //Page number
        $this->Cell(0, 10, 'Page '.$this->PageNo().' of {nb}',0,0,'C');
        // $this->Cell(0, 4, "DMHI-F-BIL-11", "", 0, 'R');
    }

    function ReportFooter() {
                #added by VAN 04-24-2009
                $labObj = new SegLab();

        $this->getBillingHead();
        $this->getBillingClerk($_SESSION['sess_temp_userid'],$_GET['encounter_nr'],$_GET['nr']);

        #italized if no save bill yet
        if ($this->clerk_italized)
            $this->SetFont('Times','I',10);
        else
            $this->SetFont($this->fontType, $this->fontStyle, $this->fontSize);

        // Signatories ...
        $xx = $this->getX();
        $yy = $this->getY();
        $this->Ln(10);
        $this->Cell(4, 4, "", "", 0, '');
        // $this->Cell(FOOTER_COL01, 4, "", "", 0, '');
        // $this->Cell(20, 4, "", "", 0, '');
        $this->Cell(FOOTER_COL02, 4, "Prepared by:", "", 1, '');
        $this->Ln(4);
        $this->Cell(4, 4, "", "", 0, '');
        // $this->Cell(FOOTER_COL01, 4, $this->head_name, "", 0, 'C');
        // $this->Cell(20, 4, "", "", 0, '');
        $this->Cell(FOOTER_COL02, 4,  $_SESSION['sess_user_name'], "", 1, 'C');

        $this->Cell(4, 4, "", "", 0, '');
        $this->Cell(FOOTER_COL01, 4, "Billing Clerk & Signature", "T", 0, 'C');
        // $this->Cell(20, 4, "", "", 0, '');
        // $this->Cell(FOOTER_COL02, 4, "Billing Clerk", "T", 1, 'C');

        // Confirmation ...
        //added by pol 07/24/2013
        //fix for bug # 308
        $this->setX($xx);
        $this->setY($yy-3);
        $this->Ln(14);
        // $this->Cell(4, 4, "", "", 0, '');
        //end by pol

        $this->Cell(4, 4, "", "", 0, '');
        $this->Cell(FOOTER_COL01, 4, "", "", 0, '');
        $this->Cell(20, 4, "", "", 0, '');
        $this->SetFont("Times", "I", "10");
        $this->Cell(FOOTER_COL02, 4, "Confirmed by:", "", 1, '');



        $this->Ln(8);
        $this->Cell(4, 4, "", "", 0, '');
        $this->Cell(FOOTER_COL01, 4, "", "", 0, '');
        $this->Cell(20, 4, "", "", 0, '');
        $this->SetFont('Times','',10);
        $this->Cell(FOOTER_COL02, 4, "Signature over Printed Name", "T", 1, 'C');
        
        $this->Ln(8);
        $this->Cell(4, 4, "", "", 0, '');
        $this->Cell(FOOTER_COL01, 4, "", "", 0, '');
        $this->Cell(20, 4, "", "", 0, '');
        $this->SetFont('Times','',10);
        $this->Cell(FOOTER_COL02, 4, "Relationship", "T", 1, 'C');
        $saccom = (!$this->ishousecase) ? strtoupper($this->objBill->getAccomodationDesc() /*TODO9*/) : "";

        $this->Ln(8);
        $this->Cell(4, 4, "", "", 0, '');
        $this->Cell(FOOTER_COL01, 4, "", "", 0, '');
        $this->Cell(20, 4, "", "", 0, '');
        $this->SetFont('Times','',10);
        $this->Cell(FOOTER_COL02, 4, "Contact Number", "T", 1, 'C');
        
        $nypos = $this->GetY();
        //edited by VAN 02-14-2013
        /*if (!(strpos($saccom, "PAYWARD") === false)){
            $this->SetY(-1 * $this->in2mm(2.2));
        }else
            $this->SetY(-1 * $this->in2mm(2));*/
        $this->SetY((-1 * $this->in2mm(1.66))-15);
        $ntmp = $this->GetY();
        if ($nypos >= $ntmp) $this->AddPage("P");

        /*if (!(strpos($saccom, "PAYWARD") === false))
            $this->SetY(-1 * $this->in2mm(2.2));
        else
            $this->SetY(-1 * $this->in2mm(2));*/
        // $this->SetY(-1 * $this->in2mm(1.66));

        $this->Cell(0, 1, "", "T", 1, 'C');
        $this->Cell(0, 4, $saccom." PATIENT CLEARANCE", "", 1, 'C');
        $this->Ln(2);

        $this->Cell(4, 2, "", "", 0, '');
        $this->Cell(FOOTER_COL01, 4, "CASE #: ".$this->encounter_nr, "", 0, 'L');
        $this->Cell(12, 2, "", "", 0, '');

//      $row = $this->personData->FetchRow();
        $row = $this->personData;
        $name = strtoupper($row['name_last'].",  ".$row['name_first']." ".$row['suffix']." ".$row['name_middle']);
        $this->Cell(FOOTER_COL02, 4, "PATIENT: ".$name, "", 1, 'L');

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

        #$this->Cell(8, 4, "", "", 0, '');
        $this->Cell($pharmaXval, 4, "", "", 0, '');
        $this->Cell(FOOTER_COL02*0.325, 4, (strpos($saccom, "PAYWARD") === false ? "LINEN: " : "PHARMACY: "), "", 0, '');
        $this->Cell(4, 4, "", "", 0, '');
        #$this->Cell(FOOTER_COL01*0.675, 4, str_repeat('_', 30), "", 1, '');
        $this->Cell(FOOTER_COL01*0.675, 4, str_repeat('_', 30), "", $pharmaNextLine, '');

        if ($hasbloodborrowed)
                $this->Ln(2);

        #$this->Cell(4, 4, "", "", 0, '');
        $this->Cell($cashierXval, 4, "", "", 0, '');
        $this->Cell(FOOTER_COL01*0.325, 4, "CASHIER: ", "", 0, '');
        $this->Cell(4, 4, "", "", 0, '');
        #$this->Cell(FOOTER_COL01*0.675, 4, str_repeat('_', 30), "", 0, '');
        $this->Cell(FOOTER_COL01*0.675, 4, str_repeat('_', 30), "", $cashierNextLine, '');

        if (!$hasbloodborrowed)
            $this->Ln(2);


        #$this->Cell(8, 4, "", "", 0, '');
        $this->Cell($nurseXval, 4, "", "", 0, '');
        $this->Cell(FOOTER_COL02*0.325, 4, "NURSE ON DUTY: ", "", 0, '');
        $this->Cell(4, 4, "", "", 0, '');
        $this->Cell(FOOTER_COL01*0.675, 4, str_repeat('_', 30), "", $nurseNextLine, '');

#added by daryl
         //04/25.2014
#added by daryl
        $this->Cell($cashierXval, 4, "", "", 0, '');
        $this->Cell((FOOTER_COL02*0.325), 4, "Date", "", 0, '');
        $this->Cell(4, 4, "", "", 0, '');
        $this->Cell((FOOTER_COL01*0.675)-20, 4, str_repeat('_', 30), "", $nurseNextLine, '');
     #----------
        $this->Cell(4, 4, "", "", 1, '');


         $this->Ln(2);


        $this->Cell($nurseXval, 4, "", "", 0, '');
        $this->Cell(FOOTER_COL02*0.325, 4, "PHIC: ", "", 0, '');
        $this->Cell(4, 4, "", "", 0, '');
        $this->Cell(FOOTER_COL01*0.675, 4, str_repeat('_', 30), "", $nurseNextLine, '');

        $this->Cell($cashierXval, 4, "", "", 0, '');

  $this->Cell((FOOTER_COL02*0.325), 4, "Time", "", 0, '');
        $this->Cell(4, 4, "", "", 0, '');
        $this->Cell(FOOTER_COL01*0.675, 4, str_repeat('_', 30), "", $nurseNextLine, '');
     #----------

        if (!(strpos($saccom, "PAYWARD") === false)) {
            if ($hasbloodborrowed)
                                $this->Ln(2);

            #$this->Cell(4, 4, "", "", 0, '');
            //removed by jasper 01/04/13
            //          $this->Cell($billingXval, 4, "", "", 0, '');
            //$this->Cell(FOOTER_COL01*0.325, 4, "BILLING: ", "", 0, '');
            //$this->Cell(4, 4, "", "", 0, '');
            //$this->Cell(FOOTER_COL01*0.675, 4, str_repeat('_', 30), "", 1, '');
            //removed by jasper 01/04/13
        }
        
    }

    function ReportTitle() {
        // $this->Ln(2);
        $this->SetFont($this->fontType, "B", "10");
        $this->Cell(0, 4, $this->reportTitle, 0 , 1, "C");
    }

    function PersonInfo() {
        global $date_format,$encobj;

        $rowArray = $this->getPersonInfo($this->encounter_nr);
        // echo $rowArray."sss";
        if (!is_bool($rowArray)) {
            $row = $rowArray->FetchRow();

            $this->personData = $row;

            $name = strtoupper($row['name_last'].",  ".$row['name_first'].' '.$row['suffix']." ".$row['name_middle']);

            $saddr1 = '';
            $saddr2 = '';
            $saddr3 = '';
            $this->trimAddress($row['street_name'], $row['brgy_name'], $row['mun_name'], $row['prov_name'], $row['zipcode'], $saddr1, $saddr2, $saddr3);
//          $admission_dte = @formatDate2Local($row['admission_dt'], $date_format);

            //$billdte       = strftime("%b %d, %Y %I:%M %p", strtotime($this->bill_date));
            //edited by jasper 06/10/2013 REMOVE TIME FROM BILL DATE
            $billdte       = strftime("%b %d, %Y", strtotime($this->bill_date));
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

             //Admitted
            $this->Cell(22.6, 4, "Admitted Date:", "", 0, '');
            $this->Cell(1, 4, ":", "", 0, 'R');
            $this->Cell(37.2, 4, $admission_dte, "", 1, 'R');
            //added by jasper 01/04/13

            //HRN
            $this->Cell(20, 4, "HRN ", "", 0, 'L');
            $this->Cell(1, 4, ":", "", 0, 'R');
            $this->Cell($this->in2mm(4.4), 4, $row['pid'], "", 0, '');

            //Date
            $this->Cell(22.6, 4, "Discharged Date ", "", 0, '');
            $this->Cell(3.7, 4, ":", "", 0, 'R');
//          $this->Cell(12, 4, date('m/d/Y'), "", 1, '');
            $this->Cell(19, 4, $billdte, "", 1, 'R');
            

            //patient name
            $this->SetFont("Times", "B", "11");
            $this->Cell(20, 4, "Name ", "", 0, 'L');
            $this->Cell(1, 4, ":", "", 0, 'R');
            $this->Cell($this->in2mm(4.4),  4,$name, "", 0, '');
            $this->SetFont("Times", "", "11");

            $this->Cell(22.6, 4, "Bill Date ", "", 0, '');
            $this->Cell(1, 4, ":", "", 0, 'R');
//          $this->Cell(12, 4, date('m/d/Y'), "", 1, '');
            $this->Cell(12, 4, strftime("%b %d, %Y %I:%M %p", strtotime($this->bill_date)), "", 1, '');

            //Department
            $this->Cell(144.3, 4, "Dept. ", "", 0, 'R');
            $this->Cell(12, 4, ":", "", 0, 'R');
            $this->Cell(12, 4, substr($row['dept_name'],0,DEPT_LEN), "", 1, '');

            //Address (line 1)
            $this->Cell(20, 4, "Address ", "", 0, '');
            $this->Cell(1, 4, ":", "", 0, 'R');
            $this->Cell($this->in2mm(4.4), 4, strtoupper($saddr1), "", 0, '');

            //Bill Reference number
            $this->Cell(22.6, 4, "Bill Ref. # ", "", 0, 'L');
            $this->Cell(1, 4, ":", "", 0, 'R');
            $this->Cell(12, 4, $this->bill_ref_nr, "", 1, '');
            //added by jasper 01/04/13

            //Address (line 2)
            $this->Cell(19, 4, "", "", 0, '');
            $this->Cell(1, 4, ":", "", 0, 'R');
            $this->Cell($this->in2mm(4.4), 4, strtoupper($saddr2), "", 0, '');

            //Classification
//          $sClassification = $this->objBill->getClassificationDesc();
            $sMembership = $this->objBill->getMemCategoryDesc();
            //added by jasper 04/24/2013
            $classification = $this->objBill->getClassificationDesc($this->encounter_nr, $this->bill_date); //TODO4


//          $this->Cell(22.75, 4, "Classification", "", 0, '');
            //edited by jasper 04/24/2013
            $this->Cell(24, 4, (!$this->isphic ? (!$classification ? " " : "Classification") : "Membership"), "", 0, '');
            $this->Cell(1, 4, (!$this->isphic ? (!$classification ? " " : ":") : ":"), "", 0, 'R');
//          $this->Cell(30, 4, ($sClassification == '' ? "No Classification" : $sClassification), "", 1, '');
            if($this->isphic && ($sMembership!='' || $classification1)){
                if(count_chars($sMembership)>15 || count_chars($classification1)>15)
                    $this->SetFont("Times", "B", "6");
            }
            $this->Cell(30, 4, ($this->isphic ? ($sMembership == '' ? "Not Specified" : $sMembership) : ($classification ? $classification : "No PHIC")), "", 1, '');
            $this->SetFont("Times", "", "10");

            


            //Address (line 3)
            if ($saddr3 != '') {
                $this->Cell(20, 4, "", "", 0, '');
                $this->Cell(1, 4, ":", "", 0, 'R');
                $this->Cell($this->in2mm(4.4), 4, strtoupper($saddr3), "", 1, '');
            }

            //Room #
            
            if ($row['room_no'] == 0) {
                if ($this->brecalc) {
                    $acchist = $this->objBill->getAccomodationList;/*getAccommodationHist();*/ // set AccommodationHist
                    /*$this->objBill->getRoomTypeBenefits(); // set Room type Benefits
                    $this->objBill->getConfineBenefits('AC');*/
                }
                else {
                    $ac = new ACBill();
                    if (!($ac instanceof ACBill)) {
                            $var_dump("No accommodation object retrieved!");
                    }
                    $ac->assignBillObject($this->objBill);
                }

                $accArray   = $this->objBill->getAccHist($accHist);
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

            $sCaseType = $this->objBill->getCaseTypeDesc($this->encounter_nr, $this->bill_date); //TODO5

            $this->Cell(20, 4, "Room #", "", 0, '');
            $this->Cell(1, 4, ":", "", 0, 'R');
            $this->Cell(10, 4, $sroom_no, "", 0, '');
//          $this->Cell($this->in2mm(4), 4, "( ".$sward_name." )".($sCaseType == '' ? '' : " - ".$sCaseType), "", 0 ,'');

            #Last billing ...
            $lastbilldte = $this->objBill->getActualLastBillDte(); //TODO6
            if ( ($lastbilldte == "0000-00-00 00:00:00") && !$this->objBill->getIsCoveredByPkg() )
                $this->Cell($this->in2mm(4), 4, "( ".$sward_name." )".($sCaseType == '' ? '' : "  "), "", 0 ,'');
            else {
                $this->Cell($this->in2mm(4), 4, "( ".$sward_name." )".($sCaseType == '' ? '' : "  "), "", 0 ,'');

                if ( $this->objBill->getIsCoveredByPkg() ) {
                    $this->Cell(23, 4, "Package ", "", 0, '');
                    $this->Cell(1, 4, ":", "", 0, 'R');
                    $this->Cell(35, 4, $this->objBill->getPackageName(), "", 1, '');
                }
                else {
                    $this->Cell(22.6, 4, "  ", "", 0, '');
                    $this->Cell(1, 4, ":", "", 0, 'R');
                    $this->Cell(12, 4, strftime("%b %d, %Y %I:%M%p", strtotime($lastbilldte)), "", 1, '');
                }
            }

            if($this->objBill->isMedicoLegal($this->encounter_nr) /*edited by nick,1/5/2014 3:43PM*/){
                $this->SetFont("Times", "B", "11");
                $this->Cell(50, 4,"Medico Legal", "", 1, 'R');
                $this->SetFont("Times", "", "11");
            }/*else{
                $this->Cell(50, 4,"1111", "", 1, 'R');
            }*/

            #Added by Jarel 06/12/2013
            if($this->death_date != ''){
                #Updated by Jane 10/17/2013
                $this->SetFont("Times", "B", "11");
                $this->Cell(20, 4, "Death Date", "", 0, 'R');
                $this->Cell(4, 4, ":", "", 0, 'R');
                $this->Cell(35, 4, strftime("%b %d, %Y %I:%M%p", strtotime($this->death_date)), "", 1, '');
                $this->SetFont("Times", "", "11");
            }/*else{
                $this->Cell(50, 4,"2222", "", 1, 'R');
            }*/
            //added by daryl
            //added by age
            // $this->Cell(133, 4, "", "BTLR", 0, '');
//added by daryl
        $encInfo=$encobj->getEncounterInfo($this->encounter_nr);
// added by daryl

$get_age = $this->specific_age($encInfo['date_birth']);
$sage_ = $get_age;
$hmo_name = $this->get_hmo_desc();
            $this->Cell(21, 4, "AGE ", "", 0, '');
            $this->Cell(3, 4, ":", "", 0, '');
            $this->Cell(3, 4, $sage_, "", 0, 'L');
            #added by Nick, 1/5/2014 6:23 PM
            $this->Cell(50, 4,"", "", 1, 'L');
            if ($this->isphic && $this->IsDetailed) {
                $icds = $this->getIcdCodes($this->encounter_nr);

                $str = "";
                $index=1;
                if (!empty($icds)){
                    foreach ($icds as $key => $value) {
                        $str .= $value['code'].",";
                    }
                    $str = trim($str,',');

                    $this->Cell(50, 4,"ICD: ".$str, "", 0, 'L');
                }

                
                   if ($this->isphic){
                        $this->SetFont("Times", "", "11");
                        $this->Cell(83, 4,"", "", 0, 'L');
                         $this->Cell(50, 4,"HMO           : ".$hmo_name['firm_id'], "", 1, 'L');
                        }else{
                        $this->SetFont("Times", "", "11");
                         $this->Cell(133, 4,"", "", 0, 'L');
                         $this->Cell(50, 4,"HMO             : ".$hmo_name['firm_id'], "", 1, 'L');
                    }



                $icps = $this->getIcpCodes($this->encounter_nr);
                $str = "";
                $index=1;
                if (!empty($icps)){
                    foreach ($icps as $key => $value) {
                        $str .= $value['code'].",";
                    }
                    $str = trim($str,',');

                    $this->Cell(50, 4,"ICP: ".$str, "", 0, 'L');
                }

            } elseif ($this->isphic) {

                // modify by Jan Chris 12/02/2019
                $result = $this->getSavePackages($this->bill_nr);
                $other = $this->getOtherDiagnosis($this->encounter_nr);
                $rvs = $this->getRvs($this->encounter_nr);
                if($result){
                    while ($soa = $result->FetchRow()) {
                        array_push($this->soa_caserate, $soa);
                    }
                }
                if($other){
                    while ($soa = $other->FetchRow()) {
                        array_push($this->soa_diagnosis, $soa);
                    }
                }
                if($rvs){
                    while ($soa = $rvs->FetchRow()) {
                        array_push($this->soa_rvs, $soa);
                    }
                }

                if($this->soa_caserate) {
                    for($i=0; $i<count($this->soa_caserate); $i++) {
                        if($this->soa_caserate[$i]['rate_type'] == 1){
                            $this->SetFont("Times", "B", "11");
                            $this->Cell(50, 4,"First Case Rate: ".$this->soa_caserate[$i]['package_id'], "", 0, 'L');
                            if ($this->isphic){
                                $this->SetFont("Times", "", "11");
                                $this->Cell(83, 4,"", "", 0, 'L');
                                 $this->Cell(50, 4,"HMO           : ".$hmo_name['firm_id'], "", 1, 'L');
                                }else{
                                $this->SetFont("Times", "", "11");
                                 $this->Cell(133, 4,"", "", 0, 'L');
                                 $this->Cell(50, 4,"HMO             : ".$hmo_name['firm_id'], "", 1, 'L');
                            }
                        }else {
                            $this->SetFont("Times", "B", "11");
                            $this->Cell(50, 4,"Second Case Rate: ".$this->soa_caserate[$i]['package_id'], "", 1, 'L');
                        }
                    }
                }

                // check if there's caserate
                if(count($this->soa_caserate) || count($this->soa_rvs)) {
                    for($i=0; $i<count($this->soa_caserate); $i++) {
                        if($this->soa_caserate[$i]['rate_type'] == 1){
                            $description = $this->getDescription($this->encounter_nr, $this->soa_caserate[$i]['package_id']);
                            for($a=0; $a<count($this->soa_rvs); $a++) {
                                if($this->soa_caserate[$i]['package_id'] == $this->soa_rvs[$a]['ops_code']) {
                                    $rvs_description = $this->soa_rvs[$a]['description'];
                                }
                            }
                            $finaldiagnosis =  $description ? $description : $rvs_description;
                            $this->SetFont("Times", "B", "8");
                            $this->Cell(50, 4,"Final Diagnosis: ".strtoupper($finaldiagnosis), "", 1, 'L');
                        }
                        else {
                            $description = $this->getDescription($this->encounter_nr, $this->soa_caserate[$i]['package_id']);
                            for($a=0; $a<count($this->soa_rvs); $a++) {
                                if($this->soa_caserate[$i]['package_id'] == $this->soa_rvs[$a]['ops_code']) {
                                    $rvs_description = $this->soa_rvs[$a]['description'];
                                }
                            }
                            $finaldiagnosis =  $description ? $description : $rvs_description;
                            $this->SetFont("Times", "B", "8");
                            $this->Cell(20, 4,' ', "", '', 'L');
                            $this->Cell(1, 4,strtoupper($finaldiagnosis), "", 1, 'L');
                        }
                    }
                }
                // check if there is 2 or more packages
                $numbering = 1;
                if(count($this->soa_diagnosis)) {
                    for($i=0; $i<count($this->soa_diagnosis); $i++) {
                        if (($this->soa_diagnosis[$i]['code'] != $this->soa_caserate[0]['package_id']) && ($this->soa_diagnosis[$i]['code'] != $this->soa_caserate[1]['package_id'])) {
                            if($numbering == 1){
                                $this->SetFont("Times", "B", "8");
                                $this->Cell(50, 4,'Other Diagnoses: '.$numbering++ .'. '.strtoupper($this->soa_diagnosis[$i]['description']), "", 1, 'L');
                            }
                            else {
                                $this->SetFont("Times", "B", "8");
                                $this->Cell(21, 4,' ', "", 0, 'L');
                                $this->Cell(1, 4,' '.$numbering++ .'. '.strtoupper($this->soa_diagnosis[$i]['description']), "", 1, 'L');
                            }
                        }
                    }
                }
                if(count($this->soa_rvs)) {
                    for($i=0; $i<count($this->soa_rvs); $i++) {
                        if (($this->soa_rvs[$i]['ops_code'] != $this->soa_caserate[0]['package_id']) && ($this->soa_rvs[$i]['ops_code'] != $this->soa_caserate[1]['package_id'])) {
                            if($numbering == 1){
                                $this->SetFont("Times", "B", "8");
                                $this->Cell(50, 4,'Other Diagnoses: '.$numbering++ .'. '.strtoupper($this->soa_rvs[$i]['description']), "", 1, 'L');
                            }
                            else {
                                $this->SetFont("Times", "B", "8");
                                $this->Cell(21, 4,' ', "", 0, 'L');
                                $this->Cell(1, 4,' '.$numbering++ .'. '.strtoupper($this->soa_rvs[$i]['description']), "", 1, 'L');
                            }
                        }
                    }
                }
                #end Jan Chris
                $this->SetFont("Times", "", "11");
            }
            #end Nick
        }
    }//end of PersonInfo

    function TitleHeader($billType){
        switch($billType){
            case 'summary':
                $this->Ln(1);

                #added by daryl
            $getaddinsurance_ = $this->getadd_insurance($this->bill_ref_nr);
        while ($getaddinsurance = $getaddinsurance_->FetchRow()){
            $this->results_insurance[] = $getaddinsurance;
        }

        foreach ($this->results_insurance as $result ) {
                // $ins_name = mb_substr($result['firmname'], 0, 3);
                $this->result_firmname = $result['firmname'];
                $this->result_sup=$result['sup'];
                $this->result_acc=$result['acc'];
                $this->result_med=$result['med'];
                $this->result_ops=$result['ops'];
                $this->result_msc=$result['msc'];
                $this->result_serv=$result['serv'];
                $this->result_srv=$result['srv'];
                $this->result_serv=$result['serv'];
                $this->result_totalPF=$result['totalpf'];


                $this->result_total=$result['sup']+
                                    $result['acc']+
                                    $result['med']+
                                    $result['msc']+

                                    $result['serv']+
                                    $result['srv']+
                                    $result['ops'];
                }

                $this->Cell(0, 4, " ", "", 1, '');//added by art 01/11/2014
                $this->Cell($this->in2mm(GEN_COL02) , 4, "Particulars", "TB", 0, 'C');
                $this->Cell(COL_MID, 4, " ", "", 0, '');
                $this->Cell(COL03_WIDTH, 4, "Actual Charges", "TB", 0, 'C');
                $this->Cell(COL_MID, 4, " ", "", 0, '');
                $this->Cell(COL04_WIDTH, 4, "Sen. Citizen", "TB", 0, 'C');
                $this->Cell(COL_MID, 4, " ", "", 0, '');
                $this->Cell(COL05_WIDTH, 4, "PHIC", "TB", 0, 'C');
                $this->Cell(COL_MID, 4, " ", "", 0, '');
                $this->Cell(COL06_WIDTH, 4, "HMO", "TB", 0, 'C');
                $this->Cell(COL_MID, 4, " ", "", 0, '');
                $this->Cell(COL07_WIDTH, 4, "Excess", "TB", 0, 'C');
                break;
            case 'detailed':
//              $this->Ln(3);
//              $this->Cell(8, 4, "#", "TB", 0, 'C');
//              $this->Cell(4, 4, " ", "", 0, '');
//              $this->Cell($this->in2mm(1.2) , 4, "Date Requested", "TB", 0, 'C');
//              $this->Cell(4, 4, " ", "", 0, '');
//              $this->Cell($this->in2mm(3.4) , 4, "Particulars", "TB", 0, 'C');
//              $this->Cell(4, 4, " ", "", 0, '');
//              $this->Cell(10 , 4, "Qty", "TB", 0, 'C');
//              $this->Cell(4, 4, " ", "", 0, '');
//              $this->Cell($this->in2mm(1.2) , 4, "Amount", "TB", 0, 'C');
            break;
        }
    } //end of function TitleHeader

    function PrintData(){
        $this->Ln(5);
        $get_disc_sc = $this->if_pat_SC($this->encounter_nr);
        $this->global_sc_discount =  $get_disc_sc['discount'];

        $get_pat_phic = $this->if_coverage_phic();
        $this->if_pat_phic = $get_pat_phic['if_phic'];

        $get_pat_hmo = $this->if_coverage_hmo();
        $this->if_pat_hmo = $get_pat_hmo['if_hmo'];

        // Accommodation
        if (!$this->objBill->isERPatient($this->encounter_nr)) $this->getAccommodationData();
        $this->getHospitalServiceData();   // Hospital services ( Laboratory & radiology)
        $this->getMedicinesData();         // Medicines
//      $this->getSuppliesData();          // Supplies
        $this->getOpsCharges();            // Operation/Procedures
        $this->getMiscellaneousCharges();  // Miscellaneous Charges

    }// end of function PrintData

    function getPFDiscount($pfarea, $npf, $nclaim) {
        global $db;

        $n_discount = 0.00;
        $n_prevdiscount = 0.00;

        $area_array = array('AC', 'D1', 'D2', 'D3', 'D4');
        //edited by jasper 04/16/2013    -CONDITION SHOULD BE THE SAME WITH FUNCTION getBillAreaDiscount IN class_billing.php
        //if ($this->objBill->isCharity() && (in_array($pfarea, $area_array))) {
  //         if ($this->objBill->isCharity() && !$this->objBill->isMedicoLegal($this->encounter_nr/*edited by nick,1/5/2014 3:43PM*/) && !$this->objBill->isPHIC() && (in_array($pfarea, $area_array))) {
        //  switch ($pfarea) {
        //      case 'D1':
        //      case 'D2':
        //      case 'D3':
        //      case 'D4':
        //          $n_discount = $npf - $nclaim;
  //                   break;
        //  }
        // }
        // else {
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
        // }
        return round($n_discount, 2);
    }

    #added by Nick, 1/5/2014
    function getIcdCodes($encounter_nr){
        global $db;
        $data = array();
        $index = 0;
        $sql = "SELECT code, description FROM seg_encounter_diagnosis WHERE encounter_nr = ".$db->qstr($encounter_nr)." AND is_deleted = 0;";
        $rs = $db->Execute($sql);
         if($rs){
            if($rs->RecordCount()>0){
                while($row = $rs->FetchRow()){
                    $data[$index] = array("code" => $row['code'], "desc" => $row['description']);
                    $index++;
                }
                return $data;
            }else{
                return false;
            }
         }else{
            return false;
         }
    }

    function getIcpCodes($encounter_nr){
        global $db;
        $data = array();
        $index = 0;
        $sql = "SELECT b.ops_code, b.description FROM seg_misc_ops AS a
                INNER JOIN seg_misc_ops_details  AS b ON a.`refno` = b.`refno`
                WHERE a.`encounter_nr` = ".$db->qstr($encounter_nr)." ORDER BY op_date DESC;";
        $rs = $db->Execute($sql);
         if($rs){
            if($rs->RecordCount()>0){
                while($row = $rs->FetchRow()){
                    $data[$index] = array("code" => $row['ops_code'], "desc" => $row['description']);
                    $index++;
                }
                return $data;
            }else{
                return false;
            }
         }else{
            return false;
         }
    }
#end nick


    function getSavePackages($bill_nr)
    {
        global $db;
        
        $this->sql = "SELECT * FROM seg_billing_caserate WHERE bill_nr =".$db->qstr($bill_nr)." ORDER BY rate_type";
        if ($result = $db->Execute($this->sql)) {
            if ($result->RecordCount()) {
                return $result;
            } else {
                return false;
            }
        }
    }

    function getOtherDiagnosis ($encounter_nr)
    {
        global $db;

        $this->sql = "SELECT code, description FROM seg_encounter_diagnosis WHERE encounter_nr = ".$db->qstr($encounter_nr)." AND is_deleted = 0";

        if ($result = $db->Execute($this->sql)) {
            if ($result->RecordCount()) {
                return $result;
            } else {
                return false;
            }
        }
    }

    function getDescription($encounter_nr, $icdcode)
    {
        global $db;

        $this->sql = "SELECT code, description FROM seg_encounter_diagnosis WHERE encounter_nr = ".$db->qstr($encounter_nr)." AND code = ".$db->qstr($icdcode)." AND is_deleted = 0";

        if ($result = $db->Execute($this->sql)) {

            if ($result->RecordCount()) {
                $res = $result->fetchRow();
                return $res[1];
            } else {
                return false;
            }
        }
    }

    function getRvs($encounter_nr)
    {
        global $db;

        $this->sql = "SELECT 
                          smod.`ops_code`,
                          smod.`description` 
                        FROM
                          seg_misc_ops smo 
                          JOIN seg_misc_ops_details smod 
                            ON smod.`refno` = smo.`refno` 
                        WHERE smo.`encounter_nr` =".$db->qstr($encounter_nr)." 
                        ORDER BY smod.`entry_no`";

        if ($result = $db->Execute($this->sql)) {
            if ($result->RecordCount()) {
                return $result;
            } else {
                return false;
            }
        }
    }

#added by Nick, 1/4/2014
function getMiscData($enc){

    global $db;

    $sql = "SELECT  (a.`total_msc_charge` * SUM(b.`discount`)) AS misc_discount,
                    a.`total_msc_charge` - (a.`total_msc_charge` * SUM(b.`discount`)) AS misc_excess
            FROM seg_billing_encounter AS a
            RIGHT JOIN seg_billingapplied_discount AS b ON a.`encounter_nr` = b.`encounter_nr`
            WHERE a.encounter_nr = ".$db->qstr($enc)." AND is_deleted IS NULL;";

    $rs = $db->Execute($sql);
    if($rs){
        if($rs->RecordCount()>0){
            $row = $rs->FetchRow();
            return array("misc_discount" => $row['misc_discount'],
                         "misc_excess" => $row['misc_excess']
                        );
        }else{
            return array("misc_discount" => 0,
                         "misc_excess" => 0
                        );
        }
    }else{
        return array("misc_discount" => 0,
                         "misc_excess" => 0
                        );
    }

}
#end nick
#added by Nick, 1/4/2014
//edited by daryl
    function getCoverage_PF_Serv($bill_nr){
        global $db;
        $sql = "SELECT  (total_d1_coverage+total_d2_coverage+total_d3_coverage+total_d4_coverage) AS total_pf_coverage,
                       SUM(total_acc_coverage + total_med_coverage + total_srv_coverage + total_ops_coverage + total_msc_coverage) AS total_serv_coverage
                FROM seg_billing_coverage WHERE bill_nr = ".$db->qstr($bill_nr)."  AND hcare_id = 18 ";
        $rs = $db->Execute($sql);

        if($rs){
            if($rs->RecordCount()>0){
                return $rs->FetchRow();
            }else{
                return array("total_pf_coverage"=>0,"total_serv_coverage"=>0);
            }
        }else{
            return array("total_pf_coverage"=>0,"total_serv_coverage"=>0);
        }
    }


        function getCoverage_PF_dr_role($role,$dr_nr){
        global $db;
  

        $sql = "SELECT  dr_claim
                 FROM seg_billing_pf WHERE bill_nr = ".$db->qstr($this->bill_nr)."  AND hcare_id = 18 AND dr_nr = ".$db->qstr($dr_nr)."AND role_area =".$db->qstr($role);
        $rs = $db->Execute($sql);
        if($rs){
            if($rs->RecordCount()>0){
                return $rs->FetchRow();
            }else{
                return array("dr_claim"=>0);
            }
        }else{
            return array("dr_claim"=>0);
        }
        
    }

    function getCoverage_PF_PerArea($dr_nr,$bill_nr,$area){
        global $db;
        $sql = '';
        $sql = "SELECT sbp.dr_claim 
                FROM seg_billing_pf sbp 
                 INNER JOIN seg_billing_coverage b 
                 ON sbp.`bill_nr` = b.`bill_nr` 
                 AND b.`hcare_id` <> '18'
                WHERE sbp.dr_nr = ".$db->qstr($dr_nr)." AND ".
                "sbp.bill_nr = ".$db->qstr($bill_nr)." AND sbp.role_area = ".$db->qstr($area)." AND "."sbp.hcare_id <> 18";
        $rs = $db->Execute($sql);
    
        if($rs){
            if($rs->RecordCount()>0){
                return $rs->FetchRow();
            }else{
                return array("dr_claim"=>0);
            }
        }else{
            return array("dr_claim"=>0);
        }
        
    }

    function getDiscount_PF_PerArea($bill_nr){
        global $db;
        $sql = "SELECT total_d1_discount, total_d2_discount,
                        total_d3_discount, total_d4_discount
                FROM seg_billingcomputed_discount WHERE bill_nr = ".$db->qstr($bill_nr);
        $rs = $db->Execute($sql);
        if($rs){
            if($rs->RecordCount()>0){
                return $rs->FetchRow();
            }else{
                return array("total_d1_discount"=>0,
                             "total_d2_discount"=>0,
                             "total_d3_discount"=>0,
                             "total_d4_discount"=>0);
            }
        }else{
            return array("total_d1_discount"=>0,
                         "total_d2_discount"=>0,
                         "total_d3_discount"=>0,
                         "total_d4_discount"=>0);
        }
    }

    function getDiscount_PF_Serv($bill_nr){
        global $db;
        $sql = "SELECT  hospital_income_discount AS total_serv_discount,
                        (total_d1_discount + total_d2_discount +
                        total_d3_discount + total_d4_discount + professional_income_discount) AS total_pf_discount
                FROM seg_billingcomputed_discount WHERE bill_nr = ".$db->qstr($bill_nr);

        $rs = $db->Execute($sql);
        if($rs){
            if($rs->RecordCount()>0){
                return $rs->FetchRow();
            }else{
                return array("total_serv_discount"=>0,"total_pf_discount"=>0);
            }
        }else{
            return array("total_serv_discount"=>0,"total_pf_discount"=>0);
        }
    }

    function if_pat_SC($enc){
               global $db;
        $sql = "SELECT 
              SUM(
                CASE
                  WHEN (discountid = 'SC') 
                  THEN 1 
                  ELSE 0 
                END
              ) AS ifSC,discount 
            FROM
              `seg_billingapplied_discount` 
            WHERE  
            discountid = 'SC'
            AND
            encounter_nr = ".$db->qstr($enc);

        $rs = $db->Execute($sql);
        if($rs){
            if($rs->RecordCount()>0){
                return $rs->FetchRow();
            }else{
                return array("ifSC"=>0,"discount"=>0);
            }
        }else{
            return array("ifSC"=>0,"discount"=>0);
        }
    }

    #added by daryl
    //11/4/14
    function get_hmo_desc(){
               global $db;
        $sql = "SELECT cif.`firm_id` 
                FROM care_insurance_firm cif 
                INNER JOIN seg_encounter_insurance sei
                ON cif.`hcare_id` = sei.`hcare_id`
                WHERE sei.`hcare_id` <> '18'
                AND sei.`encounter_nr` = ".$db->qstr($this->encounter_nr);
// echo $sql;
        $rs = $db->Execute($sql);
        if($rs){
            if($rs->RecordCount()>0){
                return $rs->FetchRow();
            }else{
                return array("firm_id"=>"N/A");
            }
        }else{
            return array("firm_id"=>"N/A");
        }
    }
    //ended by daryl

    function getTotal_PF_Serv($bill_nr){
        global $db;

        $sql = "SELECT  (total_acc_charge+total_med_charge+total_sup_charge+total_srv_charge+total_ops_charge+total_msc_charge) AS total_serv,
                        total_doc_charge AS total_pf
                FROM seg_billing_encounter WHERE bill_nr = ".$db->qstr($bill_nr);
        $rs = $db->Execute($sql);
        if($rs){
            if($rs->RecordCount()>0){
                return $rs->FetchRow();
            }else{
                return array("total_serv"=>0,"total_pf"=>0);
            }
        }else{
            return array("total_serv"=>0,"total_pf"=>0);
        }
    }

    function getOtherDiscount($enc){
        global $db;

        $sql = "SELECT hcidiscount, pfdiscount
                    FROM seg_billingapplied_discount
                WHERE discountid = 'OT' AND encounter_nr = ".$db->qstr($enc);
        $rs = $db->Execute($sql);
        if($rs){
            if($rs->RecordCount()>0){
                return $rs->FetchRow();
            }else{
                return array("hcidiscount"=>0,"pfdiscount"=>0);
            }
        }else{
            return array("hcidiscount"=>0,"pfdiscount"=>0);
        }
    }

       function get_PF_discount($enc){
        global $db;

        $sql = "SELECT SUM(sbod.`amnt_discount`) AS pfdiscount 
                FROM seg_billing_encounter sbe 
                INNER JOIN seg_billing_other_discounts sbod 
                ON sbe.`bill_nr` = sbod.`refno` 
                WHERE  sbe.`is_deleted` IS NULL
                AND sbe.`encounter_nr`  = ".$db->qstr($enc);
        $rs = $db->Execute($sql);

        if($rs){
            if($rs->RecordCount()>0){
                return $rs->FetchRow();
            }else{
                return array("pfdiscount"=>0);
            }
        }else{
            return array("pfdiscount"=>0);
        }
    }

    function GetEachPFDiscount($enc, $discountid){
        global $db;

        $this->sql = "SELECT SUM(sbod.`amnt_discount`) AS pfdiscount,
                            discountid
                    FROM seg_billing_encounter `sbe`
                    INNER JOIN seg_billing_other_discounts `sbod`
                    ON sbe.`bill_nr` = sbod.`refno`
                    WHERE sbe.`is_deleted` IS NULL
                    AND sbe.`encounter_nr` = ".$db->qstr($enc)."
                    AND sbod.`discountid` = ".$db->qstr($discountid);

        if($this->result = $db->GetRow($this->sql)){
            return $this->result;
        }else{
            return false;
        }
    }

    function get_pf_amntdisc_($drnr,$area,$bill_nr,$disc_id){
         global $db;

        $sql = "SELECT sbd.`amnt_discount`  
                FROM seg_billing_other_discounts sbd 
                WHERE  sbd.`bill_areas` = ".$db->qstr($area)."
                AND sbd.`dr_nr`  = ".$db->qstr($drnr)."
                AND sbd.refno = ".$db->qstr($bill_nr)."
                AND sbd.discountid = ".$db->qstr($disc_id);
        $rs = $db->Execute($sql);

        if($rs){
            if($rs->RecordCount()>0){
                return $rs->FetchRow();
            }else{
                return array("amnt_discount"=>"");
            }
        }else{
            return array("amnt_discount"=>"");
        }
    }
//added by daryl 
//11/19/14
//sum of total discount groupo by discount ID
        function get_pf_total_amnt_($drnr,$area,$bill_nr,$disc_id){
         global $db;

        $sql = "SELECT SUM(sbd.`amnt_discount`) AS amnt_discount  
                FROM seg_billing_other_discounts sbd 
                WHERE  sbd.refno = ".$db->qstr($bill_nr)."
                AND sbd.discountid = ".$db->qstr($disc_id);
        $rs = $db->Execute($sql);

        if($rs){
            if($rs->RecordCount()>0){
                return $rs->FetchRow();
            }else{
                return array("amnt_discount"=>"");
            }
        }else{  
            return array("amnt_discount"=>"");
        }
    }

    function getTotals(){

        $miscData = $this->getMiscData($this->encounter_nr);
        $other = $this->getOtherDiscount($this->encounter_nr);
        $coverages = $this->getCoverage_PF_Serv($this->bill_nr);
        $discounts = $this->getDiscount_PF_Serv($this->bill_nr);
        $totals = $this->getTotal_PF_Serv($this->bill_nr);
        $hci_if_SC = $this->if_pat_SC($this->encounter_nr);
        $get_pf_discount = $this->get_PF_discount($this->encounter_nr);

        $serv_ex = ($totals['total_serv'] - ($coverages['total_serv_coverage'] + $discounts['total_serv_discount'] + $other['hcidiscount'])) - $miscData['misc_discount'];
        $pf_ex = $totals['total_pf'] - ($coverages['total_pf_coverage'] + $discounts['total_pf_discount'] + $get_pf_discount['pfdiscount']);

        if($pf_ex < 0){
            $coverages['total_pf_coverage'] += $pf_ex;
        }
        $this->total_pf_coverage_phic = $coverages['total_serv_coverage'] ;

        // $this->total_pf_coverage_phic = $coverages['total_serv_coverage'] + $coverages['total_pf_coverage'];

        $pf_ex = $totals['total_pf'] - ($coverages['total_pf_coverage'] + $discounts['total_pf_discount'] + $get_pf_discount['pfdiscount']);
        $total_charge = $totals['total_serv'] + $totals['total_pf'];
        $total_discount = ($discounts['total_pf_discount'] + $get_pf_discount['pfdiscount']) + ($discounts['total_serv_discount'] + $miscData['misc_discount'] + $other['hcidiscount']);
        $total_coverage = $coverages['total_pf_coverage'] + $coverages['total_serv_coverage'];
        $total_excess = $serv_ex + $pf_ex;
// echo $hci_if_SC['ifSC']."ssss";
        if ($hci_if_SC['ifSC'] > 0 ){
            $hci_discount = $totals['total_serv'] * $hci_if_SC['discount'];

        }else{
            $hci_discount = 0;
        }

        $output = array("total_charge"=>$total_charge,
                        "total_discount"=>$total_discount,
                        "total_coverage"=>$total_coverage,
                        "total_excess"=>$total_excess,
                        "serv_charge"=>$totals['total_serv'],
                        "serv_discount"=>/* $hci_discount + $miscData['misc_discount']*/number_format($hci_discount/*-$this->mandatory_excess*/,2,'.',','),
                        "serv_discount2"=>/* $hci_discount + $miscData['misc_discount']*/number_format($hci_discount/*-$this->mandatory_excess*/,2,'.',''),
                        "serv_coverage"=>$coverages['total_serv_coverage'],
                        "serv_excess"=>$serv_ex + $miscData['misc_discount'],
                        "pf_charge"=>$totals['total_pf'],
                        "pf_discount"=>$discounts['total_pf_discount'] + $get_pf_discount['pfdiscount'],
                        "pf_coverage"=>$coverages['total_pf_coverage'],
                        "pf_excess"=>$pf_ex
                       );
        return $output;
    }
#end nick




    #added by daryl
    var $total_disc = 0;
    function getdiscount() {
        $data = array();
        $index=0;
        $result = $this->getdiscountname_($this->encounter_nr);

        //$get_pf_discount = $this->GetEachPFDiscount($this->encounter_nr);

        if($result){
            while($row=$result->FetchRow()){        
                $get_pf_discount = $this->GetEachPFDiscount($this->encounter_nr, $row['discountid']);
                $data[$index] = array("discountid"=>$row['discountid'],
                                      "discountdesc"=>$row['discountdesc'],
                                      "discount"=>$row['discount'],
                                      "discount_amnt"=>$row['discount_amnt'],
                                      "hcidiscount"=>$row['hcidiscount'],
                                      "hosp_acc"=>$row['hosp_acc'],
                                      "hosp_xlo"=>$row['hosp_xlo'],
                                      "hosp_meds"=>$row['hosp_meds'],
                                      "hosp_ops"=>$row['hosp_ops'],
                                      "hosp_misc"=>$row['hosp_misc'],
                                      "pfdiscount"=>$get_pf_discount['pfdiscount'],
                                      "pfdiscountid"=>$get_pf_discount['discountid'],
                                     );
                $index++;
            }
        }
        $total = 0;
// $get_result = array();
        foreach ($data as $data_key => $data_value) {
             

                 if ($this->hosp_total_excess <= 0){
                    $hosp_discount = "0";
                }else{
                    $hosp_discount = $data_value['hcidiscount'];
                }

                 if ($this->total_pf_excess <= 0){
                    $pfee_discount = "0";
                }else{
                    $pfee_discount =  $data_value['pfdiscount'];

                }        

              $this->total_HCI_PF += $hosp_discount+$pfee_discount;
              // $this->total_HCI_PF += $pfee_discount;

        if ($this->IsDetailed){
            if($data_value['discountdesc'] != ""){
                
                $this->Cell(COL_MID+2, 4, "", "", 0, '');
                $this->Cell($this->in2mm(GEN_COL02), 4,$data_value['discountdesc'], "",0, '');
                $this->Cell(COL_MID, 4, "", "", 1, '');

                $this->Cell(COL_MID+15, 4, "", "", 0, '');
                $this->Cell($this->in2mm(GEN_COL02), 4,"HCI", "",0, '');
                $this->Cell(COL03_WIDTH, 4, "", "", 0, 'R');
                $this->Cell(COL04_WIDTH, 4, "", "", 0, 'R');
                $this->Cell(COL05_WIDTH, 4, "", "", 0, 'R');
                $this->Cell(COL06_WIDTH, 4, "", "", 0, 'R');
                $this->Cell(COL06_WIDTH-8, 4, "", "", 1, 'R');
                // $this->Cell(COL06_WIDTH-8, 4, number_format($hosp_discount, 2, '.', ','), "", 1, 'R');

                 $this->SetFont("Times", "", "11");
                //Accommodation
                $this->Cell(COL_MID+20, 4, "", "", 0, '');
                $this->Cell($this->in2mm(GEN_COL02), 4,"Accommodation", "",0, '');
                $this->Cell(COL03_WIDTH, 4, "", "", 0, 'R');
                $this->Cell(COL04_WIDTH, 4, "", "", 0, 'R');
                $this->Cell(COL05_WIDTH, 4, "", "", 0, 'R');
                $this->Cell(COL06_WIDTH, 4, "", "", 0, 'R');
                $this->Cell(COL06_WIDTH-13, 4, number_format($data_value['hosp_acc'], 2, '.', ','), "", 1, 'R');
                //

                //X-Ray, Lab, & Others
                $this->Cell(COL_MID+20, 4, "", "", 0, '');
                $this->Cell($this->in2mm(GEN_COL02), 4,"X-Ray, Lab, & Others", "",0, '');
                $this->Cell(COL03_WIDTH, 4, "", "", 0, 'R');
                $this->Cell(COL04_WIDTH, 4, "", "", 0, 'R');
                $this->Cell(COL05_WIDTH, 4, "", "", 0, 'R');
                $this->Cell(COL06_WIDTH, 4, "", "", 0, 'R');
                $this->Cell(COL06_WIDTH-13, 4, number_format($data_value['hosp_xlo'], 2, '.', ','), "", 1, 'R');
                //

                //Drugs & Medicines
                $this->Cell(COL_MID+20, 4, "", "", 0, '');
                $this->Cell($this->in2mm(GEN_COL02), 4,"Drugs & Medicines", "",0, '');
                $this->Cell(COL03_WIDTH, 4, "", "", 0, 'R');
                $this->Cell(COL04_WIDTH, 4, "", "", 0, 'R');
                $this->Cell(COL05_WIDTH, 4, "", "", 0, 'R');
                $this->Cell(COL06_WIDTH, 4, "", "", 0, 'R');
                $this->Cell(COL06_WIDTH-13, 4, number_format($data_value['hosp_meds'], 2, '.', ','), "", 1, 'R');
                //

                //Miscellaneous
                $this->Cell(COL_MID+20, 4, "", "", 0, '');
                $this->Cell($this->in2mm(GEN_COL02), 4,"Miscellaneous", "",0, '');
                $this->Cell(COL03_WIDTH, 4, "", "", 0, 'R');
                $this->Cell(COL04_WIDTH, 4, "", "", 0, 'R');
                $this->Cell(COL05_WIDTH, 4, "", "", 0, 'R');
                $this->Cell(COL06_WIDTH, 4, "", "", 0, 'R');
                $this->Cell(COL06_WIDTH-13, 4, number_format($data_value['hosp_misc'], 2, '.', ','), "", 1, 'R');
                //

                //Operating/Delivery Room
                $this->Cell(COL_MID+20, 4, "", "", 0, '');
                $this->Cell($this->in2mm(GEN_COL02), 4,"Operating/Delivery Room", "",0, '');
                $this->Cell(COL03_WIDTH, 4, "", "", 0, 'R');
                $this->Cell(COL04_WIDTH, 4, "", "", 0, 'R');
                $this->Cell(COL05_WIDTH, 4, "", "", 0, 'R');
                $this->Cell(COL06_WIDTH, 4, "", "", 0, 'R');
                $this->Cell(COL06_WIDTH-13, 4, number_format($data_value['hosp_ops'], 2, '.', ','), "", 1, 'R');
                //
                 $this->SetFont("Times", "B", "11");

                //TOTAL
                $this->Cell(COL_MID+20, 4, "", "", 0, '');
                $this->Cell($this->in2mm(GEN_COL02), 4,"Total", "",0, '');
                $this->Cell(COL03_WIDTH, 4, "", "", 0, 'R');
                $this->Cell(COL04_WIDTH, 4, "", "", 0, 'R');
                $this->Cell(COL05_WIDTH, 4, "", "", 0, 'R');
                $this->Cell(COL06_WIDTH-5, 4, "", "", 0, 'R');
                $this->Cell(COL06_WIDTH-3, 4, number_format($hosp_discount, 2, '.', ','), "T", 1, 'C');
                //

                $this->Cell(COL_MID+20, 4, "", "", 1, '');

                $this->Cell(COL_MID+15, 4, "", "", 0, '');
                $this->Cell($this->in2mm(GEN_COL02), 4,"PF", "",0, '');
                $this->Cell(COL03_WIDTH, 4, "", "", 0, 'R');
                $this->Cell(COL04_WIDTH, 4, "", "", 0, 'R');
                $this->Cell(COL05_WIDTH, 4, "", "", 0, 'R');
                $this->Cell(COL06_WIDTH, 4, "", "", 0, 'R');
                $this->Cell(COL06_WIDTH-8, 4, "", "", 1, 'R');
                // $this->Cell(COL06_WIDTH-8, 4, number_format($pfee_discount, 2, '.', ','), "", 1, 'R');
                $this->Cell(COL_MID, 4, "", "", 1, '');

               $str_pf_name = explode("---", $this->global_dr_name);
               $str_pf_nr = explode("---", $this->global_dr_nr);
               $str_pf_role = explode("---", $this->global_dr_area);
               // print_r($str_pf);
               // echo count($str_pf);
                // $this->Cell(COL_MID+20, 4, "", "", 0, '');
                 $this->SetFont("Times", "", "11");

               for ($i=0; $i < count($str_pf_nr); $i++) { 
                    $get_pf_amntdisc = $this->get_pf_amntdisc_($str_pf_nr[$i],$str_pf_role[$i],$this->bill_nr, $data_value['discountid']);
                    $get_pf_total_amnt = $this->get_pf_total_amnt_($str_pf_nr[$i],$str_pf_role[$i],$this->bill_nr, $data_value['discountid']);#added by daryl 11/19/14

                $this->Cell(COL_MID+15, 4, "", "", 0, '');
                $this->Cell($this->in2mm(GEN_COL02), 4,$str_pf_name[$i], "",0, '');
                $this->Cell(COL03_WIDTH, 4, "", "", 0, 'R');
                $this->Cell(COL04_WIDTH, 4, "", "", 0, 'R');
                $this->Cell(COL05_WIDTH, 4, "", "", 0, 'R');
                $this->Cell(COL06_WIDTH, 4, "", "", 0, 'R');
                if($get_pf_amntdisc['amnt_discount']){
                     $this->Cell(COL06_WIDTH-8, 4, number_format($get_pf_amntdisc['amnt_discount'], 2, '.', ','), "", 1, 'R');
                }else{
                     $this->Cell(COL06_WIDTH-8, 4, $get_pf_amntdisc['amnt_discount'], "", 1, 'R');
                }
               
                // $this->Cell(COL06_WIDTH-8, 4, number_format($pfee_discount, 2, '.', ','), "", 1, 'R');
                #edited by daryl 11/19/14
                if ($get_pf_amntdisc['amnt_discount'] != ""){
                    $total_get_pf_amntdisc = $get_pf_total_amnt['amnt_discount'];
                }
           
               }

                $this->Cell(COL_MID+15, 4, "", "", 0, '');
                $this->Cell($this->in2mm(GEN_COL02), 4,"Total", "",0, '');
                $this->Cell(COL03_WIDTH, 4, "", "", 0, 'R');
                $this->Cell(COL04_WIDTH, 4, "", "", 0, 'R');
                $this->Cell(COL05_WIDTH, 4, "", "", 0, 'R');
                $this->Cell(COL06_WIDTH-1, 4, "", "", 0, 'R');
                if($total_get_pf_amntdisc){
                     $this->Cell(COL06_WIDTH-3, 4, number_format($total_get_pf_amntdisc, 2, '.', ','), "T", 1, 'C');
                }else{
                     $this->Cell(COL06_WIDTH-3, 4, $total_get_pf_amntdisc, "T", 1, 'C');
                }
               

                $this->Cell(COL_MID, 4, "", "", 1, '');

                 $this->SetFont("Times", "B", "11");

            }
        }else{
            if($data_value['discountdesc'] !=""){

                $this->Cell(COL_MID+2, 4, "", "", 0, '');
                $this->Cell($this->in2mm(GEN_COL02), 4,$data_value['discountdesc'], "",0, '');
                $this->Cell(COL_MID, 4, "", "", 0, '');

                $this->Cell(COL_MID+6, 4, "", "", 0, '');
                // $this->Cell($this->in2mm(GEN_COL02), 4,"HCI", "",0, '');
                $this->Cell(COL03_WIDTH, 4, "", "", 0, 'R');
                $this->Cell(COL04_WIDTH, 4, "", "", 0, 'R');
                $this->Cell(COL05_WIDTH, 4, "", "", 0, 'R');
                $this->Cell(COL06_WIDTH, 4, "", "", 0, 'R');
                if($hosp_discount == 0 && $pfee_discount == 0){
                $hos_pf_discount = "";
                }else if($data_value['discountid'] == $data_value['pfdiscountid']){
                $hos_pf_discount = number_format($hosp_discount+$pfee_discount, 2, '.', ',');
                $this->Cell(COL06_WIDTH-8, 4, $hos_pf_discount, "", 1, 'R');
                }else if($data_value['discountid']){
                $hos_pf_discount = number_format($hosp_discount, 2, '.', ',');
                $this->Cell(COL06_WIDTH-8, 4, $hos_pf_discount, "", 1, 'R');
                }else if($data_value['pfdiscountid']){
                $hos_pf_discount = number_format($pfee_discount, 2, '.', ',');
                $this->Cell(COL06_WIDTH-8, 4, $hos_pf_discount, "", 1, 'R');  
                }
            }else{
                 $this->Cell(COL_MID+2, 4, "", "", 0, '');
                $this->Cell($this->in2mm(GEN_COL02), 4,"", "",0, '');
                $this->Cell(COL_MID, 4, "", "", 0, '');

                $this->Cell(COL_MID+6, 4, "", "", 0, '');
                // $this->Cell($this->in2mm(GEN_COL02), 4,"HCI", "",0, '');
                $this->Cell(COL03_WIDTH, 4, "", "", 0, 'R');
                $this->Cell(COL04_WIDTH, 4, "", "", 0, 'R');
                $this->Cell(COL05_WIDTH, 4, "", "", 0, 'R');
                $this->Cell(COL06_WIDTH, 4, "", "", 0, 'R');
                $this->Cell(COL06_WIDTH-8, 4, " ", "", 1, 'R');
            }
        }
     }
             

 }
    
    //end by daryl




    //added by Nick,1/4/2014
    //edited by Nick, 1/5/2014 3:44 PM
    function Professional_Fee() {
        $this->Ln(8);
        $this->Cell(GEN_COL01, 4, "ADD:", "", 1, 'C');

        $data = array();
        $index = 0;

        $this->objBill->getProfFeesList();
        $this->objBill->getProfFeesBenefits();
        $hsp_pfs_benefits = $this->objBill->getPFBenefits();
        $proffees_list = $this->objBill->proffees_list;

        foreach($hsp_pfs_benefits as $key=> $value) {
            if ($value->role_area == $prevrole_area) continue;
            $prevrole_area = $value->role_area;
            reset($proffees_list);
            $this->objBill->initProfFeesCoverage($value->role_area);
            $totalCharge = number_format($this->objBill->getTotalPFCharge($value->role_area), 2);
            $coverage    = number_format($this->objBill->pfs_confine_coverage[$value->role_area], 2, '.', ',');
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
                                  if ($this->objBill->getIsCoveredByPkg()) break;
                            }#if
                        }#foreach
                    }#if

                    $drName = $profValue->dr_first." ".$profValue->dr_mid.(substr($profValue->dr_mid, strlen($profValue->dr_mid)-1,1) == '.' ? " " : ". ").$profValue->dr_last;
                    $drCharge = number_format($profValue->dr_charge, 2, '.', ',');
                    $totalPF += $profValue->dr_charge;

                    $data[$index] = array("dr_charge"=>$profValue->dr_charge,
                                          "role_area"=>$value->role_area,
                                          "role_desc"=>$value->role_desc,
                                          "total_charge"=>$this->objBill->getTotalPFCharge($value->role_area),
                                          "coverage"=>number_format($this->objBill->pfs_confine_coverage[$value->role_area], 2, '.', ','),
                                          "drName"=>$drName,
                                          "dr_nr" => $profValue->dr_nr
                                         );
                    $index++;
                }#if
            }#foreach
        }#foreach
// echo json_encode($data);
        $total = 0;

        foreach ($data as $data_key => $data_value) {       
            $total+=$data_value['total_charge'];
        }

        // if($this->IsDetailed){
        if(count($data)>0){
            $this->Cell($this->in2mm(GEN_COL02), 4,"Professional Fees", "",1, '');

            //counts
            $d1_count = 0;
            $d2_count = 0;
            $d3_count = 0;
            $d4_count = 0;

            foreach ($data as $data_key => $data_value) {
                if($data_value['role_area'] == "D1")
                    $d1_count++;
                if($data_value['role_area'] == "D2")
                    $d2_count++;
                if($data_value['role_area'] == "D3")
                    $d3_count++;
                if($data_value['role_area'] == "D4")
                    $d4_count++;

                     $this->countss++;

            }

            $this->result_totalPF = round($this->result_totalPF / $this->countss, 2);
            // echo    $this->result_totalPF."aa";

            $applied_discount = $this->objBill->getTotalAppliedDiscounts($this->encounter_nr);
            #Admitting -- Admitting -- Admitting
            if($d1_count>0){
                $d1_totalPF = 0;
                $this->Cell(GEN_COL01, 4, "", "", 0, '');
                $this->Cell($this->in2mm(GEN_COL02)-8, 4, "Admitting", "", ($d1_count>0)?1:0, '');
                $count_pf = 0;
                foreach ($data as $data_key => $data_value) { 
                    $count_pf++;
                    $dr_coverage = $this->getCoverage_PF_PerArea($data_value['dr_nr'],$this->bill_nr,'D1');
                    $this->global_dr_name .= $data_value['drName']."---";
                    $this->global_dr_nr .= $data_value['dr_nr']."---";
                    $this->global_dr_area .= $data_value['role_area']."---";
             
                    if($data_value['role_area'] == "D1"){
                        $this->result_totalPF_d1_ = $this->getCoverage_PF_dr_role("D1",$data_value['dr_nr']);
                        $this->result_totalPF_d1 = $this->result_totalPF_d1_['dr_claim'];
                        $this->result_totalPF_d1_total += $this->result_totalPF_d1;
                        $this->Cell(GEN_COL01 + 3.5, 4, "", "", 0, '');
                        $this->Cell($this->in2mm(GEN_COL02)-10, 4, $data_value['drName'], "", 0, '');
                        $this->Cell(COL_MID - 1.75, 4, "", "", 0, '');
                        $this->Cell(COL03_WIDTH + 4, 4, number_format($data_value['dr_charge'],2,'.',','), "", 0, 'R');
                        // if(!$this->isphic && $this->objBill->isCharity()){
                        //  $discount = $data_value['dr_charge'];
                        // }else{
                        $discount_d1 = $data_value['dr_charge'] * $applied_discount;
                        $this->total_excess = $discount_d1;
                        
                        $temp_d1_total_excess = $data_value['dr_charge']-$discount_d1-$dr_coverage['dr_claim'];
                        $temp_total_dr_chargeD0 = $data_value['dr_charge'] - $this->result_totalPF_d1;

                        $d1_exess_ =$data_value['dr_charge'] -                         
                                            $discount_d1-                                        
                                            $dr_coverage['dr_claim'] -
                                          $this->result_totalPF_d1;


                        $d1_exess = $d1_exess_;
                        // echo  number_format( $discount_d1,0,'','');
                        $this->d1_total_excess = $temp_d1_total_excess - $this->result_totalPF_d1;
                        if ($this->d1_total_excess < 0){
                            $this->d1_total_excess = 0;
                        }
                    
                        $d1_total_excess_tempo = ($data_value['dr_charge'] - $dr_coverage['dr_claim'] ) - $this->result_totalPF_d1;

                        if ($d1_total_excess_tempo<=0){
                            $d1_exess = "0";
                        }                      
                        if ($this->result_totalPF_d1 <= 0){
                            $d1_HMO = "0";
                        }else{
                            $d1_HMO =  $this->result_totalPF_d1;
                        }


                        $actual_charge_pf_d1 = number_format($data_value['dr_charge'],2,'.',',');
                        $phic_pf_d1 = $dr_coverage['dr_claim'];
                        // if ($actual_charge_d1 <= $phic_pf_d1){
                        //     $phic_pf_d1 = $dr_coverage['dr_claim'];
                        // }else{
                        //     $phic_pf_d1 =$data_value['dr_charge'];
                        //     $d1_exess =  "0";
                        // }
                        $get_res = number_format( $data_value['dr_charge'],0,'','') -                         
                                            number_format( $discount_d1,0,'','')-                                        
                                            number_format($dr_coverage['dr_claim'],0,'','')-
                                            number_format( $d1_HMO,0,'','');
                        if ($get_res < 0){
                            $this->exess_all = $get_res;
                        }else{
                            $this->exess_all_d1 =  1;
                        }

                        if (($this->exess_all_d2 == 1) ||
                            ($this->exess_all_d3 == 1) ||
                            ($this->exess_all_d4 == 1)){
                            // $phic_pf_d1 = "0";
                            $d1_exess = $data_value['dr_charge'];
                        }
                        if ($this->exess_all < 0){
                            $phic_pf_d1 =  $data_value['dr_charge'] - abs($this->exess_all);
                            if ($count_pf == 2){
                                 $phic_pf_d1 = abs($this->exess_all);
                                 // $aa =  $d1_exess;
                                 $d1_exess = $d1_exess -  $phic_pf_d1;
                               
                            }
                            if ($count_pf == 3){
                                  if ($d1_exess > 0){
                                    $d1_abs = 1;
                                 }
                            }
                            if ($d1_abs == 1){
                                $phic_pf_d1 = "0";
                            }
                        }

                          if ($d1_exess < 0){
                            $d1_exess = "0";
                          }
             
                        $this->Cell(COL04_WIDTH + 2 , 4,number_format($discount_d1,2,'.',','), "",0 , 'R');
                        $this->Cell(COL05_WIDTH + 2, 4,number_format($d1_HMO,2,'.',','), "",0 , 'R');
                        $this->Cell(COL06_WIDTH + 2, 4,number_format($phic_pf_d1,2,'.',','), "", 0, 'R');
                        $this->Cell(COL07_WIDTH + 2, 4,number_format($d1_exess,2,'.',','), "", 1, 'R');
                        $d1_totalPF+=$data_value['dr_charge'];
                        $d1_totalClaim+=/*$dr_coverage['dr_claim'];*/$d1_HMO;
                        $d1_totalDiscount +=$discount_d1;
                        $this->d1_totalDiscount +=$discount_d1;
                        $d1_pf_hmo +=$phic_pf_d1;
                        $d1_totalExcess+=$d1_exess/*$data_value['dr_charge']-$discount_d1-$dr_coverage['dr_claim']*/;

                        if ($this->exess_all < 0){
                            $this->sum_pf_D1 += 0; 
                        }else{
                        $this->sum_pf_D1 +=$this->exess_all; 
                        }
                        // $this->sum_pf_D1 +=$this->d1_total_excess; 
                        $this->total_pf_charge += $data_value['dr_charge'];
                        $this->total_phic_pf += $phic_pf_d1;
                        if ($d1_abs == 1){
                            $this->exess_all = "0";
                        }
                        $this->total_pf_excess += number_format($d1_exess,2,'.','');
                        $this->total_dr_cover_d1 += number_format($dr_coverage['dr_claim'],0,'','');

                    }
                }
                // echo    $this->total_pf_excess;
// echo $d1_totalPF."d1_totalPF<br>";
// echo $d1_totalClaim."d1_totalClaim<br>";
// echo $d1_totalExcess."d1_totalExcess<br>";
// echo $d1_totalDiscount."d1_totalDiscount<br>";
                if($d1_count>0  && $this->IsDetailed)
                    $this->Pf_Sub_Total($d1_totalPF,$d1_totalClaim,$d1_totalExcess,$d1_totalDiscount,$d1_pf_hmo,"Admitting");
            }
#Consulting -- Consulting -- Consulting
            if($d2_count>0){
                $count_pf = 0;
                $d2_totalPF = 0;
                $this->Cell(GEN_COL01, 4, "", "", 0, '');
                $this->Cell($this->in2mm(GEN_COL02)-8, 4, "Consulting", "", ($d2_count>0)?1:0, '');
                foreach ($data as $data_key => $data_value) {
                    $count_pf++;
                    $dr_coverage = $this->getCoverage_PF_PerArea($data_value['dr_nr'],$this->bill_nr,'D2');
                    if($data_value['role_area'] == "D2"){
                        $this->result_totalPF_d2_ =$this->getCoverage_PF_dr_role("D2",$data_value['dr_nr']);
                        $this->result_totalPF_d2 = $this->result_totalPF_d2_['dr_claim'];
                        $this->result_totalPF_d2_total += $this->result_totalPF_d2;

                        $this->Cell(GEN_COL01 + 3.5, 4, "", "", 0, '');
                        $this->Cell($this->in2mm(GEN_COL02)-10, 4, $data_value['drName'], "", 0, '');
                        $this->Cell(COL_MID - 1.75, 4, "", "", 0, '');
                        $this->Cell(COL03_WIDTH + 4, 4, number_format($data_value['dr_charge'],2,'.',','), "", 0, 'R');
                        // if(!$this->isphic && $this->objBill->isCharity()){
                        //  $discount = $data_value['dr_charge'];
                        // }else{
                        $discount_d2 = $data_value['dr_charge'] * $applied_discount;
                        // }
                        // $discount = $data_value['dr_charge'] * $applied_discount;
                        // $this->d2_total_discount = $discount;
                        $temp_d2_total_excess = $data_value['dr_charge']-$discount-$dr_coverage['dr_claim'];                        

                        $d2_exess_ = $data_value['dr_charge'] -                         
                                           $discount_d2 -                                        
                                            $dr_coverage['dr_claim'] -
                                           $this->result_totalPF_d2;
                        $d2_exess = $d2_exess_;

                        $this->d2_total_excess = $temp_d2_total_excess - $this->result_totalPF_d2;
                        if ($this->d2_total_excess < 0){
                            $this->d2_total_excess = 0;
                        }
                        // }
                        $d2_total_excess_tempo = ($data_value['dr_charge'] - $dr_coverage['dr_claim'] ) - $this->result_totalPF_d2;
                         if ($d2_total_excess_tempo<=0){
                            $d2_exess = "0";
                        }
                        if ($this->result_totalPF_d2 <= 0){
                            $d2_HMO = "0";
                        }else{
                             $d2_HMO = $this->result_totalPF_d2;
                        }
                        $actual_charge_pf_d2 = number_format($data_value['dr_charge'],2,'.',',');
                        $phic_pf_d2 = $dr_coverage['dr_claim'];
                        if ($actual_charge_d2 <= $phic_pf_d2){
                            $phic_pf_d2 = $phic_pf_d2;
                        }else{
                            $phic_pf_d2 = $actual_charge_pf_d2;
                            $d2_exess = "0";
                        }
                         $get_res = number_format( $data_value['dr_charge'],0,'','') -                         
                                    number_format( $discount_d2,0,'','')-                                        
                                    number_format($dr_coverage['dr_claim'],0,'','')-
                                    number_format( $d2_HMO,0,'','');
                    
                        if ($get_res < 0){
                            $this->exess_all = $get_res;
                        }else{
                            $this->exess_all_d2 = 1;
                        }

                         if (($this->exess_all_d1 == 1) ||
                            ($this->exess_all_d3 == 1) ||
                            ($this->exess_all_d4 == 1)){
                            $phic_pf_d2 = "0";
                          }

                         if ($this->exess_all < 0){
                                $phic_pf_d2 = abs($this->exess_all );
                                $excess_temp =  abs($this->exess_all);
                                $this->exess_all = number_format( $data_value['dr_charge'],0,'','') -                         
                                                    number_format( $discount_d2,0,'','')-                                        
                                                    number_format($excess_temp,0,'','')-
                                                    number_format( $d2_HMO,0,'','');
                                 if ($this->exess_all > 0)
                                   $d2_exess =   $this->exess_all;
                                else    
                                   $d2_exess =   "0"; 

                                 $this->d2_total_excess =   $d2_exess;                       

                         }

                         if ($this->exess_all < 0){
                            $phic_pf_d2 = $phic_pf_d2 - abs($this->exess_all);
                          }
// echo $this->exess_all;
                       if ($this->exess_all < 0){
                            $phic_pf_d2 =  $data_value['dr_charge'] - abs($this->exess_all);
                            if ($count_pf == 2){
                                 $phic_pf_d2 = abs($this->exess_all);
                                 // $aa =  $d1_exess;
                                 $d2_exess = $d2_exess -  $phic_pf_d2;
                               
                            }
                            if ($count_pf == 3){
                                  if ($d2_exess > 0){
                                    $d2_abs = 1;
                                 }
                            }
                            if ($d2_abs == 1){
                                $phic_pf_d2 = "0";
                            }
                        }

                          if ($d2_exess < 0){
                            $d2_exess = "0";
                          }

                        $this->Cell(COL04_WIDTH + 2 , 4,number_format($discount_d2,2,'.',','), "",0 , 'R');
                        $this->Cell(COL05_WIDTH + 2, 4, number_format($d2_HMO,2,'.',','), "",0 , 'R');
                        $this->Cell(COL06_WIDTH + 2, 4,number_format($phic_pf_d2,2,'.',','), "",0 , 'R');
                        $this->Cell(COL07_WIDTH + 2, 4, number_format($d2_exess,2,'.',','), "", 1, 'R');
                        $d2_totalPF+=$data_value['dr_charge'];
                        $d2_totalClaim+=/*$dr_coverage['dr_claim'];*/$d2_HMO;
                        $d2_totalDiscount +=$discount_d2;
                        $this->d2_totalDiscount +=$discount_d2;
                        $d2_totalExcess+=$d2_exess/*$data_value['dr_charge']-$discount_d1-$dr_coverage['dr_claim']*/;

                        if ($this->exess_all < 0){
                            $this->sum_pf_D2 += 0; 
                        }else{
                        $this->sum_pf_D2 +=$this->exess_all; 
                        }
                        // $this->sum_pf_D2 +=$this->d2_total_excess; 
                        $this->total_pf_charge += $data_value['dr_charge'];
                        $this->total_phic_pf += $phic_pf_d2;
                        $this->total_pf_excess += number_format($d2_exess,2,'.','');
                        $this->total_dr_cover_d1 += number_format($dr_coverage['dr_claim'],0,'','');
                        $d2_pf_hmo += $phic_pf_d2;

                    }
                }
                // echo    $this->total_pf_excess;

                if($d2_count>0 && $this->IsDetailed)
                    $this->Pf_Sub_Total($d2_totalPF,$d2_totalClaim,$d2_totalExcess,$d2_totalDiscount, $d2_pf_hmo,"Consulting");
            }
#Surgeon -- Surgeon -- Surgeon

            if($d3_count>0){

                $count_pf = 0;
                $d3_totalPF = 0;
                $this->Cell(GEN_COL01, 4, "", "", 0, '');
                $this->Cell($this->in2mm(GEN_COL02)-8, 4, "Surgeon", "", ($d3_count>0)?1:0, '');
                foreach ($data as $data_key => $data_value) {
                    $count_pf++; 
                    $dr_coverage = $this->getCoverage_PF_PerArea($data_value['dr_nr'],$this->bill_nr,'D3');
                    if($data_value['role_area'] == "D3"){
                        $this->result_totalPF_d3_ = $this->getCoverage_PF_dr_role("D3",$data_value['dr_nr']);
                        $this->result_totalPF_d3 = $this->result_totalPF_d3_['dr_claim'];
                        $this->result_totalPF_d3_total += $this->result_totalPF_d3;

                        $this->Cell(GEN_COL01 + 3.5, 4, "", "", 0, '');
                        $this->Cell($this->in2mm(GEN_COL02)-10, 4, $data_value['drName'], "", 0, '');
                        $this->Cell(COL_MID - 1.75, 4, "", "", 0, '');
                        $this->Cell(COL03_WIDTH + 4, 4, number_format($data_value['dr_charge'],2,'.',','), "", 0, 'R');
                        // if(!$this->isphic && $this->objBill->isCharity()){
                        //  $discount = $data_value['dr_charge'];
                        // }else{
                            // $discount = $data_value['dr_charge'] * $applied_discount;
                        // }
                        $discount_d3 = $data_value['dr_charge'] * $applied_discount;
                        $temp_d3_total_excess = $data_value['dr_charge']-$discount-$dr_coverage['dr_claim'];
                        $d3_exess_ =  $data_value['dr_charge'] -                         
                                           $discount_d3 -                                        
                                            $dr_coverage['dr_claim'] -
                                           $this->result_totalPF_d3;
                        $d3_exess = $d3_exess_;
                        $this->d3_total_excess = $temp_d3_total_excess - $this->result_totalPF_d3;
                        if ($this->d3_total_excess < 0){
                            $this->d3_total_excess = 0;
                        }
                        // }
                        $d3_total_excess_tempo = ($data_value['dr_charge'] - $dr_coverage['dr_claim'] ) - $this->result_totalPF_d3;
                        if ($d3_total_excess_tempo<=0){
                            $d3_exess ="0";
                        }
                        if ($this->result_totalPF_d3 <= 0){
                            $d3_HMO = "0";
                        }else{
                             $d3_HMO =  $this->result_totalPF_d3;
                        }

                        $actual_charge_pf_d3 = $data_value['dr_charge'] - $discount_d3;
                        $phic_pf_d3 =$dr_coverage['dr_claim'];
                        if ($actual_charge_d3 > $phic_pf_d3){
                            $phic_pf_d3 =  $phic_pf_d3;
                        }else{
                            $phic_pf_d3 =  number_format( $data_value['dr_charge'] - $discount_d3,0,'','');
                            $d3_exess ="0";
                        }
                         $get_res = number_format( $data_value['dr_charge'],0,'','') -                         
                                    number_format( $discount_d3,0,'','')-                                        
                                    number_format($dr_coverage['dr_claim'],0,'','')-
                                    number_format( $d3_HMO,0,'','');
                        if ($get_res < 0){
                            $this->exess_all = $get_res;
                        }else{
                            $this->exess_all_d3 =  1;
                        }

                        if ($this->exess_all < 0){
                                // $phic_pf_d3 = abs($this->exess_all );
                                $excess_temp =  abs($this->exess_all);
                                $this->exess_all = number_format( $data_value['dr_charge'],0,'','') -                         
                                                    number_format( $discount_d3,0,'','')-                                        
                                                    number_format($excess_temp,0,'','')-
                                                    number_format( $d3_HMO,0,'','');
                                                  
                         }

                         if ($actual_charge_pf_d3 >= number_format($dr_coverage['dr_claim'],0,'','')){
                                $phic_pf_d3 = $dr_coverage['dr_claim'];
                                // $d3_exess =  number_format($actual_charge_pf_d3,0,'','') - $dr_coverage['dr_claim'] -  number_format($d3_HMO,0,'','');
                            $d3_exess =  number_format($data_value['dr_charge'],2,'.','') - $discount_d3 - $d3_HMO - $phic_pf_d3;

                                if ($d3_exess < 0){
                                    $d3_exess = "0";

                                }
                         }

                        $this->Cell(COL04_WIDTH + 2 , 4,number_format($discount_d3,2,'.',','), "",0 , 'R');
                        $this->Cell(COL05_WIDTH + 2, 4, number_format($d3_HMO,2,'.',','), "",0 , 'R');
                        $this->Cell(COL06_WIDTH + 2, 4,number_format($phic_pf_d3,2,'.',','), "",0 , 'R');
                        $this->Cell(COL07_WIDTH + 2, 4, number_format($d3_exess,2,'.',','), "", 1, 'R');

                        $d3_totalPF+=$data_value['dr_charge'];
                        $d3_totalClaim+=/*$dr_coverage['dr_claim'];*/$d3_HMO;
                        $d3_totalDiscount +=$discount_d3;
                        $this->d3_totalDiscount +=$discount_d3;

                        $d3_totalExcess+=$data_value['dr_charge']-$discount_d3-$dr_coverage['dr_claim'];
                        if ($this->exess_all < 0){
                            $this->sum_pf_D3 += 0; 
                        }else{
                        $this->sum_pf_D3 +=$this->exess_all; 
                        }
                        // $this->sum_pf_D3 +=$this->d3_total_excess; 
                        // $this->sum_pf_D3 +=$this->exess_all; 
                        $this->total_pf_charge += $data_value['dr_charge'];
                        $this->total_phic_pf += $phic_pf_d3;
                        $this->total_pf_excess += number_format($d3_exess,2,'.','');
                        $d3_pf_hmo += $phic_pf_d3;

                    }
                }
                // echo    $this->total_pf_excess;

                if($d3_count>0 && $this->IsDetailed)
                    $this->Pf_Sub_Total($d3_totalPF,$d3_totalClaim,$d3_totalExcess,$d3_totalDiscount,$d3_pf_hmo,"Surgeon");
            }
#Surgeon -- Surgeon -- Surgeon
            if($d4_count>0){
                $count_pf = 0;
                $d4_totalPF = 0;
                $this->Cell(GEN_COL01, 4, "", "", 0, '');
                $this->Cell($this->in2mm(GEN_COL02)-8, 4, "Anaesthologist", "", ($d4_count>0)?1:0, '');
                foreach ($data as $data_key => $data_value) {
                    $count_pf++;
                    $dr_coverage = $this->getCoverage_PF_PerArea($data_value['dr_nr'],$this->bill_nr,'D4');
                    if($data_value['role_area'] == "D4"){

                        $this->result_totalPF_d4_ = $this->getCoverage_PF_dr_role("D4",$data_value['dr_nr']);
                        $this->result_totalPF_d4 = $this->result_totalPF_d4_['dr_claim'];
                        $this->result_totalPF_d4_total += $this->result_totalPF_d4;

                        $this->Cell(GEN_COL01 + 3.5, 4, "", "", 0, '');
                        $this->Cell($this->in2mm(GEN_COL02)-10, 4, $data_value['drName'], "", 0, '');
                        $this->Cell(COL_MID - 1.75, 4, "", "", 0, '');
                        $this->Cell(COL03_WIDTH + 4, 4, number_format($data_value['dr_charge'],2,'.',','), "", 0, 'R');
                        // if(!$this->isphic && $this->objBill->isCharity()){
                        //  $discount = $data_value['dr_charge'];
                        // }else{
                            // $discount = $data_value['dr_charge'] * $applied_discount;
                        // }
                            $discount_d4 = $data_value['dr_charge'] * $applied_discount;
                        $discount = $data_value['dr_charge'] * $applied_discount;
                        // $this->d4_total_discount = $discount;
                        $temp_d4_total_excess = $data_value['dr_charge']-$discount-$dr_coverage['dr_claim'];
                        $this->d4_total_excess = $temp_d4_total_excess - $this->result_totalPF;
                       
                        $d4_exess_ = $data_value['dr_charge'] -                         
                                         $discount_d4-                                        
                                          $dr_coverage['dr_claim'] -
                                          $this->result_totalPF_d4;

                        $d4_exess = $d4_exess_;
                        $this->d4_total_excess = $temp_d4_total_excess - $this->result_totalPF_d4;
                        if ($this->d4_total_excess < 0){
                            $this->d4_total_excess = 0;
                        }
                      
                        $d4_total_excess_tempo = ($data_value['dr_charge'] - $dr_coverage['dr_claim'] ) - $this->result_totalPF_d4;
                         if ($d4_total_excess_tempo<=0){
                            $d4_exess = "0";
                        }
                      
                        if ($this->result_totalPF_d4 <= 0){
                            $d4_HMO = "0";
                        }else{
                             $d4_HMO =  $this->result_totalPF_d4;
                        }

                        $actual_charge_pf_d4 = $data_value['dr_charge'] - $discount_d4;
                        $phic_pf_d4 = $dr_coverage['dr_claim'];
                        if ($actual_charge_d4 > $phic_pf_d4){
                            $phic_pf_d4 = $phic_pf_d4;
                        }else{
                            $phic_pf_d4 = $actual_charge_pf_d4;
                            $d4_exess = "0";
                        }

                        $get_res = number_format( $data_value['dr_charge'],0,'','') -                         
                                    number_format( $discount_d4,0,'','')-                                        
                                    number_format($dr_coverage['dr_claim'],0,'','')-
                                    number_format( $d4_HMO,0,'','');

                         if ($get_res < 0){
                            $this->exess_all= $get_res;
                        }else{
                            $this->exess_all_d4 = 1;
                        }  

                            if (($this->exess_all_d2 == 1) ||
                            ($this->exess_all_d3 == 1) ||
                            ($this->exess_all_d1 == 1)){
                            }
                            if ($this->exess_all < 0){
                               // $phic_pf_d4 = number_format(abs($this->exess_all ),2,'.',',');
                                $excess_temp =  abs($this->exess_all);
                                $this->exess_all = number_format( $data_value['dr_charge'],0,'','') -                         
                                                    number_format( $discount_d4,0,'','')-                                        
                                                    number_format($excess_temp,0,'','')-
                                                    number_format( $d4_HMO,0,'','');
                           }

                                             
                          if ($actual_charge_pf_d4 >= number_format($dr_coverage['dr_claim'],0,'','')){
                                $phic_pf_d4 = $dr_coverage['dr_claim'];
                                // $d4_exess =  $actual_charge_pf_d4 - $dr_coverage['dr_claim'] - $d4_HMO;
                            $d4_exess =  number_format($data_value['dr_charge'],2,'.','') - $discount_d4 - $d4_HMO - $phic_pf_d4;


                             if ($d4_exess < 0){
                                        $d4_exess = "0";

                              }
                          }

                        $this->Cell(COL04_WIDTH + 2 , 4,number_format($discount_d4,2,'.',','), "",0 , 'R');
                        $this->Cell(COL05_WIDTH + 2, 4, number_format($d4_HMO,2,'.',','), "",0 , 'R');
                        $this->Cell(COL06_WIDTH + 2, 4,number_format($phic_pf_d4,2,'.',','), "",0 , 'R');
                        $this->Cell(COL07_WIDTH + 2, 4, number_format($d4_exess,2,'.',','), "", 1, 'R');

                        $d4_totalPF+=$data_value['dr_charge'];
                        $d4_totalClaim+=/*$dr_coverage['dr_claim'];*/$d4_HMO;
                        $d4_totalDiscount +=$discount_d4;
                        $this->d4_totalDiscount +=$discount_d4;

                        $d4_totalExcess+=$data_value['dr_charge']-$discount_d4-$dr_coverage['dr_claim'];

                        // $this->sum_pf_D4 +=$this->d4_total_excess;  
                        // $this->sum_pf_D4 += $this->exess_all;
                         if ($this->exess_all < 0){
                            $this->sum_pf_D3 += 0; 
                        }else{
                        $this->sum_pf_D3 +=$this->exess_all; 
                        }
                        $this->total_pf_charge += $data_value['dr_charge'];
                        $this->total_phic_pf += $phic_pf_d4;
                        $this->total_pf_excess += number_format($d4_exess,2,'.','');
                        $d4_pf_hmo += $phic_pf_d4;
                    }
                }

                if($d4_count>0 && $this->IsDetailed)
                    $this->Pf_Sub_Total($d4_totalPF,$d4_totalClaim,$d4_totalExcess,$d4_totalDiscount,$d4_pf_hmo,"Anaesthologist");
            }
                
            $this->Ln();
            $this->Pf_Totals();

        }else{
            $this->Cell($this->in2mm(GEN_COL02), 4,"Professional Fees", "",0, '');
            $this->Cell(COL_MID, 4, "", "", 0, '');
            $this->Cell(COL03_WIDTH, 4, "0.00", "", 1, 'R');
        }
        // }else{
        //  $this->Cell($this->in2mm(GEN_COL02), 4,"Professional Fees", "",0, '');
        //  $this->Cell(COL_MID, 4, "", "", 0, '');
        //  $this->Cell(COL03_WIDTH, 4, number_format($total,2,'.',','), "", 1, 'R');
        // }

    }#end of function Professional_Fee
    //end by Nick

    function getDrClaim($dr_nr, $role_area, $drclaims) {
        $claim = 0;
        foreach($drclaims as $k=>$v) {
            if (($v->getDrNr() == $dr_nr) && ($v->getRoleArea() == $role_area)) {
                $claim = $v->getDrClaim();
            }
        }
        return $claim;
    }

#edited by Nick, 1/4/2014
var $serv_total = 0;
    function Sub_Total(){

        $this->serv_total = $this->total_accomodation +
                      $this->total_xlo +
                      $this->total_meds +
                      $this->total_ops +
                      $this->total_misc ;

        $this->Ln(2);
        $this->Cell($this->in2mm(GEN_COL02), 4, "Sub-Total" , "", 0, 'R');

        $this->Cell(COL_MID, 4, " ", "", 0, '');
        $this->Cell(COL03_WIDTH, 4, number_format($this->serv_total/*$this->hdata['serv_charge']*/,2,'.',','), "T", 0, 'R');

        $t_discount = 0;
        foreach($this->totalDiscount as $key=>$v) {
            if ($key != PF_AREA)
                $t_discount += $v;
        }
           // $this->Cell(COL03_WIDTH, 4, "0.00", "", 0, 'R');
           //      $this->Cell(COL04_WIDTH, 4,  "0.00", "", 0, 'R');
           //      $this->Cell(COL05_WIDTH+3, 4,  "0.00", "", 0, 'R');
           //      $this->Cell(COL06_WIDTH+2, 4,  "0.00", "", 0, 'R');
           //      $this->Cell(COL03_WIDTH, 4,  "0.00", "", 1, 'R');

        $this->Cell(COL_MID, 4, " ", "", 0, '');
        $this->Cell(COL04_WIDTH-2, 4, $this->hdata['serv_discount'], "T", 0, 'R');

        $this->hosp_total_discount = $this->hdata['serv_discount2'];
        //Medicare Coverage
        #accomodation + hospital services + medicines + supplies + others
        $totalcoverage = 0;
        foreach($this->totalCoverage as $key=>$v) {
            if ($key != PF_AREA)
                $totalcoverage += $v;
        }
#       $this->subTotal_Medicare = $this->ACSubTotal_Medicare + $this->HSSubTotal_Medicare + $this->MDSubTotal_Medicare + $this->SPSubTotal_Medicare;
        $this->Cell(COL_MID, 4, " ", "", 0, '');
        $this->Cell(COL05_WIDTH+1, 4, number_format($this->hdata['serv_coverage'],2,'.',','), "T", 0, 'R');

        // $this->total_phic_pf = $this->total_phic_pf + $this->hdata['serv_coverage'];
        //Excess
        #accomodation + hospital services + medicines + supplies + others
        $totalexcess = 0;
        foreach($this->totalExcess as $key=>$v) {
            if ($key != PF_AREA)
                $totalexcess += $v;
        }
#       $this->subTotal_Excess = $this->ACSubTotal_Excess + $this->HSSubTotal_Excess + $this->MDSubTotal_Excess + $this->SPSubTotal_Excess;

        $this->Cell(COL_MID, 4, " ", "", 0, '');
        $this->Cell(COL06_WIDTH, 4, number_format($this->result_total,2,'.',','), "T", 0, 'R');

        $this->Cell(COL_MID, 4, " ", "", 0, '');
#added by daryl
   // $hosp_excess =  $this->hdata['serv_excess']-$this->result_total;
   $hosp_excess = ((($this->serv_total - $this->hdata['serv_discount2']) - $this->hdata['serv_coverage'])-$this->result_total);
   // echo $this->result_total."<br>";
   // echo  $this->hdata['serv_excess'];
   if ($hosp_excess < 0){
    $hosp_excess = 0;
   }else{
    $hosp_excess = $hosp_excess;
   }
        // echo $this->serv_total."<br>";  
        // echo $this->hdata['serv_discount2']."<br>";
        // echo $this->serv_total - $this->hdata['serv_discount2']."<br>";

        $this->Cell(COL07_WIDTH+2, 4, number_format($hosp_excess/*+$this->mandatory_excess*/,2,'.',','), "T", 0, 'R');
        // $this->hosp_total_excess = $this->hdata['serv_excess']-$this->result_total;
        $this->hosp_total_excess = $hosp_excess;
    }//end of function Sub_Total

#edited by Nick, 1/4/2014
    function Pf_Sub_Total($total,$claim,$excess,$discount,$hmo,$role){

//      $this->Cell(GEN_COL01, 4, " ", "", 0, '');
        $this->Cell($this->in2mm(GEN_COL02), 4, "Sub-Total(".$role.")", "", 0, 'R');

        //Actual charges
        $this->Cell(COL_MID, 4, " ", "", 0, '');
        $this->Cell(COL03_WIDTH, 4,number_format($total, 2, '.', ','), "T", 0, 'R');

        //Discount
        $this->Cell(COL_MID, 4, " ", "", 0, '');
        $this->Cell(COL04_WIDTH, 4, number_format($discount, 2, '.', ','), "T", 0, 'R');

        //Insurance Coverage
        $this->Cell(COL_MID, 4, " ", "", 0, '');
        $this->Cell(COL05_WIDTH, 4, number_format($claim, 2, '.', ','), "T", 0, 'R');

        //HMO
        $this->Cell(COL_MID, 4, " ", "", 0, '');
        $this->Cell(COL06_WIDTH, 4, number_format($hmo, 2, '.', ','), "T", 0, 'R');

        //Excess
        $this->Cell(COL_MID, 4, " ", "", 0, '');
        $this->Cell(COL07_WIDTH, 4, number_format($excess, 2, '.', ','), "T", 1, 'R');
    }// end of function Pf_Sub_Total()

#edited by daryl
    function  Pf_Totals(){

        $other = $this->getOtherDiscount($this->encounter_nr);

        $this->Cell($this->in2mm(GEN_COL02), 4, "Sub-Total", "", 0, 'R');

        //Actual charges
        $this->Cell(COL_MID, 4, " ", "", 0, '');

        // $this->Cell(COL03_WIDTH, 4,number_format($this->hdata['pf_charge'], 2, '.', ','), "T", 0, 'R');
        $this->Cell(COL03_WIDTH, 4,number_format($this->total_pf_charge, 2, '.', ','), "T", 0, 'R');
        // $pf_d1_total_discount += $this->d1_total_discount;
        // $pf_d2_total_discount += $this->d2_total_discount;
        // $pf_d3_total_discount += $this->d3_total_discount;
        // $pf_d4_total_discount += $this->d4_total_discount;

        // $this->pf_total_total_discount = $pf_d1_total_discount + 
        //                                 $pf_d2_total_discount +         
        //                                 $pf_d3_total_discount +
        //                                 $pf_d4_total_discount;

                    

        $pf_d1_total_excess += $this->d1_total_excess;
        $pf_d2_total_excess += $this->d2_total_excess;
        $pf_d3_total_excess += $this->d3_total_excess;
        $pf_d4_total_excess += $this->d4_total_excess;


        $this->pf_total_total_excess = $this->sum_pf_D1 + 
                                    $this->sum_pf_D2 +      
                                    $this->sum_pf_D3 +
                                    $this->sum_pf_D4;  


        // echo   $this->sum_pf_D1;

        // $this->pf_total_total_excess = $this->pf_total_total_excess - ( $this->temp_total_dr_chargeD0 -
        //                                                                 $this->temp_total_dr_chargeD2 -
        //                                                                 $this->temp_total_dr_chargeD3 -
        //                                                                 $this->temp_total_dr_chargeD4 );

    
        $total_totalDiscount = $this->d1_totalDiscount +
                                        $this->d2_totalDiscount +
                                        $this->d3_totalDiscount +
                                        $this->d4_totalDiscount ;
    
     $this->total_totalDiscount =    number_format($total_totalDiscount, 2, '.', '');                                     
        //Discount
        $this->Cell(COL_MID, 4, " ", "", 0, '');
        $this->Cell(COL04_WIDTH, 4, number_format($this->total_totalDiscount, 2, '.', ','), "T", 0, 'R');

        //Insurance Coverage
        $this->Cell(COL_MID, 4, " ", "", 0, '');
        // $this->Cell(COL05_WIDTH, 4, number_format($this->hdata['pf_coverage'], 2, '.', ','), "T", 0, 'R');
        

        $this->result_totalPF = $this->result_totalPF_d1+
                                $this->result_totalPF_d2+
                                $this->result_totalPF_d3+
                                $this->result_totalPF_d4;

 $this->result_totalPF_total_total = $this->result_totalPF_d1_total +
                                        $this->result_totalPF_d2_total +
                                        $this->result_totalPF_d3_total +
                                        $this->result_totalPF_d4_total;

        $this->Cell(COL05_WIDTH, 4, number_format($this->result_totalPF_total_total, 2, '.', ','), "T", 0, 'R');

        //HMO
        $this->Cell(COL_MID, 4, " ", "", 0, '');
        $this->Cell(COL06_WIDTH, 4, number_format($this->total_phic_pf, 2, '.', ','), "T", 0, 'R');
   
        if ($this->pf_total_total_excess<0){
            $sub_total_pf_excess = 0;
        }else
        {
            $sub_total_pf_excess = $this->pf_total_total_excess;
        }
                

        //Excess
        $this->Cell(COL_MID, 4, " ", "", 0, '');
        $this->Cell(COL07_WIDTH, 4, number_format($this->total_pf_excess, 2, '.', ','), "T", 1, 'R');
    }

#edited by Nick, 1/4/2014
        #edited by daryl
    function Totals(){
        $this->Ln(4);
//      $this->Cell(GEN_COL01, 4, " ", "", 0, '');
        $this->Cell($this->in2mm(GEN_COL02), 4, "T O T A L", "", 0, '');

//      $totalActualCharge = $this->subTotal_ActualCharge + $this->pfSubTotal_ActualCharge;
//      $totalMedicare = $this->subTotal_Medicare + $this->pfSubTotal_Medicare;
//      $totalExcess = $this->subTotal_Excess + $this->pfSubTotal_Excess;

        $totalActualCharge = 0;
        $t_discount        = 0;
        $totalMedicare     = 0;
        $totalExcess       = 0;

/*      foreach($this->totalCharge as $v)
            $totalActualCharge += round($v, 2);

        foreach($this->totalDiscount as $v)
            $t_discount += round($v, 2);

        foreach($this->totalCoverage as $v)
            $totalMedicare += round($v, 2);*/

//      foreach($this->totalExcess as $v)
//          $totalExcess += round($v, 0);
        $totalExcess = $totalActualCharge - $t_discount - $totalMedicare;

        $this->SetFont("Times", "B", "11");

        //Actual charges
        $this->Cell(COL_MID, 4, " ", "", 0, '');
        // $this->Cell(COL03_WIDTH, 4,number_format($this->serv_total + $this->hdata['pf_charge'], 2, '.', ','), "T", 0, 'R');
        $this->Cell(COL03_WIDTH, 4,number_format($this->serv_total + $this->total_pf_charge, 2, '.', ','), "T", 0, 'R');

        $this->total_total_discount = $this->hosp_total_discount + $this->total_totalDiscount;

        //Discount
        $this->Cell(COL_MID, 4, " ", "", 0, '');
        $this->Cell(COL04_WIDTH, 4, number_format($this->total_total_discount, 2, '.', ','), "T", 0, 'R');

        //Insurance Coverage
        $this->Cell(COL_MID, 4, " ", "", 0, '');
        // $this->total_phic_pf = $this->total_phic_pf + $this->hdata['serv_coverage'];
        // $this->result_total += $this->result_totalPF_total_total;
        //HMO
        $this->Cell(COL05_WIDTH, 4, number_format( $this->total_pf_coverage_phic +  $this->result_totalPF_total_total, 2, '.', ','), "T", 0, 'R');

        $this->Cell(COL_MID, 4, " ", "", 0, '');
// echo  $this->result_total;
        $this->Cell(COL06_WIDTH, 4, number_format($this->total_phic_pf +  $this->result_total, 2, '.', ','), "T", 0, 'R');

        $this->total_total_excess = $this->hosp_total_excess + $this->total_pf_excess;
// echo  $this->total_total_excess = $this->total_pf_excess;
        //Excess
        $this->Cell(COL_MID, 4, " ", "", 0, '');
        $this->Cell(COL07_WIDTH, 4, number_format($this->total_total_excess/*($this->serv_total + $this->hdata['pf_charge']) - $this->hdata['total_coverage']*/, 2, '.', ','), "T", 1, 'R');

//      $this->SetFont("arial", "", "10");
        //added by jasper 04/08/2013
        $prevbill_amt = $this->PreviousBill($this->encounter_nr, $this->bill_ref_nr);
        $this->Less($hdata['pfEX']);

        
        //"SPMC-F-BIL-13"
    }//end of function Totals()

    //added by jasper 04/08/2013
    function PreviousBill ($enc_nr, $bill_nr) {
        //echo $enc_nr . "//" . $bill_nr;
        $objbillinfo = new BillInfo();
        $tot_prevbill_amt = 0;
        $result = $objbillinfo->getPreviousBillAmt($enc_nr, $bill_nr);
        // $result = $objbillinfo->getPrevBal($bill_nr);
        //echo $result;
        if ($result) {
            // while ($row = $result->FetchRow()) {
            //     $n_bill = 0;
            //     if (!empty($row["total_charge"])) $n_bill = $row["total_charge"];
            //     if (!empty($row["total_coverage"])) $n_bill -= $row["total_coverage"];
            //     if (!empty($row["total_computed_discount"])) $n_bill -= $row["total_computed_discount"];
            //     if (!empty($row["total_discount"]) && ($n_bill > 0)) $n_bill -= ($n_bill * $row["total_discount"]);
            //     $tot_prevbill_amt += $n_bill;
            // }
            $tot_prevbill_amt = $result;
        }

        $this->prev_bill_amt = $tot_prevbill_amt;

        if ($tot_prevbill_amt>0) {
            $this->SetFont("Times", "B", "11");
            $this->Ln(2);
    //        $this->Cell(GEN_COL01, 4, "", "", 0, '');
            $this->Cell($this->in2mm(GEN_COL02), 4, "Add :","", 0, '');

            $this->Ln(4);
            $this->SetFont("Times", "", "11");
    //        $this->Cell(GEN_COL02_D, 4, "", "", 0, '');
            $this->Cell(GEN_COL01, 4, "", "", 0, '');
            $this->Cell($this->in2mm(GEN_COL02)-6, 4, "Previous Bill Amount","", 0, '');

            $this->SetFont("Times", "B", "11");

            $this->Cell(COL_MID + COL03_WIDTH + COL_MID + COL04_WIDTH + COL_MID + COL05_WIDTH, 4, " ", "", 0, 'R');

            $this->Cell(COL_MID+28, 4, " ", "", 0, '');
            $this->Cell(COL06_WIDTH, 4, number_format(round($tot_prevbill_amt), 2, '.', ','), "", 1, 'R');
        }
    }
    //added by jasper 04/08/2013

#edited by Nick, 1/4/2014
    function Less($totalExcess){
        $this->SetFont("Times", "B", "11");
        $this->Ln(2);
        $this->Cell($this->in2mm(GEN_COL02), 4, "Less :","", 0, '');

        $deposit = $this->objBill->getPreviousPayments();
        $this->Ln(4);
        if (!is_null($deposit) && $deposit > 0) {
            $this->SetFont("Times", "", "11");
            $this->Cell(GEN_COL01, 4, "", "", 0, '');
            $this->Cell($this->in2mm(GEN_COL02)-6, 4, "Previous Payment (DEPOSIT)","", 0, '');
            $this->Cell(COL_MID + COL03_WIDTH + COL_MID + COL04_WIDTH + COL_MID + COL05_WIDTH + 1.5, 4, "", "", 0, 'R');
            $this->SetFont("Times", "", "11");
            $this->Cell(COL_MID, 4, " ", "", 1, '');
        }

        foreach ($this->objBill->prev_payments as $val) {
            $this->Cell(GEN_COL01, 4, "", "", 0, '');
            $this->Cell($this->in2mm(GEN_COL02)-6, 4, "    OR#: " .$val->getORNo(),"", 0, '');
            $this->Cell(COL_MID + COL03_WIDTH + COL_MID + COL04_WIDTH + COL_MID + COL05_WIDTH + 1.5, 4, "", "", 0, 'R');
            $this->Cell(COL_MID, 4, " ", "", 0, '');
            $this->Cell(COL06_WIDTH, 4, number_format($val->getAmountPaid(), 2, '.', ','), "", 1, 'R');
            $this->deposit_amount += $val->getAmountPaid();
        }
        if ($this->prev_bill_amt<0) {
            $this->SetFont("Times", "", "11");
            $this->Cell(GEN_COL01, 4, "", "", 0, '');
            $this->Cell($this->in2mm(GEN_COL02)-6, 4, "Previous Payment (PARTIAL)","", 0, '');
            $this->Cell(COL_MID + COL03_WIDTH + COL_MID + COL04_WIDTH + COL_MID + COL05_WIDTH + 1.5, 4, "", "", 0, 'R');
            $this->SetFont("Times", "", "11");
            $this->Cell(COL_MID+27, 4, " ", "", 0, '');
            $this->Cell(COL06_WIDTH, 4, number_format(round($this->prev_bill_amt*-1), 2, '.', ','), "", 1, 'R');
        }
        //added by janken 12/29/2014
        if ($this->company_charges = $this->companyAmount($this->encounter_nr)) {
            $this->SetFont("Times", "", "11");
            $this->Cell(GEN_COL01, 4, "", "", 0, '');
            $this->Cell($this->in2mm(GEN_COL02)-6, 4, "Company","", 0, '');
            $this->Cell(COL_MID + COL03_WIDTH + COL_MID + COL04_WIDTH + COL_MID + COL05_WIDTH + 1.5, 4, "", "", 0, 'R');
            $this->SetFont("Times", "", "11");
            $this->Cell(COL_MID+27, 4, " ", "", 0, '');
            $this->Cell(COL06_WIDTH, 4, number_format(round($this->company_charges), 2, '.', ','), "", 1, 'R');
        }
        
        //commented by ken 7/8/2014 nothing to do in dmhi
        // $totalOBpayments = $this->objBill->getOBAnnexPayment(); //TODO8
        // $deposit += $totalOBpayments;
        // if (!is_null($totalOBpayments)) {
        //     $this->SetFont("arial", "", "11");
        //     $this->Cell(GEN_COL01, 4, "", "", 0, '');                                          
        //     $this->Cell($this->in2mm(GEN_COL02)-6, 4, "Previous Payment (Co-Payment)","", 1, '');
        //     foreach ($this->objBill->ob_payments as $val) {
        //         $this->Cell(GEN_COL01, 4, "", "", 0, '');
        //         $this->Cell($this->in2mm(GEN_COL02)-6, 4, "    OR#: " .$val->getORNo(), "", 0, '');
        //         $this->Cell(COL_MID + COL03_WIDTH + COL_MID + COL04_WIDTH + COL_MID + COL05_WIDTH + 1.5, 4, "", "", 0, 'R');
        //         $this->Cell(COL_MID, 4, " ", "", 0, '');
        //         $this->Cell(COL06_WIDTH, 4, number_format($val->getAmountPaid(),2,'.',',') ,"", 1, 'R');
        //         $this->copay_amount += $val->getAmountPaid();

        //     }
        // }

        // $totalDiscount = $this->hdata['total_discount'];
        // if ($this->objBill->isSponsoredMember() || $this->objBill->checkIfPHS($this->encounter_nr) || $this->objBill->isHSM()) {
        //     if($this->objBill->isSponsoredMember()){
        //         $label = "SPONSORED - NO BALANCE BILLING";
        //     }elseif ($this->objBill->isHSM()) {
        //         $label = "HOSPITAL SPONSORED MEMBER";
        //     }else{
        //         $label = "INFIRMARY DISCOUNT";
        //     }

        //remove infirmary discount julz
        $totalDiscount = $this->hdata['total_discount'];
        if ($this->objBill->isSponsoredMember() || $this->objBill->isHSM()) {
            if($this->objBill->isSponsoredMember()){
                $label = "SPONSORED - NO BALANCE BILLING";
            }elseif ($this->objBill->isHSM()) {
                $label = "HOSPITAL SPONSORED MEMBER";
            }
            
            $this->SetFont("Times", "B", "11");
            $this->Ln(4);
            $this->Cell(GEN_COL01, 4, "", "", 0, '');
            $this->Cell($this->in2mm(GEN_COL02)-6, 4, $label, "", 0, '');
            $this->Cell(COL_MID + COL03_WIDTH + COL_MID + COL04_WIDTH + COL_MID + COL05_WIDTH + 1.5, 4, "", "", 0, 'R');
            $this->SetFont("Times", "B", "11");
            $this->Cell(COL_MID, 4, " ", "", 0, '');
            $netExcess = (($this->hdata['total_excess']) + $this->prev_bill_amt) - (round($deposit, 0) + round($totalDiscount, 0) + round($this->prev_bill_amt, 0));
            $this->Cell(COL06_WIDTH, 4, number_format($netExcess + $totalDiscount + $this->prev_bill_amt, 2, '.', ','), "", 0, 'R');
            $netcharges  = 0.00;
        } else {
            //added by daryl
        #function to get discount
              $this->getdiscount();

            $netcharges_ = ((($this->serv_total + $this->hdata['pf_charge']) - $this->hdata['total_coverage']) /*+ $this->prev_bill_amt*/) - (round($deposit, 0) + round($totalDiscount, 0));
            if(!$this->prev_bill_amt)
                $netcharges = ($this->total_total_excess - $this->total_HCI_PF /*+ $this->prev_bill_amt*/) - $this->deposit_amount - $this->copay_amount  - $this->company_charges;
            else
                $netcharges = ($this->total_total_excess - $this->total_HCI_PF + $this->prev_bill_amt) - $this->deposit_amount - $this->copay_amount - $this->company_charges;
            if ($netcharges < 0){
                 $netcharges = 0;
            }

        }
        


        $this->SetFont("Times", "B", "11");
        $this->Ln(6);
        $this->Cell($this->in2mm(GEN_COL02), 4, "AMOUNT DUE :","", 0, '');
        $this->SetFont("Times", "B", "13");
        $this->Cell(COL_MID + COL03_WIDTH + COL_MID + COL04_WIDTH + COL_MID + COL05_WIDTH +  COL06_WIDTH, 4, " ", "", 0, 'R');
        $this->Cell(COL_MID, 4, " ", "", 0, '');
        $this->Cell(COL06_WIDTH, 4, number_format(/*round(*/$netcharges/*)*/, 2, '.', ','), "T", 1, 'R');
        $this->Cell($this->in2mm(GEN_COL02), 4, "","", 0, '');
        $this->Cell(COL_MID + COL03_WIDTH + COL_MID + COL04_WIDTH + COL_MID + COL05_WIDTH  + COL06_WIDTH, 4, " ", "", 0, 'R');
        $this->Cell(COL_MID, 4, "", "", 0, '');
        $this->Cell(COL06_WIDTH + 1, 4, str_repeat("=", 14), "", 1, 'R');
        $this->SetFont("Times", "B", "13");
        $this->ReportFooter();
    }

    function getBillingClerk($slogin_id,$enc,$bill_nr) {
        global $db;

        $sname = '';

        #edited by VAN 02-22-2013
        $with_bill = 0;
        $this->clerk_italized = 0;
        #$strSQL1 = "Select create_id from seg_billing_encounter where encounter_nr ='".$enc."' and is_final = 1";
        $strSQL1 = "Select create_id from seg_billing_encounter where encounter_nr ='".$enc."' AND bill_nr='".$bill_nr."'";
        if ($result1 = $db->Execute($strSQL1)){
            if($result1->RecordCount()){
                $row1= $result1->FetchRow();
                $log_id = $row1['create_id'];
                $with_bill = 1;
            }else{
                $log_id = $slogin_id;
            }
        }

      #edited by VAN 02-22-2013
      #add that the billing clerk must be in billing dept when there is no SAVED BILL yet else the billing clerk is "NO FINAL BILL YET"
      $strSQL = "select pa.location_nr, cp.name_last, cp.name_first,cp.suffix, cp.name_middle ".
                        "   from care_person as cp inner join (care_users as cu inner join care_personell as cper ".
                        "      on cu.personell_nr = cper.nr) on cper.pid = cp.pid ".
                        " INNER JOIN care_personell_assignment pa ON pa.personell_nr=cper.nr ".
                        "   where login_id = '".$log_id."'".
                        "  AND cper.STATUS NOT IN ('deleted','hidden','inactive','void')  ";

        if ($result = $db->Execute($strSQL)) {
            if ($result->RecordCount()) {
                $row = $result->FetchRow();
//              $objb = new BillInfo();
//              $sname = $objb->concatname($row["name_last"], $row["name_first"], $row["name_middle"]);
                #added by VAN 02-22-2013
                #display only clerk from billing dept
                if ($row['location_nr']=='152'){
                    $sname = strtoupper($row["name_first"] . (is_null($row["name_middle"]) || ($row["name_middle"] == '') ? " " : " ".substr($row["name_middle"],0,1).". ").$row["name_last"]);
                }
                else{
                    $this->clerk_italized = 1;
                    if ($with_bill)
                        $sname = "NO NAME BE DISPLAYED, NOT A BILLING CLERK";
                    else
                        $sname = "NOT A BILLING CLERK and NO SAVE BILL YET";
                }
            }
        }

        $this->clerk_name = $sname;
    }

    function getBillingHead() {
        global $db;

        $shname = '';
        $shpos  = '';

        //added by VAN 02-14-2013
        //add AND cper.status NOT IN ('void','hidden','deleted','inactive')
        $strSQL = "select cp.name_last, cp.name_first, cp.suffix, cp.name_middle, cper.job_position, cper.other_title ".
                        "   from care_person as cp inner join (((care_personell as cper inner join care_personell_assignment as cpa ".
                                    "      on cper.nr = cpa.personell_nr) inner join care_department as cd on cpa.location_nr = cd.nr) ".
                                    "      inner join care_role_person as crp on cpa.role_nr = crp.nr) on cp.pid = cper.pid ".
                                    "   where upper(crp.role) regexp 'HEAD' and upper(cd.id) regexp 'BILLING' ".
                                    " AND cper.status NOT IN ('void','hidden','deleted','inactive') ".
                                    "   limit 1";
        if ($result = $db->Execute($strSQL)) {
            if ($result->RecordCount()) {
                $row = $result->FetchRow();

//              $objb = new BillInfo();
//              $shname = $objb->concatname($row["name_last"], $row["name_first"], $row["name_middle"]);
                $row["other_title"] = trim($row["other_title"]);
                $shname = strtoupper($row["name_first"] . (is_null($row["name_middle"]) || ($row["name_middle"] == '') ? " " : " ".substr($row["name_middle"],0,1).". ").$row["name_last"]). ( ( ($row["other_title"] != '') && !is_null($row["other_title"]) ) ? ", ".$row["other_title"] : "" );

                $shpos  = $row["job_position"];
            }
        }

        $this->head_name = $shname;
        $this->head_position = $shpos;
    }

//  function getSuppliesData(){
//      $this->objBill->getSuppliesList(); // gathered all supplies consumed
//      $this->objBill->getSupplyBenefits();
//      $this->objBill->getConfineBenefits('MS', 'S');
//
//      $totalSupConfineCoverage = $this->objBill->getSupConfineCoverage();
//      $supBenefitsArray = $this->objBill->getSupConfineBenefits();
//      $ndiscount        = $this->objBill->getBillAreaDiscount('MS','S');
//
//      $this->Ln(2);
//      $this->Cell(GEN_COL01, 4, "", "", 0, 'C');
//      $this->Cell($this->in2mm(GEN_COL02), 4,"Supplies", "", ($this->IsDetailed && (count($supBenefitsArray) > 0)) ? 1 : 0, '');
//
//      if(is_array($supBenefitsArray)){
//          foreach($supBenefitsArray  as $key=>$value){
//              $acPrice = number_format($value->item_charge, 2, '.', ',');
//              $price   = number_format($value->item_price, 2, '.', ',');
//
//              if ($this->IsDetailed){
//                  $this->Cell(GEN_COL02_D, 4, "", "", 0, '');
//                  $this->Cell(GEN_COL01, 4, "", "", 0, '');
//                  $stmp = ($value->getItemQty() > 1 ? "s" : "");
//
//                  $this->Cell($this->in2mm(GEN_COL02)-6, 4, $value->artikelname." ".$value->getItemQty()." pc".$stmp." @ ".number_format($price, 2, '.', ','), "", 0, '');
//                  $this->Cell(COL_MID, 4, "", "", 0, '');
//                  $this->Cell(COL03_WIDTH, 4, number_format($acPrice, 2, '.', ','), "", 1, 'R');
//              }
//          }
//      }

//      $TotalSupCharge = $this->objBill->getTotalSupCharge();
//      $totalExcess = $TotalSupCharge - $ndiscount - $totalSupConfineCoverage;
//
//      if ($this->IsDetailed && (count($supBenefitsArray) > 0)){
//          $this->Cell(22, 4, "", "", 0, '');
//          $this->Cell(GEN_COL01 + $this->in2mm(GEN_COL02), 4, "", "", 0, 'R');
//          $this->Cell($this->in2mm(GEN_COL02), 4, "", "", 0, 'R');
//          $this->Cell(COL_MID, 4, "", "", 0, '');
//          $this->Cell(COL03_WIDTH, 4, str_repeat("-", 25), "", 0, 'R');
//          $this->Cell(COL_MID, 4, "", "", 0, '');
//          $this->Cell(COL04_WIDTH, 4, str_repeat("-", 20), "", 0, 'R');
//          $this->Cell(COL_MID, 4, "", "", 0, '');
//          $this->Cell(COL05_WIDTH, 4, str_repeat("-", 23), "", 0, 'R');
//          $this->Cell(COL_MID, 4, "", "", 0, '');
//          $this->Cell(COL06_WIDTH, 4, str_repeat("-", 23), "", 1, 'R');
//
//          $this->Cell(22, 4, "", "", 0, '');
//          $this->Cell(GEN_COL01 + $this->in2mm(GEN_COL02), 4, "Sub-Total (Supplies)", "", 0, 'R');
//          $this->Cell($this->in2mm(GEN_COL02), 4, "Sub-Total (Supplies)", "", 0, 'R');
//      }
//
//      $this->Cell(COL_MID, 4, "", "", 0, '');
//      $this->Cell(COL03_WIDTH, 4, number_format($TotalSupCharge, 2, '.', ','), "", 0, 'R');
//      $this->Cell(COL_MID, 4, "", "", 0, '');
//      $this->Cell(COL04_WIDTH, 4, number_format($ndiscount, 2, '.', ','), "", 0, 'R');
//      $this->Cell(COL_MID, 4, "", "", 0, '');
//      $this->Cell(COL05_WIDTH, 4, number_format($totalSupConfineCoverage, 2, '.', ','), "", 0, 'R');
//      $this->Cell(COL_MID, 4, "", "", 0, '');
//      $this->Cell(COL06_WIDTH, 4, number_format($totalExcess, 2, '.', ','), "", 0, 'R');
//      $this->Ln(4);
//
//      $this->totalCharge[SP_AREA] = $TotalSupCharge;
//      $this->totalDiscount[SP_AREA] = $ndiscount;
//      $this->totalCoverage[SP_AREA] = $totalSupConfineCoverage;
//      $this->totalExcess[SP_AREA] = $totalExcess;
//
//  }// end of function getSuppliesData

#added by Nick, 1/3/2014
#edited by Nick, 1/4/2014
    var $total_meds=0;
    function getMedicinesData(){
        $data = array();
        $index = 0;

        $service_type = "meds";
      if ($this->if_pat_phic > 0){
            $get_phic_amnt_x = $this->get_coverage_phic($service_type);
            $get_phic_amnt = $get_phic_amnt_x['amount'];
        }

        if ($this->if_pat_hmo > 0){
            $get_hmo_amnt_x = $this->get_coverage_hmo($service_type);
            $get_hmo_amnt = $get_hmo_amnt_x['amount'];
        }

        $result = $this->objBill->getMedsList();
        if($result){
            while($row=$result->FetchRow()){
                $data[$index] = array("refno"=>$row['refno'],
                              "bestellnum"=>$row['bestellnum'],
                              "artikelname"=>$row['artikelname'],
                              "generic" =>$row['generic'],
                              "flag"=>$row['flag'],
                              "qty"=>$row['qty'],
                              "srv_price"=>number_format($row['price'], 2, '.', ','),
                              "itemcharge"=>number_format($row['itemcharge'], 2, '.', ','),
                              "source"=>$row['source'],
                              "total"=>$row['qty']*$row['price'],
                             );
                $index++;
            }
        }
        // echo $this->objBill->sql;exit();
        // echo json_encode($data);

        $total = 0;

        foreach ($data as $data_key => $data_value) {
            $total += $data_value['total'];
        }
        $this->total_meds = $total;

        if($this->IsDetailed){
            if(count($data)>0){
                $this->Cell($this->in2mm(GEN_COL02), 4,"Drugs & Medicines", "",1, '');
                foreach ($data as $data_key => $data_value) {
                    $this->Cell(GEN_COL01, 4, "", "", 0, '');
                    $this->MultiCell($this->in2mm(GEN_COL02)+12, 4, $data_value['artikelname']."(".$data_value['generic'].")", "", 1, '');
                    $this->Cell(GEN_COL01 + 3.5, 4, "", "", 0, '');
                    // $this->Cell($this->in2mm(GEN_COL02)-10, 4, $data_value['qty']." @ ".$data_value['srv_price']."  (".$data_value['group_desc'].")", "", 0, '');
                    $this->Cell($this->in2mm(GEN_COL02)-10, 4, $data_value['qty']." @ ".$data_value['srv_price']."", "", 0, '');
                    $this->Cell(COL_MID - 1.75, 4, "", "", 0, '');
                    $this->Cell(COL03_WIDTH + 4, 4, number_format($data_value['total'], 2, '.', ','), "", 1, 'R');
                }
                $this->Cell($this->in2mm(GEN_COL02), 4, " ", "", 0, 'R');
                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL03_WIDTH, 4, str_repeat("-", 25), "", 0, 'R');

                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL04_WIDTH, 4, ""/*str_repeat("-", 20)*/, "", 0, 'R');

                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL05_WIDTH, 4, ""/*str_repeat("-", 23)*/, "", 0, 'R');

                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL06_WIDTH, 4, ""/*str_repeat("-", 23)*/, "", 1, 'R');

                $this->Cell($this->in2mm(GEN_COL02), 4, "Sub-Total (Drugs & Medicines)", "", 0, 'R');

                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL03_WIDTH, 4, number_format($total, 2, '.', ','), "", 0, 'R');

                $this->Cell(COL04_WIDTH, 4, number_format($total*$this->global_sc_discount, 2, '.', ','), "", 0, 'R');
                $this->Cell(COL05_WIDTH+3, 4, number_format($get_phic_amnt, 2, '.', ','), "", 0, 'R');
                $this->Cell(COL06_WIDTH+2, 4, number_format($get_hmo_amnt, 2, '.', ','), "", 0, 'R');
                $total_hci_excess_x = (($total - ($total*$this->global_sc_discount) - $get_phic_amnt) - $get_hmo_amnt);
                 if($total_hci_excess_x < 0){
                    $total_hci_excess = 0;
                }else{
                    $total_hci_excess = $total_hci_excess_x;
                 }
                    $this->Cell(COL06_WIDTH+2, 4, number_format($total_hci_excess , 2, '.', ','), "", 0, 'R');

                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL04_WIDTH, 4, ""/*number_format($ndiscount, 2, '.', ',')*/, "", 0, 'R');

                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL05_WIDTH, 4, ""/*number_format($total_confine_coverage, 2, '.', ',')*/, "", 0, 'R');

                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL06_WIDTH, 4, ""/*number_format($excess, 2, '.', ',')*/, "", 1, 'R');
                $this->Cell(COL_MID, 4, "", "", 1, '');

                //added by daryl
                // $this->Cell($this->in2mm(GEN_COL02), 4, $this->result_firmname, "", 0, 'R');
                
                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL03_WIDTH, 4,"", "", 0, 'R');

                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL04_WIDTH, 4, "", "", 0, 'R');

                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL05_WIDTH, 4, "", "", 0, 'R');

                $this->Cell(COL_MID, 4, "", "", 0, '');
                // $this->Cell(COL06_WIDTH, 4, number_format($this->result_med, 2, '.', ','), "", 0, 'R');
                $this->Cell(COL_MID, 4, "", "", 0, '');

                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL07_WIDTH, 4, "", "", 1, 'R');
                $this->Cell(COL_MID, 4, "", "", 1, '');
            }else{
                $this->Cell($this->in2mm(GEN_COL02), 4,"Drugs & Medicines", "",0, '');
                $this->Cell(COL_MID, 4, "", "", 0, '');
                  $this->Cell(COL03_WIDTH, 4, "0.00", "", 0, 'R');
                $this->Cell(COL04_WIDTH, 4,  "0.00", "", 0, 'R');
                $this->Cell(COL05_WIDTH+3, 4,  "0.00", "", 0, 'R');
                $this->Cell(COL06_WIDTH+2, 4,  "0.00", "", 0, 'R');
                $this->Cell(COL03_WIDTH, 4,  "0.00", "", 1, 'R');
            }
        }else{
            $this->Cell($this->in2mm(GEN_COL02), 4,"Drugs & Medicines", "",0, '');
            $this->Cell(COL_MID, 4, "", "", 0, '');
            $this->Cell(COL03_WIDTH, 4, number_format($total,2,'.',','), "", 0, 'R');
              $this->Cell(COL04_WIDTH, 4, number_format($total*$this->global_sc_discount, 2, '.', ','), "", 0, 'R');
              $this->Cell(COL05_WIDTH+3, 4, number_format($get_phic_amnt, 2, '.', ','), "", 0, 'R');
              $this->Cell(COL06_WIDTH+2, 4, number_format($get_hmo_amnt, 2, '.', ','), "", 0, 'R');
               $total_hci_excess_x = (($total - ($total*$this->global_sc_discount) - $get_phic_amnt) - $get_hmo_amnt);
                 if($total_hci_excess_x < 0){
                    $total_hci_excess = 0;
                }else{
                    $total_hci_excess = $total_hci_excess_x;
                 }           
              $this->Cell(COL06_WIDTH+2, 4, number_format($total_hci_excess , 2, '.', ','), "", 1, 'R');

        }
    }//end of function getMedicinesData

    //added by Nick, 12/31/2013 3:43 AM
    //edited by Nick, 1/4/2014
    var $total_xlo=0;
    function getHospitalServiceData() {
        $data = array();
        $index = 0;

        $service_type = "xlo";
        if ($this->if_pat_phic > 0){
            $get_phic_amnt_x = $this->get_coverage_phic($service_type);
            $get_phic_amnt = $get_phic_amnt_x['amount'];
        }

        if ($this->if_pat_hmo > 0){
            $get_hmo_amnt_x = $this->get_coverage_hmo($service_type);
            $get_hmo_amnt = $get_hmo_amnt_x['amount'];
        }

        $result = $this->objBill->getXLOList();
        if($result){
            while($row=$result->FetchRow()){        
                $data[$index] = array("srv_desc"=>$row['service_desc'],
                                            "group_code"=>$row['group_code'],
                                            "group_desc"=>$row['group_desc'],
                                            "srv_price"=>number_format($row['serv_charge'], 2, '.', ','),
                                            "source"=>$row['source'],
                                            "qty"=>$row['qty'],
                                            "total"=>$row['qty'] * $row['serv_charge']
                                           );
                $index++;
            }
        }
// echo json_encode($data);

        $lab_count=0;
        $rad_count=0;
        $sup_count=0;
        $oth_count=0;
        $xlo_count=0;
        foreach ($data as $data_key => $data_value) {//foreach
            $total += $data_value['total'];

            if($data_value['source']=='LB')
                $lab_count++;
            if($data_value['source']=='RD')
                $rad_count++;
            if($data_value['source']=='MS' || $data_value['source']=='SU')
                $sup_count++;
            if($data_value['source']=='OA')
                $oth_count++;
        }//foreach

        $this->total_xlo = $total;
        $xlo_count=$lab_count + $rad_count + $sup_count + $oth_count;
        $this->Cell($this->in2mm(GEN_COL02), 4,"X-Ray, Lab, & Others", "", ($xlo_count>0 && $this->IsDetailed)?1:0, '');
        if($xlo_count > 0){#if1
            if($this->IsDetailed){
                #laboratory -- laboratory -- laboratory
                if($lab_count>0){
                    $this->Cell(GEN_COL01, 4, "", "", 0, '');
                    $this->SetFont("Times", "B", "10");
                    $this->Cell($this->in2mm(GEN_COL02)-6, 4, "Laboratory", "", ($lab_count>0)?1:0, '');
                    $this->SetFont("Times","", "10");
                    if($lab_count>0){
                        foreach ($data as $data_key => $data_value) {
                            if($data_value['source']=='LB'){
                                $this->Cell(GEN_COL01, 4, "", "", 0, '');
                                $this->MultiCell($this->in2mm(GEN_COL02)+12, 4, $data_value['srv_desc'], "", 1, '');
                                $this->Cell(GEN_COL01 + 3.5, 4, "", "", 0, '');
                                $this->MultiCell($this->in2mm(GEN_COL02)+10, 4, $data_value['qty']." @ ".$data_value['srv_price']." (".$data_value['group_desc'].")", "", L, '');       
                                $this->Cell(COL03_WIDTH + 52, -4.15, number_format($data_value['total'],2,'.',','), "", 1, 'R','');
                                $this->Cell(COL_MID - 1.75, 5, "", "", 1, '');
                                // $this->Cell(COL03_WIDTH + 4, 4, number_format($data_value['total'],2,'.',','), "", 1, 'R','');
                            }
                        }
                        $this->Cell(COL_MID, 4, "", "", 1, '');
                    }else{
                        $this->Cell(COL_MID+2, 4, "", "", 0, '');
                           $this->Cell(COL03_WIDTH, 4, "0.00", "", 0, 'R');
                            $this->Cell(COL04_WIDTH, 4,  "0.00", "", 0, 'R');
                            $this->Cell(COL05_WIDTH+3, 4,  "0.00", "", 0, 'R');
                            $this->Cell(COL06_WIDTH+2, 4,  "0.00", "", 0, 'R');
                            $this->Cell(COL03_WIDTH, 4,  "0.00", "", 1, 'R');
                        $this->Cell(COL_MID, 4, "", "", 1, '');
                    }
                }
                #Radiology -- Radiology -- Radiology
                if($rad_count>0){
                    $this->Cell(GEN_COL01, 4, "", "", 0, '');
                    $this->SetFont("Times", "B", "10");
                    $this->Cell($this->in2mm(GEN_COL02)-6, 4, "Radiology", "", ($rad_count>0)?1:0, '');
                    $this->SetFont("Times","", "10");
                    if($rad_count>0){
                        foreach ($data as $data_key => $data_value) {
                            if($data_value['source']=='RD'){
                                $this->Cell(GEN_COL01, 4, "", "", 0, '');
                                $this->Cell($this->in2mm(GEN_COL02)-8, 4, $data_value['srv_desc'], "", 1, '');
                                $this->Cell(GEN_COL01 + 3.5, 4, "", "", 0, '');
                                $this->Cell($this->in2mm(GEN_COL02)-10, 4, $data_value['qty']." @ ".$data_value['srv_price']."  (".$data_value['group_desc'].")", "", 0, '');
                                $this->Cell(COL_MID - 1.75, 4, "", "", 0, '');
                                $this->Cell(COL03_WIDTH + 4, 4, number_format($data_value['total'],2,'.',','), "", 1, 'R');
                            }
                        }
                        $this->Cell(COL_MID, 4, "", "", 1, '');
                    }else{
                        $this->Cell(COL_MID+2, 4, "", "", 0, '');
                           $this->Cell(COL03_WIDTH, 4, "0.00", "", 0, 'R');
                        $this->Cell(COL04_WIDTH, 4,  "0.00", "", 0, 'R');
                        $this->Cell(COL05_WIDTH+3, 4,  "0.00", "", 0, 'R');
                        $this->Cell(COL06_WIDTH+2, 4,  "0.00", "", 0, 'R');
                        $this->Cell(COL03_WIDTH, 4,  "0.00", "", 1, 'R');
                        $this->Cell(COL_MID, 4, "", "", 1, '');
                    }
                }
                #Supplies -- Supplies -- Supplies
                if($sup_count>0){
                    $this->Cell(GEN_COL01, 4, "", "", 0, '');
                    $this->SetFont("Times", "B", "10");
                    $this->Cell($this->in2mm(GEN_COL02)-6, 4, "Supplies", "", ($sup_count>0)?1:0, '');
                    $this->SetFont("Times", "", "10");
                    if($sup_count>0){
                        foreach ($data as $data_key => $data_value) {
                            if($data_value['source']=='SU' || $data_value['source']=='MS'){
                                $this->Cell(GEN_COL01, 4, "", "", 0, '');
                                $this->Cell($this->in2mm(GEN_COL02)-8, 4, $data_value['srv_desc'], "", 1, '');
                                $this->Cell(GEN_COL01 + 3.5, 4, "", "", 0, '');
                                $this->Cell($this->in2mm(GEN_COL02)-10, 4, $data_value['qty']." @ ".$data_value['srv_price']."  (".$data_value['group_desc'].")", "", 0, '');
                                $this->Cell(COL_MID - 1.75, 4, "", "", 0, '');
                                $this->Cell(COL03_WIDTH + 4, 4, number_format($data_value['total'],2,'.',','), "", 1, 'R');
                            }
                        }
                        $this->Cell(COL_MID, 4, "", "", 1, '');
                    }else{
                        $this->Cell(COL_MID+2, 4, "", "", 0, '');
                           $this->Cell(COL03_WIDTH, 4, "0.00", "", 0, 'R');
                        $this->Cell(COL04_WIDTH, 4,  "0.00", "", 0, 'R');
                        $this->Cell(COL05_WIDTH+3, 4,  "0.00", "", 0, 'R');
                        $this->Cell(COL06_WIDTH+2, 4,  "0.00", "", 0, 'R');
                        $this->Cell(COL03_WIDTH, 4,  "0.00", "", 1, 'R');
                        $this->Cell(COL_MID, 4, "", "", 1, '');
                    }
                }
                #Others -- Others -- Others
                if($oth_count>0){
                    $this->Cell(GEN_COL01, 4, "", "", 0, '');
                    $this->SetFont("Times", "B", "10");
                    $this->Cell($this->in2mm(GEN_COL02)-6, 4, "Others", "", ($oth_count>0)?1:0, '');
                    $this->SetFont("Times", "", "10");
                    if($oth_count>0){
                        foreach ($data as $data_key => $data_value) {
                            if($data_value['source']=='OA'){
                                $this->Cell(GEN_COL01, 4, "", "", 0, '');
                                $this->Cell($this->in2mm(GEN_COL02)-8, 4, $data_value['srv_desc'], "", 1, '');
                                $this->Cell(GEN_COL01 + 3.5, 4, "", "", 0, '');
                                $this->Cell($this->in2mm(GEN_COL02)-10, 4, $data_value['qty']." @ ".$data_value['srv_price']."  (".$data_value['group_desc'].")", "", 0, '');
                                $this->Cell(COL_MID - 1.75, 4, "", "", 0, '');
                                $this->Cell(COL03_WIDTH + 4, 4, number_format($data_value['total'],2,'.',','), "", 1, 'R');
                            }
                        }
                        $this->Cell(COL_MID, 4, "", "", 1, '');
                    }else{
                        $this->Cell(COL_MID+2, 4, "", "", 0, '');
                         $this->Cell(COL03_WIDTH, 4, "0.00", "", 0, 'R');
                        $this->Cell(COL04_WIDTH, 4,  "0.00", "", 0, 'R');
                        $this->Cell(COL05_WIDTH+3, 4,  "0.00", "", 0, 'R');
                        $this->Cell(COL06_WIDTH+2, 4,  "0.00", "", 0, 'R');
                        $this->Cell(COL03_WIDTH, 4,  "0.00", "", 1, 'R');
                        $this->Cell(COL_MID, 4, "", "", 1, '');
                    }
                }
                #--------------------------------------------
                $this->Cell($this->in2mm(GEN_COL02), 4, " ", "", 0, 'R');
                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL03_WIDTH, 4, str_repeat("-", 25), "", 0, 'R');

                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL04_WIDTH, 4, ""/*str_repeat("-", 20)*/, "", 0, 'R');

                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL05_WIDTH, 4, ""/*str_repeat("-", 23)*/, "", 0, 'R');

                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL06_WIDTH, 4, ""/*str_repeat("-", 23)*/, "", 1, 'R');

                $this->Cell($this->in2mm(GEN_COL02), 4, "Sub-Total (X-Ray, Lab, & Others)", "", 0, 'R');

                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL03_WIDTH, 4, number_format($total, 2, '.', ','), "", 0, 'R');

                $this->Cell(COL04_WIDTH, 4, number_format($total*$this->global_sc_discount, 2, '.', ','), "", 0, 'R');
                $this->Cell(COL05_WIDTH+3, 4, number_format($get_phic_amnt, 2, '.', ','), "", 0, 'R');
                $this->Cell(COL06_WIDTH+2, 4, number_format($get_hmo_amnt, 2, '.', ','), "", 0, 'R');
                $total_hci_excess_x = (($total - ($total*$this->global_sc_discount) - $get_phic_amnt) - $get_hmo_amnt);
                if($total_hci_excess_x < 0){
                    $total_hci_excess = 0;
                }else{
                    $total_hci_excess = $total_hci_excess_x;
                }
                $this->Cell(COL06_WIDTH+2, 4, number_format($total_hci_excess , 2, '.', ','), "", 0, 'R');
                
                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL04_WIDTH, 4, ""/*number_format($ndiscount, 2, '.', ',')*/, "", 0, 'R');

                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL05_WIDTH, 4, ""/*number_format($total_confine_coverage, 2, '.', ',')*/, "", 0, 'R');

                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL06_WIDTH, 4, ""/*number_format($excess, 2, '.', ',')*/, "", 1, 'R');
                $this->Cell(COL_MID, 4, "", "", 1, '');

                //added by daryl
                // $this->Cell($this->in2mm(GEN_COL02), 4, $this->result_firmname, "", 0, 'R');
                $this->Cell($this->in2mm(GEN_COL02), 4, "", "", 0, 'R');
                
                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL03_WIDTH, 4,"", "", 0, 'R');

                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL04_WIDTH, 4, "", "", 0, 'R');

                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL05_WIDTH, 4, "", "", 0, 'R');

                $this->Cell(COL_MID, 4, "", "", 0, '');
                // $this->Cell(COL06_WIDTH, 4, number_format($this->result_srv, 2, '.', ','), "", 0, 'R');
                $this->Cell(COL06_WIDTH, 4, "", "", 0, 'R');
                $this->Cell(COL_MID, 4, "", "", 0, '');

                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL07_WIDTH, 4, "", "", 1, 'R');
                $this->Cell(COL_MID, 4, "", "", 1, '');

            }else{
                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL03_WIDTH, 4, number_format($total, 2, '.', ','), "", 0, 'R');
                $this->Cell(COL04_WIDTH, 4, number_format($total*$this->global_sc_discount, 2, '.', ','), "", 0, 'R');
                $this->Cell(COL05_WIDTH+3, 4, number_format($get_phic_amnt, 2, '.', ','), "", 0, 'R');
                $this->Cell(COL06_WIDTH+2, 4, number_format($get_hmo_amnt, 2, '.', ','), "", 0, 'R');
                $total_hci_excess_x = (($total - ($total*$this->global_sc_discount) - $get_phic_amnt) - $get_hmo_amnt);
                 if($total_hci_excess_x < 0){
                    $total_hci_excess = 0;
                }else{
                    $total_hci_excess = $total_hci_excess_x;
                }
                $this->Cell(COL06_WIDTH+2, 4, number_format($total_hci_excess_x , 2, '.', ','), "", 1, 'R');              
            }
        }else{
            $this->Cell(COL_MID, 4, "", "", 0, '');
               $this->Cell(COL03_WIDTH, 4, "0.00", "", 0, 'R');
                $this->Cell(COL04_WIDTH, 4,  "0.00", "", 0, 'R');
                $this->Cell(COL05_WIDTH+3, 4,  "0.00", "", 0, 'R');
                $this->Cell(COL06_WIDTH+2, 4,  "0.00", "", 0, 'R');
                $this->Cell(COL03_WIDTH, 4,  "0.00", "", 1, 'R');
        }
    }// end of function LaboratoryData
    //end by Nick

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

#added by Nick, 1/3/2014
#edited by Nick, 1/5/2014
    var $total_accomodation=0;
    var $mandatory_excess=0;
    // function getAccommodationData() {
    //     //added by Nick,12/31/2013
    //     $data = array();
    //     $index = 0;
    //     if($this->death_date != ''){
    //         $todate = $this->death_date;
    //     }else{
    //         $todate = $this->bill_date;
    //     }

    //     if($this->objBill->isERPatient($this->encounter_nr) == 1)
    //         return;

    //     $result = $this->objBill->getAccomodationList();
    //     if($result)
    //     {
    //         while($row=$result->FetchRow())
    //         {   
    //          if ($row['source']=='AD'){
    //             $date_from = date('Y-m-d', strtotime($this->objBill->getCaseDate($this->encounter_nr)));
    //             $date_to = date('Y-m-d', strtotime($todate));
    //             $row['days_stay'] = ((abs(strtotime($date_to)-strtotime($date_from)))/(60*60*24));
    //             $details->name = $row['name'];
    //             $details->room_rate = $row['rm_rate'];
    //             $details->days_stay = $row['days_stay'];
    //             $details->hours_stay = $row['hrs_stay'];
    //             $details->total = $row['rm_rate'] * $details->days_stay;
    //             $details->mandatory_excess = $row['mandatory_excess'];
    //         }else{
    //             $details->name = $row['name'];
    //             $details->room_rate = $row['rm_rate'];
    //             $details->days_stay = $row['days_stay'];
    //             $details->hours_stay = $row['hrs_stay'];
    //             $details->total = $row['rm_rate'] * $details->days_stay;
    //             $details->mandatory_excess = $row['mandatory_excess'];
    //             }

    //         $data[$index] = array("name"=>$details->name,
    //                       "rm_rate"=>$details->room_rate,
    //                       "days_stay"=>$details->days_stay,
    //                       "hrs_stay"=>$details->hours_stay,
    //                       "total"=>$details->total,
    //                       "mandatory_excess"=>$details->mandatory_excess
    //                      );
    //         $index++;
    //         }
    //     }


    //     $total = 0;
    //     if(count($data)>0){
    //         $this->Cell($this->in2mm(GEN_COL02), 4,"Accommodation", "",1, '');
    //         foreach ($data as $acc_key => $acc_attr) {
            
    //             $this->Cell(GEN_COL01, 4, "", "", 0, '');
    //             $this->Cell($this->in2mm(GEN_COL02)-6, 4, $acc_attr['name'], "", 1, '');
    //             $this->Cell(GEN_COL01+2, 4, "", "", 0, '');
    //             $this->Cell($this->in2mm(GEN_COL02)-8, 4,  $acc_attr['days_stay']." days & ".$acc_attr['hrs_stay']." hrs @ ".number_format($acc_attr['rm_rate'], 2, '.', ','), "", 0, '');
    //             $this->Cell(GEN_COL01, 4, "", "", 0, '');
    //             $this->Cell(COL03_WIDTH, 4, number_format($acc_attr['total'], 2, '.', ','), "", 1, 'R');
                
    //             $this->mandatory_excess += $acc_attr['days_stay'] * $acc_attr['mandatory_excess'];

    //             $total += $acc_attr['total'];
    //         }
    //         $this->total_accomodation = $total;
            
    //         if($this->IsDetailed){
    //             $this->Cell($this->in2mm(GEN_COL02), 4, " ", "", 0, 'R');
    //             $this->Cell(COL_MID, 4, "", "", 0, '');
    //             $this->Cell(COL03_WIDTH, 4, str_repeat("-", 25), "", 0, 'R');

    //             $this->Cell(COL_MID, 4, "", "", 0, '');
    //             $this->Cell(COL04_WIDTH, 4, ""/*str_repeat("-", 20)*/, "", 0, 'R');

    //             $this->Cell(COL_MID, 4, "", "", 0, '');
    //             $this->Cell(COL05_WIDTH, 4, ""/*str_repeat("-", 23)*/, "", 0, 'R');

    //             $this->Cell(COL_MID, 4, "", "", 0, '');
    //             $this->Cell(COL06_WIDTH, 4, ""/*str_repeat("-", 23)*/, "", 1, 'R');

    //             $this->Cell($this->in2mm(GEN_COL02), 4, "Sub-Total (Accomodation)", "", 0, 'R');

    //             $this->Cell(COL_MID, 4, "", "", 0, '');
    //             $this->Cell(COL03_WIDTH, 4, number_format($total, 2, '.', ','), "", 0, 'R');

    //             $this->Cell(COL_MID, 4, "", "", 0, '');
    //             $this->Cell(COL04_WIDTH, 4, ""/*number_format($ndiscount, 2, '.', ',')*/, "", 0, 'R');

    //             $this->Cell(COL_MID, 4, "", "", 0, '');
    //             $this->Cell(COL05_WIDTH, 4, ""/*number_format($total_confine_coverage, 2, '.', ',')*/, "", 0, 'R');

    //             $this->Cell(COL_MID, 4, "", "", 0, '');
    //             $this->Cell(COL06_WIDTH, 4, "", "", 0, 'R');
    //             $this->Cell(COL_MID, 4, "", "", 0, '');

    //             $this->Cell(COL_MID, 4, "", "", 0, '');
    //             $this->Cell(COL07_WIDTH, 4, ""/*number_format($excess, 2, '.', ',')*/, "", 1, 'R');
    //             $this->Cell(COL_MID, 4, "", "", 1, '');

    //             //added by daryl
    //             $this->Cell($this->in2mm(GEN_COL02), 4, $this->result_firmname, "", 0, 'R');
                
    //             $this->Cell(COL_MID, 4, "", "", 0, '');
    //             $this->Cell(COL03_WIDTH, 4,"", "", 0, 'R');

    //             $this->Cell(COL_MID, 4, "", "", 0, '');
    //             $this->Cell(COL04_WIDTH, 4, "", "", 0, 'R');

    //             $this->Cell(COL_MID, 4, "", "", 0, '');
    //             $this->Cell(COL05_WIDTH, 4, "", "", 0, 'R');

    //             $this->Cell(COL_MID, 4, "", "", 0, '');
    //             $this->Cell(COL06_WIDTH, 4, number_format($this->result_acc, 2, '.', ','), "", 0, 'R');
    //             $this->Cell(COL_MID, 4, "", "", 0, '');

    //             $this->Cell(COL_MID, 4, "", "", 0, '');
    //             $this->Cell(COL07_WIDTH, 4, "", "", 1, 'R');
    //             $this->Cell(COL_MID, 4, "", "", 1, '');

    //         }           
    //     }else{
    //         $this->Cell($this->in2mm(GEN_COL02)+2, 4,"Accommodation", "",0, '');
    //         $this->Cell(COL03_WIDTH, 4, "0.00", "", 1, 'R');
    //         $this->total_accomodation = 0;
    //     }
    //     //end nick

    // }// end of function getAccommodationData

function getDayDiff($isFirstAd,$to,$from,$bill_date){
    $tmpTo = strtotime($to);
    $tmpFrom = strtotime($from);
    $tmpBill = strtotime($bill_date);

    $tmpTo = strtotime(date('Y-m-d',$tmpTo));
    $tmpFrom = strtotime(date('Y-m-d',$tmpFrom));
    $tmpBill = strtotime(date('Y-m-d',$tmpBill));

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
    function getAccommodationData() {
        //added by Nick,12/31/2013
        $data = array();
        $index = 0;
        $service_type = "acc";

        if ($this->if_pat_phic > 0){
            $get_phic_amnt_x = $this->get_coverage_phic($service_type);
            $get_phic_amnt = $get_phic_amnt_x['amount'];
        }

        if ($this->if_pat_hmo > 0){
            $get_hmo_amnt_x = $this->get_coverage_hmo($service_type);
            $get_hmo_amnt = $get_hmo_amnt_x['amount'];
        }

        if($this->death_date != ''){
            $todate = $this->death_date;
        }else{
            $todate = $this->bill_date;
        }

        if($this->objBill->isERPatient($this->encounter_nr) == 1)
            return;

        $total = 0;
        $result = $this->objBill->getAccomodationList();
        $arr_accomodations = array();

        if($this->IsDetailed){
              if($result->RecordCount() > 0)
                $this->Cell($this->in2mm(GEN_COL02)-2, 4,"Accommodation", "",1, '');
              else{
                $this->Cell($this->in2mm(GEN_COL02)+2, 4,"Accommodation", "",1, '');
                $this->Cell(COL03_WIDTH, 4, "0.00", "", 0, 'R');
                $this->Cell(COL04_WIDTH, 4,  "0.00", "", 0, 'R');
                $this->Cell(COL05_WIDTH+3, 4,  "0.00", "", 0, 'R');
                $this->Cell(COL06_WIDTH+2, 4,  "0.00", "", 0, 'R');
                $this->Cell(COL03_WIDTH, 4,  "0.00", "", 1, 'R');
              }
        }else{
              if($result->RecordCount() > 0)
                $this->Cell($this->in2mm(GEN_COL02)-2, 4,"Accommodation", "",0, '');
              else{
                $this->Cell($this->in2mm(GEN_COL02)+2, 4,"Accommodation", "",0, '');
                $this->Cell(COL03_WIDTH, 4, "0.00", "", 0, 'R');
                $this->Cell(COL04_WIDTH, 4,  "0.00", "", 0, 'R');
                $this->Cell(COL05_WIDTH+3, 4,  "0.00", "", 0, 'R');
                $this->Cell(COL06_WIDTH+2, 4,  "0.00", "", 0, 'R');
                $this->Cell(COL03_WIDTH, 4,  "0.00", "", 1, 'R');

              }
        }

      


        if($result->RecordCount() > 1){
            while($row = $result->FetchRow()){
                array_push($arr_accomodations, $row);
            }
            
            $is_first_admission_day = true;
            $index = 0;
            foreach ($arr_accomodations as $key => $row){
                $this->accomodation_type = $row['accomodation_type'];
                if($row['source']=='AD'){
                    if($is_first_admission_day){
                        $date_to = $row['date_to'] . ' ' . $row['time_to'];
                        $tmp_datetime = strtotime($this->objBill->getCaseDate($this->encounter_nr));

                        if($tmp_datetime <= 0){
                            $ahead_to = $arr_accomodations[$index+1]['date_from'] . ' ' . $arr_accomodations[$index+1]['time_from'];
                            if(!isset($ahead_to)){
                                $ahead_to = $bill_dte;
                            }
                        }

                        $date_from = date('Y-m-d h:i:s', $tmp_datetime);
                        $diff = $this->getDayDiff($is_first_admission_day,$date_to,$date_from,$todate);
                        $row['days_stay'] = (($diff==0)? 1 : $diff);
                        $is_first_admission_day = false;
                    }else{
                        $ahead_to = $arr_accomodations[$index+1]['date_from'] . ' ' . $arr_accomodations[$index+1]['time_from'];
                        if(!isset($ahead_to)){
                            $ahead_to = $bill_dte;
                        }
                        $date_from = $row['date_from'] . ' ' . $row['time_from'];
                        $diff = $this->getDayDiff($is_first_admission_day,$date_to,$date_from,$todate);
                        $row['days_stay'] = (($diff==0)? 1 : $diff);
                    }
                    if($row['days_stay'] > 0){
                        $charges = $row['days_stay'] * $row['rm_rate'];
                        if($this->IsDetailed){
                        $this->Cell(GEN_COL01, 4, "", "", 0, '');
                        $this->Cell($this->in2mm(GEN_COL02)-6, 4, $row['name'], "", 1, '');
                        $this->Cell(GEN_COL01+2, 4, "", "", 0, '');
                        $this->Cell($this->in2mm(GEN_COL02)-8, 4,  $row['days_stay']." days & ".$row['hrs_stay']." hrs @ ".number_format($row['rm_rate'], 2, '.', ','), "", 0, '');
                        $this->Cell(GEN_COL01, 4, "", "", 0, '');
                        $this->Cell(COL03_WIDTH, 4, number_format($charges, 2, '.', ','), "", 1, 'R');
                        }

                        $this->mandatory_excess += $row['days_stay'] * $row['mandatory_excess'];
                        $total += $charges;
                        
                        // $this->Cell(COL04_WIDTH, 4, number_format($charges*$this->global_sc_discount, 2, '.', ','), "", 1, 'R');
                       
                        
                    }
                }else{
                    if($row['days_stay'] > 0){
                        $charges = $row['days_stay'] * $row['rm_rate'];
                        if($this->IsDetailed){
                        $this->Cell(GEN_COL01, 4, "", "", 0, '');
                        $this->Cell($this->in2mm(GEN_COL02)-6, 4, $row['name'], "", 1, '');
                        $this->Cell(GEN_COL01+2, 4, "", "", 0, '');
                        $this->Cell($this->in2mm(GEN_COL02)-8, 4,  $row['days_stay']." days & ".$row['hrs_stay']." hrs @ ".number_format($row['rm_rate'], 2, '.', ','), "", 0, '');
                        $this->Cell(GEN_COL01, 4, "", "", 0, '');
                        $this->Cell(COL03_WIDTH, 4, number_format($charges, 2, '.', ','), "", 1, 'R');
                        // $this->Cell(COL04_WIDTH, 4, number_format($charges*$this->global_sc_discount, 2, '.', ','), "", 1, 'R');

                        }
                       
                        $this->mandatory_excess += $row['days_stay'] * $row['mandatory_excess'];
                        $total += $charges;

                     
              
                     }
                }
                $index++;
            }
        if(!$this->IsDetailed){
                        $this->Cell(COL03_WIDTH+4, 4, number_format($total, 2, '.', ','), "", 0, 'R');
                        $this->Cell(COL04_WIDTH, 4, number_format($total*$this->global_sc_discount, 2, '.', ','), "", 0, 'R');
                        $this->Cell(COL05_WIDTH+3, 4, number_format($get_phic_amnt, 2, '.', ','), "", 0, 'R');
                        $this->Cell(COL06_WIDTH+2, 4, number_format($get_hmo_amnt, 2, '.', ','), "", 0, 'R');
                        $total_hci_excess_x = (($total - ($total*$this->global_sc_discount) - $get_phic_amnt) - $get_hmo_amnt);
                          if($total_hci_excess_x < 0){
                                $total_hci_excess = 0;
                            }else{
                                $total_hci_excess = $total_hci_excess_x;
                            }
                        $this->Cell(COL06_WIDTH+2, 4, number_format($total_hci_excess , 2, '.', ','), "", 1, 'R');
        }
                      

        }else{
            $is_first_admission_day = true;
            while($row = $result->FetchRow()){
                $this->accomodation_type = $row['accomodation_type'];
                if($row['source']=='AD'){
                    $date_to = $row['date_to'] . ' ' . $row['time_to'];
                    if($is_first_admission_day){
                        $tmp_datetime = strtotime($this->objBill->getCaseDate($this->encounter_nr));
                        $date_from = date('Y-m-d h:i:s', $tmp_datetime);
                        $diff = $this->getDayDiff($is_first_admission_day,$date_to,$date_from,$todate);
                        $row['days_stay'] = (($diff==0)? 1 : $diff);
                        $is_first_admission_day = false;
                    }else{
                        $tmp_datetime = strtotime($row['date_from'] . ' ' . $row['time_from']);
                        $date_from = date('Y-m-d h:i:s', $tmp_datetime);
                        $diff = $this->getDayDiff($is_first_admission_day,$date_to,$date_from,$todate);
                        $row['days_stay'] = (($diff==0)? 1 : $diff);
                    }
                    if($row['days_stay'] > 0){
                        $charges = $row['days_stay'] * $row['rm_rate'];
                    if($this->IsDetailed){
                        $this->Cell(GEN_COL01, 4, "", "", 0, '');
                        $this->Cell($this->in2mm(GEN_COL02)-6, 4, $row['name'], "", 1, '');
                        $this->Cell(GEN_COL01+2, 4, "", "", 0, '');
                        $this->Cell($this->in2mm(GEN_COL02)-8, 4,  $row['days_stay']." days & ".$row['hrs_stay']." hrs @ ".number_format($row['rm_rate'], 2, '.', ','), "", 0, '');
                        $this->Cell(GEN_COL01, 4, "", "", 0, '');
                        $this->Cell(COL03_WIDTH, 4, number_format($charges, 2, '.', ','), "", 0, 'R');
                    }else{
                        $this->Cell(COL03_WIDTH+4, 4, number_format($charges, 2, '.', ','), "", 0, 'R');
                    }

                        

                        $this->mandatory_excess += $row['days_stay'] * $row['mandatory_excess'];
                        $total += $charges;

                        $this->Cell(COL04_WIDTH, 4, number_format($charges*$this->global_sc_discount, 2, '.', ','), "", 0, 'R');
                        $this->Cell(COL05_WIDTH+3, 4, number_format($get_phic_amnt, 2, '.', ','), "", 0, 'R');
                        $this->Cell(COL06_WIDTH+2, 4, number_format($get_hmo_amnt, 2, '.', ','), "", 0, 'R');
                        $total_hci_excess_x = (($charges - ($charges*$this->global_sc_discount) - $get_phic_amnt) - $get_hmo_amnt);
                          if($total_hci_excess_x < 0){
                                $total_hci_excess = 0;
                            }else{
                                $total_hci_excess = $total_hci_excess_x;
                            }
                        $this->Cell(COL06_WIDTH+2, 4, number_format($total_hci_excess , 2, '.', ','), "", 1, 'R');

                    }
                }else{
                    if($row['days_stay'] > 0){
                        $charges = $row['days_stay'] * $row['rm_rate'];
                        $this->Cell(GEN_COL01, 4, "", "", 0, '');
                    if($this->IsDetailed){

                        $this->Cell($this->in2mm(GEN_COL02)-6, 4, $row['name'], "", 1, '');
                        $this->Cell(GEN_COL01+2, 4, "", "", 0, '');
                        $this->Cell($this->in2mm(GEN_COL02)-8, 4,  $row['days_stay']." days & ".$row['hrs_stay']." hrs @ ".number_format($row['rm_rate'], 2, '.', ','), "", 0, '');
                        $this->Cell(GEN_COL01, 4, "", "", 0, '');
                    }
                        $this->Cell(COL03_WIDTH, 4, number_format($charges, 2, '.', ','), "", 0, 'R');

                        $this->mandatory_excess += $row['days_stay'] * $row['mandatory_excess'];
                        $total += $charges;

                        $this->Cell(COL04_WIDTH, 4, number_format($charges*$this->global_sc_discount, 2, '.', ','), "", 0, 'R');
                        $this->Cell(COL05_WIDTH+3, 4, number_format($get_phic_amnt, 2, '.', ','), "", 0, 'R');
                        $this->Cell(COL06_WIDTH+2, 4, number_format($get_hmo_amnt, 2, '.', ','), "", 0, 'R');
                        $total_hci_excess_x = (($charges - ($charges*$this->global_sc_discount) - $get_phic_amnt) - $get_hmo_amnt);
                           if($total_hci_excess_x < 0){
                                $total_hci_excess = 0;
                            }else{
                                $total_hci_excess = $total_hci_excess_x;
                            }
                        $this->Cell(COL06_WIDTH+2, 4, number_format($total_hci_excess , 2, '.', ','), "", 1, 'R');

                    }
                }
            }
            
        }

        $this->total_accomodation = $total;

        if($this->IsDetailed && $result->RecordCount() > 0){
            $this->Cell($this->in2mm(GEN_COL02), 4, " ", "", 0, 'R');
            $this->Cell(COL_MID, 4, "", "", 0, '');
            $this->Cell(COL03_WIDTH, 4, str_repeat("-", 25), "", 0, 'R');

            $this->Cell(COL_MID, 4, "", "", 0, '');
            $this->Cell(COL04_WIDTH, 4, ""/*str_repeat("-", 20)*/, "", 0, 'R');

            $this->Cell(COL_MID, 4, "", "", 0, '');
            $this->Cell(COL05_WIDTH, 4, ""/*str_repeat("-", 23)*/, "", 0, 'R');

            $this->Cell(COL_MID, 4, "", "", 0, '');
            $this->Cell(COL06_WIDTH, 4, ""/*str_repeat("-", 23)*/, "", 1, 'R');

            $this->Cell($this->in2mm(GEN_COL02), 4, "Sub-Total (Accomodation)", "", 0, 'R');

            $this->Cell(COL_MID, 4, "", "", 0, '');
            $this->Cell(COL03_WIDTH, 4, number_format($total, 2, '.', ','), "", 0, 'R');

            $this->Cell(COL04_WIDTH, 4, number_format($total*$this->global_sc_discount, 2, '.', ','), "", 0, 'R');

            $this->Cell(COL05_WIDTH+3, 4, number_format($get_phic_amnt, 2, '.', ','), "", 0, 'R');
             $this->Cell(COL06_WIDTH+2, 4, number_format($get_hmo_amnt, 2, '.', ','), "", 0, 'R');
             $total_hci_excess_x = (($total - ($total*$this->global_sc_discount) - $get_phic_amnt) - $get_hmo_amnt);

            if($total_hci_excess_x < 0){
                $total_hci_excess = 0;
            }else{
                $total_hci_excess = $total_hci_excess_x;
            }

            $this->Cell(COL06_WIDTH+2, 4, number_format($total_hci_excess , 2, '.', ','), "", 0, 'R');

            $this->Cell(COL_MID, 4, "", "", 0, '');
            $this->Cell(COL04_WIDTH, 4, ""/*number_format($ndiscount, 2, '.', ',')*/, "", 0, 'R');

            $this->Cell(COL_MID, 4, "", "", 0, '');
            $this->Cell(COL05_WIDTH, 4, ""/*number_format($total_confine_coverage, 2, '.', ',')*/, "", 0, 'R');

            $this->Cell(COL_MID, 4, "", "", 0, '');
            $this->Cell(COL06_WIDTH, 4, ""/*number_format($excess, 2, '.', ',')*/, "", 1, 'R');
            $this->Cell(COL_MID, 4, "", "", 1, '');
        }

    }// end of function getAccommodationData

    //added by Nick, 12/31/2013 4:40 AM
    //edited by Nick, 1/4/2014
    var $total_ops=0;
    function getOpsCharges(){
        $data = array();
        $index = 0;

          $service_type = "or";
      if ($this->if_pat_phic > 0){
            $get_phic_amnt_x = $this->get_coverage_phic($service_type);
            $get_phic_amnt = $get_phic_amnt_x['amount'];
        }

        if ($this->if_pat_hmo > 0){
            $get_hmo_amnt_x = $this->get_coverage_hmo($service_type);
            $get_hmo_amnt = $get_hmo_amnt_x['amount'];
        }

        $this->objBill->getOpBenefits();
        $opsBenefitsArray = $this->objBill->hsp_ops_benefits;
        foreach ($opsBenefitsArray as $key=>$value) {
            $data[$index] = array("desc"=>$value->op_desc,
                                  "rvu"=>$value->op_rvu,
                                  "multiplier"=>$value->op_multiplier,
                                  "total"=>$value->getOpCharge()
                                 );
            $index++;
        }


        $total = 0;

        foreach ($data as $data_key => $data_value) {
            $total += $data_value['total'];
        }
        $this->total_ops = $total;

        if(count($data)>0){
            if($this->IsDetailed){
                $this->Cell($this->in2mm(GEN_COL02), 4,"Operating/Delivery Room", "",1, '');
                foreach ($data as $data_key => $data_value) {
                    $this->Cell(GEN_COL01, 4, "", "", 0, '');
                    $this->Cell($this->in2mm(GEN_COL02)-6, 4, $data_value['desc'], "", 0, '');
                    $this->Cell(COL_MID+1.8, 4, "", "", 0, '');
                    $this->Cell(COL03_WIDTH, 4, number_format($data_value['total'], 2, '.', ','), "", 1, 'R');
                }

                $this->Cell($this->in2mm(GEN_COL02), 4, " ", "", 0, 'R');
                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL03_WIDTH, 4, str_repeat("-", 25), "", 0, 'R');

                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL04_WIDTH, 4, ""/*str_repeat("-", 20)*/, "", 0, 'R');

                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL05_WIDTH, 4, ""/*str_repeat("-", 23)*/, "", 0, 'R');

                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL06_WIDTH, 4, ""/*str_repeat("-", 23)*/, "", 1, 'R');

                $this->Cell($this->in2mm(GEN_COL02), 4, "Sub-Total (Operating/Delivery Room)", "", 0, 'R');

                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL03_WIDTH, 4, number_format($total, 2, '.', ','), "", 0, 'R');

                $this->Cell(COL04_WIDTH, 4, number_format($total*$this->global_sc_discount, 2, '.', ','), "", 0, 'R');
                 $this->Cell(COL05_WIDTH+3, 4, number_format($get_phic_amnt, 2, '.', ','), "", 0, 'R');
                 $this->Cell(COL06_WIDTH+2, 4, number_format($get_hmo_amnt, 2, '.', ','), "", 0, 'R');

                 $total_hci_excess_x = (($total - ($total*$this->global_sc_discount) - $get_phic_amnt) - $get_hmo_amnt);
                 if($total_hci_excess_x < 0){
                    $total_hci_excess = 0;
                  }else{
                       $total_hci_excess = $total_hci_excess_x;
                 }
                $this->Cell(COL06_WIDTH+2, 4, number_format($total_hci_excess , 2, '.', ','), "", 1, 'R');

                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL04_WIDTH, 4, ""/*number_format($ndiscount, 2, '.', ',')*/, "", 0, 'R');

                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL05_WIDTH, 4, ""/*number_format($total_confine_coverage, 2, '.', ',')*/, "", 0, 'R');

                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL06_WIDTH, 4, ""/*number_format($excess, 2, '.', ',')*/, "", 1, 'R');
                $this->Cell(COL_MID, 4, "", "", 1, '');

                //added by daryl
                // $this->Cell($this->in2mm(GEN_COL02), 4, $this->result_firmname, "", 0, 'R');
                
                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL03_WIDTH, 4,"", "", 0, 'R');

                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL04_WIDTH, 4, "", "", 0, 'R');

                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL05_WIDTH, 4, "", "", 0, 'R');

                $this->Cell(COL_MID, 4, "", "", 0, '');
                // $this->Cell(COL06_WIDTH, 4, number_format($this->result_ops, 2, '.', ','), "", 0, 'R');
                $this->Cell(COL_MID, 4, "", "", 0, '');

                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL07_WIDTH, 4, "", "", 1, 'R');
                $this->Cell(COL_MID, 4, "", "", 1, '');


            }else{
                $this->Cell($this->in2mm(GEN_COL02), 4,"Operating/Delivery Room", "",0, '');
                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL03_WIDTH, 4, number_format($total, 2, '.', ','), "", 0, 'R');
                $this->Cell(COL04_WIDTH, 4, number_format($total*$this->global_sc_discount, 2, '.', ','), "", 0, 'R');
                $this->Cell(COL05_WIDTH+3, 4, number_format($get_phic_amnt, 2, '.', ','), "", 0, 'R');
                $this->Cell(COL06_WIDTH+2, 4, number_format($get_hmo_amnt, 2, '.', ','), "", 0, 'R');
             $total_hci_excess_x = (($total - ($total*$this->global_sc_discount) - $get_phic_amnt) - $get_hmo_amnt);
            if($total_hci_excess_x < 0){
               $total_hci_excess = 0;
           }else{
              $total_hci_excess = $total_hci_excess_x;
           }
             $this->Cell(COL06_WIDTH+2, 4, number_format($total_hci_excess , 2, '.', ','), "", 1, 'R');
            }
        }else{
            $this->Cell($this->in2mm(GEN_COL02), 4,"Operating/Delivery Room", "",0, '');
            $this->Cell(COL_MID, 4, "", "", 0, '');
               $this->Cell(COL03_WIDTH, 4, "0.00", "", 0, 'R');
                $this->Cell(COL04_WIDTH, 4,  "0.00", "", 0, 'R');
                $this->Cell(COL05_WIDTH+3, 4,  "0.00", "", 0, 'R');
                $this->Cell(COL06_WIDTH+2, 4,  "0.00", "", 0, 'R');
                $this->Cell(COL03_WIDTH, 4,  "0.00", "", 1, 'R');
    

        }
    }
    //end by Nick

    //added by Nick, 12/31/2013 5:09 AM
    //edited by Nick, 1/4/2014
    var $total_misc = 0;
    function getMiscellaneousCharges() {
        $data = array();
        $index=0;
        
              $service_type = "misc";
      if ($this->if_pat_phic > 0){
            $get_phic_amnt_x = $this->get_coverage_phic($service_type);
            $get_phic_amnt = $get_phic_amnt_x['amount'];
        }

        if ($this->if_pat_hmo > 0){
            $get_hmo_amnt_x = $this->get_coverage_hmo($service_type);
            $get_hmo_amnt = $get_hmo_amnt_x['amount'];
        }

        $result = $this->objBill->getMiscList();
        if($result){
            while($row=$result->FetchRow()){        
                $data[$index] = array("name"=>$row['name'],
                                      "desc"=>$row['description'],
                                      "qty"=>$row['qty'],
                                      "chrg"=>($row['avg_chrg']*$row['qty'])
                                     );
                $index++;
            }
        }


        $total = 0;

        foreach ($data as $data_key => $data_value) {
            $total += $data_value['chrg'];
        }
        $this->total_misc = $total;

        if(count($data)>0){
            if($this->IsDetailed){
                $this->Cell($this->in2mm(GEN_COL02), 4,"Miscellaneous", "",1, '');
                foreach ($data as $data_key => $data_value) {
                    $this->Cell(GEN_COL01, 4, "", "", 0, '');
                    $this->Cell($this->in2mm(GEN_COL02)-6, 4, $data_value['name'], "", 0, '');
                    $this->Cell(COL_MID+1.8, 4, "", "", 0, '');
                    $this->Cell(COL03_WIDTH, 4, number_format($data_value['chrg'], 2, '.', ','), "", 1, 'R');
                }

                $this->Cell($this->in2mm(GEN_COL02), 4, " ", "", 0, 'R');
                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL03_WIDTH, 4, str_repeat("-", 25), "", 0, 'R');

                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL04_WIDTH, 4, ""/*str_repeat("-", 20)*/, "", 0, 'R');

                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL05_WIDTH, 4, ""/*str_repeat("-", 23)*/, "", 0, 'R');

                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL06_WIDTH, 4, ""/*str_repeat("-", 23)*/, "", 1, 'R');

                $this->Cell($this->in2mm(GEN_COL02), 4, "Sub-Total (Miscellaneous)", "", 0, 'R');

                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL03_WIDTH, 4, number_format($total, 2, '.', ','), "", 0, 'R');

                $this->Cell(COL04_WIDTH, 4, number_format($total*$this->global_sc_discount, 2, '.', ','), "", 0, 'R');
                 $this->Cell(COL05_WIDTH+3, 4, number_format($get_phic_amnt, 2, '.', ','), "", 0, 'R');
                 $this->Cell(COL06_WIDTH+2, 4, number_format($get_hmo_amnt, 2, '.', ','), "", 0, 'R');
             $total_hci_excess_x = (($total - ($total*$this->global_sc_discount) - $get_phic_amnt) - $get_hmo_amnt);
            if($total_hci_excess_x < 0){
               $total_hci_excess = 0;
             }else{
               $total_hci_excess = $total_hci_excess_x;
             }

                $this->Cell(COL06_WIDTH+2, 4, number_format($total_hci_excess , 2, '.', ','), "", 0, 'R');

                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL04_WIDTH, 4, ""/*number_format($ndiscount, 2, '.', ',')*/, "", 0, 'R');

                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL05_WIDTH, 4, ""/*number_format($total_confine_coverage, 2, '.', ',')*/, "", 0, 'R');

                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL06_WIDTH, 4, ""/*number_format($excess, 2, '.', ',')*/, "", 1, 'R');

                //added by daryl
                // $this->Cell($this->in2mm(GEN_COL02), 4, $this->result_firmname, "", 0, 'R');
                
                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL03_WIDTH, 4,"", "", 0, 'R');

                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL04_WIDTH, 4, "", "", 0, 'R');

                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL05_WIDTH, 4, "", "", 0, 'R');

                $this->Cell(COL_MID, 4, "", "", 0, '');
                // $this->Cell(COL06_WIDTH, 4, number_format($this->result_msc, 2, '.', ','), "", 0, 'R');
                $this->Cell(COL_MID, 4, "", "", 0, '');

                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL07_WIDTH, 4, "", "", 1, 'R');
                $this->Cell(COL_MID, 4, "", "", 1, '');
            }else{
                $this->Cell($this->in2mm(GEN_COL02), 4,"Miscellaneous", "",0, '');
                $this->Cell(COL_MID, 4, "", "", 0, '');
                $this->Cell(COL03_WIDTH, 4, number_format($total, 2, '.', ','), "", 0, 'R');
                $this->Cell(COL04_WIDTH, 4, number_format($total*$this->global_sc_discount, 2, '.', ','), "", 0, 'R');
                 $this->Cell(COL05_WIDTH+3, 4, number_format($get_phic_amnt, 2, '.', ','), "", 0, 'R');
                 $this->Cell(COL06_WIDTH+2, 4, number_format($get_hmo_amnt, 2, '.', ','), "", 0, 'R');
               $total_hci_excess_x = (($total - ($total*$this->global_sc_discount) - $get_phic_amnt) - $get_hmo_amnt);
                if($total_hci_excess_x < 0){
                   $total_hci_excess = 0;
                 }else{
                   $total_hci_excess = $total_hci_excess_x;
                 }
                   $this->Cell(COL06_WIDTH+2, 4, number_format($total_hci_excess_x , 2, '.', ','), "", 1, 'R');               
            }
        }else{
            $this->Cell($this->in2mm(GEN_COL02), 4,"Miscellaneous", "",0, '');
            $this->Cell(COL_MID, 4, "", "", 0, '');
               $this->Cell(COL03_WIDTH, 4, "0.00", "", 0, 'R');
                $this->Cell(COL04_WIDTH, 4,  "0.00", "", 0, 'R');
                $this->Cell(COL05_WIDTH+3, 4,  "0.00", "", 0, 'R');
                $this->Cell(COL06_WIDTH+2, 4,  "0.00", "", 0, 'R');
                $this->Cell(COL03_WIDTH, 4,  "0.00", "", 1, 'R');
        }
    }
    //end by Nick

    //added by daryl
    function getadd_insurance($bill_nr){
        global $db;
        
    $SQL="SELECT 
          cif.`firm_id` AS firmname,
          sbc.`total_acc_coverage` AS acc,
          sbc.`total_med_coverage` AS med,
          sbc.`total_msc_coverage` AS msc,
          sbc.`total_ops_coverage` AS ops,
          sbc.`total_services_coverage` AS serv,
          sbc.`total_srv_coverage` AS srv,
          sbc.`total_sup_coverage` AS sup,
          sbc.`total_d1_coverage` AS d1,
          sbc.`total_d2_coverage` AS d2,
          sbc.`total_d3_coverage` AS d3,
          sbc.`total_d4_coverage` AS d4,
          sbc.`total_pf_coverage` AS totalpf
        FROM
          seg_billing_coverage AS sbc 
          INNER JOIN care_insurance_firm AS cif 
            ON sbc.`hcare_id` = cif.`hcare_id` 
        WHERE sbc.`bill_nr` = ".$db->qstr($bill_nr)." 
          AND cif.`hcare_id` != 18 ";

         if($this->result=$db->Execute($SQL)){
                return $this->result;
            }else{
                return false;
            }
    }


     function if_coverage_phic(){
        global $db;
        
    $SQL="SELECT 
          COUNT(sbc.`bill_nr`) as if_phic
        FROM
          seg_billing_coverage AS sbc 
        WHERE sbc.`bill_nr` = ".$db->qstr($this->bill_ref_nr)." 
          AND sbc.`hcare_id` = 18 ";


    $rs = $db->Execute($SQL);
        if($rs){
            if($rs->RecordCount()>0){
                return $rs->FetchRow();
            }else{
                return array("if_phic"=>0);
            }
        }else{
            return array("if_phic"=>0);
        }

    }

    function if_coverage_hmo(){
        global $db;
        
    $SQL="SELECT 
          COUNT(sbc.`bill_nr`) as if_hmo
        FROM
          seg_billing_coverage AS sbc 
        WHERE sbc.`bill_nr` = ".$db->qstr($this->bill_ref_nr)." 
          AND sbc.`hcare_id` <> 18 ";

           $rs = $db->Execute($SQL);
        if($rs){
            if($rs->RecordCount()>0){
                return $rs->FetchRow();
            }else{
                return array("if_hmo"=>0);
            }
        }else{
            return array("if_hmo"=>0);
        }
    }

    function get_coverage_phic($type){
        global $db;
        
    $SQL="SELECT 
          SUM(sai.`amount`) as amount
        FROM
          seg_additional_insurance AS sai 
        WHERE sai.`encounter_nr` = ".$db->qstr($this->encounter_nr)." 
          AND sai.`hcare_id` = 18
          AND sai.`service_type` = ".$db->qstr($type);

           $rs = $db->Execute($SQL);
        if($rs){
            if($rs->RecordCount()>0){
                return $rs->FetchRow();
            }else{
                return array("amount"=>0);
            }
        }else{
            return array("amount"=>0);
        }
    }


 function get_coverage_hmo($type){
        global $db;
        
    $SQL="SELECT 
          SUM(sai.`amount`) as amount
        FROM
          seg_additional_insurance AS sai 
        WHERE sai.`encounter_nr` = ".$db->qstr($this->encounter_nr)." 
          AND sai.`hcare_id` <> 18
          AND sai.`service_type` = ".$db->qstr($type);

           $rs = $db->Execute($SQL);
        if($rs){
            if($rs->RecordCount()>0){
                return $rs->FetchRow();
            }else{
                return array("amount"=>0);
            }
        }else{
            return array("amount"=>0);
        }
    }


#added by daryl
function getdiscountname_($encounter) {
        global $db;

        $SQL="SELECT 
              sbd.`discountid`,
              sbd.`discountdesc`,
              sbd.`discount`,
              sbd.`discount_amnt`,
              (sbd.`hosp_acc` + sbd.`hosp_meds` + sbd.`hosp_misc` + sbd.`hosp_ops` + sbd.`hosp_xlo`) AS hcidiscount,
              sbd.`pfdiscount`,
              sbd.`hosp_acc`,
              sbd.`hosp_xlo`,
              sbd.`hosp_meds`,
              sbd.`hosp_ops`,
              sbd.`hosp_misc`
               FROM  seg_billingapplied_discount AS sbd 
              WHERE sbd.`encounter_nr` = ".$db->qstr($encounter)." AND sbd.`discountid` != 'SC' ";

 if($this->result=$db->Execute($SQL)){
        return $this->result;
    }else{
        return false;
    }
    }

    function getPersonInfo($encounter=''){
        global $db;

        if(!empty($encounter)){
            $this->encounter_nr = $encounter;
        }

// ---- Commented out by LST - 03102008 ---------------
//      $sql = "SELECT ce.*, cp.name_first, cp.name_middle, cp.name_last,
//                      cp.date_birth,
//                      sb.brgy_name, sm.mun_name, sm.zipcode,
//                      sp.prov_name, sr.region_name, sr.region_desc,  cd.id, cd.name_formal as dept_name,
//                      ce.current_room_nr as room_no,cw.ward_id, cw.name as ward_name
//                  FROM care_encounter AS ce
//                      INNER JOIN care_person AS cp ON ce.pid = cp.pid
//                          INNER JOIN seg_barangays AS sb ON cp.brgy_nr = sb.brgy_nr
//                          INNER JOIN seg_municity AS sm ON sb.mun_nr = sm.mun_nr
//                                INNER JOIN seg_provinces AS sp ON sm.prov_nr = sp.prov_nr
//                                INNER JOIN seg_regions AS sr ON sp.region_nr = sr.region_nr
//                          INNER JOIN care_department AS cd ON cd.nr = ce.consulting_dept_nr
//                          INNER JOIN care_ward AS cw ON ce.current_ward_nr = cw.nr
//                  WHERE ce.encounter_nr ='".$this->encounter_nr."'";

        $sql = "SELECT ce.*, cp.name_first, cp.name_middle, cp.name_last,cp.suffix,
                            cp.date_birth,cp.age,
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
            return false;// echo 'SQL - '.$sql;
        }
    }// end of getPersonInfo

    function ReportOut(){
        $this->Output();
    }

    function trimAddress($street, $brgy, $mun, $prov, $zipcode, &$s_addr1, &$s_addr2, &$s_addr3){
        $address = trim($street);
        $address1 = (!empty($address) && !empty($brgy)) ?  trim($address) : trim($address);
        $s_addr1 = $address1;

//      $address2 =  (!empty($address1) && !empty($mun)) ? trim($address1.", ".$mun) : trim($address1." ".$mun);
        $address2 = trim($brgy." ".$mun);
        $address3 =  (!empty($address2) && !empty($zipcode))? trim($address2." ".$zipcode) : $address2." ";

        $address4 = (!empty($address3) && !empty($prov))? trim($address3.", ".$prov) : trim($address3." ".$prov);
        $s_addr2  = $address4;
        $s_addr3  = '';

//      return $address4;
    }// end of  function trimAddress

    function setEncounter_nr($encounter){
        $this->encounter_nr = $encounter;
    }

    /*function setObjBill(){
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
//      return $inches * (0.35/(1/72));
                return $inches * 25.4;
    }

    //added by daryl
    function specific_age($bdate){
        $interval = date_diff(date_create(), date_create($bdate));

        if ($interval->format("%Y") <=0){
            $return = $interval->format("%M Months Old");
        }else{
            $return = $interval->format("%Y Year, %M Months Old");
        }
   
        return $return;
    }

    //added by janken 12/29/2014
    function companyAmount($enc){
        global $db;

        $enc = $db->qstr($enc);

        $this->sql = "SELECT SUM(amount) FROM seg_billing_company_areas WHERE encounter_nr = ".$enc;

        if($result = $db->GetOne($this->sql))
            return $result;
        else
            return false;
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
//  $bill_dte = $_GET['bill_dt'];
    $bill_dte = strftime("%Y-%m-%d %H:%M:%S", $_GET['bill_dt']);
else
    $bill_dte = "0000-00-00 00:00:00";

if (isset($_GET['nr']))
        $old_bill_nr = $_GET['nr'];
else
        $old_bill_nr = '';

//Instantiate BillPDF class
$pdfBill =  new BillPDF($encounter_nr, $bill_dte, $frm_dte, $old_bill_nr, true/*(isset($_GET['rcalc']) && ($_GET['rcalc'] == '1'))*/, $_GET['deathdate']);

$encobj = new Encounter();
$pdfBill->isphic = $encobj->isPHIC($encounter_nr);
$pdfBill->ishousecase = $encobj->isHouseCase($encounter_nr);

$pdfBill->objBill->getAccommodationType();

$s_accommodation = $pdfBill->objBill->getAccomodationDesc();/*strtoupper($pdfBill->objBill->getAccommodationDesc());*/ //TODO2
$pdfBill->setReportTitle(($pdfBill->IsDetailed ? "DETAILED " : "")."STATEMENT OF ACCOUNT".($s_accommodation == '' ? " - NO ACCOMMODATION" : ($pdfBill->ishousecase ? "" : " - ".$s_accommodation)));
$pdfBill->ReportTitle();

//print patient informatin
$pdfBill->PersonInfo();
//print title bar
$pdfBill->TitleHeader('summary');
#$pdfBill->TitleHeader('detailed');

//print data
$pdfBill->PrintData();
$pdfBill->Sub_Total();
$pdfBill->Professional_Fee();

$pdfBill->Totals();
//print to pdf format
$pdfBill->ReportOut();
?>
