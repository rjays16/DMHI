<?php

	define('FPDF_FONTPATH','font/');
	require($root_path."/classes/fpdf/fpdf.php");


	class PDF extends FPDF{

		//Page header

		function Header(){

		}

//edited by daryl
		//Page footer
		function Footer(){
			 //Go to 0.5 cm from bottom
				$this->SetY(-20);

				$this->SetFont('Arial','I',8);
			//Page number
				$this->Cell(5, 3 , '', "", 0,'');

				// $this->Cell(0,10,'Encoded by : '.$this->encoder,0,1,'L');
				$this->Cell(0,10,'',0,1,'L');
				 $this->AliasNbPages(); 
				//added by art 01/10/2014
				$this->SetFont('Arial','B',12);
				// $this->Cell(0,2,$this->code, "",1,'L');
				$this->Cell(0,2,'', "",1,'L');
				$this->SetFont('Arial','',8);
				// $this->Cell(60,8,'Effectivity : October 1, 2013',0,0,'L');
				$this->Cell(60,8,'',0,0,'L');
				// $this->Cell(80,8,'Revision : 0',0,0,'C');
				$this->Cell(80,8,'',0,0,'C');
				$this->Cell(50,8,'Page '.$this->PageNo().' of {nb}',0,0,'R');
				//end art
			#$this->Cell(0,10,$this->encoder,0,0,'C');
		}
	}
?>