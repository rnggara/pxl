    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
    <title>CURICULLUM VITAE</title>
    <link href="../module/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="700" border="0" align="center" style="border-collapse:collapse">
    <tr valign="top">
        <!-- <td align="center"><img src="../module/psi.gif" width="100" /> -->
        <td align="center"><img src="{{str_replace("public", "public_html", asset('images/'.$company->p_logo))}}" width="50%" />
            <p style='font-size:11px;'><b>{{$company->company_name}}<br />{{$company->web}}</b></p>
            <h2> CURICULLUM VITAE<br />
            </h2>
        </td>
        <td width="50%" align="right">
            <table width="100%" border="0">
                <tr>
                    <td colspan="2"><h3></h3></td>
                </tr>
                <tr valign="top">
                    <!-- <td style="font-size:9px">Office:</td> -->
                    <td style="font-size:9px">
                        <div style='font-size:13px; float:left; border:0;'>
                            <strong>{{$company->company_name}}</strong><br>
                            {{$company->address}}<br>
                            Phone: {{$company->phone}}<br>
                            Fax: {{$company->fax}}<br>
                            Email: {{$company->email}}
                        </div>
                        <!-- <div style='font-size:17px; float:left; width:250px; border:0;'>
                          <strong>PT. AEON RISET TEKNOLOGI</strong><br>
                          Jl. Warung Jati Barat No.22, Jakarta Selatan, - 1274012 - DKI Jakarta<br>
                          Phone: (021) 111 4567<br>
                          Fax: <br>
                          Email: support@aeon-rt.com<br>
                        </div> -->
                    </td>
                </tr>
                <!-- <tr valign="top">
                  <td style="font-size:9px">Warehouse:</td>
                  <td style="font-size:9px">Jl. Tegal Rotan Raya no. 99 Bintaro Sektor IX Jakarta Selatan<br />
                    Phone: +6221 74861512, Fax: +6221 74861508 </td>
                </tr> -->
            </table>    </td>
    </tr>
</table>
<br />
<table width="700" border="1" align="center">

    <tr>
        <td align="center">BOARD OF DIRECTOR {{strtoupper($company->company_name)}} ORDERS THE FOLLOWING CURICULLUM VITAE</td>
    </tr>
</table>
<br />
<table width="700" border="1" align="center" style="border-collapse:collapse" >
    <tr bgcolor="#CCCCCC">
        <th colspan="3">Employee Data </th>
    </tr>
    <tr>
        <th width="33%">Full Name </th>
        <th width="33%">ID Number </th>
        <th width="33%">Position</th>
    </tr>
    <tr>
        <td align="center"><?php echo $emp['emp_name']; ?></td>
        <td align="center"><?php echo $emp['emp_id']; ?></td>
        <td align="center"><?php echo $emp['emp_position']; ?></td>
    </tr>
</table>
<br />
<table width="700" border="1" align="center" style="border-collapse:collapse" >
    <tr bgcolor="#CCCCCC">
        <th colspan="3">- || -</th>
    </tr>
    <tr>
        <th width="33%">Alamat</th>
        <th width="33%">Tanggal Lahir </th>
        <th width="33%">Picture</th>
    </tr>
    <tr>
        <td valign="top">
            <p style="margin: 5px">
                <?php echo $emp['address']; ?>
            </p>
        </td>
    <!-- <td valign="top"><?php echo $emp['emp_birth']; ?></td> -->
        <td valign="top" align="center"><?php echo $emp['emp_lahir']; ?></td>
        <td align="center" valign="top"><img src="{{str_replace('public', 'public_html', asset('/media/employee_attachment/'.$emp->picture))}}" alt="picture" width="105" /></td>
    </tr>
</table>

<br />
<table width="700" border="1" align="center" style="border-collapse:collapse" >
    <tr bgcolor="#CCCCCC">
        <th width="99%">School</th>
    </tr>
    <tr>
        <td>
            <table width='100%' border='1' align='center' style='border-collapse:collapse'>
                @foreach($cv as $item)
                    @if($item->type == 1)
                        <tr>
                            <td width='20%'>{{$item['start_date']}}</td>
                            <td width='60%'>{{$item['description']}}</td>
                            <td width='20%'>{{$item['end_date']}}</td>
                        </tr>
                    @endif
                @endforeach
            </table>
<!--            --><?php //echo"$list";?>
        </td>
    </tr>
</table>
<br />
<table width="700" border="1" align="center" style="border-collapse:collapse" >
    <tr bgcolor="#CCCCCC">
        <th width="99%">Job Expirience</th>
    </tr>
    <tr>
        <td>
            <table width='100%' border='1' align='center' style='border-collapse:collapse'>
                @foreach($cv as $item)
                    @if($item->type == 2)
                        <tr>
                            <td width='20%'>{{$item['start_date']}}</td>
                            <td width='60%'>{{$item['description']}}</td>
                            <td width='20%'>{{$item['end_date']}}</td>
                        </tr>
                    @endif
                @endforeach
            </table>
        </td>
    </tr>
</table>
<br />
<table width="700" border="1" align="center" style="border-collapse:collapse" >
    <tr bgcolor="#CCCCCC">
        <th width="99%">Certificate</th>
    </tr>
    <tr>
        <td>
            <table width='100%' border='1' align='center' style='border-collapse:collapse'>
                @foreach($cv as $item)
                    @if($item->type == 3)
                        <tr>
                            <td width='20%'>{{$item['start_date']}}</td>
                            <td width='60%'>{{$item['description']}}</td>
                            <td width='20%'>{{$item['end_date']}}</td>
                        </tr>
                    @endif
                @endforeach
            </table>
        </td>
    </tr>
</table>
<p></p>
<p>&nbsp;</p>
</body>
</html>
