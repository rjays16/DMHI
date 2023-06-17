var dialogSelEnc, deathdate ,dialogInsurance, dialogInsurance,
    acc_computed = 0, //total computation of accommodation
    miscServices_computed = 0, //total computation of miscellaneous services
    med_computed = 0, //total computation of drugs and medicines
    miscCharges_computed= 0, //total computation of miscellaneous charges
    ops_computed= 0, totalPackage= 0, PercentPF, PercentHCI, HCIPackageAmount=0, PFPackageAmount = 0, totalHCIDiscount= 0,
    totalPFDiscount= 0, TotalDiscount= 0, HCIExcess= 0, PFExcess= 0, TotalExcess= 0, totalNet= 0, totalGross= 0, totalHCI= 0,
    totalPF= 0, totalHealthInsuranceHF1 = 0, totalHealthInsuranceHF = 0, totalHealthInsurancePF = 0, PFd1 = 0, PFd2 = 0, PFd3 = 0, PFd4 = 0, HIadm = 0,
    HIsurg = 0, HIanes = 0, tmpHIadm = 0, tmpHIsurg = 0, tmpHIanes = 0, deposit= 0, returnMeds= 0, firstcase=0, secondcase=0, com_total_amount=0,
    firstratecode='', secondratecode='', servDisc=0, servHIC=0, bill_nr, accexcess=0, tmpRVU = 0, drCharge = 0, bill_nr, prev_bal = 0,
    NBB = '5', HSM = '9', PHS = false, process_type='' , D1_nr = '', D4_nr = '', D3_nr = '', D1_chrg = '', D4_chrg = '', D3_chrg = '',
    D2_nr = '', D2_chrg = '', TotalUnsed = 0, TotalAutoExcess =0;
    D1_discount = 0, D2_discount = 0, D3_discount = 0, D4_discount = 0,
    D1_coverage = 0, D2_coverage= 0, D3_coverage = 0, D4_coverage = 0, opsCode = '', temp_discount = 0;
    var HSM_desc = "HOSPITAL SPONSORED MEMBER";
    var NBB_desc = "SPONSORED MEMBER";
    var NEWBORN_PKG = '99432';
    var count = 0;
    isMedicolegal = false;

var NEWBORN_PKG = '99432';

var hospitaldiscount = 0,
    profdiscount = 0,
    hospitalfinal = 0,
    pffinal = 0;

var excessHCIdiscount = 0,
    excessPFdiscount = 0,
    totalHealthInsuranceHFtemp = 0,
    excessHCIdiscounttemp = 0;
var HospitalCompany = 0,
    ProfCompany = 0;

//added by daryl
var hci_discount = 0,pf_discount = 0;
//added by Nick 1/11/2014
var total_applied_discount = 0,
    total_serv_discount=0,
    total_pf_discount=0,
    total_msc_discount=0,
    final_phic=null,
    final_discount=null,
    final_net_amount=null;

var isFinalBill = false,
    isInfirmaryOrDependent = '',//added by Nick 4/8/2014
    isNewBorn=false,hasHearingTest=false//added by Nick 4/21/2014
    isComputing = false;//added by Nick 05-27-2014

var tmpOPDetails = new Array();

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

function getPreviousPayment(prevpayment){
    $('bdeposit').innerHTML = prevpayment;
    deposit = prevpayment.replace(',','');
}

function preset(){

    var enc = $j('#encounter_nr').val();
    var pid = $j('#pid').val();
    var bill_nr = $j('#bill_nr').val();

	$j("tbody").find(".toggle").hide();
		$j('thead.togglehdr').each(function(idx, obj) {
			var obj = $j(obj);
			obj.find('th.toggleth').click( function() {
				obj.parent().children('tbody.toggle').toggle();
				obj.find(".arrow").toggleClass("up");
			});
    });

    //added by Nick 05-15-2014
    $j('#btnEditMemCat').click(function(){
        $j('#dlgMemCat')
        .dialog({
            title:"Select new Member Category",
            modal: true,
            position: "top",
            buttons:{
                Save: function(){
                    enc = $j('#encounter_nr').val();
                    memcat = $j('#optMemCat').val();
                    xajax_updateMemCat(enc,memcat);
                },
                Cancel: function(){
                    $j(this).dialog("close");
                }
            }
        });
    });

    $j('#categ_col').innerHTML = '<a title="Edit" href="#"></a>&nbsp;MEMBERSHIP CATEGORY:&nbsp;&nbsp;&nbsp;<span id="mcategdesc" name="mcategdesc"></span>';
    $j( "#btnSave" ).button({text: true,icons: {primary: "ui-icon-disk"}});
    $j( "#btnPrevPack" ).button({text: true,icons: {primary: "ui-icon-suitcase"}});
    $j( "#btnPrintOld" ).button({text: true,icons: {primary: "ui-icon-print"}});
    $j( "#btnPrint" ).button({text: true,icons: {primary: "ui-icon-print"}});
    $j( "#btnInsurance" ).button({text: true,icons: {primary: " ui-icon-plusthick"}});
    $j( "#btnDiagnosis" ).button({text: true,icons: {primary: " ui-icon-lightbulb"}});
    $j( "#btnDelete" ).button({text: true,icons: {primary: "ui-icon-trash"}});
    $j( "#btnNew" ).button({text: true,icons: {primary: " ui-icon-circle-plus"}});
    $j( "#btnaccommodation" ).button({text: true,icons: {primary: " ui-icon-circle-plus"}});
    $j( "#btnOPaccommodation" ).button({text: true,icons: {primary: " ui-icon-circle-plus"}});
    $j( "#btnmedsandsupplies" ).button({text: true,icons: {primary: " ui-icon-circle-plus"}});
    $j( "#btnaddmisc_ops" ).button({text: true,icons: {primary: " ui-icon-circle-plus"}});
    $j( "#btnaddmisc_srvc" ).button({text: true,icons: {primary: " ui-icon-circle-plus"}});
    $j( "#btnaddmisc_chrg" ).button({text: true,icons: {primary: " ui-icon-circle-plus"}});
    $j( "#btnadd_discount" ).button({text: true,icons: {primary: " ui-icon-circle-plus"}});
    $j( "#btnadddoctors" ).button({text: true,icons: {primary: " ui-icon-circle-plus"}});
    $j( "#btnCF2Part3" ).button({text: true,icons: {primary: " ui-icon-print"}});
    $j( "#btnCF2Part4" ).button({text: true,icons: {primary: " ui-icon-print"}});
    $j( "#btnOutMedsXLO" ).button({text: true,icons: {primary: " ui-icon-circle-plus"}});
    $j( "#btnCSFp1" ).button({text: true,icons: {primary: " ui-icon-print"}});
    $j( "#btnCSFp2" ).button({text: true,icons: {primary: " ui-icon-print"}});

    var pageSelEnc = "../../modules/billing/billing-select-enc.php?bill_type=phic";
    dialogSelEnc = $j('<div></div>')
                    .html('<iframe style="border: 0px; " src="' + pageSelEnc + '" width="100%" height=400px></iframe>')
                    .dialog({
                    autoOpen: false,
                    modal: true,
                    show: 'fade',
                    hide: 'fade',
                    height: 'auto',
                    width: '800',
                    title: 'Select Registered Person',
                    position: 'top',
                  });

  if(bill_nr){
        populateBill();
  }else{
    $j('#select-enc').click(function() {
          dialogSelEnc.dialog('open');
          return false;
  });
  }

  $j('#btnSave').click(function saveBill(){

    //added by Nick 05-27-2014
    if(isComputing){
        alert('Still computing please wait ...')
        return;
    }

    var check = '';
    var data = new Object();
    var com_details = new Array();
    var details = new Array();
    var fields = $j("#body_hsListDetails :input, #body_mdListDetails :input").serializeArray();
    var company_field = $j('.saving-com-details').serializeArray();
    //added by Nick, 1/12/2014

    OtherDiscountHospital = $j('#hospital_discount').val();
    OtherDiscountPf = $('pf_discount').innerHTML.replace(',','');
    totalHCIDiscount = $('hiDiscount').innerHTML.replace(',','');
    totalPFDiscount = $('pfDiscount').innerHTML.replace(',','');
    totalHealthInsuranceHF1 = $('hiHIC').innerHTML.replace(',','');
    totalHealthInsurancePF = $('pfHC').innerHTML.replace(',','');
    totalNet = $('netamnt').innerHTML.replace(',','');
    totalHCIDiscount = totalHCIDiscount - parseFloat(OtherDiscountHospital);

    //end nick

    data.encounter = $j('#encounter_nr').val();
    data.billdate = $j('#billdate').val();
    data.save_total_acc_charge = $j('#save_total_acc_charge').val();
    data.save_total_med_charge = $j('#save_total_med_charge').val();
    data.save_total_srv_charge = $j('#save_total_srv_charge').val();
    data.save_total_ops_charge = $j('#save_total_ops_charge').val();
    data.save_total_msc_charge = $j('#save_total_msc_charge').val();
    data.save_total_doc_charge = $j('#save_total_doc_charge').val();
    data.save_total_prevpayment = $j('#save_total_prevpayment').val();
    data.totaldiscounthf = $j('#temphcidiscount').val();
    data.totaldiscountpf = $j('#temppfdiscount').val();

    isphic = ($j('#phic').val().toUpperCase()=="NONE") ? false:true;

    if(isphic){
        data.first_rate = $j('#first_rate_amount').html().replace(',','');
        data.second_rate = $j('#second_rate_amount').html().replace(',','');
        data.first_rate_code = firstratecode;
        data.second_rate_code = (($j('#second_rate').val()!='0') ? secondratecode : '');
        if(data.first_rate <= 0){
            alert("No case rate selected");
            return;
        }
    }
    else{
        data.first_rate = null;
        data.second_rate = null;
        data.first_rate_code = null;
        data.second_rate_code = null;
    }

    data.first_hci = $j("#first_rate option:selected").attr('value_hf');
    data.first_pf = $j("#first_rate option:selected").attr('value_pf');
    data.second_hci = $j("#second_rate option:selected").attr('value_hf');
    data.second_pf = $j("#second_rate option:selected").attr('value_pf');

    data.d1coverage = D1_coverage;
    data.d2coverage = D2_coverage;
    data.d3coverage = D3_coverage;
    data.d4coverage = D4_coverage;
/*    data.D1_nr = D1_nr;
    data.D2_nr = D2_nr;
    data.D3_nr = D3_nr;
    data.D4_nr = D4_nr;
    data.D1_chrg = D1_chrg;
    data.D2_chrg = D2_chrg;
    data.D3_chrg = D3_chrg;
    data.D4_chrg = D4_chrg;*/
    data.D1_discount = D1_discount;
    data.D2_discount = D2_discount;
    data.D3_discount = D3_discount;
    data.D4_discount = D4_discount;
    //data.msDiscount = $j('#msDiscount').html().replace(',','');
    data.hcidiscount = totalHCIDiscount;
    data.pfdiscount = totalPFDiscount;
    data.hcicoverage = totalHealthInsuranceHF;
    data.pfcoverage = totalHealthInsurancePF;
    data.billdatefrom =  $j('#admission_date').val();
    data.pid =  $j('#pid').val();
    data.disc_id = $j('#save_discountid').val();
    data.disc = $j('#save_discount').val();
    data.disc_amnt = $j('#save_discount_amnt').val();
    data.prevbal = $('prev_bal').innerHTML.replace(',','');
    data.excess = totalNet;
    data.ndays = $j('#savethis').html();//addedby art
    data.accommodation_type = ((isPAYWARD())? '2':'1');
    data.OtherDiscountHospital = OtherDiscountHospital;
    data.mgh_date = ($('isdied').checked) ? $('death_date').value : $('billdate').value;

    $j.each(fields, function(i, field){
       details[field.name]= field.value;
    });

    $j.each(company_field, function(i, field){
        if(field.value > 0)
          com_details.push({
                    "enc": $j('#encounter_nr').val(),
                "comp_id": $j('#'+field.name).attr('company_id'),
                "amount" : field.value,
                "area"   : $j('#'+field.name).attr('comp_area')
          })
    });
    showLoading();

    if ($('isFinalBill').checked){
        check = '1';
        xajax_toggleMGH(data, 1);
        if((parseFloat(totalHealthInsurancePF)==0) && (isphic) ){
            var answer = confirm("Are you sure to save this billing WITHOUT DOCTOR\'s COVERAGE?");
            if(answer)
                xajax_saveThisBilling(data, check, details, com_details, process_type, isNewBorn);
            else{
                $('isFinalBill').checked = false;
                $j('#bill_status').html('');
                return false;
            }

        }else{
            xajax_saveThisBilling(data, check, details, com_details, process_type, isNewBorn);
        }
    }else{
        check = '0';
        xajax_toggleMGH(data, 0);
        xajax_saveThisBilling(data, check, details, com_details, process_type, isNewBorn);
    }


  });

    $j('#btnInsurance').click(function() {
        var enc = $j('#encounter_nr').val();
        var pid = $j('#pid').val();
        var seg_URL_APPEND = $j('#seg_URL_APPEND').val();
        var pageInsurance = '../../modules/registration_admission/seg_insurance.php'+seg_URL_APPEND+'&encounter_nr='+enc+
                            '&update=1&target=search&popUp=1&frombilling=1&bill_type=phic&pid='+pid;
        dialogInsurance = $j('<div></div>')
        .html('<iframe style="border: 0px; " src="' + pageInsurance + '" width="100%" height=600px></caiframe>')
        .dialog({
            autoOpen: true,
            modal: true,
            show: 'fade',
            hide: 'fade',
            height: 'auto',
            width: '65%',
            title: 'Insurance',
            position: 'top',
            close: function(){
                populateBill();
            },
        });
        return false;
    });


    var pageMiscSrvSupp = "billing-misc-services-tray-new.php";
    var htmlXLSO = '<iframe style="border: 0px; " src="' + pageMiscSrvSupp + '" width="100%" height=400px></iframe>';
	var dialogMiscSrvSupp = $j('<div></div>')
                .dialog({
                   autoOpen: false,
                   modal: true,
                   height: "auto",
                   width: "80%",
                   show: 'fade',
                   hide:'fade',
                   resizable: false,
                   draggable: false,
                   title: "Misc Services and Supplies",
                   position: "top",
                });

            $j('#btnaddmisc_srvc').click(function() {
                    dialogMiscSrvSupp.html(htmlXLSO);
                    dialogMiscSrvSupp.dialog('open');
                    return false;
            });

            $j('#dialogMiscServicesDelConfirm').dialog({
                autoOpen: false,
                modal: true,
                height: "auto",
                show: 'fade',
                hide:'fade',
                width: "60%",
                title: "Delete miscellaneous service",
                position: "top",
                buttons: {
                    "Yes": function()
                    {
                        jsDeleteMiscService();
                        $j(this).dialog("close");
                    },
                    "No": function()
                    {
                        $j(this).dialog("close");
                    }
                }
            });

            /*---------end Misc Services Dialog-------------*/

            /*------------ Misc charges  Dialog-------------*/
            var pageMisc = "billing-misc-chrgs-tray-new.php";
            var htmlMisc = '<iframe style="border: 0px; " src="' + pageMisc + '" width="100%" height=400px></iframe>';
            var dialogMisc = $j('<div></div>')
                .dialog({
                   autoOpen: false,
                   modal: true,
                   height: "auto",
                   width: "60%",
                   show: 'fade',
                   hide:'fade',
                   resizable: false,
                   draggable: false,
                   title: "Add Miscellaneous Charge(s)",
                   position: "top",
                });

            $j('#btnaddmisc_chrg').click(function() {
                    dialogMisc.html(htmlMisc);
                    dialogMisc.dialog('open');
                    return false;
            });

            $j('#dialogMiscChargesDelConfirm').dialog({
                autoOpen: false,
                modal: true,
                height: "auto",
                show: 'fade',
                hide:'fade',
                width: "60%",
                title: "Delete miscellaneous charge",
                position: "top",
                buttons: {
                    "Yes": function()
                    {
                        jsDeleteMiscCharge();
                        $j(this).dialog("close");
                    },
                    "No": function()
                    {
                        $j(this).dialog( "close" );
                    }
                }
            });
            /*---------end Misc charges  Dialog-------------*/

            /*------------ Drugs and Medicnes --------------*/
            var pageMeds = "billing-more-pharmaorder-new.php";
            var htmlMeds = '<iframe style="border: 0px; " src="' + pageMeds + '" width="100%" height=400px></iframe>';
            var dialogMeds = $j('<div></div>')
                .dialog({
                   autoOpen: false,
                   modal: true,
                   show: 'fade',
                   hide:'fade',
                   height: "auto",
                   width: "80%",
                   resizable: false,
                   draggable: false,
                   title: "Drugs and Meds",
                   position: "top",
                });

            $j('#btnmedsandsupplies').click(function() {
                    dialogMeds.html(htmlMeds);
                    dialogMeds.dialog('open');
                    return false;
            });
            $j('#dialogMedicineDelConfirm').dialog({
                autoOpen: false,
                modal: true,
                height: "auto",
                show: 'fade',
                hide:'fade',
                width: "60%",
                title: "Delete drugs and medicines",
                position: "top",
                buttons: {
                    "Yes": function()
                    {
                        jsDeleteMed();
                        $j(this).dialog("close");
                    },
                    "No": function()
                    {
                        $j(this).dialog("close");
                    }
                }
            });
            /*---------end Drugs and Medicnes --------------*/


            var dialogDc = $j('<div></div>')
                .html('<iframe id="discount_frame" style="border: 0px; " src="" width="100%" height=400px></iframe>')
                .dialog({
                   autoOpen: false,
                   modal: true,
                   height: "auto",
                   show: 'fade',
                   hide:'fade',
                   width: "60%",
                   resizable: false,
                   draggable: false,
                   title: "Discounts",
                   position: "top",
                   open:function(){
                    var shci_excess = $('hiEX').innerHTML;
                    var spf_excess = $('pfEX').innerHTML;
                    var pageDc = "billing-discounts.php?shci_excess="+shci_excess+"&spf_excess="+spf_excess;

                        $j('#discount_frame').attr("src",pageDc);
                   },
                   close:function(){
                        populateBill();
                   }
                });

            $j('#btnadd_discount').click(function() {
                    dialogDc.dialog('open');
                    return false;
            });


            /*--------dialog box for diagnosis and procedures (ICD and ICP)----*/

            $j('#btnDiagnosis').click(function() {
                var pid = $j('#pid').val();
                var encounter_nr = $j('#encounter_nr').val();
                var billDate = $j('#billdate').val();
                var frombilling = 1;
                var pageDiagnosis = "billing-diagnosis-procedures.php?pid="+pid+"&encounter_nr="+encounter_nr+"&frombilling="+frombilling+"&billDate="+billDate;
                var dialogDiagnosis = $j('<div></div>')
                .html('<iframe style="border: 0px; " src="' + pageDiagnosis + '" width="100%" height=400px></iframe>')
                .dialog({
                       autoOpen: true,
                   modal: true,
                   height: "auto",
                   width: "90%",
                   show: 'fade',
                   hide:'fade',
                   resizable: false,
                       draggable: false,
                   title: "Diagnosis and Procedure",
                   position: "top",
                   close: function(){
                        $j( "#icdCode" ).empty();
                        xajax_checkInsurance(encounter_nr);
                        populateBill();
                    },
                });
            });

            /*-----end---dialog box for diagnosis and procedures (ICD and ICP)---end---*/

            //added by poliam 01/04/2014
            //previous package
            $j('#btnPrevPack').click(function(){
                var pid = $j('#pid').val();
                var encounter_nr = $j('#encounter_nr').val();
                var PagePreviousPackage = "billing-prev-package.php?pid="+pid+"&encounter_nr="+encounter_nr;
                var dialogPrevPackage = $j('<div></div>').html('<iframe style="border: 0px;" src="'+PagePreviousPackage+'" width="100%" height=600px></iframe>').dialog({
                    autoOpen: true,
                    modal: true,
                    height: "auto",
                    width: "60%",
                    resizable: false,
                    draggable: false,
                    title: "Previous Packages",
                    position: "top",
                });


            });
            //ended by poliam 01/04/2014
            /*-------------Accommodation Dialog-------------*/
            resetAccommDialogForm(); //on page load reset accommodation dialog form
            $j('#btnaccommodation').click(function() {
                $j( "#dialogAcc").dialog({
                    autoOpen: true,
                    modal:true,
                    height: "auto",
                    show: 'fade',
                   hide:'fade',
                    width: "60%",
                    resizable: false,
                    draggable: false,
                    // show: "blind",
                    // hide: "explode",
                    title: "More Accomodation Charges",
                    position: "top",
                    buttons: {
                        "Save": function()
                        {
                            if(isValidAccomForm()){
                                jsSaveAccommodation();
                                $j( this ).dialog( "close" );
                            }
                        },
                        "Cancel": function()
                        {
                    $j( this ).dialog( "close" );
                        }
                    },
                    close: function()
                    {
                      resetAccommDialogForm();
                    }
                });

                return false;
            });

            $j('#occupydateto').datepicker({
                    dateFormat: 'mm/dd/yy',
                    changeMonth: true,
                    changeYear: true
            });

            $j('#occupydatefrom').datepicker({
                    dateFormat: 'mm/dd/yy',
                    changeMonth: true,
                    changeYear: true
            });

            $j('#occupydatefrom').bind('keypress keydown', function() {
                return false;
            });

            $j('#occupydateto').bind('keypress keydown', function() {
                return false;
            });

            $j('#dialogAccDelConfirm').dialog({
                autoOpen: false,
                modal: true,
                height: "auto",
                width: "60%",
                show: 'fade',
                hide:'fade',
                resizable: false,
                draggable: false,
                title: "Delete accommodation",
                position: "top",
                buttons: {
                    "Yes": function()
                    {
                        jsDeleteAccommodation();
                        $j(this).dialog("close");
                    },
                    "No": function()
                    {
                        $j( this ).dialog( "close" );
                    }
                }
            });
            /*-----------end Accommodation Dialog-----------*/

            /*----------Operating Room Accomodation Charges Dialog--------------*/
            $j('#btnOPaccommodation').click(function() {
                showOperatingRoomAcc();
                $j( "#dialogOR").dialog({
                    autoOpen: true,
                    modal:true,
                    height: "auto",
                    width: "auto",
                    resizable: false,
                    draggable: false,
                    show: 'fade',
                    hide: "fade",
                    title: "Operating Room Accomodation Charges",
                    position: "top",
                    buttons: {
                        "Save": function()
                        {
                            saveORAccommodation();
                            // $j( this ).dialog( "close" );
                        },
                        "Cancel": function()
                        {
                    $j( this ).dialog( "close" );
                        }
                    },
                    close: function()
                    {
                      clearORACFields();
                    }
                });
                return false;
            });
            /*----end----Operating Room Accomodation Charges Dialog------end-----*/

            /*---------------Procedure List Dialog Box--------------------------*/
            $j('#ops_selected').click(function() {
                clearORACFields();
                initProcedureList();
                $j( "#dialogProcedureList").dialog({
                    autoOpen: true,
                    modal:true,
                    height: "auto",
                    width: "80%",
                    resizable: false,
                    draggable: false,
                    show: 'fade',
                    hide: "fade",
                    title: "Procedures with Accomodation",
                    position: "top",
                    close: function(event, ui){
                        calcTotRVU();
                    },
                });
                return false;
            });
            /*-----end-------Procedure List Dialog Box----------end-------------*/

            /*------------Add Doctors Dialog Box-------------------------------*/
            $j('#btnadddoctors').click(function() {
                clearProfDialog();
                xajax_setDoctors(1,0,0);
                xajax_setRoleArea(1);
                xajax_setOptionRoleLevel();
                $j( "#dialogAddDoc").dialog({
                    autoOpen: true,
                    modal:true,
                    height: "auto",
                    width: "520px",
                    resizable: false,
                    draggable: false,
                    show: 'fade',
                    hide: "fade",
                    title: "Add Doctor",
                    position: "top",
                    close: function(event, ui){$j("#hasAnes").hide();},
                    buttons: {
                        "Save": function()
                        {
                            addDoctor();
                            $j("#hasAnes").hide();
                        },
                        "Cancel": function()
                        {
                            $j("#hasAnes").hide();
                            $j( this ).dialog( "close" );
                        }
                    }
                });
                return false;
            });

            $j('#ops4pf_selected').click(function() {
                initProcedureList();
                tmpRVU = 0;
                drCharge = 0;
                tmpOPDetails = [];
                $j( "#dialogProcedureList").dialog({
                    autoOpen: true,
                    modal:true,
                    height: "auto",
                    width: "60%",
                    resizable: false,
                    draggable: false,
                    show: 'fade',
                    hide: "fade",
                    title: "Select Procedures done by Doctor",
                    position: "top",
                    close: function(event, ui){addDrCharge();},
                });
                return false;
            });
            //added by art 01/28/2014
            $j('#charge').keyup(function(e){
                if (e.keyCode == 13) {
                   addDoctor();
                };
            });
            //end art
            /*--------end--------Add Doctors Dialog Box------------end----------------*/

            $j('#imgBtnDelAcc').click(function(){
                    $j('#btnaccommodation').dialog('open');
            });

            $j('#opwardlist').on('change', function() {
                var val = $j("#opwardlist").val();
                jsOpAccChrgOptionsChange('opwardlist',val);
                });

            $j('#orlist').on('change', function() {
                var val = $j("#orlist").val();
                jsOpAccChrgOptionsChange('orlist',val);
            });

            $j('#first_rate').on('change', function() {

                changeCase('1');
            });

            $j('#second_rate').on('change', function() {
                changeCase('2');
            });

    $j('#billdate_display').datetimepicker({
        dateFormat: 'M d, yy',
        timeFormat: 'hh:mm tt',
        onSelect: function(selectedDate){
            $j('#billdate').val(toDate(new Date(selectedDate), "yyyy-mm-dd hh:mn")+':00');
        },
        onClose: function(){
            populateBill();
        },
    });

    $j('#death_date').datetimepicker({
        dateFormat: 'M d, yy',
        timeFormat: 'hh:mm tt',
        onSelect: function(selectedDate){
            $j('#deathdate').val(toDate(new Date(selectedDate), "yyyy-mm-dd hh:mn")+':00');
        },
        onClose:function(selectedDate){
            deathdate = toDate(new Date(selectedDate), "yyyy-mm-dd hh:mn")+':00';
            var enc = $j('#encounter_nr').val();
            var pid = $j('#pid').val();
            xajax_setDeathDate(pid,enc,deathdate);
            populateBill();
        }
    });

    if(enc!=''){
    }else{
        $j('#select-enc').click();
    }


    $j('#btnDelete').click(function() {
        var old_billnr = $j('#bill_nr').val();
        var enc_nr = $j('#encounter_nr').val();
        var message = "Do you really want to delete this billing?\nClick OK to delete, CANCEL otherwise!";
        var ret_val = false;
        if (old_billnr != "") {
            ret_val = confirm(message);
            if (ret_val == true) {

                xajax_deleteBilling(old_billnr, enc_nr);
                xajax_clearBilling();

            }
        }
        else {
            alert("No billing to delete!");
        }
    });

    $j('#btnOutMedsXLO').click(function() {
        var enc = $j('#encounter_nr').val();
        xajax_getOutMedsXLO(enc);
        $j( '#dialogOutMedsXLO').dialog({
            autoOpen: true,
            modal:true,
            height: 'auto',
            width: '300',
            resizable: false,
            draggable: false,
            show: 'fade',
            hide: 'fade',
            title: 'Enter Outside Medicnes And XLO Amount',
            position: "top",
            buttons: {
                "Save": function() {
                    xajax_saveOutMedsXLO(enc,$j('#meds_total').val().replace(',',''),$j('#xlo_total').val().replace(',',''));
                    $j( this ).dialog( "close" );
                },
                "Cancel": function() {
                    $j( this ).dialog( "close" );
                },
            }
        });
    });


    $j('#doccvrg').click(function(){
        if(!$('isFinalBill').checked){
            $j( '#coverage-dialog').dialog({
                autoOpen: true,
                modal:true,
                height: 'auto',
                width: '60%',
                resizable: false,
                draggable: false,
                show: 'fade',
                hide: 'fade',
                title: 'Coverage Distribution',
                position: "top",
                buttons: {
                    "Save": function() {
                        if(parseFloat($j('#phic-max-PF').html().replace(',','')) < 0 || parseFloat($j('#total-excess').html().replace(',','')) < 0 ){
                            alert("Total PHIC PF Coverage or The total excess is negative. Please Distribute The PF Properly for Data Consistency. \n Thank You.");
                            return false;
                        }else{
                            saveDoctorCoverage();
                        }
                    },
                    "Cancel": function() {
                        $j( this ).dialog( "close" );
                    },
                },
                close: function(event, ui){
                    populateBill();
                },
                open:function(){
                    $j(".numeric").numeric();
                    calculateDetails();
                    assignDoctoTable();
                }
            });
        }
    });

$j('#hcicvrg').click(function(){
        enc = $('encounter_nr').value;
        if(!$('isFinalBill').checked){
            $j( '#coverage-dialog-hci').dialog({
                autoOpen: true,
                modal:true,
                height: 'auto',
                width: '60%',
                resizable: false,
                draggable: false,
                show: 'fade',
                hide: 'fade',
                title: 'Coverage Distribution',
                position: "top",
                buttons: {
                    "Save": function() {
                        if(parseFloat($j('#hf_phic').html().replace(',', '')) > parseFloat($j('#hf_phic_limit').html().replace(',', ''))){
                            alert("Total PHIC HCI Coverage exceeds the limit. Please Distribute The Coverage Properly for Data Consistency. \n Thank You.");
                            return false;
                        }
                        else if(parseFloat($j('#HC_total').html().replace(',', '')) < 0){
                            alert("The total excess is negative. Please Distribute The Coverage Properly for Data Consistency. \n Thank You.");
                            return false;
                        }
                        else if(parseFloat($j('#hf_phic').html().replace(',', '')) < 0){
                            alert("Total PHIC HCI Coverage is negative. Please Distribute The Coverage Properly for Data Consistency. \n Thank You.");
                            return false;
                        }
                        else
                            saveHospitalCoverage();
                    },
                    "Cancel": function() {
                        $j( this ).dialog( "close" );
                    },
                },
                close: function(event, ui){
                    populateBill();
                },
                open: function(){
                    // if(count == 0){
                        xajax_populateAdjCoverage(enc, acc_computed, miscServices_computed, med_computed, miscCharges_computed, ops_computed);
                        // xajax_populateHMOValue(enc);
                        count = 1;
                    // }
                }

            });
        }
    });
//added by daryl
//10/05/14
//dialog box for discount HCI
$j('#hcidiscount').click(function(){
        enc = $('encounter_nr').value;
        if(!$('isFinalBill').checked){
            $j( '#discount-dialog-hci').dialog({
                autoOpen: true,
                modal:true,
                height: 'auto',
                width: '60%',
                resizable: false,
                draggable: false,
                show: 'fade',
                hide: 'fade',
                title: 'Discount Distribution',
                position: "top",
                buttons: {
                    "Save": function() {
                       saveHospitalDiscount();
                    },
                    "Cancel": function() {
                        $j( this ).dialog( "close" );
                    },
                },
                close: function(event, ui){
                    populateBill();
                },
                open: function(){
                        xajax_populateAdjCoverage(enc, acc_computed, miscServices_computed, med_computed, miscCharges_computed, ops_computed);
                        xajax_populateHFdiscount(enc, acc_computed, miscServices_computed, med_computed, miscCharges_computed, ops_computed);
                }

            });
        }
    });

//--------------------------------------------
//dialog box for discount doctor
$j('#proffdiscount').click(function(){
        enc = $('encounter_nr').value;
        if(!$('isFinalBill').checked){
            $j( '#coverage-dialog-disc').dialog({
                autoOpen: true,
                modal:true,
                height: 'auto',
                width: '60%',
                resizable: false,
                draggable: false,
                show: 'fade',
                hide: 'fade',
                title: 'Discount Distribution',
                position: "top",
                buttons: {
                    "Save": function() {
                            saveDoctorCoverage_disc();

                    },
                    "Cancel": function() {
                        $j( this ).dialog( "close" );
                    },
                },
                close: function(event, ui){
                    populateBill();
                },
                open: function(){
                    $j(".numeric").numeric();
                    calculateDetails();
                    assignDoctoTable_disc();

                }

            });
        }
    });
//ended by daryl

    $j('#rate_type').on('change', function() {
        calcDrCharge();
        //alert('sa');
    });

    // Added by Jeff 04-04-18
    $j('#btnCSFp1').click(function() {
        var billnr = $j('#bill_nr').val();
        var enc_no = $j('#enc').val();
        var pid =  $j('#pid').val();
        var billdate = $j('#billdate').val();
        var rawUrlData = {reportid:'csfp1',
                      repformat:'pdf',
                      param:{enc_no:enc_no,billnr:billnr,pid:pid}};
        var urlParams = $j.param(rawUrlData);
        window.open('../reports/show_report.php?'+ urlParams, '_blank');
    });

    $j('#btnCSFp2').click(function() {
        var billnr = $j('#bill_nr').val();
        var enc_no = $j('#enc').val();
        var pid =  $j('#pid').val();
        var billdate = $j('#billdate').val();
        var rawUrlData = {reportid:'csfp2',
                      repformat:'pdf',
                      param:{enc_no:enc_no,billnr:billnr,pid:pid}};
        var urlParams = $j.param(rawUrlData);
        window.open('../reports/show_report.php?'+ urlParams, '_blank');
    });
    // END jeff ---
}


