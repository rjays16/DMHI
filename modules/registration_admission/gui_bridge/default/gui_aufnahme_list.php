<?php

# Resolve href for close button

if($HTTP_COOKIE_VARS["ck_login_logged".$sid]) $breakfilen = $root_path."main/startframe.php".URL_APPEND;
	else $breakfile = $breakfile.URL_APPEND."&target=entry";

# Start Smarty templating here
 /**
 * LOAD Smarty
 */
 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');
/*
# burn added: March 9, 2007
require_once($root_path.'include/care_api_classes/class_department.php');
$dept_obj=new Department;
#$user_dept_info = $dept_obj->getUserDeptInfo($HTTP_SESSION_VARS['sess_user_name']);
	if (!empty($HTTP_SESSION_VARS['sess_login_userid']))
		$seg_user_name = $HTTP_SESSION_VARS['sess_login_userid'];
	else
		$seg_user_name = $HTTP_SESSION_VARS['sess_temp_userid'];
	$user_dept_info = $dept_obj->getUserDeptInfo($seg_user_name);
*/


# Title in the toolbar
// $smarty->assign('sToolbarTitle',$LDAdmission.' :: '.$LDArchive);
# Title in the toolbar
 //$smarty->assign('sToolbarTitle',$LDPatientSearch); //Added by mark Nov 30, 2007

if (($allow_opd_user) || ($allow_er_user)){
	# search under ER or OPD Triage
	$smarty->assign('sToolbarTitle',"$LDConsultation :: Advanced $LDSearch");   # burn added : May 15, 2007
}else{
	#$smarty->assign('sToolbarTitle',"$LDAdmission :: $LDSearch");   # burn added : May 15, 2007
	$smarty->assign('sToolbarTitle',"$LDAdmission :: Advanced $LDSearch");   # burn added : May 15, 2007
}

 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('title',$LDAdmission.' :: '.$LDArchive);

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('admission_archive.php')");

	# Onload Javascript code
 $smarty->assign('sOnLoadJs','onLoad="if (window.focus) window.focus();"');

 # Hide the return button
 $smarty->assign('pbBack',FALSE);

/**
* Helper function to generate rows
*/
function createTR($input_name, $ld_text, $input_val, $colspan = 2, $input_size = 55){
	global $root_path;

?>

<tr>
	<td background="<?php echo createBgSkin($root_path,'tableHeaderbg3.gif'); ?>">&nbsp;<FONT SIZE=-1  FACE="Arial,verdana,sans serif"><?php echo $ld_text ?>:
	</td>
	<td colspan=<?php echo $colspan; ?> bgcolor="#eeeeee"><input name="<?php echo $input_name; ?>" type="text" size="<?php echo $input_size; ?>" value="<?php if(isset($input_val)) echo $input_val; ?>">
	</td>
</tr>

<?php
}

# Collect extra javascript

ob_start();

?>

<script  language="javascript">
<!--

function popSearchWin(target,obj_val,obj_name) {
	urlholder="./data_search.php<?php echo URL_REDIRECT_APPEND; ?>&target="+target+"&obj_val="+obj_val+"&obj_name="+obj_name;
	DSWIN<?php echo $sid ?>=window.open(urlholder,"wblabel<?php echo $sid ?>","menubar=no,width=400,height=550,resizable=yes,scrollbars=yes");
}

<?php require($root_path.'include/inc_checkdate_lang.php'); ?>

-->
</script>

<script language="javascript" src="<?php echo $root_path; ?>js/setdatetime.js"></script>
<script language="javascript" src="<?php echo $root_path; ?>js/checkdate.js"></script>
<script language="javascript" src="<?php echo $root_path; ?>js/dtpick_care2x.js"></script>

<?php

	echo '<link rel="stylesheet" type="text/css" media="all" href="' .$root_path.'js/jscalendar/calendar-win2k-cold-1.css">';
	echo '<script type="text/javascript" src="'.$root_path.'js/jscalendar/calendar.js"></script>';
	echo '<script type="text/javascript" src="'.$root_path.'js/jscalendar/lang/calendar-en.js"></script>';
	echo '<script type="text/javascript" src="'.$root_path.'js/jscalendar/calendar-setup_3.js"></script>';

# Get buffered javascript, stop buffering, assign to template

$sTemp = ob_get_contents();
ob_end_clean();

$smarty->append('JavaScript',$sTemp);

