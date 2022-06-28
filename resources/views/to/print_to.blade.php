<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
<title>Untitled Document</title>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="900" border="0" align="center">
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
	<table width="900" border="1" align="center" style="border-collapse:collapse">

  <tr bgcolor="#CCCCCC">
    <th colspan="3">Itinerary</th>
  </tr>
  <tr>
    <th width="33%">Destination</th>
    <th width="33%">Departs On </th>
    <th width="33%">Returns On </th>
  </tr>
  <tr>
    <td><?php echo $to['destination']; ?></td>
    <td><?php echo date("d-F-Y", strtotime($to['departure_dt'])); ?></td>
    <td><?php echo date("d-F-Y", strtotime($to['return_dt'])); ?></td>
  </tr>
</table>

	<br />
	<table width="900" border="1" align="center" style="border-collapse:collapse">

  <tr bgcolor="#CCCCCC">
    <th colspan="3">Purpose of Travel </th>
  </tr>
  <tr>
    <td colspan="3"><?php echo $to['purpose']; ?></td>
  </tr>
</table>

	<br />
	<table width="900" border="1" align="center" style="border-collapse:collapse">
    <tr bgcolor="#CCCCCC">
      <th colspan="5">To Be Filled Out By Client Receiving Party</th>
    </tr>
    <tr>
      <th rowspan="2">Name of Receiving Party</th>
      <th colspan="2">{{ $company->tag }}'s Crew </th>
      <th width="200" rowspan="2">Remark</th>
      <th width="200" rowspan="2">Signature</th>
    </tr>
    <tr>
      <th width="100">Arrives On </th>
      <th width="100">Departs On </th>
    </tr>
    <tr>
      <td><br><br><br><br>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
	<br />
	<br />
	<table width="900" border="1" align="center" style="border-collapse:collapse">

  <tr bgcolor="#CCCCCC">
    <th colspan="3">Notification</th>
  </tr>
  <tr>
    <td colspan="3"><ul>
      <li style="list-style-type:square">All Travel Expenses and costs are paid by {{ $company->company_name }}</li>
      <li style="list-style-type:square">In case of emergency or accident befalling {{ $company->tag }}'s crews, please contact us immeditely at {{ $company->phone }}</li>
      <li style="list-style-type:square">Your kind attention an our NOTIFICATION is greately appreciated </li>
    </ul></td>
  </tr>
</table>

	<br />
	<table width="900" border="0" align="center">

  <tr>
    <td width="33%">&nbsp;</td>
    <td width="33%">&nbsp;</td>
    <td width="33%" align="center">Jakarta, <?php echo date("d F, Y", strtotime($to['departure_dt'])); /*echo date("d F, Y");*/ ?> </td>
  </tr>
  <tr>
    <td align="center"><strong>Proposed</strong>			<br />
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
    <td align="center"><strong>Acknowledge By</strong>		<br />
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
    <td align="center"><strong>Approved By</strong>		<br />
        <br />
        <br />
        <br />
        <br />
        <br />
        <br />
        <br />
        <br />
        <br />
        <?php // echo $name_to ?>
				<hr />
				</td>
  </tr>
</table>
</body>
</html>
