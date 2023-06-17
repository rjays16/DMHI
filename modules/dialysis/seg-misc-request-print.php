<?php
	require_once('roots.php');
	require_once($root_path.'include/inc_environment_global.php');
	include_once($root_path."/classes/fpdf/pdf.class.php");
	include_once($root_path.'include/care_api_classes/or/class_segOr_miscCharges.php');
	$seg_ormisc = new SegOR_MiscCharges();
	$infoResult = $seg_ormisc->getOrderInfo($_REQUEST['ref']);
	if ($infoResult)	$info = $infoResult->FetchRow();
	include_once($root_path."include/care_api_classes/class_product.php");
	$prod_obj = new Product();

	global $db;
	#edited by daryl
	#get class of fpdf without footer
	//10/30/2014
	$pdf = new PDF2("P",'mm','Letter');
	#ended by daryl
	$pdf->AliasNbPages();   #--added
	$pdf->AddPage("P");
		
	$font="Arial";
	$borderYes="1";
	$borderNo="0";
	$newLineYes="1";
	$newLineNo="0";
	$fillYes="1";
	$fillNo="0";
	$space=1;
	
	$pdf->Image($root_path.'gui/img/logos/dmhi_logo.jpg',40,5,15);

  $pdf->Ln(8);
  	$pdf->SetFont($font,"B","9");
	$pdf->Cell(17,4);
  //$pdf->Cell(0,4,'Republic of the Philippines',$borderNo,$newLineYes,'C');

	$pdf->Cell(17,4);
	$pdf->getx();
	$gg=$pdf->gety();
	$pdf->sety($gg-13);
	$pdf->Cell(198,4,'DAVAO MEDIQUEST HOSPITAL INC',$borderNo,$newLineYes,'C');

	$pdf->SetFont($font,"B","8");
	$pdf->Cell(17,4);
  $pdf->Cell(163,4,'Mc Arthur Highway Lizada Toril Davao City',$borderNo,$newLineYes,'C');
  //$pdf->Ln(1);

	//$areaname = $db->GetOne("SELECT area_name FROM seg_pharma_areas WHERE area_code='".$info['pharma_area']."'");
