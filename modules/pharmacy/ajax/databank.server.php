<?php
require('./roots.php');
require_once($root_path.'include/inc_environment_global.php');
include_once($root_path."include/care_api_classes/class_pharma_product.php");
require_once($root_path.'modules/pharmacy/ajax/databank.common.php');

function populateProducts($page_num=0, $max_rows=10, $sort_obj=NULL, $args=NULL) {
	$objResponse = new xajaxResponse();	
	$pclass = new SegPharmaProduct();
	$codename = $args[0];
	$generic = $args[1];
	$prodclass = $args[2];
	$offset = $page_num * $max_rows;
	$sortColumns = array('prod_class','p.bestellnum','artikelname','p.price_cash', 'p.price_charge','sc_price','c1_price','c2_price','c3_price');
	$sort = array();
	if (is_array($sort_obj)) {
		foreach ($sort_obj as $i=>$v) {
			$col = $sortColumns[$i] ? $sortColumns[$i] : "date_request";
			if ((int)$v < 0) $sort[] = "$col DESC";
			elseif ((int)$v > 0) $sort[] = "$col ASC";
		}
	}
	if ($sort) $sort_sql = implode(',', $sort);
	else $sort_sql = 'date_request DESC';
	
	$result=$pclass->searchProducts($codename, $generic, $classification, $prodclass, $offset, $max_rows, $sort_sql);
	 //$objResponse->alert($pclass->sql);
	if($result) {
		$found_rows = $pclass->FoundRows();
		$last_page = ceil($found_rows/$max_rows)-1;
		if ($page_num > $last_page) $page_num=$last_page;
		
		if($data_size=$result->RecordCount()) {
			$temp=0;
			$i=0;
			$objResponse->contextAssign('currentPage', $page_num);
			$objResponse->contextAssign('lastPage', $last_page);
			$objResponse->contextAssign('maxRows', $max_rows);
			$objResponse->contextAssign('listSize', $found_rows);
			
			$DATA = array();
			while($row = $result->FetchRow()) {
				$DATA[$i]['bestellnum'] = $row['bestellnum'];
				$DATA[$i]['artikelname'] = $row['artikelname'];
				$DATA[$i]['generic'] = $row['generic'];
				$DATA[$i]['price_cash'] = $row["price_cash"];
				$DATA[$i]['price_charge'] = $row['price_charge'];
				$DATA[$i]['sc_price'] = (float)$row['sc_price']==0 ? '-' : $row['sc_price'];
				$DATA[$i]['c1_price'] = (float)$row['c1_price']==0 ? '-' : $row['c1_price'];
				$DATA[$i]['c2_price'] = (float)$row['c2_price']==0 ? '-' : $row['c2_price'];
				$DATA[$i]['c3_price'] = (float)$row['c3_price']==0 ? '-' : $row['c3_price'];
				$DATA[$i]['prod_class'] = $row['prod_class'];
				$DATA[$i]['FLAG'] = 1;
				//$objResponse->alert(print_r($DATA[$i],TRUE));
				$i++;
			} //end while
			$objResponse->contextAssign('dataSize', $data_size);
			$objResponse->contextAssign('listData', $DATA);
		}
		else {
			$objResponse->contextAssign('dataSize', 0);
			$objResponse->contextAssign('listData', NULL);
		}
		
	} else {
		// error
		//$objResponse->alert($objSS->sql);
		$objResponse->contextAssign('dataSize', -1);
		$objResponse->contextAssign('listData', NULL);
	}	
	$objResponse->script('this.fetchDone()');
	return $objResponse;
}

function deleteProduct($id) {
	global $db;
	$objResponse = new xajaxResponse();	
	$pc = new SegPharmaProduct();
	if ($pc->deleteProduct($id)) {
		$objResponse->script('plst.reload()');
		$objResponse->alert('Item successfully deleted...');
	}
	else {
		if (strpos($pc->db_error_msg,'a foreign key constraint fails')!==FALSE) {
			$objResponse->alert('This item is currently in use and cannot be deleted...');
		}
		else 
			$objResponse->alert('Database error:'.$pc->db_error_msg);
	}
	return $objResponse;
}

$xajax->processRequest();
?>