<?php

require_once('roots.php');
require_once($root_path.'include/inc_environment_global.php');
include_once($root_path.'include/inc_date_format_functions.php');

    require_once($root_path.'/include/care_api_classes/class_drg.php');
    $objDRG= new DRG;

    include_once($root_path.'include/care_api_classes/class_encounter.php');
    $enc_obj=new Encounter;
    
    require_once($root_path.'include/care_api_classes/class_hospital_admin.php');
    $objInfo = new Hospital_Admin();


require_once($root_path.'include/care_api_classes/class_person.php');
$person_obj=new Person($pid);

require_once($root_path.'include/care_api_classes/class_personell.php');
$pers_obj=new Personell;

require_once($root_path.'include/care_api_classes/class_address.php');
$address_country = new Address();
require_once($root_path.'include/inc_report.php');


interface ReportDataSource {
    public function toArray();
}


class ReportGenerator {
    
    protected $_dataSource;
    
    public function __construct(&$dataSource) {
        $this->_dataSource = $dataSource;
    }
    
}


/**
 * see if the java extension was loaded.
 */
function checkJavaExtension()
{
    if(!extension_loaded('java'))
    {
        $sapi_type = php_sapi_name();
        $port = (isset($_SERVER['SERVER_PORT']) && (($_SERVER['SERVER_PORT'])>1024)) ? $_SERVER['SERVER_PORT'] : '8080';
        if ($sapi_type == "cgi" || $sapi_type == "cgi-fcgi" || $sapi_type == "cli") 
        {
            require_once(java_include);
            return true;
        } 
        else
        {
            if(!(@require_once(java_include)))
            {
                require_once(java_include);
            }
        }
    }
    if(!function_exists("java_get_server_name")) 
    {
        return "The loaded java extension is not the PHP/Java Bridge";
    }

    return true;
}

function seg_ucwords($str) {
    $words = preg_split("/([\s,.-]+)/", mb_strtolower($str), -1, PREG_SPLIT_DELIM_CAPTURE);
    $words = @array_map('ucwords',$words);
    return implode($words);
}

/** 
 * convert a php value to a java one... 
 * @param string $value 
 * @param string $className 
 * @returns boolean success 
 */  
function convertValue($value, $className){
    // if we are a string, just use the normal conversion
    // methods from the java extension...
    try{
        if ($className == 'java.lang.String'){
            $temp = new Java('java.lang.String', $value);
            return $temp;
        }else if ($className == 'java.lang.Boolean' ||
                    $className == 'java.lang.Integer' ||
                    $className == 'java.lang.Long' ||
                    $className == 'java.lang.Short' ||
                    $className == 'java.lang.Double' ||
                    $className == 'java.math.BigDecimal')
        {
            $temp = new Java($className, $value);
            return $temp;
        }else if ($className == 'java.sql.Timestamp' ||
            $className == 'java.sql.Time')
        {
            $temp = new Java($className);
            $javaObject = $temp->valueOf($value);
            return $javaObject;
        }else if ($className == "java.util.Date"){
            #$temp = new Java('java.text.DateFormat');
            $temp = new Java('java.text.SimpleDateFormat("MM/dd/yyyy")');
            $javaObject = $temp->parse($value);
            return $javaObject;
        }
    }catch (Exception $err){
        echo (  'unable to convert value, ' . $value .
                ' could not be converted to ' . $className);
        return false;
    }

    echo (  'unable to convert value, class name '.$className.
    ' not recognised');
    return false;
} 

 
$x = checkJavaExtension();

$report = 'ClinicalCoverSheet';
$compileManager = new JavaClass("net.sf.jasperreports.engine.JasperCompileManager");

global $db;
    $newObj = new Report;
    $path = $newObj->select_global($db, $report);
// var_dump($report);die;
$report = $compileManager->compileReport(realpath($path.$report.'.jrxml'));

java_set_file_encoding("ISO-8859-1");
$fillManager = new JavaClass("net.sf.jasperreports.engine.JasperFillManager");

$params = new Java("java.util.HashMap");

$start = microtime(true);

$db->SetFetchMode(ADODB_FETCH_ASSOC);

$pid = $_GET['pid'];

