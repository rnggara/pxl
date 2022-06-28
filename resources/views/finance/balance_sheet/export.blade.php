@php
    header("Content-Type: application/octet-stream");
	header("Expires: 0");
	header("Pragma: no-cache");
	header("Content-Disposition: attachment; filename=balance-sheet-$bs->description.xls");
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <table style="width: 50%; margin-bottom : 10px">
        <tr>
            <th colspan="4">{{ $bs->description }}</th>
        </tr>
        <tr>
            <th colspan="4">Periode {{ date("m/d/Y", strtotime($from)) }} - {{ date("m/d/Y", strtotime($to)) }}</th>
        </tr>
    </table>
    @php
        $sum_total = 0;
    @endphp
    @foreach ($entry as $code => $item)
        <table border="1" style="width: 50%; margin-bottom : 10px">
            <tr>
                <th colspan="4">{{ "[$code] ".$coa_description[$code] }}</th>
            </tr>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 45%">Description</th>
                <th style="width: 25%">Date</th>
                <th style="width: 25%">Amount</th>
            </tr>
            @if (count($item) == 0)
                <tr>
                    <th colspan="4">No Data Available</th>
                </tr>
            @else
                @php
                    $sum = 0;
                @endphp
                @foreach ($item as $i => $value)
                    <tr>
                        <td align="center">{{ $i+1 }}</td>
                        <td>{!! $value['description'] !!}</td>
                        <td>{!! $value['date'] !!}</td>
                        <td align="right">{!! number_format($value['amount'], 2) !!}</td>
                    </tr>
                    @php
                        $sum += $value['amount'];
                        $sum_total += $value['amount'];
                    @endphp
                @endforeach
                <tr>
                    <th colspan="3">Total</th>
                    <th align="right">{{ number_format($sum, 2) }}</th>
                </tr>
            @endif
        </table>
        <table>
            <tr>
                <th colspan="4"></th>
            </tr>
        </table>
    @endforeach
    <table border="1" style="width: 50%; margin-bottom : 10px">
        <tr>
            <th colspan="3" style="width: 75%">Total</th>
            <th align="right">{{ number_format($sum_total, 2) }}</th>
        </tr>
    </table>
</body>
</html>
