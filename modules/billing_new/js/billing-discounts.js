function init(e){
	YAHOO.util.Event.addListener("btnAdd", "click", add_Discount); 

	shortcut.add("Ctrl+A", function(){ add_Discount(); }, {
			'type':'keypress',
			'propagate':false});
	shortcut.add("ESC", function(){ cClick(); });		
}//end function init

function initDiscountDialogBox() {
	// Define various event handlers for Dialog
	var handleSubmit = function() {
		this.submit();
	};
	var handleCancel = function() {
		this.cancel();
	};

	// Instantiate the Dialog
	YAHOO.discount.container.discountInfoBox = new YAHOO.widget.Dialog("discountInfoBox", 
																			{ width : "410px",
																			  height: "auto",
																			  fixedcenter : true,
																			  visible : false,
																			  constraintoviewport : true,
																			  buttons : [ { text:"Save", handler:handleSubmit, isDefault:true },
																						  { text:"Cancel", handler:handleCancel } ]
																			 } );
	
	YAHOO.discount.container.discountInfoBox.validate = function() {
		var data  = this.getData();
		
		if ((data.discount_list == '-') || (data.discount_list == '')) {
			alert('Please indicate the discount to apply!');
			return false;
		}

		// else if (data.remarks == '') {
		// 	alert("It is strongly recommended that you indicate accompanying\nremarks for applying this discount!");
		// 	return false;
		// }
		//added by daryl
		// else if (data.discount != '') {
  //           if ( (Number(data.discount) < 0) || (Number(data.discount) > 1) ) {
  //   			alert("You have to indicate a valid discount in decimal.\n(Greater than 0 and less than or equal to 1)");            
  //               return false;
  //           }
		// }

		// if (data.discount_list != "SC"){
		// 	if ((data.Hospdiscount == '') && (data.Profdiscount == '')) {
		// 	alert("You have to indicate both or either Hospital and Professional Discount");
		// 	return false;
		// 	}
		// }
		// data.discountamnt = parseFloat(data.discountamnt.replace(',',''));

		//added by janken 11/05/2014 to clear the saved coverages in hci side when adding SC in last part
		if(data.discount_list == 'SC'){
			var confirm_msg = confirm("All the HCI coverage/s that you have set will be delete. Do you want to proceed?");
			if(confirm_msg == true)
				xajax_deleteHCICoverage(data);
			else
				return false;
		}

		data.Hospdiscount = parseFloat(data.Hospdiscount.replace(',',''));
		data.Profdiscount = parseFloat(data.Profdiscount.replace(',',''));
        		
        xajax_SaveAppliedDiscount(data, data.bill_dte);
        window.parent.js_RecalcDiscount();
        return true;
	};	
}

function showDialogBox(discountid) {
	// alert(discountid);
	// Enforce visibility of select tags in html ...
	$('discount_list').style.visibility = "";
//added by daryl
	if (discountid=="SC"){
				$('discountlbl').style.display = '';
				$('discount').style.display = '';
				$('Hospdiscount').style.display = 'none';
				$('Profdiscount').style.display = 'none';
				$('Hospdiscountlbl').style.display = 'none';
				$('Profdiscountlbl').style.display = 'none';
		}else if (discountid == '-'){
				$('discountlbl').style.display = 'none';
				$('discount').style.display = 'none';
				$('Hospdiscount').style.display = 'none';
				$('Profdiscount').style.display = 'none';
				$('Hospdiscountlbl').style.display = 'none';
				$('Profdiscountlbl').style.display = 'none';
		}else{
				$('discount').value = '';
				$('discountlbl').style.display = 'none';
				$('discount').style.display = 'none';
				$('Hospdiscount').style.display = '';
				$('Profdiscount').style.display = '';
				$('Hospdiscountlbl').style.display = '';
				$('Profdiscountlbl').style.display = '';
		}
		

	YAHOO.discount.container.discountInfoBox.render();
	YAHOO.discount.container.discountInfoBox.show();
	
	discountid = discountid || '';
	
	xajax_fillDiscountsCbo(discountid);
}

