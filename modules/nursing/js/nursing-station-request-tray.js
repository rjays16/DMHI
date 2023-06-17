var AJAXTimerID=0;
var lastSearch="";

/*
function prepareAddEx() {
	//alert(prepareAddEx);
	var prod = document.getElementsByName('prod[]');
	//var qty = document.getElementsByName('qty[]');
	var discount_name = document.getElementsByName('discount_name[]');
	var prcCash = document.getElementsByName('prcCash[]');
	var prcCharge = document.getElementsByName('prcCharge[]');
	var nm = document.getElementsByName('pname[]');
	
	var details = new Object();
	var list = window.opener.document.getElementById('order-list');
	var result=false;
	var msg = "";
	for (var i=0;i<prod.length;i++) {
		result = false;
		if (prod[i].checked) {
			details.id = prod[i].value;
			details.name = nm[i].value;
			details.discount_name = discount_name[i].value;
			details.prcCash = prcCash[i].value;
			details.prcCharge = prcCharge[i].value;
			result = window.opener.appendOrder(list,details);
			//msg += "     x" + discount_name[i].value + " " + nm[i].value + "\n";
			discount_name[i].value = 0;
			prod[i].checked = false;
		}
	}
	window.opener.refreshTotal();
	if (msg)
		//msg = "The following laboratory services were added to the request tray:\n" + msg;
		msg = "The following laboratory services were added to the request tray:\n";
	else
		msg = "An error has occurred! The selected laboratory services were not added...";	
	alert(msg);
}
*/

function startAJAXSearch(searchID) {
	var searchEL = $(searchID);
//	var aLabServ = $("parameterselect").value;
	//alert(aLabServ);
	if (searchEL && lastSearch != searchEL.value) {
		searchEL.style.color = "#0000ff";
		if (AJAXTimerID) clearTimeout(AJAXTimerID);
		$("ajax-loading").style.display = "";
		//AJAXTimerID = setTimeout("xajax_populateRequestList('"+searchID+"','"+searchEL.value+"')",200);
//		AJAXTimerID = setTimeout("xajax_populateRequestList('"+aLabServ+"','"+searchID+"','"+searchEL.value+"')",200);
		AJAXTimerID = setTimeout("xajax_populateRequestList('"+searchID+"','"+searchEL.value+"')",200);
		lastSearch = searchEL.value;
	}
}

function endAJAXSearch(searchID) {
	var searchEL = $(searchID);
	if (searchEL) {
		$("ajax-loading").style.display = "none";
		searchEL.style.color = "";
	}
}

function enableSearch(){
	//alert(enableSearch);
	var rowSrc, list;
	document.getElementById("search").value="";
	list = $('request-list');
	dBody=list.getElementsByTagName("tbody")[0];
	rowSrc = '<tr><td colspan="6" style="">No such laboratory service exists...</td></tr>';
	dBody.innerHTML = null;
	dBody.innerHTML += rowSrc;
	
	if (document.getElementById("parameterselect").value!="none"){
		document.getElementById("search").disabled = false;       //enable textbox for searching
		document.getElementById("search_img").disabled = false;   //enable image
	}else{
		document.getElementById("search").disabled = true;       //enable textbox for searching
		document.getElementById("search_img").disabled = true;   //enable image
	}	
}


//nursing station radiology-request GUI

function prepareAdd(id) {
	var details = new Object();
	if (checkRequestDetails()){
		//alert($('discount_name'+id).value);
		details.requestDoc= $('request_doctor').value;
		details.requestDocName= $('request_doctor_name').value;
		details.is_in_house= $('is_in_house').value;
		details.clinicInfo= $('clinical_info').value;
		details.idGrp = $('idGrp'+id).innerHTML;
		details.id = $('id'+id).value;
		details.qty = 1;
		details.name = $('name'+id).innerHTML;
		//details.desc = $('desc'+id).innerHTML;
		details.prcCash = $('cash'+id).value;
		details.prcCharge= $('charge'+id).value;
		details.sservice= $('sservice'+id).value;
		//details.discount_name= $('discount_name'+id).value;
		var list = window.parent.document.getElementById('order-list');
		var msg = "requestDoc='"+details.requestDoc+"'\ndetails.is_in_house='"+details.is_in_house+
					 "'\ndetails.qty='"+details.qty+"\nid='"+id+"'\ndetails.id='"+details.id+"'\ndetails.idGrp='"+details.idGrp+
					 "'\ndetails.name='"+details.name+"'\ndetails.prcCash='"+details.prcCash+
					 "'\ndetails.prcCharge='"+details.prcCharge+"'\ndetails.sservice='"+details.sservice+"'\n";	
//alert("prepareAdd : "+msg);

		result = window.parent.appendOrder(list,details);
//		window.parent.refreshTotal();	
		window.parent.refreshDiscount();
	}
/*
	result = window.parent.appendOrder(list,details);
	//if (result && $('discount_name'+id)) {
		//$('discount_name'+id).value = "A";
	//}
	window.parent.refreshTotal();
*/
}

