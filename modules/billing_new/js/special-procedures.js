var Procedures = new Object();

Procedures.getPatientSpecialProcedures = function(procedures){
    var encounter_nr = $j('#encounter_nr').val();
    $j.ajax({
        url : 'ajax/ajax-special-procedure-details.php',
        dataType : 'json',
        type : 'get',
        data : {
            'action' : 'get_special_procedures',
            'encounter_nr' : encounter_nr
        },
        success : function(data){
            console.log(data);
            Procedures.newbornCaseRate = data.newborn;
            Procedures.specialProcedures = data.response;
            Procedures.procedures = procedures;
            Procedures.showSpecialProcedures();
        },
        error : function(x,y){
            alert(y);
        }
    });
}

Procedures.showSpecialProcedures = function(){
    var data = Procedures.specialProcedures;
    var list = '';
    var ul = $j('#special_procedure_details');
    ul.html('');

    for(var i=0;i<data.length;i++){
        var procedure = data[i];
        var value = (procedure.is_availed == true) ? "checked" : "";
        list = '<li id="li'+procedure.package_id+'" class="li_procedure">'+
            '<label>'+
            '<input id="chk'+procedure.package_id+'" type="checkbox" '+value+'/>'+
            '<strong>'+procedure.description+'</strong>'+
            '</label>'+
            '</li>';
        ul.append(list);
    }
    Procedures.init();
}

Procedures.init = function(){
    resetProcedureDetailList();
    $j('#first_rate').on('change', function() {
        changeCase('1');
        resetProcedureDetailList();
    });
    $j('#second_rate').on('change', function() {
        changeCase('2');
        resetProcedureDetailList();
    });
}

function resetProcedureDetailList(){
    hideAllProcedureDetails();
    showSelectedCaseRates();
}

function hideAllProcedureDetails(){
    $j(".li_procedure").hide();
}

function showSelectedCaseRates(){
    showProcedureDetails($j("#first_rate option:selected").attr("id"));
    showProcedureDetails($j("#second_rate option:selected").attr("id"));
}

function showProcedureDetails(code){
    var f = $j("#first_rate option:selected");
    var s = $j("#second_rate option:selected");
    var procedure = getProcedure(code);
    var prefix;

    if(procedure){
        var isAvailed = $('chk'+procedure.package_id).checked;
        if(isAvailed){
            if(procedure.is_for_availed){
                prefix = 'sp_';
            }else{
                prefix = 'orig_';
            }
        }else{
            if(procedure.is_for_availed){
                prefix = 'orig_';
            }else{
                prefix = 'sp_';
            }
        }

        var reference;
        if(f.attr('id') == code){
            reference = $j('#first_rate option:contains('+code+')');
            setAttributes(reference,prefix,procedure);
            changeCase('1');
        }else if(s.attr('id') == code){
            reference = $j('#second_rate option:contains('+code+')');
            setAttributes(reference,prefix,procedure);
            changeCase('2');
        }
    }

    //first case code = 99460
    if (!Procedures.newbornCaseRate.in_array(code)) {
        $j("#li"+code).show();
    }
}

function getProcedure(code){
    var data = Procedures.specialProcedures;
    for(var i=-0;i<data.length;i++){
        if(data[i].package_id == code){
            return data[i];
        }
    }
    return false;
}

function setAttributes(reference,prefix,procedure){
    var pf = reference.attr(prefix+'pf');
    //value -> 2950
    // value -> sp_amnt
    // value -> 2750

    //value_hf -> 2450
    // value_hf -> sp_hf
    // value_hf -> 2250
    reference.attr('value',reference.attr(prefix+'amnt'));
    reference.attr('value_hf',reference.attr(prefix+'hf'));
    // reference.attr('value_pf',pf);
}

Array.prototype.in_array = function(p_val) {
    for(var i = 0, l = this.length; i < l; i++) {
        if(this[i] == p_val) {
            return true;
        }
    }
    return false;
}