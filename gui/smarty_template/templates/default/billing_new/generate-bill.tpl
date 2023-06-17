<div>

    <div style="width:90%; margin-top:10px" align="left">
        <table border="0" cellspacing="2" cellpadding="3" align="left" width="100%">
            <tbody>
            <tr>
                <td width="15%"><strong>Company </strong></td>
                <td width="50%"> : &nbsp;&nbsp;&nbsp;<b>{{$sAgency}}</b></td>
                <td width="15%"><strong>Cut-Off Date</strong></td>
                <td width="55%"> : &nbsp;&nbsp;&nbsp;{{$sCutOff}}<td>
            </tr>
            <tr>
                <td><strong>Bill Date</strong></td>
                <td> : &nbsp;&nbsp;&nbsp; {{$sDate}}<td>
            </tr>

            </tbody>
        </table>
    </div>

    <br>
    <br>
    <br>

    <div class="segContentPane" style="width:92%;">

        <div style="width:98%" align="right">
            {{$sPreview}}
            {{$sGenerateBill}}
        </div>

        <br>

        <div style="width:98%; height:290px; overflow-y:scroll;">
            <table id="employee-list" class="jedList" width="98%" cellspacing="0" cellpadding="0" border="1">

                <thead>
                <tr>
                    <th align="left"> Discharged Date </th>
                    <th align="left"> Case Number </th>
                    <th align="left"> Employee Name </th>
                    <th align="left"> Amount </th>
                    <th align="center"> Select All &nbsp;&nbsp;
                        <input checked {{$sDisable}} id="selectall" type="checkbox" name="selectall" style="valign:bottom">
                    </th>
                </tr>
                </thead>

                <tbody id="employee-list" name="employee-list">
                {{$sListRows}}
                </tbody>
            </table>
        </div>
    </div>

    <div style="width:89.5%; margin-top:20px">
        <table class="segList" width="100%" border="0" cellspacing="0" cellpadding="0">
            <thead>
            <tr>
                <th align="left" style="font-weight:bold; font-size:12px;" colspan="2">
                    <span>Total Bill of Employees</span>
                </th>
                <th width="3%"></th>
            </tr>
            </thead>

            <tbody>
            <tr> </tr>
            <tr>
                <td width="*" align="right" height="" style="background-color:#ffffff; padding:4px">
                    SubTotal
                </td>
                <td id="show-sub-total" width="17% " align="right" style="background-color:#e0e0e0; color:#000000; font-family:Arial; font-size:15px; font-weight:bold">{{$sSubTotal}}</td>
                <td></td>
            </tr>
            <tr>
                <td align="right" style="background-color:#ffffff; padding:4px">
                    Discount
                </td>
                <td align="right" style="background-color:#cfcfcf; color:#006600; font-family:Arial; font-size:15px; font-weight:bold">
                    <a id="show-discount-total">{{$sDiscount}}</a>
                    <input class="segInput numeric" id="discount_input" name="discount_input" type="text" size="16"
                           style="font:bold 12px Arial; float;left;display: none" >

                </td>
                <td></td>
            </tr>
            <tr>
                <td align="right" style="background-color:#ffffff; padding:4px">
                    Total
                </td>
                <td id="show-net-total" align="right" style="background-color:#bcbcbc; color:#000066; font-family:Arial; font-size:15px; font-weight:bold"> {{$sNetTotal}} </td>
                <td></td>
            </tr>
            </tbody>
        </table>
    </div>
    {{$sHiddenInputs}}
</div>