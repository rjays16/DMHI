var ViewMode = false;
var array_of_servcode = new Array(); /*added by mai 07-18-2014*/
var totalDiscount = 0;
var HSM = "HOSPITAL SPONSORED MEMBER";
var NBB = "SPONSORED MEMBER";

function isCash() {
	return $('iscash1').checked;
}

function parseFloatEx(x) {
	var str = x.toString().replace(/\,|\s/,'')
	return parseFloat(str)
}

function warnClear() {
	var items = document.getElementsByName('items[]');
	if (items.length == 0) return true;
	else return confirm('Performing this action will change the price in the order tray. Do you wish to continue?');
}

function formatNumber(num,dec) {
	var nf = new NumberFormat(num);
	if (isNaN(dec)) dec = nf.NO_ROUNDING;
	nf.setPlaces(dec);
	return nf.toFormatted();
}

function resetRefNo(newRefNo,error) {
	$("refno").style.color = error ? "#ff0000" : "";
	$("refno").value=newRefNo;
}

function clearEncounter() {
	var iscash = $("iscash1").checked;
	$('ordername').value="";
	$('ordername').readOnly=!iscash;
	$('orderaddress').value="";
	$('orderaddress').readOnly=!iscash;
	$('is_tpl').disabled = !iscash;
	$('pid').value="";
	$('encounter_nr').value="";
	$('clear-enc').disabled = true;
	$('clear-enc').disabled = true;
	$('sw-class').innerHTML = 'None';
	$('encounter_type_show').innerHTML = 'WALK-IN';
	$('encounter_type').value = '';
	$('ordername').focus();

    $('warningcaption').innerHTML = '';
    $('issc').disabled = false;
    $('issc').checked = false;
	//updatePHICCoverage(['']);
	if (!iscash) {
		updateCoverage(['']);
	}
	//clearCharityDiscounts();
}

function updateCoverage( param ) {
	if (!param[0]) {
		$('cov_type').update('');
		$('cov_amount').update('');
		$('coverage').setAttribute('value',-1);
		return false;
	}

	var ctype = $('charge_type').value;
	param.push(ctype);

	if (ctype=='PERSONAL') {
		$('cov_type').update('');
		$('cov_amount').update('');
		$('coverage').setAttribute('value',-1);
	}else if(ctype=="company"){ //added by mai 07-18-2014
		$('cov_type').show();
		$('cov_amount').show();
		$('coverage').setAttribute('value',-1);
		$('phic_ajax').hide();
	}else {
		$('cov_type').hide();
		$('cov_amount').hide();
		$('phic_ajax').show();

		$('cov_type').update(ctype + ' Coverage:');
		xajax.call('updateCoverage', {
			parameters : param,
			onError: function(transport) {
				$('phic_ajax').hide();
				$('cov_type').show();
				$('cov_amount').show();
			},
			onSuccess : function(transport) {
				$('phic_ajax').hide();
				$('cov_type').show();
				$('cov_amount').show();
			}
		});

	}
}

function updatePHICCoverage( param ) {
	$('phic_cov').hide();
	$('phic_ajax').show();
	xajax.call('updatePHICCoverage', {
		parameters : param,
		onError: function(transport) {
			$('phic_ajax').hide();
			$('phic_cov').show();
		},
		onSuccess : function(transport) {
			$('phic_ajax').hide();
			$('phic_cov').show();
		}
	});
}

function pSearchClose() {
	var nr = $('encounter_nr').value;
	//updatePHICCoverage([nr]);
	if (!$("iscash1").checked) {
    	$('issc').disabled = true;
    	$('issc').checked = false;
        updateCoverage([nr]);
    }  
	cClick();
}

