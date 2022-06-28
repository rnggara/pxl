<style>
    body {
    font-family: DejaVuSansCondensed;
    font-size: 11pt;
}
.header {
    font-family: DejaVuSansCondensed;
    width: 100%;
    border-bottom:1px solid;
}
.info td {
    font-family: DejaVuSansCondensed;
    font-size: 11pt;
}
.info_isi {
    text-align: left;
    font-weight: bold;
}
.record th {
    text-align: center;
    font-weight: bold;
    border: 1px solid black;
}
.record td {
    vertical-align: top;
    border: 1px solid black;
}
.record tr {
    border: 1px solid black;
}
.record {
    font-family: DejaVuSansCondensed;
    width: 100%;
}
table {
    border-collapse: collapse;
}
th {
    font-weight: bold;
    vertical-align: top;
    text-align: left;
    padding-left: 2mm;
    padding-right: 2mm;
    padding-top: 0.5mm;
    padding-bottom: 0.5mm;
}
td {
    padding-left: 2mm;
    vertical-align: top;
    text-align: left;
    padding-right: 2mm;
    padding-top: 0.5mm;
    padding-bottom: 0.5mm;
}
th p {
    text-align: left;
    margin: 0pt;
}
td p {
    text-align: left;
    margin: 0pt;
}
hr {
    width: 70%;
    height: 1px;
    text-align: center;
    color: #999999;
    margin-top: 8pt;
    margin-bottom: 8pt;
}
a {
    color: #000066;
    font-style: normal;
    text-decoration: underline;
    font-weight: normal;
}
ul {
    text-indent: 5mm;
    margin-bottom: 9pt;
}
ol {
    text-indent: 5mm;
    margin-bottom: 9pt;
}
pre {
    font-family: DejaVuSansMono;
    font-size: 9pt;
    margin-top: 5pt;
    margin-bottom: 5pt;
}
h1 {
    /*font-weight: normal;*/
    /*font-size: 26pt;*/
    /*color: #000066;*/
    font-family: DejaVuSansCondensed;
    /*margin-top: 18pt;*/
    /*margin-bottom: 6pt;*/
    /*border-top: 0.075cm solid #000000;*/
    /*border-bottom: 0.075cm solid #000000;*/
    /*text-align: ;*/
    page-break-after: avoid;
}
h2 {
    /*font-weight: bold;*/
    /*font-size: 12pt;*/
    /*color: #000066;*/
    font-family: DejaVuSansCondensed;
    /*margin-top: 6pt;*/
    /*margin-bottom: 6pt;*/
    /*border-top: 0.07cm solid #000000;*/
    /*border-bottom: 0.07cm solid #000000;*/
    /*text-align: ;*/
    /*text-transform: uppercase;*/
    page-break-after: avoid;
}
h3 {
    /*font-weight: normal;*/
    /*font-size: 26pt;*/
    /*color: #000000;*/
    font-family: DejaVuSansCondensed;
    /*margin-top: 0pt;*/
    /*margin-bottom: 6pt;*/
    /*border-top: 0;*/
    /*border-bottom: 0;*/
    /*text-align: ;*/
    page-break-after: avoid;
}
h4 {
    /*font-weight: ;*/
    /*font-size: 13pt;*/
    /*color: #9f2b1e;*/
    font-family: DejaVuSansCondensed;
    /*margin-top: 10pt;*/
    /*margin-bottom: 7pt;*/
    /*font-variant: small-caps;*/
    /*text-align: ;*/
    /*margin-collapse: collapse;*/
    page-break-after: avoid;
}
h5 {
    /*font-weight: bold;*/
    /*font-style: italic;*/
    /*font-size: 11pt;*/
    /*color: #000044;*/
    font-family: DejaVuSansCondensed;
    /*margin-top: 8pt;*/
    /*margin-bottom: 4pt;*/
    /*text-align: ;*/
    page-break-after: avoid;
}
h6 {
    /*font-weight: bold;*/
    /*font-size: 9.5pt;*/
    /*color: #333333;*/
    font-family: DejaVuSansCondensed;
    /*margin-top: 6pt;*/
    /*margin-bottom: ;*/
    /*text-align: ;*/
    page-break-after: avoid;
}

.page {
    page-break-before: always;
}

</style>
<div class="">
    <table class='header'>
        <tr>
            <td width='80' rowspan='2'>
                <img src='{{ str_replace('public', 'public_html', asset('images/'.$comp->p_logo)) }}' width='150px'>
            </td>
            <td style='text-align:center'>
                <h2><strong>{{ strtoupper($comp->company_name) }}</strong></h2>
            </td>
            <td width='80' rowspan='2'>
                &nbsp;
            </td>
        </tr>
        <tr>
            <td style='text-align:center'><h3><strong>MANAGEMENT VISIT REPORT</strong></h3></td>
        </tr>
    </table>
    <h3>Attendance Record</h3>
<table class='info'>
    <tr>
        <td>Report ID</td>
        <td>:</td>
        <td class='info_isi'>{{ sprintf("%03d", $mv->id_main) }}/{{ $comp->tag }}-MV/{{ date('m', strtotime($mv->date_main)) }}/{{ date('y', strtotime($mv->date_main)) }}</td>
    </tr>
    <tr>
        <td>Report Date</td>
        <td>:</td>
        <td class='info_isi'>{{ date("d F Y", strtotime($mv->date_main)) }}</td>
    </tr>
    <tr>
        <td>Report Time</td>
        <td>:</td>
        <td class='info_isi'>{{ date("H:i", strtotime($mv->date_main)) }}</td>
    </tr>
    <tr>
        <td>Purpose of Visit</td>
        <td>:</td>
        <td class='info_isi'>{!! $mv->topic !!}</td>
    </tr>
    <tr>
        <td>Destination of Visit</td>
        <td>:</td>
        <td class='info_isi'>{!! $mv->location !!}</td>
    </tr>
