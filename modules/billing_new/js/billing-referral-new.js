function ClearReferral(){
     $('ReferredHospital').value = '';
     $('Reason').value = '';
}
function SaveReferral(){
    var hospital = $('ReferredHospital').value;
    var reason = $('Reason').value;
    var enc = $('encounter_nr').value;
    var nr = $('referral_nr').value;
       
    if($('ReferredHospital').value.trim() === "" && $('Reason').value.trim() === "") {
        alert('\t Unable to Save, \n Please Fill out the Fields!');
        xajax_SearchReferral(enc, nr);
        $('ReferredHospital').focus();
    }
    else if($('ReferredHospital').value.trim() === "") {
        alert('\t\t Unable to Save, \n Please Fill out the Acreditation #!');
        xajax_SearchReferral(enc, nr);
        $('ReferredHospital').focus();
    }
    else if($('Reason').value.trim() === "") {
        alert('\t Unable to Save, \n Please Fill out the Reason/s!');
        xajax_SearchReferral(enc, nr);
        $('Reason').focus();
    }
    else{
        xajax_SaveReferral(hospital,reason, enc, nr);
    }
   
}

function GetReferral(){
    var enc = $('encounter_nr').value;
    var nr = $('referral_nr').value
    xajax_Getreferral(enc, nr);
}

function CheckEmptyReferral(res){
    if (res == '1') {
        GetReferral();
    }else{
        return false;
    }
}

function SetValue(reason, hospital){
    $('ReferredHospital').value = hospital;
    $('Reason').value = reason;
}