<table class="table table-borderless">
    <tr class="text-center">
        <td class="text-center" colspan="5">
            <div class="row">
                <div class="col-12">
                    <b class="font-size-h3">{{ \Session::get("company_name_parent") }}</b> <br>
                    {!! str_replace(",", "<br>", \Session::get("company_address")) !!} <br>
                </div>
                <div class="col-12 mt-5">
                   <span class="text-danger font-weight-bold font-size-h3"> Trial Balance</span>
                </div>
                <div class="col-12 mt-5">
                    <span class="text-danger font-weight-bold font-size-h3">{{ $period }}</span>
                 </div>
            </div>
        </td>
    </tr>
</table>
<table class="table table-bordered display">
    <thead>
        <tr>
            <th class="text-center">Account</th>
            <th class="text-center">Debit</th>
            <th class="text-center">Credit</th>
            <th class="text-center">YTD Debit</th>
            <th class="text-center">YTD Credit</th>
        </tr>
    </thead>
    <tbody>
        @php
            $sumdebit = 0;
            $sumcredit = 0;
            $sumytddebit = 0;
            $sumytdcredit = 0;
        @endphp
        @foreach ($treasury as $item)
            <tr>
                <td>
                    {{ $item->source }}
                </td>
                <td align="right">
                    @if (isset($tre_debit[$item->id]))
                        {{ number_format(abs(array_sum($tre_debit[$item->id])), 2) }}
                    @else
                        {{ number_format(0, 2) }}
                    @endif
                </td>
                <td align="right">
                    @if (isset($tre_credit[$item->id]))
                        {{ number_format(abs(array_sum($tre_credit[$item->id])), 2) }}
                    @else
                        {{ number_format(0, 2) }}
                    @endif
                </td>
                <td align="right">
                    @if (isset($tre_debitYTD[$item->id]))
                        {{ number_format(abs(array_sum($tre_debitYTD[$item->id])), 2) }}
                    @else
                        {{ number_format(0, 2) }}
                    @endif
                </td>
                <td align="right">
                    @if (isset($tre_creditYTD[$item->id]))
                        {{ number_format(abs(array_sum($tre_creditYTD[$item->id])), 2) }}
                    @else
                        {{ number_format(0, 2) }}
                    @endif
                </td>
            </tr>
            @php
                $sumdebit += (isset($tre_debit[$item->id])) ? array_sum($tre_debit[$item->id]) : 0;
                $sumcredit += (isset($tre_credit[$item->id])) ? array_sum($tre_credit[$item->id]) : 0;
                $sumytddebit += (isset($tre_debitYTD[$item->id])) ? array_sum($tre_debitYTD[$item->id]) : 0;
                $sumytdcredit += (isset($tre_creditYTD[$item->id])) ? array_sum($tre_creditYTD[$item->id]) : 0;
            @endphp
        @endforeach
        @foreach ($coa as $item)
            @if (isset($coa_debitYTD[$item->code]) || isset($coa_creditYTD[$item->code]))
                <tr>
                    <td>
                        {{ "[$item->code]" }}{{ $item->name }}
                    </td>
                    <td align="right">
                        {{ (isset($coa_debit[$item->code])) ? number_format(abs(array_sum($coa_debit[$item->code])), 2) : number_format(0, 2) }}
                    </td>
                    <td align="right">
                        {{ (isset($coa_credit[$item->code])) ? number_format(abs(array_sum($coa_credit[$item->code])), 2) : number_format(0, 2) }}
                    </td>
                    <td align="right">
                        {{ (isset($coa_debitYTD[$item->code])) ? number_format(abs(array_sum($coa_debitYTD[$item->code])), 2) : number_format(0, 2) }}
                    </td>
                    <td align="right">
                        {{ (isset($coa_creditYTD[$item->code])) ? number_format(abs(array_sum($coa_creditYTD[$item->code])), 2) : number_format(0, 2) }}
                    </td>
                </tr>
            @endif
            @php
                $sumdebit += (isset($coa_debit[$item->code])) ? array_sum($coa_debit[$item->code]) : 0;
                $sumcredit += (isset($coa_credit[$item->code])) ? array_sum($coa_credit[$item->code]) : 0;
                $sumytddebit += (isset($coa_debitYTD[$item->code])) ? array_sum($coa_debitYTD[$item->code]) : 0;
                $sumytdcredit += (isset($coa_creditYTD[$item->code])) ? array_sum($coa_creditYTD[$item->code]) : 0;
            @endphp
        @endforeach
        <tfoot>
            <th class="text-center">Total</th>
            <th class="text-right">{{ number_format($sumdebit, 2) }}</th>
            <th class="text-right">{{ number_format($sumcredit, 2) }}</th>
            <th class="text-right">{{ number_format($sumytddebit, 2) }}</th>
            <th class="text-right">{{ number_format($sumytdcredit, 2) }}</th>
        </tfoot>
    </tbody>
</table>
