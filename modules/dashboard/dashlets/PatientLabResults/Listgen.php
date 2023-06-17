<?php
/**
* ListGen.php
*
*
*/

error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require "./roots.php";
require_once $root_path."include/inc_environment_global.php";
require_once $root_path."include/care_api_classes/dashboard/DashletSession.php";
require_once $root_path."classes/json/json.php";

header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
header("Cache-Control: no-cache, must-revalidate" );
header("Pragma: no-cache" );
header("Content-type: text/x-json");

$session = DashletSession::getInstance(DashletSession::SCOPE_DASHBOARD, $_SESSION['activeDashboard']);

$page = (int) $_REQUEST['page'];
$maxRows = (int) $_REQUEST['mr'];
$offset = ($page-1) * $maxRows;

$sortName = $_REQUEST['sort'];
if (!$sortName)
	$sortName = 'date';
$sortDir = $_REQUEST['dir']=='1' ? 'ASC':'DESC';
$sortMap = array(
	//'date' => 'r.serv_dt DESC, r.serv_tm',
	'date' => 'date_received',
);
//if (!$sortMap[$sortName]) $sort = 'serv_dt, serv_tm DESC';
if (!$sortMap[$sortName]) $sort = 'date_received DESC';
else	$sort = $sortMap[$sortName]." ".$sortDir;

global $db;

$session = DashletSession::getInstance(DashletSession::SCOPE_DASHBOARD, $_SESSION['activeDashboard']);
$encounter_nr = $session->get('ActivePatientFile');

$query = "SELECT pid, encounter_type, encounter_date, admission_dt, discharge_date, is_discharged 
            FROM care_encounter WHERE encounter_nr=".$db->qstr($encounter_nr);

#$pid = $db->GetOne($query);
$enc_row = $db->GetRow($query);
$pid = $enc_row['pid'];

if (($enc_row['encounter_type'] == '1') || ($enc_row['encounter_type'] == '2')){
    $encounter_date = date("Y-m-d",strtotime($enc_row['encounter_date']));
    $discharged_date = date("Y-m-d",strtotime($enc_row['encounter_date']));
}else{
    $encounter_date = date("Y-m-d",strtotime($enc_row['admission_dt']));
    if (!$enc_row['is_discharged'])
       $enc_row['discharge_date'] = date("Y-m-d"); 
    $discharged_date = date("Y-m-d",strtotime($enc_row['discharge_date']));
}

$data = Array();
if($pid) {
	      $query = "SELECT SQL_CALC_FOUND_ROWS o.refno, date_received AS request_date, 
                    SUBSTR(h.filename,INSTR(h.filename, '_')+1,LENGTH(SUBSTR(h.filename,INSTR(h.filename, '_')+1))-4) `lis_order_no`,
                    SUBSTR(h.filename,1,INSTR(h.filename, '_')-1) `pid`,
                    IF(fn_get_labtest_request_all(o.refno)<>'',
                       fn_get_labtest_request_all(o.refno),
                       CONCAT('MANUALLY ENCODED with Order No. ',
                               SUBSTR(h.filename,INSTR(h.filename, '_')+1,
                                   LENGTH(SUBSTR(h.filename,INSTR(h.filename, '_')+1))-4))) AS services, 
                    o.refno, sr.nth_take, sr.service_code, h.*
                    FROM seg_hl7_pdffile_received h
                    LEFT JOIN seg_lab_hclab_orderno o ON o.lis_order_no=(SUBSTR(h.filename,INSTR(h.filename, '_')+1,LENGTH(SUBSTR(h.filename,INSTR(h.filename, '_')+1))-4))
                    LEFT JOIN seg_lab_serv_serial sr ON sr.refno=o.refno AND sr.lis_order_no=o.lis_order_no
                    WHERE filename LIKE '$pid%'
                    AND date_received BETWEEN ".$db->qstr($encounter_date)." AND ".$db->qstr($discharged_date)." + INTERVAL 1 MONTH
                    ORDER BY date_received DESC
                    LIMIT $offset, $maxRows";       
		/*echo "<pre>";
		print_r($query);
		echo "</pre>";*/
	$db->SetFetchMode(ADODB_FETCH_ASSOC);
	$rs = $db->Execute($query);

	$data = Array();
	if ($rs !== false)
	{
		$total = 0;
		$total = $db->GetOne("SELECT FOUND_ROWS()");
		$rows = $rs->GetRows();
		foreach ($rows as $row)
		{
            
            //added by VAN 02-06-2013
            if ($row['nth_take']==1){
               $services = $row['services'].'<font color="BLUE"> (First Take)</font>'; 
            }elseif ($row['nth_take'] > 1){
               $service_code = $db->qstr($row['service_code']); 
               $sql_l = "SELECT name FROM seg_lab_services WHERE service_code=$service_code"; 
               $services = $db->GetOne($sql_l);
               
               switch($row['nth_take']){
                    case '1' :  
                                $nth_take = 'First'; 
                                break;
                    case '2' :  
                                $nth_take = 'Second'; 
                                break;
                    case '3' :  
                                $nth_take = 'Third'; 
                                break;
                    case '4' :  
                                $nth_take = 'Fourth'; 
                                break;
                    case '5' :  
                                $nth_take = 'Fift'; 
                                break;
                    case '6' :  
                                $nth_take = 'Sixth'; 
                                break;
                    case '7' :  
                                $nth_take = 'Seventh'; 
                                break;
                    case '8' :  
                                $nth_take = 'Eighth'; 
                                break;
                    case '9' :  
                                $nth_take = 'Ninth'; 
                                break;
                    case '10' : 
                                $nth_take = 'Tenth'; 
                                break;
                }

               $services = $services.'<font color="BLUE"> ('.$nth_take.' Take)</font>'; 
            }else{
               $services = $row['services'];
            }
            //----------------------
            
            $withresult = 0;
            if ($row['filename'])
                $withresult = 1;
                            
            $data[] = Array(
                'date' => nl2br(date("M-d-Y\nh:ia", strtotime($row["request_date"]))),
				        'service' => $services ,
				        'refno' => $row["refno"],
                'pid' => $row["pid"],
                'lis_order_no' => $row["lis_order_no"],
                'filename' => $row["filename"],
                'withresult' => $withresult
			);
		}
	}
}

if (!$data)
{
	$total = 0;
}

$response = array(
	'currentPage'=>$page,
	'total'=>$total,
	'data'=>$data
);

/**
* Convert data to JSON and print
*
*/

$json = new Services_JSON;
print $json->encode($response);