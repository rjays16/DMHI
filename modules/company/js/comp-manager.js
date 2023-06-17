$(document).ready(function(){
	searchComp();
});


function searchComp(){
	$('#rowData').empty();

	var details = new Object();
	
	details = {
		keyword:$('#keyword').val(),
		type: $('#type').val()
	};

	xajax_searchCompany(details);
}

function appendComp(details){
	var tr = '<tr><td>'+details.name+'</td><td>'+
						details.add+'</td><td>'+
						details.phone_nr+'</td><td>'+
						'<img style="cursor:pointer" src="../../images/cashier_edit.gif" onclick="addComp('+details.id+')"></td></tr>';
	$('#rowData').append(tr);
}

function addComp(comp_id){
	var pageLabRequest = '../../modules/company/add-company.php?comp_id='+comp_id;
 
    var dialogLabRequest = $('<div id="form-dialog"></div>')
    .html('<iframe style="border: 0px; " src="' + pageLabRequest + '" width=100% height=340px></caiframe>')
    .dialog({
        autoOpen: true,
        modal: true,
        show: 'fade',
        hide: 'fade',
        height: 'auto',
        width: '40%',
        title: 'Add Company/Employee Information',
        position: 'top',
        buttons: {
        	"Close": function(){
                $(this).dialog("close");
            }
         }
    });
}

function closeDialog(action){
	alert("Succesfully "+action+" data.");
	$("#form-dialog").dialog("close");
	location.reload();
}