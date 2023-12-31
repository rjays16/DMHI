<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/inc_environment_global.php');
require($root_path.'/modules/repgen/repgen.inc.php');

class RepGen_Medocs_Query4Research extends RepGen {
	var $date;
	var $colored = TRUE;

	function RepGen_Medocs_Query4Research ($from, $to, $code) {
		global $db;
		$this->RepGen("MEDICAL RECORDS: QUERY FOR RESEARCH");
		# 165
		$this->ColumnWidth = array(18,45,30,30,7,30,20,10,69);
		$this->RowHeight = 5;
		$this->TextHeight = 5;
		$this->TextPadding = 0.2;
		$this->Alignment = array('L','L','C','C','R','L','L','L','L');
		$this->PageOrientation = "L";
		$this->NoWrap = FALSE;
		
		if ($from) $this->from=$from;
		if ($to) $this->to=$to;
		if ($code) $this->code=$code;
		
		$this->useMultiCell = TRUE;
		$this->SetFillColor(0xFF);
		if ($this->colored)	$this->SetDrawColor(0xDD);
	}
	
	
	function Header() {
		global $root_path, $db;
		
		$this->Image($root_path.'gui/img/logos/dmhi_logo.jpg',108,8,15);
		$this->SetFont("Arial","I","9");
		$total_w = 260;
		#$this->Cell(0,4,'',1,1,'C');
		#$this->Cell($total_w,4,'',1,1,'C');
		
		$pad = 17;
		$this->Cell($pad,4);
  	$this->Cell($total_w-$pad,4,'Republic of the Philippines',$border2,1,'C');
		$this->Cell($pad,4);
	  $this->Cell($total_w-$pad,4,'DEPARTMENT OF HEALTH',$border2,1,'C');
  	$this->Ln(2);
		$this->SetFont("Arial","B","10");
		$this->Cell($pad,4);
  	$this->Cell($total_w-$pad,4,'DAVAO MEDIQUEST HOSPITAL INC',$border2,1,'C');
		$this->SetFont("Arial","","9");
		$this->Cell($pad,4);
  	$this->Cell($total_w-$pad,4,'Toril, Davao City',$border2,1,'C');
  	$this->Ln(4);
	  $this->SetFont('Arial','B',12);
		$this->Cell($pad,5);
		$text = 'QUERY FOR RESEARCH';
		if ($this->code) $text.=" ($code)";
  	$this->Cell($total_w-$pad,4,$text,$border2,1,'C');
	  $this->SetFont('Arial','B',9);
		$this->Cell($pad,5);
		if ($this->from) {
			$text = "From ".date("F j, Y",strtotime($this->from))." to ".date("F j, Y",strtotime($this->to));
#			print_r($this->shift_start);
#			print_r($this->shift_end);
#			print_r($_GET);
#			exit;
			
		}
		else
	  	$text = "Full History";
			
  	$this->Cell($total_w-$pad,4,$text,$border2,1,'C');
		$this->Ln(5);
		/*
		$from_dt=strtotime($this->from_date);
		$to_dt=strtotime($this->to_date);
		$this->SetFont("Arial","","9");
		if (!empty($this->from_date) && !empty($this->to_date))
			$this->Cell(0,5,
				sprintf('%s-%s',date("F j, Y",$from_dt),date("F j, Y",$to_dt)),
				$border2,1,'C');
		*/
		# Print table header
    $this->SetFont('Arial','B',8);
		if ($this->colored)	$this->SetFillColor(0xED);
		$this->SetTextColor(0);
		$row=6;
		$this->Cell($this->ColumnWidth[0],$row,'PID',1,0,'C',1);
		$this->Cell($this->ColumnWidth[1],$row,'Patient Name',1,0,'C',1);
		$this->Cell($this->ColumnWidth[2],$row,'Admitted',1,0,'C',1);
		$this->Cell($this->ColumnWidth[3],$row,'Discharged',1,0,'C',1);
		$this->Cell($this->ColumnWidth[4],$row,"Age",1,0,'C',1);
		$this->Cell($this->ColumnWidth[5],$row,'Brgy',1,0,'C',1);
		$this->Cell($this->ColumnWidth[6],$row,'Municipal',1,0,'C',1);
		$this->Cell($this->ColumnWidth[7],$row,'Code',1,0,'C',1);
		$this->Cell($this->ColumnWidth[8],$row,'Description',1,0,'C',1);
		$this->Ln();
	}
	
