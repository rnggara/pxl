<table class="table table-borderless">
    <tr class="text-center">
        <td class="text-center" colspan="6">
            <div class="row">
                <div class="col-12">
                    <b class="font-size-h3">{{ \Session::get("company_name_parent") }}</b> <br>
                    {!! str_replace(",", "<br>", \Session::get("company_address")) !!} <br>
                </div>
                <div class="col-12 mt-5">
                   <span class="text-danger font-weight-bold font-size-h3"> Sales & Receivables Journal</span>
                </div>
                <div class="col-12 mt-5">
                    <span class="text-danger font-weight-bold font-size-h3">{{ date("d/m/Y", strtotime($from_dt)) }} To {{ date("d/m/Y", strtotime($to_dt)) }}</span>
                 </div>
            </div>
        </td>
    </tr>
</table>
<table class="table table-bordered display" style="width : 100%">
    <thead>
        <tr>
            <th class="text-center">ID#</th>
            <th class="text-center">Account</th>
            <th class="text-center">Account Name</th>
            <th class="text-center">Credit</th>
            <th class="text-center">Debit</th>
            <th class="text-center">Job</th>
        </tr>
    </thead>
    <tbody>
        @php
            $sumdebet = 0;
            $sumcredit = 0;
        @endphp
        @foreach ($_row as $prj => $item)
            @foreach ($item['data'] as $data)
                <tr>
                    <td colspan="6">
                        <span class="font-weight-bold">
                            {{ date("d/m/Y", strtotime($data['date'])) }}, {{ $item['client'] }}, {{ $data['activity'] }}
                        </span>
                    </td>
                    <td style="display: none"></td>
                    <td style="display: none"></td>
                    <td style="display: none"></td>
                    <td style="display: none"></td>
                    <td style="display: none"></td>
                </tr>
                @foreach ($data['detail'] as $inv)
                    <tr>
                        <td>
                            {{ $inv['num'] }}
                        </td>
                        <td align="right">
                            {{ $inv['code'] }}
                        </td>
                        <td>
                            {{ $inv['code_name'] }}
                        </td>
                        <td align="right">
                            <span class="number">{{ $inv['credit'] ?? "" }}</span>
                        </td>
                        <td align="right">
                            <span class="number">{{ $inv['debit'] ?? "" }}</span>
                        </td>
                        <td align="center">
                            {{ sprintf("%03d", $prj)  }}
                        </td>
                    </tr>
                    @php
                        $sumdebet += $inv['debit'];
                        $sumcredit += $inv['credit'];
                    @endphp
                @endforeach
            @endforeach
        @endforeach
        <tfoot>
            <tr>
                <th colspan="3">Total</th>
                <th class="text-right">
                    {{ number_format($sumcredit, 2) }}
                </th>
                <th class="text-right">
                    {{ number_format($sumdebet, 2) }}
                </th>
                <th></th>
            </tr>
        </tfoot>
    </tbody>
</table>
