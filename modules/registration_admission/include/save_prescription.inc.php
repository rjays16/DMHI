<?php
/*------begin------ This protection code was suggested by Luki R. luki@karet.org ---- */
if (eregi('save_immunization.inc.php',$PHP_SELF)) 
	die('<meta http-equiv="refresh" content="0; url=../">');

require_once($root_path.'include/care_api_classes/class_prescription.php');
if(!isset($obj)) $obj=new Prescription;

require_once($root_path.'include/inc_date_format_functions.php');

if(!isset($db)||!$db) include_once($root_path.'include/inc_db_makelink.php');
if($dblink_ok){
	switch($mode)
	{	
		case 'create': 
								$HTTP_POST_VARS['prescribe_date']=@formatDate2STD($HTTP_POST_VARS['prescribe_date'],$date_format);
								$obj->setDataArray($HTTP_POST_VARS);
								if($obj->insertDataFromInternalArray()) 
									{
										header("location:".$thisfile.URL_REDIRECT_APPEND."&target=$target&pid=".$HTTP_SESSION_VARS['sess_pid']);
										exit;
									}
									else echo "<br>$LDDbNoSave";
								break;
		case 'update': 
								$HTTP_POST_VARS['date']=@formatDate2STD($HTTP_POST_VARS['date'],$date_format);
								$obj->setDataArray($HTTP_POST_VARS);
								$obj->where=' nr='.$imm_nr;
								if($obj->updateDataFromInternalArray($dept_nr)) 
									{
										header("location:".$thisfile.URL_REDIRECT_APPEND."&target=$target&pid=".$HTTP_SESSION_VARS['sess_pid']);
										exit;
									}
									else echo "$sql<br>$LDDbNoUpdate";
								break;
					
	}// end of switch
} else { echo "$LDDbNoLink<br>"; } 

?>
