var HEMO = '90935';
var CHEMO = 'CHEMOTHERAPY';
var DEB = 'DEBRIDEMENT';
var NEWBORNCAREPACKAGE = '99460';
var MCP01 = 'MCP01';

jQuery(function ($) {

    $j('#btnAddIcpCode').click(function () {


        var icpCode = $j('#icpCode').val();
        var icdDesc = $j('#icpDesc').val();

        // if(icpCode== HEMO || icdDesc.toUpperCase().indexOf(CHEMO)>=0){
        //     while (cnt) {
        //     }
        //     while (isNaN(parseFloat(cnt)) || parseFloat(cnt)<=0) {
        //         var cnt = prompt('Enter number of Sessions:');
        //         if (cnt === null) return false;
        //     }
        //     //$j("#is_special").val(1);
        //     $j("#num_sess").val(((icpCode==HEMO)?cnt:1));
        //     generateOpsDate(cnt);
        // } else if(icdDesc.toUpperCase().indexOf(DEB) >=0 && $j("#is_special").val()==1) {
        //     while (cnt) {
        //     }
        //     while (isNaN(parseFloat(cnt)) || parseFloat(cnt)<=0) {
        //         var cnt = prompt('Enter number of Operation:');
        //         if (cnt === null) return false;
        //     }

        //     $j("#num_sess").val(cnt);
        //     generateOpsDate(cnt);
        // } else {
        //     $j("#num_sess").val(1);
        //     generateOpsDate(1);
        // }

        $j("#num_sess").val(1);
        generateOpsDate(1);

        if (icpCode.toUpperCase() === MCP01) {
            generateOpsDateMCP();
        }


        if (!!icpCode) {
            if (!!icdDesc) {
                // Added by James 1/6/2014
                var code = $j('#icpCode').val();

                for (var key in globalcode) {

                    //added by Nick 05-07-2014
                    var hasDeb = (icdDesc.toUpperCase().indexOf(DEB) >= 0) ? true : false;
                    var hasChemo = (icdDesc.toUpperCase().indexOf(CHEMO) >= 0) ? true : false;
                    var hasHemo = (icpCode == HEMO) ? true : false;

                    /*commented by Nick 05-28-2014, allow multiple procedures
                    if (globalcode.hasOwnProperty(key)){
                        if (globalcode[key] == code){
                            if(hasHemo || hasDeb || hasChemo){//added by Nick 05-07-2014 - allow multiple special procedures
                                continue;
                            }else{
                                alert("Procedure already added.  " + hasDeb + "  " + hasChemo);
                                return;
                            }
                        }
                    }
                    */
                }
                $j("#opDateBox").dialog({
                    autoOpen: true,
                    modal: true,
                    height: "auto",
                    width: "auto",
                    resizable: false,
                    show: "fade",
                    hide: "explode",
                    title: "Date of Operation",
                    position: "top",
                    buttons: {
                        "Save": function () {
                            // Edited by James 1/6/2014
                            if ($("#laterality_option").val() == 0 && $("#laterality").val() == 1) {
                                alert("Please select a laterality!");
                                return;
                            } else if ($("#laterality").val() == 0) {
                                //console.log(2);
                                chkDate();
                                return;
                            }
                            //console.log(1);
                            chkDate();
                        },
                        "Cancel": function () {
                            $(this).dialog("close");
                        }
                    },
                    open: function () {
                        $j('.ui-button').focus();
                        $j.each($j('#opDate-body :input').serializeArray(), function (i, field) {
                            $j('#' + field.name).datepicker({
                                dateFormat: 'yy-mm-dd',
                                maxDate: 0
                            });
                        });
                        if (icpCode.toUpperCase() === MCP01) {
                            $j.each($j('#checkup_date_body :input').serializeArray(), function (i, field) {
                                $j('#' + field.name).datepicker({
                                    dateFormat: 'yy-mm-dd',
                                    maxDate: 0
                                });
                            });
                        }
                    }
                });
            } else {
                alert("Please indicate procedure description.");
            }
        } else {
            alert("Please indicate procedure code.");
        }

        return false;
    });

    if ($j("#icdCode")) {
        $j("#icdCode").autocomplete({
            minLength: 2,
            source: function (request, response) {
                $j.getJSON("ajax/ajax_ICD10.php?iscode=true", request, function (data, status, xhr) {
                    response(data);
                });
            },
            select: function (event, ui) {
                // alert(ui.item.label);
                $j("#icdCode").val(ui.item.id);
                $j("#icdDesc").val(ui.item.description);
            }
        });
    }

    if ($j("#icdDesc")) {
        $j("#icdDesc").autocomplete({
            minLength: 2,
            source: function (request, response) {
                $j.getJSON("ajax/ajax_ICD10.php?iscode=false", request, function (data, status, xhr) {
                    response(data);
                });
            },
            select: function (event, ui) {
                // alert(ui.item.label);
                $j("#icdCode").val(ui.item.id);
                $j("#icdDesc").val(ui.item.description);
            }
        });
    }

    if ($j("#icpCode")) {
        $j("#icpCode").autocomplete({
            minLength: 2,
            source: function (request, response) {
                $j.getJSON("ajax/ajax_ICPM.php?iscode=true", request, function (data, status, xhr) {
                    response(data);
                });
            },
            select: function (event, ui) {
                $j("#icpCode").val(ui.item.id);
                $j("#icpDesc").val(ui.item.description);
                $j("#rvu").val(ui.item.rvu);
                $j("#multiplier").val(ui.item.multiplier);
                $j("#laterality").val(ui.item.laterality);
                $j("#is_special").val(ui.item.special_case);
                $j("#for_infirmaries").val(ui.item.for_infirmaries);
                $j("#is_for_newborn").val(ui.item.is_for_newborn);

                console.log(ui);
            }
        });
    }

    if ($j("#icpDesc")) {
        $j("#icpDesc").autocomplete({
            minLength: 2,
            source: function (request, response) {
                $j.getJSON("ajax/ajax_ICPM.php?iscode=false", request, function (data, status, xhr) {
                    response(data);
                });
            },
            select: function (event, ui) {
                $j("#icpCode").val(ui.item.id);
                $j("#icpDesc").val(ui.item.description);
                $j("#rvu").val(ui.item.rvu);
                $j("#multiplier").val(ui.item.multiplier);
                $j("#laterality").val(ui.item.laterality);
                $j("#is_special").val(ui.item.special_case);
                $j("#for_infirmaries").val(ui.item.for_infirmaries);
                console.log(ui);
            }
        });
    }

    $j('#icdCode').click(function () {
        $j("#icdDesc").val("");
        $j('#icdCode').focus();
    });

    $j('#icdDesc').click(function () {
        $j("#icdCode").val("");
        $j('#icdDesc').focus();
    });

    $j('#icpCode').click(function () {
        $j("#icpDesc").val("");
        $j('#icpCode').focus();
    });

    $j('#icpDesc').click(function () {
        $j("#icpCode").val("");
        $j('#icpDesc').focus();
    });

    // $j('#check_all').change(function(){
    //
    //     $(".checkall").prop("checked", $(this).prop("checked"))
    // });
    // $(".checkall").change(function () {
    //     if ($(".checkall:checked").length != $(".checkall").length) {
    //         $j('#check_all').prop("checked", false);
    //     } else {
    //         $j('#check_all').prop("checked", true);
    //     }
    // });
});
var globalcode = {};