if ($_GET['encounter_nr']) {
        if (!($enc_info = $enc_obj->getEncounterInfo($_GET['encounter_nr']))){
            #echo "enc_obj->sql = '".$enc_obj->sql."' <br> \n";     
            echo '<em class="warn">Sorry but the page cannot be displayed!</em>';
            exit();
        }
        #echo "enc_obj->sql = '".$enc_obj->sql."' <br> \n";     
        extract($enc_info);
        #$personell_obj->getPersonellInfo($referrer_dr);
    }else{
            echo '<em class="warn">Sorry but the page cannot be displayed! <br>Invalid Case Number! </em>';
            exit(); 
    }
//example sa encounter
    //sa name
    //$data[0]['name_last'] = $enc_info['name_last'];

$dispo_result = getdisposition($_GET['encounter_nr']);
$get_confine = getconfinement($_GET['encounter_nr']);
$get_insurance = getinsurance($_GET['encounter_nr']);
$get_companyname = get_companyname($pid);

$check_phic = "";
$check_none = "";
$check_hmo = "";

 while($row = $get_insurance->FetchRow()){
    $hcare_id = $row['hcare_id'];
    if ($hcare_id == 18){
        $check_phic = "/";
    }

    if ($hcare_id != 18){
            $check_hmo = "/";
    }
 }

if ($hcare_id == ""){
            $check_none = "/";
     }
          $data[0]['check_phic'] = $check_phic;
          $data[0]['check_none'] = $check_none;
          $data[0]['check_hmo'] = $check_hmo;

foreach ($get_insurance as $value){
   $hcare_id = $value['hcare_id'];
}


foreach ($dispo_result as $value){
   $result_code = $value['result_code'];
   $disp_code = $value['disp_code'];
}

foreach ($get_confine as $value){
   $confinement_id = $value['confinement_id'];
   $confinement_type = $value['confine_desc'];
}

if ($row = $objInfo->getAllHospitalInfo()) {            
        $row['hosp_agency'] = strtoupper($row['hosp_agency']);
        $row['hosp_name']   = strtoupper($row['hosp_name']);
    }
    else {
        $row['hosp_country'] = "Republic of the Philippines";
        $row['hosp_agency']  = "DEPARTMENT OF HEALTH";
        $row['hosp_name']    = "DAVAO MEDIQUEST HOSPITAL INC";
        $row['hosp_addr1']   = "Mc Arthur Highway Lizada Toril Davao City";     
    }


 $data[0]['hosp_name'] = $row['hosp_name'];
 $data[0]['hosp_add'] = $row['hosp_addr1'];

 $pid2 = $pid;
 $pid2[0] = 0;
   
 $data[0]['hrn'] = $pid2;
 $data[0]['case_no'] = $encounter_nr;

 $data[0]['name_last'] =  mb_strtoupper($name_last);
 $data[0]['suffix'] =  mb_strtoupper($suffix);
 $data[0]['name_first'] =  mb_strtoupper($name_first);
 $data[0]['name_middle'] =  mb_strtoupper($name_middle);
 $data[0]['name_maiden'] =  mb_strtoupper($name_maiden);


