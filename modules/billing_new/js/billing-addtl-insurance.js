function init(e){
	YAHOO.util.Event.addListener("btnAdd", "click", add_Discount); 

	shortcut.add("Ctrl+A", function(){ add_Discount(); }, {
			'type':'keypress',
			'propagate':false});
	shortcut.add("ESC", function(){ cClick(); });		
}//end function init

function initInsuranceDialogBox() {
	// Define various event handlers for Dialog
	var handleSubmit = function() {
		this.submit();
	};
	var handleCancel = function() {
		this.cancel();
	};
														  														  	
	// Instantiate the Dialog
	YAHOO.discount.container.insuranceInfoBox = new YAHOO.widget.Dialog("insuranceInfoBox", 
																			{ width : "420px",
																			  fixedcenter : true,
																			  visible : false, 
																			  constraintoviewport : true,
																			  buttons : [ { text:"Save", handler:handleSubmit, isDefault:true },
																						  { text:"Cancel", handler:handleCancel } ]
																			 } );
	
	YAHOO.discount.container.insuranceInfoBox.validate = function() {
		var data  = this.getData();
		
		if ((data.insurance_list == '-') || (data.insurance_list == '')) {
			alert('Please indicate the insurance to apply!');
			return false;
		}
		data.accommodation = parseFloat(data.accommodation.replace(',',''));
		data.xlso = parseFloat(data.xlso.replace(',',''));
		data.meds = parseFloat(data.meds.replace(',',''));
		data.or = parseFloat(data.or.replace(',',''));
		data.misc = parseFloat(data.misc.replace(',',''));
		data.doctors = parseFloat(data.doctors.replace(',',''));
		data.accEx = parseFloat(window.parent.document.getElementById('accAP').innerHTML.replace(",",""));
		data.xloEx = parseFloat(window.parent.document.getElementById('hsAP').innerHTML.replace(",",""));
		data.medEx = parseFloat(window.parent.document.getElementById('medAP').innerHTML.replace(",",""));
		data.orEx = parseFloat(window.parent.document.getElementById('opsAP').innerHTML.replace(",",""));
		data.mscEx = parseFloat(window.parent.document.getElementById('mscAP').innerHTML.replace(",",""));
		data.pfEx = parseFloat(window.parent.document.getElementById('pfEX').innerHTML.replace(",",""));
		data.hfEx = parseFloat(window.parent.document.getElementById('hiEX').innerHTML.replace(",",""));

        xajax_SaveAppliedInsurance(data);
        // return true;
	};	
}

function showDialogBox(id) {
	// Enforce visibility of select tags in html ...
	$('insurance_list').style.visibility = "";
	var enc = window.parent.document.getElementById('encounter_nr').value;

	YAHOO.discount.container.insuranceInfoBox.render();
	YAHOO.discount.container.insuranceInfoBox.show();
	
	id = id || '';
	$('accommodation').value = '';
	$('xlso').value = '';
	$('meds').value = '';		
	$('or').value = '';
	$('misc').value = '';
	$('doctors').value = '';	
	xajax_fillInsuranceCbo(id, enc);
}

function add_Discount() {
	showDialogBox();			
}

function editInsurance(enc, id) {
	$('id').value = 'update';	
	showDialogBox(id);		
	xajax_getInsuranceInfo(enc, id);
}

function deleteInsurance(enc, id) {
	xajax_deleteInsurance(enc, id);
	window.parent.js_RecalcDiscount();
}

function clearOtherField(obj, elemnm) {
    if ( (obj.value != '') && (Number(obj.value) != 0) ) {
        $(elemnm).value = '';
    }
}

function js_showInsuranceInfo(id, desc, acc, xlso, meds, ops, misc, doc) {
	$('insurance_id').value = id;
	$('insurance_desc').value = desc;
	$('accommodation').value = formatNumber(Number(acc), 2);
	$('xlso').value = formatNumber(Number(xlso), 2);
	$('meds').value = formatNumber(Number(meds), 2);		
	$('or').value = formatNumber(Number(ops), 2);
	$('misc').value = formatNumber(Number(misc), 2);
	$('doctors').value = formatNumber(Number(doc), 2);	
}

function showProgressBar() {
	$('progress_indicator').style.display = '';	
}

function hideProgressBar() {
	$('progress_indicator').style.display = 'none';
}

function js_getApplicableInsurance() {
	var pid = window.parent.document.getElementById('pid').value;
	var enc = window.parent.document.getElementById('enc').value;
	
	$('pid').value = pid;
	$('enc').value = enc;
	
	xajax_getApplicableInsurance(enc);
	
	hideProgressBar();
	
	$('discounts_tbl').style.display = '';
	$('footer').style.display = '';
}

function clearFields(){
	$('accommodation').value = '';
	$('xlso').value = '';
	$('meds').value = '';		
	$('or').value = '';
	$('misc').value = '';
	$('doctors').value = '';
}

function focusId(id){
	setTimeout(function() {
      document.getElementById(id).focus();
    }, 0);
}

function addApplicableInsurance(id, desc, acc, xlso, meds, ops, misc, doc) {
	var srcRow;	
	var root_path = $('root_path').value;
	var pid = window.parent.document.getElementById('pid').value;
	var enc = window.parent.document.getElementById('enc').value;
	window.parent.populateBill();
	if (id) {
		srcRow = '<tr>'+
					'<td width="27%">'+
					'<input type="hidden" name="insurance_id_'+id+'" id="insurance_id_'+id+'" value="'+id+'">'+desc+'</td>'+
					'<td width="14%" align="center">'+formatNumber(Number(acc), 2)+'</td>'+	
					'<td width="14%" align="center">'+formatNumber(Number(xlso), 2)+'</td>'+	
					'<td width="14%" align="center">'+formatNumber(Number(meds), 2)+'</td>'+	
					'<td width="14%" align="center">'+formatNumber(Number(ops), 2)+'</td>'+
					'<td width="14%" align="center">'+formatNumber(Number(misc), 2)+'</td>'+					
                    '<td width="14%" align="center">'+formatNumber(Number(doc), 2)+'</td>'+
			        '<td width="*" align="right" nowrap="nowrap">';

		srcRow += '<a title="Edit" href="#"><img class="segSimulatedLink" src="'+root_path+'images/cashier_edit.gif" border="0" align="absmiddle" onclick="editInsurance(\''+enc+'\', \''+id+'\')"/></a>&nbsp;&nbsp;'+
					'<a title="Delete" href="#"><img class="segSimulatedLink" src="'+root_path+'images/cashier_delete.gif" border="0" align="absmiddle" onclick="if (confirm(\'Delete this insurance?\')) deleteInsurance(\''+enc+'\', '+id+')"/>'+
					'</a>';
		srcRow += '</td></tr>';
	}
	else {
		srcRow = '<tr>'+		
					'<td colspan="8" width="100%">No Insurance found!</td>'+
				 '</tr>';		
	}	
	$('insurance_details').innerHTML += srcRow;
	
}

function jsInsuranceOptionChange(obj, value) {	
	if (obj.id== 'insurance_list') {
		$('insurance_id').value  = value;	
		$('insurance_desc').value = obj.options[obj.selectedIndex].text;
		
	}
}
