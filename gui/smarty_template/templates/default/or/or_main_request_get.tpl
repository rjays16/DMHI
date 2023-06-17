<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title></title>
{{$check_date_string}}
{{$or_main_css}}
{{foreach from=$javascript_array item=js}}
		{{$js}}
{{/foreach}}
<script>


</script>
</head>
<body onload="preset();">

<div id="or_main_request" align="left">
	{{$form_start}}
	<span id="reminder">Required fields are marked with {{$required_mark}}</span>


	<fieldset>
	<legend>Patient Information</legend>
	<table>
		<tr>
			<td width="210px"><label>Patient Name:</label><!-- {{$required_mark}}--></td>
			<td width="160px"><strong>{{$patient_name}}</strong></td>
			<td>{{$patient_select_button}}</td>
			<td><span id="patient_name_msg">{{$error_input}}</span></td>
		</tr>
		<tr>
			<td><label>Patient Gender:</label></td>
			<td>{{$patient_gender}}</td>
			<td>{{$error_input}}</td>
		</tr>

		<tr>
			<td><label>Patient Age:</label></td>
			<td>{{$patient_age}}</td>
			<td>{{$error_input}}</td>
		</tr>
		<tr>
			<td><label>Patient Address:</label></td>
			<td>{{$patient_address}}</td>
			<td>{{$error_input}}</td>
		</tr>
		<tr>
			<td><label>Hospital Number:</label></td>
			<td>{{$patient_hospital_number}}</td>
			<td>{{$error_input}}<td>
		</tr>
	</table>
	</fieldset>

	<fieldset>
		<legend>Request Details</legend>
	<table>
		<!--<tr>
			<td width="210px"><label>Department:</label></td>
			<td width="160px">{{$or_request_department}}</td>
			<td></td>
			<td></td>
		</tr> -->
		<tr>
			<td><label>Department</label> {{$required_mark}} </td>
				<td>
					<select name="or_request_department" id="or_request_department">
						{{html_options options=$or_request_department selected=$or_request_department_selected}}
					</select>
				</td>
					<td valign="middle"><span id="or_request_department_msg">{{$error_input}}</span></td>
		</tr>
		<!--<tr>
			<td><label>Operating Room:</label></td>
			<td>{{$or_op_room}}</td>
			<td></td>
			<td></td>
		</tr>-->
		<tr>
			<td valign="middle"><label>Transaction:</label> {{$required_mark}}</td>
			<td>{{html_radios name="or_transaction_type" options=$or_transaction_type selected=$or_transaction_type_selected id="or_transaction_type" disabled="disabled"}}</td>
			<td valign="middle"><span id="transaction_type_msg">{{$error_input}}</span></td>
			<td></td>
		</tr>
		<tr>
			<td valign="middle"><label>OR Type:</label> {{$required_mark}}</td>
			<td>
				<select name="or_type" id="or_type">
					{{html_options options=$or_type selected=$or_type_selected}}
				</select>
			</td>
			<td valign="middle"><span id="transaction_type_msg">{{$error_input}}</span></td>
			<td></td>
		</tr>
		<tr>
			<td valign="middle"><label>Priority:</label> {{$required_mark}}</td>
			<td id="priority">{{html_radios name="or_request_priority" options=$or_request_priority separator="<br/>" selected=$or_request_priority_selected id="or_request_priority" disabled="disabled"}}</td>
			<td valign="middle"><span id="priority_msg">{{$error_input}}</span></td>
			<td></td>
		</tr>

		<tr>
			<td><label>Date and Time Requested:</label></td>
			<td>{{$or_request_date_display}}{{$or_request_date}}</td>
			<td>{{$or_request_dt_picker}}</td>
			<td>{{$or_request_calendar_script}}</td>
		</tr>

		<tr>
			<td><label>Ward:</label></td>
			<td>{{$ward}}</td>
		</tr>

		<!--Added by Cherry 04-28-10-->
		<tr>
			<td><label>Requesting SROD/Surgeon:</label> {{$required_mark}} </td>
				<td>
					<select name="or_doctor" id="or_doctor">
						{{html_options options=$or_doctor selected=$or_doctor_selected}}
					</select>
				</td>
				 <td valign="middle"><span id="or_doctor_msg">{{$error_input}}</span></td>
		</tr>

		<tr>
			<td><label>Date and Time Received:</label></td>
			<td>{{$or_received_date_time}}</td>
		</tr>

		<!--
		<tr>
			<td valign="middle"><label>Consent Signed: {{$required_mark}}</label></td>
			<td>{{html_radios name="or_consent_signed" options=$or_consent_signed selected=$or_consent_signed_selected}}</td>
			<td valign="middle"><span id="or_consent_signed_msg">{{$error_input}}</span></td>
			<td></td>
		</tr>

		<tr>
			<td valign="middle"><label>Case: {{$required_mark}}</label></td>
			<td>
				<table cellpadding="5" cellspacing="5">
					<tr>

						<td width="90px">Service:<br/>{{html_radios name="or_request_case" options=$or_request_case_service separator="<br/>" selected=$or_request_case_selected}}</td>

						<td>Pay:<br/>{{html_radios name="or_request_case" options=$or_request_case_pay separator="<br/>" selected=$or_request_case_selected}}</td>
					</tr>
				</table>
			</td>
			<td valign="middle"><span id="or_request_case_msg">{{$error_input}}</span></td>
			<td></td>


		</tr>-->


	</table>
	</fieldset>

	<fieldset>
		<legend>Pre-operation Details</legend>
		<table>
			<!--<tr>
				<td width="210px"><label>Date and time of operation:</label></td>
				<td width="160px">{{$or_operation_date_display}}{{$or_operation_date}}</td>
				<td>{{$or_operation_dt_picker}}</td>
				<td>{{$or_operation_calendar_script}}</td>
			</tr>-->

			<!--<tr>
				<td><label>Estimated length of operation:</label></td>
				<td>{{$or_est_op_length}}</td>
				<td></td>
			</tr> -->

			<!--<tr>
				<td valign="middle"><label>Case classification:</label> {{$required_mark}}</td>
				<td>{{html_radios name="or_case_classification" options=$or_case_classification separator="<br/>" selected=$or_case_classification_selected}}</td>
				<td valign="middle"><span id="or_case_classification_msg">{{$error_input}}</span></td>
			</tr> -->
			<tr>
				<td width="210px"><label>Date and Time of Operation:</label></td>
				<td width="160px">{{$or_operation_date_display}}{{$or_operation_date}}</td>
				<td>{{$or_operation_dt_picker}}</td>
				<td>{{$or_operation_calendar_script}}</td>
			</tr>

			<tr>
				<td><label>Procedure:</label></td>
				<td>{{$package_name}}</td>
				<td>{{$procedure_select}}</td>
				<td></td>
			</tr>

			<tr>
				<td valign="middle"><label>Pre-operative diagnosis:</label></td>
				<td>{{$pre_operative_diagnosis}}</td>
				<td></td>
			</tr>

			<tr>
				<td valign="middle"><label>Remarks:</label></td>
				<td>{{$remarks}}</td>
				<td></td>
			</tr>
		</table>
	</fieldset>

	<fieldset>
		<legend>Requirements</legend>
		<table>
			<tr>
				<td width="210px" valign="middle"><label>Special requirements:</label> {{$required_mark}}</td>
				<td width="180px">{{html_checkboxes name="or_special_requirements" options=$or_special_requirements separator="<br/>" selected=$or_special_requirements_selected id="or_special_requirements" disabled="disabled"}}</td>
				<td valign="middle"><span id="special_req_msg">{{$error_input}}</span></td>
			</tr>
		</table>
	</fieldset>

	{{$package_id}}

	{{$or_main_submit}}
	{{$or_main_cancel}}
	{{$or_main_print}}
	{{$patient_pid}}
	{{$encounter_nr}}
	{{$hospital_number}}
	{{$mode}}
	{{$submitted}}
	{{$dept_nr}}
	{{$op_room}}
	{{$op_nr}}
	{{$refno}}
	{{$or_request_nr}}
	{{$form_end}}
</div>

</body>
</html>