function saveDoctorCoverage()
{
    var data = new Array();
    var data_com = new Array();
    var fields = $j('.save-pf-details').serializeArray();
    var company_field = $j('.save-com-doc').serializeArray();

    data["refno"] = $j('#adj_refno').val();
    data["enc"] = $j('#encounter_nr').val();

    $j.each(fields, function(i, field){
        data[field.name] = parseFloat(field.value.replace(',',''));
    });
    $j.each(company_field, function(i, field){
        var itemcode = $j('#'+field.name).attr("itemcode");
        var dr_nr = $j('#'+field.name).attr("dr_nr");
        data_com.push({"enc":data["enc"], "amount":parseFloat(field.value.replace(',','')), "itemcode":itemcode, "dr_nr":dr_nr});
    });

    xajax_saveDoctorCoverage(data);
    xajax_saveComdetails(data_com,'doc');
}

//added by daryl
//saving in doctors discounts
function saveDoctorCoverage_disc()
{
    var data_discount = new Array();
    var discount_field = $j('.save-pf-discount').serializeArray();
    data_discount["refno"] = $j('#adj_refno_pf').val();
     $j.each(discount_field, function(i, field){
        data_discount[field.name] = parseFloat(field.value.replace(',',''));
    });
    xajax_saveDiscountPF(data_discount);

}
//ended by daryl

function assignMemCategDesc(categ_desc, id, hist)
{
    if ($j('#phic').val().toUpperCase() == "NONE") {
         categ_desc = "NONE ASSIGNED";
     }else{
         categ_desc = (categ_desc == "" ? "NONE ASSIGNED" : categ_desc);
         $j('#memcategory_id').val(id);
     }

    $('billcol_01').colspan = "1";
    $('categ_col').style.display = "inline";
     categ_desc = (categ_desc == "" ? "NONE ASSIGNED" : categ_desc);
    $('mcategdesc').innerHTML = categ_desc;
    $('mcategdesc').style.background = "red";
}

function assignInsurance(nr)
{
    $j('#phic').val(nr);
    dialogInsurance.dialog('close');
}


function jsAddRefNo(tagId, ref){
    var refno = ref.refno;
    var source = ref.source;
    var hd = '<input type="hidden" id="'+source+'" name="'+source+'" value="'+refno+'"/>';
   $(tagId).innerHTML += hd;
}
//added by poliam 01/04/2014
function jsOnchangeConfineType() {
    var enc = $j('#encounter_nr').val();
    var type = $('confineTypeOption').options[$('confineTypeOption').selectedIndex].value;
    var bill_dte = $('billdate').value;
    var classify_id = $j('#classify_id').val();
    var create_id =  $j('#classify_id').val();

    if(enc != '') {
        //alert("selected options ="+ $('confineTypeOption').selectedIndex+ "\n selected value = "+ $('confineTypeOption').value );
        xajax_setConfinementType(enc, type, classify_id, create_id, bill_dte);
    } else {
        alert('Please select patient first');
    }
}

function js_setOption(tagId, value){
   var e1Targe = '#'+tagId;

   $j(e1Targe).val(value);
}// end of function js_setOption


//ended by poliam 01/04/2014
/*-------------Accommodation Dialog-------------*/
function updateValidationMessage(obj, msg){
    $(obj).style.display = '';
    $(obj).innerHTML += (msg +'<br>');
}//end updateValidationMessage

function jsClearList(tagId){
    $(tagId).innerHTML = '';
}

function disable(){
    var hide = '';

    if ($('isFinalBill').checked){
        hide = 'display:none';
    }else if (b_new){
        hide = 'display:none';
    }
}


/*-------------Accommodation Dialog-------------*/
var cur_accom_type = '';
function resetAccommDialogForm(){
    xajax_setWardOptions();
    assignRmRate('');
    jsClearList('validationAccomMsgBox');
    $('ward_nr').value = '';
    $('rm_nr').value = '';
    $('validationAccomMsgBox').style.display = 'none';
}//end resetAccommDialogForm

function assignRmRate(rmrate) {
    $('rate').value = rmrate;
}//end assignRmRate

function jsAccOptionsChange(obj, value){
    assignRmRate('');
    if(obj.id == 'wardlist') {
        $('ward_nr').value = value;
        if(Number(value)>0){
            xajax_setWardRooms(value);
        }
        else{
            js_ClearOptions('roomlist');
            js_AddOptions('roomlist','- Select Room -', 0);
        }
    }
    else {
        $('rm_nr').value = value;
        if(Number(value)>0){
            var room_info = new Object();
            room_info.ward_nr = $('ward_nr').value;
            room_info.room_nr = value;
            xajax_getAccommodationRate(room_info);
        }

    }
}//end jsAccOptionsChange

// function js_AddOptions(tagId, text, value){
//     var elTarget = $(tagId);
//     if(elTarget){
//         var opt = new Option(text, value);
//         opt.id = value;
//         elTarget.appendChild(opt);
//     }
//     var optionsList = elTarget.getElementsByTagName('OPTION');
// }//end of function js_AddOption

// function js_ClearOptions(tagId){
//     var optionsList, el=$(tagId);
//     if(el){
//         optionsList = el.getElementsByTagName('OPTION');
//         for(var i=optionsList.length-1; i >=0 ; i--){
//             optionsList[i].parentNode.removeChild(optionsList[i]);
//         }
//     }
// }//end of function js_ClearOptions

function isValidAccomForm(){
    var ward_nr = Number($('ward_nr').value.trim());
    var room_nr = Number($('rm_nr').value.trim());
    var encounter_nr = Number($('acc_enc_nr').value.trim());
    var room_rate = Number($('rate').value.trim());
    var objBox = 'validationAccomMsgBox';
    jsClearList(objBox);
    if(ward_nr<=0){
        updateValidationMessage(objBox, 'Please select a ward');
    }
    if(room_nr<=0){
        updateValidationMessage(objBox, 'Please select a room');
    }
    if(room_rate<=0){
        updateValidationMessage(objBox, 'Accommodation charge must be nonzero');
    }
    if($(objBox).style.display == ''){
      return false;
    }
    else {
      return true;
    }
}//end isValidAccomForm

function jsSetupAccommodationForm(start_date, end_date){
    var min_date = new Date(start_date);
    var max_date = new Date(end_date);
    if(min_date >= max_date ){
        $('btnaccommodation').disabled = 'disabled';
    }else{
        jQuery('#btnaccommodation').removeAttr('disabled');
        jQuery('#occupydatefrom').datepicker('option', 'minDate', min_date);
        jQuery('#occupydatefrom').datepicker('option', 'maxDate', min_date);
        jQuery('#occupydatefrom').datepicker('setDate', min_date);
        jQuery('#occupydateto').datepicker('option', 'minDate', min_date);
        jQuery('#occupydateto').datepicker('option', 'maxDate', max_date);
        jQuery('#occupydateto').datepicker('setDate', min_date);
    }
}

function jsSaveAccommodation(){
    var data = new Object();

    data.ward_nr = Number($('ward_nr').value.trim());
    data.room_nr = Number($('rm_nr').value.trim());
    data.encounter_nr =  Number($('acc_enc_nr').value.trim());
    data.room_rate = Number($('rate').value.trim());
    data.datefrom = $('occupydatefrom').value;
    data.dateto = $('occupydateto').value;

    var bill_dt = $('billdate').value;
    var fields = jQuery("#faccbox :input").serializeArray();


    xajax_saveAccommodation(data, bill_dt);
}//end jsSaveAccommodation

