@php
$border = 0;
    if($view == "export"){
        header("Content-Type: application/octet-stream");
        header("Expires: 0");
        header("Pragma: no-cache");
        $file_name = "Balance_investor_".date('Y_m_d');
        header("Content-Disposition: attachment; filename=".$file_name.".xls");
        $border = 1;
    }
@endphp
<table class="table table-hover table-bordered table-reponsive" {{ ($border == 1) ? "border=1" : "" }}>
    <thead>
    <tr>
        <th class="text-center" colspan="11">
            <h3 class="font-weight-bold">BUSINESS PARTNERS</h3>
            <h4 class="font-weight-bold">TAHUN : {{ date("Y", strtotime($period)) }}</h4>
            <h4 class="font-weight-bold">PERIODE : {{ strtoupper(date("F", strtotime($period))) }}</h4>
        </th>
    </tr>
    <tr>
        <th class="text-center" rowspan="2">No</th>
        <th class="text-center" rowspan="2">Partners</th>
        <th class="text-center" rowspan="2">Business</th>
        <th class="text-center" rowspan="2">Period (Month)</th>
        <th class="text-center">Interest</th>
        <th class="text-center" colspan="6">Installment</th>
    </tr>
    <tr>
        <th class="text-center">%</th>
        <th class="text-center">MONTH</th>
        <th class="text-center">TOTAL</th>
        <th class="text-center">PAID</th>
        <th class="text-center">PAYMENT BALANCE</th>
        <th class="text-center">PRINCIPAL BALANCE</th>
        <th class="text-center">TOTAL BALANCE</th>
    </tr>
    </thead>
    <tbody>
    @if (!empty($row))
        @php
            $sumMonthly = 0;
            $sumAmount = 0;
            $sumPaid = 0;
            $sumPBalance = 0;
            $sumSummary = 0;
            $sumTotal = 0;
            $i = 1;
        @endphp
        @foreach ($row as $partnerKey => $items)
            @php
                $payment_balance = 0;
                $summary_balance = 0;
                $total_balance = 0;
                $total_p_balance = 0;
                $key_first = array_key_first($items);
                $first = $items[$key_first];
                // $payment_balance = $first['amount'] - $first['paid'];
                $payment_balance = $first['bl'];
                $summary_balance = $first['summary'];
                // $summary_balance = $first['amount'] - $first['paid'];
                // $total_balance = $payment_balance + $summary_balance;
                $total_balance = $first['amount'] - $first['paid'];
                $total_p_balance += $payment_balance;

                $sumMonthly += $first['monthly'];
                $sumAmount += $first['amount'];
                $sumPaid += $first['paid'];
                $sumPBalance += $payment_balance;
                $sumSummary += $summary_balance;
                $sumTotal += $total_balance;
                $bg = "";
                if ($i % 2 == 0) {
                    $bg = "bg-light-primary bg-hover-light-secondary";
                }
            @endphp
            <tr class="{{ $bg }}">
                <td align="center" rowspan="{{ count($items) }}">{{ $i++ }}</td>
                <td rowspan="{{ count($items) }}">{{ $partners[$partnerKey] }}</td>
                <td>
                    {{ $first['name'] }}
                    <br>
                    <span class="font-size-sm font-italic">{!! $first['info'] !!}</span>
                </td>
                <td align="center"><span class="{{ ($first['n'] != $first['period']) ? "font-weight-bold text-danger" : "" }}">{{ $first['n'] }}</span>{{ "/".$first['period'] }}</td>
                <td align="center">{{ number_format($first['rate'], 2) }}</td>
                <td align="right">{{number_format( $first['monthly'], 2) }}</td>
                <td align="right">{{number_format( $first['amount'], 2) }}</td>
                <td align="right">{{number_format( $first['paid'], 2) }}</td>
                <td align="right">{{number_format( $payment_balance, 2) }}</td>
                <td align="right">{{number_format( $summary_balance, 2) }}</td>
                <td align="right">{{number_format( $total_balance, 2) }}</td>
            </tr>
            @foreach ($items as $key => $bs)
                @if ($key != $key_first)
                @php
                    $payment_balance = 0;
                    $summary_balance = 0;
                    $total_balance = 0;
                    // $payment_balance = $bs['amount'] - $bs['paid'];
                    $payment_balance = $bs['bl'];
                    $summary_balance = $bs['summary'];
                    // $summary_balance = $bs['amount'] - $bs['paid'];
                    // $total_balance = $payment_balance + $summary_balance;
                    $total_balance = $bs['amount'] - $bs['paid'];
                    $total_p_balance += $payment_balance;

                    $sumMonthly += $bs['monthly'];
                    $sumAmount += $bs['amount'];
                    $sumPaid += $bs['paid'];
                    $sumPBalance += $payment_balance;
                    $sumSummary += $summary_balance;
                    $sumTotal += $total_balance;
                @endphp
                    <tr class="{{ $bg }}">
                        <td>
                            {{ $bs['name'] }}
                            <br>
                            <span class="font-size-sm font-italic">{!! $bs['info'] !!}</span>
                        </td>
                        <td align="center"><span class="{{ ($bs['n'] != $bs['period']) ? "font-weight-bold text-danger" : "" }}">{{ $bs['n'] }}</span>{{ "/".$bs['period'] }}</td>
                        <td align="center">{{ number_format($bs['rate'], 2) }}</td>
                        <td align="right">{{number_format( $bs['monthly'], 2) }}</td>
                        <td align="right">{{number_format( $bs['amount'], 2) }}</td>
                        <td align="right">{{number_format( $bs['paid'], 2) }}</td>
                        <td align="right">{{number_format( $payment_balance, 2) }}</td>
                        <td align="right">{{number_format( $summary_balance, 2) }}</td>
                        <td align="right">{{number_format( $total_balance, 2) }}</td>
                    </tr>
                @endif
            @endforeach
            <tr class="{{ $bg }}">
                <td colspan="8"></td>
                <td align="right"><span class="font-weight-bold">{{ number_format($total_p_balance, 2) }}</span></td>
                <td colspan="2">
                    {{-- @foreach ($items as $item)
                        @if(!empty($item['unpaid']))
                        <div class="row">
                            <div class="col-12">
                                <ul>
                                    <li><span class="font-weight-bold font-size-lg">{{ $item['name'] }}</span></li>
                                    @foreach ($item['unpaid'] as $iKey => $val)
                                    <div class="d-flex align-items-center flex-wrap mb-3">
                                        <div class="d-flex flex-column flex-grow-1 mr-2">
                                            <span class="font-weight-bold text-dark-75 mb-1">{{ date("F Y", strtotime($iKey)) }}</span>
                                        </div>
                                        <span class="label label-xl label-light label-inline my-lg-0 my-2 text-dark-50 font-weight-bolder">{{ number_format($val, 2) }}</span>
                                    </div>
                                    <hr>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        @endif
                    @endforeach --}}
                </td>
            </tr>
        @endforeach
        <tr>
            <td colspan="5" align="center"><span class="font-weight-bold font-size-lg">Total</span></td>
            <td align="right">{{ number_format($sumMonthly, 2) }}</td>
            <td align="right">{{ number_format($sumAmount, 2) }}</td>
            <td align="right">{{ number_format($sumPaid, 2) }}</td>
            <td align="right">{{ number_format($sumPBalance, 2) }}</td>
            <td align="right">{{ number_format($sumSummary, 2) }}</td>
            <td align="right">{{ number_format($sumTotal, 2) }}</td>
        </tr>
    @else
        <tr>
            <td align="center" colspan="11">No Data Available</td>
        </tr>
    @endif
    </tbody>
</table>
