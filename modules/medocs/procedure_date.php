<?php
require('./roots.php');

require($root_path.'modules/medocs/ajax/medocs_common.php'); 
require($root_path.'include/inc_environment_global.php');
require_once($root_path.'include/inc_date_format_functions.php');

echo '<script type="text/javascript" src="'.$root_path.'modules/medocs/js/medocs_function.js"></script>';


$encounter = $_GET['enc'];
$encounter_type = $_GET['enc_type'];
$adate = $_GET['aDate'];
$code = $_GET['code'];
$doc_nr = $_GET['doc_nr'];
$dept_nr = $_GET['dept_nr'];
$create_id = $_GET['create_id'];
$target = $_GET['target'];
$type = $_GET['type'];
?>
<html lang="en">
<head>
	<meta charset="utf-8">

</head>
<body>

<!-- <p> Select Date: <input type="text" id="datepicker"></p> -->



 <table border=0 cellpadding=1 cellspacing=1 align="center" >
	<tr class="adm_item">
		<td bgcolor="C0D5BF"><FONT color="#000066" font-style='arial'>Procedures Date:</td></tr>
		<tr><td>
		<div id="opDateBox">
		<div class="bd">
			   
				<table width="100%" class="segPanel">
					<tr><td>
						<table width="100%" border="0">
							<tbody id="opDate-body">
							</tbody>
						</table>
					</td></tr>
				</table>
				<input type="text" id="datepicker" name="datepicker"  required readonly/>         
		
		</div>
		</div>
		</td></tr>
		<input type="hidden" name="encounter_nr" id="encounter_nr" value="<?=$encounter?>"></input>
		<input type="hidden" name="encounter_type" id="encounter_type" value="<?=$encounter_type?>"></input>
		<input type="hidden" name="adate" id="adate" value="<?=$adate?>"></input>
		<input type="hidden" name="code" id="code" value="<?=$code?>"></input>
		<input type="hidden" name="doc_nr" id="doc_nr" value="<?=$doc_nr?>"></input>
		<input type="hidden" name="dept_nr" id="dept_nr" value="<?=$dept_nr?>"></input>
		<input type="hidden" name="create_id" id="create_id" value="<?=$create_id?>"></input>
		<input type="hidden" name="target" id="target" value="<?=$target?>"></input>
		<input type="hidden" name="type" id="type" value="<?=$type?>"></input>

	</tr>

	<tr>
		
		<td colspan='2' align="center"><input  height="23" width="72" type="image" src="../../gui/img/control/default/en/en_savedisc.gif"  onClick="savedata();"></td>
	</tr>
</table>	
		<input type="hidden" name="mode" id="mode" value="save"></input>



</body>
</html>

<style type="text/css">
body {
	font-size: 62.5%;
	font-family: "Trebuchet MS", "Arial", "Helvetica", "Verdana", "sans-serif";
	background-color: #FFFFCC;
}

table {
	font-size: 1em;
}

.demo-description {
	clear: both;
	padding: 12px;
	font-size: 1.3em;
	line-height: 1.4em;
}

.ui-draggable, .ui-droppable {
	background-position: top;
}
</style>

<link rel="stylesheet" href="<?= $root_path ?>js/jquery/themes/seg-ui/jquery.ui.all.css" type="text/css" /> 
<script type="text/javascript" src="<?= $root_path ?>js/jquery/jquery-1.10.2.js"></script> 
<script type="text/javascript" src="<?= $root_path ?>js/jquery/ui/jquery.ui.core.js"></script> 
<script type="text/javascript" src="<?= $root_path ?>js/jquery/ui/jquery.ui.widget.js"></script> 
<script type="text/javascript" src="<?= $root_path ?>js/jquery/ui/jquery.ui.datepicker.js"></script> 
<script type="text/javascript" src="<?= $root_path ?>modules/medocs/js/medocs_function.js"></script>
<?php
$xajax->printJavascript($root_path.'classes/xajax');

?>
	<script>
	$(function() {
		$( "#datepicker" ).datepicker({
			changeMonth: true,
			changeYear: true
		});
	});


	function savedata(){
    var encounter_nr = document.getElementById('encounter_nr').value;
    var encounter_type = document.getElementById('encounter_type').value;
    var adate = document.getElementById('adate').value;
    var code = document.getElementById('code').value;
    var doc_nr = document.getElementById('doc_nr').value;
    var dept_nr = document.getElementById('dept_nr').value;
    var create_id = document.getElementById('create_id').value;
    var target = document.getElementById('target').value;
    var type = document.getElementById('type').value;
    var rvsdate = document.getElementById('datepicker').value;
    var ifrvs = 1;

    if (rvsdate == ""){
    	return false;
    }else{
			xajax_addCode(encounter_nr,encounter_type,adate,code,doc_nr,dept_nr,create_id,target,type,rvsdate,ifrvs);
    }
	}
	
	</script>