function add_Discount() {
	$('entry_no').value = '0';	
	showDialogBox();	
	$('discount').value = '';	
	$('remarks').value = '';
	$('areas_id').value = '';
	$('areas_desc').value = '';		
}

function editDiscount(enc_nr, entry_no, amount, id) {
	//edited by daryl
	// if(amount)
	// 	$('discountamnt').value = amount;
	// else
	// 	$('discountamnt').value ='';
	$('entry_no').value = entry_no;	
	showDialogBox(id);		
	xajax_getDiscountInfo(enc_nr, entry_no);
}

function deleteDiscount(enc_nr, entry_no) {
	xajax_deleteDiscount(enc_nr, entry_no);
	window.parent.js_RecalcDiscount();
}

function clearOtherField(obj, elemnm, elemn2, elemn3, elemn4) {
    if(elemn4){
	    if ( (obj.value != '') && (Number(obj.value) != 0) ) {
	        $(elemnm).value = '';
	         $(elemn2).value = '';
	          $(elemn3).value = '';
	    }
	}else{
		$(elemnm).value = '';
	   	$(elemn2).value = '';
	}
}
//edited by daryl
function js_showDiscountInfo(id, desc, remarks, areas_id, areas_desc, discount,hcidiscount,pfdiscount) {
	$('Hospdiscount').value = hcidiscount;
	$('Profdiscount').value = pfdiscount;
	$('discount_id').value = id;
	$('discount_desc').value = desc;
	$('areas_id').value = areas_id;
	$('areas_desc').value = areas_desc;			
	$('remarks').value = remarks;	
	$('discount').value = formatNumber(Number(discount), 4);	
	
}

function showProgressBar() {
	$('progress_indicator').style.display = '';	
}

function hideProgressBar() {
	$('progress_indicator').style.display = 'none';
}

function js_getApplicableDiscounts() {
	var enc = window.parent.document.getElementById('encounter_nr').value;
	var frm_dte = window.parent.document.getElementById('bill_frmdte').value;
	var bill_dt = window.parent.document.getElementById('billdate').value;	
	
	$('enc_nr').value = enc;
	$('bill_dte').value = bill_dt;
	
	xajax_getApplicableDiscounts(enc, frm_dte, bill_dt);
	
	hideProgressBar();
	
	$('discounts_tbl').style.display = '';
	$('footer').style.display = '';
}

function addApplicableDiscount(enc_nr, no, id, description, areas_id, areas_desc, remarks, rate, amount, hosp, pf) {
	var srcRow;	
	var root_path = $('root_path').value;
	
	if ((no) && (enc_nr)) {
		if (areas_desc == '') areas_desc = 'NONE';
		srcRow = '<tr>'+
					'<td width="27%">'+description+'</td>'+
					'<td align="center" width="12%">'+
					'    <input type="hidden" name="billareas_id_'+no+'_'+id+'" id="billareas_id_'+no+'_'+id+'" value="'+areas_id+'">'+
					'        <div id="billareas_desc_'+no+'_'+id+'" style="display:none">'+areas_desc+'</div>'+
					'        <a id="billareas_label_'+no+'_'+id+'" name="billareas_label_'+no+'_'+id+'" href="javascript:void(0);" onmouseover="return overlib($(\'billareas_desc_'+no+'_'+id+'\').innerHTML, LEFT);" onmouseout="return nd();">View Areas</a>'+
					'</td>'+
					'<td width="30%">'+remarks+'</td>'+
                    '<td width="14%" align="right">'+formatNumber(Number(rate) * 100, 3)+'</td>'+					
                    '<td width="14%" align="right">'+formatNumber(Number(amount), 2)+'</td>'+
                    '<td width="14%" align="right">'+formatNumber(Number(hosp), 2)+'</td>'+
                    '<td width="14%" align="right">'+formatNumber(Number(pf), 2)+'</td>'+
			        '<td width="*" align="right" nowrap="nowrap">';
					
		if (Number(no) == 0) 
			srcRow += '&nbsp;';
		else
			srcRow += '<a title="Edit" href="#"><img class="segSimulatedLink" src="'+root_path+'images/cashier_edit.gif" border="0" align="absmiddle" onclick="editDiscount(\''+enc_nr+'\', '+no+', '+amount+', \''+id+'\')"/></a>&nbsp;&nbsp;'+
						'<a title="Delete" href="#"><img class="segSimulatedLink" src="'+root_path+'images/cashier_delete.gif" border="0" align="absmiddle" onclick="if (confirm(\'Delete this discount?\')) deleteDiscount(\''+enc_nr+'\', '+no+')"/>'+
						'</a>';
		srcRow += '</td></tr>';
	}
	else {
		srcRow = '<tr>'+		
					'<td colspan="8" width="100%">No discount found!</td>'+
				 '</tr>';		
	}	
	$('discount_details').innerHTML += srcRow;
	
}

