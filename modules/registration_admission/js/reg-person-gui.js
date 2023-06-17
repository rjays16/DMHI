function getcompany(pid=""){
	xajax_getcompany(pid);
}

function getcompanyname(pid){
	alert(pid);
}

function js_ClearOptions(tagId){

   	document.getElementById(tagId).options.length = 0;
}

function js_AddOptions(tagId, text, value){
    var o = new Option(text, value); 
	o.innerHTML = text; 
	document.getElementById(tagId).appendChild(o);
}

function js_setOption(tagId, value){
    document.getElementById(tagId).value = value;
}// end of function js_setOption
