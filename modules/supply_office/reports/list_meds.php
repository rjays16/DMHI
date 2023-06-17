<?php
/**
 * SegHIS - Hospital Information System (BPH Deployment)
 * by Segworks Technologies Corporation
 * 
 * (Using JRXML)
 * 
 * @author  Bong S. Trazo 
 * @uses Jasper Report
 */

error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path . 'include/inc_environment_global.php');
include_once($root_path . "include/care_api_classes/class_hospital_admin.php");
include_once($root_path . 'include/care_api_classes/class_area.php');
include_once($root_path . 'include/care_api_classes/class_pharma_product.php');
require_once($root_path . 'include/care_api_classes/inventory/class_sku_inventory.php');
require_once($root_path . 'include/care_api_classes/reports/JasperReport.php');

$areacode = $_GET['area'];
$asofdate = $_GET['date'];

$jasper = new JasperReport();
$trDate = '';
if($asofdate == '') {
    $asof_date = '(No Date)';
} else {
    $asof_date = date("m/d/Y", strtotime($asofdate));
    $trDate = date("Y-m-d", strtotime($asofdate));
}

$objInfo = new Hospital_Admin();
$row = $objInfo->getAllHospitalInfo();
if ($row) {
    $row['hosp_agency'] = strtoupper($row['hosp_agency']);
    $row['hosp_name']   = strtoupper($row['hosp_name']);
}
else {
    $row['hosp_country'] = "Republic of the Philippines";
    $row['hosp_agency']  = "PROVINCE OF BUKIDNON";
    $row['hosp_name']    = "BPH";
    $row['hosp_addr1']   = "Bukidnon, Philippines";
}

$area_obj = new SegArea();
$areaname = $area_obj->getAreaName($areacode);

$imgpath = $jasper->getLogoPath();

// Assign the report and format to generate ...
$report = 'INV_meds-list';
$repformat = 'pdf';

$params = array();
$params[] = array("hospcountry", $row['hosp_country'], 'java.lang.String');
$params[] = array("hospagency", $row['hosp_agency'], 'java.lang.String');
$params[] = array("hospname", $row['hosp_name'], 'java.lang.String');
$params[] = array("hospaddr", $row['hosp_addr1'], 'java.lang.String');
$params[] = array("imagepath", $imgpath, 'java.lang.String');
$params[] = array("areacode", $areacode, 'java.lang.String');
$params[] = array("areaname", $areaname, 'java.lang.String');
$params[] = array("asofdate", $asof_date, 'java.lang.String');
$params[] = array("trDate", $trDate, 'java.lang.String');

include($root_path . 'modules/reports/render_report_jasper.php');
?>