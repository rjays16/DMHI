$(document).ready(function(){
	if($("#comp_id").val()!=0){
		getData();
	}
});

function saveComp(action){
	if($('#full_name').val() && $('#address').val() && $('#short_name').val()){
		var details = new Object();

		details = {
			comp_full_name:$('#full_name').val(),
			comp_name:$('#short_name').val(),
			comp_add:$('#address').val(),
			comp_email_add:$('#e_add').val(),
			comp_phone_nr:$('#contact').val(),
			comp_ceo:$('#pres').val(),
			comp_hr:$('#hr').val(),
			c_type: $('#type').val(),
			comp_id: $('#comp_id').val()
		};
		
		if(action == 'save'){
			xajax_saveCompany(details);
		}else if(action == 'update'){
			xajax_updateCompany(details);
		}

	}else{
		alert("Please complete required fields");
	}
}

function response(result, action){
	if(result){
		window.parent.closeDialog(action);
	}else{
		alert("Error adding data.");
	}
}

function getData(){
	var comp_id = $("#comp_id").val();
	xajax_getData(comp_id);
}

function setData(details){
	$("#full_name").val(details['comp_full_name']);
	$("#short_name").val(details['comp_name']);
	$("#address").html(details['comp_add']);
	$("#e_add").val(details['comp_email_add']);
	$("#contact").val(details['comp_phone_nr']);
	$("#pres").val(details['pres']);
	$("#hr").val(details['hr']);
	$("#type").val(details['type']);
}	