	function Footer()
	{
		$this->SetY(-23);
		$this->SetFont('Arial','I',8);
		$this->Cell(0,10,'Page '.$this->PageNo().' of {nb}. Generated: '.date("Y-m-d h:i:sa"),0,0,'R');
	}
	
	function BeforeData() {
		if ($this->colored) {
			$this->DrawColor = array(0xDD,0xDD,0xDD);
		}
	}
	
	function BeforeCellRender() {
		$this->FONTSIZE = 8;
		if ($this->colored) {
			if (($this->RENDERPAGEROWNUM%2)>0) 
				$this->RENDERCELL->FillColor=array(0xee, 0xef, 0xf4);
			else
				$this->RENDERCELL->FillColor=array(255,255,255);
		}
	}
	
	/*
	function BeforeCell() {
		$w=$this->GetStringWidth($this->DATA);
		if ($w>$this->ColumnWidth[$this->COLNUM]) {
			$trim=(float)$this->ColumnWidth[$this->COLNUM]/(float)$w;
			$this->DATA = substr($this->DATA, 0, floor(strlen($this->DATA)*$trim)-4)."...";
		}
	}
	*/
	
	function AfterData() {
		global $db;
		
		if (!$this->_count) {
			$this->SetFont('Arial','B',9);
			$this->SetFillColor(255);
			$this->SetTextColor(0);
			$this->Cell(0, $this->RowHeight, "No records found for this report...", 1, 1, 'L', 1);
		}
		
		$cols = array();
	}
	
	function FetchData() {		
		global $db;

		if ($this->from) {
			$where[]="DATE(e.discharge_date) BETWEEN '$this->from' AND '$this->to'";
		}
		
		if ($this->code) {
			$where[] = "d.code='$this->code'";
		}

		if ($where)
			$whereSQL = "AND (".implode(") AND (",$where).")";

		$sql = "
SELECT 
p.pid,CONCAT(IFNULL(p.name_last,''),IFNULL(CONCAT(', ', p.name_first),''),IFNULL(CONCAT(', ', p.name_middle),'')) AS patient_name,p.sex,
FLOOR(fn_calculate_age(p.date_birth,NOW())) AS age,
brgy.brgy_name,mun.mun_name,
e.admission_dt AS admission_date, e.discharge_date,
d.code, icd.description
FROM care_encounter_diagnosis AS d
LEFT JOIN care_encounter AS e ON e.encounter_nr=d.encounter_nr
LEFT JOIN care_person AS p ON p.pid=e.pid
LEFT JOIN seg_barangays AS brgy ON brgy.brgy_nr=p.brgy_nr
LEFT JOIN seg_municity AS mun ON mun.mun_nr=brgy.mun_nr
LEFT JOIN care_icd10_en AS icd ON d.code=diagnosis_code
WHERE (e.encounter_type=3 OR e.encounter_type=4) AND e.discharge_date IS NOT NULL $whereSQL\n";
		
		$sql .= "ORDER BY DATE(discharge_date),patient_name";
		$result=$db->Execute($sql);
		if ($result) {
			$this->_count = $result->RecordCount();
			$this->Data=array();
			while ($row=$result->FetchRow()) {
				$this->Data[]=array(
					$row['pid'],					
					$row['patient_name'],
					date("m/d/Y h:ia",strtotime($row['admission_date'])),
					date("m/d/Y h:ia",strtotime($row['discharge_date'])),
					$row['age'],
					$row['brgy_name'],
					$row['mun_name'],
					$row['code'],
					$row['description'],
				);
			}
		}
		else {
			print_r($sql);
			print_r($db->ErrorMsg());
			exit;
			# Error
		}			
	}
}

$iss = new RepGen_Medocs_Query4Research ($_GET['from'], $_GET['to'], $_GET['code']);
$iss->AliasNbPages();
$iss->FetchData();
$iss->Report();
?>