<?php
//error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/inc_environment_global.php');
/**
* CARE2X Integrated Hospital Information System Deployment 2.1 - 2004-10-02
* GNU General Public License
* Copyright 2002,2003,2004,2005 Elpidio Latorilla
* elpidio@care2x.org,
*
* See the file "copy_notice.txt" for the licence notice
*/
define('LANG_FILE','specials.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/inc_front_chain_lang.php');
$breakfile='startframe.php'.URL_APPEND;
$thisfile=basename(__FILE__);

// reset all 2nd level lock cookies
require($root_path.'include/inc_2level_reset.php');

$HTTP_SESSION_VARS['sess_file_break']=$top_dir.$thisfile;
$HTTP_SESSION_VARS['sess_file_return']=$top_dir.$thisfile;
$HTTP_SESSION_VARS['sess_file_editor']='headline-edit-select-art.php';
$HTTP_SESSION_VARS['sess_file_reader']='headline-read.php';
$HTTP_SESSION_VARS['sess_title']=$LDEditTitle.'::'.$LDSubmitNews;
//$HTTP_SESSION_VARS['sess_user_origin']='main_start';
$HTTP_SESSION_VARS['sess_path_referer']=$top_dir.$thisfile;
$HTTP_SESSION_VARS['sess_dept_nr']=0; // reset the department number used in the session

# Start Smarty templating here
 /**
 * LOAD Smarty
 */

 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

 # Create a helper smarty object without reinitializing the GUI
 $smarty2 = new smarty_care('common', FALSE);

# Toolbar title

 $smarty->assign('sToolbarTitle',$LDSpexFunctions);

 # href for the help button
 $smarty->assign('pbHelp',"javascript:gethelp('submenu1.php','$LDSpexFunctions')");

 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('title',$LDSpexFunctions);

 # Prepare the submenu icons

 $aSubMenuIcon=array(createComIcon($root_path,'notepad.gif','0'),
										// createComIcon($root_path,'dollarsign.gif','0'),
										// createComIcon($root_path,'calculator_edit.png','0'),
										createComIcon($root_path,'application_form_add.png','0'),	#---added by CHA, Feb 23, 2010
										createComIcon($root_path,'dollarsign.gif','0'),
										createComIcon($root_path,'man-gr.gif','0'),
										createComIcon($root_path,'lockfolder.gif','0'),
										createComIcon($root_path,'home2.gif','0'),
										createComIcon($root_path,'settings_tree.gif','0'),
										createComIcon($root_path,'bnplus.gif','0'),
										createComIcon($root_path,'team_wksp.gif','0'),
										createComIcon($root_path,'lampboard-small.gif','0'),
										createComIcon($root_path,'lampboard-small.gif','0'),
										createComIcon($root_path,'lampboard-small.gif','0'),
										createComIcon($root_path,'camera_s.gif','0'),
										createComIcon($root_path,'video.gif','0'),
										createComIcon($root_path,'man-gr.gif','0'),
										createComIcon($root_path,'snowflake.gif','0'),
										createComIcon($root_path,'calmonth.gif','0'),
										createComIcon($root_path,'bubble.gif','0'),
										createComIcon($root_path,'calendar.gif','0'),
										createComIcon($root_path,'settings_tree.gif','0'),
										createComIcon($root_path,'padlock.gif','0'),
										createComIcon($root_path,'discussions.gif','0')
										);

# Prepare the submenu item descriptions

$aSubMenuText=array($LDPluginsTxt,
										/*$LDBillingTxt,*/
										// $ServicePriceTxt,
										// 'Add, edit and delete data for managing cost center gui',
										'Add, edit and delete service price for every area',
										$LDPersonellMngmntTxt,
										$LDInsuranceCoMngrTxt,
										$LDAddressMngrTxt,
										$LDOccupationMngrTxt,
										$LDReligionMngrTxt,
										$LDEthnicMngrTxt,
										$LDicd10Mngrtxt,
										$LDicpmMngrtxt,
										$LDicpmMngrtxt." For PHIC",
										$LDPhotoLabTxt,
										$LDWebCamTxt,
										$swSocialServiceTxt,
										$LDStandbyDutyTxt,
										$LDCalendarTxt,
										$LDNewsTxt,
										$LDCalcTxt,
										$LDUserConfigOptTxt,
										$LDAccessPwTxt,
										$LDNewsgroupTxt
										);

# Prepare the submenu item links indexed by their template tags