function jsDiscountOptionChange(obj, value) {
	var enc = window.parent.document.getElementById('encounter_nr').value;
	xajax_validateDC(value,enc);

	if (obj.id== 'discount_list') {
		$('discount_id').value  = value;	
		$('discount_desc').value = obj.options[obj.selectedIndex].text;
		
		if (value=="SC"){
			//added by daryl
				$('discountlbl').style.display = '';
				$('discount').style.display = '';
				// $('discountamnt').style.display = 'none';
				$('Hospdiscount').style.display = 'none';
				$('Profdiscount').style.display = 'none';
				// $('discountamntlbl').style.display = 'none';
				$('Hospdiscountlbl').style.display = 'none';
				$('Profdiscountlbl').style.display = 'none';
				$('remarks').style.display = '';
				$('areas_desc').style.display = '';

				xajax_getDiscount(value);

		}else if (value == '-'){
				$('discountlbl').style.display = 'none';
				$('discount').style.display = 'none';
				$('Hospdiscount').style.display = 'none';
				$('Profdiscount').style.display = 'none';
				$('Hospdiscountlbl').style.display = 'none';
				$('Profdiscountlbl').style.display = 'none';
				$('remarks').style.display = '';
				$('areas_desc').style.display = '';


		}else{
				$('discount').value = '';
				$('discountlbl').style.display = 'none';
				$('discount').style.display = 'none';
				$('Hospdiscount').style.display = '';
				$('Profdiscount').style.display = '';
				$('Hospdiscountlbl').style.display = '';
				$('Profdiscountlbl').style.display = '';
				$('remarks').style.display = '';
				$('areas_desc').style.display = '';
				

		}
		//ended by daryl
		xajax_getBillAreasApplied(value);		
	}
}

function js_showBillAreas(ids, descs) {
	$('areas_id').value = ids;
	$('areas_desc').value = descs;
	$('Hospdiscount').value = "";
	$('Profdiscount').value = "";
				
}

function js_showDiscount(discount) {
	$('discount').value = discount;	
}

function js_resetOption(){
		// $('discount_id').value  = "";	
		document.getElementById('discount_list').value = "-";
				$('discountlbl').style.display = 'none';
				$('discount').style.display = 'none';
				$('Hospdiscount').style.display = 'none';
				$('Profdiscount').style.display = 'none';
				$('Hospdiscountlbl').style.display = 'none';
				$('areas_desc').style.display = 'none';
				$('remarks').style.display = 'none';
				$('Profdiscountlbl').style.display = 'none';
}


function checkhci_amount(data){

	var hci_excess_ =	$('shci_excess').value;
	var hci_excess =	parseFloat(hci_excess_.replace(',',''));
	var hci_input_dc=	$('Hospdiscount').value;

if (hci_input_dc > hci_excess){
	alert("Cannot Enter Amount The Total Hospital Excess is P"+hci_excess_);
	$('Hospdiscount').value = hci_excess;
	$('Hospdiscount').focus();
}
}


function checkpf_amount(data){

	var pf_excess_ =	$('spf_excess').value;
	var pf_excess =	parseFloat(pf_excess_.replace(',',''));
	var pf_input_dc=	$('Profdiscount').value;

if (pf_input_dc > pf_excess){
	alert("Cannot Enter Amount The Total PF Excess is P"+pf_excess_);
	$('Profdiscount').value = pf_excess;
	$('Profdiscount').focus();
}

}