<?php /* Smarty version 2.6.0, created on 2021-07-13 12:16:42
         compiled from nursing/ward_occupancy_list.tpl */ ?>

<table cellspacing="0" width="100%" border="0">
<tbody>
	<tr>
		<td class="wardlisttitlerow" width="1%">&nbsp;</td>
		<td class="wardlisttitlerow" width="9%"><?php echo $this->_tpl_vars['LDRoom']; ?>
</td>
		<td class="wardlisttitlerow" width="6%"><?php echo $this->_tpl_vars['LDBed']; ?>
</td>
		<td class="wardlisttitlerow" width="*"><?php echo $this->_tpl_vars['LDFamilyName']; ?>
, <?php echo $this->_tpl_vars['LDName']; ?>
</td>
		<td class="wardlisttitlerow" width="11%"><?php echo $this->_tpl_vars['LDBirthDate']; ?>
</td>
		<td class="wardlisttitlerow" width="13%"><?php echo $this->_tpl_vars['LDPatNr']; ?>
</td>
		<!--<td class="wardlisttitlerow" width="13%"><?php echo $this->_tpl_vars['LDInsuranceType']; ?>
</td>-->
		<td class="wardlisttitlerow" width="13%"><?php echo $this->_tpl_vars['LDCaseNo']; ?>
</td>
		<td class="wardlisttitlerow" width="15%"><?php echo $this->_tpl_vars['LDOptions']; ?>
</td>
	</tr>

	<?php echo $this->_tpl_vars['sOccListRows']; ?>


 </tbody>
</table>