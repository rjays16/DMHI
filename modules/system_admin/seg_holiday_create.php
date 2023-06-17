<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/inc_environment_global.php');

define('LANG_FILE','edp.php');
$local_user='ck_edv_user';
require_once($root_path.'include/inc_front_chain_lang.php');
/* Load the insurance object */
require_once($root_path.'include/care_api_classes/class_holidays.php');
$holidays_obj =new Holidays;

require_once($root_path.'include/care_api_classes/class_ward.php');
$ward_obj=new Ward;


$breakfile='seg_holiday_list.php'.URL_APPEND;

if(!isset($mode)) $mode='';

$nr = $_GET['holiday_id'];

if(!empty($mode)){

	$is_img=false;
	switch($mode)
	{	
		case 'create': 
		{	
			
			#$HTTP_POST_VARS['history']='Create: '.date('Y-m-d H:i:s').' '.$HTTP_SESSION_VARS['sess_user_name'];
			$HTTP_POST_VARS['create_id']=$HTTP_SESSION_VARS['sess_user_name'];
			$HTTP_POST_VARS['modify_id']=$HTTP_SESSION_VARS['sess_user_name'];
			$HTTP_POST_VARS['create_time']=date('YmdHis');
			$HTTP_POST_VARS['modify_time']=date('YmdHis');
			
			$HTTP_POST_VARS['type']="ward";
			$date = $HTTP_POST_VARS['holidaydate'];
			$description = $HTTP_POST_VARS['description'];
			$create_id = $HTTP_POST_VARS['create_id'];

			$ward_obj->setDataArray($HTTP_POST_VARS);
			if($holidays_obj->SaveHoliday($date, $description, $create_id, $HTTP_POST_VARS['create_time'])){
				header("location:seg_holiday_list.php".URL_REDIRECT_APPEND."&edit=1&mode=update");
			}else{
			 	echo "<br>$LDDbNoSave";
			}	
			
			break;
		}	
		case 'update':
		{ 
			#$HTTP_POST_VARS['history']=$ward_obj->ConcatHistory("Update: ".date('Y-m-d H:i:s')." ".$HTTP_SESSION_VARS['sess_user_name']."\n");
			$HTTP_POST_VARS['modify_id']=$HTTP_SESSION_VARS['sess_user_name'];
			$HTTP_POST_VARS['modify_time']=date('YmdHis');
			$date = $HTTP_POST_VARS['holidaydate'];
			$description = $HTTP_POST_VARS['description'];
			$modify_id = $HTTP_POST_VARS['modify_id'];
			$dateid = $HTTP_POST_VARS['nr']; 
			$ward_obj->setDataArray($HTTP_POST_VARS);
			if($holidays_obj->UpdateHoliday($date, $description, $modify_id, $HTTP_POST_VARS['modify_time'], $dateid)){
				header("location:seg_holiday_list.php".URL_REDIRECT_APPEND."&edit=1&mode=update");
				exit;
			
			}else{
				 echo "<br>$LDDbNoSave";
			}
			
			break;
		}
		
	}// end of switch
	#echo "sql = ".$ward_obj->sql;	
}

# Start Smarty templating here
 /**
 * LOAD Smarty
 */
 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('system_admin');

# Title in toolbar
 $smarty->assign('sToolbarTitle',''.$LDHolidays .':: '.$LDCreate.'');

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('dept_create.php')");

 # href for close button
 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('sWindowTitle',''.$LDHolidays .':: '.$LDCreate.'');

# Buffer page output

ob_start();
?>

<style type="text/css" name="formstyle">

td.pblock{ font-family: verdana,arial; font-size: 12}
div.box { border: solid; border-width: thin; width: 100% }
div.pcont{ margin-left: 3; }

</style>

<script language="javascript">
<!-- 

function chkForm(d){
	if(d.holidaydate.value==""){
		alert("<?php echo $LDPlsHolidayDates ?>");
		d.holidaydate.focus();
		return false;
	}else if(d.description.value==""){
		alert("Pls. enter a description of the type of room..");
		d.description.focus();
		return false;
	}
		return true;
	
}

//---------------------------------
// -->
</script>

<?php

$sTemp = ob_get_contents();
ob_end_clean();

$smarty->append('JavaScript',$sTemp);

$holidayType = $holidays_obj->GetHolidayInfo($nr);
# Buffer page output

ob_start();

?>

 <ul>
 <body onLoad="">
<font face="Verdana, Arial" size=-1><?php echo $LDEnterAllFields ?>
<form action="seg_holiday_create.php" method="post" name="roomtype" ENCTYPE="multipart/form-data" onSubmit="return chkForm(this)">
<table border=0>
<!--	
  <tr>
    <td class=pblock align=right bgColor="#eeeeee">Room Type: 
	 </td>
    <td class=pblock>
		  
	      <select id="type" name="type">
		  		<?php
					if ($roomtype['type']=="ward")
						echo "<option value='ward' selected>Ward</option>";
					else
						echo "<option value='ward'>Ward</option>";		 
						
					if ($roomtype['type']=="op")		  
						echo "<option value='op' selected>OP Room</option>";
					else	
						echo "<option value='op'>OP Room</option>";
				?>
		  </select>
    </td>
  </tr>
  -->
  <tr>
    <td class=pblock align=right bgColor="#eeeeee"><font color=#ff0000><b>*</b>
			Date of Holiday(MM/DD)</font>: 
	 </td>
    <td class=pblock>
	      <input name="holidaydate" id="holidaydate" type="text" size=40 maxlength=5 value="<?php echo trim($holidayType['holiday_date']); ?>">
    </td>
  </tr>
  <tr>
    <td class=pblock align=right bgColor="#eeeeee"><font color=#ff0000><b>*</b>
			Description</font>: 
	 </td>
    <td class=pblock>
	      <textarea id="description" name="description" cols="30" rows="3"><?php echo trim($holidayType['description']); ?></textarea>
    </td>
  </tr>
 </table>

<input type="hidden" name="sid" value="<?php echo $sid ?>">
<input type="hidden" name="edit" value="<?php echo $edit ?>">
<input type="hidden" name="lang" value="<?php echo $lang ?>">
<input type="hidden" name="nr" id="nr" value="<?=$nr;?>">
<!--
<?php
 if($mode=='select') {
?>
<input type="hidden" name="mode" value="update">

<input type="image" <?php echo createLDImgSrc($root_path,'savedisc.gif','0'); ?>>
<?php
}
else
{
?>
<input type="hidden" name="mode" value="create">
 
<input type="submit" value="<?php echo $LDCreate ?>">
<?php
}
?>
-->
<?php
	   if ($nr){
?>
			<input type="hidden" name="mode" id="mode" value="update">
<?php }else{ ?>	
			<input type="hidden" name="mode" id="mode" value="create">
<?php } ?>			

<input type="submit" value="<?php echo $LDSave ?>">

</form>
<p>

<a href="javascript:history.back()"><img <?php echo createLDImgSrc($root_path,'cancel.gif','0') ?> border="0"></a>

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
</body>