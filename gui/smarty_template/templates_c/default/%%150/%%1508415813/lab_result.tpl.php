<?php /* Smarty version 2.6.0, created on 2019-02-01 11:58:05
         compiled from laboratory/lab_result.tpl */ ?>
<div>
<?php echo $this->_tpl_vars['form_start']; ?>


<div style="width:90%; margin-top:10px" align="left">
	<table border="0" cellspacing="2" cellpadding="3" align="center" width="100%">
		<tbody>
			<tr>
				<td class="segPanelHeader" width="*" colspan="2">Patient Details</td>
			</tr>
			<tr>
				<td class="segPanel" align="left" valign="top">
					<table  width="100%" class="transaction_details_table" cellpadding="0" cellspacing="0" style="font:normal 12px Arial; padding:4px" >
						<tr>
							<td align="left" width="30%" nowrap="nowrap"><strong>Case Number : </strong><?php echo $this->_tpl_vars['sPatientID']; ?>
</td>
						</tr>
						<tr>
							<td align="left" width="30%" nowrap="nowrap"><strong>Name : </strong><b><?php echo $this->_tpl_vars['patient_name']; ?>
</b></td>
						</tr>
						<tr>
							<td align="left" width="30%" nowrap="nowrap"><strong>Address : </strong><b><?php echo $this->_tpl_vars['patient_address']; ?>
</b></td>
						</tr>
						<tr>
							<td align="left" width="30%" nowrap="nowrap"><strong>Ward : </strong><b><?php echo $this->_tpl_vars['patient_ward']; ?>
</b></td>
						</tr>
					</table>
				</td>
				<td class="segPanel" align="left" valign="top">
					<table  width="100%" class="transaction_details_table" cellpadding="0" cellspacing="0" style="font:normal 12px Arial; padding:4px" >
						<tr>
							<td align="left" width="30%" nowrap="nowrap"><strong>Age : </strong><b><?php echo $this->_tpl_vars['patient_age']; ?>
</b></td>
						</tr>
						<tr>
							<td align="left" width="30%" nowrap="nowrap"><strong>Sex : </strong><b><?php echo $this->_tpl_vars['patient_sex']; ?>
</b></td>
						</tr>
						<tr>
							<td align="left" width="30%" nowrap="nowrap"><strong>Physician : </strong><b><?php echo $this->_tpl_vars['patient_dr']; ?>
</b></td>
						</tr>
						<tr>
							<td align="left" width="30%" nowrap="nowrap"><strong>Date : <span id="show_date"><?php echo $this->_tpl_vars['dateToday']; ?>
</span></strong>
								<input id="seldate" type="hidden" name="seldate" value="<?php echo $this->_tpl_vars['dateTodayValue']; ?>
"/>
								<img src="../../gui/img/common/default/calendar.png" class="segSimulatedLink" id="tg_seldate" name="tg_seldate" onclick="return false;" align="absmiddle" border="0"/></td>
							<script type="text/javascript">
									Calendar.setup ({
											displayArea: "show_date",
											inputField : "seldate",
											ifFormat : "%Y-%m-%d",
											daFormat : "	%B %e, %Y",
											showsTime : false,
											button : "tg_seldate",
											singleClick : true,
											step : 1
									});
							</script>
						</tr>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
	<div id="tab-lab-result" style="font: bold 12px Tahoma;">
			<div class="dashlet" style="margin-top:5px">
				<table width="100%" cellpadding="0" cellspacing="0" border="0" class="dashletHeader" style="font: bold 12px Tahoma;">
					<tbody>
						<tr>
							<td width="10%" valign="top"><h1 style="white-space:nowrap">List of Result Form/s for <?php echo $this->_tpl_vars['service_code']; ?>
</h1></td>
							<td align="right">
								<button class="segButton"  id="openAddFormBtn" name="openAddFormBtn"  onclick="openLabResults();" style="cursor:pointer"><img src="../../gui/img/common/default/add.png" border="0"/>Add Form/s</button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div id="form-lab-result" name="form-lab-result" align="center">
			</div>
	</div>
	<div>
		<div class="dashlet" style="margin-top:5px">
				<table width="100%" colspan="2" border="0" class="dashletHeader" style="font: bold 12px Tahoma;">
					<tbody>
						<tr>
							<td width="50%" valign="top" align="right">Medical Technologist: </td>
							<td align="left">
								<?php echo $this->_tpl_vars['medTech']; ?>

							</td>
						</tr>
						<tr>
							<td width="50%" valign="top" align="right">Pathologist: </td>
							<td align="left">
								<?php echo $this->_tpl_vars['pathologist']; ?>

							</td>
						</tr>
					</tbody>
				</table>
				<?php echo $this->_tpl_vars['saveBtn'];  echo $this->_tpl_vars['doneBtn'];  echo $this->_tpl_vars['cancelBtn'];  echo $this->_tpl_vars['printBtn']; ?>

		</div>
	</div>
</div>


<?php echo $this->_tpl_vars['form_end']; ?>

<?php echo $this->_tpl_vars['ptype']; ?>

<?php echo $this->_tpl_vars['request_source']; ?>

<?php echo $this->_tpl_vars['is_bill_final']; ?>

<?php echo $this->_tpl_vars['encounter_nr']; ?>

<?php echo $this->_tpl_vars['isIc_hidden']; ?>

</div>