/**
 * Added: Juna Dajang 08-30-2021
 * Disable newborn hearing test when
 * field is not checked
 */
function disable_enableHearing(newborn_hearing) {

    var reg_card = document.getElementById("registry_card_no");
    var nhst = document.getElementById("nhst_result");
    var or = document.getElementById("check_attached");

    reg_card.disabled = newborn_hearing.checked ? false : true;
    nhst.disabled = newborn_hearing.checked ? false : true;
    or.disabled = newborn_hearing.checked ? false : true;

    if (!reg_card.disabled) {

        reg_card.focus();
        nhst.focus();
        or.focus();
    }else
        reg_card.clear();
        nhst.value = 'NOT APPLICABLE';

}

function addProcedure(opDate, special_dates) {

    var details = new Object();
    var mul = $j('#multiplier').val();

    details.encNr = $j('#encounter_nr').val();
    details.bDate = $j('#billdate').val();
    details.code = $j('#icpCode').val();
    details.desc = $j('#icpDesc').val();
    details.opDate = opDate;
    details.user = $j('#create_id').val();
    details.multiplier = parseInt(mul);
    details.rvu = $j('#rvu').val();
    details.charge = details.multiplier * details.rvu;
    details.laterality = $j("#laterality_option").val();
    details.sess_num = $j("#num_sess").val();
    details.special_dates = special_dates;
    details.icpDesc = $j('#icdDesc').val();
    // details.sticker_no = sticker_no;
    details.checkup_date1 = $j('#checkup_date1').val();
    details.checkup_date2 = $j('#checkup_date2').val();
    details.checkup_date3 = $j('#checkup_date3').val();
    details.checkup_date4 = $j('#checkup_date4').val();
    details.filter_card_no = $j('#filter_card_no').val();
    details.registry_card_no = $j('#registry_card_no').val();
    details.nhst_result = $j('#nhst_result').val();
    details.nhst = $j('#newborn_hearing').is(':checked');
    details.is_for_newborn = $j('#is_for_newborn').val();
    xajax_addProcedure(details);

}

