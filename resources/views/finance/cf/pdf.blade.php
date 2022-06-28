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
            padding: 15px;
        }

        .table-text {
            font-size: 16px;
            font-weight: bold
        }
    </style>
</head>
<body>
    <table style="width: 100%" class="table">
        <tr class="table">
            <th colspan="3">
                <h3>Cashflow Period {{ date("F Y", strtotime($mnth)) }}</h3>
            </th>
        </tr>
        {{-- START - STARTING BALANCE --}}
        <tr class="table">
            <th colspan="3" align="left">
                STARTING BALANCE
            </th>
        </tr>
        <tr>
            <td align="center"><b>Description</b></td>
            <td align="right"><b>Amount (IDR)</b></td>
            <td align="right"><b>Amount (USD)</b></td>
        </tr>
        @php
            $startData = [];
            if (isset($data['data']['begin'])) {
                $startData = $data['data']['begin'];
            }
            $sumStartIDR = 0;
            $sumStartUSD = 0;
        @endphp
        @foreach ($treasury as $i => $tre)
            @php
                $amountIDR = 0;
                $amountUSD = 0;
                if(isset($startData['IDR'])){
                    $amountIDR = (isset($startData['IDR'][$tre->id])) ? $startData['IDR'][$tre->id] : 0;
                }
                if(isset($startData['USD'])){
                    $amountUSD = (isset($startData['USD'][$tre->id])) ? $startData['USD'][$tre->id] : 0;
                }
                $sumStartIDR += $amountIDR;
                $sumStartUSD += $amountUSD;
            @endphp
            <tr style="border-bottom : 1px dotted">
                <td>
                    {{ $i+1 }}. {{ $tre->source }}
                </td>
                <td align="right">
                    {{ number_format($amountIDR, 2) }}
                </td>
                <td align="right">
                    {{ number_format($amountUSD, 2) }}
                </td>
            </tr>
        @endforeach
        <tr>
            <td><b>TOTAL STARTING BALANCE</b></td>
            <td align="right"><b>{{ number_format($sumStartIDR, 2) }}</b></td>
            <td align="right"><b>{{ number_format($sumStartUSD, 2) }}</b></td>
        </tr>
        {{-- END - STARTING BALANCE --}}
        {{-- START - CASH IN/OUT --}}
        @php
            $sumSurDefIDR = $sumStartIDR;
            $sumSurDefUSD = $sumStartUSD;
            $iData = $data['data'];
        @endphp
        @foreach ($cash as $item)
            @php
                $itemData = [];
                if(isset($iData[$item])){
                    $itemData = $iData[$item];
                }

                $sumItemIDR = 0;
                $sumItemUSD = 0;
            @endphp
            <tr class="table">
                <th colspan="3" align="left">
                    {{ strtoupper(str_replace("_", " ", $item)) }}
                </th>
            </tr>
            <tr>
                <td align="center"><b>Description</b></td>
                <td align="right"><b>Amount (IDR)</b></td>
                <td align="right"><b>Amount (USD)</b></td>
            </tr>
            @if (isset($setting[$item]))
                @foreach ($setting[$item] as $n => $st)
                    @empty($st->parent)
                    @php
                        $sumStIDR = 0;
                        $sumStUSD = 0;
                    @endphp
                    <tr style="border-bottom : 1px dotted">
                        <td colspan="3">{{ $n+1 }}. {{ $st->label }}</td>
                    </tr>
                        @if (!empty($st->child))
                            @foreach ($st->child as $m => $child)
                                <tr style="border-bottom : 1px dotted ">
                                    <td>{{ ($n+1).".".($m+1) }} {{ $child->label }}</td>
                                    <td align="right">
                                        @php
                                            $amountIDR = 0;
                                            $amountUSD = 0;
                                            if(isset($itemData['IDR'])){
                                                $amountIDR = (isset($itemData['IDR'][$child->id])) ? $itemData['IDR'][$child->id] : 0;
                                            }

                                            if(isset($itemData['USD'])){
                                                $amountUSD = (isset($itemData['USD'][$child->id])) ? $itemData['USD'][$child->id] : 0;
                                            }

                                            $sumItemIDR += $amountIDR;
                                            $sumItemUSD += $amountUSD;
                                            $sumStIDR += $amountIDR;
                                            $sumStUSD += $amountUSD;
                                        @endphp
                                        {{ number_format($amountIDR,2) }}
                                    </td>
                                    <td align="right">
                                        {{ number_format($amountUSD,2) }}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    <tr style="border-bottom : 1px dotted ">
                        <td>TOTAL {{ $st->label }}</td>
                        <td align="right">
                            {{ number_format($sumStIDR, 2) }}
                        </td>
                        <td align="right">
                            {{ number_format($sumStUSD, 2) }}
                        </td>
                    </tr>
                    @endif
                @endforeach
            @endif
            <tr>
                <td><b>TOTAL {{ strtoupper(str_replace("_", " ", $item)) }}</b></td>
                <td align="right"><b>{{ number_format($sumItemIDR, 2) }}</b></td>
                <td align="right"><b>{{ number_format($sumItemUSD, 2) }}</b></td>
            </tr>
            @php
                if($item == "cash_in"){
                    $sumSurDefIDR += $sumItemIDR;
                    $sumSurDefUSD += $sumItemUSD;
                } else {
                    $sumSurDefIDR -= $sumItemIDR;
                    $sumSurDefUSD -= $sumItemUSD;
                }

            @endphp
        @endforeach
        {{-- END - CASH IN/OUT --}}
        <tr class="table">
            <th align="left">
                SURPLUS/DEFISIT
            </th>
            <th align="right"><b><span style="color: {{ ($sumSurDefIDR > 0) ? 'green' : (($sumSurDefIDR == 0) ? '' : 'red')  }}">{{ number_format($sumSurDefIDR, 2) }}</span></b></th>
            <th align="right"><b><span style="color: {{ ($sumSurDefUSD > 0) ? 'green' : (($sumSurDefUSD == 0) ? '' : 'red')  }}">{{ number_format($sumSurDefUSD, 2) }}</span></b></th>
        </tr>
        {{-- START - ENDING BALANCE --}}
        <tr class="table">
            <th colspan="3" align="left">
                ENDING BALANCE
            </th>
        </tr>
        <tr>
            <td align="center"><b>Description</b></td>
            <td align="right"><b>Amount (IDR)</b></td>
        </tr>
        @php
            $endData = [];
            if (isset($data['data']['end'])) {
                $endData = $data['data']['end'];
            }
            $sumEndIDR = 0;
            $sumEndUSD = 0;
        @endphp
        @foreach ($treasury as $i => $tre)
            <tr style="border-bottom : 1px dotted">
                <td>
                    {{ $i+1 }}. {{ $tre->source }}
                </td>
                <td align="right">
                    @php
                        $amountIDR = 0;
                        $amountUSD = 0;
                        if(isset($endData['IDR'])){
                            $amountIDR = (isset($endData['IDR'][$tre->id])) ? $endData['IDR'][$tre->id] : 0;
                        }
                        if(isset($endData['USD'])){
                            $amountUSD = (isset($endData['USD'][$tre->id])) ? $endData['USD'][$tre->id] : 0;
                        }
                        $sumEndIDR += $amountIDR;
                        $sumEndUSD += $amountUSD;
                    @endphp
                    {{ number_format($amountIDR, 2) }}
                </td>
                <td align="right">
                    {{ number_format($amountUSD, 2) }}
                </td>
            </tr>
        @endforeach
        <tr>
            <td><b>TOTAL ENDING BALANCE</b></td>
            <td align="right"><b>{{ number_format($sumEndIDR, 2) }}</b></td>
            <td align="right"><b>{{ number_format($sumEndUSD, 2) }}</b></td>
        </tr>
    </table>
</body>
</html>