function autoSuggestWalkin(element) {
	if ($("iscash1").checked && !element.readOnly) {
		$('orderaddress').readOnly = false;
		if (!$F('orderaddress')) $('orderaddress').value = "NOT PROVIDED";
		var script = "ajax/walkin.php";
		var options = {
			delay: 5,
			timeout: 2000,
			script: function (input) { return ( script + '?s='+input); },
			callback: function (obj) {
				//$('xml_info').update('you have selected: '+obj.id + ' ' + obj.value + ' (' + obj.info + ')');
				$('ordername').readOnly = true;
				$('orderaddress').readOnly = true;
				$('ordername').value = obj.value;
				$('orderaddress').value = obj.info;
				$('pid').value = 'W'+obj.id;
				$('clear-enc').disabled = false;
			}
		};
		var xml=new AutoComplete(element.id,options);
		return true;
	}
	else {
		$('orderaddress').readOnly = true;
		return false;
	}
}

function emptyTray() {
	clearOrder($('order-list'));
	appendOrder($('order-list'),null);
	refreshDiscount();
}

function reclassRows(list,startIndex) {
	if (list) {
		var dBody=list.getElementsByTagName("tbody")[0];
		if (dBody) {
			var dRows = dBody.getElementsByTagName("tr");
			if (dRows) {
				for (i=startIndex;i<dRows.length;i++) {
					dRows[i].className = "wardlistrow"+(i%2+1);
				}
			}
		}
	}
}

/*
function clearCharityDiscounts() {
	var cNodes = document.getElementsByName("charity[]");
	if (cNodes) {
		for (var i=cNodes.length-1;i>=0;i--) {
			cNodes[i].parentNode.removeChild(cNodes[i]);
		}
	}
}

function addCharityDiscount(discountid, discount) {
	var dsc = document.createElement("INPUT");
	dsc.setAttribute("type","text");
	dsc.setAttribute("id","ch"+discountid);
	dsc.setAttribute("name","charity[]");
	dsc.setAttribute("discount",discount);
	dsc.setAttribute("value",discountid);
	$("orderForm").appendChild(dsc);
}
*/

function clearOrder(list) {
	if (!list) list = $('order-list')
	if (list) {
		var dBody=list.getElementsByTagName("tbody")[0]
		if (dBody) {
			trayItems = 0
			dBody.innerHTML = ""
			return true
		}
	}
	return false
}

