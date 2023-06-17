<?php

class PermissionHandler{
	
	function hasSession(){
		if(trim($_SESSION['sess_user_name']) != '' && 
			trim($_SESSION['sess_user_personell_nr']) != '' &&
			trim($_SESSION['sess_login_userid']) != '' &&
			trim($_SESSION['sess_permission']) != ''
		){
			return array(
				'message'	=> "",
				'hasSession' => true
			);
		}
		include('./roots.php');	
		
		ob_start();
	    header('Location:'.$root_path);
	    ob_end_flush();
	    exit();
	}
}

?>