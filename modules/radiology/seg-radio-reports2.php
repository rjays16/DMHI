<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/inc_environment_global.php');
/**
* CARE2X Integrated Hospital Information System Deployment 2.1 - 2004-10-02
* GNU General Public License
* Copyright 2002,2003,2004,2005 Elpidio Latorilla
* elpidio@care2x.org
*
* See the file "copy_notice.txt" for the licence notice
*/
define('LANG_FILE','lab.php');
#define('NO_2LEVEL_CHK',1);
define('NO_CHAIN',1);
require_once($root_path.'include/inc_front_chain_lang.php');
//$db->debug=1;

$dbtable='care_config_global'; // Table name for global configurations
$GLOBAL_CONFIG=array();
$new_date_ok=0;
# Create global config object
require_once($root_path.'include/care_api_classes/class_globalconfig.php');
require_once($root_path.'include/inc_date_format_functions.php');

$glob_obj=new GlobalConfig($GLOBAL_CONFIG);
if($glob_obj->getConfig('date_format')) $date_format=$GLOBAL_CONFIG['date_format'];
$date_format=$GLOBAL_CONFIG['date_format'];
$phpfd=$date_format;
$phpfd=str_replace("dd", "%d", strtolower($phpfd));
$phpfd=str_replace("mm", "%m", strtolower($phpfd));
$phpfd=str_replace("yyyy","%Y", strtolower($phpfd));
$phpfd=str_replace("yy","%y", strtolower($phpfd));

$title="Radiology :: Reports Generator";
/* 2007-09-27 FDP
 replaced the orig line (which follows) for Close button target
$breakfile=$root_path."modules/pharmacy/seg-pharma-retail-functions.php".URL_APPEND."&userck=$userck";
 */
$breakfile = "radiolog.php";
$thisfile='seg-radio-reports.php';

# Start Smarty templating here
 /**
 * LOAD Smarty
 */

 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme
 
   require_once($root_path.'include/inc_front_chain_lang.php');
	
	# Create laboratory service object
	require_once($root_path.'include/care_api_classes/class_radiology.php');
	$srvObj=new SegRadio();
	
	require_once($root_path.'include/care_api_classes/class_encounter.php');
	$enc_obj=new Encounter;
	
	require_once($root_path.'include/care_api_classes/class_personell.php');
	$pers_obj=new Personell;

	require_once($root_path.'include/care_api_classes/class_department.php');
	$dept_obj=new Department;
	
	require_once($root_path.'include/care_api_classes/class_person.php');
	$person_obj=new Person();
	
	require_once($root_path.'include/care_api_classes/class_ward.php');
	$ward_obj = new Ward;

	global $db;
 	
 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

 # Title in the title bar
 $smarty->assign('sToolbarTitle',"$title");

 # href for the back button
