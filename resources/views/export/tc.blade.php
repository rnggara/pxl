@php
    header("Content-Type: application/octet-stream");
	header("Expires: 0");
	header("Pragma: no-cache");
	header("Content-Disposition: attachment; filename=$type-$file_name.xls");
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
    <table border="1">
        <tr>
            <th colspan="4">{{ $file_name }}</th>
        </tr>
        <tr>
            <th>No</th>
            <th>Description</th>
            <th>Date</th>
            <th>Amount</th>
        </tr>
        @if (count($history) == 0)
            <tr>
                <th colspan="4">No Data Available</th>
            </tr>
        @else
            @php
                $sum = 0;
            @endphp
            @foreach ($history as $i => $item)
                @php
                    $amount = (!empty($item->debit)) ? $item->debit : $item->credit * -1;
                    $description = (isset($t_his[$item->id_treasure_history])) ? $t_his[$item->id_treasure_history] : $item->description;
                @endphp
                <tr>
                    <td align="center">{{ $i+1 }}</td>
                    <td>{!! $description !!}</td>
                    <td>{{ $item->coa_date }}</td>
                    <td align="right">{{ number_format($amount, 2) }}</td>
                    @php
                        $sum += $amount;
                    @endphp
                </tr>
            @endforeach
            <tr>
                <th colspan="3">Total</th>
                <th align="right">{{ number_format($sum, 2) }}</th>
            </tr>
        @endif
    </table>
</body>
</html>