function clearList(listID) {
	// Search for the source row table element
	var list=$(listID),dRows, dBody;
	if (list) {
		dBody=list.getElementsByTagName("tbody")[0];
		if (dBody) {
			dBody.innerHTML = "";
			return true;	// success
		}
		else return false;	// fail
	}
	else return false;	// fail
}

function addProductToList(listID, id, name, grp_code, cash, charge, sservice) {
	var list=$(listID), dRows, dBody, rowSrc;
	var i;
//	alert("addProductToList : id = '"+id+"'");
	if (list) {
		dBody=list.getElementsByTagName("tbody")[0];
		dRows=dBody.getElementsByTagName("tr");

		// get the last row id and extract the current row no.
		if (id) {
			/*
			rowSrc = "<tr>"+
									'<td>'+
										'<span id="name'+id+'" style="font:bold 12px Arial">'+name+'</span><br />'+
									'</td>'+
									'<td><span id="id'+id+'" style="font:bold 11px Arial;color:#660000">'+id+'</span></td>'+
									'<td align="right">'+
										'<input id="cash'+id+'" type="hidden" value="'+cash+'"/>'+cash+'</td>'+
									'<td align="right">'+
										'<input id="charge'+id+'" type="hidden" value="'+charge+'"/>'+charge+'</td>'+
									'<td align="center"><select name="discount_name'+id+'" id="discount_name'+id+'">'+
																'<option value="A">A</option>'+
																'<option value="C1">C1</option>'+
																'<option value="C2">C2</option>'+
																'<option value="C3">C3</option>'+
									'</td>'+
									'<td>'+
										'<input type="button" value=">" style="color:#000066; font-weight:bold; padding:0px 2px" '+
											'onclick="prepareAdd(\''+id+'\')" '+
										'/>'+
									'</td>'+
								'</tr>';
				*/
				
				rowSrc = "<tr>"+
									'<td width="*" align="left">'+
										'<span id="name'+id+'" style="font:bold 12px Arial">'+name+'</span><br />'+
									'	<input id="sservice'+id+'" type="hidden" value="'+sservice+'"/>'+	
									'</td>'+
									'<td width="17%" align="left">'+
									'	<span id="idGrp'+id+'" style="font:bold 11px Arial;color:#660000">'+id+' ('+grp_code+')</span>'+
									'	<input id="id'+id+'" type="hidden" value="'+id+'"/>'+
									'</td>'+									
									'<td align="right" width="15%">'+
										'<input id="cash'+id+'" type="hidden" value="'+cash+'"/>'+cash+'</td>'+
									'<td align="right" width="15%">'+
										'<input id="charge'+id+'" type="hidden" value="'+charge+'"/>'+charge+'</td>'+
									'<td width="2%">'+
										'<input type="button" value=">" style="color:#000066; font-weight:bold; padding:0px 2px" '+
											'onclick="prepareAdd(\''+id+'\')" '+
										'/>'+
									'</td>'+
								'</tr>';
		} //<input id="qty'+id+'" align="right" type="text" style="width:90%" value="" style="text-align:right" onblur="this.value = isNaN(parseFloat(this.value))?\'\':parseFloat(this.value)"/>
		else {
			rowSrc = '<tr><td colspan="6" style="">No such radiological service exists...</td></tr>';
		}
//		alert("aaddProductToList : rowSrc \n"+rowSrc);
		dBody.innerHTML += rowSrc;
	}
}

/*******       burn added : September 3, 2007       *******/

		/*	
				This will trim the string i.e. no whitespaces in the
				beginning and end of a string AND only a single
				whitespace appears in between tokens/words 
				input: object
				output: object (string) value is trimmed
		*/
function trimString(objct){
	objct.value = objct.value.replace(/^\s+|\s+$/g,"");
	objct.value = objct.value.replace(/\s+/g," "); 
}/* end of function trimString */


	/*
	*	Clears the list of options [0, doctors; 1, departments]
	*	burn added ; August 6, 2007
	*/
