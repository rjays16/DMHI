<?php
/**
* @package care_api
*/
require_once($root_path.'include/care_api_classes/class_core.php');
  
class Unit extends Core{    
    var $tb_unit = "seg_unit";
    
    /*
    * @var Integer
    */
    var $unit_id;
    /*
    * @var String
    */
    var $unit_name;
    /*
    * @var String
    */
    var $unit_desc;   
    /*
    * @var Boolean
    */
    var $is_unit_per_pc; 
    
    /*
    * @var Array
    */    
    var $unit_flds = array(
        "unit_id",
        "unit_name",
        "is_unit_per_pc",
        "unit_desc",
        "is_deleted",
        "is_default",
        "modify_id",
        "create_id"        
    ); 
    
    function Unit() {
        $this->coretable = $this->tb_unit;
        $this->setTable($this->coretable);
        $this->setRefArray($this->unit_flds);
        $this->unit_id = DEFAULT_UNIT;
        $this->is_unit_per_pc = 1;
    }
    
    function getUnitName($id) {
        global $db;
        
        $this->sql = "select unit_name from $this->tb_unit where unit_id = $id";
        if ($result = $db->Execute($this->sql)) {
            if ($this->rec_count=$result->RecordCount()) {
                $row = $result->FetchRow();
                $this->unit_name = $row["unit_name"];
                return $row["unit_name"];
            }
            else
                return FALSE;
        }
        else
            return FALSE;
    }
    
    function getUnitDesc($id) {
        global $db;
        
        $this->sql = "select unit_desc from $this->tb_unit where unit_id = $id";
        if ($result = $db->Execute($this->sql)) {
            if ($this->rec_count=$result->RecordCount()) {
                $row = $result->FetchRow();
                $this->unit_desc = $row["unit_desc"]; 
                return $row["unit_desc"];
            }
            else
                return FALSE;
        }
        else
            return FALSE;
    }
    
    function isUnitIDBigUnit($unit) {
        global $db;
        
        $this->sql = "SELECT is_unit_per_pc FROM seg_unit WHERE unit_id=$unit";
        if ($result = $db->Execute($this->sql)) {
            if ($this->rec_count=$result->RecordCount()) {
                $row = $result->FetchRow();
                $this->is_unit_per_pc = ($row["is_unit_per_pc"] != 0);
                return ($row["is_unit_per_pc"] == 0);
            }
            else
                return FALSE;
        }
        else
            return FALSE;        
    }
	
	function getAllUnits(){
		global $db;
		
		$this->sql="SELECT * FROM seg_unit ORDER BY unit_name";
						  
		if ($this->result=$db->Execute($this->sql)) {
	    if ($this->count=$this->result->RecordCount()){
			return $this->result;
		 }else{
				return FALSE;
		 }
	 	}else{
			return FALSE;
    	}
  	}
    
    function getUnitInfo($id){
        global $db;
        
        $this->sql="SELECT * FROM $this->tb_unit where unit_id = $id";                          
        if ($this->result=$db->Execute($this->sql)) {
            if ($this->count=$this->result->RecordCount()){
                return ($this->result->FetchRow());
             }else{
                    return FALSE;
             }
         }else{
            return FALSE;
        }
      }    
	
	function getUnitPerPc($id) {
        global $db;
        
        $this->sql = "select is_unit_per_pc from $this->tb_unit where unit_id = $id";
        if ($result = $db->Execute($this->sql)) {
            if ($this->rec_count=$result->RecordCount()) {
                $row = $result->FetchRow();
                $this->unit_desc = $row["is_unit_per_pc"]; 
                return $row["is_unit_per_pc"];
            }
            else
                return FALSE;
        }
        else
            return FALSE;
    }
    
    function deleteUnit($id) {
        $this->sql = "delete from $this->tb_unit where unit_id = $id";
        return($this->Transact($this->sql));        
    }    
}
?>
