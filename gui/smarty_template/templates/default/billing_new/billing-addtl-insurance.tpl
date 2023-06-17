<div align="center" style="font:bold 12px Tahoma; color:#990000; ">{{$sWarning}}</div><br />
{{$sFormStart}}
<style type="text/css">
	#discount_details tr td {
		font:normal 12px Arial, Helvetica, sans-serif;
	}				
</style>
<div align="center" id="mainSection">
	<table width="94%" id="progress_indicator" name="progress_indicator" cellpadding="0" cellspacing="1" style="display:none">
		<tr>
			<td align="left" width="*">{{$sProgressBar}}</td>
		</tr>	
	</table>	
	<table class="segList" width="94%" id="discounts_tbl" name="discounts_tbl" cellpadding="0" cellspacing="1" style="display:none">
		<thead>
			<tr>
				<th align="left" width="27%" style="font-size:12px;">Insurance Name</th>
				<th width="14%"><span style="font-size:12px;">Accommodation</span></th>
                <th width="14%"><span style="font-size:12px;">&nbsp;&nbsp;XLSO&nbsp;&nbsp;</span></th>
				<th width="14%"><span style="font-size:12px;">Drugs & Meds</span></th>
                <th width="14%"><span style="font-size:12px;">Operation Room</span></th>
                <th width="14%"><span style="font-size:12px;">Miscellaneous</span></th>
                <th width="14%"><span style="font-size:12px;">Doctors Fee</span></th>
				<th width="*14%"><span style="font-size:12px;">&nbsp;</span></th>
			</tr>
		</thead>
		<tbody id="insurance_details">
		</tbody>	
	</table>
	<br>
	<table width="94%" id="footer" name="footer" cellpadding="0" cellspacing="1" style="display:none">
		<tr>
			<td align="left" width="*">{{$sAddButton}}</td>
		</tr>	
	</table>
</div>
<div id="insuranceInfoBox">
<div class="hd" align="left">Insurance Information</div>
<div class="bd">
	<form id="fprof" method="post" action="document.location.href">
		<table width="100%" class="segPanel">
			<tbody>
				<tr>
					<td width="31%" align="right"><b>Select Insurance :</b></td>
					<td width="69%">
						<select id="insurance_list" name="insurance_list" onchange="jsInsuranceOptionChange(this, this.options[this.selectedIndex].value)">
							<option value="">- Select Insurance -</option>
						</select>
				  </td>
				</tr>
				<tr>
					<td align="right"><b>Accommodation :</b></td>
					<td>
						<input style="text-align:right" onblur="trimString(this); genChkDecimal(this, 2);" onFocus="this.select();" id="accommodation" name="accommodation" value="" />
					</td>
				</tr>				
				<tr>
					<td align="right"><b>XLO :</b></td>
					<td>
						<input style="text-align:right" onblur="trimString(this); genChkDecimal(this, 2);" onFocus="this.select();" id="xlso" name="xlso" value="" />
					</td>
				</tr>

				<tr>
					<td align="right"><b>Drugs and Meds :</b></td>
					<td><input style="text-align:right" onblur="trimString(this); genChkDecimal(this, 2);" onFocus="this.select();" id="meds" name="meds" value="" /></td>
				</tr>
				<tr>
					<td align="right"><b>Operating Room :</b></td>
					<td><input style="text-align:right" onblur="trimString(this);  genChkDecimal(this, 2);" onFocus="this.select();" id="or" name="or" value="" /></td>
				</tr>
				<tr>
					<td align="right"><b>Miscellaneous :</b></td>
					<td><input style="text-align:right" onblur="trimString(this);  genChkDecimal(this, 2);" onFocus="this.select();" id="misc" name="misc" value="" /></td>
				</tr>
				<tr>
					<td align="right"><b>Doctors Fee :</b></td>
					<td><input style="text-align:right" onblur="trimString(this);  genChkDecimal(this, 2);" onFocus="this.select();" id="doctors" name="doctors" value="" /></td>
				</tr>
				<!-- for misc -->
				<!-- <tr>
					<td align="right"><b>Discount misc:</b></td>
					<td><input style="text-align:right" onblur="trimString(this); genChkDecimal(this, 2); " onFocus="this.select();" id="discountamnt" name="discountamnt" value="" /></td>
				</tr> -->
				<!-- comment out by poliam
				because manual input of price in billing
				hospital Fee and doctor's fee and misc fee

				<tr>
					<td align="right"><b>Discount (%):</b></td>
					<td><input style="text-align:right" onblur="trimString(this); genChkDecimal(this, 4); clearOtherField(this, 'discountamnt');" onFocus="this.select();" id="discount" name="discount" value="" /></td>
				</tr>
				<tr>
					<td align="right"><b>Discount (Fixed):</b></td>
					<td><input style="text-align:right" onblur="trimString(this); genChkDecimal(this, 2); clearOtherField(this, 'discount');" onFocus="this.select();" id="discountamnt" name="discountamnt" value="" /></td>
				</tr>                 -->
			</tbody>
		</table>
		{{$sHiddenInputs}}
	</form>
</div>
</div>
<span style="font:bold 15px Arial">{{$sDebug}}</span>
{{$sMainHiddenInputs}}
{{$sFormEnd}}
{{$sTailScripts}}