//pdf->SetFont($font,"B","11");
	//$pdf->Cell(0,5,$areaname,$borderNo,$newLineYes,'C');

	$type = ($info["is_cash"] == "1") ? "CASH" : "CHARGE";
	$pdf->SetFont($font,"B","9.3");
	$pdf->Cell(0,5,"MISCELLANOUS ORDER REQUEST ($type)",$borderNo,$newLineYes,'C');
	
	if ($info["is_urgent"] == "1") {
		$pdf->SetFont($font,"B","9");
		$pdf->SetTextColor(0,0,0);
		$pdf->Cell(0,3,"URGENT",$borderNo,$newLineYes,'C');
		$pdf->SetTextColor(0,0,0);
	}
	
	$pdf->Ln(1);
	
	$maxW = 196;
	$pdf->SetFont($font,"","10");
	$pdf->Cell($maxW*0.15,4,'Reference No.',$borderNo,$newLineNo,'L');
	$pdf->SetFont($font,"B","10");
	$pdf->Cell($maxW*0.35,4,$info['refno'],$borderNo,$newLineNo,'L');	
	$pdf->SetFont($font,"","10");
	$pdf->Cell($maxW*0.25,4,'Order Date',$borderNo,$newLineNo,'R');
	$pdf->SetFont($font,"B","10");
	$pdf->Cell($maxW*0.25,4,date("F j,Y h:ia",strtotime($info['orderdate'])),$borderNo,$newLineYes,'R');
	
	$pdf->SetFont($font,"","10");
	$pdf->Cell($maxW*0.15,4,'PID',$borderNo,$newLineNo,'L');
	$pdf->SetFont($font,"B","10");

	if ($info['pid'] == ""){
		$pid = $info['walkin_pid'];
	}else{
		$pid = $info['pid'];
	}
	$pdf->Cell($maxW*0.35,4,$pid,$borderNo,$newLineNo,'L');	
	$pdf->SetFont($font,"","10");
	$pdf->Cell($maxW*0.25,4,'Case No.',$borderNo,$newLineNo,'R');
	$pdf->SetFont($font,"B","10");
	$pdf->Cell($maxW*0.25,4,$info['encounter_nr'],$borderNo,$newLineYes,'R');
	
	$pdf->SetFont($font,"","10");
	$pdf->Cell($maxW*0.15,4,'Name',$borderNo,$newLineNo,'L');
	$pdf->SetFont($font,"B","10");
	$pdf->Cell(0,4,$info['ordername'],$borderNo,$newLineYes,'L');
	
	$pdf->SetFont($font,"","10");
	$pdf->Cell($maxW*0.15,4,'Address',$borderNo,$newLineNo,'L');
	$pdf->SetFont($font,"","10");
	$pdf->Cell(0,4,$info['orderaddress'],$borderNo,$newLineYes,'L');
	
	$pdf->Ln(2);
	$pdf->SetFont($font,"B","10");
	//$pdf->Cell(0,6,'Item List',$borderNo,$newLineNo,'L');
	$pdf->SetFont($font,"B","9.5");
	$pdf->SetFillColor(220);
	$pdf->Cell(0,0,'',$borderNo,$newLineYes,'L',$fillNo);
	$pdf->Cell($maxW*0.13,5,'Item No.',$borderYes,$newLineNo,'C',$fillNo);
	$pdf->Cell($maxW*0.60,5,'Description',$borderYes,$newLineNo,'C',$fillNo);
	$pdf->Cell($maxW*0.10,5,'Price',$borderYes,$newLineNo,'C',$fillNo);
	$pdf->Cell(($maxW-60)*0.10,5,'Qty',$borderYes,$newLineNo,'C',$fillNo);
	$pdf->Cell($maxW*0.10,5,'Amount',$borderYes,$newLineYes,'C',$fillNo);
	$pdf->SetFillColor(220);
	
	$pdf->SetFont('Arial',"B","9");
	$result = $seg_ormisc->getOrderItemsFullInfo($_REQUEST['ref']);
	$total = 0;
	while ($row = $result->FetchRow()) {
		$pdf->Cell($maxW*0.13,5,$row['service_code'],$borderYes,$newLineNo,'L',$fillNo);
		$pdf->Cell($maxW*0.60,5,$row['name'],$borderYes,$newLineNo,'L',$fillNo);
		$pdf->Cell($maxW*0.10,5,number_format($row['chrg_amnt'],2),$borderYes,$newLineNo,'R',$fillNo);
		$pdf->Cell(($maxW-60)*0.10,5,$row['quantity'],$borderYes,$newLineNo,'C',$fillNo);
		$amount = $row['adjusted_amnt'];
		$total += $amount;
		$pdf->Cell($maxW*0.10,5,number_format($amount,2),$borderYes,$newLineYes,'R',$fillNo);
	}
	$pdf->SetFont($font,"B","9");
	$pdf->Cell($maxW*0.88,6,"Total",$borderYes,$newLineNo,'R',$fillNo);
	$pdf->SetFont('Arial',"B","9.5");
	$pdf->Cell(($maxW-40)*0.15,6,number_format($total,2),$borderYes,$newLineYes,'R',$fillNo);
	
	$pdf->Cell(13,10,'',"",0,'L');
	$pdf->Cell(73,10,'',"B",0,'L');
	$pdf->Cell(33,10,'',"",0,'L');
	$pdf->SetFont('Arial','',$fontSizeLabel);
	// $pdf->Cell(5,10,'',"B",0,'L');
	// $pdf->Cell(50,10,'',"B",0,'L');
	$pdf->Cell(30,10,"","0",1,'L');
	
	$pdf->SetFont('Arial',$fontSizeLabel);
	$pdf->Cell(15,5,"","0",0,'L');	
	$pdf->Cell(60,5,"Patient Representative Signature","0",0,'L');
	$pdf->Cell(50,5,"",0,'L');
	// $pdf->Cell(30,5,"Pharmacist Signature","0",0,'L');
	$pdf->Ln($space*.05);
	

	$pdf->Output();	
?>