</table>
<br />
<table class='record'>
    <tr>
        <th>Full Name</th>
        <th>Position</th>
        <th>E-Mail</th>
        <th>Phone Number</th>
        <th>Signature</th>
    </tr>
    @foreach ($attendence as $i => $emp)
        <tr>
            <td>{{ $emp->emp_name }}</td>
            <td>{{ $emp->emp_position }}</td>
            <td>{{ $emp->email }}</td>
            <td>{{ $emp->phone }}</td>
            <td>
                <img src='{{ str_replace('public', 'public_html', asset('media/sign_mv/'.$emp->sig_address)) }}' alt='picture' width='40px'/>
            </td>
        </tr>
    @endforeach
</table>
</div>
<br>
<div class="page">
    <table class='header'>
        <tr>
            <td width='80' rowspan='2'>
                <img src='{{ str_replace('public', 'public_html', asset('images/'.$comp->p_logo)) }}' width='150px'>
            </td>
            <td style='text-align:center'>
                <h2><strong>{{ strtoupper($comp->company_name) }}</strong></h2>
            </td>
            <td width='80' rowspan='2'>
                &nbsp;
            </td>
        </tr>
        <tr>
            <td style='text-align:center'><h3><strong>MANAGEMENT VISIT REPORT</strong></h3></td>
        </tr>
    </table>
    <br>
    <h3>Report Record</h3>
    <table class='info'>
        <tr>
            <td>Report ID</td>
            <td>:</td>
            <td class='info_isi'>{{ sprintf("%03d", $mv->id_main) }}/{{ $comp->tag }}-MV/{{ date('m', strtotime($mv->date_main)) }}/{{ date('y', strtotime($mv->date_main)) }}</td>
        </tr>
        <tr>
            <td>Report Date</td>
            <td>:</td>
            <td class='info_isi'>{{ date("d F Y", strtotime($mv->date_main)) }}</td>
        </tr>
        <tr>
            <td>Report Time</td>
            <td>:</td>
            <td class='info_isi'>{{ date("H:i", strtotime($mv->date_main)) }}</td>
        </tr>
        <tr>
            <td>Purpose of Visit</td>
            <td>:</td>
            <td class='info_isi'>{!! $mv->topic !!}</td>
        </tr>
        <tr>
            <td>Destination of Visit</td>
            <td>:</td>
            <td class='info_isi'>{!! $mv->location !!}</td>
        </tr>
    </table>
    <br />
    <table class='record'>
        <tr>
            <th>Time</th>
            <th>Report By</th>
            <th width='400px'>Description</th>
            <th>Action</th>
            <th>Deadline</th>
        </tr>
        @foreach ($mom as $i => $item)
            <tr>
                <td>{{ date('H:i:s', strtotime($item->input_time)) }}</td>
                <td>{{ $item->floor }}</td>
                <td>{!! $item->content !!}</td>
                <td>{!! $item->pic !!}</td>
                <td>{{ date('d F Y', strtotime($item->deadline)) }}</td>
            </tr>
        @endforeach
    </table>
</div>
<div class="page">
    <table class='header'>
        <tr>
            <td width='80' rowspan='2'>
                <img src='{{ str_replace('public', 'public_html', asset('images/'.$comp->p_logo)) }}' width='150px'>
            </td>
            <td style='text-align:center'>
                <h2><strong>{{ strtoupper($comp->company_name) }}</strong></h2>
            </td>
            <td width='80' rowspan='2'>
                &nbsp;
            </td>
        </tr>
        <tr>
            <td style='text-align:center'><h3><strong>MANAGEMENT VISIT REPORT</strong></h3></td>
        </tr>
    </table>
    <br>
    <h3>Report Entries</h3>
    <table class='info'>
        <tr>
            <td>Report ID</td>
            <td>:</td>
            <td class='info_isi'>{{ sprintf("%03d", $mv->id_main) }}/{{ $comp->tag }}-MV/{{ date('m', strtotime($mv->date_main)) }}/{{ date('y', strtotime($mv->date_main)) }}</td>
        </tr>
        <tr>
            <td>Picture Date & Time</td>
            <td>:</td>
            <td class='info_isi'>{{ date("d F Y", strtotime($mv->date_main)) }}</td>
        </tr>
        <tr>
            <td>Report Time</td>
            <td>:</td>
            <td class='info_isi'>{{ date("H:i", strtotime($mv->date_main)) }}</td>
        </tr>
        <tr>
            <td>Purpose of Visit</td>
            <td>:</td>
            <td class='info_isi'>{!! $mv->topic !!}</td>
        </tr>
        <tr>
            <td>Destination of Visit</td>
            <td>:</td>
            <td class='info_isi'>{!! $mv->location !!}</td>
        </tr>
    </table>
    <br />
    <table class='record'>
        <tr>
            <th>Attachment Picture</th>
            <th>Date & Time</th>
        </tr>
        @foreach ($pict as $item)
            <tr>
                <td align="center"><img src='{{ str_replace("public", "public_html", asset($item->attach_pic)) }}' alt='picture' style='max-width:40px; max-height:40px'/></td>
                <td align="center">{{ date('d F Y H:i', strtotime($item->created_at)) }}</td>
            </tr>
        @endforeach
    </table>
</div>