if (stristr($age,'years')){
        $age = substr($age,0,-5);
        if ($age>1)
            $labelyear = "years";
        else
            $labelyear = "year";
                
        $age = floor($age)." ".$labelyear;
    }elseif (stristr($age,'year')){ 
        $age = substr($age,0,-4);
        if ($age>1)
            $labelyear = "years";
        else
            $labelyear = "year";
            
        $age = floor($age)." ".$labelyear;
        
    }elseif (stristr($age,'months')){   
        $age = substr($age,0,-6);
        if ($age>1)
            $labelmonth = "months";
        else
            $labelmonth = "month";
            
        $age = floor($age)." ".$labelmonth; 
        
    }elseif (stristr($age,'month')){    
        $age = substr($age,0,-5);
        
        if ($age>1)
            $labelmonth = "months";
        else
            $labelmonth = "month";
            
        $age = floor($age)." ".$labelmonth;     
        
    }elseif (stristr($age,'days')){ 
        $age = substr($age,0,-4);
                    
        if ($age>30){
            $age = $age/30;
            if ($age>1)
                $label = "months";
            else
                $label = "month";
            
        }else{
            if ($age>1)
                $label = "days";
            else
                $label = "day";
        }
                        
        $age = floor($age).' '.$label;  
            
    }elseif (stristr($age,'day')){  
        $age = substr($age,0,-3);
        
        if ($age>1)
            $labelday = "days";
        else
            $labelday = "day";
            
        $age = floor($age)." ".$labelday;       
    }else{
        if ($age){
            if ($age>1)
                $labelyear = "years";
            else
                $labelyear = "year";
            
            $age = $age." ".$labelyear;
        }else
            $age = "0 day"; 
    }
 //added by daryl   
 $get_age = specific_age($date_birth);  
 $data[0]['age'] =  $get_age;


 if ($sex=='f')
        $gender = 'FEMALE';
    elseif ($sex=='m')
        $gender = 'MALE';   


  $data[0]['gender'] =    mb_strtoupper($gender);
  $data[0]['civil_status'] =    mb_strtoupper($civil_status);

    $contact = $phone_1_nr;
    if (!isset($contact) || empty($contact)) $contact = $cellphone_1_nr;
    if (!isset($contact) || empty($contact)) $contact = $phone_2_nr;
    if (!isset($contact) || empty($contact)) $contact = $cellphone_2_nr;

  $data[0]['contact_no'] =   $contact_no;


    $data[0]['date_birth'] = @formatDate2Local($date_birth,$date_format);
    $data[0]['place_birth'] =  mb_strtoupper($place_birth);
    $data[0]['nationality'] = mb_strtoupper($citizenship);
    $data[0]['religion'] =  mb_strtoupper($religion);

if ($street_name){
        if ($brgy_name!="NOT PROVIDED")
            $street_name = $street_name.", ";
        else
            $street_name = $street_name.", ";   
    }#else
        #$street_name = ""; 
        
    if ((!($brgy_name)) || ($brgy_name=="NOT PROVIDED"))
        $brgy_name = "";
    else 
        $brgy_name  = $brgy_name.", ";  
                    
    if ((!($mun_name)) || ($mun_name=="NOT PROVIDED"))
        $mun_name = "";     
    else{   
        if ($brgy_name)
            $mun_name = $mun_name;  
        #else
            #$mun_name = $mun_name;     
    }           
    
    if ((!($prov_name)) || ($prov_name=="NOT PROVIDED"))
        $prov_name = "";        
    #else
    #   $prov_name = $prov_name;            
                
    if(stristr(trim($mun_name), 'city') === FALSE){
        if ((!empty($mun_name))&&(!empty($prov_name))){
            if ($prov_name!="NOT PROVIDED") 
                $prov_name = ", ".trim($prov_name);
            else
                $prov_name = "";    
        }else{
            #$province = trim($prov_name);
            $prov_name = "";
        }
    }else
        $prov_name = " ";   
                
    $address = $street_name.$brgy_name.$mun_name.$prov_name;
    $data[0]['address'] = $address;


    $FmiddleInitial = "";
        if (trim($father_mname)!=""){
            $thisMI=split(" ",$father_mname);   
            foreach($thisMI as $value){
                if (!trim($value)=="")
                    $FmiddleInitial .= $value[0];
            }
            if (trim($FmiddleInitial)!="")
                $FmiddleInitial = " ".$FmiddleInitial.".";
        }
    
    $father_name = $father_fname." ".$FmiddleInitial." ".$father_lname;

    $data[0]['father_name'] = $father_name;

$MmiddleInitial = "";
        if (trim($mother_mname)!=""){
            $thisMI=split(" ",$mother_mname);   
            foreach($thisMI as $value){
                if (!trim($value)=="")
                    $MmiddleInitial .= $value[0];
            }
            if (trim($MmiddleInitial)!="")
                $MmiddleInitial = " ".$MmiddleInitial.".";
        }
    
    $mother_name = $mother_fname." ".$MmiddleInitial." ".$mother_lname;

    $data[0]['mother_name'] = $mother_name;
    $data[0]['spouse_name'] = $spouse_name;


