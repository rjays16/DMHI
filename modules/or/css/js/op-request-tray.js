//edited by VAN 04-22-08 pagination
var AJAXTimerID=0;
var lastSearch="";


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

function startAJAXSearch(searchID, page) {
	var searchEL = $(searchID);
	trimString(searchEL);   //omit unnecessary white spaces
	
	//if (searchEL && lastSearch != searchEL.value) {
	if (searchEL) {	
		searchEL.style.color = "#0000ff";
		if (AJAXTimerID) clearTimeout(AJAXTimerID);
		$("ajax-loading").style.display = "";
		
		//alert('page = '+page+", "+searchEL.value);
		//AJAXTimerID = setTimeout("xajax_populateICPMList('"+searchID+"','"+searchEL.value+"')",200);
		//edited by VAN 04-22-08
		AJAXTimerID = setTimeout("xajax_populateICPMList('"+searchID+"','"+searchEL.value+"',"+page+")",200);
		lastSearch = searchEL.value;
	}
}

//-----------added by VAN 04-22-08
var currentPage=0, lastPage=0;
var FIRST_PAGE=1, PREV_PAGE=2, NEXT_PAGE=3, LAST_PAGE=4, SET_PAGE=0;

function setPagination(pageno, lastpage, pagen, total) {
	currentPage=parseInt(pageno);
	lastPage=parseInt(lastpage);	
	firstRec = (parseInt(pageno)*pagen)+1;
	
	//alert('currentPage, lastPage, firstRec, total = '+currentPage+", "+lastPage+", "+firstRec+", "+total);
	if (currentPage==lastPage)
		lastRec = total;
	else
		lastRec = (parseInt(pageno)+1)*pagen;
	
	if (parseInt(total)==0)
		$("pageShow").innerHTML = '<span>Showing '+(lastRec)+'-'+(lastRec)+' out of '+(parseInt(total))+' record(s).</span>';
	else
		$("pageShow").innerHTML = '<span>Showing '+(firstRec)+'-'+(lastRec)+' out of '+(parseInt(total))+' record(s).</span>';
	
	$("pageFirst").className = (currentPage>0 && lastPage>0 && total>10) ? "segSimulatedLink" : "segDisabledLink";
	$("pagePrev").className = (currentPage>0 && lastPage>0 && total>10) ? "segSimulatedLink" : "segDisabledLink";
	$("pageNext").className = (currentPage<lastPage && total>10) ? "segSimulatedLink" : "segDisabledLink";
	$("pageLast").className = (currentPage<lastPage && total>10) ? "segSimulatedLink" : "segDisabledLink";
	
}

function jumpToPage(el, jumpType, set) {
	if (el.className=="segDisabledLink") return false;
	if (lastPage==0) return false;
	//alert(jumpType);
	//alert(currentPage+", "+lastPage);
	switch(jumpType) {
		case FIRST_PAGE:
			if (currentPage==0) return false;
			startAJAXSearch('search',0);
		break;
		case PREV_PAGE:
			if (currentPage==0) return false;
			startAJAXSearch('search',parseInt(currentPage)-1);
		break;
		case NEXT_PAGE:
			if (currentPage >= lastPage) return false;
			startAJAXSearch('search',parseInt(currentPage)+1);
		break;
		case LAST_PAGE:
			if (currentPage >= lastPage) return false;
			startAJAXSearch('search',parseInt(lastPage));
		break;
	}
}

function checkEnter(e,searchID){
	//alert('e = '+e);	
	var characterCode; //literal character code will be stored in this variable

	if(e && e.which){ //if which property of event object is supported (NN4)
		e = e;
		characterCode = e.which; //character code is contained in NN4's which property
	}else{
		//e = event;
		characterCode = e.keyCode; //character code is contained in IE's keyCode property
	}

	if(characterCode == 13){ //if generated character code is equal to ascii 13 (if enter key)
		startAJAXSearch(searchID,0);
	}else{
		return true;
	}		
}

//---------------------------------------

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
	list = $('procedure-list');
	dBody=list.getElementsByTagName("tbody")[0];
	rowSrc = '<td colspan="5" style="font-weight:normal">No such procedure description/code exists...</td>';
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


function prepareAdd(id) {

	var details = new Object();

	details.id= id;
	details.description= $('descriptionFull'+id).value;
	details.code= $('code'+id).value;
	details.rvu= $('rvu'+id).value;
	details.multiplier= $('multiplier'+id).value;
	details.ops_charge='0.00';   //default value

	var list = window.parent.document.getElementById('order-list');
	var msg = "details='"+details+
				 "\ndetails.id='"+details.id+
				 "'\ndetails.description='"+details.description+
				 "'\ndetails.code='"+details.code+
				 "'\ndetails.rvu='"+details.rvu+
				 "'\ndetails.multiplier='"+details.multiplier+	
				 "'\ndetails.ops_charge='"+details.ops_charge+"'\n";	
//alert("prepareAdd : "+msg);

		result = window.parent.appendOrder(list,details);
//		window.parent.refreshTotal();	
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

//"procedure-list",$result["code"],$description,$result["rvu"],$result["multiplier"]);

function addProductToList(listID, id, description, descriptionFull, rvu, multiplier) {
	var list=$(listID), dRows, dBody, rowSrc;
	var i;
//	alert("addProductToList : id = '"+id+"'");
	if (list) {
		dBody=list.getElementsByTagName("tbody")[0];
		dRows=dBody.getElementsByTagName("tr");

		// get the last row id and extract the current row no.
		if (id) {			
				rowSrc = "<tr>"+
									'<td>'+
										'<span id="description'+id+'" style="font:bold 12px Arial">'+description+'</span><br />'+
										'<input id="descriptionFull'+id+'" type="hidden" value="'+descriptionFull+'">'+
									'</td>'+
									'<td>'+
									'	<span style="font:bold 12px Arial;color:#660000">'+id+'</span>'+
									'	<input id="code'+id+'" type="hidden" value="'+id+'">'+
									'</td>'+									
									'<td align="center">'+
										'<input id="rvu'+id+'" type="hidden" value="'+rvu+'">'+rvu+'</td>'+
									'<td align="center">'+
										'<input id="multiplier'+id+'" type="hidden" value="'+multiplier+'">'+multiplier+'</td>'+
									'<td>'+
										'<input type="button" value=">" style="color:#000066; font-weight:bold; padding:0px 2px" '+
											'onclick="prepareAdd(\''+id+'\')">'+
									'</td>'+
								'</tr>';
		} //<input id="qty'+id+'" align="right" type="text" style="width:90%" value="" style="text-align:right" onblur="this.value = isNaN(parseFloat(this.value))?\'\':parseFloat(this.value)"/>
		else {
			rowSrc = '<tr><td colspan="5" style="">No such procedure description/code exists...</td></tr>';
		}
//		alert("aaddProductToList : rowSrc \n"+rowSrc);
		dBody.innerHTML += rowSrc;
	}
}

/*******       burn added : September 3, 2007       *******/



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