function chkDate() {
    var checker = true;
    var checkup_date_checker = true;
    var special_dates = '';
    var filterCardNo = '';
    var code = $j('#icpCode').val();
    var is_for_newborn = $j('#is_for_newborn').val();
    $j.each($j('#opDate-body :input').serializeArray(), function (i, field) {
        if (field.value == '') {
            checker = false;
        } else {
            if (i == 0) {
                opDate = field.value
            }
            if ($j("#is_special").val() == 1) {
                special_dates += field.value + ',';
            }
        }
    });
    $j.each($j('#checkup_date_body :input').serializeArray(), function (i, field) {
        if (field.value == '') {
            checkup_date_checker = false;
        }

    });

    if (checker && checkup_date_checker) {
            if (is_for_newborn == 1) {
                // filterCardNo = prompt("Filter Card Number:");
                //added by Juna Dajang 09-01-21
                $j("#newbornPackage").dialog({
                    autoOpen: true,
                    modal: true,
                    height: "auto",
                    width: "auto",
                    resizable: false,
                    show: "fade",
                    hide: "explode",
                    title: "Newborn Care Package",
                    position: "top",
                    buttons: {
                        "Save": function () {

                            saveNewborn(opDate, special_dates);

                        },
                        "Cancel": function () {

                            $(this).dialog("close");
                            // $j("#newbornPackage").dialog("close");
                        }
                    }
                });

            } else {
                addProcedure(opDate, special_dates);
            }
            $j("#opDateBox").dialog("close");
            // if (filterCardNo.length >= 1 && filterCardNo.length <= 20) {
            //     $j("#opDateBox").dialog("close");
            //     console.log(filterCardNo);
            //     addProcedure(opDate, special_dates, filterCardNo);
            // } else {
            //     if (code == NEWBORNPACKAGE) {
            //         alert('Maximum number of Filter Card number is 20 please try again.');
            //     } else {
            //         $j("#opDateBox").dialog("close");
            //         addProcedure(opDate, special_dates, filterCardNo);
            //     }
            // }

        } else {
            alert("Please enter a valid date!");
        }
}
/**
 * added by Juna Dajang
 * 09-01-21
 **/
