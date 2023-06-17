<?php
/*Created by mai 06-27-2014*/
require('./roots.php');
require($root_path.'include/inc_environment_global.php');
require_once($root_path.'include/care_api_classes/class_person.php');
require_once($root_path.'include/care_api_classes/class_encounter.php');
header('Content-Type: text/html; charset=iso-8859-1');

// Process page parameters
$comp_id = !empty($_GET["comp_id"]) ? $_GET["comp_id"] : null;
$comp_name = $_GET['comp_name'];
$max_amount = !empty($_GET["max_amount"]) ? number_format($_GET["max_amount"], 2) : null;
$encounter_nr = isset($_GET["encounter_nr"]) ? $_GET["encounter_nr"] : null;
$remarks = !empty($_GET["remarks"]) ? $_GET["remarks"] : null;
?>

<div class="modal-top"></div>
<div class="modal-content">
    <h1 class="modal-title">Charge Details</h1>
    <div class="modal-loading"></div>
    <div class="modal-message"></div>
    <form id="member-form" action="#">
        <label>Company</label>
        <input type="text" id="comp_name" class="segInput required-verify required-save" value="<?=$comp_name ?>" disabled><br>
        <label for="member-id">Maximum Charge</label>
        <input type="text" id="max_amount" class="segInput required-verify required-save" name="max_amount" value="<?=$max_amount ?>" /><br>
        <label>Remarks</label>
        <textarea id="remarks" name="remarks" class="segInput required-verify required-save"><?php echo $remarks?></textarea>
        <input type="hidden" id="comp_id" name="comp_id" value="<?= $comp_id?>"/>
        <input type="hidden" id="encounter_nr" name="encounter_nr" value="<?= $encounter_nr ?>" />
    </form>
</div>

<div class="modal-bottom" style="text-align:center">
    <button id="member-save" type="button" class="segButton">
        <img src="../../gui/img/common/default/add.png" />Charge to company
    </button>

    <button id="member-cancel" type="button" class="segButton">
        <img src="../../gui/img/common/default/cancel.png" />Close
    </button>
</div>