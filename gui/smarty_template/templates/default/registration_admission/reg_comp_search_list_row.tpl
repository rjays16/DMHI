{{* reg_search_list_row.tpl  *}}
{{* This is the row for the resulting list of the person/patient search module *}}
{{* If you rearrange the row columns, be sure to synchronize it with the title row at reg_search_main.tpl *}}

<tr  {{if $toggle}} class="wardlistrow2" {{else}} class="wardlistrow1" {{/if}}>
    <td>&nbsp;{{$sCaseNr}}</td>
	<td>&nbsp;{{$sRegistryNr}}</td>
	<td>&nbsp;{{$sSex}}</td>
	<td>&nbsp;{{$sLastName}}</td>
	<td>&nbsp;{{$sFirstName}} {{$sCrossIcon}}</td>
	<td>&nbsp;{{$sBday}}</td>
	<td>&nbsp;{{$sAdmissionDate}}</td>
	<td>&nbsp;{{$sLocation}}</td>
	<td>&nbsp;{{$sDischargeDate}}</td>
	<td>&nbsp;{{$sOptions}} {{$sHiddenBarcode}}</td>
    <td>&nbsp;{{$sOptions2}}</td>
</tr>