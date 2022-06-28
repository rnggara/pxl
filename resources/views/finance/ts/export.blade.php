<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Excel</title>
</head>
<body>
    <table border="1">
        <tr>
            <th colspan="4">Data {{ $title }}</th>
        </tr>
        <tr>
            <th colspan="4">
                periode {{ date("m/d/Y", strtotime($from)) }} - {{ date("m/d/Y", strtotime($to)) }}
            </th>
        </tr>
        <tr>
            <th>No</th>
            <th>Entry</th>
            <th>Date</th>
            <th>Amount</th>
        </tr>
        @php
            $sum = 0;
        @endphp
        @foreach ($entry as $i => $item)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $item['entry'] }}</td>
                <td>{{ $item['date'] }}</td>
                <td>{{ $item['amount'] }}</td>
            </tr>
            @php
                $sum += $item['amount'];
            @endphp
        @endforeach
        <tr>
            <th colspan="3">Total</th>
            <th>{{ $sum }}</th>
        </tr>
    </table>
</body>
</html>
