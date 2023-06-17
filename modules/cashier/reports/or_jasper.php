<?php
require_once('roots.php');
require_once($root_path.'include/inc_jasperReporting.php');
require_once($root_path.'include/inc_environment_global.php');
define('LANG_FILE','products.php');
define('NO_2LEVEL_CHK',1);
$local_user='ck_prod_db_user';
require_once($root_path.'include/inc_front_chain_lang.php');

if (empty($_SESSION['sess_temp_userid'])) {
	die('Not logged in');
}

global $db;
$db->setFetchMode(ADODB_FETCH_ASSOC);

include_once($root_path."/classes/json/json.php");
include_once($root_path."include/care_api_classes/class_cashier.php");

function getVAT_Exempt_Sales($total){
    global $db;
    $result = $db->GetOne("SELECT value FROM care_config_global WHERE type='vat_rate' ");
    $vat_rate = $result + 1;
    $total_less = $total * $vat_rate;

	$get_total = ($total / $vat_rate);
	$get_totalR = ($total - $get_total);

	$totalAmount = round($get_totalR, 2);
	$sTotalAmount = number_format($totalAmount, 2);
	return $sTotalAmount;
}

function getLess_VAT($total){
    global $db;
    $vat_rate = $db->GetOne("SELECT value FROM care_config_global WHERE type='vat_rate' ");
	$total_less = $total * $vat_rate;

	$totalAmount = round($total_less, 2);
	$sTotalAmount = number_format($totalAmount, 2);
	return $sTotalAmount;
}

function gettotal_SALES($VAT_Less, $totalAmount){
	$total = ($totalAmount - $VAT_Less);

	$totalAmount = round($total, 2);
	$sTotalAmount = number_format($totalAmount, 2);
	return $sTotalAmount;
}




function intToWords($number) {
    if (($number < 0) || ($number > 999999999)) {
        return "$number";
    }

    $Gn = floor($number / 1000000);  /* Millions (giga) */
    $number -= $Gn * 1000000;
    $kn = floor($number / 1000);     /* Thousands (kilo) */
    $number -= $kn * 1000;
    $Hn = floor($number / 100);      /* Hundreds (hecto) */
    $number -= $Hn * 100;
    $Dn = floor($number / 10);       /* Tens (deca) */
    $n = $number % 10;               /* Ones */

    $res = "";

    if ($Gn) {
        $res .= intToWords($Gn) . " Million";
    }

    if ($kn) {
        $res .= (empty($res) ? "" : " ") .
                intToWords($kn) . " Thousand";
    }

    if ($Hn) {
        $res .= (empty($res) ? "" : " ") .
                intToWords($Hn) . " Hundred";
    }

    $ones = array("", "One", "Two", "Three", "Four", "Five", "Six",
        "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen",
        "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen",
        "Nineteen");
    $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty",
        "Seventy", "Eigthy", "Ninety");

    if ($Dn || $n) {
        if (!empty($res)) {
            $res .= " and ";
        }

        if ($Dn < 2) {
            $res .= $ones[$Dn * 10 + $n];
        } else {
            $res .= $tens[$Dn];

            if ($n) {
                $res .= "-" . $ones[$n];
            }
        }
    }

    if (empty($res)) {
        $res = "zero";
    }

    return $res;
}

$ORNo = $_REQUEST['nr'];
$cClass = new SegCashier();
$get_discount = $cClass->get_ifvatable($ORNo,"discount");

$info = $cClass->GetPayInfo( $ORNo, $showDetails=true );
if ($info == false) {
	die('Error in retrieving payment information...');
}
//--start-----------code



$rsDetails = $cClass->GetPayDetails( $ORNo );
// echo $cClass->sql;
$details = $rsDetails->GetRows();
$items = array();
foreach ($details as $row) {
    $code = explode("|",$row["account_code"]);
    $items[] =  array(
        'code' => $code[0],
        'name' => preg_replace('/\s+/', ' ', addslashes($row["service"])),
        'price' => ((float) $row['amount_due']) / ((float) $row['qty']),
        'quantity' => (int) $row['qty'],
        'ref_source' => $row['ref_source']
    );
}

// Collecting Officer
$encoder = $db->GetOne("SELECT `name` FROM care_users WHERE login_id=".$db->qstr($_SESSION['sess_temp_userid']));
if (!$encoder) {
    die('Could not retrieve encoder information');
}
$designated = $db->GetOne("SELECT value FROM care_config_global WHERE type='cashier_or_designated_officer'");
if (!$designated) {
    die('Could not retrieve designated officer information');
}
//officer left
// $officer = strtoupper($encoder.'/'.$designated);
$officer = strtoupper($encoder);



$line = 12;
$totalAmount = 0;


$ii = 0;
foreach ($items as $i => $item) {
 $data[$ii]['code'] =  substr($item['code'],0,10);
 $data[$ii]['item_name'] = substr($item['name'],0,32);

    $amount = round(round($item['price'],2) * $item['quantity'], 2);
    $sAmount = number_format($amount, 2);

 $data[$ii]['amount'] = $sAmount;
 $data[$ii]['price'] = number_format($item['price'], 2);
 $data[$ii]['qty'] = $item['quantity'];

$totalAmount += $amount;

 // $is_vat = $db->GetOne("SELECT is_vat FROM care_pharma_products_main WHERE bestellnum = ".$db->qstr($item['service_code']) );

if($item['ref_source'] == 'PH'){
//daryl vat exempt sales
    $VAT_Exempt_Sales = getVAT_Exempt_Sales($amount);
    //daryl less vat
    $VAT_Less       = getLess_VAT($amount);
    //daryl total sales
    $VAT_Total_Sales += gettotal_SALES($VAT_Less, $amount);
}else{
    $VAT_Exempt_Sales =  number_format(0, 2);
    $VAT_Less =  number_format(0, 2);
    $VAT_Total_Sales +=  number_format(0, 2);
}
    $data_vat_less += $VAT_Less;
// Total Amount Left


 $ii++;
}

$totalAmount = round($totalAmount - $get_discount, 2);

$sTotalAmount = number_format($totalAmount, 2);

$sget_discount = number_format($get_discount, 2);

$params = array(
                "total_amount"=>(string)$sTotalAmount,
                "discount"=>(string)$sget_discount,
                "sc"=> number_format(0, 2),
                "vat_less"=>(string)$data_vat_less,
                "vat_total_sales"=>(string)$VAT_Total_Sales,
                "officer"=>$officer,
                "date"=>date("M j, Y g:iA", strtotime($info['or_date'])),
                "name"=>strtoupper($info['or_name'])
                );




// die;
//---end----------code
showReport('OR_jasper',$params,$data,"PDF");


?>