function saveNewborn(opDate, special_dates) {

    var new_checker = true;
    var filter_card_no = '';
    var regCard = '';
    var nhstResult = '';

    $j.each($j('#newbornPackage :input').serializeArray(), function (i, field) {
        if (field.value == '') {
            new_checker = false;
        } else {

            filter_card_no = $j('#filter_card_no').val();
            regCard = $j('#registry_card_no').val();
            nhstResult = $j('#nhst_result').val();

        }
    });

    if (new_checker){
        if (filter_card_no.length >= 1 && filter_card_no.length <= 20) {
            $j("#newbornPackage").dialog("close");
            addProcedure(opDate, special_dates);
            alert("Saved successfully!");
        }else
            alert("Maximum number of filter card is 20. Please try again.");
    } else {
        alert("Please enter missing fields!");
    }

}


//added by Nick, 3/4/2014
function edit_icp(code) {
    $('icp_desc_input' + code).style.display = '';
    $('description' + code).style.display = 'none';
    $('icp_desc_input' + code).focus();
}

function cancel_icp(code, pDesc) {
    $('description' + code).style.display = '';
    $('icp_desc_input' + code).style.display = 'none';
}

function updateIcpAltDesc(e, code) {
    var characterCode;
    var enc_nr = $('encounter_nr').value;
    var user_id = $('create_id').value;

    if (e) {
        if (e && e.which) {
            characterCode = e.which;
        } else {
            characterCode = e.keyCode;
        }
    } else
        characterCode = 13;

    if ((characterCode == 13) || (isESCPressed(e))) {
        var refno = $('icp_refno' + code).value;
        var desc = $('icp_desc_input' + code).value;
        xajax_updateIcpDesc(refno, code, desc);
        $('description' + code).innerHTML = '<a id="description' + code + '" style="font:bold 12px Arial" onclick="edit_icp(' + code + ')">' + desc + '</a>';
        $('description' + code).style.display = '';
        $('icp_desc_input' + code).style.display = 'none';
    }
}

//end nick

//added by Nick 05-07-2014
function incrementOpCount(code) {
    elem = $j("#" + code);
    if (typeof elem.html() != 'undefined') {
        opCount = $j('#' + code + ' td:nth-child(3)').html();
        opCount++;
        $j('#' + code + ' td:nth-child(3)').html(opCount);
        return true;
    } else {
        return false;
    }
}