function jsRecomputeAccommodation(){
    var details = new Object();
    details.bill_dt = $('billdate').value;
    details.bill_frmdte = $('date_admitted').value;
    details.encounter_nr = Number($('encounter_nr').value.trim());
    details.death_date = '';
    if($('isdied').checked){
        details.death_date = $('deathdate').value;
    }
    xajax_populateAccommodation(details);
}//jsRecomputeAccommodation

function showAccommodationList(bshow) {
    $('accommodation_div').style.display = 'none';
    if (bshow){
        $('accommodation_div').style.display = '';
    }
}//showAccommodationList

function jsAccommodationList(data, total_charge, b_new){
    var type_nr = Number(data.type_nr);
    var room = data.room;
    var ward = data.ward;
    var rm_nr = data.location_nr;
    var type_desc = data.name;
    var days_count = data.days_stay;
    var rm_rate = data.room_rate;
    var source = data.source;
    var srcRow, prefx = '';
    var hide = '';
    var acc_type = data.accommodation_type;

    if ($('isFinalBill').checked){
        hide = 'display:"none"';
    }else if (b_new){
        hide = 'display:"none"';
    }

    $j('#accomodation_type').val(data.accommodation_type);

    if (!isNaN(type_nr)) {
        if (source=='BL'){
            prefx = '<td align="center" width="3%"><img src="../../images/btn_delitem.gif" class="imgdelete" style="border-right:hidden; cursor:pointer; '+hide+';" onclick="promptDelAccom('+ward+',\''+source+'\',\''+room+'\')"></td>'+
                    '<td style="border-left:hidden" width="52%">'+
                        'Room No. :'+rm_nr+'<br>'+
                        'Room Type:'+type_desc+
                    '</td>';
            }else{
                 prefx = '<td align="center" width="3%"><img src="../../images/btn_delitem.gif" class="imgdelete" style="border-right:hidden; cursor:pointer; display:'+b_new+';" onclick="promptDelAccom('+ward+',\''+source+'\',\''+room+'\')"></td>'+
                    '<td style="border-left:hidden" width="52%">'+
                        'Room No. :'+rm_nr+'<br>'+
                        'Room Type:'+type_desc+
                    '</td>';
                }
        var days_label = 'Day';
        if(Number(days_count)>1)
            days_label += 's';
        srcRow = '<tr id="type_'+type_nr+'_'+source+'">'+prefx+
                    '<td width="15%" align="center">'+
                        days_count+' '+days_label+' <br>'+
                    '</td >'+
                    '<td width="15%" align="right">'+rm_rate
                    +'</td>'+
                    '<td width="15%" align="right">'+total_charge
                    +'</td>'+
                '</tr>';
    }else{
        srcRow = '<tr>'+
                    '<td colspan="2" width="55%">No accommodation charged!</td>'+
                    '<td width="15%">&nbsp;</td >'+
                    '<td width="15%">&nbsp;</td>'+
                    '<td width="15%">&nbsp;</td>'+
                '</tr>';
    }
    $('body_accListDetails').innerHTML += srcRow;

}

function setAccSubTotal(accAp,excess){
    $('accAP').innerHTML = accAp; // Display actual price charge


    if(excess)
        excess = excess;
    else
        excess = 0;

    accexcess = parseFloat(excess);
    acc_computed = parseFloat(accAp.replace(",",""));
     $j('#save_total_acc_charge').val(acc_computed);
}//end setAccSubTotal

function promptDelAccom(id, type, rm_type){
    $('delAccomType').value = type;
    $('room').value = id;
    $('ward').value = rm_type;
    jQuery('#dialogAccDelConfirm').dialog('open');
}//end promptDelAccom

function jsDeleteAccommodation(){
    var room_info = new Object();
    room_info.encounter_nr = Number($('acc_enc_nr').value.trim());
    room_info.accom_type = $('delAccomType').value;
    room_info.room_type = $('room').value;
    room_info.ward_type = $('ward').value;
    xajax_delAccommodation(room_info);
}//end jsDeleteAccommodation
/*----------------end Accommodation Dialog------------------------------*/

/*----------------Miscellaneous Service(s)-------------*/
function addMiscService(info) {
    if(info.source=='Miscellaneous'){
        xajax_chargeMiscService(info,'xlo');
    }else{
        xajax_chargePharmaSupply(info,'xlo');
    }
}//end addMiscService

function jsHospitalServices(obj, servCharge, b_new){
    info = JSON.parse(obj);
    var prefx = '';
    var servCode = info.srv_code.trim();
    var servDesc = info.srv_desc.trim();
    var servQty = info.qty;
    var servPrice = info.srv_price;
    var servProvider = info.source_code;
    var grpDesc = info.grp_desc;
    var refno = info.ref_nr;
    var hidden = '';
    var hide = '';

    if ($('isFinalBill').checked){
        hide = 'display:"none"';
    }else if (b_new){
        hide = 'display:"none"';
    }

    var srcRow, onClickAction='', source='', provider ='';
    if (isNaN(servProvider)) {
        switch (servProvider) {
            case 'LB':
                provider = 'LAB - '+ grpDesc;
                prefx = '<td align="center" width="3%"></td><td width="35%">'+servDesc+'</td>';
                break;
            case 'RD':
                provider = 'RAD - '+ grpDesc;
                prefx = '<td align="center" width="3%"></td><td width="35%">'+servDesc+'</td>';
                break;
            case 'SU':
                provider = grpDesc;
                prefx = '<td align="center" width="3%"></td><td width="35%">'+servDesc+'</td>';
                break;
            case 'MS':
                source = 'Pharmacy';
                provider = grpDesc;
                prefx = '<td align="center" width="3%">'
                            +'<img src="../../images/btn_delitem.gif" class="imgdelete" '
                            +'style="border-right:hidden; cursor:pointer; '+hide
                                +';" onclick="promptDelMiscService(\''
                                +servCode+'\', \''+source+'\')"></td>'+
                        '<td style="border-left:hidden" width="35%">'+servDesc+'</td>';
                break;
            case 'OA':
                source = 'Miscellaneous';
                provider = grpDesc;
                prefx = '<td align="center" width="3%">'
                            +'<img src="../../images/btn_delitem.gif"  class="imgdelete" '
                            +'style="border-right:hidden; cursor:pointer; '+hide
                                +';" onclick="promptDelMiscService(\''
                                +servCode+'\', \''+source+'\')"></td>'+
                        '<td style="border-left:hidden" width="35%">'+servDesc+'</td>';
                break;
        }

        if(!info.same){
            hidden = '<div id="xlo_hidden_inputs" style="display:none">'+
                        '<input type="hidden" id="xlo_'+refno+'" name="xlo_'+refno+'" value="'+refno+'_'+servProvider+'"/>'+
                     '</div>';
        }

        srcRow = '<tr id="code_'+servCode+'">'
                    +prefx+
                    '<td width="17%" align="left">'+provider+'</td>'+
                    '<td width="15%" align="center">'+servQty+'</td>'+
                    '<td width="15%" align="right">'+servPrice+'</td>'+
                    '<td width="15%" align="right">'+servCharge+'</td>'+
                    hidden+
                 '</tr>';
    } else {
        srcRow = '<tr>'+
                    '<td colspan="2" width="*">No hospital services charged!</td>'+
                    '<td width="17%">&nbsp;</td>'+
                    '<td width="15%">&nbsp;</td>'+
                    '<td width="15%">&nbsp;</td>'+
                    '<td width="15%">&nbsp;</td>'+
                 '</tr>';
    }

    $('body_hsListDetails').innerHTML += srcRow;
}//end of jsHospitalServices

function promptDelMiscService(serv_code, source){
    $('delMiscServCode').value = serv_code;
    $('delSource').value = source;
    jQuery('#dialogMiscServicesDelConfirm').dialog('open');

}//end promptDelMiscService

function jsDeleteMiscService(){
    var details = new Object();
    details.encounter_nr = Number($('encounter_nr').value.trim());
    details.bill_dt = $('billdate').value;
    details.bill_frmdte = $('admission_dte').value;
    details.serv_code = Number($('delMiscServCode').value);
    var source = $('delSource').value;
    details.tbl_loc = 'xlo';
    if(source=='Pharmacy'){
        xajax_delPharmaSupply(details,'xlo');
    }else{
        xajax_delMiscService(details);
    }
}//end jsDeleteMiscService

function jsInventoryStockOut(data) {
    xajax_inventoryStockOut(data);
}

function jsInventoryStockIn(data) {
    xajax_inventoryStockIn(data);
}

//populate all fields...
function jsRecomputeServices(area){

    var enc = $j('#encounter_nr').val();
    var bill_dte = $j('#billdate').val();
    var bill_frmdte = $j('#admission_dte').val();
    var bill_nr = $j('#bill_nr').val();
    var pid = $j('#pid').val();
    if($('isdied').checked){
        deathdate = $j('#deathdate').val();
    }else{
        deathdate = '';
    }


    if(area=='xlo'){
        xajax_populateXLO(enc,bill_dte,bill_frmdte,deathdate);
    }else if (area=='meds') {

        xajax_populateMeds(enc,bill_dte,bill_frmdte,deathdate);
    }else if (area=='misc'){
        xajax_populateMisc(enc,bill_dte,bill_frmdte,deathdate);
    }else if(area=='op'){
        xajax_getBilledOps(enc,bill_dte,bill_frmdte,deathdate);
    }
    xajax_populateBill(enc,pid,bill_dte,bill_frmdte,deathdate,firstratecode,secondratecode);

}//end jsRecomputeServices

function setMiscServices(hsAP){
    $('hsAP').innerHTML = hsAP;
    miscServices_computed = parseFloat(hsAP.replace(",",""));
    $j('#save_total_srv_charge').val( miscServices_computed);
}//end setHospitalServices
/*----------------end Miscellaneous Service(s)-------------*/

/*------------------- Drugs & Medicines -------------------*/
function jsRecomputeMeds(){
    var details = new Object();
    details.encounter_nr = Number($('encounter_nr').value.trim());
    details.bill_dt = $('billdate').value;
    details.bill_frmdte = $('date_admitted').value;

    xajax_populateMeds(details);
}//end jsRecomputeMeds

function jsMedicineList(obj, b_new) {

    var info = JSON.parse(obj);
    var tagId = 'body_mdListDetails';
    var refno = info.ref_nr;
    var bestellnum = info.srv_code;
    var artikelname = info.srv_desc;
    var generic = info.srv_generic;
    var itemqty = info.qty;
    var itemprice = info.srv_price;
    var acPrice = info.itemcharge;
    var flag = info.flag;
    var unused = info.unused;
    var unused_amnt = parseFloat(info.unused_amnt);
    var unused_qty = info.unused_qty;
    var source = info.src;
    var srcRow, sMsg;
    var hidden ='';
    var servProvider;
    var hide, desc;

    if ($('isFinalBill').checked){
        hide = 'display:"none"';
    }else if (b_new){
        hide = 'display:"none"';
    }

    isphic = ($j('#phic').val().toUpperCase()=="NONE") ? false:true;

    if(unused=='1' && isphic){
       desc = artikelname+' <span style="color:#ff002a;font-weight:bold">(Unused Blood ('+unused_qty+') )</span>';
    } else {
        desc = artikelname+'('+generic+')';
    }

    //replace apostrophe to be recognized
    artikelname = artikelname.replace('\'', '\\\'');

    if (bestellnum) {

        if (source=='Order'){
            srcRow = '<tr id="code_'+bestellnum+'"><td align="center" width="3%"><img src="../../images/btn_delitem.gif" class="imgdelete" style="border-right:hidden; cursor:pointer; '+hide+'" onclick="promptDelSupply('+bestellnum+')"></td>'+
                        '<td style="border-left:hidden" width="52%">'+desc+'</td>';
            servProvider = 'OR';
        }else{
            srcRow = '<tr><td width="3%"></td><td width="52%">'+desc+'</td>';
            servProvider = 'PH';
        }

        if(!info.same){
            hidden = '<td id="meds_hidden_inputs" style="display:none">'+
                        '<input type="hidden" id="md_'+refno+'" name="md_'+refno+'" value="'+refno+'_'+servProvider+'"/>'+
                     '</td>';
        }

        srcRow += '<td width="15%" align="center">'+itemqty+'</td>'+
                    '<td width="15%" align="right">'+itemprice+'</td>'+
                    '<td width="15%" align="right">'+acPrice+'</td>'+
                    hidden+
                    '</tr>';
    } else {
        sMsg = "No medicines charged!";
        srcRow = '<tr>'+
                    '<td colspan="2" width="55%">'+sMsg+'</td>'+
                    '<td width="15%">&nbsp;</td>'+
                    '<td width="15%">&nbsp;</td>'+
                    '<td width="15%">&nbsp;</td>'+
                '</tr>';
    }
    $(tagId).innerHTML += srcRow;
}//end jsMedicineList

function setMedicine(medAP){
    $('medAP').innerHTML = medAP;
    med_computed = parseFloat(medAP.replace(",",""));
    $j('#save_total_med_charge').val(med_computed);
}// end of setMedicine

function addMoreMedicine(details){
    xajax_chargePharmaSupply(details,'meds');
}//end addMoreMedicine

function promptDelSupply(code, name){
    $('delMedCode').value = code;
    jQuery('#dialogMedicineDelConfirm').dialog('open');
    }

function jsDeleteMed(){
    var details = new Object();
    details.encounter_nr = Number($('encounter_nr').value.trim());
    details.bill_dt = $('billdate').value;
    details.bill_frmdte = $('date_admitted').value;
    details.serv_code = $('delMedCode').value;
    details.tbl_loc = 'med';
    //get quantity of the item
    details.quantity = $('code_' + details.serv_code).childNodes[2].innerHTML;
    xajax_delPharmaSupply(details,'meds');
}
/*-------------------end Drugs & Medicines ----------------*/

/*------------------- Miscellaneous Charges ---------------*/
function jsRecomputeMiscCharges(){
    var enc = $j('#encounter_nr').val();
    var bill_dte = $j('#billdate').val();
    var bill_frmdte = $j('#admission_dte').val();
    var bill_nr = $j('#bill_nr').val();
    var pid = $j('#pid').val();
    if($('isdied').checked){
        deathdate = $j('#deathdate').val();
    }else{
        deathdate = '';
    }

    xajax_populateBill(enc,pid,bill_dte,bill_frmdte,deathdate,firstratecode,secondratecode);
}//end jsRecomputeMiscCharges

function jsMiscellaneousList(obj, total, b_new) {
    var details = JSON.parse(obj);
    var code = details.code.trim();
    var refno = details.refno;
    var name = details.name.trim();
    var description = ((details.desc!='null')? '' : details.desc);
    var qty = details.qty;
    var misc_chrg = details.chrg;
    var srcRow;
    var hide;

    if ($('isFinalBill').checked){
        hide = 'display:"none"';
    }else if (b_new){
        hide = 'display:"none"';
    }

    if (code) {

            srcRow = '<tr id="code_'+code+'">'+
                    '<td align="center" width="3%"><img src="../../images/btn_delitem.gif" class="imgdelete" style="border-right:hidden; cursor:pointer; '+hide+'" onclick="promptDelMiscChrg(\''+code+'\', \''+name+'\')"></td>'+
                    '<td style="border-left:hidden" width="52%"><span>'+name+'</span><br/><span class="description">'+description+'</span></td>';

        srcRow += '<td width="15%" align="center">'+qty+'</td>'+
                     '<input id="ref'+details.code+'_'+details.refno+'" type="hidden" value="'+details.refno+'">'+
                    '<td width="15%" align="right">'+misc_chrg+'</td>'+
                    '<td width="15%" align="right">'+total+'</td>'+
                 '</tr>';
    }else {
        srcRow = '<tr>'+
                    '<td colspan="2" width="*">No miscellaneous charges!</td>'+
                    '<td width="15%">&nbsp;</td>'+
                    '<td width="15%">&nbsp;</td>'+
                    '<td width="15%">&nbsp;</td>'+
                 '</tr>';
    }

    $('body_mscListDetails').innerHTML += srcRow;
}//end jsMiscellaneousList

function setMiscCharges(McharAp){
    $('mscAP').innerHTML = McharAp;

    miscCharges_computed = parseFloat(McharAp.replace(",",""));
    $j('#save_total_msc_charge').val(miscCharges_computed);
}//end setMiscCharges

function promptDelMiscChrg(code, name){
    $('delMiscChargeName').innerHTML = name;
    $('delMiscChargeCode').value = code;

    jQuery('#dialogMiscChargesDelConfirm').dialog('open');
}//end promptDelMiscChrg

function jsDeleteMiscCharge(){
    var data = new Object();
    data.encounter_nr = Number($('encounter_nr').value.trim());
    data.bill_dt = $('billdate').value;
    data.bill_frmdte = $('admission_dte').value;
    data.code = $('delMiscChargeCode').value;
    xajax_delMiscChrg(data);
}//end jsDeleteMiscCharge

function addMiscChrg(details){
    xajax_chargeMiscChrg(details);
}//end addMiscChrg

/*---------------end  Miscellaneous Charges ---------------*/

/*--------------------Operating Room Accomodation Charges---------------------------*/
function clearORACFields() {
    $j("#total_rvu").val('');
    $j("#multiplier").val('');
    $j("#oprm_chrg").val('');
    tmpRVU = 0;
    drCharge = 0;
    tmpOPDetails = [];
}

function clearAppliedProcedureList(){
    $j('#procedure-list-body').empty();
}

function js_ClearOptions(tagId){
    var id = '#'+tagId;
    $j(id).empty();
}

function js_AddOptions(tagId, text, value){
    var elTarget = '#'+tagId;
    $j(elTarget).append($j("<option></option>").val(value).text(text));
}

function showOperatingRoomAcc() {
    clearORACFields();
    xajax_setORWardOptions();
}

//added by Nick 05-27-2014
function disableCaseTypeOptions(txt){
    if($('updateCaseTypePermission').value != 1){
        $j.each($j('#caseTypeOption option'),function(i, field){
            if(field.value > 2){
                $j('#caseTypeOption [value='+field.value+']').prop('disabled',true);
            }
        });
    }
}

function jsOpAccChrgOptionsChange(id, value){
    if(id == 'opwardlist') {
        $('opw_nr').value  = value;
        xajax_setORWardRooms(value);
    }
    else {
        $('opr_nr').value = value;
        //xajax_getRoomRate($('ward_nr').value, $('rm_nr').value);
    }
}

function initProcedureList() {
    var enc_nr = $j('#encounter_nr').val();
    xajax_populateAppliedOpsList(enc_nr);
}

//added by Nick 05-12-2014
function updateOpDate(entryno, code, refno){

    var editBox = 'opdateEditBox'+entryno+'_'+code;
    var editBoxVal = $(editBox).value;
    var origdate = $(editBox).value;

    if(confirm("Are you sure you want to edit the date?")){
        xajax_updateOpDate(editBoxVal, refno, code, entryno);
    }else{
        $(editBox).value = origdate;
    }
}

//added by Nick 05-12-2014
function showHideOpEditBox(entryno,code,mode,selectedDate){
    var editBox = 'opdateEditBox'+entryno+'_'+code;
    var editLnk = 'opdateEditLink'+entryno+'_'+code;
    if(mode){
        $(editBox).style.display = '';
        $(editLnk).style.display = 'none';
        $(editBox).focus();
    }else{
        $(editBox).style.display = 'none';
        $(editLnk).style.display = '';
        $(editLnk).innerHTML = $(editBox).value;
    }
}