/* Load  the tabs */
$tab_bot_line='#66ee66';
$parent_admit = TRUE;

require('./gui_bridge/default/gui_tabs_patadmit.php');

# Start buffering output

ob_start();

if(isset($mode)&&($mode=='search'||$mode=='paginate')){

	 //echo $LDSearchKeyword.': '.$where;
?>

<table border=0>
	<tr>
		<td><img <?php echo createMascot($root_path,'mascot1_r.gif','0','bottom') ?> align="absmiddle"></td>
		<td class="prompt">
		<?php

			if ($rows) echo str_replace("~no.~",$totalcount,$LDFoundData).' '.$LDShowing.' '.$pagen->BlockStartNr().' '.$LDTo.' '.$pagen->BlockEndNr().'.';
				else echo str_replace('~nr~','0',$LDSearchFoundAdmit);

?>
		</td>
	</tr>
</table>

<?php
}

/*
* If search result not empty, list the basic info
*/
// if(isset($rows)&&$rows>1)
if(!empty($rows)){

	# Initialize the icon and background images

	$img_male=createComIcon($root_path,'spm.gif','0');
	$img_female=createComIcon($root_path,'spf.gif','0');

	$bgimg='tableHeaderbg3.gif';
	$tbg= 'background="'.$root_path.'gui/img/common/'.$theme_com_icon.'/'.$bgimg.'"';

?>

<table border=0 cellpadding=0 cellspacing=0>
	<tr class="wardlisttitlerow">
		<td><b>
		<?php
				if($oitem=='sex') $flag=TRUE;
				else $flag=FALSE;

			echo $pagen->SortLink($LDSex,'sex',$odir,$flag);
		?></b></td>
		<td><b>
		<?php
				if($oitem=='isDischarged') $flag=TRUE;
				else $flag=FALSE;

			echo $pagen->SortLink($LDDischarged,'isDischarged',$odir,$flag);
		?></b></td>
			<td><b>
		<?php
			if($oitem=='name_last') $flag=TRUE;
			else $flag=FALSE;
		echo $pagen->SortLink($LDLastName,'name_last',$odir,$flag);
			 ?></b></td>
			<td><b>
		<?php
			if($oitem=='name_first') $flag=TRUE;
			else $flag=FALSE;
		echo $pagen->SortLink($LDFirstName,'name_first',$odir,$flag);
			 ?></b></td>
			<td><b>
		<?php
			if($oitem=='date_birth') $flag=TRUE;
			else $flag=FALSE;
		echo $pagen->SortLink($LDBday,'date_birth',$odir,$flag);
		?></b></td>
			<td><b>
		<?php
			if($oitem=='brgy_name') $flag=TRUE;
			else $flag=FALSE;
		echo $pagen->SortLink("Barangay",'brgy_name',$odir,$flag);
		?></b></td>
			<td><b>
		<?php
			if($oitem=='mun_name') $flag=TRUE;
			else $flag=FALSE;
		echo $pagen->SortLink("Muni/City",'mun_name',$odir,$flag);
		?></b></td>
			<td align="center"><b>
		<?php
#	  	if($oitem=='addr_zip') $flag=TRUE;
#			else $flag=FALSE;
#		 echo $pagen->SortLink($LDZipCode,'addr_zip',$odir,$flag);
			if($oitem=='zipcode') $flag=TRUE;
			else $flag=FALSE;
		 echo $pagen->SortLink($LDZipCode,'zipcode',$odir,$flag);
		?></b></td>
			<td><b>
		<?php
			if($oitem=='encounter_nr') $flag=TRUE;
			else $flag=FALSE;
		echo $pagen->SortLink($LDAdmitNr,'encounter_nr',$odir,$flag);
			 ?></b></td>

			<td><b>
		<?php
			if($oitem=='encounter_date') $flag=TRUE;
			else $flag=FALSE;
		echo $pagen->SortLink($LDAdmitDate,'encounter_date',$odir,$flag);
			 ?></b></td>

	</tr>

<?php
	/* Load common icons*/
	$img_arrow=createComIcon($root_path,'r_arrowgrnsm.gif','0');

	$toggle=0;
	while($result=$ergebnis->FetchRow()){
		echo'
		<tr ';
		if($toggle){ echo "bgcolor=#efefef"; $toggle=0;} else {echo "bgcolor=#ffffff"; $toggle=1;}

		$buf='aufnahme_daten_zeigen.php'.URL_APPEND.'&origin=archive&encounter_nr='.$result['encounter_nr'].'&target=archiv&ptype='.$ptype;

		echo '>
			<td>&nbsp;<a href="'.$buf.'" title="'.$LDClk2Show.'">';

		switch($result['sex']){
			case 'f': echo '<img '.$img_female.'>'; break;
			case 'm': echo '<img '.$img_male.'>'; break;
			default: echo '&nbsp;'; break;
		}

			# burn added: March 27, 2007
		$date_birth = @formatDate2Local($result['date_birth'],$date_format);
		$bdateMonth = substr($date_birth,0,2);
		$bdateDay = substr($date_birth,3,2);
		$bdateYear = substr($date_birth,6,4);
		if (!checkdate($bdateMonth, $bdateDay, $bdateYear)){
			# invalid birthdate
			$date_birth='';
		}

		echo '</a></td>
			<td>&nbsp; <a href="'.$buf.'" title="'.$LDClk2Show.'">'.$result['name_last'].'</a></td>
			<td>&nbsp; &nbsp;<a href="'.$buf.'" title="'.$LDClk2Show.'">'.$result['name_first'].'</a></td>
			<td>&nbsp; &nbsp;'.$date_birth.'</td>';
#			<td>&nbsp; &nbsp;'.@formatDate2Local($result['date_birth'],$date_format).'</td>';
#			<td align=right>&nbsp; &nbsp;'.$result['addr_zip'].'</td>
		echo '
			<td align=right>&nbsp; &nbsp;'.$result['brgy_name'].'</td>
			<td align=right>&nbsp; &nbsp;'.$result['mun_name'].'</td>
			<td align=right>&nbsp; &nbsp;'.$result['zipcode'].'</td>
			<td align=right>&nbsp; &nbsp;'.$result['encounter_nr'].'</td>
			<td align=right>&nbsp; &nbsp;<a href="'.$buf.'" title="'.$LDClk2Show.'">'.@formatDate2Local($result['encounter_date'],$date_format).'</a></td>
		</tr>
		<tr bgcolor=#0000ff>
			<td colspan=10 height=1><img src="'.$root_path.'gui/img/common/default/pixel.gif" border=0 width=1 height=1></td>
		</tr>';
	}
		echo '
		<tr>
			<td colspan=8><font face=arial size=2>'.$pagen->makePrevLink($LDPrevious).'</td>
			<td align=right><font face=arial size=2>'.$pagen->makeNextLink($LDNext).'</td>
		</tr>';
 ?>
</table>

<p>

<form method="post"  action="aufnahme_list.php" >
<input type="hidden" name="sid" value="<?php echo $sid ?>">
<input type="hidden" name="lang" value="<?php echo $lang ?>">
<input type="hidden" name="mode" value="?">
<input type="submit" value="<?php echo $LDNewArchive ?>" >
</form>

<?php

	# End of the search result list display

}else{

	# Else if result is empty, display the input form

?>

<form method="post" action="<?php echo $thisfile; ?>" name="aufnahmeform">

<table border=0 cellspacing=1 cellpadding=0>

<?php

	if(!isset($pid)) $pid='';

	createTR('encounter_nr', $LDAdmitNr,$encounter_nr);

	$phpfd=$date_format;

	$phpfd=str_replace("dd", "%d", strtolower($phpfd));
	$phpfd=str_replace("mm", "%m", strtolower($phpfd));
	$phpfd=str_replace("yyyy","%Y", strtolower($phpfd));
	//$phpfd=str_replace("yy","%Y", strtolower($phpfd));

?>

	<tr>
		<td background="<?php echo createBgSkin($root_path,'tableHeaderbg3.gif'); ?>">&nbsp;<?php echo $LDAdmitDate ?>:
		</td>
		<td bgcolor="#eeeeee">
			<input name="date_start" id="date_text"  type="text" size=10 maxlength=10  value="<?php if(!empty($date_start)) echo @formatDate2Local($date_start,$date_format);  ?>"  onBlur="IsValidDate(this,'<?php echo $date_format ?>')" onKeyUp="setDate(this,'<?php echo $date_format ?>','<?php echo $lang ?>')">
			<!--<a href="javascript:show_calendar('aufnahmeform.date_start','<?php echo $date_format ?>')">-->
			<img <?php echo createComIcon($root_path,'show-calendar.gif','0','absmiddle'); ?> id="date_trigger" style="cursor:pointer "><font size=1>[<?php
			$dfbuffer="LD_".strtr($date_format,".-/","phs");
			echo $$dfbuffer;
		?>] </font>

			<!--EDITED: SEGWORKS -->
	<script type="text/javascript">
	Calendar.setup ({
		inputField : "date_text", ifFormat : "<?php echo $phpfd?>", showsTime : false, button : "date_trigger", singleClick : true, step : 1

	});
</script>

		</td>
		<td bgcolor="#eeeeee">
			<nobr>&nbsp;<?php echo $LDTo ?>:
			<input name="date_end" id="date_text1" type="text" size=10 maxlength=10 value="<?php if(!empty($date_end)) echo @formatDate2Local($date_end,$date_format);  ?>"  onBlur="IsValidDate(this,'<?php echo $date_format ?>')" onKeyUp="setDate(this,'<?php echo $date_format ?>','<?php echo $lang ?>')">
			<!--<a href="javascript:show_calendar('aufnahmeform.date_end','<?php echo $date_format ?>')">-->
			<img <?php echo createComIcon($root_path,'show-calendar.gif','0','absmiddle'); ?> id="date_trigger1" style="cursor:pointer "><font size=1>[<?php
			$dfbuffer="LD_".strtr($date_format,".-/","phs");
			echo $$dfbuffer;
		?>] </font>
			</nobr>
			<!--EDITED: SEGWORKS -->
	<script type="text/javascript">
	Calendar.setup ({
		inputField : "date_text1", ifFormat : "<?php echo $phpfd?>", showsTime : false, button : "date_trigger1", singleClick : true, step : 1

	});
</script>
		</td>
	</tr>

<?php

	if(!isset($name_last)) $name_last='';
	if(!isset($name_first)) $name_first='';
	createTR('name_last', $LDLastName,$name_last);
	createTR( 'name_first', $LDFirstName,$name_first);

#commented by VAN 02-22-08
/*
	if ($GLOBAL_CONFIG['patient_name_2_show']){
		if(!isset($name_2)) $name_2='';
		createTR('name_2', $LDName2,$name_2);
	}

	if ($GLOBAL_CONFIG['patient_name_3_show']){
		if(!isset($name_3)) $name_3='';
		createTR('name_3', $LDName3,$name_3);
	}
*/
	if ($GLOBAL_CONFIG['patient_name_middle_show']){
		if(!isset($name_middle)) $name_middle='';
		createTR('name_middle', $LDNameMid,$name_middle);
	}

	if(!isset($date_birth)) $date_birth='';
	if(!isset($addr_str)) $addr_str='';
	if(!isset($addr_str_nr)) $addr_str_nr='';
	if(!isset($addr_zip)) $addr_zip='';
	if(!isset($addr_city_town)) $addr_city_town='';
?>

	<tr>
		<td background="<?php echo createBgSkin($root_path,'tableHeaderbg3.gif'); ?>">&nbsp;<?php echo $LDBday ?>:
		</td>
		<td bgcolor="#eeeeee" colspan=2>
			<input name="date_birth" id="date_text2" type="text" size="15" maxlength=10 value="<?php if(!empty($date_birth)) echo @formatDate2Local($date_birth,$date_format);  ?>"
			onFocus="this.select();"  onBlur="IsValidDate(this,'<?php echo $date_format ?>')" onKeyUp="setDate(this,'<?php echo $date_format ?>','<?php echo $lang ?>')">
			<!--<a href="javascript:show_calendar('aufnahmeform.date_birth','<?php echo $date_format ?>')">-->
			<img <?php echo createComIcon($root_path,'show-calendar.gif','0','absmiddle'); ?> id="date_trigger2" style="cursor:pointer">
			<font size=1>[<?php
			$dfbuffer="LD_".strtr($date_format,".-/","phs");
			echo $$dfbuffer;
			?>]</font>
			<!--EDITED: SEGWORKS -->
	<script type="text/javascript">
	Calendar.setup ({
		inputField : "date_text2", ifFormat : "<?php echo $phpfd?>", showsTime : false, button : "date_trigger2", singleClick : true, step : 1

	});
</script>
		</td>
	</tr>

	<tr>
		<td background="<?php echo createBgSkin($root_path,'tableHeaderbg3.gif'); ?>">&nbsp;<?php echo $LDSex ?>:
		</td>
		<td colspan=2 bgcolor="#eeeeee"><input name="sex" type="radio" value="m" <?php if ($sex=='m') echo 'checked' ?>><?php echo $LDMale ?>&nbsp;&nbsp;
			<input name="sex" type="radio" value="f" <?php if ($sex=='f') echo 'checked' ?>><?php echo $LDFemale ?>
		</td>

	</tr>
	<tr>
		<td background="<?php echo createBgSkin($root_path,'tableHeaderbg3.gif'); ?>">&nbsp;<?php echo $LDDischarged ?>:
		</td>
		<td colspan=2 bgcolor="#eeeeee">
			<input name="isDischarged" type="radio" value="1" <?php if ($isDischarged=='1') echo 'checked' ?>><?php echo $LDIsDischarged ?>&nbsp;&nbsp;
			<input name="isDischarged" type="radio" value="0" <?php if ($isDischarged=='0') echo 'checked' ?>><?php echo $LDIsNotDischarged ?>
		</td>
		
	</tr>
<?php
	#added by VAN 02-22-08
	if(($allow_ipd_user)||($allow_medocs_user)){
?>

	<tr bgcolor="white">
		<td background="<?php echo createBgSkin($root_path,'tableHeaderbg3.gif'); ?>">&nbsp;<?php echo $LDAdmitClass ?>:
		</td>
		<td colspan=2 bgcolor="#eeeeee" >
<?php

		# Create  encounter classes radiobuttons
		while($result=$encounter_classes->FetchRow()) {
			#added by VAN 02-22-08
			if ($allow_ipd_user){
				if (($result['class_nr']==3)||($result['class_nr']==4)){
?>
			<input name="encounter_class_nr" type="radio"  value="<?php echo $result['class_nr']; ?>"  <?php if($encounter_class_nr==$result['class_nr']) echo 'checked'; ?>>
<?php
				$LD=$result['LD_var'];
				if(isset($$LD)&&!empty($$LD)) echo $$LD; else echo $result['name'];
				echo '&nbsp;';
				}
			}elseif ($allow_medocs_user){
				?>
				<input name="encounter_class_nr" type="radio"  value="<?php echo $result['class_nr']; ?>"  <?php if($encounter_class_nr==$result['class_nr']) echo 'checked'; ?>>
				<?php
				$LD=$result['LD_var'];
				if(isset($$LD)&&!empty($$LD)) echo $$LD; else echo $result['name'];
				echo '&nbsp;';
			}
		}
?>
		</td>
	</tr>

	<tr bgcolor="white">
		<td background="<?php echo createBgSkin($root_path,'tableHeaderbg3.gif'); ?>">&nbsp;<?php echo $LDWard ?>:
	</td>

		<td colspan=2 bgcolor="#eeeeee">
			<select name="current_ward_nr">
				<option value="">-Select Ward-</option>
<?php

		# Generate select box for the wards

		if(!empty($ward_info)&&$ward_info->RecordCount()){
			while($station=$ward_info->FetchRow()){
				echo '
				<option value="'.$station['nr'].'" ';
				if(isset($current_ward_nr)&&($current_ward_nr==$station['nr'])) echo 'selected';
				echo '>'.$station['name'].'</option>';
			}
		}
?>
			</select>
		</td>
	</tr>
<?php } ?>
<?php
	createTR( 'referrer_diagnosis', 'Referred '.$LDDiagnosis,$referrer_diagnosis);
	createTR( 'referrer_dr', $LDRecBy,$referrer_dr);
	createTR( 'referrer_recom_therapy', $LDTherapy,$referrer_recom_therapy);
	createTR( 'referrer_notes', $LDSpecials,$referrer_notes);
?>

	<tr bgcolor="white">
		<td background="<?php echo createBgSkin($root_path,'tableHeaderbg3.gif'); ?>">&nbsp;<?php echo $LDBillType ?>:
		</td>
		<td colspan=2 bgcolor="#eeeeee">
<?php

	# Create the bill type radiobuttons

	if($insurance_classes){
		while($result=$insurance_classes->FetchRow()) {
?>
			<input name="insurance_class_nr" type="radio"  value="<?php echo $result['class_nr']; ?>" >
<?php
			$LD=$result['LD_var'];
			if(isset($$LD)&&!empty($$LD)) echo $$LD; else echo $result['name'];
			echo '&nbsp;';
		}
	}
?>
		</td>
	</tr>

<?php
	createTR( 'insurance_nr', $LDInsuranceNr,$insurance_nr);
	createTR( 'insurance_firm_name', $LDInsuranceCo,$insurance_firm_name);

	//if (!$GLOBAL_CONFIG['patient_care_service_hide'] && $care_ok)
	if (!$GLOBAL_CONFIG['patient_service_care_hide']){
?>
	<tr>
		<td background="<?php echo createBgSkin($root_path,'tableHeaderbg3.gif'); ?>"><?php echo $LDCareServiceClass ?>:
		</td>
		<td colspan=2 bgcolor="#eeeeee"><nobr>
			<select name="sc_care_class_nr" onFocus="hidecat()">
				<option value=""> </option>
<?php

		# Generate the service classes

		while($buffer=$care_service->FetchRow()){
			echo '
				<option value="'.$buffer['class_nr'].'">';
			if(empty($$buffer['LD_var'])) echo $buffer['name']; else echo $$buffer['LD_var'];
			echo '</option>';
		}
?>
			</select>
		</td>
	</tr>
<?php
	}

	//if (!$GLOBAL_CONFIG['patient_service_room_hide'] && $room_ok)
	if (!$GLOBAL_CONFIG['patient_service_room_hide']){
?>
	<tr>
		<td background="<?php echo createBgSkin($root_path,'tableHeaderbg3.gif'); ?>"><?php echo $LDRoomServiceClass ?>:
		</td>
		<td colspan=2 bgcolor="#eeeeee">
			<select name="sc_room_class_nr" onFocus="hidecat()">
				<option value="" > </option>
<?php

		# Generate the service classes
		while($buffer=$room_service->FetchRow()){
			echo '
				<option value="'.$buffer['class_nr'].'">';
			if(empty($$buffer['LD_var'])) echo $buffer['name']; else echo $$buffer['LD_var'];
			echo '</option>';
		}
?>
			</select>
		</td>
	</tr>
<?php
	}

	//if (!$GLOBAL_CONFIG['patient_service_att_dr_hide'] && $att_dr_ok)
	if (!$GLOBAL_CONFIG['patient_service_att_dr_hide']){
?>
	<tr>
		<td background="<?php echo createBgSkin($root_path,'tableHeaderbg3.gif'); ?>"><?php echo $LDAttDrServiceClass ?>:
		</td>
		<td colspan=2 bgcolor="#eeeeee">
			<select name="sc_att_dr_class_nr" onFocus="hidecat()">
				<option value="" > </option>
<?php

		# Generate the service classes
		while($buffer=$att_dr_service->FetchRow()){
			echo '
				<option value="'.$buffer['class_nr'].'">';
			if(empty($$buffer['LD_var'])) echo $buffer['name']; else echo $$buffer['LD_var'];
			echo '</option>';

		}
?>
			</select>
		</td>
	</tr>
<?php
	}
?>

</table>

<p>

<input type=hidden name="sid" value=<?php echo $sid; ?>>
<input type=hidden name="lang" value="<?php echo $lang; ?>">
<input type="hidden" name="mode" value="search">
<input type="hidden" name="addr_citytown_nr">
<input  type="image" <?php echo createLDImgSrc($root_path,'searchlamp.gif','0') ?> alt="<?php echo $LDSaveData ?>" align="absmiddle">

</form>

<?php
}
?>

<p>
<a href="
<?php if($HTTP_COOKIE_VARS['ck_login_logged'.$sid])
/*---interchanged, 2007-10-03 FDP
echo 'startframe.php';
	else echo 'patient.php';
----*/
echo 'patient.php';
	else echo $root_path.'main/startframe.php';
//----------
	echo URL_APPEND;

?>
"><img <?php echo createLDImgSrc($root_path,'cancel.gif','0') ?> alt="<?php echo $LDCancelClose ?>"></a>

<p>

<?php

# End buffering, get contents, assign to templates and display template

$sTemp = ob_get_contents();
ob_end_clean();

$smarty->assign('sMainDataBlock',$sTemp);

$smarty->assign('sMainBlockIncludeFile','registration_admission/admit_plain.tpl');

$smarty->display('common/mainframe.tpl');

?>