function appendOrder(list, details, disabled, existingOrder) { //added by mai 07/28/2014, checker if item is already existing
	existingOrder = (existingOrder === 'undefined') ? false : existingOrder; //added by mai 07/28/2014
	if (!list) list = $('order-list');

	if (list) {
		var dBody=list.getElementsByTagName("tbody")[0];
		if (dBody) {
			var discount = parseFloatEx($("discount").value);
			var isCash = $("iscash1").checked;
			var isSC = $("issc").checked;  // Senior Citizen checking
			var totalCash, totalCharge;
			var src;
			var lastRowNum = null,
					items = document.getElementsByName('items[]');
					dRows = dBody.getElementsByTagName("tr");
			if (details) {
				var id = details.id,
					qty = parseFloatEx(details.qty),
					//allowedQty = parseFloatEx(details.allowedQty),
					prcCash = parseFloatEx(details.prcCash),
					prcCharge = parseFloatEx(details.prcCharge),
					prcCashSC = parseFloatEx(details.prcCashSC),
					prcChargeSC = parseFloatEx(details.prcChargeSC),
					totalCash, totalCharge;
				netPrice = isCash ? prcCash : prcCharge;
				orig = netPrice;
				if (isSC)	{
					seniorPrice = parseFloatEx(isCash ? details.prcCashSC : prcChargeSC);
					if (seniorPrice > 0)
						netPrice = seniorPrice
				}

				// Check if item is socialized and discount is of effect
				if (parseInt(details.isSocialized)==1 && isCash) {
					//if (discount==1.0)
					//	netPrice=0;
					//else {
						if (parseFloatEx(details.prcDiscounted) < netPrice) {
							netPrice = parseFloatEx(details.prcDiscounted)
							if (netPrice == 0) netPrice = orig;
						}
					//}
				}
				if (details.forcePrice) netPrice = details.forcePrice;
				tot = netPrice*qty;

				var coverageLimit = parseFloatEx($('coverage').value);

				// Check coverage limit
				if (coverageLimit >= 0) {
					if (coverageLimit < tot) {
						//alert("Coverage limit exceeded for this item...");
						//return true;
						alert('you will exceed you coverage limit');
					}
				}

				orig = isNaN(orig) ? '<span style="margin-right:5px">-</span>' : formatNumber(orig,2);
				if (items) {
					if ($('rowID'+id)) {
//                        alert('Item Already in Tray');
//                        return false;
						var itemRow = $('row'+id),
								itemQty = $('rowQty'+id)

                        // if(parseFloatEx(itemQty.value) + parseFloatEx(details.qty) > allowedQty) {
                        //     itemQty.value = allowedQty;
                        // } else {
						itemQty.value = parseFloatEx(itemQty.value) + parseFloatEx(details.qty)
                       // }

						itemQty.setAttribute('prevValue',itemQty.value)
						qty = parseFloatEx(itemQty.value)
						tot = netPrice*qty
						$('rowPrcCashSC'+id).value		= prcCashSC
						$('rowPrcChargeSC'+id).value	= prcChargeSC
						$('rowPrcCash'+id).value			= details.prcCash
						$('rowPrcCharge'+id).value		= details.prcCharge
						$('rowPrc'+id).setAttribute("prevValue",orig)
						//$('qty'+id).innerHTML 				= isNaN(qty) ? '<span style="margin-right:5px">-</span>' : 'x'+formatNumber(qty,null)
						$('rowPrc'+id).value 					= isNaN(netPrice) ? '<span style="margin-right:5px">-</span>' : formatNumber(netPrice,2)
						$('tot'+id).innerHTML 				= isNaN(tot) ? '<span style="margin-right:5px">-</span>' : formatNumber(tot,2)
						return true
					}
					if (items.length == 0) clearOrder(list)
				}

				alt = (dRows.length%2) ? 'class="alt"' : '';
				qty = isNaN(qty) ? '<span style="margin-right:5px">-</span>;' : 'x'+formatNumber(qty,null)
				prc = isNaN(netPrice) ? '<span style="margin-right:5px">-</span>' : formatNumber(netPrice,2)
				tot = isNaN(tot) ? '<span style="margin-right:5px">-</span>' : formatNumber(tot,2)

				var disabledAttrib = disabled ? 'disabled="disabled"' : ""

				src =
					'<tr '+alt+' id="row'+id+'" style="height:26px">' +
						'<input type="hidden" name="soc[]" id="rowSoc'+id+'" value="'+details.isSocialized+'" />'+
						'<input type="hidden" name="pdisc[]" id="rowPrcDiscounted'+id+'" value="'+details.prcDiscounted+'" />'+
						'<input type="hidden" name="pcashsc[]" id="rowPrcCashSC'+id+'" value="'+prcCashSC+'" />'+
						'<input type="hidden" name="pchargesc[]" id="rowPrcChargeSC'+id+'" value="'+prcChargeSC+'" />'+
						'<input type="hidden" name="pcash[]" id="rowPrcCash'+id+'" value="'+details.prcCash+'" />'+
						'<input type="hidden" name="pcharge[]" id="rowPrcCharge'+id+'" value="'+details.prcCharge+'" />'+
						'<input type="hidden" name="items[]" id="rowID'+id+'" value="'+id+'" />';

				if (disabled)
					src+='<td></td>'
				else
					src+='<td class="centerAlign"><img class="segSimulatedLink" src="../../images/cashier_delete_small.gif" border="0" onclick="removeItem(\''+id+'\')"/></td>'

				src+=
					'<td class="centerAlign">'+id+'</td>'+
					'<td><span style="color:#660000">'+details.name+'</span></td>'+
					'<td class="centerAlign"><input type="checkbox" name="consigned[]" value="'+id+'" '+(parseInt(details.isConsigned)==1 ? 'checked="checked"' : '')+' '+(disabled ? 'disabled="disabled"' : '')+'></td>'+
					'<td class="centerAlign" nowrap="nowrap" id="qty'+id+'">'+
						//'<input type="text" class="segInput" name="qty[]" id="rowQty'+id+'" itemID="'+id+'" value="'+details.qty+'" prevValue="'+details.qty+'" style="width:80%;text-align:right"'+(disabled ? ' disabled="disabled"' : '')+' onfocus="this.value=this.getAttribute(\'prevValue\')" onchange="adjustQty(this)"/>'+
						'<input type="text" class="segInput" name="qty[]" id="rowQty'+id+'" itemID="'+id+'" value="'+details.qty+'" prevValue="'+details.qty+'" style="width:80%;text-align:center"'+(disabled ? ' disabled="disabled"' : '')+' onblur="adjustQty(this);" onkeyup="editQuantity(\''+id+'\')"/>'+
					'</td>'+
					'<td class="rightAlign" id="prc'+id+'">'+orig+'</td>'+
					'<td class="rightAlign">'

				if	(disabled || (parseFloatEx(details.prcDiscounted)>0 && (!isSC || (isSC && parseFloatEx(seniorPrice)>0))))
					src+= '<input type="text" class="segClearInput" name="prc[]" id="rowPrc'+id+'" value="'+prc+'" style="width:95%;text-align:right" itemID="'+id+'" prevValue="'+netPrice+'" readonly="readonly"/>'
				else
					src+= '<input type="text" class="segInput" name="prc[]" id="rowPrc'+id+'" value="'+prc+'" style="width:95%;text-align:right" itemID="'+id+'" prevValue="'+netPrice+'" onfocus="this.value=this.getAttribute(\'prevValue\')" onblur="adjustPrice(this)"/>'

				src+=	'</td>'+
					'<td class="rightAlign" id="tot'+id+'">'+tot+'</td>'+
				'</tr>';
				trayItems++;

				var totalAmount = parseFloat($('show-net-total').innerHTML.replace(",",""))+parseFloat(tot.replace(",",""));

				if(!existingOrder){
					if(!checkExceedLimit(totalAmount, id)){
						src="";
					}
				}
			}
			else {
				src = "<tr style=\"height:26px\"><td colspan=\"8\">Order list is currently empty...</td></tr>";
			}
			dBody.innerHTML += src;
			return true;
		}
	}
	alert('Failed to Add Item');
return false;
}