function addProcedureToList(data, isFromAdd) {
    var rowSrc;
    var rate_type = 2;
    var elTarget = '#' + data.target;
    var code = data.code;
    var filterCardNo = '';
    var filterCardUi = '';
    var checkdateUi = '';
    var newbornUpdate = '';
    globalcode[code] = code; // Addded by James 1/6/2014
    console.log(data);
    //added by Nick 05-07-2014 - for multiple special procedures
    if (isFromAdd) {
        if (incrementOpCount(code)) {
            return;
        }
    }

    if (data) {
        // if (code == NEWBORNPACKAGE) {
        //     filterCardNo = ' | Filter Card Number: ' + data.opSticker;
        //     filterCardUi = '<img src="../../images/cashier_edit_3.gif" style="border-right:hidden; cursor:pointer; margin-right:5px;" onclick="updateFCN(' + encounter_nr + ',' + data.code + ',' + data.opRefno + ')" title="Update Filter Card Number">';
        // }//commented by Juna, 09-01-21
        if (code == NEWBORNCAREPACKAGE) {

            newbornUpdate = '<img src="../../images/cashier_edit_3.gif" style="border-right:hidden; cursor:pointer; margin-left:5px;" onclick="editNewborn(' + encounter_nr + ',' + data.code + ',' + data.opRefno + ')">'; //edit_newborn_package added by juna 08/26/21
        }
        if (code === MCP01) {
            checkdateUi = '<img src="../../images/cashier_edit_3.gif" style="border-right:hidden; cursor:pointer; margin-right:5px;" onclick="editCheckupDate(' + encounter_nr + ',' + data.code + ',' + data.opRefno + ')">'; // editCheckupDate
        }
        // if(data.frmtransmit == '1'){
        //  del_proc = "seg_rmvICP_code";
        // }else{
        del_proc = "rmvICP_code";
        // }
        opEntry = "'" + data.opEntry + "'";
        rowSrc = '<tr id=' + data.code + '>' +
            '<td>' +
            '<span style="font:bold 12px Arial;color:#660000">' + data.code + '</span>' +
            '</td>' +
            '<td onclick="edit_icp(' + data.code + ')">' +
            '<input id="icp_refno' + data.code + '" type="hidden" value="' + data.opRefno + '" />' +
            '<input id="icp_desc_input' + data.code + '" style="font:bold 12px Arial; display:none; width:100%;" value="' + data.opDesc + '" onblur="cancel_icp(' + data.code + ')" onFocus="this.select();" onkeyup="updateIcpAltDesc(event,' + data.code + ')">' + // //added by Nick, 3/4/2014
            '<a id="description' + data.code + '" style="font:bold 12px Arial" >' + data.opDesc + '</a><br/>' +
            '</td>' +
            '<td align="center">' + data.opCount + '</td>' +
            '<td align="center">' + data.opDate + '</td>' +
            '<td align="center">' + '<input id="rvu' + data.code + '" type="hidden" value="' + data.opRVU + '">' + data.opRVU + '</td>' +
            '<td align="center">' + '<input id="multiplier' + data.code + '" type="hidden" value="' + data.opMultiplier + '">' + data.opMultiplier + '</td>' +
            '<td align="right">' + '<input id="charge' + data.charge + '" type="hidden" value="' + data.charge + '">' + data.charge + '</td>' +
            // '<td align="center"><img src="../../images/btn_delitem.gif" style="border-right:hidden; cursor:pointer" onclick="xajax_delICP(\''+id+'\')" ></td></tr>';
            '<td align="center"><img src="../../images/btn_delitem.gif" style="border-right:hidden; cursor:pointer" onclick="' + del_proc + '(\'' + data.code + '\',' + rate_type + ',' + data.frmtransmit + ');"> ' + checkdateUi + newbornUpdate + '</td>' +

            //'<td align="center">'+filterCardUi+'<img src="../../images/btn_delitem.gif" style="border-right:hidden; cursor:pointer" onclick="prepDelProc('+new_code+','+opEntry+', '+data.opRefno+');"></td>'+
            '</tr>';


    } else {
        rowSrc = '<tr><td colspan="9" style="">No procedure encoded yet ...</td></tr>';
    }

    $j(elTarget).prepend(rowSrc);
    clearICPFields();

}

