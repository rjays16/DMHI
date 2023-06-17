<?php 
	
	require('roots.php');
	require($root_path.'language/en/lang_en_date_time.php');
	require('../include/inc_environment_global.php');
	require_once($root_path.'include/inc_date_format_functions.php');
	
	include("classes/DateDDLGenerator.class.php");
	$dategen = new DateGenerator;	
	
	include_once("classes/doctor.class.php");	
	$doctorObj = new Doctor;
	
	if ($_GET['mode']){
		$mode = $_GET['mode'];
	}else{
		if (($_GET['make']==1)&&($_GET['edit']==0))
			$mode = 'save';
		elseif (($_GET['make']==0)&&($_GET['edit']==1))
			$mode = 'update';
		else		
			$mode = 'save';
	}		

	$editvalue = $_GET['edit'];
	$makevalue = $_GET['make'];	

?>
<script type="text/javascript">
	function preSet(edit, make){
		//document.getElementById('view').style.display = 'none';
		//alert(edit+" - "+make+" - "+document.getElementById('mode').value);
		if ((edit==1)&& (make==0)){
			//alert("true");
			document.getElementById('divlabel').innerHTML = 'Edit Appointment';
			unhideBody();
			document.getElementById('mode').value = 'update';
		}else{
			//alert("false");
			if (document.getElementById('mode').value=='save'){
				//alert("false 01");
				//document.getElementById('divlabel').innerHTML = '<a href="<?=$thisfile?>?mode=save&make=1&edit=0" onClick="document.getElementById('mode').value='save';unhideBody();resetForm();">Make Appointment</a>';	
				document.getElementById('divlabel').innerHTML = '<a id="make">Make Appointment</a>';	
				unhideBody();
				document.getElementById('mode').value = 'save';
			}else if (document.getElementById('mode').value=='update'){
				//alert("false 02");
				document.getElementById('divlabel').innerHTML = 'Edit Appointment';
				unhideBody();
				document.getElementById('mode').value = 'update';
			}else{
				document.getElementById('divlabel').innerHTML = 'Make Appointment';	
				unhideBody();
				document.getElementById('mode').value = 'save';
			}	
		}
	}
	
	function setEdit(){
		document.getElementById('editvalue').value=0;
	}
	
	function hideButton(){
		document.getElementById('buttonfxn').style.display = 'none';
	}
	
	function unhideButton(){
		document.getElementById('buttonfxn').style.display = '';
	}
	
	function unhideBody(){
		document.getElementById('divbody').style.display = '';
	}
	
	function hideBody(){
		document.getElementById('divbody').style.display = 'none';
	}

	
	function resetForm(){
		document.getElementById('client').value='';
		document.getElementById('purpose').value='';
		document.getElementById('place').value='';
	}
	
	function validateForm(d){
		if(d.client.value==''){
			alert("Please enter the client name.");
			d.client.focus();
			return false;
		}else if(d.purpose.value==''){		
			alert("Please enter the purpose of the appointment.");
			d.purpose.focus();
			return false;
		}else if(d.place.value==''){		
			alert("Please enter the place of the appointment.");
			d.place.focus();
			return false;	
		}else{
			return true;
		}	
	}
	
	function updateAppointment(){
		//document.getElementById('makerow').innerHTML = 'Edit Appointments';
		document.getElementById('mode').value = 'update';
	}
	
	<?php
		require_once($root_path.'include/inc_checkdate_lang.php'); 
	?>

</script>

<?php
	echo '<link rel="stylesheet" type="text/css" media="all" href="' .$root_path.'js/jscalendar/calendar-win2k-cold-1.css">'."\n";
	echo '<script language="javascript" src="'.$root_path.'js/setdatetime.js"></script>'."\n";
	echo '<script language="javascript" src="'.$root_path.'js/checkdate.js"></script>'."\n";
	echo '<script language="javascript" src="'.$root_path.'js/dtpick_care2x.js"></script>'."\n";
	echo '<script type="text/javascript" src="'.$root_path.'js/jscalendar/calendar.js"></script>'."\n";
	echo '<script type="text/javascript" src="'.$root_path.'js/jscalendar/lang/calendar-en.js"></script>'."\n";
	echo '<script type="text/javascript" src="'.$root_path.'js/jscalendar/calendar-setup_3.js"></script>'."\n";

?>
<title>SegHIS Doctor Dashboard</title>

<link media="all, handheld" rel="stylesheet" href="default.css" type="text/css">

<body onLoad="preSet(document.getElementById('editvalue').value, document.getElementById('makevalue').value);">