//$aSubMenuItem=array('LDPlugins' => '<a href="'.$root_path.'plugins/plugins.php'.URL_APPEND.'">'.$LDPlugins.'</a>',
//										'LDBilling' => '<a href="'.$root_path.'modules/ecombill/ecombill_pass.php'.URL_APPEND.'">'. $LDBilling.'</a>',
//										'LDPersonellMngmnt' => '<a href="'.$root_path.'modules/personell_admin/personell_admin_pass.php'.URL_APPEND.'&retpath=spec&target=personell_reg">'.$LDPersonellMngmnt.'</a>',
//										'LDInsuranceCoMngr' => '<a href="'.$root_path.'modules/insurance_co/insurance_co_manage_pass.php'.URL_APPEND.'">'. $LDInsuranceCoMngr.'</a>',
//										'LDAddressMngr' => '<a href="'.$root_path.'modules/address/address_menu_pass.php'.URL_APPEND.'">'. $LDAddressMngr.'</a>',
//										'LDicd10Mngr' => '<a href="'.$root_path.'modules/ICD10/icd10_manage_pass.php'.URL_APPEND.'">'. $LDicd10Mngr.'</a>',
//										'LDicpmMngr' => '<a href="'.$root_path.'modules/ICPM/icpm_manage_pass.php'.URL_APPEND.'">'. $LDicpmMngr.'</a>',
//										'LDPhotoLab' => '<a href="'.$root_path.'modules/fotolab/fotolab_pass.php'.URL_APPEND.'&ck_config='.$ck_config.'">'.$LDPhotoLab.'</a>',
//										'LDWebCam' => '<a href="../modules/video_monitor/video_monitoring.php'.URL_APPEND.'">'.$LDWebCam.'</a>',
//										'LDStandbyDuty' => '<a href="'.$root_path.'main/spediens-bdienst-zeit-erfassung.php'.URL_APPEND.'&retpath=spec">'.$LDStandbyDuty.'</a>',
//										'LDCalendar' => '<a href="'.$root_path.'modules/calendar/calendar.php'.URL_APPEND.'">'. $LDCalendar.'</a>',
//										'LDNews' => '<a href="'.$root_path.'modules/news/editor-pass.php'.URL_APPEND.'&dept_nr=1&title='.$LDEditTitle.'">'.$LDNews.'</a>',
//										'LDCalc' => '<a href="'.$root_path.'modules/tools/calculator.php'.URL_APPEND.'">'. $LDCalc.'</a>',
//										'LDUserConfigOpt' => '<a href="config_options.php'.URL_APPEND.'">'. $LDUserConfigOpt.'</a>',
//										'LDAccessPw' => '<a href="'.$root_path.'modules/myintranet/my-passw-change.php'.URL_APPEND.'">'. $LDAccessPw.'</a>',
//										'LDNewsgroup' => '<a href="http://www.mail-archive.com/care2002-developers@lists.sourceforge.net/">'.$LDNewsgroup.'</a>'
//
//										);

