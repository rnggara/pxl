<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
    <style>
        body {
            font-family: monospace;
        }
        .table {
            border: 1px solid black;
        }

        .table {
            border-collapse: collapse;
            padding: 10px;
        }

        .table-text {
            font-size: 16px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <table style="width: 100%" class="table">
        <tr>
            <th colspan="3">
                Profit & Loss <br>
                Periode : {{ date("d F Y", strtotime($from)) }} - {{ date("d F Y", strtotime($to)) }}
            </th>
        </tr>
        <tr>
            <th nowrap="nowrap" class="table">Code</th>
            <th nowrap="nowrap" class="table">Amount</th>
            <th nowrap="nowrap" class="table">Total</th>
        </tr>
        @php
            $rate = (!empty($setting->tax)) ? $setting->tax : 0;
        @endphp
        @foreach ($data as $key => $item)
            @php
                $total[$key] = 0;
            @endphp
            @if (is_array($item))
                <tr class="bg-secondary">
                    <td colspan="3" class="font-weight-bold table-text table">{{ str_replace("_", " ", ucwords($key)) }}</td>
                </tr>
                @foreach ($item as $i => $val)
                    @if (!in_array($i, $oe))
                        @php
                            $sum = array_sum($val['amount']);
                            $total[$key] += $sum
                        @endphp
                        <tr>
                            <td>{{ $val['code'] }}</td>
                            <td align="right">
                                {{ number_format($sum, 2) }}
                            </td>
                            <td></td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="3" class="font-weight-bold table-text">{{ str_replace("_", " ", ucwords($i)) }}</td>
                        </tr>
                        @if (count($val) > 0)
                            @foreach ($val as $ival)
                                @php
                                    $sum = array_sum($ival['amount']);
                                    $total[$key] += $sum;
                                @endphp
                                <tr>
                                    <td>{{ $ival['code'] }}</td>
                                    <td align="right">{{number_format($sum, 2)}}</td>
                                    <td></td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td>No Data</td>
                                <td align="right">{{ number_format(0, 2) }}</td>
                                <td></td>
                            </tr>
                        @endif
                    @endif
                @endforeach
                <tr>
                    <td colspan="2" class="font-weight-bold table-text table">Total {{ str_replace("_", " ", ucwords($key)) }}</td>
                    <td align="right" class="font-weight-bold table">{{ number_format($total[$key], 2) }}</td>
                </tr>
            @else
            <tr class="bg-secondary">
                @php
                    $total[$key] = eval("return $item;");
                @endphp
                <td colspan="2" class="font-weight-bold table-text table">{{ str_replace("_", " ", ucwords($key)) }}</td>
                <td align="right" class="font-weight-bold table">
                    {{ number_format($total[$key], 2) }}
                </td>
            </tr>
            @endif
        @endforeach
    </table>
</body>
</html>