function addAppliedOPtoList(details) {

    // var target = $('section').value;
    var rowSrc;
    var charge = details.rvu * details.multiplier;
    var fcharge = numFormat(charge);

        if (details.code) {
            rowSrc = '<tr>'+
                        '<td>'+
                            '<span id="description'+details.entry_no+'_'+details.code+'" style="font:bold 12px Arial">'+details.description+'</span><br />'+
                            '<input id="descriptionFull'+details.entry_no+'_'+details.code+'" type="hidden" value="'+details.descriptionFull+'">'+
                        '</td>'+
                        '<td>'+
                        '    <span style="font:bold 12px Arial;color:#660000">'+details.code+'</span>'+
                        '    <input id="code'+details.entry_no+'_'+details.code+'" type="hidden" value="'+details.code+'">'+
                        '</td>'+
                        // '<td align="center">'+
                        //     '<input id="groupcode'+details.entry_no+'_'+details.code+'" type="hidden" value="'+details.groupcode+'">'+details.groupcode+
                        // '</td>'+
                        '<td align="center">'+
                            '<input id="opdate'+details.entry_no+'_'+details.code+'" type="hidden" value="'+details.opdate+'">'+
                            //added by Nick 05-12-2014
                            '<a id="opdateEditLink'+details.entry_no+'_'+details.code+'" type="text" class="opDateEditor" onclick="showHideOpEditBox('+details.entry_no+','+details.code+',true,\'\')">'+details.opdate+'</a>'+
                            '<input id="opdateEditBox'+details.entry_no+'_'+details.code+'" type="text" value="'+details.opdate+'" class="opDateEditor" style="display:none;" />'+
                            //end Nick
                        '</td>'+
                        '<td align="center">'+
                            '<input id="rvu'+details.entry_no+'_'+details.code+'" type="hidden" value="'+details.rvu+'">'+details.rvu+'</span></td>'+
                        '<td align="center">'+
                            '<input id="multiplier'+details.entry_no+'_'+details.code+'" type="hidden" value="'+details.multiplier+'">'+details.multiplier+
                        '</td>'+
                        '<td align="right">'+
                            '<span id="chrgrow_'+details.entry_no+'_'+details.code+'"><input id="chrg'+details.entry_no+'_'+details.code+'" type="hidden" value="'+charge+'">'+fcharge+'</span>'+
                        '</td>'+
                        '<td align="center">'+
                            '<input onclick="addSelectedOP('+details.code+',\''+details.entry_no+'\',\''+details.refno+'\',\''+charge+'\',\''+details.multiplier+'\','+details.rvu+');" type="checkbox" id="op_selected'+details.entry_no+'_'+details.code+'" name="op_selected'+details.entry_no+'_'+details.code+'" value="">'+
                            '<input id="entryno_'+details.code+'" type="hidden" value="'+details.entry_no+'">'+
                            '<input id="refno_'+details.ntry_no+'_'+details.code+'" type="hidden" value="'+details.refno+'">'+
                        '</td>'+
                    '</tr>';
        }
        else {
            rowSrc = '<tr><td colspan="8" style="">No procedure...</td></tr>';
        }

        $j("#procedure-list-body").prepend( rowSrc );

        //added by Nick 05-12-2014
        $j('.opDateEditor').datepicker({
            onSelect: function(selectedDate){
                updateOpDate(details.entry_no, details.code, details.refno);
            },
            onClose: function(){
                showHideOpEditBox(details.entry_no, details.code, false);
            },
            changeMonth: true,
            changeYear: true
        });

}//end of function addAppliedOPtoList

function addSelectedOP(code,entryno,refno,charge,multiplier,rvu){
    var tmpID = "#op_selected"+entryno+'_'+code;
    var role = $j('#role_nr').val();
    var charge = parseFloat(charge);
    var pfCharge = 0;

    if(role=='D3'){
        pfCharge = charge;
    }else if(role=='D4'){
        pfCharge = charge * (0.40);
    }else{
        pfCharge = 0;
    }

    var n = 0, i = 0, tmp;
    for(i=0;n<1;i++){
        if(!tmpOPDetails[i]){
            tmpOPDetails[i] = refno+';'+entryno+';'+code+';'+rvu+';'+multiplier;
            n=1;
        }
    }

    if($j(tmpID).is(':checked')){
        tmpRVU+=rvu;
        drCharge+=pfCharge;
    }else{
        tmpRVU-=rvu;
        drCharge-=pfCharge;
    }

    opsCode = code;

}

function addDrCharge(){
    $j("#charge").val(drCharge);
}

function calcTotRVU(){
    var pDetails = new Object();
    pDetails.encNr = $j('#encounter_nr').val();
    pDetails.billdate = $j('#billdate').val();
    pDetails.nrvu = tmpRVU;
    pDetails.opsCode = opsCode;
    if(tmpRVU>0)
        xajax_updateRVUTotal(pDetails);
}

function applyRVUandMult(rvu,mul,chg){

    mul = parseFloat(mul).toFixed(2);
    chg = parseFloat(chg).toFixed(2);
    // fcharge = fcharge.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

    $j("#total_rvu").val(rvu);
    $j("#multiplier").val(mul);
    $j("#oprm_chrg").val(chg);
}

function saveORAccommodation(){
    var accDetails = new Object();
    var billDate = $j('#billdate').val();

    accDetails.opacc_enc_nr = $j('#opacc_enc_nr').val();
    accDetails.opw_nr = $j('#opwardlist').val();
    accDetails.opr_nr = $j('#orlist').val();
    accDetails.total_rvu = $j('#total_rvu').val();
    accDetails.multiplier = $j('#multiplier').val();
    accDetails.oprm_chrg = $j('#oprm_chrg').val();
    accDetails.frm_dte = $j('#admission_dte').val();

    // alert(accDetails.opw_nr);
    if(accDetails.opr_nr)
        xajax_saveORAccommodation(accDetails,billDate,tmpOPDetails);
    else
        alert("Please fill the neccesary inputs!");
}

function addORAccCharge(opAccDetails){

    var ward = $j("#opwardlist option:selected").text();
    var rm = opAccDetails.rm_nr;
    var n_rvu = opAccDetails.nrvu;
    var n_multiplier = opAccDetails.nmultiplier;
    var n_total = opAccDetails.nchrg;
    var total = numFormat(n_total);

    if(opAccDetails.desc){
        var desc = opAccDetails.desc;
    }else{
        var desc = ward+' - Room '+rm;
    }

    var srcRow;
    var idROw = 'op_code'+rm+n_rvu;
    var sFunc = 'onclick="promptDelOpAccom(\''+rm+'\', \''+idROw+'\')">';

    if(rm){
        srcRow += '<tr id="'+idROw+'">'+
                  '<td align="center" width="3%"><img src="../../images/btn_delitem.gif" class="imgdelete" style="border-right:hidden; cursor:pointer;" '+sFunc+'</td>'+
                  '<td style="border-left:hidden" width="52%">'+desc+'</td>'+
                  '<td width="15%" align="center">'+n_rvu+'</td>'+
                  '<td width="15%" align="right">'+n_multiplier+'</td>'+
                      '<td width="15%" align="right">'+n_total+'</td>'+
                  '</tr>';
    }
    // else{
    //     sMsg = "No O.R. accommodation charged!";
    //     srcRow = '<tr>'+
    //                 '<td colspan="2">'+sMsg+'</td>'+
    //                 '<td width="15%">&nbsp;</td>'+
    //                 '<td width="15%">&nbsp;</td>'+
    //                 '<td width="15%">&nbsp;</td>'+
    //             '</tr>';
    // }


    $j( '#body_opsListDetails' ).prepend( srcRow );
    $j( "#dialogOR").dialog("close");
}

function promptDelOpAccom(rm,idRow){
    var bill_dte = $j('#billdate').val();
    var bill_frmdte = $j('#admission_dte').val();
    var enc = $j('#encounter_nr').val();

        xajax_delOpAccommodation(enc, bill_dte, bill_frmdte, rm, idRow);
    }

function delORAccCharge(idRow){
    var id = '#'+idRow;
    $j(id).remove();
    alert("Successfully Deleted!");
}

function showOpsTotals(opsAP) {
    $('opsAP').innerHTML = opsAP;
    ops_computed = parseFloat(opsAP.replace(",",""));
    $j('#save_total_ops_charge').val(ops_computed);
    // $('opsDiscount').innerHTML = opsDiscount;
    // $('opsHC').innerHTML = opsHC;
    // $('opsEX').innerHTML = opsEX;
}

/*----------end--------Operating Room Accomodation Charges-----------end--------------*/

/*------------------------------------Add Doctors------------------------------------*/


function clearProfDialog() {
    $('doclist').value = "0";
    $('rolearea').value = "0";
    $('role_level').value = "0";
    $('ndays').value = "0"; // added by art 01/28/2014
    $('rate_type').value = "0"; // added by art 01/28/2014

    $('charge').value = '';
    $('dr_nr').value = '';
    $('role_nr').value = '';
    $('tier_nr').value = '';
    $('opstaken').innerHTML = '';

    $('doclist').focus();
}

function addDoctor(){
    var data = new Object();
    data.dr_nr = $j('#doclist').val();
    data.role_nr = $j('#rolearea').val();
    data.role_level = $j('#role_level').val();
    data.tier_nr = $j('#role_level').val();
    data.ndays = $j('#ndays').val();
    data.charge = $j('#charge').val();
    data.excluded = ($j('#is_excluded').checked) ? '1' : '0';
    data.hiAdm = HIadm;
    data.hiSurg = HIsurg;
    data.hiAnes = HIanes;
    data.enc = $j('#encounter_nr').val();
    data.bill_dte = $j('#billdate').val();

    //edited by art 01/28/2014
    //if(data.doclist == '0')
    if(data.dr_nr == '0')
        alert("Please select a doctor");
    //else if(data.rolearea == '0')
    else if(data.role_nr == '0')
        alert("Specify doctor's role");
   /* else if(data.role_level == '0')
        alert("Specify doctor's role level");*/
    //end art
    else if(data.charge=='')
        alert("Enter doctor's charge");
    else {
        xajax_processPrivateDrCharge(data, data.bill_dte, tmpOPDetails);
        clearProfDialog();
        $j( "#dialogAddDoc").dialog( "close" );
        return false;
    }
}

function jsDoctorsFees(tblId, roleId, roleDesc, totalCharge, Coverage){
    var srcRow;
    if(roleId){
        srcRow = '<tr>'+
                    '<td align="right"></td>'+
                    '<td colspan="2" style="font-weight:bold">'+roleDesc+
                        '<table id="'+roleId+'" width="95%" border="0" cellpadding="1" cellspacing="0" align="right">'+
                        '</table>'+
                    '</td>'+
                    // '<td align="right">'+totalCharge+'</td>'+
                    // '<td align="right" id="coverage_'+roleId+'">'+Coverage+'</td>'+
                 '</tr>';
    }else{
        srcRow = '<tr>'+
                    '<td colspan="2">No professional fees charged!</td>'+
                    '<td align="right">&nbsp;</td>'+
                    // '<td align="right">&nbsp;</td>'+
                 '</tr>';
    }
    $(tblId).innerHTML += srcRow;
}// end of jsDoctorsFee

function initMsgDialog(id,role_nr){
     var conf=confirm("Delete the selected doctor?");
    if (conf==true)
    {
        //clearDocVars();
        xajax_rmPrivateDr($('encounter_nr').value, id, role_nr, $('billdate').value);
    }

}

function initMsgDialog2(id,role_nr){
    var conf=confirm("Delete the selected doctor?");
    if (conf==true)
    {
        xajax_rmDr($('encounter_nr').value, id, role_nr, $('billdate').value);
    }
    }

function showPFTotals(detaislPF) {
   if (typeof(detaislPF)!=='undefined') {
        totalPF = parseFloat(detaislPF.pfCharge);
        totalPFDiscount = parseFloat(detaislPF.pfDiscount);
        PFd1 = parseFloat(detaislPF.d1);
        PFd2 = parseFloat(detaislPF.d2);
        PFd3 = parseFloat(detaislPF.d3);
        PFd4 = parseFloat(detaislPF.d4);
   } else {
        totalPF = 0;
        totalPFDiscount = 0;
        PFd1 = 0;
        PFd2 = 0;
        PFd3 = 0;
        PFd4 = 0;
   }


}

function jsOptionChange(obj, value){
    switch (obj.id) {
        // case 'doclist':
        //     $j('#dr_nr').val(value);
        //     getDrRole();
        //     break;

        case 'rolearea':
            xajax_getDrRole(value);
            break;

        // case 'role_level':
        //     $j('#tier_nr').val(value);
        //     getDrRole();
        //     break;
    }

}

function drRole(role){
    $j('#role_nr').val(role);
    $j('#rate_type').val(0);

    if(role=='D3'){
        $j("#hasAnes").show();
    }


}

function calcDrCharge(){
    var days = $j("#ndays").val();
    var role = $j('#role_nr').val();
    var chk = $j('#Anes').is(':checked');
    var rate_nr = $j('#rate_type').val();

    var charge_D1 = 0, charge_D3 = 0, charge_D4 = 0;

    var first_D1 = $j('#first_rate option:selected').attr('value_D1');
    var first_D3 = $j('#first_rate option:selected').attr('value_D3');
    var first_D4 = $j('#first_rate option:selected').attr('value_D4');

    //Professional Fee Distrbution based on Role for 2nd Case
    var second_D1 = $j('#second_rate option:selected').attr('value_D1');
    var second_D3 = $j('#second_rate option:selected').attr('value_D3');
    var second_D4 = $j('#second_rate option:selected').attr('value_D4');

    if(rate_nr==1){
        charge_D1 = ((first_D1) ? first_D1 : 0 );
        charge_D3 = ((first_D3) ? first_D3 : 0 );
        charge_D4 = ((first_D4) ? first_D4 : 0 );
    }else if(rate_nr==2){
        charge_D1 = ((second_D1) ? second_D1 : 0 );
        charge_D3 = ((second_D3) ? second_D3 : 0 );
        charge_D4 = ((second_D4) ? second_D4 : 0 );
    }else if(rate_nr==3){
        charge_D1 = parseFloat(((first_D1) ? first_D1 : 0 )) + parseFloat(((second_D1) ? second_D1 : 0 ));
        charge_D3 = parseFloat(((first_D3) ? first_D3 : 0 )) + parseFloat(((second_D3) ? second_D3 : 0 ));
        charge_D4 = parseFloat(((first_D4) ? first_D4 : 0 )) + parseFloat(((second_D4) ? second_D4 : 0 ));
    }


    if(days){
        days = parseFloat(days);
    }else{
        days = 0;
    }

    if((role=='D1')||(role=='D2')){
        if(HIadm){
            $j("#charge").val(charge_D1);
        }else if(role=='D1'){
            $j("#charge").val((days * 300));
        }else{
            $j("#charge").val(0.00);
        }
    }else{
        $j("#charge").val(0.00);
    }

    switch(role){

        case 'D1':
              if(HIadm){
                    $j("#charge").val(charge_D1);
                }else{
                    $j("#charge").val((days * 300));
                }
              break;

        case 'D2':
              if(HIadm){
                    $j("#charge").val(charge_D1);
                }else{
                    $j("#charge").val((days * 300));
                }
              break;

        case 'D3':
                if(HIsurg){
                    if(chk){
                        $j("#charge").val(charge_D3);
                    }else{
                        $j("#charge").val(parseFloat(charge_D3)+parseFloat(charge_D4));
                    }
                }else{
                    $j("#charge").val(0.00);
                }
          break;

        case 'D4':
              if(HIanes){
                    $j("#charge").val(charge_D4);
                }else{
                    $j("#charge").val(0.00);
                }
          break;

        default:
            $j("#charge").val(0.00);
        break;

    }

}

/*--------------end-------------------Add Doctors------------end---------------------*/


function genChkDecimal(obj, n){
    var objValue = obj.value;
    objValue = objValue.replace(',', '');

    if (objValue=="")
        return false;

    if (isNaN(objValue)) {
        alert("Invalid amount!");
        obj.value="0.00";
        // obj.focus();
        return false;
    }

    // n = n || 2;

    // var nf = new NumberFormat();
    // nf.setPlaces(n);
    // nf.setNumber(objValue);

    // obj.value = nf.toFormatted();
    return true;
}// end of function genChkDecimal

function genChkInteger(obj){
    var objValue = obj.value;

    if (objValue=="")
        return false;

    if (isNaN(objValue)) {
        alert("Invalid whole number!");
        obj.value="0";
        // obj.focus();
        return false;
    }

    // var nf = new NumberFormat();
    // nf.setPlaces(0);
    // nf.setNumber(objValue);

    // obj.value = nf.toFormatted();
    return true;
}// end of function genChkInteger

function trimString(objct){
  objct.value.replace(/^\s+|\s+$/g,"");
  objct.value = objct.value.replace(/\s+/g,"");
}

function closeSelEncDiaglog()
{
    dialogSelEnc.dialog('close');
    populateBill();
}

//added by Nick, 2/25/2014
function disableImgDelete(){
    if(isFinalBill){
        $j('.imgdelete').hide();
    }
}

function disableUI(){
    //enable diagnosis and insurance by borj 2014-11-03
    //$('btnInsurance').disabled = 'disabled';
    //$('btnDiagnosis').disabled = 'disabled';
    $('isdied').disabled = 'disabled';
    $('btnOutMedsXLO').disabled = 'disabled';
    $('confineTypeOption').disabled = 'disabled';
    $('caseTypeOption').disabled = 'disabled';
    $('billdate_display').disabled = 'disabled';
    $('first_rate').disabled = 'disabled';
    $('second_rate').disabled = 'disabled';
}

function populateBill()
{

    showLoading();//added by Nick 05-12-2014

    var enc = $j('#encounter_nr').val();
    var pid = $j('#pid').val();
    var bill_dte = $j('#billdate').val();
    var bill_frmdte = $j('#admission_dte').val();
    var bill_nr = $j('#bill_nr').val();
    if($('isdied').checked){
        deathdate = $j('#deathdate').val();
    }else{
        deathdate = '';
    }
    totalHealthInsuranceHF = 0;
    com_total_amount = 0;
    HospitalCompany = 0;
    ProfCompany = 0;

    xajax_checkInsurance(enc);
    xajax_populateMeds(enc,bill_dte,bill_frmdte,deathdate);
    xajax_populateXLO(enc,bill_dte,bill_frmdte,deathdate);
    xajax_populateMisc(enc,bill_dte,bill_frmdte,deathdate);
    xajax_getBilledOps(enc,bill_dte,bill_frmdte,deathdate);
    xajax_populateBill(enc,pid,bill_dte,bill_frmdte,deathdate,firstratecode,secondratecode);
    // xajax_populateCaseRate(enc,bill_dte,bill_frmdte);
     //added by poliam 01/04/2014
     xajax_classification(enc,bill_dte,bill_frmdte);
     xajax_confinment(enc);
     xajax_getConfineTypeOption(enc,bill_dte);
     // xajax_populatePrevBal(pid, enc, bill_dte, bill_frmdte);
     //calculateTotals();
     //ended by poliam

     //added by daryl
     //to populate HMO and discount in HCi
     //10/21/14
      xajax_populateAdjCoverage(enc, acc_computed, miscServices_computed, med_computed, miscCharges_computed, ops_computed);
      // xajax_populateHFdiscount(enc, acc_computed, miscServices_computed, med_computed, miscCharges_computed, ops_computed);
      //ended by daryl
}

function assignValue(id,val){
    $j('#'+id).val(val);
}

function toggleDeathDate(bool) {
    if($('isdied').checked){
        $('label_deathdate').style.display = "";
        $('input_deathdate').style.display = "";
    }else if(!$('isdied').checked){
        $('label_deathdate').style.display = "none";
        $('input_deathdate').style.display = "none";
        if(bool){
            xajax_setDeathDate($j('#pid').val());
            populateBill();
        }
    }

}
//added by Art 01/05/2014
function showRemainingDays(show,ndayscover,ndaysremain,save){
    if(show && typeof(ndaysremain)!=='undefined'){
        if (ndaysremain == '') {
            $('remaindays').style.display = "none";
        }else{
            $('remaindays').style.display = "";}
        if (ndayscover == '') {
            $('coverdays').style.display = "none";
        }else{
            $('coverdays').style.display = "";}

        $('remaindays').innerHTML = "Remaining Days : "+ndaysremain;
        $('coverdays').innerHTML = "Number of Days Covered : "+ndayscover;
        //$('coverdays').style.display = "";
        $('savethis').innerHTML = +save;
    }else{
        $('remaindays').style.display = "none";
        $('coverdays').style.display = "none";
    }
}
//end art

/*----------------------------------------------MEDICO LEGAL-------------------------------------------------*/

function showMedicoLegal(show_medico, medicoDesc) {
    if (show_medico==1) {
        $('medicolegal').style.display = "";
        $('ShowMedicoCases').value = medicoDesc;
        isMedicolegal = true;
    } else {
        $('medicolegal').style.display = "none";
    }

}
/*----------------------------------------------IS ER PATIENT-------------------------------------------------*/
function hideAccomodation (code)
{
    if (code == 1)
        $('accommodation_div').style.display = "none";
}


/*----------------------------------------------Case Rates-------------------------------------------------*/

function setFields(){
    var sel = "<option id='0' value='0'>-Select Code-</option>";
    var amnt = "0";
    var desc = "No case rate selected."
    $j('#first_rate').html(sel);
    $j('#second_rate').html(sel);
    $j('#first_case_desc').html(desc);
    $j('#second_case_desc').html(desc);
    $j('#first_rate_amount').html(numFormat(amnt));
    $j('#second_rate_amount').html(numFormat(amnt));
}