// $smarty->assign('pbBack',$returnfile);

 # href for the help button
 $smarty->assign('pbHelp',"javascript:gethelp('report_how2generate.php','Reports Generator')");

 # href for the close button
 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('sWindowTitle',"$title");

 # Assign Body Onload javascript code

 #$onLoadJS='onLoad="preSet();"';
 $onLoadJS='';
 $smarty->assign('sOnLoadJs',$onLoadJS);

 # Collect javascript code

 ob_start();
 # Load the javascript code
	#require($root_path.'include/inc_js_retail.php');
	echo '<link rel="stylesheet" type="text/css" media="all" href="' .$root_path.'js/jscalendar/calendar-win2k-cold-1.css">'."\r\n";
	echo '<script type="text/javascript" src="'.$root_path.'js/jsprototype/prototype.js"></script>'."\r\n";
	
	echo '<script type="text/javascript" src="'.$root_path.'js/setdatetime.js"></script>'."\r\n";
	echo '<script type="text/javascript" src="'.$root_path.'js/checkdate.js"></script>'."\r\n";
	echo '<script type="text/javascript" src="'.$root_path.'js/fat/fat.js"></script>'."\r\n";
	
	echo '<script type="text/javascript" src="'.$root_path.'js/jscalendar/calendar.js"></script>'."\r\n";
	echo '<script type="text/javascript" src="'.$root_path.'js/jscalendar/lang/calendar-en.js"></script>'."\r\n";
	echo '<script type="text/javascript" src="'.$root_path.'js/jscalendar/calendar-setup_3.js"></script>'."\r\n";
	
	echo '<script type="text/javascript" src="'.$root_path.'js/NumberFormat154.js"></script>'."\r\n";
	
	$sTemp = ob_get_contents();
 ob_end_clean();

 $smarty->append('JavaScript',$sTemp);

 $smarty->assign('sFormStart','<form ENCTYPE="multipart/form-data" action="'.$thisfile.'" method="post" name="inputform" id="inputform" onSubmit="return prufform()">');
 $smarty->assign('sFormEnd','</form>');

	$smarty->assign('sFromDateInput','<input name="fromdt" id="from_date" type="text" size="8" 
													value="">');
	$smarty->assign('sFromDateIcon','<img ' . createComIcon($root_path,'show-calendar.gif','0') . ' id="from_date_trigger" align="absmiddle" style="cursor:pointer">');
	
	$smarty->assign('sToDateInput','<input name="todt" id="to_date" type="text" size="8" 
													value="">');
	$smarty->assign('sToDateIcon','<img ' . createComIcon($root_path,'show-calendar.gif','0') . ' id="to_date_trigger" align="absmiddle" style="cursor:pointer">');
	
	$jsCalScript = "<script type=\"text/javascript\">
		Calendar.setup (
			{
				inputField : \"from_date\", 
				ifFormat : \"%Y-%m-%d\", 
				daFormat : \"$phpfd\", 
				showsTime : false, 
				button : \"from_date_trigger\", 
				singleClick : true, 
				step : 1
			}
		);
		Calendar.setup (
			{
				inputField : \"to_date\", 
				ifFormat : \"%Y-%m-%d\", 
				daFormat : \"$phpfd\", 
				showsTime : false, 
				button : \"to_date_trigger\", 
				singleClick : true, step : 1
			}
		);
</script>
";	

	$smarty->assign('jsCalendarSetup', $jsCalScript);


# Collect hidden inputs

ob_start();
$sTemp='';
 ?>

  <input type="hidden" name="sid" value="<?php echo $sid?>">
  <input type="hidden" name="lang" value="<?php echo $lang?>">
  <input type="hidden" name="cat" value="<?php echo $cat?>">
  <input type="hidden" name="userck" value="<?php echo $userck?>">  
  <input type="hidden" name="mode" id="modeval" value="<?php if($saveok) echo "update"; else echo "save"; ?>">
  <input type="hidden" name="encoder" value="<?php echo  str_replace(" ","+",$HTTP_COOKIES_VARS[$local_user.$sid])?>">
  <input type="hidden" name="dstamp" value="<?php echo  str_replace("_",".",date(Y_m_d))?>">
  <input type="hidden" name="tstamp" value="<?php echo  str_replace("_",".",date(H_i))?>">
  <input type="hidden" name="lockflag" value="<?php echo  $lockflag?>">
  <input type="hidden" name="update" value="<?php if($saveok) echo "1"; else echo $update;?>">
	
	<!--added by VAN 02-06-08-->
	<!--for shortcut keys -->
	<script type="text/javascript" src="<?=$root_path?>js/shortcut.js"></script>
	<script type="text/javascript">
		
		function prufform(){
		  var d = document.inputform;
		  
		  if (((d.from_date.value=='')&&(d.to_date.value!='')) || ((isNaN(d.from_date.value)==false)&&(isNaN(d.to_date.value)==true))) {
				alert("Enter the starting date of the report.");
				d.from_date.focus();
				return false;
		  }
			  
			if (((d.from_date.value!='')&&(d.to_date.value=='')) || ((isNaN(d.from_date.value)==true)&&(isNaN(d.to_date.value)==false))) {
				alert("Enter the end date of the report.");
				d.to_date.focus();
				return false;
			}
			  
			if (d.from_date.value > d.to_date.value){
				alert("Starting date should be earlier than the ending date");
				d.from_date.focus();
				return false;
			 }

			return true;
	 	}

		function viewStatistics(){
			var bol = prufform();
			if (bol){
				var fromdate = document.getElementById('from_date').value;
				var todate = document.getElementById('to_date').value;
				
				if (isNaN(fromdate)==false){
					fromdate = 0;
				}
				
				if (isNaN(todate)==false)
					todate = 0;	
				
				window.open("seg-radio-stat-report-pdf.php?fromdate="+fromdate+"&todate="+todate+"&showBrowser=1","viewPatientResult","width=620,height=440,menubar=no,resizable=yes,scrollbars=yes")
			}
		}
	</script>
	
<?php 

$sTemp = ob_get_contents();
ob_end_clean();

	$smarty->assign('sHiddenInputs',$sTemp);
	$smarty->assign('sBreakButton','<img '.createLDImgSrc($root_path,$sBreakImg,'0','left').' alt="'.$LDBack2Menu.'" onclick="window.location=\''.$breakfile.'\'" onsubmit="return false;" style="cursor:pointer">');
	#$smarty->assign('sContinueButton','<input type="image" '.createLDImgSrc($root_path,'continue.gif','0','left').' align="absmiddle">');
	
	$smarty->assign('sContinueButton','<img name="viewreport" id="viewreport" onClick="viewReport();" style="cursor:pointer" align="absmiddle" ' . createLDImgSrc($root_path,'showreport.gif','0','left') . ' border="0">');
	$smarty->assign('sStatButton','<img name="viewStat" id="viewStat" onClick="viewStatistics();" style="cursor:pointer" align="absmiddle" ' . createLDImgSrc($root_path,'showstatreport.gif','0','left') . ' border="0">');

	# Assign the form template to mainframe
	$smarty->assign('sMainBlockIncludeFile','radiology/form_report.tpl');

 /**
 * show Template
 */
 $smarty->display('common/mainframe.tpl');
?>