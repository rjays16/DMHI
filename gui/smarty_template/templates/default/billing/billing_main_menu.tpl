
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
							<TD width="1%">{{$sRequestTestIcon}}</TD>
							<TD class="submenu_item" width=35%><nobr>{{$LDViewBill}}</`nobr></TD>
							<TD>Process billing of admitted patient or ER patient</TD>
						</tr> -->
						{{* Added by Francis *}}
						<!-- comment by: shandy <TR>
							<TD width="1%">{{$sRequestTestIcon}}</TD>
							<TD class="submenu_item" width=35%><nobr>{{$LDViewBillnPHIC}}</`nobr></TD>
							<TD>Process billing of patients without PHIC</TD>
						</tr> -->
						<!-- added by poliam 01/05/2014 -->
						<!-- {{include file="common/submenu_row_spacer.tpl"}} -->
						<!-- ended by poliam 01/05/2014 -->
						<TR>
							<TD width="1%">{{$sRequestTestIcon}}</TD>
							<TD class="submenu_item" width=35%><nobr>{{$LDViewBillPHIC}}</`nobr></TD>
							<!-- edited by:ian1-6-2014 -->
							<TD>Process billing of admitted patient or ER patient</TD>
						</tr>
						{{* end - Francis *}}
						{{include file="common/submenu_row_spacer.tpl"}}
						<TR>
							<TD width="1%">{{$sLabServicesRequestIcon}}</TD>
							<TD class="submenu_item" width=35%><nobr>{{$LDListOfBilling}}</nobr></TD>
							<TD>List of patients billed.</TD>
						</tr>
						{{*include file="common/submenu_row_spacer.tpl"*}}

						
						{{* Added By Genesis D. Ortiz 05-22-2014 *}}
					<!--	<TR>
							<TD width="1%">{{$sLabServicesRequestIcon}}</TD>
							<TD class="submenu_item" width=35%><nobr>{{$LDListOfPatient}}</nobr></TD>
							<TD>List of patients corresponds with admission date.</TD>
						</tr>-->
						{{include file="common/submenu_row_spacer.tpl"}}
							
						{{* End Added By Genesis D. Ortiz 05-22-2014 *}}
						

					</TBODY>
					</TABLE>
				</TD>
			</TR>
			</TABLE>
			<p>
                {{* Added by Jarel 07/17/2014 *}}
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
                                    <TD width="1%">{{$sCompanyBillingIcon}}</TD>
                                    <TD class="submenu_item" width=35%><nobr>{{$sCompanyBilling}}</nobr></TD>
                                    <TD>Process of Company Billing</TD>
                                </tr>
                                {{include file="common/submenu_row_spacer.tpl"}}
                                <TR>
                                    <TD width="1%">{{$sCompanyManagerIcon}}</TD>
                                    <TD class="submenu_item" width=35%><nobr>{{$sCompanyManager}}</nobr></TD>
                                    <TD>Manage company options</TD>
                                </tr>
                                {{include file="common/submenu_row_spacer.tpl"}}
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
							<TD width="1%">{{$sManagePackageIcon}}</TD>                                            
							<TD class="submenu_item" width=35%><nobr>{{$LDManageClassification}}</nobr></TD>       
							<TD>Manage Packages </TD>                                                              
						</tr>   -->                                                                           
{{*						{{include file="common/submenu_row_spacer.tpl"}}				*}}	
						<TR>
							<TD width="1%">{{$sLDOtherServicesIcon}}</TD>
							<TD class="submenu_item" width=35%><nobr>{{$LDOtherServices}}</nobr></TD>
							<TD>Manager for Miscellaneous Services</TD>
						</tr>
						{{include file="common/submenu_row_spacer.tpl"}}
						<TR>
							<TD width="1%">{{$sLDSocialReportsIcon}}</TD>
							<TD class="submenu_item" width=35%><nobr>{{$LDBillReports}}</nobr></TD>
							<TD>Process transmittals to health insurances.</TD>
						</tr>
						{{include file="common/submenu_row_spacer.tpl"}}
                        <TR>
                            <TD width="1%">{{$sLDTransmittalsHistIcon}}</TD>
                            <TD class="submenu_item" width=35%><nobr>{{$LDTransmittalsHistory}}</nobr></TD>
                            <TD>History of Transmittals.</TD>
                        </tr>
                        {{include file="common/submenu_row_spacer.tpl"}}                        
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
                            <TD width="1%">{{$sLDBillingReportsIcon}}</TD>
                            <TD class="submenu_item" width=35%><nobr>{{$LDBillingReports}}</nobr></TD>
                            <TD>Reports of Billing</TD>
                        </tr>
                        <TR>
                            <TD width="1%">{{$sLDBillingReportsIcon_jasper}}</TD>
                            <TD class="submenu_item" width=35%><nobr>{{$LDBillingReports_jasper}}</nobr></TD>
                            <TD>Reports of Billing</TD>
                        </tr>
						{{include file="common/submenu_row_spacer.tpl"}}
					</TBODY>
					</TABLE>
				</TD>
			</TR>
			</TABLE>
			<p>
			<a href="{{$breakfile}}"><img {{$gifClose2}} alt="{{$LDCloseAlt}}" {{$dhtml}}></a>
			<p>
			</blockquote>
