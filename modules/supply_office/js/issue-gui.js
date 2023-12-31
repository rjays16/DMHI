
var ViewMode = false;

function jsRqstngAreaOptionChngIss(obj, value){
    if(obj.id == 'area_issued') {        
        //$('opw_nr').value  = value;   
        js_ClearOptions('area_dest');
        xajax_getRequestedAreasIss(value);
    }
}

function resetRefNo(newRefNo,error) {
    $("refno").style.color = error ? "#ff0000" : "";
    $("refno").value=newRefNo;
}

function showRequestedAreasIss(options) {
    $('destinationIss_area').innerHTML = options;
}

function jsAreaSRCOptionChngIss(value){ 
        js_ClearOptions('area_issued');
        xajax_getSourceAreasbypidIss(value);   
}

function showRequestedSRCAreasIss(options) {
    $('sourceIss_area').innerHTML = options;
}


function display(str) {
    document.write(str);
}

var totalDiscount = 0;

function parseFloatEx(x) {
    var str = x.toString().replace(/\,|\s/,'')
    return parseFloat(str)
}

function warnClear() {
    var items = document.getElementsByName('items[]');
    if (items.length == 0) return true;
    else return confirm('Performing this action will clear the order tray. Do you wish to continue?');
}

    
function emptyTray() {
    clearOrder($('order-list'));
    appendOrder($('order-list'),null);
    refreshDiscount();
}

function clearOrder(list) {    
    if (!list) list = $('order-list')
    if (list) {
        var dBody=list.getElementsByTagName("tbody")[0]
        if (dBody) {
//            trayItems = 0
            dBody.innerHTML = ""
            return true
        }
    }
    return false
}

