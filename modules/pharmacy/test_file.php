<table border="0" width="90%" align="center">
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><strong style="white-space:nowrap">Department:</strong>
				<select class="jedInput" name="area" id="area">
				  <option selected="selected">Please select...</option>
				  <option value ="d1">Department 1</option>
				  <option value ="d2">Department 2</option>
				  <option value ="d3">Department 3</option>
				  <option value ="d4">Department 4</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<table border="0" align="center" width="100%">
									
					<tr>
						<thead>
							<td class="jedPanelHeader" width="*">Name of Personnel</td>
							<td class="jedPanelHeader" width="25%">Reference #</td>
							<td class="jedPanelHeader" width="25%">Date of Request</td>
						</thead>
						<tbody align="center">
							<td>
								<table align="left">
									<tr>
										<td>Name:</td>
										<td><input class="jedInput" id="name" name="name" type="text" size="40" style="font:bold 12px Verdana"/></td>
										<td><img src="img/btn_encounter_small.gif" border=0 width="26" height="22" id="orderdate_trigger" class="segSimulatedLink" align="absmiddle" style="margin-left:2px;cursor:pointer"></td>
										<td><input class="jedInput" type="button" value="Clear" style="font:bold 11px Verdana" onClick="(confirm('Clear the list?'))"/></td>
									</tr>
								</table>
							</td>
							<td>
								<input class="jedInput" id="refno" name="refno" type="text" size="12" value="2008000285" style="font:bold 12px Verdana"/>
								<input class="jedInput" type="button" value="Reset" style="font:bold 11px Arial" onClick="xajax_reset_referenceno()"/>

							</td>
							<td>
							<span id="show_orderdate" class="jedInput" style="font-weight:bold; color:#0000c0; padding:0px 2px;width:200px; height:24px">October 11, 2008 11:39am</span><input class="jedInput" name="orderdate" id="orderdate" type="hidden" value="2008-10-11 11:39" style="font:bold 12px Verdana"><img src="img/show-calendar.gif" border=0 width="26" height="22" id="orderdate_trigger" class="segSimulatedLink" align="absmiddle" style="margin-left:2px;cursor:pointer">

							</td>
						</tbody>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table width="100%">
			<tr>
				<td align="right">
					<img src="img/btn_submitorder.gif" align="center" onClick="if (validate()) document.inputform.submit()"  style="cursor:pointer" />
					<img src="img/en_cancel.gif" border=0 align="center" width="73" height="23" alt="Go back to databank menu" onClick="window.location='../../modules/pharmacy/seg-pharma-order-functions.php?sid=04e9d69c6be5a4a0c8b46fffccd72336&lang=en&userck=ck_prod_order_user'" onsubmit="return false;" style="cursor:pointer">
				</td>
			</tr>
		</table>

			</td>
		</tr>
		<tr>
			<td>
				<table id="order-list" class="jedList" border="0" cellpadding="0" cellspacing="0" width="100%">
						<thead>
							<tr id="order-list-header">
								<th width="1%" nowrap="nowrap">&nbsp;</th>
								<th width="10%" nowrap="nowrap" align="left">Code</th>
								<th width="*" nowrap="nowrap" align="left">Description</th>
								<th width="4%" nowrap="nowrap" align="center">Packaging</th>
								<th width="10%" align="center" nowrap="nowrap">Unit Cost</th>
								<th width="10%" align="right" nowrap="nowrap">Cost</th>
								<th width="10%" align="right" nowrap="nowrap">Total</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td colspan="7">Order list is currently empty...</td>
							</tr>
						</tbody>
				</table>
			</td>
		</tr>
		<tr>
			<td>
					<table width="100%" style="font-size: 12px; margin-top: 5px" border="0" cellspacing="1">
					<tbody>
						<tr>
					<td width="*" align="right" style="background-color:#ffffff; padding:4px" height=""><strong>Sub-Total</strong></th>
					<td id="show-sub-total" align="right" width="17% "style="background-color:#e0e0e0; color:#000000; font-family:Verdana; font-size:15px; font-weight:bold"></th>

				</tr>
				<tr>
					<td align="right" style="background-color:#ffffff; padding:4px"><strong>Discount</strong></th>
					<td id="show-discount-total" align="right" style="background-color:#cfcfcf; color:#006600; font-family:Verdana; font-size:15px; font-weight:bold"></th>
				</tr>
				<tr>
					<td align="right" style="background-color:#ffffff; padding:4px"><strong>Net Total</strong></th>
					<td id="show-net-total" align="right" style="background-color:#bcbcbc; color:#000066; font-family:Verdana; font-size:15px; font-weight:bold"></th>

						</tr>
					</tbody>
				</table>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
	</table>