/*
*  Updated by Jarel 03/05/2014
*  Populate Case Rate Details
*  Add features for Single Period of Confinement
*
* Updated by Nick 4/22/2014
* Different case rate amount for new born with hearing
* and non-hearing test
*/
function populateCaseRates(details){
    var spc_label;
    //added by Nick, 4/22/2014
    var amntSecond, shf, spf,
        amntFirst  = details.amntFirst,
        hf1        = details.hf1,
        pf1        = details.pf1,
        amntSecond = details.amntSecond,
        hf2        = details.hf2,
        pf2        = details.pf2;
    // if(hasHearingTest){
    //     amntFirst  = (details.sp_amnt!=0) ? details.sp_amnt : details.amntFirst ;
    //     hf1        = (details.sp_hf!=0) ? details.sp_hf : details.hf1 ;
    //     pf1        = (details.sp_pf!=0) ? details.sp_pf : details.pf1 ;
    //     amntSecond = (details.sp_amnt!=0) ? details.sp_amnt : details.amntSecond ;
    //     hf2        = (details.sp_hf!=0) ? details.sp_hf : details.hf2 ;
    //     pf2        = (details.sp_pf!=0) ? details.sp_pf : details.pf2 ;
    // }else{
    // }
    //end nick

    spc_label = ((details.spc == 1) ? ' (SPC)' : '');
    if(details.code){
        $j('#first_rate').append($j('<option>', {
            value: amntFirst,
            text : details.code +spc_label,
            id : details.code,
            value_hf : hf1,
            value_pf : pf1,
            value_D1 : details.D1,
            value_D3 : details.D3,
            value_D4 : details.D4,
            desc : details.desc,
            case_type : details.cType,

            orig_amnt: details.amntFirst,
            orig_hf: details.hf1,
            orig_pf: details.pf1,
            sp_amnt: details.sp_amnt,
            sp_hf: details.sp_hf,
            sp_pf: details.sp_pf,
            //added by Nick, 4/22/2014
            // temp_amnt    : details.amntFirst,
            // temp_hf      : details.hf1,
            // temp_pf      : details.pf1,
            // temp_sp_amnt : details.sp_amnt,
            // temp_sp_hf   : details.sp_hf,
            // temp_sp_pf   : details.sp_pf
            //end nick
        }));

        if(details.isSecCase==1){
            $j('#second_rate').append($j('<option>', {
                value: amntSecond,
                text : details.code +spc_label,
                id : details.code,
                value_hf : hf2,
                value_pf : pf2,
                value_D1 : details.D1_sec,
                value_D3 : details.D3_sec,
                value_D4 : details.D4_sec,
                laterality : details.laterality,
                desc : details.desc,
                case_type : details.cType,

                orig_amnt: details.amntSecond,
                orig_hf: details.hf2,
                orig_pf: details.pf2,
                sp_amnt: details.sp_amnt,
                sp_hf: details.sp_hf,
                sp_pf: details.sp_pf,
                //added by Nick, 4/22/2014
                // temp_amnt    : details.amntSecond,
                // temp_hf      : details.hf2,
                // temp_pf      : details.pf2,
                // temp_sp_amnt : details.sp_amnt,
                // temp_sp_hf   : details.sp_hf,
                // temp_sp_pf   : details.sp_pf
                //end nick
            }));
        }

        if(details.spc == 1) {
            $j('#first_rate option:contains('+details.code+')').attr('disabled','disabled');
            $j('#second_rate option:contains('+details.code+')').attr('disabled','disabled');
        }
    }

}

function setCaseValues(first_code,second_code){
    elem1 = $j('#first_rate option:contains('+first_code+')');
    elem2 = $j('#second_rate option:contains('+second_code+')');

    $j("#first_rate option:selected").val( getCaseValues(elem1,1) );
    $j('#first_rate option:contains('+first_code+')').attr('value_hf', getCaseValues(elem1,2));

    $j("#second_rate option:selected").val( getCaseValues(elem2,1) );
    $j('#second_rate option:contains('+second_code+')').attr('value_hf', getCaseValues(elem2,2));
}

function getCaseValues(elem,mode){
    if(mode==1){
        if(elem.text() == NEWBORN_PKG){
            if(hasHearingTest){
                return (elem.attr('temp_amnt') > 0) ? elem.attr('temp_amnt') : 0;
            }else{
                return (elem.attr('temp_sp_amnt') > 0) ? elem.attr('temp_sp_amnt') : 0;
            }
        }else{
            return (elem.attr('temp_amnt') > 0) ? elem.attr('temp_amnt') : 0;
        }
    }else{
        if(elem.text() == NEWBORN_PKG){
            if(hasHearingTest){
                return (elem.attr('temp_hf') > 0) ? elem.attr('temp_hf') : 0;
            }else{
                return (elem.attr('temp_sp_hf') > 0) ? elem.attr('temp_sp_hf') : 0;
            }
        }else{
            return (elem.attr('temp_hf') > 0) ? elem.attr('temp_hf') : 0;
        }
    }
}

/**
 * Added by Nick 4/22/2014
 * Set Second case attributes
 * @param string  first_code
 * @param string  second_code
 * @param boolean withHtest
 */
function setSecondCaseAttributes(first_code,second_code,withHtest){
    /**
     * added by Nick, 4/22/2014
     * 1  - true       - call from ajax
     * 2  - true/false - call from js (self)
     */
    elem1 = $j("#first_rate option:selected");
    elem2 = $j("#second_rate option:selected");

    if(elem1.attr('temp_sp_amnt') > 0 || elem2.attr('temp_sp_amnt') > 0){
        $('lblHearingTest').style.display = '';
    }else{
        $('lblHearingTest').style.display = 'none';
}

    if(withHtest == 1){
        setCaseValues(first_code,second_code);
    }else if(withHtest == 2){
        hasHearingTest = $('chkHearingTest').checked;
        setCaseValues(first_code,second_code);
    }else{
        hasHearingTest = false;
        $('chkHearingTest').checked = false;
        setCaseValues(first_code,second_code);
    }
}

/*
* Added by Jarel 03/05/2014
* Set Case Rate detials
*
* Updated by Nick, 4/22/2014
* Change case rate amount for new born
* with hearing or without hearing test
*/
function setCaserate(first_code,second_code)
{
    var HF1, HF2, HF, PF1, PF2, PF;
    var first_D1, first_D3, first_D4,
        second_D1, second_D3, second_D4;


    //alert(first_code+' = '+second_code);
    firstratecode = first_code;
    secondratecode = second_code;
    $j('#first_rate option:contains('+first_code+')').attr('selected',true);
    $j('#second_rate option:contains('+second_code+')').attr('selected',true);
    // setSecondCaseAttributes(first_code,second_code,withHtest);

    $j('#first_case_desc').html($j('#first_rate option:contains('+first_code+')').attr('desc'));
    $j('#second_case_desc').html($j('#second_rate option:contains('+second_code+')').attr('desc'));
    $j('#first_rate_amount').html(numFormat($j("#first_rate option:selected").val()));
    $j('#second_rate_amount').html(numFormat($j("#second_rate option:selected").val()));

    //Assign Hopital Fee
    HF1 = $j('#first_rate option:contains('+first_code+')').attr('value_hf');
    HF2 = $j('#second_rate option:contains('+second_code+')').attr('value_hf');
    HF = parseFloat(((HF1)? HF1 : 0 )) + parseFloat(((HF2)? HF2 : 0 ));

    //Assign Professional Fee
    PF1 = $j('#first_rate option:contains('+first_code+')').attr('value_pf');
    PF2 = $j('#second_rate option:contains('+second_code+')').attr('value_pf');
    PF = parseFloat(((PF1)? PF1 : 0 )) + parseFloat(((PF2)? PF2 : 0 ));

    //Professional Fee Distrbution based on Role for First Case
    first_D1 = $j('#first_rate option:contains('+first_code+')').attr('value_D1');
    first_D3 = $j('#first_rate option:contains('+first_code+')').attr('value_D3');
    first_D4 = $j('#first_rate option:contains('+first_code+')').attr('value_D4');

    //Professional Fee Distrbution based on Role for 2nd Case
    second_D1 = $j('#second_rate option:contains('+second_code+')').attr('value_D1');
    second_D3 = $j('#second_rate option:contains('+second_code+')').attr('value_D3');
    second_D4 = $j('#second_rate option:contains('+second_code+')').attr('value_D4');

    //var HF = parseFloat(()?$j("#first_rate option:selected").val():0);
    totalHealthInsuranceHF1 = parseFloat(HF);
    totalHealthInsurancePF = parseFloat(PF);
    //totalPackage = parseFloat(details.Total);
    //firstcase = parseFloat(details.amntFirst);
    //secondcase = parseFloat(details.amntSec);
    HIadm = parseFloat((first_D1)? first_D1 : 0) + parseFloat((second_D1)? second_D1 : 0) ;
    HIsurg = parseFloat((first_D3)? first_D3 : 0) + parseFloat((second_D3)? second_D3 : 0) ;
    HIanes = parseFloat((first_D4)? first_D4 : 0) + parseFloat((second_D4)? second_D4 : 0) ;

    calculateTotals();
    calculateDetails();
    if(parseFloat($j('#phic-max-PF').html().replace(',','')) < 0 || parseFloat($j('#total-excess').html().replace(',','')) < 0 ){
        alert("Total PHIC PF Coverage is negative. Please Distribute The PF Properly for Data Consistency. \n Thank You.");
        $j('#doccvrg').click();
        return false;
    }
}

function changeCase(caseNum){
    var enc = $j('#encounter_nr').val();
    var laterality, firstratecode_temp, secondratecode_temp, code;
    var retVal = true;
    firstratecode_temp = $j("#first_rate option:selected").text();
    secondratecode_temp = $j("#second_rate option:selected").text();
    laterality = $j("#second_rate option:selected").attr('laterality');

    if (caseNum == '2') {
        if (firstratecode_temp == secondratecode_temp && laterality == 'B')
            secondratecode = secondratecode_temp;
        else if (firstratecode_temp != secondratecode_temp)
            secondratecode = secondratecode_temp;
        else {
            //Added by Nick, 4/23/2014
            // $('chkHearingTest').checked = !$('chkHearingTest').checked;
            retVal = false;
            //end nick
            alert('The Package is already Selected as First Case Rate');
            $j('#second_rate').val(0);
            secondratecode = secondratecode;
        }
    }

    setCaserate(firstratecode_temp, secondratecode);
    return retVal;
}

/*-------------------end---------------------------Case Rates----------------------end---------------------------*/

function numFormat(num){
    var tmpNr = '';
    tmpNr = parseFloat(num).toFixed(2);
    tmpNr = tmpNr.replace(/\B(?=(\d{3})+(?!\d))/g,",");

    return tmpNr;
}

//added by borj 2014-06-01
//modified by Nick, 4/11/2014 - hide btnDelete, btnCF2Part3
function showBillingStatus(bool) {
    var elem = $('bill_status');

    if (bool == 1) {
        isFinalBill = true;
        elem.style.visibility = "";
        elem.innerHTML = "[FINAL BILLING]";

        //$('chkHearingTest').disabled = 'disabled';

        //$('lblHearingTest').disabled = 'disabled';

        $('btnEditMemCat').style.display = 'none';

        $('death_date').disabled = 'disabled';



        $('isFinalBill').checked = true;
        $('btnCF2Part3').style.display = "";
       $('btnCF2Part4').style.display = "";
        //$('btnPrevPack').disabled = "disabled";
        $('btnSave').style.display = "none";
        $j("#chkboxrow").hide();

        $('btnaccommodation').disabled = 'disabled';
        $('btnaddmisc_srvc').disabled = 'disabled';
        $('btnmedsandsupplies').disabled = 'disabled';
        $('btnOPaccommodation').disabled = 'disabled';
        $('btnaddmisc_chrg').disabled = 'disabled';
        $('btnadddoctors').disabled = 'disabled';
        $('btnadd_discount').disabled = 'disabled';
        $('first_rate').disabled = 'disabled';
        $('second_rate').disabled = 'disabled';
        $('chkHearingTest').disabled = 'disabled';

        //added by Nick, 2/24/2014
        $('isdied').disabled = 'disabled';
        $('btnOutMedsXLO').disabled = 'disabled';
        $('confineTypeOption').disabled = 'disabled';
        $('caseTypeOption').disabled = 'disabled';
        $('billdate_display').disabled = 'disabled';
        //end Nick

    } else {
        elem.innerHTML = "[NOT YET FINAL]";
        $('isFinalBill').checked = false;
    }
}
//added by borj 2014-06-01
function disabled_button(final) {
     var elem = $('bill_status');
        if (final==1) {
        $('btnCF2Part3').style.display = "";
         $('btnCF2Part4').style.display = "";
        //$('btnInsurance').style.display = "none";
        //$('btnPrevPack').style.display = "none";
        //$('btnDiagnosis').style.display = "none";
        $('btnSave').style.display = "none";
        $('btnDelete').style.display = "";
        $j("#chkboxrow").hide();
        $j(".imgdelete").remove();


        $('btnaccommodation').disabled = 'disabled';
        $('btnaddmisc_srvc').disabled = 'disabled';
        $('btnmedsandsupplies').disabled = 'disabled';
        $('btnOPaccommodation').disabled = 'disabled';
        $('btnaddmisc_chrg').disabled = 'disabled';
        $('btnadddoctors').disabled = 'disabled';
        $('btnadd_discount').disabled = 'disabled';
        $('chkHearingTest').disabled = 'disabled';

         // $('first_rate').disabled = 'disabled';
         // $('second_rate').disabled = 'disabled';

        }else{

             elem.innerHTML = "[NOT YET FINAL]";
        }
    }


function js_NewBilling() {
    window.location.href="billing-main-new.php";
}

function toggleFinalBill() {

    if ($('hasbloodborrowed').value=='1'){
        alert('This Patient has a pending transaction in Blood Bank. \n Please advice the patient to settle this transaction.');
        $('isFinalBill').checked = false;
        return;
    }

    if($('isFinalBill').checked){
        $('bill_status').style.display = "";
        $('bill_status').innerHTML = "[FINAL BILLING]";
    }else{
        $('bill_status').style.display = "none";
    }

}


function billingHeader(details)
{
    if (typeof(details)=='object') {
        var death_date = details.death_date;
        var is_final = details.is_final;

        if(death_date != '') {
            $j('#isdied').attr('checked',true);
            $j('#label_deathdate').show();
            $j('#input_deathdate').show();
            $j('#death_date').val(details.fdeath_date);
            $j('#deathdate').val(details.death_date);
        }else{
            $j('#isdied').attr('checked',false);
            $j('#label_deathdate').hide();
            $j('#input_deathdate').hide();
        }

        $j('#billdate').val(details.bill_dte);
        $j('#billdate_display').val(details.fbill_dte);
        $j('#admission_dte').val(details.bill_frmdte);
        $j('#admission_date').val(details.fbill_frmdte);
        $j('#bill_nr').val(details.bill_nr);

        if(details.hasTransmittal == 1)
            $('btnDelete').disabled = 'disabled';

        showBillingStatus(is_final);
    }
}

//added by poliam 01/04/2014
function ClassificationHeader(Class){
    if (Class){
        $('classification').innerHTML =Class;
    }else{
        $('classification').innerHTML= 'NO CLASSIFICATION ';
    }
}

function ConfinmentHeader(type){
    if (type){
         $('confine_label').style.display = "";
         $('confine_cbobox').style.display = "";
     }else{
         $('confine_label').style.display = "none";
         $('confine_cbobox').style.display = "none";
     }
}
//ended by poliam 01/04/2014

//added by ken 1/4/2013
function packageDisplay(insurance){
    if(insurance == 'PHIC'){
        // $('td02').style.display = "";
        $('td02').innerHTML = "<span id='' style='font-weight: bold;'>First Case Rate<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>: </span>"+
                                "<span id=''><select id='first_rate' name='first_rate'><option value='00.00'>-Select Code-</option></select></span>"+
                                "<span>&nbsp;&nbsp;&nbsp;P&nbsp;</span>"+
                                "<span id='first_rate_amount'>00.00</span><br/>"+
                                "<span>&nbsp;&nbsp;</span>"+
                                "<span id='first_case_desc'>. . . .</span><br/>"+
                                "<span id='' style='font-weight: bold;'>Second Case Rate : </span>"+
                                "<span id=''><select id='second_rate' name='second_rate'><option value='00.00'>-Select Code-</option></select></span>"+
                                "<span>&nbsp;&nbsp;&nbsp;P&nbsp;</span>"+
                                "<span id='second_rate_amount'>00.00</span><br/>"+
                                "<span>&nbsp;&nbsp;</span>"+
                                "<span id='second_case_desc'>. . . .</span><br/>"+
                                "<span>&nbsp;</span>"+
                                "<ul id='special_procedure_details' style='margin-top:0;margin-left:0;margin-bottom:10px;'></ul>"+
                                "<label id='lblHearingTest' style='display:none; cursor:pointer;'><strong>New Born with Hearing Test:</strong>&nbsp;<input id='chkHearingTest' type='checkbox'/><label>";//added by Nick, 4/21/2014
            Procedures.showSpecialProcedures();
            // $j('#first_rate').on('change', function() {
            //     changeCase('1');
            // });
            //
            // $j('#second_rate').on('change', function() {
            //     changeCase('2');
            // });
            //
            // //added by Nick, 4/21/2014
            // $j('#chkHearingTest').on('change',function updateHearingTest(){
            //     value = 0;
            //     if($('chkHearingTest').checked){
            //         value = 1;
            //     }
            //     xajax_updateHearingTest($('encounter_nr').value,value);
            // });
        //jsRecomputeServices();
    }
    else{
        // $('td02').style.display = "none";
        $('td02').innerHTML = "<span id='' style='font-weight: bold;'></span>"+
                                "<span id=''></span>"+
                                "<span></span>"+
                                "<span id=''></span><br/>"+
                                "<span></span>"+
                                "<span id=''></span><br/>"+
                                "<span id='' style='font-weight: bold;'></span>"+
                                "<span id=''></span>"+
                                "<span></span>"+
                                "<span id=''></span><br/>"+
                                "<span></span>"+
                                "<span id=''></span><br/>"+
                                "<span></span>";
        //jsRecomputeServices();
    }
}

function getexcessdiscount(amnt, area){
    if(!amnt)
        amnt = 0;
    if(area == 'hci'){
        excessHCIdiscount = amnt;
    }else{
        excessPFdiscount = amnt;
    }
}

function CompanySetCoverageUi(HospitalCompanyAmount, ProfCompanyAmount){
    if(HospitalCompanyAmount != 0){
        HospitalCompany = HospitalCompanyAmount;
    }
    if(ProfCompanyAmount != 0){
        ProfCompany = ProfCompanyAmount;
    }

}