if (isset($is_discharged) && $is_discharged){
        if ( ($encounter_type==3) || ($encounter_type==4) ){
    #       $admitting_dr=$er_opd_admitting_physician_nr;
    #       $attending_dr=$attending_physician_nr;
            $admitting_dr=$er_opd_admitting_physician_name;
            $attending_dr=$attending_physician_name;
        }else{
    #       $attending_dr=$attending_physician_nr;
            $attending_dr=$attending_physician_name;
        }
    }else{
            # assuming that ONLY ecnounters with encounter_type==3 or 4
        #$attending_dr='';
        $attending_dr=$attending_physician_name;
        $admitting_dr=$er_opd_admitting_physician_name; 
    }

   $data[0]['attending_dr']  = mb_strtoupper($attending_dr);
   $data[0]['admitting_dr']  = mb_strtoupper($admitting_dr);
   $data[0]['admitting_diagnosis']  = mb_strtoupper($admitting_diagnosis);


    $result_therapy = array();
    if (isset($is_discharged) && $is_discharged){
        if ($rs_therapy = $objDRG->getProcedureCodes($_GET['encounter_nr'])){
            $rowsTherapy = $rs_therapy->RecordCount();
            while($temp=$rs_therapy->FetchRow()){
                $temp_therapy = array();
                $temp_therapy['type'] = $temp['type'];
                $temp_therapy['code'] = $temp['code'];
                $temp_therapy['therapy'] = $temp['therapy'];
                array_push($result_therapy,$temp_therapy);
            }           
        }
    }
    if (isset($is_discharged) && ($rowsTherapy)){
        $count=0;
        foreach ($result_therapy as $value) {
              $operations =   $value['code']." : ".$value['therapy'];
                $count++;
        }
     
    }else{
        $operations = "";
    }
     $data[0]['operations']  =  $operations;



        if ($admission_dt)
            $admission_dt = @formatDate2Local($admission_dt,$date_format,1);
      else
            $admission_dt = "";
            
        $data[0]['admission_dt']  = mb_strtoupper($admission_dt);


    if (isset($is_discharged) && $is_discharged)
        $discharge_dt = @formatDate2Local($discharge_dt,$date_format,1);
    else
        $discharge_dt = "";

        $data[0]['discharge_dt']  = mb_strtoupper($discharge_dt);



$result_diagnosis = array();
    if (isset($is_discharged) && $is_discharged){
        if ($rs_diagnosis = $objDRG->getDiagnosisCodes($_GET['encounter_nr'])){
            $rowsDiagnosis = $rs_diagnosis->RecordCount();
            while($temp=$rs_diagnosis->FetchRow()){
                $temp_diagnosis = array();
                $temp_diagnosis['type'] = $temp['type'];
                $temp_diagnosis['code'] = $temp['code'];
                $temp_diagnosis['diagnosis'] = $temp['diagnosis'];
                array_push($result_diagnosis,$temp_diagnosis);
            }           
        }
    }
    
    if (isset($is_discharged) && ($rowsDiagnosis)){
        $count=0;
        foreach ($result_diagnosis as $value) {
            if ($value['type']==1){
                $final_diagnosis = $value['code']." : ".$value['diagnosis'];
                $count++;
            }
        }
}else{
    $final_diagnosis = "";
}
        $data[0]['final_diagnosis']  = $final_diagnosis;

        $recovered_mark  = "";
       $improved_mark = "";
        $unimproved_mark = "";
        $died_mark  = "";
         $discharge_mark  = "";
        $transfer_mark  = "";
       $hama_mark = "";
        $absconded_mark  = "";


switch ($result_code) {
    case '5':
        $recovered_mark = "/";
        break;
    case '6':
        $improved_mark = "/";
        break;
    case '7':
        $unimproved_mark = "/";
        break;   
    case '8':
        $died_mark = "/";
        break;  
    default:
        $recovered_mark = "";
        $improved_mark = "";
        $unimproved_mark = "";
        $died_mark = "";
        break;
}

        $data[0]['recovered_mark']  = $recovered_mark;
        $data[0]['improved_mark']  = $improved_mark;
        $data[0]['unimproved_mark']  = $unimproved_mark;
        $data[0]['died_mark']  = $died_mark;



switch ($disp_code) {
    case '7':
        $discharge_mark = "/";
        break;
    case '8':
        $transfer_mark = "/";
        break;
    case '9':
        $hama_mark = "/";
        break;   
    case '10':
        $absconded_mark = "/";
        break;  
    default:
        $discharge_mark = "";
        $transfer_mark = "";
        $hama_mark = "";
        $absconded_mark = "";
        break;
}
        $data[0]['discharge_mark']  = $discharge_mark;
        $data[0]['transfer_mark']  = $transfer_mark;
        $data[0]['hama_mark']  = $hama_mark;
        $data[0]['absconded_mark']  = $absconded_mark;