function removeItem(id) {
	var destTable, destRows;
	var table = $('order-list');
	var rmvRow=document.getElementById("row"+id);
	if (table && rmvRow) {
		var rndx = rmvRow.rowIndex-1;
		table.deleteRow(rmvRow.rowIndex);
		if (!document.getElementsByName("items[]") || document.getElementsByName("items[]").length <= 0)
			appendOrder(table, null);
		reclassRows(table,rndx);
	}
	refreshTotal();
}

function seniorCitizen() {
	var iscash = $("iscash1").checked
	var issc = $("issc").checked
	var discount = parseFloatEx($("discount").value)
	var pdisc = document.getElementsByName('pdisc[]')
	var soc = document.getElementsByName('soc[]')
	var items = document.getElementsByName('items[]')
	var cash = document.getElementsByName('pcash[]')
	var charge = document.getElementsByName('pcharge[]')
	var cashsc = document.getElementsByName('pcashsc[]')
	var chargesc = document.getElementsByName('pchargesc[]')
	var prc = document.getElementsByName('prc[]')
	var isCash = $("iscash1").checked
	var newPrice, discountPrice, seniorPrice, cashPrice, chargePrice,
			cashSc, chargeSc

	for (var i=0;i<items.length;i++) {
		priceCash = parseFloatEx(cash[i].value)
		priceCharge = parseFloatEx(charge[i].value)
		newPrice = iscash ?  priceCash : priceCharge
		discountPrice = newPrice
		if (parseInt(soc[i].value)==1 && iscash) {
			//if (discount==1.0)	newPrice = 0
			//else {
				discountPrice = parseFloatEx(pdisc[i].value)
				if (discountPrice > 0) newPrice = discountPrice
			//}
		}

		seniorPrice = 1.0
		if (issc) {
			cashSc = parseFloatEx(cashsc[i].value)
			chargeSc = parseFloatEx(chargesc[i].value)
			seniorPrice = Math.min(newPrice, iscash ? cashSc : chargeSc)
			if (seniorPrice > 0) newPrice = seniorPrice
		}

		// disabled flag
		disabledFlag = false
		//alert('issc:'+issc+'\ndsc:'+discountPrice+'\nsprc:'+seniorPrice)
		if (disabledFlag || (discountPrice >0 && (!issc || (issc && seniorPrice>0)))) {
			prc[i].className = "segClearInput"
			prc[i].value = formatNumber(newPrice,2)
			prc[i].readOnly = true
			prc[i].setAttribute("prevValue", newPrice)
			prc[i].setAttribute("onfocus", "")
			prc[i].setAttribute("onblur", "")
		}
		else {
			prc[i].className = "segInput"
			prc[i].readOnly = false
			prc[i].value = formatNumber(newPrice,2)
			prc[i].setAttribute("prevValue", newPrice)
			prc[i].setAttribute("onfocus", "this.value=this.getAttribute(\'prevValue\')")
			prc[i].setAttribute("onblur", "adjustPrice(this)")
		}
	}
	refreshDiscount()
}

