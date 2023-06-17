function closeRelInfoPrompt() {
    window.parent.cClick();
}

function addSelectedItem() {

    var id = $('item_code').value;
    var unit = $('unit').value;
    var unit_name = $('unit_name').value;
    var isperpc = $('isperpc').value;
    var qty = $('item_qty').value;
    qty = qty.replace(",","");
    var expiry_dt = '';
    var serial_no = '';
    var lot_no = '';

    //added by bryan 102709
    var price_add = $('price_add').value;
    //alert(price_add);
    if ($('chk_expiry').checked) expiry_dt = $('expiry_date').value;
    if ($('chk_serial').checked) serial_no = $('serial_no').value;    
    if ($('chk_lot').checked) lot_no = $('lot_no').value;
    window.parent.prepareAdd(id, unit, unit_name, isperpc, qty, expiry_dt, serial_no, lot_no, price_add);
    window.parent.cClick();  
}

//added by ken: for updating the entry items in delivery tray

function updateSelectedItem() {
    var id = $('item_code').value;
    var qty = $('item_qty').value;
    var srow = $('srow').value;
    qty = qty.replace(",","");
    var expiry_dt = '';
    var serial_no = '';
    var lot_no = '';
    var price_add = $('price_add').value;

    if ($('chk_expiry').checked) expiry_dt = $('expiry_date').value;
    if ($('chk_serial').checked) serial_no = $('serial_no').value; 
    if ($('chk_lot').checked) lot_no = $('lot_no').value;

    window.parent.updateItem(id, qty, expiry_dt, serial_no, price_add, srow, lot_no);  

}

function checkQty(order, old_qty){
    var qty = $('item_qty').value;
    if(order){
        if(qty > old_qty){
            alert('Quantity exceeded in ordered quantity');
            $('item_qty').value = old_qty; 
        }
    }
}

//ended by ken
