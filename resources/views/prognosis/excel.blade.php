<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        table tr td {
            border: 1px solid black;
            padding: 10px
        }
        table {
            border-collapse: collapse;
        }
    </style>
</head>
<?php
/** @var TYPE_NAME $prognosis */
$filename = $prognosis->subject."-".date("Y-m-d");
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=".$filename.".xls");
?>
<body>
<table>
    <tr>
        <td>#</td>
        <td>Paper</td>
        <td>Amount</td>
        <td>Discount</td>
        <td>PPN</td>
        <td>Total</td>
        <td>Paid</td>
        <td>Unpaid</td>
        <td>Overpaid</td>
    </tr>
    @php $i=1; $sumAmount = 0; $sumDiscount = 0;$sumPPN = 0;$sumTotal = 0;$sumPaid = 0;$sumUnpaid = 0; $sumOver = 0; @endphp
    @if(isset($data[$id]))
        @foreach($data[$id] as $key => $item)
{{--            {{dd($item)}}--}}
            @if(isset($item['description']))
                @foreach($item['description']['value'] as $nId => $value)
                    @if(isset($value['subcost']))
                        @foreach($value['subcost'] as $sb)
                            <?php
                            $amount = (isset($sb['amount'])) ? $sb['amount'] : 0;
                            $discount = (isset($sb['discount'])) ? $sb['discount'] : 0;
                            $ppn = (isset($sb['ppn'])) ? $sb['ppn'] : 0;
                            $total = $amount + $discount + $ppn;
                            $paid = $amount;
                            $unpaid = $total - $paid;
                            $ovPaid = 0;
                            if ($unpaid < 0){
                                $ovPaid = $unpaid;
                                $unpaid = 0;
                            }
                            $sumAmount += $amount;
                            $sumDiscount += $discount;
                            $sumPPN += $ppn;
                            $sumTotal += $total;
                            $sumPaid += $paid;
                            $sumUnpaid += $unpaid;
                            $sumOver += $ovPaid;
                            ?>
                            <tr>
                                <td align="center">{{$i++}}</td>
                                <td>{{$sb['desc']}}</td>
                                <td align="right">{{number_format($amount, 2)}}</td>
                                <td align="right">{{number_format($discount, 2)}}</td>
                                <td align="right">{{number_format($ppn, 2)}}</td>
                                <td align="right">{{number_format($total, 2)}}</td>
                                <td align="right">{{number_format($paid, 2)}}</td>
                                <td align="right">{{number_format($unpaid, 2)}}</td>
                                <td align="right">{{number_format($ovPaid, 2)}}</td>
                            </tr>
                        @endforeach
                    @else
                        <?php
                        $paid = 0;
                        $amount = (isset($value['amount'])) ? $value['amount'] : 0;
                        $discount = (isset($value['discount'])) ? $value['discount'] : 0;
                        $ppn = (isset($value['ppn'])) ? $value['ppn'] : 0;
                        $total = $amount + $discount + $ppn;
                        /** @var TYPE_NAME $item */
                        /** @var TYPE_NAME $nId */
                        if (isset($item['description']['paid'])){
                            $dataPaid = $item['description']['paid'][$nId];
                            $paid = $dataPaid['amount'];
                        }
                        $unpaid = $total - $paid;
                        $ovPaid = 0;
                        if ($unpaid < 0){
                            $ovPaid = $unpaid;
                            $unpaid = 0;
                        }
                        $sumAmount += $amount;
                        $sumDiscount += $discount;
                        $sumPPN += $ppn;
                        $sumTotal += $total;
                        $sumPaid += $paid;
                        $sumUnpaid += $unpaid;
                        $sumOver += $ovPaid;
                        ?>
                            <tr>
                                <td align="center">{{$i++}}</td>
                                <td>{{$value['paper']}}</td>
                                <td align="right">{{number_format($amount, 2)}}</td>
                                <td align="right">{{number_format($discount, 2)}}</td>
                                <td align="right">{{number_format($ppn, 2)}}</td>
                                <td align="right">{{number_format($total, 2)}}</td>
                                <td align="right">{{number_format($paid, 2)}}</td>
                                <td align="right">{{number_format($unpaid, 2)}}</td>
                                <td align="right">{{number_format($ovPaid, 2)}}</td>
                            </tr>
                    @endif
                @endforeach
            @endif
        @endforeach
    @else
        <tr>
            <td colspan="8" align="center">No Data Available</td>
        </tr>
    @endif
    <tr>
        <td colspan="2" align="center"><b>TOTAL</b></td>
        <td align="right">{{number_format($sumAmount, 2)}}</td>
        <td align="right">{{number_format($sumDiscount, 2)}}</td>
        <td align="right">{{number_format($sumPPN, 2)}}</td>
        <td align="right">{{number_format($sumTotal, 2)}}</td>
        <td align="right">{{number_format($sumPaid, 2)}}</td>
        <td align="right">{{number_format($sumUnpaid, 2)}}</td>
        <td align="right">{{number_format($sumOver, 2)}}</td>
    </tr>
</table>
</body>
</html>
