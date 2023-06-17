/*Created by mai 06-26-2014*/
var member, pageParams = null;

function showVerificationResult(data) {
    if (window.parent.$('encounter_nr')){
        var enc = window.parent.$('encounter_nr').value;
    } else {
        var pid = $('pid').value;
        var enc ='';
    }


	var urlholder = '../../modules/eclaims/reports/cewsreport.php?data='+encodeURIComponent(data)+'&frombilling='+getPageParam('frombilling')+'&enc='+enc+'&pid='+pid;

	nleft = (screen.width - 680)/2;
	ntop = (screen.height - 520)/2;
	printwin = window.open(urlholder, "Verification Result", "toolbar=no, status=no, menubar=no, width=700, height=500, location=center, dependent=yes, resizable=yes, scrollbars=yes, top=" + ntop + ",left=" + nleft);
	return true;
}

function prepareAdd(id, bjustadded, data) {

	var details = new Object();
	details.id = $('id'+id).value;
	details.name = $('name'+id).innerHTML;

	if (data) {
		details.principal_id = data.pid;
		details.last_name = data.last_name;
		details.first_name = data.first_name;
		details.middle_name = data.middle_name;
		details.street = data.street;
		details.infosrc = data.infosrc;
		details.is_updated = 1;
		details.nr = data.insurance_nr;
        details.trackingno = '';
	}
	else {
		details.principal_id = '';
		details.last_name = '';
		details.first_name = '';
		details.middle_name = '';
		details.street = '';
		details.infosrc = 2;
		details.is_updated = 0;
		details.nr= $('nr'+id).value;
        details.trackingno = '';
	}

	if ($('isPrincipal'+id).checked){
		details.isPrincipal2 = "YES";
		details.isPrincipal = 1;
	}else{
		details.isPrincipal2 = "NO";
		details.isPrincipal = 0;
	}
	var list = window.parent.document.getElementById('order-list');
	result = window.parent.appendOrder(list,details, bjustadded);
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

function getPageParam(param)
{
    if (pageParams === null) {
        var hash,
            hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');

        pageParams = [];

        for(var i = 0; i < hashes.length; i++)
        {
            hash = hashes[i].split('=');
            pageParams.push(hash[0]);
            pageParams[hash[0]] = hash[1];
        }
    }
    if ('undefined' !== typeof pageParams[param]) {
        return pageParams[param];
    } else {
        return null;
    }
}

function disableButtonAdd(id){
	document.getElementById('add_insurance'+id).disabled=true;
	document.getElementById('isPrincipal'+id).disabled=true;
}


function addProductToList(listID, id, firm_id, name, encounter_nr, max_amount, remarks) {
	var list= $(listID), dRows, dBody, rowSrc;
	var i;

	if (list) {
		dBody=list.getElementsByTagName("tbody")[0];
		dRows=dBody.getElementsByTagName("tr");

		if(id){

				alt = (dRows.length%2)+1;
				var img, disable;

				if (encounter_nr){
					img = '<img src="../../images/cashier_edit_3.gif"></img>';
                } else{
                    img = '';
                }

				rowSrc = "<tr>"+
                            '<td>'+
                                '<input type="hidden" id="max_amount_'+id+'" value="'+max_amount+'"/>'+
                                '<input type="hidden" id="remarks_'+id+'" value="'+remarks+'"/>'+
                                '<div id="name'+id+'" style="font:bold 12px Arial;color:#660000">'+firm_id+'</div>'+
                                '<div id="desc'+id+'" style="font:normal 11px Arial; color:#003366">'+name+'</div>'+
							'</td>'+
                            '<td align="center">'+img+'</td>'+
                            '<td align="center" style="white-space:nowrap">'+
                                '<button class="segButton" name="add_insurance'+id+'" id="add_insurance'+id+'" '+'data-provider="' + id + '" '+
                                    'onclick="prepareHolderData(\'' + id  +'\',\'' + name + '\'); return false;" '+ '><img src="../../gui/img/common/default/text_list_bullets.png" />Details</button>'+
                            '</td>'+
							'</tr>';
		}

		else {
			rowSrc = '<tr><td colspan="9" style="">No such company exists...</td></tr>';
		}

		dBody.innerHTML += rowSrc;
	}
}

function prepareHolderData(comp_id, comp_name) {
    var maxAmount = $J("#max_amount_"+comp_id).val();
    var remarks = $J("#remarks_"+comp_id).val();
    promptMemberInfo(maxAmount, comp_id, comp_name, remarks);
}

function promptMemberInfo(maxAmount,companyId, comp_name, remarks){
	// load the member form using ajax
	jQuery.get("seg-companyinfo.php",
        {
        	comp_name: comp_name,
            comp_id: companyId,
            max_amount: maxAmount,
            encounter_nr: getPageParam('encounter_nr'),
            remarks: remarks
        },
		function(data){
			// create a modal dialog with the data
			jQuery(data).modal({
				closeHTML: "<a href='#' title='Close' class='modal-close'>x</a>",
                modal: true,
				overlayId: 'modal-overlay',
				containerId: 'member-container',
                position: ['5%'],
				onOpen: member.open,
				onShow: member.show,
				onClose: member.close
			});
		}
	);
}


jQuery(function($) {
	member = {
		messages: [],
		open: function (dialog) {
			dialog.overlay.fadeIn(200, function () {
				dialog.container.fadeIn(200, function () {
					dialog.data.fadeIn(200, function () {});
				});
			});
		},
        save: function() {
                var params = {};
                $J.each($J('#member-form').serializeArray(), function(index,value) {
                    params[value.name] = value.value;
                });
                $J.get("ajax/save_chargecompany_info.php", params,
                    function (data) {
                            member.clearMessages();
                            window.parent.location.reload();   
                    }
                ).error(function(problem) {
                    member.clearMessages();
                    if (problem.responseText) {
                        member.addMessage(problem.responseText);
                    } else {
                        member.addMessage(problem.statusText);
                    }
                    member.showMessages();
                });
        },
		show: function(dialog) {
            $('#member-save').click(function(e) {
                e.preventDefault();
                member.save(dialog);
            });

            $('#member-cancel').click(function (e) {
                e.preventDefault();
                member.close(dialog);
            });
		},
		close: function (dialog) {
            $('.simplemodal-data button').attr('disabled', 'disabled');
			$('#member-container .modal-message').fadeOut();
			$('#member-container form').fadeOut(200);
			$('#member-container .modal-content').animate({
				height: 40
			}, function () {
				dialog.data.fadeOut(200, function () {
//					dialog.container.fadeOut(200, function () {
                    dialog.overlay.fadeOut(200, function () {
                        $.modal.close();
                    });
//					});
				});
			});
		},
		error: function (xhr) {
			alert(xhr.statusText);
		},

        addMessage: function (msg) {
            member.messages.push(msg);
        },

        clearMessages: function () {
            member.messages = [];
            $('#member-container .modal-message').text('');
        },

		showMessages: function () {
            var msg = $('<div class="message-error"></div>');
            msg.append(member.messages.join('<br/>'));
            msg.click(function(){
                var $this=$(this);
                $this.fadeOut(200);
            });
			$('#member-container .modal-message')
                .hide()
			    .append(msg)
			    .fadeIn(200);
}	};
});