function changeChargeType() {
	$('charge_type').style.display = $("iscash1").checked ? 'none' : '';
	updateCoverage([$('encounter_nr').value]);
	refreshDiscount();
}

function changeTransactionType() {
	var isCash = $("iscash1").checked;
	var phic = $('phic_nr').innerHTML;


	//clearEncounter();
	if (!isCash) {
		if (!$('encounter_nr').value) {
			alert("Charging is only allowed for current hospital patients...");
			$("iscash1").checked = true;
			isCash = true;
			$('issc').disabled = false;
		}
	}

	if($("iscash0").checked){
		$('issc').disabled = true;
		$('issc').checked = false;
	}
	else
		$('issc').disabled = false;

    var mgh = $('is_maygohome').value;
    var bill_nr = $('bill_nr').value;
    var warning = $('warningcaption').innerHTML;

    if ((mgh==1) && (bill_nr!='') &&(!isCash)){
        //mgh or have save billing
        alert('Charging is NOT allowed to this patient. '+warning);
        $("iscash1").checked = true;
        isCash = true;
    }

	$('charge_type').style.display = $("iscash1").checked ? 'none' : '';
	if ($('encounter_nr').value && !$("iscash1").checked) {
		if ($('phic_nr').innerHTML == "None") {
			updateCoverage([$('encounter_nr').value])
			refreshDiscount();
		}else{
			$('charge_type').value = 'PHIC';
			updateCoverage([$('encounter_nr').value])
			refreshDiscount();
		}
	}else{
		$('charge_type').value = 'PERSONAL';
		/*added by mai 07-18-2014*/
		$('cov_type').update('');
		$('cov_amount').update('');
		$('cov_type').hide();
		$('cov_amount').hide();
		/*end added by mai*/
		
			updateCoverage([$('encounter_nr').value])
			refreshDiscount();
	}
}

function adjustPrice(obj) {
	var id = obj.getAttribute("itemID");
	if (isNaN(obj.value)) {
		obj.value = formatNumber(obj.getAttribute("prevValue"),2);
		return false;
	}

	if (parseFloatEx(obj.value) != parseFloatEx(obj.getAttribute("prevValue"))) {
		$('tot'+id).innerHTML = formatNumber(obj.value*parseFloatEx($('rowQty'+id).value),2);
		refreshDiscount();
	}
	obj.setAttribute("prevValue",parseFloatEx(obj.value));
	obj.value = formatNumber(obj.value,2);
	return true;
}

//Added by Jarel 04/11/2013
function editQuantity(id) {
    var qtyInput = $('rowQty' + id);

    //prevent item request to exceed from available stock
    var allowedQty = qtyInput.getAttribute("data-qty");
    if (parseFloatEx(obj.value) > allowedQty) {
        obj.value = allowedQty;
    }
   
    var newqty = parseFloat(qtyInput.value);
   if (!isNaN(newqty) && newqty!=null){
        document.getElementById("rowQty"+id).setAttribute("value",newqty);
   }else{
        document.getElementById("rowQty"+id).setAttribute("value",0);    
   }

   refreshDiscount();
}

