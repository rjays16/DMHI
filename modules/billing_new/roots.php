<?php
	$root_path='../../';
	$top_dir='modules/billing_new/';
	
	$QuickMenu = array(

	// array('label'=>'|'),
	// array('icon'=>'patdata.gif', 
	// 			'url'=>$root_path.'modules/billing/bill-pass.php{{$URL_APPEND}}&target=seg_billing',
	// 			'label'=>'Process'),
#added by shand 01/02/2014
	array('label'=>'|'),
	array('icon'=>'patdata.gif', 
				'url'=>$root_path.'modules/billing/bill-pass.php{{$URL_APPEND}}&target=seg_billing_PHIC',
				'label'=>'Process'),
#end by shand 01/02/2014	
	array('label'=>'|'),
	array('icon'=>'statbel2.gif', 
				'url'=>$root_path.'modules/billing/bill-pass.php?{{$URL_APPEND}}&target=seg_billing_list',
				'label'=>'List'),
				
	array('label'=>'|'),

	array('icon'=>'file_update.gif',
				'url'=>$root_path.'modules/billing/bill-pass.php{{$URL_APPEND}}&target=seg_billing_transmittal',
				'label'=>'Transmittal'),
				
	array('label'=>'|'),
	
	array('icon'=>'report.png',
				'url'=>$root_path.'modules/billing/bill-pass.php?{{$URL_APPEND}}&target=seg_billing_reports',
				'label'=>'Reports'),
				
	array('label'=>'|')
	
);
?>
