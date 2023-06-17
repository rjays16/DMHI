<div style="width:70%">
    <table width="100%" border="0" style="font-size: 12px; margin-top:5px" cellspacing="2" cellpadding="2">
        <tbody>
            <tr>
                <td align="left" class="jedPanelHeader" ><strong>Search options</strong></td>
            </tr>
            <tr>
                <td nowrap="nowrap" align="left" class="jedPanel">
                    <table width="100%" border="0" cellpadding="2" cellspacing="0">
                      	<tr>
                      		<td width="20%" align="right">Company: </td>
                      		<td width="10%" align="left" nowrap="nowrap">
                      			<input type="text" id="keyword" class="jedInput" size="30" >
                      		</td>
                      		<td>
                      			<button class="jedButton" onclick="searchComp();" style="cursor:pointer">
                      				{{$sSearch}}
                      			</button>
                      			<button class="jedButton" onclick="addComp(0);" style="cursor:pointer">
                      				{{$sNew}}
                      			</button>
                      		</td>
                  				</tr>
                          <tr>
                              <td width="20%" align="left"></td>
                  					  <td width="10%" align="left">
                            			<select class="jedInput" id="type">
                      							<option value="">All</option>
                      							<option value="company">Company</option>
                      							<!-- <option value="person">Employee</option> -->
                            			</select>
                              </td>
                          </tr>
                      </table>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<div style="width:72%">
    <table width="98%" class="segContentPaneHeader" style="margin-top:10px">
        <tr>
          <td>
            <h1>Search result:</h1>
          </td>
        </tr>
    </table>
    <div class="segContentPane">
        <table id="" class="jedList" width="98%" border="0" cellpadding="0" cellspacing="0">
            <thead>
            <tr>
                <th width="25%">Name</th>
                <th width="*">Address</th>
                <th width="20%">Contact Number</th>
                <th width="7%" colspan="2">Options</th>
            </tr>
            </thead>
            <tbody id="rowData">
            	
            </tbody>
        </table>
        <br />
    </div>
</div> 