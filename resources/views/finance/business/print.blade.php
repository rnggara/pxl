<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print</title>
    <style>
        body{
            font-size: 12px;
        }
        .table {
            border: 1px solid #000;
        }

        .table-content{
            vertical-align: top;
        }
        pre {
            overflow-x: auto;
            white-space: pre-wrap;
            white-space: -moz-pre-wrap;
            white-space: -pre-wrap;
            white-space: -o-pre-wrap;
            word-wrap: break-word;
            border: 1px solid black;
        }

        #table-detail tr{
            border: 1px solid #000;
        }

        #table-detail td{
            border: 1px solid #000;
        }

        #table-detail th{
            border: 1px solid #000;
        }

        #table-detail {
            border-collapse: collapse;
        }
    </style>
</head>
<body>
<table width="100%" class="table">
    <tr>
        <td colspan="2">
            <table>
                <td>Business Project</td>
                <td>:</td>
                <td><b>{{$business->bank}}</b></td>
            </table>
        </td>
    </tr>
    <tr>
        <td class="table-content">
            <table>
                <tr>
                    <td nowrap="nowrap">Partner Name</td>
                    <td>:</td>
                    <td><b>{{$business->description}}</b></td>
                </tr>
                <tr>
                    <td nowrap="nowrap">Currency</td>
                    <td>:</td>
                    <td>{{$business->currency}}</td>
                </tr>
                <tr>
                    <td nowrap="nowrap">Invested Amount</td>
                    <td>:</td>
                    <td><b>{{$business->currency.". ".number_format($business->value, 2)}}</b></td>
                </tr>
                <tr>
                    <td nowrap="nowrap">Invested Date</td>
                    <td>:</td>
                    <td>{{date('d F Y', strtotime($business->moneydrop))}}</td>
                </tr>
                <tr>
                    <td nowrap="nowrap">Interest Percentage</td>
                    <td>:</td>
                    <td>{{$business->bunga}} % per month</td>
                </tr>
                <tr>
                    <td nowrap="nowrap">Business Duration</td>
                    <td>:</td>
                    <td>{{$business->period}} month(s)</td>
                </tr>
            </table>
        </td>
        <td class="table-content">
            <table style="margin-left: 10px">
                <tr>
                    <td nowrap="nowrap">Payment Start Date</td>
                    <td>:</td>
                    <td>{{date('d F Y', strtotime($business->start))}}</td>
                </tr>
                <tr>
                    <td>Proportional</td>
                    <td>:</td>
                    <td>{{$business->type}} - {{($business->type == "LUM") ? "LUMPSUM" : "PROPORTIONAL"}}</td>
                </tr>
                <tr>
                    <td>Penalty</td>
                    <td>:</td>
                    <td><b>{{$business->currency.". ".number_format($business->own_amount, 2)}} / day</b></td>
                </tr>
                <tr>
                    <td nowrap="nowrap">Penalty Remarks</td>
                    <td>:</td>
                    <td>
                        <pre>{{$business->own_remarks}}</pre>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<h3>Business Detail</h3>
<hr>
<table width="100%" id="table-detail">
    <tr>
        @foreach($fields as $field)
        <th style="text-align: center">
            {{ucwords(str_replace("_", " ", $field))}}
        </th>
        @endforeach
    </tr>
    @foreach($data as $key => $item)
        <tr>
        @foreach($fields as $field)
            <?php
                /** @var TYPE_NAME $item */
                /** @var TYPE_NAME $field */
                $echo = explode("-", $item[$field]);
            ?>
            <td align="{{(end($echo) == "value") ? "right" : "center"}}">
                {{$echo[0]}}
            </td>
        @endforeach
        </tr>
    @endforeach
    <tr>
        <td colspan="4" align="right">Totals</td>

        <td align="right">{{$foot['installment']}}</td>
        <td align="right">{{$foot['profit']}}</td>
        <td align="right">{{$foot['penalty']}}</td>
        <td align="right">{{$foot['total']}}</td>
        <td></td>
    </tr>
</table>

</body>
</html>
