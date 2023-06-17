<?php
#added by bryan on Sept 18,2008
#  
    function add_item($items, $item_names, $desc, $pending, $unitid, $perpc, $unitdesc, $expdate=NULL, $serial=NULL, $avg=NULL) {
        global $db;
        $objResponse = new xajaxResponse();
        
        //added by bryan
        $objprod = new SegPharmaProduct();

        # Later: Put this in a Class
        if (!is_array($items)) $items = array($items);
        if (!is_array($item_names)) $item_names = array($item_names);
        if (!is_array($desc)) $desc = array($desc);
        if (!is_array($pending)) $pending = array($pending);
        if (!is_array($unitid)) $unitid = array($unitid);
        if (!is_array($unitdesc)) $unitdesc = array($unitdesc);
        if (!is_array($perpc)) $perpc = array($perpc);
        if (!is_array($expdate)) $expdate = array($expdate);
        if (!is_array($serial)) $serial = array($serial);
        if (!is_array($avg)) $avg = array($avg); 
        
        foreach ($items as $i=>$item) {
             //added by bryan on 091409
             $extendedrow = $objprod->getExtendedProductInfo($item);
        
            #$objResponse->call("clearOrder",NULL);
            $obj = (object) 'details';
            $obj->id = $items[$i];
            $obj->name = $item_names[$i];
            $obj->desc= $desc[$i];
            $obj->pending = $pending[$i];
            $obj->unitid = $unitid[$i];
            $obj->unitdesc = $unitdesc[$i];
            $obj->perpc = $perpc[$i];
            $obj->expdate = $expdate[$i];
            $obj->serial = $serial[$i];
            //$obj->avg = $avg[$i]; 
            $obj->avg = $extendedrow['avg_cost'];  
            $obj->qtyperpack = $extendedrow['qty_per_pack'];                                

            $objResponse->call("appendOrder", NULL, $obj, false, $i);
        }
        return $objResponse;
    }
    
    function getRequestedAreasIss($s_areacode, $r_areacode='') {
         global $db;
        $objResponse = new xajaxResponse();
        
        $objdept = new Department();
        $area = new SegArea();
        $result = $area->getInventoryAreas();

        $count = 0;
        if ($result) {
            foreach($result as $row) {
                $checked=strtolower($row['area_code'])==strtolower($r_areacode) ? 'selected="selected"' : "";
                if($row['area_code'] == $s_areacode)
                    continue;
                $dest_area .= "<option value=\"".$row['area_code']."\" $checked>".$row['area_name']."</option>\n";
                if ($checked) $index = $count;
                $count++;
            }
            $dest_area = '<select class="jedInput" name="area_dest" id="area_dest" >'."\n".$dest_area."</select>\n".
                "<input type=\"hidden\" id=\"area3\" name=\"area3\" value=\"".$r_areacode."\"/>"; 
                   
            $objResponse->call("showRequestedAreasIss",$dest_area);
        }  
        
        return $objResponse;
    }  
    
    function getSourceAreasbypidIss($iss_id, $r_areacode='') {
         global $db;
        $objResponse = new xajaxResponse();
        
        $objdept = new Department();
        $objpnl = new Personell();

        $fetchNR = "select nr from care_personell where pid=$iss_id";
        $resultNR = $db->Execute($fetchNR);
        $rowNR = $resultNR->FetchRow();
         
        $deptofpersonnel = $objpnl->getPersonellInfo($rowNR['nr']);
        
        $result = $objdept->getAreasInDept($deptofpersonnel['location_nr']);
        $dest_area = "";
        $count = 0;
        if ($result) {
            if($row=$result->FetchRow())
            {
                $checked=strtolower($row['area_code'])==strtolower($r_areacode) ? 'selected="selected"' : "";
                $dest_area .= "<option value=\"".$row['area_code']."\" $checked>".$row['area_name']."</option>\n";
                if ($checked) $index = $count;

                $count++;
                
                $firstfetched = $row['area_code'];

                while($row=$result->FetchRow()){
                    $checked=strtolower($row['area_code'])==strtolower($r_areacode) ? 'selected="selected"' : "";          
                    $dest_area .= "<option value=\"".$row['area_code']."\" $checked>".$row['area_name']."</option>\n";
                    if ($checked) $index = $count;

                    $count++;
                }
                $dest_area = '<select class="jedInput" id="area_issued" name="area_issued" onchange="jsRqstngAreaOptionChngIss(this, this.options[this.selectedIndex].value);">'."\n".$dest_area."</select>\n".
                    "<input type=\"hidden\" id=\"area3\" name=\"area3\" value=\"".$r_areacode."\"/>"; 
            } 
                  
            $objResponse->call("showRequestedSRCAreasIss",$dest_area);  
            
            $dest_area = "";
            
            $result = $objdept->getAllAreas($firstfetched);
            $count = 0;
            if ($result) {
                while($row=$result->FetchRow()){
                    $checked=strtolower($row['area_code'])==strtolower($r_areacode) ? 'selected="selected"' : "";
                    $dest_area .= "<option value=\"".$row['area_code']."\" $checked>".$row['area_name']."</option>\n";
                    if ($checked) $index = $count;
                    $count++;
                }
                $dest_area = '<select class="jedInput" name="area_dest" id="area_dest" >'."\n".$dest_area."</select>\n".
                    "<input type=\"hidden\" id=\"area3\" name=\"area3\" value=\"".$r_areacode."\"/>"; 
                       
                $objResponse->call("showRequestedAreasIss",$dest_area);
            }  
            
                
        } 
         else {
            $dest_area_no = "<option value=\"\"> No areas for issuing personnel </option>\n"; 
            $dest_area = '<select class="jedInput" id="area_issued" name="area_issued" onchange="jsRqstngAreaOptionChngIss(this, this.options[this.selectedIndex].value);">'."\n".$dest_area_no."</select>\n".
                "<input type=\"hidden\" id=\"area3\" name=\"area3\" value=\"".$r_areacode."\"/>"; 
                
            $objResponse->call("showRequestedSRCAreasIss",$dest_area);
           
        }       

        return $objResponse;
    } 
    
    function reset_referenceno() {
        global $db;
        $objResponse = new xajaxResponse();
        
        $sc = new Issuance();
        $lastnr = $sc->getLastNr(date("Y-m-d"));

        if ($lastnr)
            $objResponse->call("resetRefNo",$lastnr);
        else
            $objResponse->call("resetRefNo","Error!",1);
        return $objResponse;
    }
    
    require('./roots.php');
    require($root_path.'include/inc_environment_global.php');

    require($root_path.'include/care_api_classes/class_discount.php');
    require($root_path.'include/care_api_classes/class_order.php');
    require_once($root_path.'include/care_api_classes/class_department.php');
    require_once($root_path.'include/care_api_classes/inventory/class_issuance.php');
    require_once($root_path.'include/care_api_classes/class_personell.php');
    require_once($root_path.'include/care_api_classes/class_pharma_product.php');   
    require_once($root_path.'modules/supply_office/ajax/issue.common.php');
    $xajax->processRequest();
?>