function adjustQty(obj) {

	var id = obj.getAttribute("itemID");
	if (isNaN(obj.value)) {
		obj.value = obj.getAttribute("prevValue");
		return false;
	}

    //prevent item request to exceed from available stock
//    var allowedQty = obj.getAttribute("data-qty");
//    if(parseFloatEx(obj.value) > allowedQty) {
//        obj.value = allowedQty;
//    }

	if (parseFloatEx(obj.value) != parseFloatEx(obj.getAttribute("prevValue"))) {
		$('tot'+id).innerHTML = formatNumber(parseFloatEx($('rowPrc'+id).value)*parseFloatEx($('rowQty'+id).value),2);
		refreshDiscount();

		/*added by mai 07/28/2014*/
		var totalAmount = parseFloat($('show-net-total').innerHTML.replace(",",""));
		if(!checkExceedLimit(totalAmount, id)){
			obj.value = obj.getAttribute("prevValue");
			refreshDiscount();
			return false;
		}
		/*end added by mai*/
	}

	obj.setAttribute("prevValue",parseFloatEx(obj.value));

	//obj.value = formatNumber(obj.value,2);
	return true;
}

function refreshDiscount() {
	var nodes;
	var nr = $('encounter_nr').value;
	if (nr)
		nodes = document.getElementsByName("charity[]");
	else
		nodes=document.getElementsByName("discount[]");
	totalDiscount = 0;
	if (nodes) {
		for (var i=0;i<nodes.length;i++) {
			if (nodes[i].value) totalDiscount += parseFloatEx(nodes[i].getAttribute('discount'));
		}
	}
	var dItem = $("show-discount");
	if (dItem) {
		dItem.value = parseFloatEx(totalDiscount * 100).toFixed(2);
	}
	refreshTotal();
}

