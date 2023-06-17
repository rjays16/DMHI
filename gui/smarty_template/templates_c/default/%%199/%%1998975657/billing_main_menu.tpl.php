<?php /* Smarty version 2.6.0, created on 2017-05-03 15:28:04
         compiled from billing/billing_main_menu.tpl */ ?>

			<blockquote>
			<TABLE cellSpacing=0  width=600 class="submenu_frame" cellpadding="0">
			<TBODY>
			<TR>
				<TD>
					<TABLE cellSpacing=1 cellPadding=3 width=600>
					<TBODY class="submenu">
						<TR>
							<TD class="submenu_title" colspan=3>Billing Service</TD>
						</tr>
						<!-- <TR>
							<TD width="1%"><?php echo $this->_tpl_vars['sRequestTestIcon']; ?>
</TD>
							<TD class="submenu_item" width=35%><nobr><?php echo $this->_tpl_vars['LDViewBill']; ?>
</`nobr></TD>
							<TD>Process billing of admitted patient or ER patient</TD>
						</tr> -->
												<!-- comment by: shandy <TR>
							<TD width="1%"><?php echo $this->_tpl_vars['sRequestTestIcon']; ?>
</TD>
							<TD class="submenu_item" width=35%><nobr><?php echo $this->_tpl_vars['LDViewBillnPHIC']; ?>
</`nobr></TD>
							<TD>Process billing of patients without PHIC</TD>
						</tr> -->
						<!-- added by poliam 01/05/2014 -->
						<!-- <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/submenu_row_spacer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> -->
						<!-- ended by poliam 01/05/2014 -->
						<TR>
							<TD width="1%"><?php echo $this->_tpl_vars['sRequestTestIcon']; ?>
</TD>
							<TD class="submenu_item" width=35%><nobr><?php echo $this->_tpl_vars['LDViewBillPHIC']; ?>