//added by daryl 09/03/2014
//show input by medical records
function addProcedureToList_seg(data, isFromAdd) {
    var rowSrc;
    var elTarget = '#' + data.target;
    var code = data.code;

    globalcode[code] = code; // Addded by James 1/6/2014

    //added by Nick 05-07-2014 - for multiple special procedures
    if (isFromAdd) {
        if (incrementOpCount(code)) {
            return;
        }
    }

    if (data) {

        rowSrc = '<tr id=' + data.code + '>' +
            '<td>' +
            '<span style="font:bold 12px Arial;color:#660000">' + data.code + '</span>' +
            '</td>' +
            '<td onclick="edit_icp(' + data.code + ')">' +
            '<input id="icp_refno' + data.code + '" type="hidden" value="' + data.opRefno + '" />' +
            '<input id="icp_desc_input' + data.code + '" style="font:bold 12px Arial; display:none; width:100%;" value="' + data.opDesc + '" onblur="cancel_icp(' + data.code + ')" onFocus="this.select();" onkeyup="updateIcpAltDesc_seg(event,' + data.code + ')">' + // //added by Nick, 3/4/2014
            '<a id="description' + data.code + '" style="font:bold 12px Arial" >' + data.opDesc + '</a><br/>' +
            '</td>' +
            '<td align="center">' + data.opCount + '</td>' +
            '<td align="center">' + data.opDate + '</td>' +
            '<td align="center">' + '<input id="rvu' + data.code + '" type="hidden" value="' + data.opRVU + '">' + data.opRVU + '</td>' +
            '<td align="center">' + '<input id="multiplier' + data.code + '" type="hidden" value="' + data.opMultiplier + '">' + data.opMultiplier + '</td>' +
            '<td align="right">' + '<input id="charge' + data.charge + '" type="hidden" value="' + data.charge + '">' + data.charge + '</td>' +
            // '<td align="center"><img src="../../images/btn_delitem.gif" style="border-right:hidden; cursor:pointer" onclick="xajax_delICP(\''+id+'\')" ></td></tr>';
            '<td style="display:none"  align="center"><img src="../../images/btn_delitem.gif" style="border-right:hidden; cursor:pointer" onclick="prepDelProc(' + code + ');"></td>' +
            '<td></td></tr>';
    } else {
        rowSrc = '<tr><td colspan="9" style="">No procedure encoded yet ...</td></tr>';
    }

    $j(elTarget).prepend(rowSrc);
    clearICPFields();

}

function updateIcpAltDesc_seg(e, code) {
    var characterCode;
    var enc_nr = $('encounter_nr').value;
    var user_id = $('create_id').value;
    var frommedrec = 1;
    if (e) {
        if (e && e.which) {
            characterCode = e.which;
        } else {
            characterCode = e.keyCode;
        }
    } else
        characterCode = 13;

    if ((characterCode == 13) || (isESCPressed(e))) {
        var refno = $('icp_refno' + code).value;
        var desc = $('icp_desc_input' + code).value;
        xajax_updateIcpDesc(refno, code, desc, frommedrec, enc_nr, user_id);
        $('description' + code).innerHTML = '<a id="description' + code + '" style="font:bold 12px Arial" onclick="edit_icp(' + code + ')">' + desc + '</a>';
        $('description' + code).style.display = '';
        $('icp_desc_input' + code).style.display = 'none';
    }
}

//ednded by daryl

function clearProcList() {
    $j('#ProcedureList-body').empty();
}

function rmvProcRow(id) {
    // $j('#'+id).remove();
    //added by Nick 05-07-2014
    opCount = $j('#' + id + ' td:nth-child(3)').html();
    if (opCount > 1) {
        opCount--;
        $j('#' + id + ' td:nth-child(3)').html(opCount);
    } else {
        $j('#' + id).remove();
    }
    //end nick
    alert("Procedure successfully deleted!");
}

function prepDelProc(code) {
    var details = new Object();
    details.enc = $j('#encounter_nr').val();
    details.bdate = $j('#billdate').val();
    details.fdate = $j('#admission_dt').val();
    details.code = code;

    for (var key in globalcode) {
        if (globalcode.hasOwnProperty(key))
            if (globalcode[key] == code) {
                delete globalcode[key];
                return;
            }
    }
    xajax_deleteProcedure(details);

}

//added by daryl 09/15/2014
function prepDelProc_seg(code) {

    var details = new Object();
    details.enc = $j('#encounter_nr').val();
    details.code = code;

    for (var key in globalcode) {
        if (globalcode.hasOwnProperty(key))
            if (globalcode[key] == code) {
                delete globalcode[key];
                return;
            }
    }
    xajax_deleteProcedure_seg(details);

}

function preprmvICP_code(code, result, result2) {

    if ((code == result) || code == result2) {
        alert("Cannot be Deleted! Package has been used!");
    } else
        prepDelProc(code);
}

function preprmvICP_code_seg(code, result, result2) {
    if ((code == result) || code == result2) {
        alert("Cannot be Deleted! Package has been used!");
    } else
        prepDelProc_seg(code);
}