$aSubMenuItem=array('LDPlugins' => '<a href="'.$root_path.'plugins/plugins.php'.URL_APPEND.'">'.$LDPlugins.'</a>',
										// 'ServicePrice' => '<a href="'.$root_path.'modules/price_adjustments/seg_price_adjustments_pass.php'.URL_APPEND.'&target=adjustment">'.$ServicePrice.'</a>',
										// 'ServicePriceList' => '<a href="'.$root_path.'modules/price_adjustments/seg_price_adjustments_pass.php'.URL_APPEND.'&target=pricelist">Services Pricelist</a>',
										'LDCostCenterGuiMgr' => '<a href="'.$root_path.'modules/system_admin/cost_center_gui_mgr/cost-center-mgr-pass.php?target=add_gui&'.URL_APPEND.'">Cost Center Gui Manager</a>',
										'LDBilling' => '<a href="'.$root_path.'modules/ecombill/ecombill_pass.php'.URL_APPEND.'">'. $LDBilling.'</a>',
										'LDBillingMain' => '<a href="'.$root_path.'modules/billing/billing_main_pass.php'.URL_APPEND.'">'. $sgBilling.'</a>',
										'LDPersonellMngmnt' => '<a href="'.$root_path.'modules/personell_admin/personell_admin_pass.php'.URL_APPEND.'&retpath=spec&target=personell_reg">'.$LDPersonellMngmnt.'</a>',
										'LDInsuranceCoMngr' => '<a href="'.$root_path.'modules/insurance_co/seg_insurance_pass.php'.URL_APPEND.'">'. $LDInsuranceCoMngr.'</a>',
										'LDAddressMngr' => '<a href="'.$root_path.'modules/address/address_menu_pass.php'.URL_APPEND.'">'. $LDAddressMngr.'</a>',
										'LDOccupationMngr' => '<a href="'.$root_path.'modules/system_admin/edv-system_manage_pass.php'.URL_APPEND.'&target=occupation">'. $LDOccupationMngr.'</a>',
										'LDReligionMngr' => '<a href="'.$root_path.'modules/system_admin/edv-system_manage_pass.php'.URL_APPEND.'&target=religion">'. $LDReligionMngr.'</a>',
										'LDEthnicMngr' => '<a href="'.$root_path.'modules/system_admin/edv-system_manage_pass.php'.URL_APPEND.'&target=ethnic">'. $LDEthnicMngr.'</a>',
										'LDicd10Mngr' => '<a href="'.$root_path.'modules/ICD10/icd10_manage_pass.php'.URL_APPEND.'">'. $LDicd10Mngr.'</a>',
										'LDicpmMngr' => '<a href="'.$root_path.'modules/ICPM/icpm_manage_pass.php'.URL_APPEND.'&phic=0">'. $LDicpmMngr.'</a>',
										'LDicpmMngr2' => '<a href="'.$root_path.'modules/ICPM/icpm_manage_pass.php'.URL_APPEND.'&phic=1">'. $LDicpmMngr.' For PHIC </a>',
										'LDPhotoLab' => '<a href="'.$root_path.'modules/fotolab/fotolab_pass.php'.URL_APPEND.'&ck_config='.$ck_config.'">'.$LDPhotoLab.'</a>',
										'LDWebCam' => '<a href="../modules/video_monitor/video_monitoring.php'.URL_APPEND.'">'.$LDWebCam.'</a>',
										'LDSocialService'=>'<a href="'.$root_path.'modules/social_service/administrator/social_service_admin_pass.php'.URL_APPEND.'">'.$swSocialService.'</a>',
										'LDStandbyDuty' => '<a href="'.$root_path.'main/spediens-bdienst-zeit-erfassung.php'.URL_APPEND.'&retpath=spec">'.$LDStandbyDuty.'</a>',
										'LDCalendar' => '<a href="'.$root_path.'modules/calendar/calendar.php'.URL_APPEND.'">'. $LDCalendar.'</a>',
										'LDNews' => '<a href="'.$root_path.'modules/news/editor-pass.php'.URL_APPEND.'&dept_nr=1&title='.$LDEditTitle.'">'.$LDNews.'</a>',
										'LDCalc' => '<a href="'.$root_path.'modules/tools/calculator.php'.URL_APPEND.'">'. $LDCalc.'</a>',
										'LDUserConfigOpt' => '<a href="config_options.php'.URL_APPEND.'">'. $LDUserConfigOpt.'</a>',
										'LDAccessPw' => '<a href="'.$root_path.'modules/myintranet/my-passw-change.php'.URL_APPEND.'">'. $LDAccessPw.'</a>',
										'LDNewsgroup' => '<a href="http://www.mail-archive.com/care2002-developers@lists.sourceforge.net/">'.$LDNewsgroup.'</a>'

										);

# Create the submenu rows

$iRunner = 0;

while(list($x,$v)=each($aSubMenuItem)){
	$sTemp='';
	ob_start();
		if($cfg['icons'] != 'no_icon') $smarty2->assign('sIconImg','<img '.$aSubMenuIcon[$iRunner].'>');
		$smarty2->assign('sSubMenuItem',$v);
		$smarty2->assign('sSubMenuText',$aSubMenuText[$iRunner]);
		$smarty2->display('common/submenu_row.tpl');
		$sTemp = ob_get_contents();
	ob_end_clean();
	$iRunner++;
	$smarty->assign($x,$sTemp);

}

# Create conditional submenu items

if(($cfg['bname']=="msie")&&($cfg['bversion']>4)){
	$sTemp='';
	ob_start();
		if($cfg['icons'] != 'no_icon') $smarty2->assign('sIconImg','<img '.createComIcon($root_path,'uhr.gif','0').'>');
		$smarty2->assign('sSubMenuItem','<a href="'.$root_path.'modules/tools/clock.php?sid='.$sid.'&lang='.$lang.'">'.$LDClock.'</a>');
		$smarty2->assign('sSubMenuText',$LDDigitalClock);
		$smarty2->display('common/submenu_row.tpl');
		$sTemp = ob_get_contents();
	ob_end_clean();

	$smarty->assign('LDClock',$sTemp);
	$smarty->assign('bShowClock',TRUE);
}

# Assign the submenu to the mainframe center block

 $smarty->assign('sMainBlockIncludeFile','common/submenu_specialtools.tpl');

 /**
 * show Template
 */

 $smarty->display('common/mainframe.tpl');
 // $smarty->display('debug.tpl');
 ?>
