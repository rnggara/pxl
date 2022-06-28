<table border="0" cellpadding="3" align="center" width="100%">
	<tr>
		<td align="center" colspan="5">
			<img src="{{ str_replace("public", "public_html", asset('images/'.$pref->app_logo)) }}" style="max-height: 100px">
		</td>
	</tr>
	<tr>
		<td align='center' valign='middle' style='padding:5px; height:50px'><p><strong>NIK : {{$emp['emp_id']}}</strong></p></td>
		<td colspan='3' nowrap="nowrap" width='70' align='center' valign='middle'>Employee name: <strong>{{$emp['emp_name']}}</strong></td>
		<td align='center' nowrap="nowrap" valign='middle'>Position: <strong>{{$emp['emp_position']}}</strong></td>
	</tr>
	<tr>
		<td colspan="5" align="center">
			<h2>Salary Slip : {{date('M Y', strtotime($period))}}</h2>
		</td>
	</tr>
	<tr>
		<td colspan="5"></td>
	</tr>
	<tr>
		<td width='165'>Basic Salary: </td>
		<td align='right'><strong>Rp {{ number_format(base64_decode($emp->salary), 0) }}</strong></td>
		<td>&nbsp;</td>
		<td>Absence Deduction:</td>
		<td align='right'><strong>Rp {{ number_format($archive->lateness, 0) }}</strong></td>
	</tr>
	<tr>
		<td>Position Allowance:</td>
		<td align='right'><strong>Rp {{ number_format($emp->allowance_office, 0) }}</strong></td>
		<td>&nbsp;</td>
		<td>Personal Loan:</td>
		<td align='right'><strong>Rp {{ number_format($archive->deduction, 0) }}</strong></td>
	</tr>
	<tr>
		<td>Transport Allowance:</td>
		<td align='right'><strong>Rp {{ number_format(base64_decode($emp->transport), 0) }}</strong></td>
		<td>&nbsp;</td>
		<td>Sunction Deduction:</td>
		<td align='right'><strong>Rp {{ number_format($archive->lateness, 0) }}</strong></td>
	</tr>
	<tr>
		<td>Meal Allowance:</td>
		<td align='right'><strong>Rp {{ number_format(base64_decode($emp->meal), 0) }}</strong></td>
		<td>&nbsp;</td>
		<td>BPJS Tenaga Kerja Deduction:</td>
		<td align='right'><strong>Rp {{ number_format($archive->deduc_bpjs_tk, 0) }}</strong></td>
	</tr>
	<tr>
		<td>Health Allowance:</td>
		<td align='right'><strong>Rp {{ number_format(base64_decode($emp->health), 0) }}</strong></td>
		<td>&nbsp;</td>
		<td>BPJS Kesehatan Deduction:</td>
		<td align='right'><strong>Rp {{ number_format($archive->deduc_bpjs_kes, 0) }}</strong></td>
	</tr>
	<tr>
		<td>House Allowance:</td>
		<td align='right'><strong>Rp {{ number_format(base64_decode($emp->house), 0) }}</strong></td>
		<td>&nbsp;</td>
		<td>JSHK Deduction:</td>
		<td align='right'><strong>Rp {{ number_format($archive->deduc_jshk, 0) }}</strong></td>
	</tr>
	<tr>
		<td>Voucher Allowance:</td>
		<td align='right'><strong>Rp {{ number_format($archive->voucher, 0) }}</strong></td>
		<td>&nbsp;</td>
		<td>PPH21 Deduction:</td>
		<td align='right'><strong>Rp {{ number_format($archive->deduc_pph21, 0) }}</strong></td>
	</tr>
	<tr>
		<td>BPJS Tenaga Kerja:</td>
		<td align='right'><strong>Rp {{ number_format($archive->allow_bpjs_tk, 0) }}</strong></td>
		<td width='24'>&nbsp;</td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td>BPJS Kesehatan:</td>
		<td align='right'><strong>Rp {{ number_format($archive->allow_bpjs_kes, 0) }}</strong></td>
		<td width='24'>&nbsp;</td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td>JSHK:</td>
		<td align='right'><strong>Rp {{ number_format($archive->allow_jshk, 0) }}</strong></td>
		<td width='24'>&nbsp;</td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td width='165'>WH Bonus:</td>
		<td align='right'><strong>Rp {{ number_format($archive->wh_nom, 0) }}</strong></td>
		<td width='24'>&nbsp;</td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td>Overtime:</td>
		<td align='right'><strong>Rp {{ number_format($archive->ovt_nom, 0) }}</strong></td>
		<td width='24'>&nbsp;</td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td>THR:</td>
		<td align='right'><strong>Rp {{ number_format($archive->thr, 0) }}</strong></td>
		<td width='24'>&nbsp;</td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td width='160'>ODO Bonus:</td>
		<td align='right'><strong>Rp {{ number_format($archive->odo_nom, 0) }}</strong></td>
		<td width='24'>&nbsp;</td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td>Field Bonus:</td>
		<td width='187' align='right'><strong>Rp {{ number_format($archive->field_nom, 0) }}</strong></td>
		<td width='24'>&nbsp;</td>
		<td></td>
		<td></td>
	</tr>
</tr>
<tr>
	<td colspan="5"></td>
</tr>
<tr>
	<td width='153'><strong>Total Salary:</strong></td>
	<td width='148' align='right'><strong>Rp {{ number_format($total_sal, 0) }}</strong></td>
	<td>&nbsp;</td>
	<td><strong>Total Deduction:</strong></td>
	<td align='right'><strong>Rp {{ number_format($total_deduc, 0) }}</strong></td>
</tr>
<tr>
	<td width='153'><strong>Yearly Bonus:</strong></td>
	<td width='148' align='right'><strong>Rp {{ number_format($yearly_bonus) }}</strong></td>
	<td>&nbsp;</td>
	<td><strong>Grand Total:</strong></td>
	<td align='right'><strong>Rp {{ number_format($total_sal - $total_deduc) }}</strong></td>
</tr>
</table>