function rmvICP_code(code, rate_type, frmtransmit = "") {
    var enc_nr = $('encounter_nr').value;
    xajax_getbillcaserate_ICP(enc_nr, code, rate_type, frmtransmit);
}

function clearICPFields() {
    $j('#icpCode').val("");
    $j('#icpDesc').val("");
}


function generateOpsDate(cnt) {
    var rowSrc = '';
    var elTarget = '#opDate-body';
    $j('#opDate-body').empty();
    for (var i = 0; i < cnt; i++) {
        rowSrc += '<tr id="opDateBox-date-' + i + '">' +
            '<td width="*" align="left">' +
            '    <strong> Date ' + ((cnt > 1) ? parseInt(i + 1) : '') + '</strong>' +
            '</td>' +
            '<td width="*" align="left">' +
            '    <input type="text" id="op_date_' + i + '" name="op_date_' + i + '" maxlength="10" size="10" />' +
            '</td>' +
            '</tr>';

        if ($j("#laterality").val() == 1) {
            rowSrc += '<tr id="opDateBox-laterality">' +
                '    <td width="*" align="left">' +
                '        <strong> Laterality </strong>' +
                '    </td>' +
                '    <td width="*" align="left">' +
                '        <select id="laterality_option">' +
                '            <option value="0">-Select-</option>' +
                '            <option value="L">Left</option>' +
                '            <option value="R">Right</option>' +
                '            <option value="B">Both</option>' +
                '        </select>' +
                '    </td>' +
                '</tr>'
        }
    }
    $j(elTarget).prepend(rowSrc);

}

function generateOpsDateMCP() {
    let elTarget = '#checkup_date_body';
    let rowSrc = null;
    $j(elTarget).empty();
    for (let i = 1; i <= 4; i++) {
        rowSrc += '<tr id="checkup_date_box' + i + '">' +
            '<td width="*" align="left">' +
            '    <strong>Check-up Date ' + i + '</strong>' +
            '</td>' +
            '<td width="*" align="left">' +
            '    <input type="text" id="checkup_date' + i + '" name="checkup_date' + i + '" maxlength="10" size="10" />' +
            '</td>' +
            '</tr>';
        //console.log();
    }
    $j(elTarget).prepend(rowSrc);
}

//added by daryl 09/04/2014
function rmvcode(diagnosis_nr, is_primary, create_id, code, rate_type) {

    var enc_nr = $('encounter_nr').value;
    xajax_getbillcaserate(enc_nr, diagnosis_nr, is_primary, create_id, code, rate_type);
}

function process_rmvcode(enc_nr, diagnosis_nr, is_primary, create_id, code, result) {

    if (code == result)
        alert("Cannot be Deleted! Package has been used!");

    else
        xajax_rmvCode(diagnosis_nr, is_primary, create_id);
}

//ended by daryl

// Added by Jeff 04-26-18 for saving of patient diagnosis.
function saveDiagnosis() {
    var enc_nr = $('encounter_nr').value;
    var enc_diagnosis = $('encDiagnosis').value;
    xajax_saveDiagnosisEnc(enc_nr, enc_diagnosis);
}

// END jeff ---

// function updateFCN(enc, code, refno) {
//
//     var enc = enc;
//     var code = code;
//     var refno = refno;
//     var ok;
//
//     var fcn = prompt("New Filter Card Number:");
//
//     if (fcn != "") {
//         if (fcn == null) {
//             // Do nothing lang swa!...
//         } else {
//
//             if (fcn.length >= 1 && fcn.length <= 20) { // Added by Johnmel
//                 ok = xajax_saveFilterCardNumber(enc, code, refno, fcn);
//                 if (ok) {
//                     alert("Success: Filter Card Number successfully saved!");
//                     location.reload();
//                 }
//             } else {
//                 alert('Maximum number of Filter Card number is 20 please try again.');
//             }
//         }
//     } else {
//         alert("System: Filter Card Number cannot be empty!");
//         this.updateFCN(enc, code, refno);
//     }
// } //commented due to updated newborn package
/**
 * Added: Juna Dajang 08-26-2021
 * Update newborn care package
 *
 */
