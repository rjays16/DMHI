<?php /* Smarty version 2.6.0, created on 2021-07-13 12:16:42
         compiled from nursing/ward_occupancy_list_row.tpl */ ?>

 <?php if ($this->_tpl_vars['bToggleRowClass']): ?>
	<tr class="<?php echo $this->_tpl_vars['class_label']; ?>
">
 <?php else: ?>
	<tr class="<?php echo $this->_tpl_vars['class_label']; ?>
">
 <?php endif; ?>
		<td><?php echo $this->_tpl_vars['sMiniColorBars']; ?>
</td>
		<td style="font-size:x-small"><?php echo $this->_tpl_vars['sRoom']; ?>
</td>
		<td style="font-size:x-small ">&nbsp;<?php echo $this->_tpl_vars['sBed']; ?>
 <?php echo $this->_tpl_vars['sBedIcon']; ?>
</td>
		<td ><?php echo $this->_tpl_vars['sTitle']; ?>
 <?php echo $this->_tpl_vars['sFamilyName'];  echo $this->_tpl_vars['cComma']; ?>
 <?php echo $this->_tpl_vars['sName']; ?>
</td>
		<td style="font-size:x-small "><?php echo $this->_tpl_vars['sBirthDate']; ?>
</td>
		<td style="font-size:x-small ">&nbsp;<?php echo $this->_tpl_vars['sPatNr']; ?>
</td>
	<!--	<td style="font-size:x-small ">&nbsp;<?php echo $this->_tpl_vars['sInsuranceType']; ?>
</td>-->
		<td style="font-size:x-small ">&nbsp;<?php echo $this->_tpl_vars['sCaseNo']; ?>
</td>
		<td>&nbsp;<?php echo $this->_tpl_vars['sAdmitDataIcon']; ?>
 <?php echo $this->_tpl_vars['sChartFolderIcon']; ?>
 <?php echo $this->_tpl_vars['sNotesIcon']; ?>
 <?php echo $this->_tpl_vars['sTransferIcon']; ?>
 <?php echo $this->_tpl_vars['sDischargeIcon']; ?>
</td>
		</tr>
				 
				 <?php if ($this->_tpl_vars['isBaby']): ?>
					<?php echo $this->_tpl_vars['BabyRows']; ?>

				 <?php else: ?>
				 <?php endif; ?>

		<!-- dati code, jan. 24, 2010
				<?php if ($this->_tpl_vars['isBaby']): ?>
				<?php if ($this->_tpl_vars['bToggleRowClass']): ?>
				<tr class="wardlistrow1">
			 <?php else: ?>
				<tr class="wardlistrow2">
			 <?php endif; ?>
					<td></td>
					<td style="font-size:x-small"><?php echo $this->_tpl_vars['sRoom']; ?>
</td>
					<td style="font-size:x-small ">&nbsp;<?php echo $this->_tpl_vars['sBed']; ?>
 <?php echo $this->_tpl_vars['sBabyBedIcon']; ?>
</td>
					<td><?php echo $this->_tpl_vars['sBabyIcon']; ?>
 <?php echo $this->_tpl_vars['sBabyFamilyName'];  echo $this->_tpl_vars['cComma']; ?>
 <?php echo $this->_tpl_vars['sBabyName']; ?>
</td>
					<td style="font-size:x-small "><?php echo $this->_tpl_vars['sBabyBirthDate']; ?>
</td>
					<td style="font-size:x-small ">&nbsp;<?php echo $this->_tpl_vars['sBabyPatNr']; ?>
</td>
					<td></td>
					<td>&nbsp;<?php echo $this->_tpl_vars['sBabyNotesIcon']; ?>
 <?php echo $this->_tpl_vars['sBabyTransferIcon']; ?>
 </td>
					</tr>
			 <?php else: ?>
			 <?php endif; ?>
		-->
		
		<tr>
		<td colspan="8" class="thinrow_vspacer"><?php echo $this->_tpl_vars['sOnePixel']; ?>
</td>
		</tr>