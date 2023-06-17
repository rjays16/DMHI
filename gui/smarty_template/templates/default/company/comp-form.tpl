<div style="width:100%">
    <table width="100%" border="0" style="font-size: 12px; margin-top:5px" cellspacing="2" cellpadding="2">
        <tbody>
            <tr>
                <td align="left" class="jedPanelHeader" ><strong>Details</strong></td>
            </tr>
            <tr>
                <td nowrap="nowrap" align="left" class="jedPanel">
                    <table width="100%" border="0" cellpadding="2" cellspacing="0">
                      	<tr>
                        		<td width="30%" align="right">*Company/Employee: </td>
                        		<td width="50%" align="left" nowrap="nowrap">
                        			<input type="text" id="full_name" class="jedInput" size="45" >
                        		</td>
                  			</tr>
                        <tr>
                            <td width="30%" align="right">*Address: </td>
                            <td width="50%" align="left" nowrap="nowrap">
                              <textarea id="address" class="SegInput" style="width:74%" ></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td width="30%" align="right">Contact: </td>
                            <td width="50%" align="left" nowrap="nowrap">
                              <input type="text" id="contact" class="jedInput" size="45" >
                            </td>
                        </tr>
                         <tr>
                            <td width="30%" align="right">Email Address: </td>
                            <td width="50%" align="left" nowrap="nowrap">
                              <input type="text" id="e_add" class="jedInput" size="45" >
                            </td>
                        </tr>
                        <tr>
                            <td width="30%" align="right">*Short Name: </td>
                            <td width="50%" align="left" nowrap="nowrap">
                              <input type="text" id="short_name" class="jedInput" size="45" >
                            </td>
                        </tr>
                        <tr>
                            <td width="30%" align="right">CEO/President: </td>
                            <td width="50%" align="left" nowrap="nowrap">
                              <input type="text" id="pres" class="jedInput" size="45" >
                            </td>
                        </tr>
                         <tr>
                            <td width="30%" align="right">HR: </td>
                            <td width="50%" align="left" nowrap="nowrap">
                              <input type="text" id="hr" class="jedInput" size="45" >
                            </td>
                        </tr>
                         <tr>
                            <td width="30%" align="right">Type: </td>
                            <td width="50%" align="left" nowrap="nowrap">
                              <select id="type" class="SegInput">
                                <option value="company">Company</option>
                                <option value="person">Employee</option>
                              </select>
                            </td>
                         </tr>
                         <tr>
                            <td colspan="2" align="center" nowrap="nowrap">
                              {{$sButton}}
                            </td>
                        </tr>
                      </table>
                </td>
            </tr>
        </tbody>
    </table>
</div>