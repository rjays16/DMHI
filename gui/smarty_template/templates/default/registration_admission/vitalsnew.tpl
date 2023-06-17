{{* form.tpl  Form template for orders module (pharmacy & meddepot) 2004-07-04 Elpidio Latorilla *}}
<div align="center" style="font:bold 12px Tahoma; color:#990000; ">{{$sWarning}}</div><br />

{{$sFormStart}}
    <table border="0" cellspacing="2" cellpadding="2" width="95%" align="center">
        <tbody>
            <tr>
                <td class="segPanelHeader" width="*">
                    Personal Vital Signs
                </td>
            </tr>
            <tr>
                <td rowspan="3" class="segPanel" align="center" valign="top">
                  <table width="100%" border="0" cellpadding="2" cellspacing="0" style="margin-top:8px">
                   <tr>
                    <td width="50%">    
                    <table width="100%" border="0" cellpadding="2" cellspacing="0" style="margin-top:8px">
                        <tr>
                            <td valign="top" width="42%"><strong>Vital Sign Date:</strong></td>
                            <td width="1" valign="middle" width="53%">
                                {{$sVitCalendarInput}} {{$sVitCalendarIcon}}
                            </td>
                        </tr>
                        <tr>
                            <td valign="top"><strong>Blood Pressure (BP):</strong></td>
                            <td width="1" valign="middle">
                                {{$sVitbp}}
                            </td>
                        </tr>
                        <tr>
                            <td valign="top"><strong>Temperature (T):</strong></td>
                            <td width="1" valign="middle">
                                {{$sVitt}}
                            </td>
                        </tr>
                    </table>
                    </td>
                    <td width="50%">
                        <table width="100%" border="0" cellpadding="2" cellspacing="0" style="margin-top:8px">
                        <tr>
                            <td valign="top" width="42%" align="left"><strong>Weight (W):</strong></td>
                            <td width="1" valign="middle" width="53%">
                                {{$sVitw}}
                            </td>
                        </tr>
                        <tr>
                            <td valign="top"><strong>Respiratory (RR):</strong></td>
                            <td width="1" valign="middle">
                                {{$sVitrr}}
                            </td>
                        </tr>
                        <tr>
                            <td valign="top"><strong>Pulse Rate (PR):</strong></td>
                            <td width="1" valign="middle">
                                {{$sVitpr}}
                            </td>
                        </tr>
                    </table>
                    </td>
                    </tr>
                    </table>
                </td>
                
            </tr>
            
        </tbody>
    </table>

<br>
    <div align="left" style="width:95%">
        <table width="100%">
            <tr>
                <td width="50%" align="left">
                    {{$sBtnAddItem}}
                    {{$sBtnEmptyList}}
                    {{$sBtnPDF}}
                </td>
                <td align="right">
                    {{$sContinueButton}}
                    {{$sBreakButton}}
                </td>
            </tr>
        </table>
        <table id="vital-list" class="segList" border="0" cellpadding="0" cellspacing="0" width="100%">
            <thead>
                <tr id="vital-list-header">
                    <th width="6%" nowrap align="left">Encounter No.<span id="counter"></span></th>
                    <th width="*" nowrap align="center">&nbsp;&nbsp;Date</th>
                    <th width="12%" nowrap align="center">&nbsp;&nbsp;BP</th>
                    <th width="12%" nowrap align="center">&nbsp;&nbsp;T</th>
                    <th width="12%" nowrap align="center">&nbsp;&nbsp;W</th>
                    <th width="10%" nowrap align="center">&nbsp;&nbsp;RR</th>
                    <th width="10%" nowrap align="center">&nbsp;&nbsp;PR</th>
                    <th width="2%" nowrap align="left">&nbsp;&nbsp;Edit</th>
                    <th width="2%" nowrap align="left">&nbsp;&nbsp;Delete</th>
                </tr>
            </thead>
            <tbody>
{{$sVitalItems}}
            
        </table>
        
    </div>
    
{{$sHiddenInputs}}
{{$jsVitCalendarSetup}}
{{$sIntialRequestList}}
<br/>
<img src="" vspace="2" width="1" height="1"><br/>
{{$sDiscountControls}}
<span id="tdShowWarnings" style="font-family:Arial, Helvetica, sans-serif;font-size:12px;font-weight:normal;"></span>
<br/>



<span style="font:bold 15px Arial">{{$sDebug}}</span>
{{$sFormEnd}}
{{$sTailScripts}}     
<hr/>
<!--
<input type="button" name="btnRefreshDiscount" id="btnRefreshDiscount" onclick="refreshDiscount()" value="Refresh Discount">
<input type="button" name="btnRefreshTotal" id="btnRefreshTotal" onclick="refreshTotal()" value="Refresh Totals">
-->