function calculateTotals()
{
    tmpHIadm = HIadm;
    tmpHIsurg = HIsurg;
    tmpHIanes = HIanes;

    if( HIadm > (PFd1+PFd2) ){
        tmpHIadm = PFd1+PFd2;
    }

    if( HIanes > PFd4 ){
        tmpHIanes = PFd4;
    }

    if( HIanes>PFd4 ){
        tmpHIsurg += (HIanes - PFd4);
    }

    if( tmpHIsurg > PFd3 ){
        tmpHIsurg = PFd3;
    }

    //totalHealthInsurancePF = tmpHIadm + tmpHIsurg + tmpHIanes;
    TotalAutoExcess = parseFloat(accexcess) + parseFloat(TotalUnsed)/* + parseFloat(TotalMiscAutoExcess)*/;
    totalHCI = acc_computed+miscServices_computed+med_computed+ops_computed+miscCharges_computed;

    total_pf_discount = parseFloat($j('#pfDiscount').html().replace(',',''));
    $j('#temppfdiscount').val(total_pf_discount);
    HealthInsurancePF = parseFloat($j('#pfHC').html().replace(',',''));
    total_pf_excess = totalPF - (total_pf_discount + HealthInsurancePF);

    $('hiTotal').innerHTML = numFormat(totalHCI);
    $('pfAP').innerHTML = numFormat(totalPF);
    $('pfEX').innerHTML = numFormat(total_pf_excess);

    isphic = ($j('#phic').val().toUpperCase()=="NONE") ? false:true;
    acc_appDiscount = 0;
    xlo_appDiscount = 0;
    med_appDiscount = 0;
    ops_appDiscount = 0;
    //pfs_appDiscount = 0;
    msc_appDiscount = 0;

    acc_appDiscount = acc_computed * total_applied_discount;
    xlo_appDiscount = miscServices_computed * total_applied_discount;
    med_appDiscount = med_computed * total_applied_discount;
    ops_appDiscount = ops_computed * total_applied_discount;
    //pfs_appDiscount = totalPF * total_applied_discount;
    msc_appDiscount = miscCharges_computed * total_applied_discount;

    discounts_hci = (acc_appDiscount + xlo_appDiscount + med_appDiscount + ops_appDiscount + msc_appDiscount);
    $('hf_discount').innerHTML = numFormat(discounts_hci);
    // $('hf_discount_disc').innerHTML = numFormat(discounts_hci);

    temp_serv_phic = 0;
    temp_pf_phic = 0;

    if(!isphic && isPAYWARD()){//none-phic, payward

        total_serv_discount_ = (acc_appDiscount + xlo_appDiscount + med_appDiscount + ops_appDiscount + msc_appDiscount);
        total_serv_discount = total_serv_discount_;

        total_serv_excess = totalHCI - total_serv_discount;

        if(total_serv_discount < 0){
            total_serv_excess = (totalHCI - totalHealthInsuranceHF) + Math.abs(total_serv_discount);
            total_serv_discount = 0;
        }

      totalHealthInsuranceHFtemp = 0;
        if(total_serv_excess > 0){
            total_serv_excess = total_serv_excess - totalHealthInsuranceHF;
            if(total_serv_excess < 0){
                totalHealthInsuranceHFtemp = totalHealthInsuranceHF + total_serv_excess;
                total_serv_excess = 0;
            }
        }

        excessHCIdiscounttemp = 0;
        if(total_serv_excess > 0){
            total_serv_excess = total_serv_excess - parseFloat(excessHCIdiscount);
            if(total_serv_excess < 0){
                excessHCIdiscounttemp = parseFloat(excessHCIdiscount) + total_serv_excess;
                total_serv_excess = 0;
            }
        }

        if(total_serv_excess > 0){
            total_serv_excess = total_serv_excess - parseFloat(HospitalCompany);
            if(totalHealthInsuranceHFtemp > 0){
                totalHealthInsuranceHFtemp = totalHealthInsuranceHFtemp + parseFloat(HospitalCompany);
            }else{
                totalHealthInsuranceHF = totalHealthInsuranceHF + parseFloat(HospitalCompany);
            }
        }

        $('hiDiscount').innerHTML = numFormat((excessHCIdiscounttemp) ? total_serv_discount + parseFloat(excessHCIdiscounttemp):total_serv_discount + parseFloat(excessHCIdiscount));
        $('hiHIC').innerHTML = numFormat((totalHealthInsuranceHFtemp) ? totalHealthInsuranceHFtemp:(totalHealthInsuranceHF));
        $('hiEX').innerHTML = numFormat(total_serv_excess);

    }else if(!isphic && !isPAYWARD()){//none-phic, charity

        total_serv_discount_ = ( ((isMedicolegal) ? acc_appDiscount : acc_computed) + xlo_appDiscount + med_appDiscount + ops_appDiscount + msc_appDiscount) - TotalAutoExcess;
        total_serv_discount = total_serv_discount_ + hci_discount;

        totalHealthInsuranceHF = 0;

        total_serv_excess = totalHCI - total_serv_discount;

        if(total_serv_discount < 0){
            total_serv_excess = (totalHCI - totalHealthInsuranceHF) + Math.abs(total_serv_discount);
            total_serv_discount = 0;
        }

        $('hiDiscount').innerHTML = numFormat(total_serv_discount);
        $('hiHIC').innerHTML = numFormat(totalHealthInsuranceHF);
        $('hiEX').innerHTML = numFormat(total_serv_excess);


    }else if(isphic && isPAYWARD()){//with phic, payward
        total_serv_discount_ = (acc_appDiscount + xlo_appDiscount + med_appDiscount + ops_appDiscount + msc_appDiscount);

        total_serv_discount = total_serv_discount_;

        total_serv_excess = totalHCI - (total_serv_discount + totalHealthInsuranceHF);
        if(total_serv_discount < 0){
            total_serv_excess = (totalHCI - totalHealthInsuranceHF) + Math.abs(total_serv_discount);
            total_serv_discount = 0;
        }

        serv_excess_negative = false;

        if(total_serv_excess < 0){
            serv_excess_negative = true;
            temp_serv_phic = totalHealthInsuranceHF + total_serv_excess;
            total_serv_excess = 0;
        }
        excessHCIdiscounttemp = 0;

        if(total_serv_excess > 0){
            total_serv_excess = total_serv_excess - parseFloat(excessHCIdiscount);
            if(total_serv_excess < 0){
                excessHCIdiscounttemp = parseFloat(excessHCIdiscount) + total_serv_excess;
                total_serv_excess = 0;
            }
        }

        if(total_serv_excess > 0){
            total_serv_excess = total_serv_excess - parseFloat(HospitalCompany);
            if(excessHCIdiscounttemp > 0){
                excessHCIdiscounttemp = excessHCIdiscounttemp + parseFloat(HospitalCompany);
            }else{
                totalHealthInsuranceHF = totalHealthInsuranceHF + parseFloat(HospitalCompany);
            }
        }
        $('hiHIC').innerHTML = numFormat((serv_excess_negative) ? temp_serv_phic + parseFloat(HospitalCompany):(totalHealthInsuranceHF));
        $('hiDiscount').innerHTML = numFormat((excessHCIdiscounttemp) ? total_serv_discount + parseFloat(excessHCIdiscounttemp):(total_serv_discount + parseFloat(excessHCIdiscount)));
        $('hiEX').innerHTML = numFormat(total_serv_excess);


    }else if(isphic && !isPAYWARD()){//with phic, charity

        total_serv_discount_ = (acc_appDiscount + xlo_appDiscount + med_appDiscount + ops_appDiscount + msc_appDiscount) /*- accexcess*/;
         total_serv_discount = total_serv_discount_ + hci_discount;

        total_serv_excess = totalHCI - (total_serv_discount + totalHealthInsuranceHF);

        if(total_serv_discount < 0){
            total_serv_excess = (totalHCI - totalHealthInsuranceHF) + Math.abs(total_serv_discount);
            total_serv_discount = 0;
        }


        serv_excess_negative = false;
        pf_excess_negative = false;

        if(total_serv_excess < 0){
            serv_excess_negative = true;
            temp_serv_phic = totalHealthInsuranceHF + total_serv_excess;
            total_serv_excess = 0;
        }

        $('hiHIC').innerHTML = numFormat(((serv_excess_negative) ? temp_serv_phic:totalHealthInsuranceHF));
        $('hiDiscount').innerHTML = numFormat(total_serv_discount);
        $('hiEX').innerHTML = numFormat(total_serv_excess);

    }
    if(excessHCIdiscounttemp){
        total_serv_discount = total_serv_discount + parseFloat(excessHCIdiscounttemp);
    }else{
        total_serv_discount = total_serv_discount + parseFloat(excessHCIdiscount);
    }
    $j('#temphcidiscount').val(total_serv_discount);
    final_discount = total_serv_discount + total_pf_discount;
    final_net_amount = total_serv_excess + total_pf_excess + prev_bal - deposit;
    final_phic = parseFloat($j('#hiHIC').html().replace(',','')) + parseFloat($j('#pfHC').html().replace(',',''));
    totalGross =  totalHCI + totalPF;
    $j('#save_total_prevpayment').val(deposit);

    $('netbill').innerHTML = numFormat(totalGross);
    $('HealthInsuranceTotal').innerHTML = numFormat(final_phic);
    $('DiscountTotal').innerHTML = numFormat(final_discount);
    $('netamnt').innerHTML = numFormat(final_net_amount);

    //commented by janken 1/23/2015 for infirmary issue.
    // if (isSponsoredMember() || isHSM() || isPHS() || isInfirmaryOrDependent.trim() != '') {

    //     if (isSponsoredMember() && !isPAYWARD()) {
    //         $j('#amntlabel_discount').html("Total Discount : (NBB)");
    //         $j('#DiscountTotal').html(numFormat(final_net_amount + final_discount));
    //         $j('#netamnt').html('0.00');
    //         $j('#save_discountid').val('NBB');
    //         $j('#save_discount').val('0');
    //         $j('#save_discount_amnt').val(final_net_amount + final_discount);
    //     } else if(isHSM() && !isPAYWARD()) {
    //         $j('#amntlabel_discount').html("Total Discount : (HSM)");
    //         $j('#DiscountTotal').html(numFormat(final_net_amount + final_discount));
    //         $j('#netamnt').html('0.00');
    //         $j('#save_discountid').val('HSM');
    //         $j('#save_discount').val('0');
    //         $j('#save_discount_amnt').val(final_net_amount + final_discount);
    //     } else if(isPHS() || isInfirmaryOrDependent.trim() != ''){

    //         //updated by Nick, 4/8/2014 - fix infirmary/infirmary-dependent patients - sei7
    //         // if(isInfirmaryOrDependent.trim() != ''){
    //         //     $j('#amntlabel_discount').html("Total Discount : <strong>(" + isInfirmaryOrDependent.toUpperCase() + ')</strong>');
    //         //     if(isInfirmaryOrDependent.trim().toUpperCase() == 'INFIRMARY'){
    //         //         temp_discount = total_serv_excess + total_pf_excess + final_discount;
    //         //     }else if(isInfirmaryOrDependent.trim().toUpperCase() == 'DEPENDENT'){
    //         //         temp_discount = total_serv_excess + final_discount;
    //         //     }
    //         // }

    //         var net = parseFloat($j('#netbill').html().replace(',',''));
    //         var coverage = parseFloat($j('#HealthInsuranceTotal').html().replace(',',''));
    //         //alert(net +" = "+temp_discount+" = "+discount);
    //         $j('#DiscountTotal').html(numFormat(temp_discount));
    //         $j('#netamnt').html(numFormat(parseFloat(net) - (parseFloat(coverage) + parseFloat(temp_discount) + parseFloat(deposit)) ));
    //         $j('#save_discountid').val('Inf');
    //         $j('#save_discount').val('0');
    //         $j('#save_discount_amnt').val(temp_discount);
    //         //end Nick

    //     }
    // }

    disableImgDelete();

}
    //added by Nick, 1/1/2014

function getBillNr(){
    var data = new Object();
    data.encounter = $j('#encounter_nr').val();
    xajax_setBillNr(data);
    //console.log(bill_nr);
}

function setBillNr(nr){
    bill_nr = nr;
    $j('#bill_nr').val(nr);
}

function js_btnHandler(){
    getBillNr();
}

//added by janken 11/06/2014 for old soa
function getBillNr_Old(){
    var data = new Object();
    data.encounter = $j('#encounter_nr').val();
    xajax_setBillNrOld(data);
    //console.log(bill_nr);
}

function js_btnHandler_Old(){
    getBillNr_Old();
}

function showCF2Part3()
{
    var rpath = $('rpath').value;
    var enc = $('encounter_nr').value;
    var pid = $('pid').value;
    var bill_nr = $('bill_nr').value;
    var seg_URL_APPEND = $F('seg_URL_APPEND');

    urlholder = rpath+'modules/billing_new/billing-cf2-part3.php'+seg_URL_APPEND+
                '&pid='+pid+'&encounter_nr='+enc+'&bill_nr='+bill_nr;

    nleft = (screen.width - 680)/2;
    ntop = (screen.height - 520)/2;
     printwin = window.open(urlholder, "Print Billing", "toolbar=no, status=no, menubar=no, width=700, height=500, location=center, dependent=yes, resizable=yes, scrollbars=yes, top=" + ntop + ",left=" + nleft);


}

function showCF2Part4()
{
    var rpath = $('rpath').value;
    var enc = $('encounter_nr').value;
    var pid = $('pid').value;
    var bill_nr = $('bill_nr').value;
    var seg_URL_APPEND = $F('seg_URL_APPEND');

    urlholder = rpath+'modules/billing_new/billing-cf2-part4.php'+seg_URL_APPEND+
                '&pid='+pid+'&encounter_nr='+enc+'&bill_nr='+bill_nr;

    nleft = (screen.width - 680)/2;
    ntop = (screen.height - 520)/2;
     printwin = window.open(urlholder, "Print Billing", "toolbar=no, status=no, menubar=no, width=700, height=500, location=center, dependent=yes, resizable=yes, scrollbars=yes, top=" + ntop + ",left=" + nleft);


}

/* Added by jeff 04-05-18 */


function showSoa(){
    if(!bill_nr || bill_nr==""){
        alert("This bill has not been saved yet!");
        return;
    }
    //process_type = 'print';
    //$j('#btnSave').click();

    var rpath = $('rpath').value;
    var enc = $('encounter_nr').value;
    var pid = $('pid').value;
    var bill_dt = $('billdate').value;
    //var bill_nr = $('old_bill_nr').value;
    var frm_dte = $('bill_frmdte').value;
    var seg_URL_APPEND = $F('seg_URL_APPEND');

    if($('isdied').checked){
        var deathdate = $j('#deathdate').val();
    }else{
        var deathdate = '';
    }


        if (bill_nr == '') {
                var is_finalbill = ($('isFinalBill').checked) ? 1 : 0;
                if (is_finalbill) {
                    alert("This bill has not been saved yet! Please SAVE this final bill before printing!");
                    return;
                }
        }
        var detailed;
        if ($('IsDetailed').checked)
            detailed = 1;
        else
            detailed = 0;
        ///modules/billing_new/bill-pdf-summary.php
        urlholder = rpath+'modules/billing_new/bill-pdf-summary_new.php'+seg_URL_APPEND+
                          '&pid='+pid+'&encounter_nr='+enc+
                          '&from_dt='+(getDateFromFormat(frm_dte, 'yyyy-MM-dd HH:mm:ss')/1000)+
                          '&bill_dt='+(getDateFromFormat(bill_dt, 'yyyy-MM-dd HH:mm:ss')/1000)+
                          '&nr='+bill_nr+'&IsDetailed='+detailed+'&deathdate='+deathdate;

        nleft = (screen.width - 680)/2;
        ntop = (screen.height - 520)/2;
        if(enc != "" && pid != "")
        {
            if (/*ld_computed &&*/ (acc_computed!=null) && (med_computed!=null) && /*(xlo_computed!=null) &&*/ (ops_computed!=null) /*&& (pfs_computed!=null) &&*/ /*(msc_computed!=null)*/)
                    printwin = window.open(urlholder, "Print Billing", "toolbar=no, status=no, menubar=no, width=700, height=500, location=center, dependent=yes, resizable=yes, scrollbars=yes, top=" + ntop + ",left=" + nleft);
            else
                alert('Please wait for the billing calculation to complete!');
        }else{
            alert("Please specify patient!");
        }

        return true;
    }
//end nick

//added by janken 11/06/2014
function showSoaOld(){
    if(!bill_nr || bill_nr==""){
        alert("This bill has not been saved yet!");
        return;
    }
    //process_type = 'print';
    //$j('#btnSave').click();

    var rpath = $('rpath').value;
    var enc = $('encounter_nr').value;
    var pid = $('pid').value;
    var bill_dt = $('billdate').value;
    //var bill_nr = $('old_bill_nr').value;
    var frm_dte = $('bill_frmdte').value;
    var seg_URL_APPEND = $F('seg_URL_APPEND');

    if($('isdied').checked){
        var deathdate = $j('#deathdate').val();
    }else{
        var deathdate = '';
    }


        if (bill_nr == '') {
                var is_finalbill = ($('isFinalBill').checked) ? 1 : 0;
                if (is_finalbill) {
                    alert("This bill has not been saved yet! Please SAVE this final bill before printing!");
                    return;
                }
        }
        var detailed;
        if ($('IsDetailed').checked)
            detailed = 1;
        else
            detailed = 0;
        ///modules/billing_new/bill-pdf-summary.php
        urlholder = rpath+'modules/billing_new/bill-pdf-summary_old.php'+seg_URL_APPEND+
                          '&pid='+pid+'&encounter_nr='+enc+
                          '&from_dt='+(getDateFromFormat(frm_dte, 'yyyy-MM-dd HH:mm:ss')/1000)+
                          '&bill_dt='+(getDateFromFormat(bill_dt, 'yyyy-MM-dd HH:mm:ss')/1000)+
                          '&nr='+bill_nr+'&IsDetailed='+detailed+'&deathdate='+deathdate;

        nleft = (screen.width - 680)/2;
        ntop = (screen.height - 520)/2;
        if(enc != "" && pid != "")
        {
            if (/*ld_computed &&*/ (acc_computed!=null) && (med_computed!=null) && /*(xlo_computed!=null) &&*/ (ops_computed!=null) /*&& (pfs_computed!=null) &&*/ /*(msc_computed!=null)*/)
                    printwin = window.open(urlholder, "Print Billing", "toolbar=no, status=no, menubar=no, width=700, height=500, location=center, dependent=yes, resizable=yes, scrollbars=yes, top=" + ntop + ",left=" + nleft);
            else
                alert('Please wait for the billing calculation to complete!');
        }else{
            alert("Please specify patient!");
        }

        return true;
    }
//end nick



function isSponsoredMember()
{
    var mem_id = $j('#memcategory_id').val();
    if (mem_id==NBB) {
        return true;
    }else{
        return false;
    }
}


function isHSM()
{
    var mem_id = $j('#memcategory_id').val();
    if (mem_id==HSM) {
        return true;
    }else{
        return false;
    }
}


function assigPHS(bool)
{
    PHS = bool;
}


function isPHS()
{
    return PHS;
}

//updated by Nick 2/8/2014
function isPAYWARD()
{

    if($j('#accomodation_type').val() == 1)
        return false;
    else
        return true;


}

function setTotalDiscounts(data){
    total_applied_discount = data;
}


function jsOnchangeCaseType() {
    var enc = $j('#encounter_nr').val();
    var type = $j('#caseTypeOption').val();
    var bill_dte = $j('#billdate').val();
    var mem_category = $j('#mcategdesc').html();
    if(enc != '') {
        if (mem_category!=NBB_desc && type==7) {
            alert('Please change Member Category first to SPONSORED MEMBER before selecting this case type');
            $('caseTypeOption').selectedIndex = 0;
        }else if(mem_category!=HSM_desc && type==8) {
            alert('Please change Member Category first to HOSPITAL SPONSORED MEMBER before selecting this case type');
             $('caseTypeOption').selectedIndex = 0;
        }else{
            xajax_setCaseType(enc, type, bill_dte);
        }

    } else {
        alert('Please select patient first');
    }
}


function setHasBloodTrans(bool)
{
    $j('#hasbloodborrowed').val(bool);
}


/*function assignDrDetails(details)
{
    isphic = ($j('#phic').val().toUpperCase()=="NONE") ? false:true;
    if (typeof(details)=='object') {
        if (details.area == 'D1' && D1_nr == '') {
            D1_nr = details.dr_nr;
            D1_chrg = details.charge;
        }else if (details.area == 'D2' && D2_nr == '') {
            D2_nr = details.dr_nr;
            D2_chrg = details.charge;
        }else if (details.area == 'D3' && D3_nr == '') {
            D3_nr = details.dr_nr;
            D3_chrg = details.charge;
        }
        else if (details.area == 'D4' && D4_nr == '') {
            D4_nr = details.dr_nr;
            D4_chrg = details.charge;
        }

        var total_doc_Charge = details.totalCharge.replace(',','');
        if(details.area=='D1'){
           if(!isphic && !isPAYWARD()){
                D1_discount = total_doc_Charge
           }else if(total_applied_discount != ''){
                D1_discount = total_doc_Charge * total_applied_discount;
           }else{
                D1_discount = 0;
           }
        }else if(details.area == 'D2'){
            if(!isphic && !isPAYWARD()){
                D2_discount = total_doc_Charge;
           }else if(total_applied_discount != ''){
                D2_discount = total_doc_Charge * total_applied_discount;
           }else{
                D2_discount = 0;
           }
        }else if(details.area == 'D3'){
            if(!isphic && !isPAYWARD()){
                D3_discount = total_doc_Charge;
           }else if(total_applied_discount != ''){
                D3_discount = total_doc_Charge * total_applied_discount;
           }else{
                D3_discount = 0;
           }
        }else if(details.area == 'D4'){
            if(!isphic && !isPAYWARD()){
                D4_discount = total_doc_Charge;
           }else if(total_applied_discount != ''){
                D4_discount = total_doc_Charge * total_applied_discount;
           }else{
                D4_discount = 0;
           }
        }
   }
}*/


