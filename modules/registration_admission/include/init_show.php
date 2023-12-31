<?php
$lang_tables[]='person.php';
$lang_tables[]='prompt.php';
define('LANG_FILE','aufnahme.php');
$local_user='aufnahme_user';
require_once($root_path.'include/inc_front_chain_lang.php');
require_once($root_path.'include/inc_date_format_functions.php');
require_once($root_path.'include/inc_editor_fx.php');
require_once($root_path.'include/care_api_classes/class_person.php');
# Load the template class
require_once($root_path.'include/care_api_classes/class_template.php');
# Create the template object
$TP_obj=new Template($root_path);

$breakfile='patient.php';
$admissionfile='aufnahme_start.php'.URL_APPEND;

if((!isset($pid)||!$pid)&&$HTTP_SESSION_VARS['sess_pid']) $pid=$HTTP_SESSION_VARS['sess_pid'];
	elseif((isset($pid)&&$pid)&&!$HTTP_SESSION_VARS['sess_pid']) $HTTP_SESSION_VARS['sess_pid']=$pid;

$HTTP_SESSION_VARS['sess_path_referer']=$top_dir.$thisfile;
//$HTPP_SESSION_VARS['sess_pid']=$pid;

/* Default path for fotos. Make sure that this directory exists! */
$default_photo_path=$root_path.'fotos/registration';
$photo_filename='nopic';

if(!isset($user_id) || !$user_id)
{
    $user_id=$local_user.$sid;
    $user_id=$$user_id;
}
 
if(isset($pid) && ($pid!='')) {
	$person_obj=new Person($pid);
	if($data_obj=&$person_obj->getAllInfoObject()){
		$zeile=$data_obj->FetchRow();
		//while(list($x,$v)=each($zeile))	$$x=$v;
		extract($zeile);       
	}
}

require_once($root_path.'include/care_api_classes/class_globalconfig.php');
$glob_obj=new GlobalConfig($GLOBAL_CONFIG);
$glob_obj->getConfig('person_%');
//$glob_obj->getConfig('patient_%');
$glob_obj->getConfig('notes_%');
if(!$GLOBAL_CONFIG['notes_preview_maxlen']) $GLOBAL_CONFIG['notes_preview_maxlen']=60; // hardcoded default value;

//$HTTP_SESSION_VARS['sess_full_pid']=$pid+$GLOBAL_CONFIG['person_id_nr_adder'];
$HTTP_SESSION_VARS['sess_full_pid']=$pid;
		
/* Check whether config foto path exists, else use default path */			
$photo_path = (is_dir($root_path.$GLOBAL_CONFIG['person_foto_path'])) ? $GLOBAL_CONFIG['person_foto_path'] : $default_photo_path;
require_once($root_path.'include/inc_photo_filename_resolve.php');

# set to safe defaults
if(!isset($is_discharged)) $is_discharged=true;
if(!isset($edit)) $edit=false;
# Check if person is currently admitted
$current_encounter=$person_obj->CurrentEncounter($pid);

if($HTTP_SESSION_VARS['sess_parent_mod']=='admission') {
	
	# Resolve the encounter number
	if((!isset($encounter_nr)||!$encounter_nr)&&$HTTP_SESSION_VARS['sess_en']) $encounter_nr=$HTTP_SESSION_VARS['sess_en'];
		elseif((isset($encounter_nr)&&$encounter_nr)&&!$HTTP_SESSION_VARS['sess_en']) $HTTP_SESSION_VARS['sess_en']=$encounter_nr;

	$parent_admit=true;
	$page_title=$LDAdmission;
	
	# Get the overall status
	include_once($root_path.'include/care_api_classes/class_encounter.php');
	$enc_obj=new Encounter;
	if($stat=&$enc_obj->AllStatus($HTTP_SESSION_VARS['sess_en'])){
		$enc_status=$stat->FetchRow();
	}
	# If current_encounter is this encounter nr
	if($current_encounter==$HTTP_SESSION_VARS['sess_en']){
		$is_discharged=false;
		$edit=true;

	}
	//echo " curr $current_encounter this ".$HTTP_SESSION_VARS['sess_en'];
}else{
	$parent_admit=false;
	$page_title=$LDPatientRegister;
}
?>