function appendOrder(list, details, disabled, cnt) {

    if (!list) list = $('order-list');
    if (list) {
        var dBody=list.getElementsByTagName("tbody")[0];

        if (dBody) {
            var src;
            var totalqty;
            var lastRowNum = null,
                    items = document.getElementsByName('items[]');
                    dRows = dBody.getElementsByTagName("tr");
            if (details) {
                var id = details.id,
                    name = details.name,
                    desc = details.desc,
                    pending = details.pending,
                    d = details.d,
                    soc = details.soc,
                    unitid = details.unitid,
                    unitdesc = details.unitdesc,
                    perpc = details.perpc,
                    type = details.type,
                    expdate = details.expdate,
                    avg = ((typeof(details.avg) == 'undefined') ? 0 : details.avg),
                    qtyperpack = details.qtyperpack,
                    serial = details.serial,
                    epropno = details.epropno,
                    eestlife = details.eestlife;

                details.avg = ((typeof(details.avg) == 'undefined') ? 0 : details.avg)

                //prototype js selectors
                var row = $$("tr[data-item='" + id + "']");
                if(row.length > 0) {

                    var confirmed = confirm('Item Exists on Tray. Do you want to overwrite?');
                    if(!confirmed)
                        return false;

                    var rowNo = row[0].dataset['row'];
                    var itemCode = row[0].dataset['item'];
                    //remove item row if exists
                    removeItem(itemCode, rowNo);
                }

                if (items.length == 0) {
                        clearOrder(list);
                        rowno = String(1);
                    }
                    else
                        rowno = String(items.length + 1);

                if (items) {
                    if ($('rowID'+id)) {

                        var itemRow = $('row'+id),
                                itemQty = $('rowQty'+id)
                        itemQty.value = parseFloatEx(itemQty.value) + parseFloatEx(details.qty)
                        itemQty.setAttribute('prevValue',itemQty.value)
                        qty = parseFloatEx(itemQty.value)
                        tot = netPrice*qty
                        //$('rowid'+id).value     =   details.id
                        $('rowname'+id+rowno).value    = details.name
                        $('rowrefno'+id+rowno).value            = details.refno
                        $('rowdesc'+id+rowno).value            = details.desc
                        $('rowpending'+id+rowno).value        = details.pending
                        $('rowunitid'+id+rowno).value        = details.unitid
                        $('rowunitdesc'+id+rowno).value        = details.unitdesc
                        $('rowperpc'+id+rowno).value        = details.perpc
                        $('rowexpdate'+id+rowno).value        = details.expdate
                        $('rowserial'+id+rowno).value        = details.serial
                        $('rowd'+id+rowno).value        = details.d
                        $('rowsoc'+id+rowno).value        = details.soc

                        return true;
                    }
                    if (items.length == 0) clearOrder(list)
                }

                var qtyslashcommas = details.pending;
                qtyslashcommas = qtyslashcommas.replace(',', '');
                qtyslashcommas = parseFloat(qtyslashcommas);

                alt = (dRows.length%2)+1

                var disabledAttrib = disabled ? 'disabled="disabled"' : ""

                src =
                    '<tr class="wardlistrow'+alt+'" id="row'+id+rowno+'" data-item="' + id + '" data-row = "' + rowno + '">' +
                    '<input type="hidden" name="soc[]" id="rowsoc'+id+rowno+'" value="'+details.soc+'" />'+
                    '<input type="hidden" name="d[]" id="rows'+id+rowno+'" value="'+details.d+'" />'+
                    '<input type="hidden" name="pending[]" id="rowpending'+id+rowno+'" value="'+qtyslashcommas+'" />'+
                    '<input type="hidden" name="desc[]" id="rowdesc'+id+rowno+'" value="'+details.desc+'" />'+
                    '<input type="hidden" name="name[]" id="rowname'+id+rowno+'" value="'+details.name+'" />'+
                    '<input type="hidden" name="unitid[]" id="rowunitid'+id+rowno+'" value="'+details.unitid+'" />'+
                    '<input type="hidden" name="unitdesc[]" id="rowunitdesc'+id+rowno+'" value="'+details.unitdesc+'" />'+
                    '<input type="hidden" name="perpc[]" id="rowperpc'+id+rowno+'" value="'+details.perpc+'" />'+
                    '<input type="hidden" name="avg[]" id="rowavg'+id+rowno+'" value="'+details.avg+'" />'+
                    '<input type="hidden" name="expdate[]" id="rowexpdate'+id+rowno+'" value="'+details.expdate+'" />'+
                    '<input type="hidden" name="serial[]" id="rowserial'+id+rowno+'" value="'+details.serial+'" />'+
                    '<input type="hidden" name="epropno[]" id="rowepropno'+id+rowno+'" value="'+details.epropno+'" />'+
                    '<input type="hidden" name="eestlife[]" id="roweestlife'+id+rowno+'" value="'+details.eestlife+'" />'+
                    '<input type="hidden" name="items[]" id="rowis'+id+rowno+'" value="'+details.id+'" />';

                if (disabled)
                    src+='<td></td>'
                else
                    src+='<td class="centerAlign" width="5%"><img class="segSimulatedLink" src="../../images/close_small.gif" border="0" onclick="removeItem(\''+id+'\',\''+rowno+'\')"/></td>'

                if(perpc == 1) {
                    totalqty = qtyslashcommas * avg;
                }
                else {
                    totalqty = qtyslashcommas * avg * qtyperpack;
                }

                src+=
                    '<td>'+details.id+'</td>'+
                    '<td ><span style="color:#660000">'+details.name+'</span></td>'+
                    '<td ><span style="color:#660000">'+details.desc+'</span></td>'+
                    '<td align="center"><span style="color:#660000">'+qtyslashcommas+'</span></td>'+
                    '<td align="center"><span style="color:#660000">'+details.unitdesc+'</span></td>'+
                    '<td align="right"><span style="color:#660000">'+formatNumber(Number(details.avg),2)+'</span></td>'+
                    '<td align="right"><span style="color:#660000">'+formatNumber(Number(totalqty),2)+'</span></td></tr>';

//                trayItems++;
            }
            else {
                src = "<tr><td colspan=\"10\">Issue list is currently empty...</td></tr>";
            }

            dBody.innerHTML += src;
            return true;
        }
    }
    return false;
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
    
}

function formatNumber(num,dec) {
    var nf = new NumberFormat(num);
    if (isNaN(dec)) dec = nf.NO_ROUNDING;
    nf.setPlaces(dec);
    return nf.toFormatted();
}

function pSearchClose() {
    var nr = $('encounter_nr').value;

    cClick();
}

function removeItem(id,rowno) {
    var destTable, destRows;
    var table = $('order-list');
    var rmvRow=document.getElementById("row"+id+rowno);
    if (table && rmvRow) {
        var rndx = rmvRow.rowIndex-1;
        table.deleteRow(rmvRow.rowIndex);
        if (!document.getElementsByName("items[]") || document.getElementsByName("items[]").length <= 0)
            appendOrder(table, null);
        reclassRows(table,rndx);
    }
    refreshTotal();
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