/*function clearDocVars()
{
    D1_nr = '';
    D1_chrg = '';
    D2_nr = '';
    D2_chrg = '';
    D3_nr = '';
    D3_chrg = '';
    D4_nr = '';
    D4_chrg = '';
    D1_discount = 0;
    D2_discount = 0;
    D3_discount = 0;
    D4_discount = 0;
}
*/

function setUnusedAmount(amnt)
{
    isphic = ($j('#phic').val().toUpperCase()=="NONE") ? false:true;
    if(isphic){
        TotalUnsed = amnt;
   }else{
        TotalUnsed = 0;
   }

}

//added by Nick, 4/8/2014
function setIsInfirmaryOrDependent(desc){
    isInfirmaryOrDependent = desc;
}

//added by Nick, 4/21/2014
function setIsNewBorn(data){
    isNewBorn = data;
    // if(isNewBorn){
    //     $('lblHearingTest').style.display = '';
    // }else{
    //     $('lblHearingTest').style.display = 'none';
    // }
}

//added by Nick, 4/21/2014
function setHasHearingTest(data){
    hasHearingTest = (data == 1) ? true : false;
    $('chkHearingTest').checked = hasHearingTest;
}

function debug(data){
    $j("<div><pre>"+data+"</pre></div>").dialog({
        autoOpen:true,
        width:"90%",
        height:500,
        position:"top"
    });
}
// end nick

function jsDoctorsCoverage(tblId,data)
{
    $(tblId).innerHTML += data;
}


function calculateDetails()
{
    var total_doc_Charge = 0,total_doc_discount = 0, area, hcare_id ='', temp_id = '',
        total_excess_x = 0, total_excess_y = 0, phic_coverage=0;
    var total_doc_Charge_pf = 0, total_excess_x_pf = 0, total_excess_y_pf = 0, total_doc_discount_pf=0, hmo_excess_pf=0, comp_total=0;
    var hcare_amount = new Array();

    var charge_field = $j('.calc-actual').serializeArray();
    var discount_field = $j('.calc-discount').serializeArray();
    var excess_field_x = $j('.calc-excess').serializeArray();
    var hcare_field = $j('.calc-hcare').serializeArray();

    var charge_field_PF = $j('.calc-actual-PF').serializeArray();
    var discount_field_PF = $j('.calc-discount-PF').serializeArray();
    var excess_field_x_PF = $j('.calc-excess-PF').serializeArray();
    var hcare_field_PF = $j('.calc-hcare-PF').serializeArray();
    var com_field = $j('.save-com-doc').serializeArray();

        D1_discount = 0;
        D2_discount = 0;
        D3_discount = 0;
        D4_discount = 0;

        D1_coverage = 0;
        D2_coverage = 0;
        D3_coverage = 0;
        D4_coverage = 0;

    $j.each(charge_field, function(i, field){
        var str = field.name.split("_");
        var charge = field.value;
        var dr_nr = str[2];
        var role = str[3]
        var temp_excess = 0;
        total_doc_Charge += parseFloat(field.value);

        $j.each(excess_field_x, function(i, field_excess){
            var str2 =  field_excess.name.split("_");
            if(dr_nr == str2[2] && role == str2[3])
                temp_excess += parseFloat(field_excess.value.replace(',',''));
        });

        total_excess_x = charge - temp_excess;
        total_excess_y += total_excess_x;
        $j('#total-excess-'+dr_nr+'-'+role).html(numFormat(total_excess_x));
    });

    //added by daryl
        $j.each(charge_field_PF, function(i, field){
        var strpf = field.name.split("_");
        var chargepf = field.value;
        var dr_nrpf = strpf[2];
        var rolepf = strpf[3]
        var temp_excesspf = 0;
        total_doc_Charge_pf += parseFloat(field.value);

        $j.each(excess_field_x_PF, function(i, field_excess){
            var str2pf =  field_excess.name.split("_");
            if (str2pf[1] != "discount" && str2pf[0] != "doc"){
                if(dr_nrpf == str2pf[2] && rolepf == str2pf[3])
                temp_excesspf += parseFloat(field_excess.value.replace(',',''));
            }

        });

        total_excess_x_pf = chargepf - temp_excesspf;
        total_excess_y_pf += total_excess_x_pf;

        hmo_excess_pf = parseFloat($('total-excess-'+dr_nrpf+'-'+rolepf).innerHTML.replace(',',''));

        $j('#total-excessdisc-'+dr_nrpf+'-'+rolepf).html(numFormat(hmo_excess_pf - temp_excesspf));
        // $j('#total-excessdisc-'+dr_nrpf+'-'+rolepf).html(numFormat(total_excess_x_pf));

    });

         $j.each(discount_field_PF, function(i, field){
        total_doc_discount_pf += parseFloat(field.value);
    });

               $j.each(hcare_field_PF, function(i, field){
        disc_id = $j('#'+field.name).attr('discID');
        $j('#totaldisc_'+disc_id).html(0);
    });

         $j.each(hcare_field_PF, function(i, field){
        disc_id = $j('#'+field.name).attr('discID');
        $j('#totaldisc_'+disc_id).html(numFormat(parseFloat($j('#totaldisc_'+disc_id).html().replace(',','')) + parseFloat(field.value.replace(',',''))));
    });
    //ended by daryl

    $j.each(discount_field, function(i, field){
        total_doc_discount += parseFloat(field.value);
        area = $j('#'+field.name).attr('area');
        if(area=="D1"){
            D1_discount += parseFloat(field.value.replace(',',''));
        }else if(area=="D2"){
            D2_discount += parseFloat(field.value.replace(',',''));
        }else if(area=="D3"){
            D3_discount += parseFloat(field.value.replace(',',''));
        }else if(area=="D4"){
            D4_discount += parseFloat(field.value.replace(',',''));
        }
    });

    $j.each(hcare_field, function(i, field){
        hcare_id = $j('#'+field.name).attr('hcareid');
        $j('#total_'+hcare_id).html(0);
    });

    $j.each(hcare_field, function(i, field){
        hcare_id = $j('#'+field.name).attr('hcareid');
        area = $j('#'+field.name).attr('itemcode');
        $j('#total_'+hcare_id).html(numFormat(parseFloat($j('#total_'+hcare_id).html().replace(',','')) + parseFloat(field.value.replace(',',''))));
        if(hcare_id=='18'){
            phic_coverage +=  parseFloat(field.value.replace(',',''));
            if(area=="D1"){
                D1_coverage += parseFloat(field.value.replace(',',''));
            }else if(area=="D2"){
                D2_coverage += parseFloat(field.value).replace(',','');
            }else if(area=="D3"){
                D3_coverage+= parseFloat(field.value.replace(',',''));
            }else if(area=="D4"){
                D4_coverage += parseFloat(field.value.replace(',',''));
            }
        }

    });

    $j('#company_doc_total').html('0.00');

    $j.each(com_field, function(i, field){
        str = field.name.split("_");
        dr_nr = str[2];
        role = str[3]
        com_id = $j('#'+field.name).attr('company_id');
        enc = $j('#'+field.name).attr('itemcode');
        comp_total +=  parseFloat(field.value.replace(',',''));

        //total_excess_y -= parseFloat(field.value.replace(',',''));
       // $j('#total-excess-'+dr_nr+'-'+role).html(numFormat(parseFloat($j('#total-excess-'+enc+'-'+str[3]).html().replace(',','')) - parseFloat(field.value.replace(',',''))));
        $j('#company_doc_total').html(numFormat(parseFloat($j('#company_doc_total').html().replace(',','')) + parseFloat(field.value.replace(',',''))));
    });

    setCoverageLimit();
    $j('#phic-max-PF').html(numFormat(parseFloat($j('#phic-max-PF').html().replace(',',''))- phic_coverage));
    $j('#phic-max-PF_disc').html(numFormat(parseFloat($j('#phic-max-PF_disc').html().replace(',',''))- phic_coverage));
    $j('#total-excess').html(numFormat(total_excess_y));
    $j('#total-charges').html(numFormat(total_doc_Charge));
    $j('#total-charges-PF').html(numFormat(total_doc_Charge_pf));
    $j('#total-discount').html(numFormat(total_doc_discount));
    $j('#total-discount-PF').html(numFormat(total_doc_discount_pf));
    $j('#total-excessdisc-PF').html(numFormat(total_excess_y_pf));

}



function applyAllCoverage(hcare_id,dr_nr,area)
{
    var is_apply = $('apply_'+hcare_id+'_'+dr_nr+'_'+area).checked;
    var dr_charge = parseFloat($j('#doc_charge_'+dr_nr+'_'+area).val());
    var dr_discount = parseFloat($j('#doc_discount_'+dr_nr+'_'+area).val());
    var phic_max = parseFloat($j('#phic-max-PF').html().replace(',',''));
    var temp_total = dr_charge - dr_discount;

    if(is_apply && (temp_total > phic_max)){
        $j('#coverage_'+hcare_id+'_'+dr_nr+'_'+area).val(numFormat(phic_max));
    }else if(is_apply){
        $j('#coverage_'+hcare_id+'_'+dr_nr+'_'+area).val(numFormat(temp_total));
    }else{
        $j('#coverage_'+hcare_id+'_'+dr_nr+'_'+area).val(0);
    }
    calculateDetails()

}


function setCoverageLimit()
{
    PF1 = $j("#first_rate option:selected").attr('value_pf');
    PF2 = $j("#second_rate option:selected").attr('value_pf');
    HF1 = $j("#first_rate option:selected").attr('value_hf');
    HF2 = $j("#second_rate option:selected").attr('value_hf');
    totalHealthInsurancePF  =  parseFloat(((PF1)? PF1 : 0 )) + parseFloat(((PF2)? PF2 : 0 ));
    totalHealthInsuranceHF1  =  parseFloat(((HF1)? HF1 : 0 )) + parseFloat(((HF2)? HF2 : 0 ));
    $j('#HC_first').html(numFormat(parseFloat(((HF1)? HF1 : 0 ))));
    $j('#HC_second').html(numFormat(parseFloat(((HF2)? HF2 : 0 ))));
    $j('#phic-max-PF').html(numFormat(totalHealthInsurancePF));
    $j('#phic-max-PF_disc').html(numFormat(totalHealthInsurancePF));
}


function checkNegation(obj)
{
    var hcare_field = $j('.calc-hcare').serializeArray();
    var phic_coverage = 0, val = 0;
    var str = obj.id.split("_");
    var dr_charge = parseFloat($j('#doc_charge_'+str[2]+'_'+str[3]).val());
    var dr_discount = parseFloat($j('#doc_discount_'+str[2]+'_'+str[3]).val());
    var temp_total = dr_charge - dr_discount;

    $j.each(hcare_field, function(i, field){
        hcare_id = $j('#'+field.name).attr('hcareid');
        $j('#total_'+hcare_id).html(numFormat(parseFloat($j('#total_'+hcare_id).html().replace(',','')) + parseFloat(field.value)));
        if(hcare_id=='18')
            phic_coverage +=  parseFloat(field.value);

    });
    var total_excess =  parseFloat($j('#total-excess-'+str[2]+'-'+str[3]).html().replace(',',''));
    if(parseFloat($j('#'+obj.id).val()) > total_excess)
        $j('#'+obj.id).val(numFormat(total_excess));

    parseFloat($j('#'+obj.id).val(numFormat(parseFloat($j('#'+obj.id).val().replace(',','')))));
    if((phic_coverage > totalHealthInsurancePF) || (temp_total < parseFloat($j('#'+obj.id).val()))) {
        alert('The amount you enter is greater than PHIC Maximum Coverage \n OR the greater than Actual Charge.');
        $j('#'+obj.id).val(0)
        $j('#total_18').html(0);
        $j.each(hcare_field, function(i, field){
            if(obj.name == field.name){
                val = 0;
            }else{
                val = field.value;
            }
            hcare_id = $j('#'+field.name).attr('hcareid');
            $j('#total_'+hcare_id).html(numFormat(parseFloat($j('#total_'+hcare_id).html().replace(',','')) + parseFloat(val)))
        });
        calculateDetails();
        return true;
    }else{
        return false;
    }
}


function assignDoctoTable()
{
    //First rate Info
    var PF1 = $j("#first_rate option:selected").attr('value_pf');
    var D1_first = $j("#first_rate option:selected").attr('value_D1');
    var D3_first = $j("#first_rate option:selected").attr('value_D3');
    var D4_first = $j("#first_rate option:selected").attr('value_D4');

    //Second rate info
    var PF2  = $j("#second_rate option:selected").attr('value_pf');
    var D1_second = $j("#second_rate option:selected").attr('value_D1');
    var D3_second = $j("#second_rate option:selected").attr('value_D3');
    var D4_second = $j("#second_rate option:selected").attr('value_D4');

    $j('#D1_first').html(((D1_first) ? numFormat(D1_first) : '0.00' ) );
    $j('#D1_second').html( ((D1_second) ? numFormat(D1_second) : '0.00' ) );
    $j('#D1_total').html( numFormat(parseFloat($j('#D1_first').html().replace(',','')) + parseFloat($j('#D1_second').html().replace(',',''))));
    $j('#D3_first').html( ((D3_first) ? numFormat(D3_first) : '0.00' ) );
    $j('#D3_second').html( ((D3_second) ? numFormat(D3_second) : '0.00') );
    $j('#D3_total').html( numFormat(parseFloat($j('#D3_first').html().replace(',','')) + parseFloat($j('#D3_second').html().replace(',',''))));
    $j('#D4_first').html( ((D4_first) ? numFormat(D4_first) : '0.00' )  );
    $j('#D4_second').html( ((D4_second) ? numFormat(D4_second) : '0.00') );
    $j('#D4_total').html( numFormat(parseFloat($j('#D4_first').html().replace(',','')) + parseFloat($j('#D4_second').html().replace(',',''))));

    $j('#first_total').html(((PF1) ? numFormat(PF1) : '0.00' ));
    $j('#second_total').html(((PF2) ? numFormat(PF2) : '0.00' ));

}

//added by daryl
function assignDoctoTable_disc()
{
    //First rate Info
    var PF1 = $j("#first_rate option:selected").attr('value_pf');
    var D1_first = $j("#first_rate option:selected").attr('value_D1');
    var D3_first = $j("#first_rate option:selected").attr('value_D3');
    var D4_first = $j("#first_rate option:selected").attr('value_D4');

    //Second rate info
    var PF2  = $j("#second_rate option:selected").attr('value_pf');
    var D1_second = $j("#second_rate option:selected").attr('value_D1');
    var D3_second = $j("#second_rate option:selected").attr('value_D3');
    var D4_second = $j("#second_rate option:selected").attr('value_D4');

    $j('#D1_first_disc').html(((D1_first) ? numFormat(D1_first) : '0.00' ) );
    $j('#D1_second_disc').html( ((D1_second) ? numFormat(D1_second) : '0.00' ) );
    $j('#D1_total_disc').html( numFormat(parseFloat($j('#D1_first_disc').html().replace(',','')) + parseFloat($j('#D1_second_disc').html().replace(',',''))));
    $j('#D3_first_disc').html( ((D3_first) ? numFormat(D3_first) : '0.00' ) );
    $j('#D3_second_disc').html( ((D3_second) ? numFormat(D3_second) : '0.00') );
    $j('#D3_total_disc').html( numFormat(parseFloat($j('#D3_first_disc').html().replace(',','')) + parseFloat($j('#D3_second_disc').html().replace(',',''))));
    $j('#D4_first_disc').html( ((D4_first) ? numFormat(D4_first) : '0.00' )  );
    $j('#D4_second_disc').html( ((D4_second) ? numFormat(D4_second) : '0.00') );
    $j('#D4_total_disc').html( numFormat(parseFloat($j('#D4_first_disc').html().replace(',','')) + parseFloat($j('#D4_second_disc').html().replace(',',''))));

    $j('#first_total_disc').html(((PF1) ? numFormat(PF1) : '0.00' ));
    $j('#second_total_disc').html(((PF2) ? numFormat(PF2) : '0.00' ));

}
//ended by daryl

//added by Nick, 05-12-2014
function addTooltip(elem,mod_id,mod_date){
    if(mod_id.trim() == "" || mod_date.trim() ==""){
        return;
    }
    var caption = "Modified by: " + mod_id + "<br>Modified date: " + mod_date;
    $j("#"+elem).hover(function(){
        return overlib(caption, CENTER);
    });

    $j("#"+elem).mouseleave(function(){
        return nd();
    });
}

//added by Nick, 05-12-2014
function showLoading(){
    isComputing = true;
    return overlib('Please Wait ...<br><img src="../../images/ajax_bar.gif">',
            WIDTH,300, TEXTPADDING,5, BORDER,0,
            STICKY, SCROLL, CLOSECLICK, MODAL,
            NOCLOSE, CAPTION,'',
            MIDX,0, MIDY,0,
            STATUS,'');

    // $j('#loadingBox')
    // .dialog({
    //     autoOpen:true,
    //     modal:true,
    //     title:"Loading",
    //     width:300,
    //     height:100,
    //     position:"top",
    //     closeOnEscape: false,
    //     open: function(event, ui){
    //         $j("#loadingBox .ui-dialog-titlebar-close ui-corner-all").hide();
    //     }
    // });
}

//added by Nick, 05-12-2014
function hideLoading(){
    isComputing = false;
    cClick();
    // $j('#loadingBox').dialog("close");
}

//added by Nick
function Dlg(elem,mode){
    $j('#'+elem).dialog(mode);
}

function hideDaysCovered(){
    $('coverdays').style.display = "none";
}

function calculateHFDetails(encounter="")
{
    xajax_jscalculateHFDetails_less_disc(encounter);
}

