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
        <th class="text-center" colspan="15">
            <h3 class="font-weight-bold">BUSINESS INVESTOR</h3>
            <h4 class="font-weight-bold">TAHUN : {{ date("Y", strtotime($period)) }}</h4>
            <h4 class="font-weight-bold">PERIODE : {{ strtoupper(date("F", strtotime($period))) }}</h4>
        </th>
    </tr>
    <tr>
        <th class="" colspan="15">
            <div class="row justify-content-end">
                <div class="col-1">
                    Legend : <br>
                    <span>
                        <i class="fa fa-square text-danger"></i> for installment
                    </span>
                    <br>
                    <span>
                        <i class="fa fa-square text-primary"></i> for profit
                    </span>
                </div>
            </div>
        </th>
    </tr>
    <tr>
        <th class="text-center" rowspan="2">No</th>
        <th class="text-center" rowspan="2">Investors</th>
        <th class="text-center" rowspan="2">Business</th>
        <th class="text-center" rowspan="2">Period (Month)</th>
        <th class="text-center" colspan="2">Interest</th>
        <th class="text-center" colspan="6">Installment</th>
        <th class="text-center" colspan="3">Adm</th>
    </tr>
    <tr>
        <th class="text-center">%</th>
        <th class="text-center">ADM</th>
        <th class="text-center">MONTH</th>
        <th class="text-center">TOTAL</th>
        <th class="text-center">PAID</th>
        <th class="text-center">PAYMENT BALANCE</th>
        <th class="text-center">PRINCIPAL BALANCE</th>
        <th class="text-center">TOTAL BALANCE</th>
        <th class="text-center">MONTH</th>
        <th class="text-center">PAYMENT BALANCE</th>
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
        $total_sum_balance = 0;
        $total_sum_adm_balance = 0;
        $sum_total_balance = 0;
        $i = 1;
     ?>
        @if(!empty($row))
            @foreach ($row as $inv => $item)
            @php
                $bg = "bg-light-primary bg-hover-secondary";
                $mod = $i % 2;
                if($mod == 0){
                    $bg = "";
                }
            @endphp
                <tr class="{{ $bg }}">
                    @php
                        $total_princ_balance = 0;
                        $total_balance2 = 0;
                        $total_balance = 0;
                        $total_adm = 0;
                        $total_sum_balance2 = 0;
                        foreach ($item as $key => $value) {
                            foreach ($value['details'] as $iValue) {
                                $total_balance += $iValue['amount'] - $iValue['paid'];
                                $total_adm += $iValue['adm_amount'];
                                $total_month += $iValue['monthly'];
                                $total_am += $iValue['amount'];
                                $total_paid += $iValue['paid'];
                                $total_bl += $iValue['amount'] - $iValue['paid'];
                                $total_adm_balance += $iValue['adm_amount'];
                                $total_adm_paid += $iValue['adm'];
                                $total_sum_balance += $iValue['adm_balance'];
                            }
                        }

                        $key_first = array_key_first($item);
                        $first = $item[$key_first];
                        $period = 0;
                        $bunga_adm = 0;
                        $monthly = 0;
                        $total = 0;
                        $paid = 0;
                        $balance = 0;
                        $adm_paid = 0;
                        $adm_balance = 0;
                        $sum_balance = 0;
                        $rate = 0;
                        $nUnpaid = 0;
                        $cicilan = 0;
                        $profit = 0;
                        $_adm_balance = 0;

                        foreach ($first['details'] as $key => $value) {
                            if($value['period'] > $period){
                                $period = $value['period'];
                            }
                            if($value['rate'] > $bunga_adm){
                                $bunga_adm = $value['rate'];
                                $rate = $value['rate'];
                            }

                            $nUnpaid += count($value['unpaid']);

                            $monthly += $value['monthly'];
                            $total += $value['amount'];
                            $paid += $value['paid'];
                            $adm_paid += $value['adm'];
                            $adm_balance += $value['adm_amount'];
                            $sum_balance = $value['sum_balance'];
                            $total_sum_balance2 += $value['sum_balance'];
                            $cicilan = $value['cicilan'];
                            $profit = $value['profit'];
                            $_adm_balance = ($value['adm_balance'] < 0) ? 0 : $value['adm_balance'];
                            $total_sum_adm_balance+= $_adm_balance;
                        }

                        $total_princ_balance += $sum_balance;

                        $sum_total_balance += $total_balance;

                        $install_balance = $total - $paid;

                        $total_balance2 += $sum_balance + $install_balance;

                        $bunga = (isset($bs_data[$key_first])) ? number_format($bs_data[$key_first]->bunga, 2) : 0;
                        $b_adm = $bunga - $bunga_adm;

                    @endphp
                    <td align="center" rowspan="{{ count($item) + 1 }}">{{ $i++ }}</td>
                    <td align="center" rowspan="{{ count($item) + 1 }}">
                        <b>{{ str_replace("_", " ", $inv) }}</b>
                        <span class="font-italic">{!! $first['account_info'] !!}</span>
                    </td>
                    <td align="center">
                        {{ $first['name'] }}
                    </td>
                    <td align="center">
                        {{ $nUnpaid }}/{{ $period }}
                    </td>
                    <td align="center">
                        {{ number_format($rate, 2) }}
                    </td>
                    <td align="center">
                        {{ number_format($b_adm, 2) }}
                    </td>
                    <td align="right" nowrap="nowrap">
                        {{ number_format($monthly, 2) }}
                        <br>
                        <span class="text-danger">{{ number_format($cicilan, 2) }}</span>
                        <br>
                        <span class="text-primary">{{ number_format($profit, 2) }}</span>
                    </td>
                    <td align="right">
                        {{ number_format($total, 2) }}
                    </td>
                    <td align="right">
                        {{ number_format($paid, 2) }}
                    </td>
                    <td align="right">
                        {{ number_format($install_balance, 2) }}
                    </td>
                    <td align="right">{{ number_format($sum_balance, 2) }}</td>
                    <td align="right">{{ number_format($install_balance + $sum_balance, 2) }}</td>
                    <td align="right">
                        {{ number_format($adm_paid, 2) }}
                    </td>
                    <td align="right">
                        {{ number_format(round($adm_balance), 2) }}
                    </td>
                    <td align="right">{{ number_format($_adm_balance, 2) }}</td>
                </tr>
                @foreach ($item as $kItem => $vItem)
                    @if ($kItem != $key_first)
                        @php
                            $period = 0;
                            $bunga_adm = 0;
                            $monthly = 0;
                            $total = 0;
                            $paid = 0;
                            $balance = 0;
                            $adm_paid = 0;
                            $adm_balance = 0;
                            $total_adm = 0;
                            $sum_balance = 0;
                            $rate = 0;
                            $nUnpaid = 0;
                            $cicilan = 0;
                            $profit = 0;
                            $_adm_balance = 0;
                            foreach($vItem['details'] as $key => $value){
                                if($value['period'] > $period){
                                    $period = $value['period'];
                                }

                                $nUnpaid += count($value['unpaid']);

                                if($value['rate'] > $bunga_adm){
                                    $bunga_adm = $value['rate'];
                                    $rate = $value['rate'];
                                }

                                $monthly += $value['monthly'];
                                $total += $value['amount'];
                                $paid += $value['paid'];
                                $adm_paid += $value['adm'];
                                $adm_balance += $value['adm_amount'];
                                $sum_balance = $value['sum_balance'];
                                $cicilan = $value['cicilan'];
                                $profit = $value['profit'];
                                $_adm_balance = ($value['adm_balance']  < 0) ? 0 : $value['adm_balance'];
                                $total_sum_adm_balance+= $_adm_balance;
                            }

                            $total_princ_balance += $sum_balance;

                            $install_balance = $total - $paid;

                            $total_balance2 += $sum_balance + $install_balance;

                            $bunga = (isset($bs_data[$kItem])) ? number_format($bs_data[$kItem]->bunga, 2) : 0;
                            $b_adm = $bunga - $bunga_adm;
                        @endphp
                        <tr class="{{ $bg }}">
                            <td align="center">
                                {{ $vItem['name'] }}
                            </td>
                            <td align="center">
                                {{ $nUnpaid }}/{{ $period }}
                            </td>
                            <td align="center">
                                {{ number_format($rate, 2) }}
                            </td>
                            <td align="center">
                                {{ number_format($b_adm, 2) }}
                            </td>
                            <td align="right">
                                {{ number_format($monthly, 2) }}
                                <br>
                                <span class="text-danger">{{ number_format($cicilan, 2) }}</span>
                                <br>
                                <span class="text-primary">{{ number_format($profit, 2) }}</span>
                            </td>
                            <td align="right">{{ number_format($total, 2) }}</td>
                            <td align="right">{{ number_format($paid, 2) }}</td>
                            <td align="right">{{ number_format($install_balance, 2) }}</td>
                            <td align="right">{{ number_format($sum_balance, 2) }}</td>
                            <td align="right">{{ number_format($install_balance + $sum_balance, 2) }}</td>
                            <td align="right">{{ number_format($adm_paid, 2) }}</td>
                            <td align="right">{{ number_format($adm_balance, 2) }}</td>
                            <td align="right">{{ number_format($_adm_balance, 2) }}</td>
                        </tr>
                    @endif
                @endforeach
                <tr class="{{ $bg }}">
                    <td colspan="5"></td>
                    <td align="right">
                        <b>{{ number_format($total_am, 2) }}</b>
                    </td>
                    <td align="right">
                        <b>{{ number_format($total_paid, 2) }}</b>
                    </td>
                    <td align="right">
                        <b>{{ number_format($total_balance, 2) }}</b>
                    </td>
                    <td align="right">
                        <b>{{ number_format($total_princ_balance, 2) }}</b>
                    </td>
                    <td align="right">
                        <b>{{ number_format($total_balance2, 2) }}</b>
                    </td>

                    <td colspan="3">
                        {{-- @foreach ($item as $data)
                            @php
                                $un_paid = [];
                            @endphp
                            @foreach ($data['details'] as $detail)
                                @if(!empty($detail['unpaid']))
                                    @foreach ($detail['unpaid'] as $date_unpaid => $unpaid)
                                        @php
                                            $un_paid[$date_unpaid][] = $unpaid;
                                        @endphp
                                    @endforeach
                                @endif
                            @endforeach
                            @if (count($un_paid) > 0)
                            @php
                                $sum_unpaid = 0;
                            @endphp
                                <div class="row">
                                    <div class="col-12">
                                        <ul>
                                            <li> <span class="font-weight-bold font-size-lg">{{ $data['name'] }}</span>
                                                @foreach ($un_paid as $iKey => $val)
                                                <div class="d-flex align-items-center flex-wrap mb-3">
                                                    <div class="d-flex flex-column flex-grow-1 mr-2">
                                                        <span class="font-weight-bold text-dark-75 mb-1">{{ date("F Y", strtotime($iKey)) }}</span>
                                                    </div>
                                                    <span class="label label-xl label-light label-inline my-lg-0 my-2 text-dark-50 font-weight-bolder">{{ number_format(array_sum($val), 2) }}</span>
                                                    @php
                                                        $sum_unpaid += array_sum($val);
                                                    @endphp
                                                </div>
                                                <hr>
                                                @endforeach
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            @endif
                        @endforeach --}}
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="6" class="text-center"><strong>Totals</strong></td>
                <td align="right"><b><?= number_format($total_month, 0) ?></b></td>
                <td align="right"><b><?= number_format($total_am, 0) ?></b></td>
                <td align="right"><b><?= number_format($total_paid, 0) ?></b></td>
                <td align="right"><b><?= number_format($total_bl, 0) ?></b></td>
                <td align="right"><b><?= number_format($total_sum_balance, 0) ?></b></td>
                <td align="right"><b><?= number_format($total_bl + $total_sum_balance, 0) ?></b></td>
                <td align="right"><b><?= number_format($total_adm_paid, 0) ?></b></td>
                <td align="right"><b><?= number_format($total_adm_balance, 0) ?></b></td>
                <td align="right"><b><?= number_format($total_sum_adm_balance, 0) ?></b></td>
            </tr>
        @else
            <tr>
                <td align="center" colspan="14"><strong>No data available</strong></td>
            </tr>
        @endif
    </tbody>
</table>