if ($confinement_id == 0){
    $confinement_type = "";
}
    
        $data[0]['confinement']  = $confinement_type;


        $consulting_dr = "";
    if(stristr(trim($ward_name), 'charity') === FALSE)
        $consulting_dr = mb_strtoupper($attending_dr);
                
        $data[0]['consulting_dr']  = $consulting_dr;

        $data[0]['dept']  = $name_formal;

    $ward = mb_strtoupper($ward_id)." Room ".$current_room_nr;


        $data[0]['ward']  = $ward;

        $clerk = mb_strtoupper($admitting_clerk_er_opd);

        $data[0]['clerk']  = $clerk;


        $data[0]['company'] = $get_companyname['company'];

    function get_companyname($pids){
            global $db;
            $strSQL =
            "SELECT stc.company_desc AS company\n".
            "FROM care_person as cp\n".
            "INNER JOIN seg_type_company AS stc ON cp.company_name=stc.company_id\n".
            "WHERE cp.pid = ".$db->qstr($pids);
        
        if($result=$db->Execute($strSQL)) {
            $row = $result->FetchRow();
            return $row;
        } else { return false; }
    }
    
    

//function get disposition
            function getdisposition($enc)
    {
        global $db;
        $strSQL="SELECT ser.`result_code` , sed.`disp_code`
                FROM  seg_encounter_disposition AS sed 
                INNER JOIN seg_encounter_result as ser
                    ON ser.encounter_nr = sed.encounter_nr
                WHERE sed.encounter_nr = ".$db->qstr($enc);
        
        if($result = $db->Execute($strSQL)){
            if($result->RecordCount()){
                while ($row = $result->FetchRow()) {
                   $returned_results[] = array(
                    'result_code'=> $row['result_code'],
                    'disp_code'  => $row['disp_code'],
                    );
                }
                return $returned_results;
            }else {return false;}
        }else {return false;}   
        
    }


//function get disposition
            function getconfinement($enc)
    {
        global $db;
        $strSQL="SELECT ce.`confinement_type` , st.`casetype_desc`
                FROM care_encounter AS ce
                INNER JOIN seg_type_case AS st ON ce.`confinement_type` = st.`casetype_id`
                WHERE ce.`encounter_nr` = ".$db->qstr($enc);
        
        if($result = $db->Execute($strSQL)){
            if($result->RecordCount()){
                  while ($row = $result->FetchRow()) {
                       $returned_results[] = array(
                    'confinement_id'=> $row['confinement_type'],
                    'confine_desc'  => $row['casetype_desc'],
                    );
                }
                return $returned_results;
            }else {return false;}
        }else {return false;}   
        
    }

//function get disposition
            function getinsurance($enc)
    {
        global $db;
        $strSQL="SELECT hcare_id 
                FROM seg_encounter_insurance 
                WHERE encounter_nr = ".$db->qstr($enc);
        
        $rs = $db->Execute($strSQL);
        if($rs){
            return $rs;
        }
        
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


$jCollection = new Java("java.util.ArrayList");
foreach ($data as $i => $row) {
    $jMap = new Java('java.util.HashMap');
    foreach ( $row as $field => $value ) {
        $jMap->put($field, $value);
    }
    $jCollection->add($jMap);
}

$jMapCollectionDataSource = new Java("net.sf.jasperreports.engine.data.JRMapCollectionDataSource", $jCollection);
$jasperPrint = $fillManager->fillReport($report, $params, $jMapCollectionDataSource);

$end = microtime(true);

$outputPath  = tempnam(java_tmp, '');
chmod($outputPath, 0777);

$exportManager = new JavaClass("net.sf.jasperreports.engine.JasperExportManager");
$exportManager->exportReportToPdfFile($jasperPrint, $outputPath);


header("Content-type: application/pdf;");
#header('Content-Transfer-Encoding: binary');
#header('Content-Disposition: attachment; filename="BirthCertificate.pdf"');
readfile($outputPath);

unlink($outputPath);
