<?php
#added by daryl
#create a server for ajax hospital holiday
#10/20/14

		require('./roots.php');
		require($root_path.'include/inc_environment_global.php');
		require_once($root_path.'include/care_api_classes/class_paginator.php');
		require_once($root_path.'include/care_api_classes/class_globalconfig.php');
		require_once($root_path.'include/care_api_classes/class_holidays.php');

		require_once($root_path.'modules/system_admin/ajax/hosp_holiday.common.php');

		#-------------------added by VAN -----------------------------------
	
		
		#added by VAN 06-10-08
		function deleteHospHoliday($holiday_id,$holiday_name){
				global $db;
				$holiday_obj = new Holidays;
				$objResponse = new xajaxResponse();

				$result = $holiday_obj->DeleteHoliday($holiday_id);

				if ($result){
					$objResponse->addScriptCall("removeHolidayID",$holiday_id);
					$objResponse->addAlert(strtoupper($holiday_name)." is successfully deleted.");
				}else{
					$objResponse->addAlert(strtoupper($holiday_name)." is not deleted.");
				}

				return $objResponse;
		}
		#--------------------


		$xajax->processRequests();
?>