function ajxClearDocDeptOptions(status) {
	var optionsList;
	var el;

	if (status==0){
		el=$('request_doctor_in');
	}else{
		el=$('request_dept');
	}
	 
	if (el) {
		optionsList = el.getElementsByTagName('OPTION');
		for (var i=optionsList.length-1;i>=0;i--) {
			optionsList[i].parentNode.removeChild(optionsList[i]);
		}
	}
}/* end of function ajxClearDocDeptOptions */

	/*
	*	Adds an item in the list of options [0, doctors; 1, departments]
	*	burn added ; August 6, 2007
	*/
function ajxAddDocDeptOption(status, text, value) {
	var grpEl;

	if (status==0){
		grpEl=$('request_doctor_in');
	}else{
		grpEl=$('request_dept');
	}
	
	if (grpEl) {
		var opt = new Option( text, value );
		opt.id = value;
		grpEl.appendChild(opt);
	}
}/* end of function ajxAddDocDeptOption */


function ajxSetDepartment(dept_nr,list) {
			// burn added : August 30, 2007
		var current_dept = $('request_dept').value;
		var array = list.split(",");
//		alert("ajxSetDepartment : current_dept = '"+current_dept+"' \nlist = '"+list+"' \narray.length = '"+array.length+"'");
		for (var x=0; x<array.length; x++){
//			alert("ajxSetDepartment_d : array["+x+"] = '"+array[x]+"'");
			if (array[x]==current_dept){
				dept_nr=current_dept;
				break;
			}		
		}
		$('request_dept').value = dept_nr;
}

/*
function ajxSetDepartment(dept_nr) {
	alert("ajxSetDepartment ORIG : dept_nr ='"+dept_nr+"'");
	$('request_dept').value = dept_nr;
}
*/
function ajxSetDoctor(personell_nr) {
//	alert("ajxSetDoctor ; personell_nr = "+personell_nr);
	$('request_doctor_in').value = personell_nr;
}

function jsSetDoctorsOfDept(){
	var aDepartment_nr = $F('request_dept');
		
//		alert("jsRequestDoctor : aDepartment_nr ='"+aDepartment_nr+"'");

	if (aDepartment_nr != 0) {
		xajax_setDoctors(aDepartment_nr,0);	//get the list of ALL doctors under "aDepartment_nr" department
	} else{
		xajax_setDoctors(0,0);	//get the list of ALL doctors from ALL departments
	}	
//	alert("jsRequestDoctor : aDepartment_nr ='"+aDepartment_nr+"'");
//	request_doc_handler();
}

function jsSetDepartmentOfDoc(){
	var aPersonell_nr = $F('request_doctor_in');
		
//		alert("jsRequestDepartment : aPersonell_nr ='"+aPersonell_nr+"'");

	if (aPersonell_nr != 0) {
		xajax_setDepartmentOfDoc(aPersonell_nr);
	}
	request_doc_handler();
}

function request_doc_handler(){
	var docValue = $F('request_doctor_in');
//	alert("request_doc_handler : docValue ='"+docValue+"'");
	if (docValue==0){
		$('is_in_house').value = '0';
		$('request_doctor_out').disabled = false;
		$('request_doctor').value = '';
	}else{
		$('is_in_house').value = '1';
		$('request_doctor').value = $F('request_doctor_in');
		$('request_doctor_out').value = '';		
		$('request_doctor_out').disabled = true;		
	}
}


	/*	
			Checks if the requesting doctor & clinical form are filled-up 
			before enlisting a service.
			return : boolean
			burn added : August 31, 2007
	*/
function checkRequestDetails(){
		if (($F('request_doctor_in')=='0') && ($F('request_doctor_out')=='')){
			alert("Please specify the requesting doctor");
			$('request_doctor_out').focus();
			return false;	
		}else	if ($F('clinical_info')==''){
			alert("Please indicate the clinical information.");
			$('clinical_info').focus();
			return false;	
		}
		
		if ($F('is_in_house')=='1'){
			$('request_doctor').value = $F('request_doctor_in');
			var docObj = $('request_doctor_in');
			$('request_doctor_name').value = docObj.options[docObj.selectedIndex].text;
		}else{
			$('request_doctor').value = $F('request_doctor_out');
			$('request_doctor_name').value = $F('request_doctor_out');
		}	
		return true;
}