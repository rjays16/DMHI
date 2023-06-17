<?php
require_once('roots.php');
require_once($root_path.'include/inc_jasperReporting.php');
require_once($root_path.'include/inc_environment_global.php');
require_once($root_path.'include/care_api_classes/class_cashier.php');
require_once($root_path.'include/care_api_classes/curl/class_curl.php');

$cash_obj = new SegCashier;
$curl_obj = new Rest_Curl;

define('CASH_ITEM',8);
define('BILL_PAYMENT',10);
define('DEPOSIT',11);
define('ARA',2);
define('COMPANY_PAY', 3);
define('DISCOUNT_PAY',5);

$date = strtotime($_GET['date']);

$list = $cash_obj->getCashTrans($date);
$account_codes = $curl_obj->getAllAccounts();
$account_codes = json_decode($account_codes, true);
$code = array();
$collection = array();

foreach ($account_codes as $key => $value) {
	$code[$value['account_code']] = $value['account_name'];
}

foreach ($list as $key => $value) {
	if($value['ref_source'] == 'OTHER'){
		$value['service_code'] = substr($value['service_code'], 0, -1);
		$account_code = $curl_obj->getAccountCodes($value['service_code'], 'OT', CASH_ITEM);

		$list[$key]['accounts'] = $code[$account_code[0]['income_account']];
	}
	else if($value['ref_source'] == 'MISC'){
		$value['service_code'] = $curl_obj->getMiscServiceCode($value['service_code']);
		$account_code = $curl_obj->getAccountCodes($value['service_code'], 'OT', CASH_ITEM);

		$list[$key]['accounts'] = $code[$account_code[0]['income_account']];
	}
	else if($value['ref_source'] == 'PH'){
		$account_code = $curl_obj->getAccountCodes($value['service_code'], 'PH', CASH_ITEM);

		$list[$key]['accounts'] = $code[$account_code[0]['income_account']];
	}
	else if($value['ref_source'] == 'RD'){
		$account_code = $curl_obj->getAccountCodes($value['service_code'], 'RD', CASH_ITEM);

		$list[$key]['accounts'] = $code[$account_code[0]['income_account']];
	}	
	else if($value['ref_source'] == 'LD'){
		$account_code = $curl_obj->getAccountCodes($value['service_code'], 'LD', CASH_ITEM);

		$list[$key]['accounts'] = $code[$account_code[0]['income_account']];
	}
	else if($value['ref_source'] == 'FB'){
		$account_code = $curl_obj->getAccountCodes(BILL_PAYMENT, '', BILL_PAYMENT);

		$list[$key]['accounts'] = $code[$account_code[0]['credit_id']];
	}
	else if($value['ref_source'] == 'PP'){
		$account_code = $curl_obj->getAccountCodes(DEPOSIT, '', DEPOSIT);

		$list[$key]['accounts'] = $code[$account_code[0]['credit_id']];
	}
	else if($value['ref_source'] == 'COM'){
		$account_code = $curl_obj->getAccountCodes($value['pid'], '', COMPANY_PAY);

		$list[$key]['accounts'] = $code[$account_code[0]['credit_id']];
	}

	else if($value['ref_source'] == 'SP'){
		if($value['trans_type'] == 'insurance'){
			$account_code = $curl_obj->getAccountCodes($value['pid'], '', ARA);
			$list[$key]['accounts'] = $code[$account_code[0]['credit_id']];
		}
		else{
			$account_code = $curl_obj->getAccountCodes($value['pid'], '', DISCOUNT_PAY);
			$list[$key]['accounts'] = $code[$account_code[0]['credit_id']];
		}
	}
}

foreach ($list as $key => $value) {
	$collection[$value['accounts']] += $value['amount_due'];
	$total += $value['amount_due'];
}

$or_from = $list[0]['or_no'];
$or_to = $list[count($list)-1]['or_no'];

$x = 0;
foreach ($collection as $key => $value) {
	$data[$x] = array('accounts' => $key.' ',
						'account_amount' => 'P '.number_format($value,2),
						'prepared_by' => $_SESSION['sess_user_name'],
						'total_receipts' => 'P '.number_format($total,2));
	$x++;
}
$data[0]['date_dcpr'] = date("F d, Y", $date);
$data[0]['OR_from'] = $or_from;
$data[0]['OR_to'] = $or_to;
$data[0]['amt_OR'] = 'P '.number_format($total,2);
$data[0]['total_cash'] = 'P '.number_format($total,2);


showReport('DCPR_Cashier',$params,$data,"PDF");


?>