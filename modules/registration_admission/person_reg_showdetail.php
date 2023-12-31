<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/inc_environment_global.php');
/**
* CARE2X Integrated Hospital Information System beta 2.0.1 - 2004-07-04
* GNU General Public License
* Copyright 2002,2003,2004,2005 Elpidio Latorilla
* elpidio@care2x.org, 
*
* See the file "copy_notice.txt" for the licence notice
*/
$lang_tables[]='prompt.php';
$lang_tables[]='person.php';
define('LANG_FILE','aufnahme.php');
$local_user='aufnahme_user';
require_once($root_path.'include/inc_front_chain_lang.php');

$thisfile=basename(__FILE__);
$breakfile='patient.php';
$admissionfile='aufnahme_start.php'.URL_APPEND;

#echo  "BEFORE pid = '".$pid."' <br> \n";

if((!isset($pid)||!$pid)&&$HTPP_SESSION_VARS['sess_pid']) $pid=$HTPP_SESSION_VARS['sess_pid'];
/*
echo  "HTPP_SESSION_VARS['sess_pid'] = '".$HTPP_SESSION_VARS['sess_pid']."' <br> \n";
echo  "AFTER pid = '".$pid."' <br> \n";
echo  "URL_APPEND = '".$URL_APPEND."' <br> \n";
echo  "URL_REDIRECT_APPEND = '".URL_REDIRECT_APPEND."' <br> \n";
*/
if (isset($pid)){      # burn added: March 28, 2007
		$HTTP_SESSION_VARS['sess_path_referer']=$top_dir.$thisfile;
		$HTTP_SESSION_VARS['sess_file_return']=$thisfile;
		$HTTP_SESSION_VARS['sess_pid']=$pid;
		//$HTTP_SESSION_VARS['sess_full_pid']=$pid+$GLOBAL_CONFIG['person_id_nr_adder'];
		$HTTP_SESSION_VARS['sess_parent_mod']='registration';
		$HTTP_SESSION_VARS['sess_user_origin']='registration';
		# Reset the encounter number
		$HTTP_SESSION_VARS['sess_en']=0;
		
		# Load the standard tags functions
		require('./gui_bridge/default/gui_std_tags.php');
		
		######## here starts the GUI ############
		
		echo StdHeader();
		echo setCharSet();
		?>
		 <TITLE><?php echo $LDPatientRegister ?></TITLE>
		
		<?php
		require($root_path.'include/inc_js_gethelp.php');
		require($root_path.'include/inc_css_a_hilitebu.php');
		?>
		
		</HEAD>
		
		<BODY bgcolor="<?php echo $cfg['bot_bgcolor'];?>" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0 onLoad="if (window.focus) window.focus();"
		<?php if (!$cfg['dhtml']){ echo 'link='.$cfg['body_txtcolor'].' alink='.$cfg['body_alink'].' vlink='.$cfg['body_txtcolor']; } ?>>
		
		<table width=100% border=0 cellspacing="0"  cellpadding=0 >
		<tr>
		<td bgcolor="<?php echo $cfg['top_bgcolor']; ?>">
		<FONT  COLOR="<?php echo $cfg['top_txtcolor']; ?>"  SIZE=+2  FACE="Arial"><STRONG> &nbsp;<?php echo $LDPatientRegister ?></STRONG> <font size=+2>(<?php echo ($pid) ?>)</font></FONT>
		</td>
		
		<td bgcolor="<?php echo $cfg['top_bgcolor']; ?>" align="right">
		<a href="javascript:gethelp('person_details.php')"><img <?php echo createLDImgSrc($root_path,'hilfe-r.gif','0') ?>  <?php if($cfg['dhtml'])echo'style=filter:alpha(opacity=70) onMouseover=hilite(this,1) onMouseOut=hilite(this,0)>';?></a><a
		href="javascript:window.close()"><img <?php echo createLDImgSrc($root_path,'close2.gif','0') ?> alt="<?php echo $LDCloseWin ?>"   <?php if($cfg['dhtml'])echo'style=filter:alpha(opacity=70) onMouseover=hilite(this,1) onMouseOut=hilite(this,0)>';?></a>
		</td>
		</tr>
		
		<tr>
		<td colspan=3   bgcolor="<?php echo $cfg['body_bgcolor']; ?>">
		<ul>
		
		<?php
		
			# Display the data
		
			require_once($root_path.'include/care_api_classes/class_gui_person_show.php');
			$person = & new GuiPersonShow;
			$person->setPID($pid);
			$person->display();
		
		?>
		
		</ul>
		
		</FONT>
		<p>
		</td>
		</tr>
		</table>
		
		<p>
		<ul>
		
		<a href="javascript:window.close()"><img <?php echo createLDImgSrc($root_path,'close2.gif','0') ?> alt="<?php echo $LDCloseWin ?>"></a>
		</ul>
		<p>
		<?php
		require($root_path.'include/inc_load_copyrite.php');
		?>
		</FONT>
		<?php
		StdFooter();
		?>
<?php

}else{   
		# burn added: March 28, 2007
	if(file_exists("patient_register_pass.php")){
		header("Location: patient_register_pass.php".URL_REDIRECT_APPEND);
		exit;
	}else{
		header("Location: startframe.php".URL_REDIRECT_APPEND);
		exit();
	}		
}

?> 

