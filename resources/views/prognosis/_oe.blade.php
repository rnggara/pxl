<table class="table table-hover table-bordered">
    <thead>
    <tr>
        <th class="text-center" width="5%">#</th>
        <th class="text-center" width="40%">OPERATING EXPENSE</th>
        <th class="text-center" width="10%">PASS CODE PROJECT</th>
        <th class="text-center" width="10%">PROGNOSIS</th>
        <th class="text-center" width="5%">%</th>
        <th class="text-center" width="5%">Setting</th>
        <th class="text-center" width="10%">Actual Value</th>
        <th class="text-center" width="5%">%</th>
        <th class="text-center" width="10%">UNPAID</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $percentSales = 0;
    $amountSales  = 0;
    $actPercentSales  = 0;
    $actAmountSales  = 0;
    $unpaid = 0;
    ?>
    @foreach($prognosis as $list)
        @if($list->category == "operating_expenses")
            <tr>
                <td align="center"></td>
                <td>{{strtoupper($list->subject)}}</td>
                <td align="center">{{strtoupper($list->RCTR)}}</td>
                <td align="right">{{number_format($totalsales * ($list->amount/100), 2)}} %</td>
                <td align="right">{{number_format($list->amount, 2)}}</td>
                <td align="center">
                    <button type="button" class="btn btn-xs btn-primary btn-icon" onclick="salesModal('oe','{{$list->id}}')"><i class="fa fa-cog"></i></button>
                </td>
                <td align="right">
                    @php $act_value = 0; $paid_value = 0; @endphp
                    @if(isset($data_value[$list->id]))
                        @foreach($data_value[$list->id] as $val)
                            @php
                                /** @var TYPE_NAME $val */
                                $act_value += $val['actual_value'];
                                $paid_value += $val['paid_value'];
                            @endphp
                        @endforeach
                    @endif
                    {{number_format($act_value, 2)}}
                </td>
                <td align="right">{{number_format(($act_value / $totalsales) * 100, 2)}} %</td>
                <td align="right">{{number_format($act_value - $paid_value, 2)}}</td>
            </tr>
            <?php /** @var TYPE_NAME $list */
            /** @var TYPE_NAME $totalsales */
            $percentSales += $list->amount;
            $amountSales += $totalsales * ($list->amount/100);
            /** @var TYPE_NAME $act_value */
            $actAmountSales += $act_value;
            $actPercentSales += ($act_value / $totalsales) * 100;
            /** @var TYPE_NAME $paid_value */
            $unpaid += $act_value - $paid_value;
            ?>
        @endif
    @endforeach
    </tbody>
    <tr>
        <td align="center" colspan="3"><b>TOTAL OPERATING EXPENSES</b></td>
        <td align="right">{{number_format($amountSales, 2)}}</td>
        <td align="right">{{number_format($percentSales, 2)}} %</td>
        <td></td>
        <td align="right">{{number_format($actAmountSales, 2)}}</td>
        <td align="right">{{number_format($actPercentSales, 2)}} %</td>
        <td align="right">{{number_format($unpaid, 2)}}</td>
    </tr>
</table>
