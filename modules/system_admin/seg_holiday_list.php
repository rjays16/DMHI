
<?php
#fix by daryl
#change proper vaiables and functions to work
#10/20/14

error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/inc_environment_global.php');
require($root_path.'modules/system_admin/ajax/hosp_holiday.common.php');
$xajax->printJavascript($root_path.'classes/xajax');

define('LANG_FILE','edp.php');
$local_user='ck_edv_user';
require_once($root_path.'include/inc_front_chain_lang.php');
/* Load the insurance object */
require_once($root_path.'include/care_api_classes/class_holidays.php');
$holidays_obj =new Holidays;


$breakfile='seg_holiday_list.php'.URL_APPEND;

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

# Title in toolbar
 $smarty->assign('sToolbarTitle',''.$LDHolidays .':: '.$LDList.'');

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('dept_list.php')");

 # href for close button
 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('sWindowTitle',''.$LDHolidays .':: '.$LDList.'');

 # Buffer page output
 ob_start();
?>

<style type="text/css" name="formstyle">
td.pblock{ font-family: verdana,arial; font-size: 12}

div.box { border: solid; border-width: thin; width: 100% }

div.pcont{ margin-left: 3; }

</style>

<script type="text/javascript">
	function deleteHoliday(holiday_id, holiday_desc){
		var answer = confirm("Are you sure you want to delete the holiday "+(holiday_desc.toUpperCase())+"?");
		if (answer){
			xajax_deleteHospHoliday(holiday_id,holiday_desc);
		}
	}
	
	function removeHolidayID(id) {
	   var table = document.getElementById("holiday_list");
		var rowno;
		var rmvRow=document.getElementById("row"+id);
		if (table && rmvRow) {
			rowno = 'row'+id;
			var rndx = rmvRow.rowIndex;
			table.deleteRow(rmvRow.rowIndex);
			//window.location.reload(); 
		}
	}
</script>

<?php 

$sTemp = ob_get_contents();
ob_end_clean();


$smarty->append('JavaScript',$sTemp);

$holiday_data = $holidays_obj->GetHolidays();
# Buffer page output
ob_start();

?>

<table border=0 cellpadding=3 id="holiday_list">
  <tr class="wardlisttitlerow">
	 <td class=pblock align=center width="5%"><?php echo $LDDelete ?></td>
     <td class=pblock align=center width="30%">Holiday Date</td>
	 <td class=pblock align=center width="*">Description</td>
 </tr> 
  
<?php

if(is_object($holiday_data)){
	while($result=$holiday_data->FetchRow()){
		$date = explode('/', $result['holiday_date']);
		switch ($date[0]) {
			case '01':
				$holidayDate = "January";
				$holidayDate .= ", ".$date[1];
				break;
			case '02':
				$holidayDate = "February";
				$holidayDate .= ", ".$date[1];
				break;
			case '03':
				$holidayDate = "March";
				$holidayDate .= ", ".$date[1];
				break;
			case '04':
				$holidayDate = "April";
				$holidayDate .= ", ".$date[1];
				break;
			case '05':
				$holidayDate = "May";
				$holidayDate .= ", ".$date[1];
				break;
			case '06':
				$holidayDate = "June";
				$holidayDate .= ", ".$date[1];
				break;
			case '07':
				$holidayDate = "July";
				$holidayDate .= ", ".$date[1];
				break;
			case '08':
				$holidayDate = "August";
				$holidayDate .= ", ".$date[1];
				break;
			case '09':
				$holidayDate = "September";
				$holidayDate .= ", ".$date[1];
				break;
			case '10':
				$holidayDate = "October";
				$holidayDate .= ", ".$date[1];
				break;
			case '11':
				$holidayDate = "November";
				$holidayDate .= ", ".$date[1];
				break;
			case '12':
				$holidayDate = "December";
				$holidayDate .= ", ".$date[1];
				break;
			default:
				
				break;
		}
?>
  <tr id="row<?=$result['id'];?>">
	   <td class=pblock  bgColor="#eeeeee" align="center" valign="middle" width="5%">
 			<img name="delete<?=$result['id'];?>" id="delete<?=$result['id'];?>" src="../../images/btn_delitem.gif" style="cursor:pointer" border="0" onClick="deleteHoliday('<?=$result['id'];?>','<?=$result['description'];?>');"/>
		 </td>
	
		<td class=pblock  bgColor="#eeeeee" width="30%">
 			<a href="seg_holiday_create.php<?php echo URL_APPEND."&holiday_id=". $result['id']; ?>">
 				<?php echo $holidayDate; ?>
			</a> 
		 </td>
		 <td class=pblock  bgColor="#eeeeee" width="*">
 			<?php echo $result['description']; ?>
		 </td>
	
  </tr> 
<?php
	}
}
 ?>
 
</table>

<p>

<a href="javascript:history.back()"><img <?php echo createLDImgSrc($root_path,'cancel.gif','0') ?> border="0"></a>

<?php

$sTemp = ob_get_contents();
 ob_end_clean();

# Assign the data  to the main frame template

 $smarty->assign('sMainFrameBlockData',$sTemp);

 /**
 * show Template
 */
 $smarty->display('common/mainframe.tpl');

?>
