<?php

require_once($root_path.'include/care_api_classes/class_core.php');

class Holidays extends Core {
	
	
	function SaveHoliday($date, $description, $created, $CreateTime){
		global $db;

		$this->sql ="INSERT INTO seg_holidays 
									(holiday_date,
									 description,
									 create_id,
									 create_time)
								VALUES (".$db->qstr($date).", 
									   ".$db->qstr($description).",
									   ".$db->qstr($created).",
									   ".$db->qstr($CreateTime).")";
		 $result = $db->Execute($this->sql);
		 if($result){
		 	return true;
		 }else{
		 	return false;
		 }
	}

   function GetHolidays(){
   	global $db;

   	$this->sql = "SELECT holiday_date, description, id 
   					FROM seg_holidays
   					WHERE is_deleted = '0'";

   	$result = $db->Execute($this->sql);
   	if($result){
   		return $result;
   	}else{
   		return false;
   	}

   }

  function GetHolidayInfo($id){
		global $db;

		$this->sql = "SELECT holiday_date, description
						FROM seg_holidays
						WHERE id =".$db->qstr($id);

		if ($this->result=$db->Execute($this->sql)) {
				 $this->count=$this->result->RecordCount();
				 return $this->result->FetchRow();
			} else{
				 return FALSE;
			}
	}

  function UpdateHoliday($date, $description, $modify_id, $modify_time, $id){
  	global $db;

  	$this->sql ="UPDATE seg_holidays
  					SET holiday_date = ".$db->qstr($date).",
  						description = ".$db->qstr($description).",
  						modify_id =".$db->qstr($modify_id).",
  						modify_time = ".$db->qstr($modify_time)."
  					WHERE id = ".$db->qstr($id);
  	 $result = $db->Execute($this->sql);
		 if($result){
		 	return true;
		 }else{
		 	return false;
		 }
  }
	
#added by daryl
#query to delete holiday
function DeleteHoliday($holiday_id) {
		global $db,$HTTP_SESSION_VARS;

		if(empty($holiday_id) || (!$holiday_id))
			return FALSE;

		$this->sql="DELETE FROM seg_holidays WHERE id='$holiday_id'";
		return $this->Transact();
	}	

}
?>