function editNewborn(enc, code, refno) {

    var new_checker = true;
    var filter_card_no = '';
    var registry_card_no = '';
    var nhst_result = '';
    var enc = enc;
    var code = code;
    var refno = refno;
    var save;

    var reg_card = document.getElementById("registry_card_no");
    var nhst = document.getElementById("nhst_result");
    var or = document.getElementById("check_attached");
    var chk = document.getElementById("newborn_hearing");

    if (reg_card.value != '') {

        reg_card.disabled = false;
        nhst.disabled = false;
        or.disabled = false;
        chk.checked = true;
    }

    $j("#newbornPackage").dialog({
        autoOpen: true,
        modal: true,
        height: "auto",
        width: "auto",
        resizable: false,
        show: "fade",
        hide: "explode",
        title: "Newborn Care Package",
        position: "top",
        buttons: {
            "Save": function () {

                filter_card_no = $j('#filter_card_no').val();

                if ($j('#newborn_hearing').is(':checked') && $j('#registry_card_no').val() == '' || $j('#filter_card_no').val() == '') {
                    new_checker = false;
                    alert("Missing fields are required and cannot be empty");
                }

                if (new_checker && filter_card_no.length >= 1 && filter_card_no.length <= 20) {

                    filter_card_no = $j('#filter_card_no').val();
                    registry_card_no = $j('#registry_card_no').val();
                    nhst_result = $j('#nhst_result').val();
                    var newborn_hearing = $j('#newborn_hearing').is(':checked') ? true : false;

                    save = xajax_updateNewbornPackage(enc, code, refno, filter_card_no, registry_card_no, nhst_result, newborn_hearing);

                    if (save) {
                        $j("#newbornPackage").dialog("close");
                        alert("Successfully updated!");
                        location.reload();
                    } else {
                        alert("Fields not saved!");
                    }
                }else
                    alert("Maximum number of filter card is 20. Please try again.");
            },
            "Cancel": function () {

                // $( this ).dialog( "close" );
                $j("#newbornPackage").dialog("close");
            }
        }
    });

}

/**
 * Added: Jan Chris Ogel 01-26-2021
 * Update pre-natal checkup dates
 * @param enc
 * @param code
 * @param refno
 */
function editCheckupDate(enc, code, refno) {

    generateOpsDateMCP();

    $j("#opDateBox").dialog({
        autoOpen: true,
        modal: true,
        height: "auto",
        width: "auto",
        resizable: false,
        show: "fade",
        hide: "explode",
        title: "Pre-natal Checkup",
        position: "top",
        buttons: {
            "Save": function () {
                var checkup_date_checker = true;
                $j.each($j('#checkup_date_body :input').serializeArray(), function (i, field) {
                    if (field.value == '') {
                        checkup_date_checker = false;
                    }
                })

                if (checkup_date_checker) {
                    var dates = [
                        $j('#checkup_date1').val(),
                        $j('#checkup_date2').val(),
                        $j('#checkup_date3').val(),
                        $j('#checkup_date4').val()
                    ]
                    var ok = xajax_updateCheckupDate(enc, code, refno, dates);
                    if (ok) {
                        alert("Success: Pre-natal Checkup Saved!");
                        location.reload();
                    }
                } else {
                    alert("Checkup Date fields is null");
                }
            },
            "Cancel": function () {
                $j(this).dialog("close");
            }
        },
        open: function () {
            $j('.ui-button').focus();
            $j.each($j('#checkup_date_body :input').serializeArray(), function (i, field) {
                $j('#' + field.name).datepicker({
                    dateFormat: 'yy-mm-dd',
                    maxDate: 0
                });
            });
        }
    });
}