<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Schedule Payment</title>
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
</head>

<style>
    @page
    {
        size: auto;   /* auto is the initial value */

        /* this affects the margin in the printer settings */
        padding: 0px;
    }

    @media print{
        html, body {
            padding: 0px;
        }
    }

    body
    {
        /* this affects the margin on the content before sending to printer */
        margin: 15px;
    }
</style>

<body>
<div id='logo' style="width:300px; border:0px solid #000; text-align:center; float:left; margin-bottom:30px;">
    <img src="{{str_replace('public', 'public_html', asset('images/'.\Illuminate\Support\Facades\Session::get('company_p_logo')))}}" height="100px" >
    <div id='comp_name'>
        {{\Illuminate\Support\Facades\Session::get('company_name_parent')}}
    </div>
</div>
<div style='font-size:10px; float:left; border:0px solid #000; width:400px'>
    <strong>{{\Illuminate\Support\Facades\Session::get('company_name_parent')}}</strong><br>
    {{\Illuminate\Support\Facades\Session::get('company_address')}}<br>
    Phone : {{\Illuminate\Support\Facades\Session::get('company_phone')}}<br>
    {{--            Fax : {{\Illuminate\Support\Facades\Session::get('company_address')}}<br>--}}
    Email : {{\Illuminate\Support\Facades\Session::get('company_email')}}<br>
    NPWP : {{\Illuminate\Support\Facades\Session::get('company_npwp')}}
</div>
<div id='doc_name' style="clear:right; border:0px solid #000; float:left; width:180px; text-align:right; font-size:20px; font-weight:bold">
    Schedule Payment
    <br />
</div>

<div style="clear:both;">
    <div class="col-md-6">
        <table border='0' width="900">
            <tr valign="top">
                <td width="90%">
                    <table border='0' width="100%">
                        <tr valign='top'>
                            <td>No</td><td>:</td>
                            <td>{{$sp->num}}</td>
                        </tr>
                        <tr valign='top'>
                            <td>Date</td><td>:</td>
                            <td>{{date('d F Y', strtotime($sp->date1))}} - {{date('d F Y', strtotime($sp->date2))}}</td>
                        </tr>
                        <tr valign='top'>
                            <td>Division</td><td>:</td>
                            <td>Finance & Accounting</td>
                        </tr>
                        <tr valign='top'>
                            <td>Bank</td><td>:</td>
                            <td>{{$treasury->source}}</td>
                        </tr>
                        <tr valign='top'>
                            <td>Currency</td><td>:</td>
                            <td>
                                IDR
                            </td>
                        </tr>
                    </table>

                </td>
            </tr>
        </table>
    </div>
</div>

<div class="row mt-5">
    <div class="col-md-6">
        <table width="900" class='mytable'>
            <tr>
                <th class="text-center">Date</th>
                <th class="text-left">Bank</th>
                <th class="text-left">Description</th>
                <th class="text-right">Debit</th>
                <th class="text-right">Credit</th>
            </tr>
            <?php
            $sumdebit = 0;
            $sumcredit = 0;
            ?>
            @foreach($his as $key => $value)
                <tr>
                    <td align="center">{{$value->date_input}}</td>
                    <td align="center">{{$treasury->source}}</td>
                    <td align="center">{{$value->description}}</td>
                    <td align="right">
                        {{($value->IDR < 0) ? number_format(abs($value->IDR)) : number_format(0)}}
                    </td>
                    <td align="right">
                        {{($value->IDR > 0) ? number_format(abs($value->IDR)) : number_format(0)}}
                    </td>
                    <?php
                    /** @var TYPE_NAME $value */
                    if ($value->IDR < 0){
                        $sumdebit += $value->IDR;
                        $sumcredit += 0;
                    } else {
                        $sumdebit += 0;
                        $sumcredit += $value->IDR;
                    }
                    ?>
                </tr>
            @endforeach
            <tr>
                <td colspan='3' align='right'>Sub Total :</td>
                <td align="right">{{number_format(abs($sumdebit))}}</td>
                <td align="right">{{number_format(abs($sumcredit))}}</td>
            </tr>
            <tr>
                <td colspan='3' align='right'>Current Balance :</td>
                <td colspan='2' align="right">{{number_format(abs($sumcredit) - abs($sumdebit))}}</td>
            </tr>
            <tr>
                <td colspan='3' align='right'>Hold Amount :</td>
                <td colspan='2' align="right">({{number_format($treasury->account_idr)}})</td>
            </tr>
            <tr>
                <td colspan='3' align='right'>Available Balance :</td>
                <td colspan='2' align="right">{{number_format((abs($sumcredit) - abs($sumdebit)) - $treasury->account_idr)}}</td>
            </tr>
        </table>
        <table width="900" style="margin-top: 100px" class='mytable'>
            <tr>
                <td align="center" width="33%">
                    <strong>Prepared By
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></strong>
                </td>
                <td align="center" width="33%">
                    <strong>Approved By
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></strong>
                </td>
                <td align="center" width="33%">
                    <strong>Checked By
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></strong>
                </td>
            </tr>
        </table>
    </div>
</div>
</body>
@include('layouts.scripts')
</html>