</`nobr></TD>
							<!-- edited by:ian1-6-2014 -->
							<TD>Process billing of admitted patient or ER patient</TD>
						</tr>
												<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/submenu_row_spacer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
						<TR>
							<TD width="1%"><?php echo $this->_tpl_vars['sLabServicesRequestIcon']; ?>
</TD>
							<TD class="submenu_item" width=35%><nobr><?php echo $this->_tpl_vars['LDListOfBilling']; ?>
</nobr></TD>
							<TD>List of patients billed.</TD>
						</tr>
						
						
											<!--	<TR>
							<TD width="1%"><?php echo $this->_tpl_vars['sLabServicesRequestIcon']; ?>
</TD>
							<TD class="submenu_item" width=35%><nobr><?php echo $this->_tpl_vars['LDListOfPatient']; ?>
</nobr></TD>
							<TD>List of patients corresponds with admission date.</TD>
						</tr>-->
						<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/submenu_row_spacer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
							
												

					</TBODY>
					</TABLE>
				</TD>
			</TR>
			</TABLE>
			<p>
                                <TABLE cellSpacing=0  width=600 class="submenu_frame" cellpadding="0">
                    <TBODY>
                    <TR>
                        <TD>
                            <TABLE cellSpacing=1 cellPadding=3 width=600>
                                <TBODY class="submenu">
                                <TR>
                                    <TD class="submenu_title" colspan=3>Company Billing Service</TD>
                                </tr>
                                <TR>
                                    <TD width="1%"><?php echo $this->_tpl_vars['sCompanyBillingIcon']; ?>
</TD>
                                    <TD class="submenu_item" width=35%><nobr><?php echo $this->_tpl_vars['sCompanyBilling']; ?>
</nobr></TD>
                                    <TD>Process of Company Billing</TD>
                                </tr>
                                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/submenu_row_spacer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                                <TR>
                                    <TD width="1%"><?php echo $this->_tpl_vars['sCompanyManagerIcon']; ?>
</TD>
                                    <TD class="submenu_item" width=35%><nobr><?php echo $this->_tpl_vars['sCompanyManager']; ?>
</nobr></TD>
                                    <TD>Manage company options</TD>
                                </tr>
                                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/submenu_row_spacer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
					</TBODY>
					</TABLE>
				</TD>
			</TR>
			</TABLE>
			<p>
			<TABLE cellSpacing=0  width=600 class="submenu_frame" cellpadding="0">
			<TBODY>
			<TR>
				<TD>
					<TABLE cellSpacing=1 cellPadding=3 width=600>
					<TBODY class="submenu">
						<TR>
							<TD class="submenu_title" colspan=3>Billing Management</TD>
						</tr>
<!--						<TR>                                                                                       
							<TD width="1%"><?php echo $this->_tpl_vars['sManagePackageIcon']; ?>
</TD>                                            
							<TD class="submenu_item" width=35%><nobr><?php echo $this->_tpl_vars['LDManageClassification']; ?>
</nobr></TD>       
							<TD>Manage Packages </TD>                                                              
						</tr>   -->                                                                           
	
						<TR>
							<TD width="1%"><?php echo $this->_tpl_vars['sLDOtherServicesIcon']; ?>
</TD>
							<TD class="submenu_item" width=35%><nobr><?php echo $this->_tpl_vars['LDOtherServices']; ?>
</nobr></TD>
							<TD>Manager for Miscellaneous Services</TD>
						</tr>
						<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/submenu_row_spacer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
						<TR>
							<TD width="1%"><?php echo $this->_tpl_vars['sLDSocialReportsIcon']; ?>
</TD>
							<TD class="submenu_item" width=35%><nobr><?php echo $this->_tpl_vars['LDBillReports']; ?>
</nobr></TD>
							<TD>Process transmittals to health insurances.</TD>
						</tr>
						<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/submenu_row_spacer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                        <TR>
                            <TD width="1%"><?php echo $this->_tpl_vars['sLDTransmittalsHistIcon']; ?>
</TD>
                            <TD class="submenu_item" width=35%><nobr><?php echo $this->_tpl_vars['LDTransmittalsHistory']; ?>
</nobr></TD>
                            <TD>History of Transmittals.</TD>
                        </tr>
                        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/submenu_row_spacer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>                        
					</TBODY>
					</TABLE>
				</TD>
			</TR>
			</TABLE>
			<br/>
			<TABLE cellSpacing=0  width=600 class="submenu_frame" cellpadding="0">
			<TBODY>
			<TR>
				<TD>
					<TABLE cellSpacing=1 cellPadding=3 width=600>
					<TBODY class="submenu">
						<TR>
							<TD class="submenu_title" colspan=3>Administration</TD>
						</tr>
						<TR>
                            <TD width="1%"><?php echo $this->_tpl_vars['sLDBillingReportsIcon']; ?>
</TD>
                            <TD class="submenu_item" width=35%><nobr><?php echo $this->_tpl_vars['LDBillingReports']; ?>
</nobr></TD>
                            <TD>Reports of Billing</TD>
                        </tr>
                        <TR>
                            <TD width="1%"><?php echo $this->_tpl_vars['sLDBillingReportsIcon_jasper']; ?>
</TD>
                            <TD class="submenu_item" width=35%><nobr><?php echo $this->_tpl_vars['LDBillingReports_jasper']; ?>
</nobr></TD>
                            <TD>Reports of Billing</TD>
                        </tr>
						<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/submenu_row_spacer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
					</TBODY>
					</TABLE>
				</TD>
			</TR>
			</TABLE>
			<p>
			<a href="<?php echo $this->_tpl_vars['breakfile']; ?>
"><img <?php echo $this->_tpl_vars['gifClose2']; ?>
 alt="<?php echo $this->_tpl_vars['LDCloseAlt']; ?>
" <?php echo $this->_tpl_vars['dhtml']; ?>
></a>
			<p>
			</blockquote>