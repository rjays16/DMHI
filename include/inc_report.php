<?php 

  /**
   * 
   */
  class Report
  {
    
    function select_global($db, $template_name)
    {
      $select_global = "SELECT value FROM care_config_global WHERE type = 'new_report_jrxml'";
        $result_global = $db->Execute($select_global);
        $row_global = $result_global->FetchRow();
        $value = $row_global['value'];
        $value_new = explode(',', $value);

        $path = java_resource;
            if (in_array($template_name, $value_new)) {
                $path = java_resource_new;
            }

        return $path;
    }
  }

 ?>