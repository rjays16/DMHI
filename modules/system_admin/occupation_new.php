<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/inc_environment_global.php');
/**
 * Segworks Technologies Corporation (c)2007
 * Hospital Information System
 * MLHE
 */
define('LANG_FILE','icd10icpm.php');
$local_user='aufnahme_user';

$thisfile='occupation_new.php';

require_once($root_path.'include/inc_front_chain_lang.php');

# Load the address object
require_once($root_path.'include/care_api_classes/class_person.php');
$person_obj=new Person;

//$db->debug=1;
switch($retpath)
{
	case 'list': $breakfile='occupation_list.php'.URL_APPEND.'&target=occupation'; break; 
	case 'search': $breakfile='occupation_search.php'.URL_APPEND.'&target=occupation'; break;
	default: $breakfile='edv-system_manage.php'.URL_APPEND.'&target=occupation';
}


if(!isset($mode)){
	$mode='';
	$edit=true;		
}else{
	switch($mode)
	{
		case 'save':
		{
			
			$HTTP_POST_VARS['create_id']=$HTTP_SESSION_VARS['sess_user_name'];
			$HTTP_POST_VARS['modify_id']=$HTTP_SESSION_VARS['sess_user_name'];
			$HTTP_POST_VARS['create_date']=date('YmdHis');
			$HTTP_POST_VARS['modify_date']=date('YmdHis');
			
			$person_obj->setDataArray($HTTP_POST_VARS);
			if($person_obj->saveOccupation($HTTP_POST_VARS)){ 
				$occupation_nr=$person_obj->LastInsertPK('occupation_nr',$db->Insert_ID());
				header("location:occupation_info.php?sid=$sid&lang=$lang&occupation_nr=$occupation_nr&mode=show&save_ok=1&retpath=$retpath");
				exit;
			}else{
				echo "<br>$LDDbNoSave";
			}	
			
			break;
		}//case
		
	} // end of switch($mode)
}//else


# Start Smarty templating here
 /**
 * LOAD Smarty
 */
 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('system_admin');
 
 # Title in toolbar
 $smarty->assign('sToolbarTitle',"Occupation :: New Occupation");

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('icpm_new.php')");

 # href for close button
 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('sWindowTitle',"Occupation :: New Occupation");

# Coller Javascript code

 
ob_start();
?>

<script type="text/javascript">
<!--
 // insert javascript here.
function chkfld(d){
	if(d.occupation_name.value==""){
		alert("Please type the occupation name");
		d.occupation_name.focus();
		return false;
	}
	return true;
}


//-->
</script>


<?php

$sTemp = ob_get_contents();
ob_end_clean();

$smarty->append('JavaScript',$sTemp);

# Buffer page output

ob_start();

?>

<ul>
<?php
if(!empty($mode)){ 
?>
<table border=0>
  <tr>
    <td><img <?php echo createMascot($root_path,'mascot1_r.gif','0','bottom') ?>></td>
    <td valign="bottom"><br><font class="warnprompt"><b>
		</b></font><p>
	</td>
  </tr>
</table>
<?php 
} 
?>
&nbsp;<br>

<form action="<?php echo $thisfile; ?>" method="post" name="icpm" onSubmit="return chkfld(this);">
<font face="Verdana, Arial" size=-1><?php echo $LDEnterAllFields ?></font>
<table border=0>
  <tr>
    <td align=right class="adm_item"><font color=#ff0000><b>*</b></font> Occupation : </td>
    <td class="adm_input">
			<input type="hidden" id="occupation_nr" name="occupation_nr" value="<?php echo $occupation_nr ?>" />
   		<input type="text" id="occupation_name" name="occupation_name" size="50" value="<?php echo $occupation_name ?>" />
	</td>    
  
  <tr>
    <td class=pblock><input type="image" <?php echo createLDImgSrc($root_path,'savedisc.gif','0'); ?>></td>
    <td  align=right><a href="<?php echo $breakfile ?>"><img <?php echo createLDImgSrc($root_path,'cancel.gif','0') ?> border="0"></a></td>
  </tr>
</table>
<input type="hidden" name="sid" value="<?php echo $sid ?>">
<input type="hidden" name="mode" value="save">
<input type="hidden" name="lang" value="<?php echo $lang ?>">
<input type="hidden" name="retpath" value="<?php echo $retpath ?>">
</form>

</ul>

<?php

$sTemp = ob_get_contents();
ob_end_clean();

# Assign page output to the mainframe template

$smarty->assign('sMainFrameBlockData',$sTemp);
 /**
 * show Template
 */
 $smarty->display('common/mainframe.tpl');

?>