//added by daryl
//to calculate discounts in HCI
function calculateHFDetails_less_disc(discountid,acc,xlo,meds,or,misc,discountid_sc,discount_sc,companydetails,insurancedetails){
    var total_details_Charge = 0, area, hcare_id ='', temp_id = '', hcare = '', comp = 0, temp_hcare_id = '',
        total_excess_x = 0, total_excess_y = 0, phic_coverage=0, hmo_coverage = 0, hf_excess = 0, insurance_coverage = 0;
    var hcare_amount = new Array();
    var charge_field = $j('.calc-actual-1').serializeArray();
    var excess_field_x = $j('.calc-excess-1').serializeArray();
    var hcare_field = $j('.calc-hcare-1').serializeArray();
    var com_field = $j('.save-com-hci').serializeArray();
    var total_discounts = parseFloat($('hf_discount').innerHTML.replace(',',''));
    var detail_x = 0;
    var enc_x = 0;
    var discAmnt_acc = 0, discAmnt_xlo = 0, discAmnt_meds = 0, discAmnt_or = 0, discAmnt_misc = 0;
    var sc_acc = 0,sc_xlo = 0,sc_meds = 0,sc_or = 0,sc_misc = 0,sc_amnt = 0;
    var charge_acc = 0,charge_xlo = 0,charge_meds = 0,charge_or = 0,charge_misc = 0,charge_total = 0;
    var total_OTdisc = 0;
    var comp_acc = 0, comp_xlo = 0, comp_meds = 0, comp_or = 0, comp_misc = 0, comp_total = 0;
    var phic_acc = 0, phic_xlo = 0, phic_meds = 0, phic_or = 0, phic_misc = 0, phic_total = 0;
    var hmo_acc = 0, hmo_xlo = 0, hmo_meds = 0, hmo_or = 0, hmo_misc = 0, hmo_total = 0;

    $j.each(charge_field, function(i, field){
        var str = field.name.split("_");
        var charge = field.value;
        var detail = str[0];
        var enc = str[2];
        var temp_excess = 0;

        enc_x = enc;
        total_details_Charge += parseFloat(field.value);

        $j.each(excess_field_x, function(i, field_excess){
            var str2 =  field_excess.name.split("_");
            if(enc == str2[2] && detail == str2[3]){
                temp_excess += parseFloat(field_excess.value.replace(',',''));
            }
        });

        total_excess_x = (charge - temp_excess) ;
        hmo_coverage += temp_excess;
        total_excess_y += total_excess_x;

    });

    $j('#total-charges-hf').html(numFormat(total_details_Charge));

     if(companydetails['total'] != 0){
        comp_total = parseFloat(companydetails['total']);
        if(companydetails['acc'] != 0){
            comp_acc = parseFloat(companydetails['acc']);
        }
        if(companydetails['xlo'] != 0){
            comp_xlo = parseFloat(companydetails['xlo']);
        }
        if(companydetails['meds'] != 0){
            comp_meds = parseFloat(companydetails['meds']);
        }
        if(companydetails['or'] != 0){
            comp_or = parseFloat(companydetails['or']);
        }
        if(companydetails['misc'] != 0){
            comp_misc = parseFloat(companydetails['misc']);
        }
    }

    $j('#hf_comp').html(numFormat(comp_total));
    $j('#hf_comp_disc').html(numFormat(comp_total));


    if(insurancedetails['phic_total'] != 0){
        phic_total = parseFloat(insurancedetails['phic_total']);
        if(insurancedetails['acc'] != 0){
            phic_acc = parseFloat(insurancedetails['acc']);
        }
        if(insurancedetails['xlo'] != 0){
            phic_xlo = parseFloat(insurancedetails['xlo']);
        }
        if(insurancedetails['meds'] != 0){
            phic_meds = parseFloat(insurancedetails['meds']);
        }
        if(insurancedetails['or'] != 0){
            phic_or = parseFloat(insurancedetails['or']);
        }
        if(insurancedetails['misc'] != 0){
            phic_misc = parseFloat(insurancedetails['misc']);
        }
    }

    $j('#hf_phic').html(numFormat(phic_total));
    $j('#hf_phic_disc').html(numFormat(phic_total));

    if(insurancedetails['hmo_total'] != 0){
        hmo_total = parseFloat(insurancedetails['hmo_total'])
        if(insurancedetails['acc'] != 0){
            hmo_acc = parseFloat(insurancedetails['acc']);
        }
        if(insurancedetails['xlo'] != 0){
            hmo_xlo = parseFloat(insurancedetails['xlo']);
        }
        if(insurancedetails['meds'] != 0){
            hmo_meds = parseFloat(insurancedetails['meds']);
        }
        if(insurancedetails['or'] != 0){
            hmo_or = parseFloat(insurancedetails['or']);
        }
        if(insurancedetails['misc'] != 0){
            hmo_misc = parseFloat(insurancedetails['misc']);
        }
    }

    $j('#hf_hmo').html(numFormat(hmo_total));
    $j('#hf_hmo_disc').html(numFormat(hmo_total));


if(discountid_sc == "SC"){
      sc_acc = parseFloat($('discount_'+discountid_sc+"_"+enc_x+"_acc").innerHTML.replace(',',''));
      sc_xlo = parseFloat($('discount_'+discountid_sc+"_"+enc_x+"_xlo").innerHTML.replace(',',''));
      sc_meds = parseFloat($('discount_'+discountid_sc+"_"+enc_x+"_meds").innerHTML.replace(',',''));
      sc_or = parseFloat($('discount_'+discountid_sc+"_"+enc_x+"_or").innerHTML.replace(',',''));
      sc_misc = parseFloat($('discount_'+discountid_sc+"_"+enc_x+"_misc").innerHTML.replace(',',''));
      sc_amnt = (sc_acc + sc_xlo + sc_meds + sc_or + sc_misc) ;
      sc_total = numFormat(sc_amnt);

     $j('#HCI_total_'+discountid_sc).html(sc_total);
     $j('#HCI_totaldisc_'+discountid_sc).html(sc_total);
}

      charge_acc = parseFloat($('accAP').innerHTML.replace(',',''));
      charge_xlo = parseFloat($('hsAP').innerHTML.replace(',',''));
      charge_meds = parseFloat($('medAP').innerHTML.replace(',',''));
      charge_or = parseFloat($('opsAP').innerHTML.replace(',',''));
      charge_misc = parseFloat($('mscAP').innerHTML.replace(',',''));

      total_OTdisc = parseFloat($('hiDiscount').innerHTML.replace(',',''));

            $j('#total-excess-'+enc_x+'-'+"acc").html(numFormat((charge_acc) - sc_acc ));

            $j('#total-excess-'+enc_x+'-'+"xlo").html(numFormat((charge_xlo ) - sc_xlo));

            $j('#total-excess-'+enc_x+'-'+"meds").html(numFormat((charge_meds ) - sc_meds));

            $j('#total-excess-'+enc_x+'-'+"or").html(numFormat((charge_or ) - sc_or));

            $j('#total-excess-'+enc_x+'-'+"misc").html(numFormat((charge_misc ) - sc_misc));

     charge_total = (((charge_acc ) - sc_acc) +
                     ((charge_xlo ) - sc_xlo) +
                     ((charge_meds ) - sc_meds) +
                     ((charge_or ) - sc_or) +
                     ((charge_misc ) - sc_misc)
                            );

    $j.each(com_field, function(i, field){
        comp_id = $j('#'+field.name).attr('company_id');
        comp += parseFloat(field.value.replace(',',''));
        hmo_coverage -= parseFloat(field.value.replace(',',''));
    });

    $j.each(hcare_field, function(i, field){
        hcare_id = $j('#'+field.name).attr('hcareid');
        $j('#hf_total_'+hcare_id).html(numFormat(0));
    });

    $j.each(hcare_field, function(i, field){
        var str = field.name.split("_");
        hcare_id = $j('#'+field.name).attr('hcareid');
        enc = $j('#'+field.name).attr('itemcode');
        if(hcare_id=='18'){
            phic_coverage +=  parseFloat(field.value.replace(',',''));
            hmo_coverage -= parseFloat(field.value.replace(',',''));
            $j('#hf_phic').html(numFormat(phic_coverage));
            $j('#hf_phic_disc').html(numFormat(phic_coverage));
        }
        else{
            $j('#hf_hmo').html(numFormat(hmo_coverage));
        }
        charge_total -= parseFloat(field.value.replace(',',''));
        $j('#total-excess-'+enc+'-'+str[3]).html(numFormat(parseFloat($j('#total-excess-'+enc+'-'+str[3]).html().replace(',','')) - parseFloat(field.value.replace(',',''))));
        $j('#hf_total_'+hcare_id).html(numFormat(parseFloat($j('#hf_total_'+hcare_id).html().replace(',','')) + parseFloat(field.value.replace(',',''))));
    });

    $j('#company_total_hci').html('0.00');
    $j('#hf_comp').html('0.00');
    comp_total = 0;

    $j.each(com_field, function(i, field){
        var str = field.name.split("_");
        com_id = $j('#'+field.name).attr('company_id');
        enc = $j('#'+field.name).attr('itemcode');
        comp_total +=  parseFloat(field.value.replace(',',''));

        charge_total -= parseFloat(field.value.replace(',',''));
        $j('#total-excess-'+enc+'-'+str[3]).html(numFormat(parseFloat($j('#total-excess-'+enc+'-'+str[3]).html().replace(',','')) - parseFloat(field.value.replace(',',''))));
        $j('#company_total_hci').html(numFormat(parseFloat($j('#company_total_hci').html().replace(',','')) + parseFloat(field.value.replace(',',''))));
         $j('#hf_comp').html(numFormat(parseFloat($j('#hf_comp').html().replace(',','')) + parseFloat(field.value.replace(',',''))));
    });

    hf_excess = total_details_Charge - (phic_coverage + hmo_coverage + total_discounts + comp_total);

    setCoverageLimit();

    $j('#hf_excess').html(numFormat(total_details_Charge));
    $j('#hf_excess_disc').html(numFormat(total_details_Charge));
    $j('#HC_total').html(numFormat(hf_excess));
    // $j('#HC_total_disc').html(numFormat(hf_excess));
    $j('#total-excess-hf').html(numFormat(charge_total));
    $j('#hf_phic_limit').html(numFormat(totalHealthInsuranceHF1));
    $('hf_discount').innerHTML = numFormat(total_OTdisc);
    // $('hf_discount_disc').innerHTML = numFormat(total_OTdisc);
}

function calculateHFDetails_disc()
{
    var charge_field = $j('.calc-actual-disc-1').serializeArray();
    var enc_x = "";
    $j.each(charge_field, function(i, field){
        var str = field.name.split("_");
        var enc = str[2];
        enc_x = enc;
    });

xajax_get_phic(enc_x);

}

function calculateHFDetails_disc2(total_acc,total_xlo,total_meds,total_or,total_misc)
{
    var total_details_Charge = 0, area, discount_id ='', temp_id = '', hcare = '',
        total_excess_x = 0, total_excess_y = 0, phic_coverage=0, hmo_coverage = 0, hf_excess = 0;
    var hcare_amount = new Array();
    var charge_field = $j('.calc-actual-disc-1').serializeArray();
    var excess_field_x = $j('.calc-excess-disc-1').serializeArray();
    var sc_field = $j('.calc-sc-disc-1').serializeArray();
    var hcare_field = $j('.calc-discountpf-1').serializeArray();
    var total_discounts = parseFloat($('hf_discount').innerHTML.replace(',',''));
    var sc_val = 0, disc_sc = 0, disc_notsc = 0;
    var health_insurance = 0;
    $j.each(charge_field, function(i, field){
        var str = field.name.split("_");
        var charge = field.value;
        var detail = str[0];
        var enc = str[2];
        var temp_excess = 0;
        total_details_Charge += parseFloat(field.value);

        $j.each(excess_field_x, function(i, field_excess){
            var str2 =  field_excess.name.split("_");
            if(enc == str2[2] && detail == str2[3]){
                temp_excess += parseFloat(field_excess.value.replace(',',''));
            }
        });

        if ( str[3] == "withSC"){
             sc_val = parseFloat($('discount_SC'+"_"+enc+"_"+detail).innerHTML.replace(',',''));
             disc_sc = parseFloat($('HCI_totaldisc_SC').innerHTML.replace(',',''));
        } else{
             sc_val = 0;
        }
        if(detail == 'acc')
            health_insurance = total_acc;
        if(detail == 'xlo')
            health_insurance = total_xlo;
        if(detail == 'meds')
            health_insurance = total_meds;
        if(detail == 'or')
            health_insurance = total_or;
        if(detail == 'misc')
            health_insurance = total_misc;


        hmo_coverage += temp_excess;
        total_excess_x = (( charge - temp_excess ) - sc_val ) - health_insurance;
        total_excess_y += total_excess_x;

        $j('#total-excess-disc-'+enc+'-'+detail).html(numFormat(total_excess_x));
    });

    $j.each(hcare_field, function(i, field){
        discount_id = $j('#'+field.name).attr('disc_id');
        $j('#HCI_totaldisc_'+discount_id).html(0);
    });

    $j.each(hcare_field, function(i, field){
        discount_id = $j('#'+field.name).attr('disc_id');
        enc = $j('#'+field.name).attr('itemcode_disc');
        $j('#HCI_totaldisc_'+discount_id).html(numFormat(parseFloat($j('#HCI_totaldisc_'+discount_id).html().replace(',','')) + parseFloat(field.value.replace(',',''))));
        disc_notsc = parseFloat($('HCI_totaldisc_'+discount_id).innerHTML.replace(',',''));
    });

    setCoverageLimit();
    $j('#total-excess-disc-hf').html(numFormat(total_excess_y));
    $j('#total-charges-disc-hf').html(numFormat(total_details_Charge));
    $j('#HC_total_disc').html(numFormat(total_excess_y));
    $j('#hf_discount_disc').html(numFormat(disc_notsc + disc_sc));
    // alert(disc_notsc);

}
//ended by daryl

function saveHospitalCoverage()
{
    var data = new Array();
    var fields = $j('#hf-hci-coverage :input').serializeArray();
    data["enc"] = $j('#adj_enc').val();
    $j.each(fields, function(i, field){
        data[field.name] = parseFloat(field.value.replace(',',''));
    });

    xajax_saveHospitalCoverage(data);
}

//added by daryl
//saving of discounts Hospital
function saveHospitalDiscount()
{
    var data = new Array();
    var fields = $j('#hf-hci-discount :input').serializeArray();
    data["enc"] = $j('#adj_enc').val();
    $j.each(fields, function(i, field){
        data[field.name] = parseFloat(field.value.replace(',',''));
    });

    xajax_saveHospitalDiscount(data);
}
//ended by daryl

function setOtherCoverage(amount){
    totalHealthInsuranceHF += parseFloat(amount.replace(',',''));
}

function callHMOValue(enc){
    xajax_populateHMOValue(enc);
}

//added by daryl
//populate discount for hospital and docotr
function callHCIDiscount(enc){
    xajax_populateHCIdiscount(enc);
}

function setHCIDiscount(discountid, enc, total, amount, type){
    if(discountid == "SC"){
         var sc_acc = parseFloat($('discount_'+discountid+"_"+enc+"_acc").innerHTML.replace(',',''));
         var sc_xlo = parseFloat($('discount_'+discountid+"_"+enc+"_xlo").innerHTML.replace(',',''));
         var sc_meds = parseFloat($('discount_'+discountid+"_"+enc+"_meds").innerHTML.replace(',',''));
         var sc_or = parseFloat($('discount_'+discountid+"_"+enc+"_or").innerHTML.replace(',',''));
         var sc_misc = parseFloat($('discount_'+discountid+"_"+enc+"_misc").innerHTML.replace(',',''));
         var sc_amnt = (sc_acc + sc_xlo + sc_meds + sc_or + sc_misc) ;
         var sc_total = numFormat(sc_amnt);
         $j('#HCI_totaldisc_'+discountid).html(sc_total);
    }else{
         amount_ = numFormat(parseFloat(amount.replace(',','')));
        total_ = numFormat(parseFloat(total.replace(',','')));
        $j('#discount_'+discountid+'_'+enc+'_'+type).val(amount_);
        $j('#HCI_totaldisc_'+discountid).html(total_);
    }
}

function callProffDiscount(enc){
    xajax_populateProofdiscount(enc);
}

function setProofDiscount(discountid, enc, amount, type){
    amount_ = numFormat(parseFloat(amount.replace(',','')));
    $j('#discount_'+discountid+'_'+enc+'_'+type).val(amount_);
    $j('#PROFF_total_'+discountid).html(amount_);
}
//ended by daryl

function setHMOValue(hcare, enc, type, amount){
    amount = numFormat(parseFloat(amount.replace(',','')));
    $j('#coverage_'+hcare+'_'+enc+'_'+type).val(amount);
     calculateHFDetails(enc);
}

//added by janken 10/23/2014 to populate the set charges in cost centers
function setCompanyCharges(id, amount, src){
    amount = numFormat(parseFloat(amount.replace(',','')));
    com_total_amount += parseFloat(amount.replace(',',''));
    $j('#com_'+src.toLowerCase()).html(amount);
    $j('#com_total').html(numFormat(com_total_amount));
}

function set_hcidiscount(amount){
 hci_discount = parseFloat(amount.replace(',',''));
}

function set_pfdiscount(amount){
    //alert(amount);
pf_discount = parseFloat(amount.replace(',',''));

}

function printPrevBal(amnt){
    if(!amnt)
        amnt = 0;
    $j('#prev_bal').html(numFormat(amnt));
    prev_bal = parseFloat(amnt);
}

function checkHMONegation(field){
    field.value = parseFloat(field.value.replace(',',''));
    var hi_excess = parseFloat($j('#HC_total').html().replace(',',''));
    var current_excess = hi_excess - field.value;
    var str = field.name.split("_");
    var detail = str[3];
    var enc = str[2];
    var temp_excess = parseFloat($j('#total-excess-'+enc+'-'+detail).html().replace(',',''));
    var section_charge = parseFloat($j('#'+detail+'_charge_'+enc).val().replace(',',''));
    var section_excess = parseFloat($j('#'+detail+'_charge_'+enc).val().replace(',','')) - field.value;
    var field_excess = hi_excess - (field.value - (section_charge - temp_excess));

    if(field_excess < 0)
        field.value = numFormat(field.value);
    else{
        if(current_excess < 0){
            alert('The amount you have entered exceeds the Hospital Charge excess..');
            field.value = numFormat(field.value);
            return false;
        }
        else{
            if(section_excess < 0){
                field.value = numFormat(section_charge);
                return false;
            }
        field.value = numFormat(field.value);
        }
    }
}


//added by daryl
//limit of input discount in HCI
function checkDiscountLimit(field,val){

     var str = field.split("_");
     var old_amount = val.defaultValue

     var hi_excess = parseFloat($('hiEX').innerHTML.replace(',',''));
     var amount = parseFloat($j('#'+field).val().replace(',',''));

     var amount_acc = parseFloat($j('#'+str[0]+"_"+str[1]+"_"+str[2]+"_acc").val().replace(',',''));
     var amount_xlo = parseFloat($j('#'+str[0]+"_"+str[1]+"_"+str[2]+"_xlo").val().replace(',',''));
     var amount_meds = parseFloat($j('#'+str[0]+"_"+str[1]+"_"+str[2]+"_meds").val().replace(',',''));
     var amount_or = parseFloat($j('#'+str[0]+"_"+str[1]+"_"+str[2]+"_or").val().replace(',',''));
     var amount_misc = parseFloat($j('#'+str[0]+"_"+str[1]+"_"+str[2]+"_misc").val().replace(',',''));

     var excess_acc = parseFloat($('total-excess-disc'+"-"+str[2]+"-acc").innerHTML.replace(',',''));
     var excess_xlo = parseFloat($('total-excess-disc'+"-"+str[2]+"-xlo").innerHTML.replace(',',''));
     var excess_meds = parseFloat($('total-excess-disc'+"-"+str[2]+"-meds").innerHTML.replace(',',''));
     var excess_or = parseFloat($('total-excess-disc'+"-"+str[2]+"-or").innerHTML.replace(',',''));
     var excess_misc = parseFloat($('total-excess-disc'+"-"+str[2]+"-misc").innerHTML.replace(',',''));

     var total_amount = amount_acc+amount_xlo+amount_meds+amount_or+amount_misc;
     val.value = parseFloat(val.value.replace(',',''));

     if (str[3] == "acc"){
         if (amount>excess_acc){
             alert('The amount you have entered exceeds the Hospital Accomodation excess..');
             val.value = numFormat(old_amount);
             return false;
        }else if (total_amount>hi_excess){
             alert('The amount you have entered exceeds the Hospital Charge excess..');
             val.value = numFormat(old_amount);
             return false;
        }else{
             val.value =  numFormat(amount);
             $j('#HCI_totaldisc_'+str[1]).html(numFormat(total_amount));
        }
     }else if (str[3] == "xlo"){
         if (amount>excess_xlo){
             alert('The amount you have entered exceeds the Hospital XLO excess..');
             val.value = numFormat(old_amount);
             return false;
        }else if (total_amount>hi_excess){
             alert('The amount you have entered exceeds the Hospital Charge excess..');
             val.value = numFormat(old_amount);
             return false;
        }else{
             val.value =  numFormat(amount);
             $j('#HCI_totaldisc_'+str[1]).html(numFormat(total_amount));
        }
     }else if (str[3] == "meds"){
         if (amount>excess_meds){
             alert('The amount you have entered exceeds the Hospital Drugs & Meds excess..');
             val.value = numFormat(old_amount);
             return false;
        }else if (total_amount>hi_excess){
             alert('The amount you have entered exceeds the Hospital Charge excess..');
             val.value = numFormat(old_amount);
             return false;
        }else{
             val.value =  numFormat(amount);
             $j('#HCI_totaldisc_'+str[1]).html(numFormat(total_amount));
        }
     }else if (str[3] == "or"){
         if (amount>excess_or){
             alert('The amount you have entered exceeds the Hospital  Operating Room excess..');
             val.value = numFormat(old_amount);
             return false;
        }else if (total_amount>hi_excess){
             alert('The amount you have entered exceeds the Hospital Charge excess..');
             val.value = numFormat(old_amount);
             return false;
        }else{
             val.value =  numFormat(amount);
             $j('#HCI_totaldisc_'+str[1]).html(numFormat(total_amount));
        }
     }else if (str[3] == "misc"){
         if (amount>excess_misc){
             alert('The amount you have entered exceeds the Hospital MISCELLANEOUS excess..');
             val.value = numFormat(old_amount);
             return false;
        }else if (total_amount>hi_excess){
             alert('The amount you have entered exceeds the Hospital Charge excess..');
             val.value = numFormat(old_amount);
             return false;
        }else{
             val.value =  numFormat(amount);
             $j('#HCI_totaldisc_'+str[1]).html(numFormat(total_amount));
        }
     }
    calculateHFDetails_disc();
      xajax_populateAdjCoverage(enc, acc_computed, miscServices_computed, med_computed, miscCharges_computed, ops_computed);
}

//ended by daryl


// Added by Jeff 04-16-18
function checkDrAccreditation(id,is_valid,prompt_msg){
    if (is_valid == '0') {
        alert(prompt_msg);
        $j('#apply_'+id).attr("checked",false);
    }
}