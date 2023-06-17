<?php
/**
* @package care_api
*/

/**
*/
require_once($root_path.'include/care_api_classes/class_notes.php');
/**
*  Patient referral.
*  Note this class should be instantiated only after a "$db" adodb  connector object  has been established by an adodb instance.
* @author Elpidio Latorilla
* @version beta 2.0.1
* @copyright 2002,2003,2004,2005,2005 Elpidio Latorilla
* @package care_api
*/
class Configuration extends Notes {

   var $tb_config='seg_config';

   function GetImagePatch($field){
    global $db;

    $this->sql = "SELECT field_value
                    FROM ".$this->tb_config."
                    WHERE field_name = ".$db->qstr($field);
   if($result = $db->Execute($this->sql)){
            while ($row = $result->FetchRow()){
                if($row['field_value']){
                    return $row['field_value'];
                }else{
                    return  false;
                }
            }
        }else{
            return  false;
        }
   }
  
 }
?>
