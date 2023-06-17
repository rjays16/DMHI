<?php
#created by Bryan on November 18,2008

    function populateVitalSigns($vitalno, $encnr, $date, $bp, $temp, $weight, $resprate, $pulserate, $pid) {
        global $db;
        $objResponse = new xajaxResponse();
        $x=0;
        # Later: Put this in a Class
        if (!is_array($vitalno)) $encnr = array($vitalno);
        if (!is_array($encnr)) $encnr = array($encnr);
        if (!is_array($date)) $date = array($date);
        if (!is_array($bp)) $bp = array($bp);
        if (!is_array($temp)) $temp = array($temp);
        if (!is_array($weight)) $weight = array($weight);
        if (!is_array($resprate)) $resprate = array($resprate);
        if (!is_array($pid)) $pid = array($pid);
        if (!is_array($pulserate)) $pulserate = array($pulserate); 
                                              
        foreach ($date as $i=>$date) {
            //$objResponse->call("clearIssue",NULL);
        
            $obj = (object) 'details';
            
            $obj->date = $date;
            $obj->vitalno = $vitalno[$i];
            $obj->encnr = $encnr[$i];
            $obj->bp= $bp[$i];
            $obj->temp = $temp[$i];
            $obj->weight = $weight[$i];
            $obj->resprate = $resprate[$i];
            $obj->pulserate = $pulserate[$i];
            $obj->pid = $pid[$i];
            
            $objResponse->call("appendTheVitalList", NULL, $obj);  
        }
        return $objResponse;
    }
    
    function deleteVitaltoList($vitalno, $pid, $encount_nr){
        global $db;
        $objResponse = new xajaxResponse();
        $vitals_obj = new SegVitalsign();
        $success;
       
        $success = $vitals_obj->deleteVitalSign($vitalno);

        $fetchVits = $vitals_obj->fetchVitalsbyEncandPid($pid,$encount_nr);

        if($fetchVits){

            if($db->Affected_Rows()){                                                            //dsd
                $script = '<script type="text/javascript" language="javascript">'; 
                $i = 0;
                while($vitalRow = $fetchVits->fetchRow())
                { 
                    $vitalRowAddvitalno[$i] = $vitalRow['vitalsign_no'];
                    $vitalRowAddencounter_nr[$i] = $vitalRow['encounter_nr'];
                    $vitalRowAdddate[$i] = $vitalRow['date'];
                    $bpAdd[$i] = $vitalRow['systole']."/".$vitalRow['diastole'];
                    $vitalRowAddtemp[$i] = $vitalRow['temp'];
                    $vitalRowAddweight[$i] = $vitalRow['weight'];         
                    $vitalRowAddresp_rate[$i] = $vitalRow['resp_rate'];
                    $vitalRowAddpulse_rate[$i] = $vitalRow['pulse_rate'];
                    $vitalRowAddpid[$i] = $vitalRow['pid'];
                    $i++;
                
                }  
                
                $script .= "var vitalno0 = ['" .implode("','",$vitalRowAddvitalno)."'];";
                $script .= "var encnr0 = ['" .implode("','",$vitalRowAddencounter_nr)."'];";
                $script .= "var date0= ['" .implode("','",$vitalRowAdddate)."'];";
                $script .= "var bp0 = ['" .implode("','",$bpAdd). "'];";
                $script .= "var temp0 = ['" .implode("','",$vitalRowAddtemp). "'];";
                $script .= "var weight0= ['" .implode("','",$vitalRowAddweight). "'];";
                $script .= "var resprate0= ['" .implode("','",$vitalRowAddresp_rate). "'];";
                $script .= "var pid0= ['" .implode("','",$vitalRowAddpid). "'];";
                $script .= "var pulserate0 = ['" .implode("','",$vitalRowAddpulse_rate). "'];";
                $script .= "js_alert($pid);";
                $script .= "xajax_populateVitalSigns(vitalno0, encnr0, date0, bp0, temp0, weight0, resprate0, pulserate0, pid0);";
                $script .= "</script>";
                $src = $script;
            
                if ($src) $smarty->assign('sVitalItems',$src);  
            }
        }
        if($success){
            $objResponse->call('reload', $pid, $encount_nr);
        }
        
        return $objResponse; 
    }
    

    require('./roots.php');
    require($root_path.'include/inc_environment_global.php');
    require_once($root_path.'modules/registration_admission/ajax/vital.common.php');
    require_once($root_path.'include/care_api_classes/class_vitalsign.php');
    
    
    $xajax->processRequest();
?>



