<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
<title>Untitled Document</title>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="800" border="0" align="center" style="border-collapse:collapse">
	<tr valign="top">
		<td align="center"><img src="{{ str_replace("public", "public_html", asset('images/'.$company->p_logo)) }}" width="100" />
            <h2> Work Travel Order<br />
          {{ $to->doc_num }}</h2></td>
        <td width="50%" align="right">
            <table width="100%" border="0">
                <b>{{ $company->company_name }}</b><br />
                {{ $company->address }}<br />
                <b>Phone :</b> {{ $company->phone }}<br />
                <b>Fax :</b> {{ $company->fax }}<br />
                <b>{{ $company->web }} | {{ $company->email }}</b><br />
            </table>
        </td>
	</tr>
</table>
<br />
<table width="900" border="1" align="center">

    <tr>
      <td align="center">BOARD OF DIRECTOR {{ $company->company_name }} ORDERS THE FOLLOWING WORK TRAVEL ARRANGEMENTS </td>
    </tr>
  </table>
  <br />
  <br />
  <table width="900" border="1" align="center" style="border-collapse:collapse">
    <tr bgcolor="#CCCCCC">
      <th colspan="3">Employee Data </th>
    </tr>
    <tr>
      <th width="33%">Full Name </th>
      <th width="33%">ID Number </th>
      <th width="33%">Position</th>
    </tr>
    <tr>
      <td><?php echo $to['emp_name']; ?></td>
      <td><?php echo $to['emp_id']; ?></td>
      <td><?php echo $to['emp_position']; ?></td>
    </tr>
  </table>

<br />
<table width="800" border="1" align="center" style="border-collapse:collapse" >
	<tr bgcolor="#CCCCCC">
		<th colspan="3">Financial Traveling</th>
	</tr>
	<tr>
		<th width="33%">Type Of Travel</th>
		<th width="33%">Periode</th>
		<th width="33%">Total</th>
	</tr>
	<tr>
		<td>
			{{ ($to->sppd_type == 'dom') ?'Domestic' : 'OverSeas' }}
		</td>
		<td>
			<?php
			$departure_dt = date('d/m/Y', strtotime($to['departure_dt']));
			$return_dt = date('d/m/Y', strtotime($to['return_dt']));
			echo $departure_dt." - ".$return_dt." (";
			if ($to['dest_type'] == 'wh')
			{
				echo round(((strtotime($to['return_dt']) - strtotime($to['departure_dt']))/86400) + 1);
			}
			else
			{
				echo round(((strtotime($to['return_dt']) - strtotime($to['departure_dt']))/86400));
			}
			echo " days)";
			?>
		</td>
		<td>
            {{ $data['mata_uang']}}
			<?php echo number_format($data['totalcost'] = $data['subcost_meal'] + $data['subcost_spending'] + $data['subcost_overnight'] + $data['subcost_transport'] + $data['timetravel']);?></font>,-
		</td>
	</tr>
