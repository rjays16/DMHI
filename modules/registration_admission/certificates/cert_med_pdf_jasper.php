<?php
require_once('roots.php');
require_once($root_path.'include/inc_jasperReporting.php');
require_once($root_path.'include/inc_environment_global.php');



include_once($root_path.'include/inc_date_format_functions.php');

require_once($root_path.'/include/care_api_classes/class_drg.php');
$objDRG= new DRG;

include_once($root_path.'include/care_api_classes/class_encounter.php');
$enc_obj=new Encounter;

include_once($root_path.'include/care_api_classes/class_cert_med.php');

include_once($root_path.'include/care_api_classes/class_personell.php');
$pers_obj=new Personell;

require_once($root_path.'include/care_api_classes/class_hospital_admin.php');
$objInfo = new Hospital_Admin();


if($_GET['id']){
    if(!($encInfo = $enc_obj->getEncounterInfo($_GET['id']))){
        echo '<em class="warn"> sorry byt the page cannot be displayed!</em>';
        exit();
    }
    extract($encInfo);
}else{
    echo '<em class="warn">Sorry but the page cannot be displayed! <br> Invalid Case Number!</em>';
    exit();
}

    $image_path = '/srv/tomcat/webapps/JavaBridge/resource/images/';

    $params = array("image_path"=> $image_path,
               );

//added by daryl
$get_age = specific_age($encInfo['date_birth']);
$sage_ = $get_age;



$cert_nr = $_GET['cert_nr'];
$referral_nr = $_GET['referral_nr'];
#echo "sql = ".$enc_obj->sql;
$obj_medCert = new MedCertificate($encounter_nr);
$medCertInfo = $obj_medCert->getMedCertRecord($encounter_nr,$referral_nr,$cert_nr);


$toDate= "".@formatDate2Local($discharge_dt,$date_format);
$date_created = date("m/d/Y",strtotime($medCertInfo["scheduled_date"]));


//Content text
$sex = ($sex == "m")? "MALE":"FEMALE";
#$address = trim($street_name).", ".trim($brgy_name).", ".trim($mun_name)." ".trim($zipcode)." ".trim($prov_name);
if (trim($brgy_name)=='NOT PROVIDED')
    $brgy_name = "";
else
    $brgy_name = trim($brgy_name).", ";

if (trim($mun_name)=='NOT PROVIDED')
    $mun_name = "";

$address = trim($street_name).", ".$brgy_name.trim($mun_name)." ".trim($prov_name);

#doctor's name was commented by VAN 04-28-08
if (($medCertInfo['consultation_date']!='0000-00-00') && ($medCertInfo['consultation_date']!=""))
    $er_opd_datetime = $medCertInfo['consultation_date'];

if (($encounter_type==1)||($encounter_type==2)){
    $fromDate= "".@formatDate2Local($er_opd_datetime,$date_format);
    #$name_doctor = $er_opd_admitting_physician_name;
}else{
    #edited by VAN 12-27-2012
    #with Ma'am Suzanne's approval
    if (($er_opd_datetime!='0000-00-00') && ($er_opd_datetime!=""))
        $consult_date =  $er_opd_datetime;
    else    
        $consult_date =  $admission_dt;
    #$fromDate= "".@formatDate2Local($admission_dt,$date_format);
    $fromDate= "".@formatDate2Local($consult_date,$date_format);
    #$name_doctor = $attending_physician_name;
}

if (empty($sage))
    $sage = "___";

if (($encounter_type==3)||($encounter_type==4)){
    $confine = ", confined";
    $dateconfine = $fromDate.' to '.$toDate;
}else{
    $confine = "";
    $dateconfine = $fromDate;
}


    //passing of data
        $data[0]['hrn']  =  $pid;
        $data[0]['caseno']  =  $encounter_nr;
        $data[0]['date']  =  $date_created;
        $data[0]['diagnosis']  =      strtoupper($medCertInfo['diagnosis_verbatim']);
        $data[0]['name']  =    utf8_decode(stripslashes(strtoupper($name_last))).", ".utf8_decode(stripslashes(strtoupper($name_first))).' '.utf8_decode(stripslashes(strtoupper($name_middle)));
        $data[0]['age']  =  $sage_;
        $data[0]['sex']  =  stripslashes(strtoupper($sex));
        $data[0]['status']  =  mb_strtoupper($civil_status);
        $data[0]['address']  =  utf8_decode(trim(stripslashes(strtoupper($address))));
        $data[0]['confine']  =  $confine;
        $data[0]['admittedate']  =  $dateconfine;



   //      $data[0]['admittedate'] = '&#09;This is to certify that <b>'.utf8_decode(stripslashes(strtoupper($name_last))).", ".utf8_decode(stripslashes(strtoupper($name_first))).' '.utf8_decode(stripslashes(strtoupper($name_middle))).
   //                  ' , '.$sage_.', '.stripslashes(strtoupper($sex)).' '.
   //                  ', '.mb_strtoupper($civil_status).'</b>  and a resident of <b>'.utf8_decode(trim(stripslashes(strtoupper($address)))).
   //                  '</b> was examined, treated <b>'.$confine.'</b> in this hospital on/from <b>'.$dateconfine.
   //                  '</b> with the following findings/diagnosis.';


   // $data[0]['admittedate'] = '&#09;"<style isBold=\\"true\\">" + fafafa + ": </style>" ';

if (trim($medCertInfo['procedure_verbatim'])!=""){

    $withproc = 0;
    //Operation
    
    $proc = trim($medCertInfo['procedure_verbatim']);
    if (!(empty($proc))){
        $procedureVerbatim = "OPERATION:";
        $procedureVerbatim_desc=strtoupper($medCertInfo['procedure_verbatim']);
        $withproc = 1;
        $data[0]['procedure']  =  $procedureVerbatim;
        $data[0]['procedure_desc']  =  $procedureVerbatim_desc;

    }

}

if ($medCertInfo['is_doc_sig']){

     if (is_numeric($medCertInfo['dr_nr'])){
    $docInfo = $pers_obj->getPersonellInfo($medCertInfo['dr_nr']);
    $dr_middleInitial = "";
    if (trim($docInfo['name_middle'])!=""){
        $thisMI=split(" ",$docInfo['name_middle']);
        foreach($thisMI as $value){
            if (!trim($value)=="")
                $dr_middleInitial .= $value[0];
        }
            if (trim($dr_middleInitial)!="")
            $dr_middleInitial = " ".$dr_middleInitial.".";
    }
    $name_doctor = "".$docInfo['name_first']." ".$docInfo['name_2']." ".$dr_middleInitial." ".$docInfo['name_last'];
     }else{
            $name_doctor = "".$medCertInfo['dr_nr'];
     }
  
    $data[0]['attendingphysician'] = strtoupper($name_doctor);
    $data[0]['lic'] = $medCertInfo['lic_no'];
    $data[0]['ptr'] = $medCertInfo['ptr_no'];
    $data[0]['preparedby'] = strtoupper($medCertInfo['prepared_by']);

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





showReport('medicalcertificate',$params,$data,"PDF");


?>