function refreshTotal() {
	var items = document.getElementsByName('items[]');
	var cash = document.getElementsByName('pcash[]');
	var charge = document.getElementsByName('pcharge[]');
	var qty = document.getElementsByName('qty[]');
	var prc = document.getElementsByName('prc[]');
	var isCash = $("iscash1").checked;
	var total = 0.0, orig = 0.0;
	var id
	for (var i=0;i<items.length;i++) {
		id = items[i].value
		orig+=parseFloatEx(isCash ? cash[i].value : charge[i].value)*parseFloatEx(qty[i].value);
		val = parseFloatEx(prc[i].value)*parseFloatEx(qty[i].value)
		total+=val;
		$('tot'+id).innerHTML = formatNumber(val,2)
	}

	var subTotal = $("show-sub-total");
	var discountTotal = $("show-discount-total");
	var netTotal = $("show-net-total");

	subTotal.innerHTML = formatNumber(orig.toFixed(2),2);
	disc = total-orig;
	if (disc <= 0) {
		discountTotal.style.color = "#006600";
		discountTotal.innerHTML = "("+formatNumber(Math.abs(disc),2)+")";
	}
	else {
		discountTotal.style.color = "red";
		discountTotal.innerHTML = formatNumber(Math.abs(disc),2);
	}
	netTotal.innerHTML = formatNumber(total.toFixed(2),2);

	if ($('coverage').value!=-1 && !$("iscash1").checked) {
		var coverage=parseFloatEx($('coverage').value);
		if($('mem_category').innerHTML == HSM){
			$('cov_amount').update('HSM');
		}else if ($('mem_category').innerHTML == NBB){
			$('cov_amount').update('NBB');
		} else{
			$('cov_amount').update(formatNumber(coverage-total,2));
		}
		
	}
if($('charge_type').value=="company"){ /*added by mai 07-15-2014*/
    	xajax_getChargeCompanyBalance($('encounter_nr').value, 'PHA', $('refno').value); //getBalance
    }
}
/*added by mai 07-15-2014*/
function preset(){
	if($('billing').value && $('encounter_type_show').innerHTML == "INPATIENT (ER)"){

		$('iscash0').checked = true;
		$('iscash1').checked = false;
		
		changeTransactionType();
}

	if($('comp_id').value){
		
		var grantSelect = $('charge_type');
		var grantOption = document.createElement('option');
		grantOption.value = "company";
		grantOption.innerHTML = "COMPANY";
		grantSelect.appendChild(grantOption);
		
		grantSelect.value="company";

		$('iscash0').checked = true;
		$('iscash1').checked = false;
		
		changeTransactionType();
	}
}
function checkExceedLimit(totalAmount, id){
	if($('charge_type').value=="company"){
		if($('cov_amount').innerHTML!="NO LIMIT"){
			xajax_getChargeCompanyBalance($('encounter_nr').value, 'PHA', $('refno').value);
			var compBalance = parseFloat($('charge_comp_balance').value);
					
			if(!array_of_servcode.in_array(id)){
				if(totalAmount > compBalance){
					var ans = confirm("You already exceeded the limit for this item. Are you sure you want to add this item?");
					if(!ans){
						return false;
					}
				}
			}
		}
	}
	return true;
}
function chargeToCompany(){
	var balance = $('charge_comp_balance').value;
	if(balance != "NO LIMIT"){
		var net_total = parseFloat($('show-net-total').innerHTML.replace(",","")); //net total
		var	remaining_charged_company = parseFloat((balance - net_total));
		$('cov_amount').update(formatNumber(remaining_charged_company,2));
	}else{
		$('cov_amount').update('NO LIMIT');
	}
	$('cov_type').update("Company Coverage: ");
}
function IfPhicExceed(){
	var enc = $('encounter_nr').value;
	var pid = $('pid').value;
	if($('PhicExceed').checked){
		xajax.call('updatePhicExceed', {
			parameters : [enc, 1, pid]
			
		});
		$('ExceedPhic').innerHTML = "Already Exceed Phic coverage";
	}else{
		xajax.call('updatePhicExceed', {
			parameters : [enc, 2, pid]
			
		});
		$('ExceedPhic').innerHTML = "";
	}
}
Array.prototype.forEach = function() {
	var total = 0;
    for(var i = 0, l = this.length; i < l; i++) {
        total += parseFloat(this[i]);
    }
    return total;
}
Array.prototype.in_array = function(p_val) {
    for(var i = 0, l = this.length; i < l; i++) {
        if(this[i] == p_val) {
            return true;
        }
    }
    return false;
}
//added by VAN 03-13-2013
//fixed for bug id 110
//Commented By Jarel Mandated by Dr. Vega for HSM changes
/*function validatePHIC(){

    if (!$("iscash1").checked) {
        if($J('#charge_type').val()=="PHIC") {
            var phic_nr = $J('#phic_nr').html();
            phic_nr = phic_nr.replace(/-/g,'');

            //if phic is temporarary or not the right format of phic number which is PHIC nr has a 16 digit format
            //if ((phic_nr.toLowerCase().match('temp')=='temp') || (phic_nr.length!=12)){
            if (phic_nr.toLowerCase().match('temp')=='temp'){
                return false;
            }else
                return true;
        }else{
            return true;
        }
    }else{
        return true;
    }
}*/

//added by janken 1/21/2015 for changing the price
function changePrice(){
	var items = document.getElementsByName('items[]');
	var encounter_nr = $('encounter_nr').value;
	var refno =  $('refno').value;
	var trans;

	if(refno){
		for (var i=0;i<items.length;i++) {
			id = items[i].value;

			if(!$("iscash1").checked){
				trans = 'charge';
			}
			else{
				trans = 'cash';
			}

			xajax_changePrice(id, refno, trans);
		}
	}
}

function changePrices(value, id){
	$('rowPrcCash'+id).value = formatNumber(value,2);
	$('rowPrcCharge'+id).value = formatNumber(value,2);
	$('prc'+id).innerHTML = formatNumber(value,2);
	$('rowPrc'+id).value = formatNumber(value,2);

	refreshTotal();

}
