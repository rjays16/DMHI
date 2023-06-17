/**
 * Created by Jarel on 7/26/14.
 */

//added by EJ 09/30/2014
var array_cases = [];
var case_numbers = [];

$j(function(){

    $j('#billdate_display').datetimepicker({
        dateFormat: 'M d, yy',
        timeFormat: 'hh:mm tt',
        onSelect: function(selectedDate){
            $j('#billdate').val(toDate(new Date(selectedDate), "yyyy-mm-dd hh:mn")+':00');
        }
    });

    $j('#selectall').click( function(){
        var sub_total = 0;
        var flag = (($j('#selectall').is(':checked')) ? true : false);
        var fields = $j('.checkall').serializeArray();
        $j.each(fields, function(i, field){
            $j('#check-'+field.name).attr('checked', flag);
            if(flag)
               sub_total += parseFloat($j('#'+field.name).val().replace(',',''));

            if($j('#selectall').is(':checked')){
                var id_elm = $j('#check-'+field.name).attr('id');
                var encounter = id_elm.replace("check-select-employee-", "");
                getCaseNumbers(encounter);
            }else{
                case_numbers = [];
            }
        });
        $j('#show-sub-total').html(numFormat(sub_total));
        calculateTotal();
    });


    $j('#btnSave').click(function (){
        var data = new Object();
        var details = new Array();

        var fields = $j('.checkall').serializeArray();
        $j.each(fields, function(i, field){
            var flag = (($j('#check-'+field.name).is(':checked')) ? true : false);
            if(flag){
                var enc = $j('#'+field.name).attr('encounter_nr');
                details.push({"enc":enc, "amount":field.value.replace(',','')});
            }
        });

        data.comp_id = $j('#comp_id').val();
        data.billdate = $j('#billdate').val();
        data.billnr = $j('#billnr').val();
        data.discount = parseFloat($j('#show-discount-total').html().replace(',',''));
        xajax_saveCompanyBill(data,details);
    });

    $j('#show-discount-total').click(function (){
        $j('#discount_input').show();
        $j('#show-discount-total').hide();
        $j('#discount_input').val('');
    });

    $j('#discount_input').on("blur", function (){
        var sub_total = parseFloat($j('#show-sub-total').html().replace(',',''));
        var discount = parseFloat($j('#discount_input').val().replace(',',''));

        if((discount > sub_total) || (isNaN(discount)))
            discount = 0;

        $j('#discount_input').hide();
        $j('#show-discount-total').show();
        $j('#show-discount-total').html(numFormat(discount));
        calculateTotal();

    });


});

function numFormat(num){
    var tmpNr = '';
    tmpNr = parseFloat(num).toFixed(2);
    tmpNr = tmpNr.replace(/\B(?=(\d{3})+(?!\d))/g,",");

    return tmpNr;
}

function calculateTotal(){
    var sub_total = parseFloat($j('#show-sub-total').html().replace(',',''));
    var discount = parseFloat($j('#show-discount-total').html().replace(',',''));
    var total = sub_total - discount;
    $j('#show-net-total').html(numFormat(total));
}

function calculateSub(enc)
{
    var temp_total = parseFloat($j('#select-employee-'+enc).val().replace(',',''));
    var sub_total = parseFloat($j('#show-sub-total').html().replace(',',''));

    if($j('#check-select-employee-'+enc).is(':checked')){
        $j('#show-sub-total').html(numFormat(temp_total + sub_total));
    }else{
        $j('#show-sub-total').html(numFormat(temp_total - sub_total));
    }
    calculateTotal();
 }

//created by EJ 09/30/2014
function getCaseNumbers(enc)
{   
    if($j('#check-select-employee-'+enc).is(':checked')) {
        array_cases.push(enc);
    }
    else {
        var index = array_cases.indexOf(enc);

        if (index > -1) {
        array_cases.splice(index, 1);
        }
    }

    var unique = function(origArr) {
    var newArr = [],
        origLen = origArr.length,
        found, x, y;

    for (x = 0; x < origLen; x++) {
        found = undefined;
        for (y = 0; y < newArr.length; y++) {
            if (origArr[x] === newArr[y]) {
                found = true;
                break;
            }
        }
        if (!found) {
            newArr.push(origArr[x]);
        }
    }
    return newArr;
    }

    case_numbers = unique(array_cases);
}

//added by mai 07/29/2014 
function printReport(comp_id, cutoff_time, bill_nr){
    var root_path = $('root_path').value;

    if (case_numbers == "") {
        alert("Please select employee(s)");
        exit;
    };

    urlholder = root_path+'modules/billing_new/billing_company_transmittal.php?'+
                    '&comp_id='+comp_id+'&cutoff_time='+cutoff_time+
    '&bill_nr='+bill_nr+'&case_nrs='+case_numbers;
    if(comp_id){
        printwindow = window.open(urlholder, "Print Company Billing", "toolbar=no, status=no, menubar=no, width=700, height=500, location=center, dependent=yes, resizable=yes, scrollbars=yes");
    }else{
        alert("Please specify company!");
    }
}

function toDate(epoch, format, locale) {
    var date = new Date(epoch),
        format = format || 'dd/mm/YY',
        locale = locale || 'en'
    dow = {};

    dow.en = [
        'Sunday',
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday'
    ];

    var formatted = format
        .replace('D', dow[locale][date.getDay()])
        .replace('dd', ("0" + date.getDate()).slice(-2))
        .replace('mm', ("0" + (date.getMonth() + 1)).slice(-2))
        .replace('yyyy', date.getFullYear())
        .replace('yy', (''+date.getFullYear()).slice(-2))
        .replace('hh', ("0" + date.getHours()).slice(-2))
        .replace('mn', ("0" + date.getMinutes()).slice(-2));

    return formatted;
}