<!--<img src="images/seghis_logo.jpg">-->
<?php
	include ("include/seg_logo.inc");
	if (isset($_SESSION['sid'])){
		#print_r($_SESSION);
		include("include/page.inc");	
?>
<h4><font color="blue">APPOINTMENTS</font></h4>
<?php
	$thisfile=basename(__FILE__);
	if(!isset($currDay)||!$currDay) $currDay=date('d');
	if(!isset($currMonth)||!$currMonth) $currMonth=date('m');
	if(!isset($currYear)||!$currYear) $currYear=date('Y');
	if(isset($HTTP_SESSION_VARS['sess_parent_mod'])) $HTTP_SESSION_VARS['sess_parent_mod']='';
	
	$HTTP_SESSION_VARS['sess_parent_mod']='';
	$HTTP_SESSION_VARS['sess_appt_dept_nr']='';
	$HTTP_SESSION_VARS['sess_appt_doc']='';

	$_SESSION['datesel'] = $_GET['date'];
	
	# Buffer calendar output
	/*generate the calendar */
	include('include/calendar_jl/class.calendar.php');
	/** CREATE CALENDAR OBJECT **/
	$Calendar = new Calendar;
	/** WRITE CALENDAR **/
	echo "<span class=\"reglink\">";
	$Calendar -> mkCalendar ($currYear, $currMonth, $currDay,$dept_nr,$aux);
	echo "</span>";
	
	$dateappt = $_POST['Year']."-".$_POST['Month']."-".$_POST['Day'];
	$timeappt = $_POST['Hour'].":".$_POST['Minutes'].":".$_POST['Seconds']." ".$_POST['Meridiem'];
	
	if ($dateappt){
		$appdate = strtotime($dateappt);
		$apptdate = date("Y-m-d",$appdate);
	}
	
	if ($timeappt){
		$apptime = strtotime($timeappt);
		$appttime = date("H:i:s",$apptime);
	}	

	if ($_GET['currMonth'])
		$month = $_GET['currMonth'];
	else
		$month = date('m');
	
	if ($_GET['currDay'])
		$day = $_GET['currDay'];
	else
		$day = date('d');		

	if ($_GET['currYear'])
		$year = $_GET['currYear'];
	else
		$year = date('Y');		
	
	if ($_GET['date'])
		$date = $_GET['date'];
	else
		$date = $year."-".$month."-".$day;
	
	$datefin = date('Y-m-d',strtotime($date));
	
   $_SESSION['date'] = $datefin;
	
	$rsAppointment = $doctorObj->getAppointments($datefin, $_SESSION['dr_nr']);
	$count = $doctorObj->count;
	
	if ($count){
	
		$rows=array();
		while ($row=$rsAppointment->FetchRow()) {
			$rows[] = $row;
		}
	
		foreach ($rows as $i=>$row) {
			if ($row) {
				$count++;
				$alt = ($count%2)+1;
			
				$src .= '<tr class="wardlistrow'.$alt.'" id="row'.$row['id'].'">
						<td headers="header1" class="reglabel"><a href="'.$thisfile.'?rowID='.$row['id'].'&date='.$_SESSION['date'].'&mode=update&edit=1&make=0" onClick="updateAppointment();unhideEdit();hideMakeHeader();hideMake();"><img src="images/eye_s.gif" width="16" height="16" border="0"></a></td>
						<td headers="header2" class="reglabel">'.$row['apptdate'].'</td>
						<td headers="header3" class="reglabel">'.$row['appttime'].'</td>
						<td headers="header4" class="reglabel">'.strtoupper($row['client']).'</td>
						<td headers="header5" class="reglabel">'.strtoupper($row['purpose']).'</td>
						<td headers="header6" class="reglabel">'.strtoupper($row['place']).'</td>
					</tr>
				';
			}
		}
	}else{
		$src .= '<tr class="wardlistrow1">
						<td colspan="6" class="reglabel">No available appointments at this day.</td>
					</tr>
				';
	}			
	
	$data = array(
			'apptdate'=>$apptdate,
			'appttime'=>$appttime,
			'client'=>$_POST['client'],
			'purpose'=>$_POST['purpose'],
			'place'=>$_POST['place'], 
			'dr_nr'=>$_SESSION['dr_nr'],
			'create_id'=>$_SESSION['username'],   
			'create_dt'=>date('YmdHis'),  
			'modify_id'=>$_SESSION['username'],   
			'modify_dt'=>date('YmdHis')   
		 );
	
	
	if (isset($submit)){	
		switch($_POST['mode']) {      
   	    case 'save': 
			 		$doctorObj->useSegAppointment();
					$data["history"] = "Create: ".date('Y-m-d H:i:s')." [\\".$_SESSION['username']."]\\n";
					$doctorObj->setDataArray($data);
					$saveok=$doctorObj->insertDataFromInternalArray();
					break;
			 case 'update':
			 		$data["id"] = $_POST['rowID'];
					
					if ($data["id"]==NULL)
						$data["id"] = $_GET["rowID"];
					
					$doctorObj->where=" id='".$data["id"]."'";
			 		$doctorObj->useSegAppointment();
					$data["history"] = $doctorObj->ConcatHistory("Update: ".date('Y-m-d H:i:s')." [\\".$_SESSION['username']."]\\n");
					$doctorObj->setDataArray($data);
					$saveok=$doctorObj->updateDataFromInternalArray($data["id"]);
					break;
 		} #end of switch statement	 
	}
	
	if ($_GET['rowID']){
		$rsspecappointment = $doctorObj->getSpecificAppointment($_GET['rowID'], $_SESSION['dr_nr']);
	}else{
		$dategen->setToCurrentDay();
		$dategen->setToCurrentTime();
	}	
	
	#echo "client = ".$rsspecappointment['client'];	
?>

<br/>
<div id="view">
  <label class="reg2label">View Appointments</label>
  <table id="view-table" width="30%">
  <!-- class="segList"-->
		<thead>
			<tr class="wardlistrow3">
				<!--<img src="images/eye_s.gif" width="16" height="16"> -->
				<th width="1%" id="header1" class="reg3label">&nbsp;</th>
				<th width="10%" id="header2" class="reg3label"><strong>Date</strong></th>
				<th width="10%" id="header3" class="reg3label"><strong>Time</strong></th>
				<th width="30%" id="header4" class="reg3label"><strong>Client</strong></th>
				<th width="20%" id="header5" class="reg3label"><strong>Purpose</strong></th>
				<th width="30%" id="header6" class="reg3label"><strong>Place</strong></th>
			</tr>	
		</thead>
		<tbody id="viewbody">
				<?= $src; ?>
		</tbody>
	</table>
</div>	
<br>	
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onSubmit="return validateForm(this);">
<div>
	<label id="divlabel" class="reg2label"></label>
		<div id="divbody">
			<p>
				<input type="hidden" name="rowID" id="rowID" value="<?=$_GET['rowID']?>">
				<label>Date&nbsp;:</label>
				<br>
				<?php
					#month / day / year
					/*
					$mon = date('m',strtotime($rsspecappointment['apptdate']));
					$day = date('d',strtotime($rsspecappointment['apptdate']));
					$yr = date('Y',strtotime($rsspecappointment['apptdate']));
					*/
					
					$_SESSION['mon'] = date('m',strtotime($rsspecappointment['apptdate']));
					$_SESSION['day'] = date('d',strtotime($rsspecappointment['apptdate']));
					$_SESSION['yr'] = date('Y',strtotime($rsspecappointment['apptdate']));
					#echo "date = ".$mon."-".$day."-".$yr."<br>";
					
					$dategen->genMonth();
					$dategen->genDay();
					$dategen->genYear();
				?>
			</p>
			<p>
				<label>Time&nbsp;:</label>
				<br>
				<?php
					/*
					$hr = date('h',strtotime($rsspecappointment['appttime']));
					$min = date('i',strtotime($rsspecappointment['appttime']));
					$sec = date('s',strtotime($rsspecappointment['appttime']));
					$mer = date('A',strtotime($rsspecappointment['appttime']));
					*/
					
					$_SESSION['hr'] = date('h',strtotime($rsspecappointment['appttime']));
					$_SESSION['min'] = date('i',strtotime($rsspecappointment['appttime']));
					$_SESSION['sec'] = date('s',strtotime($rsspecappointment['appttime']));
					$_SESSION['mer'] = date('A',strtotime($rsspecappointment['appttime']));
					
					$dategen->genHour();
					$dategen->genMinutes();
					$dategen->genSeconds();
					$dategen->genMeridiem();
				?>
			</p>
			<p>
				<label>Client&nbsp;:</label>
				<br>
				<input type="text" name="client" id="client" size="20" value="<?=$rsspecappointment['client']?>">
			</p>
			<p>
				<label>Purpose&nbsp;:</label>
				<br>
				<textarea id="purpose" name="purpose" cols="20" rows="2"><?=$rsspecappointment['purpose']?></textarea>
			</p>
			<p>
				<label>Place&nbsp;:</label>
				<br>
				<textarea id="place" name="place" cols="20" rows="2"><?=$rsspecappointment['place']?></textarea>
			</p>
			<p>
				<input type="submit" id="submit" name="submit" value="Save" onClick="setEdit();">&nbsp;
				<input type="button" id="cancelappt" name="cancelappt" value="Cancel" onClick="document.getElementById('mode').value='';resetForm();setEdit();">
			</p>

		</div>

</div>

<input type="hidden" name="mode" id="mode" value="<?=$mode?>">
<input type="hidden" name="editvalue" id="editvalue" value="<?=$editvalue?>">
<input type="hidden" name="makevalue" id="makevalue" value="<?=$makevalue?>">
</form>
<?php 
	}else{
		include("include/error.php");
		#header("Location:../doctor/index.php");
		#exit();
	}

?>

</body>
