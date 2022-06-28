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
<table class="table table-hover table-bordered" {{ ($border == 1) ? "border=1" : "" }}>
    <thead>
    <tr>
        <th class="text-center" colspan="14">
            <h3 class="font-weight-bold">BUSINESS INVESTOR</h3>
            <h4 class="font-weight-bold">TAHUN : {{ date("Y", strtotime($period)) }}</h4>
            <h4 class="font-weight-bold">PERIODE : {{ strtoupper(date("F", strtotime($period))) }}</h4>
        </th>
    </tr>
    <tr>
        <th class="text-center" rowspan="2">No</th>
        <th class="text-center" rowspan="2">Business</th>
        <th class="text-center" rowspan="2">Item</th>
        <th class="text-center" rowspan="2">Period (Month)</th>
        <th class="text-center" colspan="2">Interest</th>
        <th class="text-center" colspan="5">Installment</th>
        <th class="text-center" colspan="3">Adm</th>
    </tr>
    <tr>
        <th class="text-center">%</th>
        <th class="text-center">ADM</th>
        <th class="text-center">MONTH</th>
        <th class="text-center">TOTAL</th>
        <th class="text-center">PAID</th>
        <th class="text-center">BALANCE</th>
        <th class="text-center">TOTAL BALANCE</th>
        <th class="text-center">PAID</th>
        <th class="text-center">BALANCE</th>
        <th class="text-center">TOTAL BALANCE</th>
    </tr>
    </thead>
    <tbody>
    <?php
        $total = 0;
        $total_month = 0;
        $total_am = 0;
        $total_paid = 0;
        $total_bl = 0;
        $total_adm_balance = 0;
        $total_adm_paid = 0;
     ?>
        @if(!empty($col))
            @foreach($col as $key => $value)
                @php
                    $iInv = 1;
                @endphp
                @foreach ($value as $inv => $item)
                @php
                    $total_balance = 0;
                    $total_adm = 0;
                    foreach($item['data'] as $bl){
                        $total_balance += $bl['c_total_am'] - $bl['cpaid'];
                        $total_adm += $bl['adm_balance'] - $bl['adm_paid'];
                    }
                @endphp
                    <tr>
                        <td align="center" rowspan="{{ count($item['data']) }}">{{ $iInv++ }}</td>
                        <td>
                            @php
                                $first_key = array_key_first($item['data']);
                                $first = $item['data'][$first_key];
                                echo $bs_data['name'][$first_key];
                            @endphp
                        </td>
                        <td align="center" rowspan="{{ count($item['data']) }}">{{ strtoupper(str_replace("_", " ", $inv)) }}</td>
                        <td align="center">
                            {{ $bs_data['period'][$first_key] }}
                        </td>
                        <td align="center">
                            {{ number_format($first['interest'], 2) }} %
                        </td>
                        <td align="center">
                            {{ number_format($first['adm_percentage'], 2) }} %
                        </td>
                        <td align="right">
                            {{ number_format($first['amount'], 0) }}
                        </td>
                        <td align="right">
                            {{ number_format($first['c_total_am'], 0) }}
                        </td>
                        <td align="right">
                            {{ number_format($first['cpaid'], 0) }}
                        </td>
                        @php
                            $balance = $first['c_total_am'] - $first['cpaid'];
                        @endphp
                        <td align="right">
                            {{ number_format($balance, 0) }}
                        </td>
                        <td rowspan="{{ count($item['data']) }}" align="right">
                            {{ number_format($total_balance, 0) }}
                        </td>
                        <td align="right">
                            {{ number_format($first['adm_paid'], 0) }}
                        </td>
                        <td align="right">
                            {{ number_format($first['adm_balance'], 0) }}
                        </td>
                        <td rowspan="{{ count($item['data']) }}" align="right">
                            {{ number_format($total_adm, 0) }}
                        </td>
                        @php
                            $total_month += $first['amount'];
                            $total_am += $first['c_total_am'];
                            $total_paid += $first['cpaid'];
                            $total_bl += $balance;
                            $total_adm_paid += $first['adm_paid'];
                            $total_adm_balance += $total_adm;
                        @endphp
                    </tr>
                    @if(count($item['data']) > 0)
                        @php
                            unset($item['data'][$first_key]);
                        @endphp
                        @foreach ($item['data'] as $inv_key => $inv_)
                            <tr>
                                <td>{{ $bs_data['name'][$inv_key] }}</td>
                                <td align="center">
                                    {{ $bs_data['period'][$inv_key] }}
                                </td>
                                <td align="center">{{ number_format($inv_['interest'], 2) }} %</td>
                                <td align="center">{{ number_format($inv_['adm_percentage'], 2) }} %</td>
                                <td align="right">
                                    {{ number_format($inv_['amount'], 0) }}
                                </td>
                                <td align="right">
                                    {{ number_format($inv_['c_total_am'], 0) }}
                                </td>
                                <td align="right">
                                    {{ number_format($inv_['cpaid'], 0) }}
                                </td>
                                @php
                                    $balance = $inv_['c_total_am'] - $inv_['cpaid'];
                                @endphp
                                <td align="right">
                                    {{ number_format($balance, 0) }}
                                </td>
                                <td align="right">
                                    {{ number_format($inv_['adm_paid'], 0) }}
                                </td>
                                <td align="right">
                                    {{ number_format($inv_['adm_balance'], 0) }}
                                </td>
                                @php
                                    $total_month += $inv_['amount'];
                                    $total_am += $inv_['c_total_am'];
                                    $total_paid += $inv_['cpaid'];
                                    $total_bl += $balance;
                                    $total_adm_paid += $inv_['adm_paid'];
                                @endphp
                            </tr>
                        @endforeach
                    @endif
                @endforeach
            @endforeach
        @else
            <tr>
                <td align="center" colspan="3"><strong>No data available</strong></td>
            </tr>
        @endif
    </tbody>
    <tr>
        <td colspan="6" class="text-center"><strong>Totals</strong></td>
        <td align="right"><b><?= number_format($total_month, 0) ?></b></td>
        <td align="right"><b><?= number_format($total_am, 0) ?></b></td>
        <td align="right"><b><?= number_format($total_paid, 0) ?></b></td>
        <td align="right"><b><?= number_format($total_bl, 0) ?></b></td>
        <td align="right"><b><?= number_format($total_bl, 0) ?></b></td>
        <td align="right"><b><?= number_format($total, 0) ?></b></td>
        <td align="right"><b><?= number_format($total_adm_balance, 0) ?></b></td>
        <td align="right"><b><?= number_format($total_adm_balance, 0) ?></b></td>
    </tr>
</table>