</table>
<br />
<table width="800" border="1" align="center" style="border-collapse:collapse" >
	<tr>
		<td width="118" align="center" valign="middle" bgcolor="#CCCCCC">Name</td>
		<td width="107" align="center" valign="middle" bgcolor="#CCCCCC">By {{ $company->tag }}</td>
		<td width="111" align="center" valign="middle" bgcolor="#CCCCCC">Day</td>
		<td width="80" align="center" valign="middle" bgcolor="#CCCCCC">Cost</td>
	</tr>
	<tr>
		<td align="center" valign="middle">Meal</td>
		<td align="right" valign="middle"><?php echo number_format($to['to_meal']);?></td>
		<td align="center" valign="middle">
			<?php
			$departure_dt = date('d/m/Y', strtotime($to['departure_dt']));
			$return_dt = date('d/m/Y', strtotime($to['return_dt']));
			echo $departure_dt." - ".$return_dt." <br>(";
			if ($to['dest_type'] == 'wh')
			{
				echo round(((strtotime($to['return_dt']) - strtotime($to['departure_dt']))/86400) + 1);
			}
			else
			{
				echo round(((strtotime($to['return_dt']) - strtotime($to['departure_dt']))/86400));
			}
			echo " days)";
			?>
		</td>
		<td align="right" valign="middle"><?php echo $data['mata_uang']; echo number_format($data['subcost_meal']);?>,-</td>
	</tr>
	<tr>
		<td align="center" valign="middle">Spending</td>
		<td align="right" valign="middle"><?php echo $data['mata_uang']; echo  number_format($to['to_spending']);?></td>
		<td align="center" valign="middle">
			<?php
			$departure_dt = date('d/m/Y', strtotime($to['departure_dt']));
			$return_dt = date('d/m/Y', strtotime($to['return_dt']));
			echo $departure_dt." - ".$return_dt." <br>(";
			if ($to['dest_type'] == 'wh')
			{
				echo round(((strtotime($to['return_dt']) - strtotime($to['departure_dt']))/86400) + 1);
			}
			else
			{
				echo round(((strtotime($to['return_dt']) - strtotime($to['departure_dt']))/86400));
			}
			echo " days)";
			?>
		</td>
		<td align="right" valign="middle"><?php echo $data['mata_uang']; echo number_format($data['subcost_spending']);?>,-</td>
	</tr>
	<tr>
		<td align="center" valign="middle">Stay Overnight</td>
		<td align="right" valign="middle"><?php echo $data['mata_uang']; echo number_format($to['to_overnight']);?></td>
		<td align="center" valign="middle">
			<?php
			$departure_dt = date('d/m/Y', strtotime($to['departure_dt']));
			$return_dt = date('d/m/Y', strtotime($to['return_dt']));
			echo $departure_dt." - ".$return_dt." <br>(";
			if ($to['dest_type'] == 'wh')
			{
				echo ((strtotime($to['return_dt']) - strtotime($to['departure_dt']))/86400) + 1;
			}
			else
			{
				echo round(((strtotime($to['return_dt']) - strtotime($to['departure_dt']))/86400));
			}
			echo " days)";
			?>
		</td>
		<td align="right" valign="middle"><?php echo $data['mata_uang']; echo number_format($data['subcost_overnight']);?>,-</td>
	</tr>
	<tr>
		<td align="center" valign="middle">
			Local Transportation
			<?php
			if($to['to_cektransport'] == '1'){echo" By Train";}
			elseif($to['to_cektransport'] == '2'){echo" By Plane";}
			elseif($to['to_cektransport'] == '3'){echo" By Bus";}
			else{echo " TO WH PSI Cileungsi";}?>
		</td>
		<td align="right" valign="middle"><?php echo $data['mata_uang']; echo number_format($to['to_transport']);?></td>
		<td align="center" valign="middle">-</td>
		<td align="right" valign="middle"><?php echo $data['mata_uang']; echo number_format($data['subcost_transport']);?>,-</td>
	</tr>
	<tr>
		<td align="center" valign="middle">Airport Tax</td>
		<td align="right" valign="middle"><?php echo $data['mata_uang']; echo number_format($to['airtax']);?></td>
		<td align="center" valign="middle">&nbsp;</td>
		<td align="right" valign="middle"><?php echo $data['mata_uang']; echo number_format($to['airtax']);?></td>
	</tr>
	<tr>
		<td align="center" valign="middle">Taxi</td>
		<td align="right" valign="middle"><?php echo $data['mata_uang']; echo number_format($to['taxi']);?></td>
		<td align="center" valign="middle">&nbsp;</td>
		<td align="right" valign="middle"><?php echo $data['mata_uang']; echo number_format($to['taxi']);?></td>
	</tr>
	<tr>
		<td align="center" valign="middle">Car Rent</td>
		<td align="right" valign="middle"><?php echo $data['mata_uang']; echo number_format($to['rent']);?></td>
		<td align="center" valign="middle">&nbsp;</td>
		<td align="right" valign="middle"><?php echo $data['mata_uang']; echo number_format($to['rent']);?></td>
	</tr>
	<tr>
		<td align="center" valign="middle">Transportation (Travel / Boat)</td>
		<td align="right" valign="middle"><?php echo $data['mata_uang']; echo number_format(($to['transport']));?></td>
		<td align="center" valign="middle"> - </td>
		<td align="right" valign="middle"><?php echo $data['mata_uang']; echo number_format(($to['transport']));?>,-</td>
	</tr>
	<tr>
		<td colspan="3" align="center" valign="middle" bgcolor="#CCCCCC">Total Cost</td>
		<td align="right" valign="middle" >
			<?php echo $data['mata_uang']; echo number_format($totalcost = $data['subcost_meal'] + $data['subcost_spending'] + $data['subcost_overnight'] + $data['subcost_transport'] + $data['timetravel']);?>,-
		</td>
	</tr>
</table>
<br />
<table width="800" border="1" align="center" style="border-collapse:collapse" >
	<tr bgcolor="#CCCCCC">
		<th colspan="3">Notification</th>
	</tr>
	<tr>
		<td colspan="3">
			<ul>
				<li style="list-style-type:square">All Travel Expenses and costs are paid by {{ $company->company_name }}</li>
				<li style="list-style-type:square">In case of emergency or accident befalling {{ $company->tag }}'s crews, please contact us immeditely at {{ $company->phone }}</li>
				<li style="list-style-type:square">Your kind attention an our NOTIFICATION is greately appreciated </li>
			</ul>
		</td>
	</tr>
</table>
<br />
<table width="800" border="0" align="center">
	<tr>
		<td width="33%">&nbsp;</td>
		<td width="33%">&nbsp;</td>
		<td width="33%" align="center">Jakarta, <?php echo date("d F, Y"); ?> </td>
	</tr>
	<tr>
		<td align="center">
			<strong>Proposed</strong><br />
			<br />
			<br />
	        <br />
	        <br />
	        <br />
	        <br />
	        <br />
	        <br />
	        <br />
			<hr />
		</td>
		<td align="center">
			<strong>Acknowledge By</strong>    <br />
			<br />
	        <br />
	        <br />
	        <br />
	        <br />
	        <br />
	        <br />
	        <br />
	        <br />
			<hr />
		</td>
		<td align="center">
			<strong>Approved By</strong>   <br />
			<br />
            <br />
            <br />
            <br />
            <br />
            <br />
            <br />
            <br />
            <br />
	        <?php //echo $name_to ?>
			<hr />
		</td>
	</tr>
</table>
</body>
</html>
