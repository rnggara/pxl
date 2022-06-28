@extends('layouts.template')

@section('css')
<style>
    @media print {
        body * {
            visibility: hidden;
            background-color: #fff;
        }

        #print-section,
        #print-section * {
            visibility: visible;
        }

        #print-section {
            position: absolute;
            left: 0;
            top: 0;
            height: auto;
        }
    }

</style>
@endsection
@section('content')
<div class="card card-custom gutter-b">
    <div class="card-header">
        <div class="card-title">
            <h3>Business - Investor Details</h3>
        </div>
        <div class="card-toolbar">
            <a href="{{route('business.detail', $business->id)}}" class="btn btn-xs btn-icon btn-success"><i
                    class="fa fa-arrow-left"></i></a>
        </div>
    </div>
    <div class="card-body">
        <div class="card card-custom gutter-b bg-secondary">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <table class="table table-borderless">
                            <tr>
                                <td>Business Project</td>
                                <td>:</td>
                                <td><b>{{$business->bank}}</b></td>
                            </tr>
                            <tr>
                                <td>Partner Name</td>
                                <td>:</td>
                                <td><b>{{$partner[$business->partner]}}</b></td>
                            </tr>
                            <tr>
                                <td>Currency</td>
                                <td>:</td>
                                <td>{{$business->currency}}</td>
                            </tr>
                            <tr>
                                <td>Invested Amount</td>
                                <td>:</td>
                                <td><b>{{$business->currency.". ".number_format($business->value, 2)}}</b></td>
                            </tr>
                            <tr>
                                <td>Invested Date</td>
                                <td>:</td>
                                <td>{{date('d F Y', strtotime($business->moneydrop))}}</td>
                            </tr>
                            <tr>
                                <td>Interest Percentage</td>
                                <td>:</td>
                                <td>{{$business->bunga}} % per month</td>
                            </tr>
                            <tr>
                                <td>Business Duration</td>
                                <td>:</td>
                                <td>{{$business->period}} month(s)</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-4">
                        <table class="table table-borderless">
                            <tr>
                                <td nowrap="nowrap">Payment Start Date</td>
                                <td>:</td>
                                <td>{{date('d F Y', strtotime($business->start))}}</td>
                            </tr>
                            <tr>
                                <td>Proportional</td>
                                <td>:</td>
                                <td>{{$business->type}} - {{($business->type == "LUM") ? "LUMPSUM" : "PROPORTIONAL"}}
                                </td>
                            </tr>
                            <tr>
                                <td>Penalty</td>
                                <td>:</td>
                                <td><b>{{$business->currency.". ".number_format($business->own_amount, 2)}}</b></td>
                            </tr>
                            <tr>
                                <td nowrap="nowrap">Penalty Remarks</td>
                                <td>:</td>
                                <td>
                                    {!! $business->own_remarks !!}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <hr>
    </div>
</div>
<div class="card card-custom gutter-b">
    <div class="card-body">
        <div class="row mt-10" id="summary">
            <div class="col-md-12">
                <h3>Add new investor</h3>
                <hr>
            </div>
            <div class="col-md-6 mx-auto">
                <form action="{{route('business.investors.list.add')}}" method="post">
                    @csrf
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Investor Name</label>
                        <div class="col-md-9">
                            <select name="investor" class="form-control select2" required>
                                <option value="">Select Investor</option>
                                @foreach ($inv_master as $key => $value)
                                <option value="{{ $key }}">{{ ucwords($value) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Profit Rate</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control number" name="profit_rate" placeholder="%" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Amount</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control number" name="amount" placeholder="Amount" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-3 col-form-label">Start From</label>
                        <div class="col-md-9">
                            <input type="date" required class="form-control" name="start_from"
                                value="{{ $business->start }}" min="{{ $business->start }}"
                                max="{{ date('Y-m-d', strtotime("+".$business->period." months ".$business->start)) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label"></label>
                        <div class="col-md-9">
                            <input type="hidden" name="id_business" value="{{$business->id}}">
                            <button type="submit" class="btn btn-xs btn-success"><i class="fa fa-plus"></i> Add</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@php
$balanceOwn = $business->value;
$sumCicilOwn = 0;
$sumProfitOwn = 0;
$sumTotalAmount = 0;

$balanceInv = 0;

$cicilInv = [];
$blInv = [];
@endphp


@foreach ($investor_item as $keyInvestor => $item)
    @php
        $sumCicilOwn = 0;
        $sumProfitOwn = 0;
        $sumTotalAmount = 0;
        $invBalance = 0;
        foreach ($item as $key => $value) {
            $invBalance += ((!empty($value->actual_amount)) ? $value->actual_amount : $value->amount) + $value->closing_amount;
            if(!empty($value->actual_amount)){
                $balanceInv += $value->actual_amount;
            } else {
                $balanceInv += $value->amount;
            }
        }
        $balanceOwn -= $balanceInv;
        if(!empty($value->closing_amount)){
            $balanceOwn += $value->closing_amount;
        }
        $totalVa = 0;
        $totalVb = 0;
        $totalAdm = 0;
        $totalProf = 0;
    @endphp
    <div class="card card-custom gutter-b">
        <div class="card-body">
            <h3 class="card-title">{{ $investors_master[$keyInvestor] }} <button onclick="button_delete({{ $keyInvestor }})" class="btn btn-outline-danger"><i class="fa fa-trash"></i>Delete</button></h3>
            <div class="row">
                <div class="col-12 mb-10">
                    <label for=""><b>Invested Amount : </b>
                        {{ $business->currency." ". number_format($invBalance, 2) }}
                        <button type="button" class="btn btn-xs btn-icon btn-outline-info collapsed" aria-expanded="false" data-toggle="collapse" data-target="#collapseHis{{ $keyInvestor }}"><i class="fa fa-history"></i></button>
                    </label>
                </div>
                <div class="col-12">
                    <div class="accordion accordion-solid accordion-toggle-plus mb-10" id="accordionHis{{ $keyInvestor }}">
                        <div id="collapseHis{{ $keyInvestor }}" class="collapse" data-parent="#accordionHis{{ $keyInvestor }}">
                            <div class="col-12">
                                <table class="table table-hover">
                                    <tr>
                                        <th class="text-center">No.</th>
                                        <th class="text-center">Amount</th>
                                        <th class="text-center">Exchange Rate</th>
                                        <th class="text-center">Sub Total (IDR)</th>
                                        <th class="text-center">
                                            <button type="button" data-toggle="modal" data-target="#modalAddInvestment{{ $keyInvestor }}" class="btn btn-icon btn-xs btn-success"><i class="fa fa-plus"></i></button>
                                        </th>
                                    </tr>
                                    @php
                                        $detail = [];
                                        if (!empty($item[0]->details)) {
                                            $detail = json_decode($item[0]->details, true);
                                        }
                                        $iDet = 1;
                                        $sumIdr = 0;
                                    @endphp
                                    @if (isset($detail['details']) && count($detail['details']) > 0)
                                        @foreach ($detail['details'] as $i => $itemDet)
                                        <tr>
                                            <td align="center">{{ $iDet++ }}</td>
                                            <td align="left">{{ $itemDet['currency']." ".number_format($itemDet['amount'], 2) }}</td>
                                            <td align="center">{{ number_format($itemDet['exchange_rate'], 2) }}</td>
                                            <td align="right">IDR {{ number_format($itemDet['idr'], 2) }}</td>
                                            <td align="center">
                                                <a href="{{ route('business.investors.list.deleteInvesment', ['id' => $item[0]->id, 'index' => $i]) }}" class="btn btn-xs btn-icon btn-danger"><i class="fa fa-trash"></i></button>
                                            </td>
                                            @php
                                                $sumIdr += $itemDet['idr'];
                                            @endphp
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="3">
                                                Invested Amount : {{ number_format($invBalance, 2) }}
                                            </td>
                                            <td align="right">IDR {{ number_format($sumIdr, 2) }}</td>
                                            <td>
                                                <div>Balance : IDR {{ number_format($invBalance - $sumIdr, 2) }}</div>
                                                <div>
                                                    @php
                                                        $unusedPayment = "";
                                                        if (isset($detail['unusedPayment'])) {
                                                            $unusedPayment = $detail['unusedPayment'];
                                                        }
                                                    @endphp
                                                    <form action="{{ route('business.investors.list.save.text') }}" method="post">
                                                        @csrf
                                                        <textarea name="content" class="form-control" id="" cols="30" rows="10">{{ $unusedPayment }}</textarea>
                                                        <input type="hidden" name="id" value="{{ $item[0]->id }}">
                                                        <button type="submit" class="btn btn-success">Save</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @else
                                    <tr>
                                        <td colspan="5" align="center">
                                            No Data Available
                                        </td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="modalAddInvestment{{ $keyInvestor }}" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content" id="contentModalAddInvestment">
                                <form action="{{ route('business.investors.list.addInvesment') }}" method="post">
                                    @csrf
                                    <div class="modal-header">
                                        <h1 class="modal-title">Add Investment {{ $investors_master[$keyInvestor] }}</h1>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <label for="" class="col-form-label col-3">Currency</label>
                                            <div class="col-9">
                                                <select name="currency" class="form-control select2" required>
                                                    @foreach(json_decode($list_currency) as $keyCurrency => $valueCurrency)
                                                        <option value="{{$keyCurrency}}" {{($keyCurrency == "IDR") ? "selected" : ""}}>{{strtoupper($keyCurrency."-".$valueCurrency)}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="" class="col-form-label col-3">Amount</label>
                                            <div class="col-9">
                                                <input type="text" class="form-control number" name="amount" placeholder="Insert invesment amount">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="" class="col-form-label col-3">Exchange Rate to IDR</label>
                                            <div class="col-9">
                                                <input type="text" class="form-control number" name="rate" placeholder="Insert exchange rate">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="hidden" name="id" value="{{ $item[0]->id }}">
                                        <input type="hidden" name="type" value="investor">
                                        <button type="button" class="btn btn-light-primary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Add</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12" id="history{{ $keyInvestor }}"></div>
                @foreach ($item as $key => $value)
                    @if (isset($investor_details[$value->id]))
                    @php
                        $totalVa += $value->adm_a;
                        $totalVb += $value->adm_b;
                        $totalAdm += $value->adm;
                    @endphp
                    <div class="col-12">
                        <div class="row">
                            <div class="col-12">
                                <div class="accordion accordion-solid accordion-toggle-plus mb-10" id="accordion{{ $value->id }}">
                                    <div class="card">
                                        <div class="card-header col-6" id="headingOne6">
                                            <div class="card-title collapsed" data-toggle="collapse" data-target="#collapse{{ $value->id }}">
                                                <span class="svg-icon svg-icon-primary">
                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                                            <path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" fill="#000000" fill-rule="nonzero"></path>
                                                            <path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) "></path>
                                                        </g>
                                                    </svg>
                                                </span>
                                                <div class="card-label pl-4">
                                                    {{ $value->investment_name }}
                                                </div>
                                            </div>
                                        </div>
                                        <div id="collapse{{ $value->id }}" class="collapse" data-parent="#accordion{{ $value->id }}">
                                            <div class="card-body bg-secondary">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <button type="button" onclick="adiv('print-div-{{ $value->id }}')" class="btn btn-icon btn-sm btn-outline-primary"><i class="fa fa-print"></i></button>
                                                        {{-- <a href="{{ route('business.investor.edit', $value->id) }}" class="btn btn-sm btn-icon btn-outline-info">
                                                            <i class="fa fa-edit"></i>
                                                        </a> --}}
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="row">
                                                        <div class="col-4">
                                                            <table class="table table-borderless">
                                                                <tr>
                                                                    <td>Investor</td>
                                                                    <td>: {{ $investors_master[$keyInvestor] }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Invested Amount</td>
                                                                    <td>: {{ $business->currency }} {{ number_format((!empty($value->amount_before)) ? $value->amount_before : $value->amount, 2) }}</td>
                                                                </tr>
                                                                @if (!empty($value->amount_before))
                                                                    <tr>
                                                                        <td>Balance Amount</td>
                                                                        <td>: {{ $business->currency }} {{ number_format($value->amount, 2) }}</td>
                                                                    </tr>
                                                                @endif
                                                                <tr>
                                                                    <td>Project Name</td>
                                                                    <td>: {{ $business->bank }}</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <table class="table table-bordered table-dark table-hover table-responsive-xl">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="text-center">#</th>
                                                                        <th>Payment Date</th>
                                                                        <th>Profit rate (%)</th>
                                                                        <th>Balance</th>
                                                                        <th>Installment</th>
                                                                        <th>Profit</th>
                                                                        <th>Total Amount</th>
                                                                        <th>Status Payment</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @php
                                                                        $total_amount_inv = 0;
                                                                        $sumCicilInv = 0;
                                                                        $sumProfitInv = 0;
                                                                        $sumTotalInv = 0;
                                                                        $balanceInv = $value->amount;
                                                                        $sumPaid = 0;
                                                                        $prevDateKey = "";
                                                                        $bInv = [];
                                                                    @endphp
                                                                    @foreach ($investor_details[$value->id] as $i => $inv_detail)
                                                                    @if ($inv_detail->closed == 0)
                                                                        <form action="{{ route('business.investors.list.editInvestment') }}" method="post" id="form-edit-{{ $inv_detail->id }}">
                                                                        @csrf
                                                                        <tr>
                                                                            <td align="center">
                                                                                {{ $i+1 }}
                                                                                <div>
                                                                                    @if (empty($inv_detail->paid_at))
                                                                                    <button type="button" onclick="btn_edit_click(this)" class="btn btn-sm btn-light-primary btn-icon" data-action="edit"><i class="fa fa-edit"></i></button>
                                                                                    @endif
                                                                                </div>
                                                                            </td>
                                                                            <td align="center">
                                                                                {{ date("d F Y", strtotime($inv_detail->plan_date)) }}
                                                                            </td>
                                                                            <td align="center">
                                                                                <span class="tr-label">
                                                                                    {{ number_format($inv_detail->bunga_rate, 2) }}
                                                                                </span>
                                                                                <input type="text" class="form-control number tr-hide" id="rate_{{ $inv_detail->id }}" onkeyup="rate_profit('profit_{{ $inv_detail->id }}', {{ $value->amount }}, this)" name="profit_rate" value="{{ $inv_detail->bunga_rate }}">
                                                                            </td>
                                                                            <td align="right">
                                                                                {{ number_format($balanceInv, 2) }}
                                                                            </td>
                                                                            <td align="right">
                                                                                <span class="tr-label">
                                                                                    {{ number_format($inv_detail->cicilan, 2) }}
                                                                                </span>
                                                                                <input type="text" class="form-control number tr-hide" name="installment" value="{{ $inv_detail->cicilan }}">
                                                                            </td>
                                                                            <td align="right">
                                                                                <span class="tr-label">
                                                                                    {{ number_format($inv_detail->bunga, 2) }}
                                                                                </span>
                                                                                <input type="text" class="form-control number tr-hide" id="profit_{{ $inv_detail->id }}" onkeyup="pr_rt('rate_{{ $inv_detail->id }}', {{ $value->amount }}, this)" name="profit" value="{{ $inv_detail->bunga }}">
                                                                            </td>
                                                                            @php
                                                                                $keyDate = date("Y_m", strtotime($inv_detail->plan_date));
                                                                                $cicilInv[$keyDate][] = $inv_detail->cicilan;
                                                                                $prevDateKey = $keyDate;
                                                                                $total_amount_inv = $inv_detail->cicilan + $inv_detail->bunga;
                                                                                $balanceInv -= $inv_detail->cicilan;
                                                                                $sumCicilInv += $inv_detail->cicilan;
                                                                                $sumProfitInv += $inv_detail->bunga;
                                                                                $sumTotalInv += $total_amount_inv;
                                                                                if(!empty($inv_detail->paid_at)){
                                                                                    $sumPaid += $total_amount_inv;
                                                                                }
                                                                                $totalProf += $inv_detail->bunga;
                                                                            @endphp
                                                                            <td align="right">{{ number_format($total_amount_inv, 2) }}</td>
                                                                            <td align="center">
                                                                                @if (empty($inv_detail->paid_at))
                                                                                <input type="hidden" name="id" value="{{ $inv_detail->id }}">
                                                                                <button type="button" onclick="btn_edit('form-edit-{{ $inv_detail->id }}')" class="btn btn-sm btn-primary btn-icon tr-hide" data-action="edit"><i class="fa fa-check"></i></button>
                                                                                <div class="tr-label">
                                                                                    <button type="button" onclick="pay_detail({{ $inv_detail->id }}, 'investor')" class="btn btn-sm btn-success">Pay</button>
                                                                                    <button type="button" onclick="close_detail({{ $inv_detail->id }}, 'investor')" class="btn btn-sm btn-danger">Close</button>
                                                                                </div>
                                                                                @else
                                                                                    {{ date("d F Y", strtotime($inv_detail->paid_at)) }}
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                    </form>
                                                                    @endif
                                                                    @php
                                                                        $actual_amount = $value->amount;
                                                                        if($inv_detail->closed){
                                                                            if(!empty($value->actual_amount)){
                                                                                $actual_amount = $value->actual_amount;
                                                                            }
                                                                        }
                                                                        if(!empty($value->close)){
                                                                            $next_date = date("Y-m-d", strtotime("+1 month ".$value->close));

                                                                        }
                                                                        $row['amount'] = $actual_amount;
                                                                        $row['date'] = $inv_detail->plan_date;
                                                                        $blInv[$value->id][date("Y_m", strtotime($inv_detail->plan_date))] = $actual_amount;
                                                                    @endphp
                                                                    @endforeach
                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <td colspan="3" align="center">Total</td>
                                                                        <td align="right">{{ number_format($balanceInv, 2) }}</td>
                                                                        <td align="right">{{ number_format($sumCicilInv, 2) }}</td>
                                                                        <td align="right">{{ number_format($sumProfitInv, 2) }}</td>
                                                                        <td align="right">{{ number_format($sumTotalInv, 2) }}</td>
                                                                        <td align="right">{{ number_format($sumPaid, 2) }}</td>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 print-div" id="print-div-{{ $value->id }}">
                                <div class="row">
                                    <div class="col-4">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td>Investor</td>
                                                <td>: {{ $investors_master[$keyInvestor] }}</td>
                                            </tr>
                                            <tr>
                                                <td>Invested Amount</td>
                                                <td>: {{ $business->currency }} {{ number_format($value->amount, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Project Name</td>
                                                <td>: {{ $business->bank }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="row" id="table-print">
                                    <div class="col-12">
                                        <table class="table table-bordered table-dark table-hover table-responsive-xl">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th>Payment Date</th>
                                                    <th>Profit rate (%)</th>
                                                    <th>Balance</th>
                                                    <th>Installment</th>
                                                    <th>Profit</th>
                                                    <th>Total Amount</th>
                                                    <th>Status Payment</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $total_amount_inv = 0;
                                                    $sumCicilInv = 0;
                                                    $sumProfitInv = 0;
                                                    $sumTotalInv = 0;
                                                    $balanceInv = $value->amount;
                                                    $sumPaid = 0;
                                                    $prevDateKey = "";
                                                    $bInv = [];
                                                @endphp
                                                @foreach ($investor_details[$value->id] as $i => $inv_detail)
                                                @if ($inv_detail->closed == 0)
                                                <form action="{{ route('business.investors.list.editInvestment') }}" method="post" id="form-edit-{{ $inv_detail->id }}">
                                                    @csrf
                                                    <tr>
                                                        <td align="center">
                                                            {{ $i+1 }}
                                                        </td>
                                                        <td align="center">
                                                            {{ date("d F Y", strtotime($inv_detail->plan_date)) }}
                                                        </td>
                                                        <td align="center">
                                                            <span class="tr-label">
                                                                {{ number_format($inv_detail->bunga_rate, 2) }}
                                                            </span>
                                                            <input type="text" class="form-control number tr-hide" id="rate_{{ $inv_detail->id }}" onkeyup="rate_profit('profit_{{ $inv_detail->id }}', {{ $value->amount }}, this)" name="profit_rate" value="{{ $inv_detail->bunga_rate }}">
                                                        </td>
                                                        <td align="right">
                                                            {{ number_format($balanceInv, 2) }}
                                                        </td>
                                                        <td align="right">
                                                            <span class="tr-label">
                                                                {{ number_format($inv_detail->cicilan, 2) }}
                                                            </span>
                                                            <input type="text" class="form-control number tr-hide" name="installment" value="{{ $inv_detail->cicilan }}">
                                                        </td>
                                                        <td align="right">
                                                            <span class="tr-label">
                                                                {{ number_format($inv_detail->bunga, 2) }}
                                                            </span>
                                                            <input type="text" class="form-control number tr-hide" id="profit_{{ $inv_detail->id }}" onkeyup="pr_rt('rate_{{ $inv_detail->id }}', {{ $value->amount }}, this)" name="profit" value="{{ $inv_detail->bunga }}">
                                                        </td>
                                                        @php
                                                            $keyDate = date("Y_m", strtotime($inv_detail->plan_date));
                                                            $row['amount'] = $value->amount;
                                                            $row['date'] = $inv_detail->plan_date;
                                                            $prevDateKey = $keyDate;
                                                            $total_amount_inv = $inv_detail->cicilan + $inv_detail->bunga;
                                                            $balanceInv -= $inv_detail->cicilan;
                                                            $sumCicilInv += $inv_detail->cicilan;
                                                            $sumProfitInv += $inv_detail->bunga;
                                                            $sumTotalInv += $total_amount_inv;
                                                            if(!empty($inv_detail->paid_at)){
                                                                $sumPaid += $total_amount_inv;
                                                            }
                                                            $totalProf += $inv_detail->bunga;
                                                        @endphp
                                                        <td align="right">{{ number_format($total_amount_inv, 2) }}</td>
                                                        <td align="center">
                                                            @if (empty($inv_detail->paid_at))
                                                                waiting
                                                            @else
                                                                {{ date("d F Y", strtotime($inv_detail->paid_at)) }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </form>
                                                @endif
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="3" align="center">Total</td>
                                                    <td align="right">{{ number_format($balanceInv, 2) }}</td>
                                                    <td align="right">{{ number_format($sumCicilInv, 2) }}</td>
                                                    <td align="right">{{ number_format($sumProfitInv, 2) }}</td>
                                                    <td align="right">{{ number_format($sumTotalInv, 2) }}</td>
                                                    <td align="right">{{ number_format($sumPaid, 2) }}</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
                <div class="col-12">
                    <div class="accordion accordion-solid accordion-toggle-plus mb-10" id="accordionNew{{ $keyInvestor }}">
                        <div class="card">
                            <div class="card-header col-6" id="headingOne6">
                                <div class="card-title collapsed" data-toggle="collapse" data-target="#collapseNew{{ $keyInvestor }}">
                                    <span class="svg-icon svg-icon-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                                <path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" fill="#000000" fill-rule="nonzero"></path>
                                                <path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) "></path>
                                            </g>
                                        </svg>
                                    </span>
                                    <div class="card-label pl-4">
                                        Add New Investment
                                    </div>
                                </div>
                            </div>
                            <div id="collapseNew{{ $keyInvestor }}" class="collapse" data-parent="#accordionNew{{ $keyInvestor }}">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <form action="{{route('business.investors.list.add')}}" method="post">
                                                @csrf
                                                <div class="form-group row">
                                                    <label class="col-md-3 col-form-label">Investment Name</label>
                                                    <div class="col-md-9">
                                                        <input type="text" class="form-control" name="investment_name" required>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-md-3 col-form-label">Profit Rate</label>
                                                    <div class="col-md-9">
                                                        <input type="text" class="form-control number" name="profit_rate" placeholder="%" required>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-md-3 col-form-label">Amount</label>
                                                    <div class="col-md-9">
                                                        <input type="text" class="form-control number" name="amount" placeholder="Amount" required>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="" class="col-md-3 col-form-label">Start From</label>
                                                    <div class="col-md-9">
                                                        <input type="date" required class="form-control" name="start_from"
                                                            value="{{ $business->start }}" min="{{ $business->start }}"
                                                            max="{{ date('Y-m-d', strtotime("+".$business->period." months ".$business->start)) }}">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-md-3 col-form-label"></label>
                                                    <div class="col-md-9">
                                                        <input type="hidden" name="investor" value="{{ $keyInvestor }}">
                                                        <input type="hidden" name="id_business" value="{{$business->id}}">
                                                        <button type="submit" class="btn btn-xs btn-success"><i class="fa fa-plus"></i> Add</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <table class="table table-bordered table-hover font-weight-bold">
                        <tr>
                            <td bgcolor="#5dfdcb">{{$partner[$business->partner]}}</td>
                            <td bgcolor="#3699FF" class="text-white">{{$investors_master[$keyInvestor]}}</td>
                            <td bgcolor="#3699FF" class="text-white">Profit</td>
                            <td bgcolor="#3699FF" class="text-white">Administration</td>
                        </tr>
                        <tr>
                            <td bgcolor="#5dfdcb">{{ number_format($totalVa, 2) }}</td>
                            <td bgcolor="#3699FF" class="text-white">{{ number_format($totalVb, 2) }}</td>
                            <td bgcolor="#3699FF" class="text-white">{{ number_format($totalProf, 2) }}</td>
                            <td bgcolor="#3699FF" class="text-white">{{ number_format($totalAdm, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endforeach


<div class="card card-custom gutter-b">
    <div class="card-body">
        <h3 class="card-title">{{ \Session::get('company_tag') }} Calculation <button type="button"  data-toggle="collapse" data-target="#collapseComCog" class="btn btn-outline-secondary btn-icon btn-xs collapsed" aria-expanded="false"><i class="fa fa-cog"></i></button></h3>
        <div class="row">
            <div class="col-12">
                <div class="accordion accordion-solid accordion-toggle-plus mb-10" id="accordionComCog">
                    <div id="collapseComCog" class="collapse" data-parent="#accordionComCog">
                        <form action="{{ route('business.updateRate') }}" method="post">
                            @csrf
                            <div class="form-group row">
                                <label for="" class="col-2 col-form-label">Profit Rate</label>
                                <div class="col-4">
                                    <div class="input-group">
                                        <input type="text" class="form-control number" name="profit_rate" value="{{ (empty($business->own_percent)) ? $business->bunga : $business->own_percent }}">
                                        <div class="input-group-append">
                                            <input type="hidden" name="business" value="{{$business->id}}">
                                            <input type="hidden" name="type" value="company">
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <label for=""><b>Invested Amount : </b>
                    {{ $business->currency." ". number_format($balanceOwn, 2) }}
                    <button type="button" class="btn btn-xs btn-icon btn-outline-info collapsed" aria-expanded="false" data-toggle="collapse" data-target="#collapseHis"><i class="fa fa-history"></i></button>
                </label>
            </div>
            <div class="col-12">
                <div class="accordion accordion-solid accordion-toggle-plus mb-10" id="accordionHis">
                    <div id="collapseHis" class="collapse" data-parent="#accordionHis">
                        <div class="col-12">
                            <table class="table table-hover">
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th class="text-center">Amount</th>
                                    <th class="text-center">Exchange Rate</th>
                                    <th class="text-center">Sub Total (IDR)</th>
                                    <th class="text-center">
                                        <button type="button" data-toggle="modal" data-target="#modalAddInvestment" class="btn btn-icon btn-xs btn-success"><i class="fa fa-plus"></i></button>
                                    </th>
                                </tr>
                                @if(empty($business->company))
                                    <tr>
                                        <td colspan="5" align="center">No data available</td>
                                    </tr>
                                @else
                                    <tbody>
                                        @php
                                            $sumInvCom = 0;
                                        @endphp
                                    @foreach(json_decode($business->company) as $keyCompany => $valueCompany)
                                        <tr>
                                            <td align="center">{{$keyCompany + 1}}</td>
                                            <td>{{$valueCompany->currency." ".number_format($valueCompany->amount, 2)}}</td>
                                            <td>{{number_format((isset($valueCompany->exchange)) ? $valueCompany->exchange : 1, 2)}}</td>
                                            <td align="right">IDR {{number_format($valueCompany->IDR, 2)}}</td>
                                            <td align="center">
                                                <a href="{{route('business.deleteInvesment')}}?b={{$business->id}}&t=c&p={{$keyCompany}}" class="btn btn-xs btn-icon btn-danger"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        @php
                                            $sumInvCom += $valueCompany->IDR;
                                        @endphp
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td colspan="2">
                                            Invesment Amount : {{$business->currency}} {{number_format($balanceOwn, 2)}}
                                        </td>
                                        <td align="right">Total</td>
                                        <td align="right">
                                            {{$business->currency}} {{number_format($sumInvCom, 2)}}
                                        </td>
                                        <td>
                                            Balance : {{$business->currency}} {{number_format($balanceOwn - $sumInvCom, 2)}}
                                        </td>
                                    </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-10">
                <div class="accordion accordion-solid accordion-toggle-plus mb-10" id="accordionExample6">
                    <div class="card">
                        <div class="card-header col-6" id="headingOne6">
                            <div class="card-title collapsed" data-toggle="collapse" data-target="#collapseOne6">
                                <span class="svg-icon svg-icon-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                            <path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" fill="#000000" fill-rule="nonzero"></path>
                                            <path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) "></path>
                                        </g>
                                    </svg>
                                </span>
                                <div class="card-label pl-4">
                                    See Details
                                </div>
                            </div>
                        </div>
                        <div id="collapseOne6" class="collapse" data-parent="#accordionExample6">
                            <div class="card-body bg-secondary">
                                <div class="row">
                                    <div class="col-12">
                                        <button type="button" onclick="adiv('print-comp')" class="btn btn-icon btn-sm btn-outline-primary"><i class="fa fa-print"></i></button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td>Investor</td>
                                                <td>: {{ \Session::get('company_name_parent') }}</td>
                                            </tr>
                                            <tr>
                                                <td>Invested Amount</td>
                                                <td>: {{ $business->currency }} {{ number_format($balanceOwn, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Project Name</td>
                                                <td>: {{ $business->bank }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table table-bordered table-dark table-hover table-responsive-xl">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Payment Date</th>
                                                    <th>Profit rate (%)</th>
                                                    <th>Balance</th>
                                                    <th>Installment</th>
                                                    <th>Profit</th>
                                                    <th>Total Amount</th>
                                                    <th>Status Payment</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $blOwn = $business->value;
                                                    $preBlInv = 0;
                                                    $nCicil = 0;
                                                    $sumBl = $business->value;
                                                    $sumComPaid = 0;
                                                    $sumComProfit = 0;
                                                    // dd($blInv);
                                                @endphp
                                                @foreach ($bs_detail as $i => $item)
                                                    @php
                                                        $bunga_rate = (empty($business->own_percent)) ? $item->bunga_rate : $business->own_percent;
                                                        $cicilOwn = $item->cicilan;
                                                        $dateKey = date("Y_m", strtotime($item->plan_date));
                                                        $sumBlInv = 0;
                                                        foreach ($blInv as $in) {
                                                            if(isset($in[$dateKey])){
                                                                $sumBlInv += $in[$dateKey];
                                                            }
                                                        }
                                                        $blOwn = $business->value - $sumBlInv;
                                                        $bungaOwn = $blOwn * ($bunga_rate / 100);
                                                        if(isset($cicilInv[$dateKey])){
                                                            $cicilOwn -= array_sum($cicilInv[$dateKey]);
                                                        }

                                                        $inst = ($cicilOwn > ($blOwn - $nCicil)) ? ($blOwn - $nCicil) : $cicilOwn;
                                                        $blme = $blOwn - $nCicil;
                                                    @endphp
                                                    <tr>
                                                        <td align="center">{{ $i+1 }}</td>
                                                        <td align="center">{{ date("d F Y", strtotime($item->plan_date)) }}</td>
                                                        <td align="center"><label for="" class="form-bunga_rate">{{ number_format($bunga_rate, 2) }}</label></td>
                                                        <td align="right">{{ number_format(($blme < 100) ? 0 : $blme, 2) }}</td>
                                                        <td align="right"><label for="" class="form-cicilan">{{ number_format(($inst < 100) ? 0 : $inst, 2) }}</label></td>
                                                        <td align="right"><label for="" class="form-bunga">{{ number_format($bungaOwn, 2) }}</label></td>
                                                        @php
                                                            $total_amount = $inst + $bungaOwn;
                                                            $nCicil += $inst;
                                                            $sumCicilOwn += $inst;
                                                            $sumProfitOwn += $bungaOwn;
                                                            $sumTotalAmount += $total_amount;
                                                            $sumComProfit += $bungaOwn;
                                                        @endphp
                                                        <td align="right">{{ number_format(($total_amount < 100) ? 0 : $total_amount, 2) }}</td>
                                                        <td align="center">
                                                            @if (empty($item->paid_investment))
                                                            <button type="button" onclick="pay_company(this, {{ $item->id }})" class="btn btn-sm btn-success">Pay</button>
                                                            @else
                                                                @php
                                                                    $payment = json_decode($item->paid_investment, true);
                                                                    echo date("d F Y", strtotime($payment['date']));
                                                                    $sumComPaid += $payment['profit'] + $payment['installment'];
                                                                @endphp
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="3" align="center">Total</td>
                                                    <td align="right">{{ number_format($blOwn - $nCicil, 2) }}</td>
                                                    <td align="right">{{ number_format($sumCicilOwn, 2) }}</td>
                                                    <td align="right">{{ number_format($sumProfitOwn, 2) }}</td>
                                                    <td align="right">{{ number_format($sumTotalAmount, 2) }}</td>
                                                    <td align="right">{{ number_format($sumComPaid, 2) }}</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row print-div" id="print-comp">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-4">
                                <table class="table table-borderless">
                                    <tr>
                                        <td>Investor</td>
                                        <td>: {{ \Session::get('company_name_parent') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Invested Amount</td>
                                        <td>: {{ $business->currency }} {{ number_format($balanceOwn, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Project Name</td>
                                        <td>: {{ $business->bank }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="row" id="table-print">
                            <div class="col-12">
                                <table class="table table-bordered table-dark table-hover table-responsive-xl">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Payment Date</th>
                                            <th>Profit rate (%)</th>
                                            <th>Balance</th>
                                            <th>Installment</th>
                                            <th>Profit</th>
                                            <th>Total Amount</th>
                                            <th>Status Payment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $blOwn = $business->value;
                                            $preBlInv = 0;
                                            $nCicil = 0;
                                            $sumBl = $business->value;
                                            $sumComPaid = 0;
                                            $sumComProfit = 0;
                                            // dd($cicilInv);
                                        @endphp
                                        @foreach ($bs_detail as $i => $item)
                                            @php
                                                $sumBlInv = 0;
                                                $bunga_rate = (empty($business->own_percent)) ? $item->bunga_rate : $business->own_percent;
                                                $cicilOwn = $item->cicilan;
                                                $dateKey = date("Y_m", strtotime($item->plan_date));
                                                foreach ($blInv as $in) {
                                                    if(isset($in[$dateKey])){
                                                        $sumBlInv += $in[$dateKey];
                                                    }
                                                }
                                                $blOwn = $business->value - $sumBlInv;
                                                $bungaOwn = $blOwn * ($bunga_rate / 100);
                                                if(isset($cicilInv[$dateKey])){
                                                    $cicilOwn -= array_sum($cicilInv[$dateKey]);
                                                }

                                                $inst = ($cicilOwn > ($blOwn - $nCicil)) ? ($blOwn - $nCicil) : $cicilOwn;
                                                $blme = $blOwn - $nCicil;
                                            @endphp
                                            <tr>
                                                <td align="center">{{ $i+1 }}</td>
                                                <td align="center">{{ date("d F Y", strtotime($item->plan_date)) }}</td>
                                                <td align="center"><label for="" class="form-bunga_rate">{{ number_format($bunga_rate, 2) }}</label></td>
                                                <td align="right">{{ number_format(($blme < 100) ? 0 : $blme, 2) }}</td>
                                                <td align="right"><label for="" class="form-cicilan">{{ number_format(($inst < 100) ? 0 : $inst, 2) }}</label></td>
                                                <td align="right"><label for="" class="form-bunga">{{ number_format($bungaOwn, 2) }}</label></td>
                                                @php
                                                    $total_amount = $inst + $bungaOwn;
                                                    $nCicil += $inst;
                                                    $sumCicilOwn += $inst;
                                                    $sumProfitOwn += $bungaOwn;
                                                    $sumTotalAmount += $total_amount;
                                                    $sumComProfit += $bungaOwn;
                                                @endphp
                                                <td align="right">{{ number_format(($total_amount < 100) ? 0 : $total_amount, 2) }}</td>
                                                <td align="center">
                                                    @if (empty($item->paid_investment))
                                                        waiting
                                                    @else
                                                        @php
                                                            $payment = json_decode($item->paid_investment, true);
                                                            echo date("d F Y", strtotime($payment['date']));
                                                            $sumComPaid += $payment['profit'] + $payment['installment'];
                                                        @endphp
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" align="center">Total</td>
                                            <td align="right">{{ number_format($blOwn - $nCicil, 2) }}</td>
                                            <td align="right">{{ number_format($sumCicilOwn, 2) }}</td>
                                            <td align="right">{{ number_format($sumProfitOwn, 2) }}</td>
                                            <td align="right">{{ number_format($sumTotalAmount, 2) }}</td>
                                            <td align="right">{{ number_format($sumComPaid, 2) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="modalAddInvestment" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content" id="contentModalAddInvestment">
                            <form action="{{ route('business.investors.list.addInvesment') }}" method="post">
                                @csrf
                                <div class="modal-header">
                                    <h1 class="modal-title">Add Investment</h1>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group row">
                                        <label for="" class="col-form-label col-3">Currency</label>
                                        <div class="col-9">
                                            <select name="currency" class="form-control select2" required>
                                                @foreach(json_decode($list_currency) as $keyCurrency => $valueCurrency)
                                                    <option value="{{$keyCurrency}}" {{($keyCurrency == "IDR") ? "selected" : ""}}>{{strtoupper($keyCurrency."-".$valueCurrency)}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="" class="col-form-label col-3">Amount</label>
                                        <div class="col-9">
                                            <input type="text" class="form-control number" name="amount" placeholder="Insert invesment amount">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="" class="col-form-label col-3">Exchange Rate to IDR</label>
                                        <div class="col-9">
                                            <input type="text" class="form-control number" name="rate" placeholder="Insert exchange rate">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <input type="hidden" name="id" value="{{ $business->id }}">
                                    <input type="hidden" name="type" value="company">
                                    <button type="button" class="btn btn-light-primary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Add</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <table class="table table-bordered table-hover font-weight-bold">
                    <tr>
                        <td bgcolor="#5dfdcb">{{$partner[$business->partner]}}</td>
                        <td bgcolor="#3699FF" class="text-white">{{ \Session::get('company_tag') }}</td>
                        <td bgcolor="#3699FF" class="text-white">Profit</td>
                        <td bgcolor="#3699FF" class="text-white">Administration</td>
                    </tr>
                    <tr>
                        @php
                            $v_a = floor($balanceOwn + ($balanceOwn * $business->bunga / 100) * $business->period);
                            $v_b = floor($balanceOwn + ($balanceOwn * $business->own_percent / 100) * $business->period);
                        @endphp
                        <td bgcolor="#5dfdcb">{{ number_format($v_a, 2) }}</td>
                        <td bgcolor="#3699FF" class="text-white">{{ number_format($v_b, 2) }}</td>
                        <td bgcolor="#3699FF" class="text-white">{{ number_format($sumComProfit, 2) }}</td>
                        <td bgcolor="#3699FF" class="text-white">{{ number_format($v_a - $v_b, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPay" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" id="contentModalPay">

        </div>
    </div>
</div>

<div class="modal fade" id="modalPayCompany" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('business.investors.pay') }}" method="post">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Pay</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="" class="col-form-label col-3">Profit Rate</label>
                        <div class="col-9">
                            <input type="text" class="form-control number" name="profit_rate" id="form-profit-rate" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-form-label col-3">Installment</label>
                        <div class="col-9">
                            <input type="text" class="form-control number" name="installment" id="form-installment" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-form-label col-3">Profit</label>
                        <div class="col-9">
                            <input type="text" class="form-control number" name="profit" id="form-profit" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-form-label col-3">Total Amount</label>
                        <div class="col-9">
                            <input type="text" class="form-control number" id="form-total-amount" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="type" value="company">
                    <input type="hidden" name="id" value="" id="id_detail">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Pay</button>
                </div>
            </form>

        </div>
    </div>
</div>

<div class="modal fade" id="modalClose" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" id="contentModalClose">

        </div>
    </div>
</div>
@endsection

@section('custom_script')
<script src="{{asset('theme/assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js?v=7.0.5')}}"></script>
<script src="{{ asset('assets/jquery-number/jquery.number.js') }}"></script>
<script>

    function adiv(divName){
		var divToPrint=document.getElementById(divName);

        var newWin=window.open('','Print-Window');

        var css = "<header><style>#table-print table, #table-print td, #table-print th {border: 1px solid black;} #table-print table {width: 100%;border-collapse: collapse;} td {padding: 5px}</style></header>"

        newWin.document.open();

        newWin.document.write('<html>'+css+'<body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');

        newWin.document.close();

        setTimeout(function(){newWin.close();},10);
    }

    function rate_profit(x,y,z){
        var val = $(z).val().replaceAll(",", "")
        var sum = (val/100) * y
        $("#"+x).val(sum)
    }

    function pr_rt(x,y,z){
        var val = $(z).val().replaceAll(",", "")
        var sum = (val/y) * 100
        $("#"+x).val(sum)
    }

    function btn_edit(form){
        Swal.fire({
            title: "Are you sure?",
            text: "",
            icon: "info",
            showCancelButton: true,
            confirmButtonText: "Update"
        }).then(function(result) {
            if (result.value) {
                $("#"+form).submit()
            }
        });
    }

    function close_detail(x, y){
        $("#modalClose").modal('show')
        $.ajax({
            url : "{{ route('business.investors.close.list') }}",
            type : "post",
            data : {
                _token : "{{ csrf_token() }}",
                x : x,
                y : y
            },
            cache : false,
            success : function(response){
                $("#contentModalClose").html(response)
                var number = $("#contentModalClose").find(".number")
                number.css('text-align', 'right')

                var form_hide = $("#contentModalClose").find(".form-hide")
                var inputs = form_hide.find("input").not("input[type=hidden]")
                console.log(inputs)
                form_hide.hide()
                var left_amount = $("#left-amount").val().replaceAll(",", "")
                $("#close-amount").on('change blur',function(){
                    var left = left_amount - $(this).val().replaceAll(",", "")
                    console.log($(this).val())
                    console.log(left_amount)
                    console.log(left)
                    if(left > 0 ){
                        inputs.prop('required', true)
                        form_hide.show()
                    } else {
                        inputs.prop('required', false)
                        form_hide.hide()
                        $("#close-amount").val(left_amount)
                    }
                })

                number.number(true, 2)
            }
        })
    }

    function pay_detail(x, y){
        $("#modalPay").modal('show')
        $.ajax({
            url : "{{ route('business.investors.pay.list') }}",
            type : "post",
            data : {
                _token : "{{ csrf_token() }}",
                x : x,
                y : y
            },
            cache : false,
            success : function(response){
                $("#contentModalPay").html(response)
                var number = $("#contentModalPay").find(".number")
                number.css('text-align', 'right')
            }
        })
    }

    function pay_company(btn, x){
        $("#modalPayCompany").modal('show')
        var td = $(btn).parent()
        var tr = td.parent()
        var cicilan = tr.find("label.form-cicilan")
        var profit_rate = tr.find("label.form-bunga_rate")
        var profit = tr.find("label.form-bunga")
        $("#form-profit-rate").val(profit_rate.text())
        $("#form-installment").val(cicilan.text())
        $("#form-profit").val(profit.text())
        console.log(parseInt(cicilan.text().replaceAll(',', '')))
        console.log(parseInt(profit.text().replaceAll(',', '')))
        var total = parseInt(cicilan.text().replaceAll(',', '')) + parseInt(profit.text().replaceAll(',', ''))
        $("#form-total-amount").val(total)
        $("#id_detail").val(x)
    }

    function btn_edit_click(btn){
        var type = $(btn).attr('data-action')
        var div = $(btn).parent()
        var td = div.parent()
        var tr = td.parent()
        console.log(td)
        console.log(tr)
        var hide = tr.find(".tr-hide")
        var label = tr.find(".tr-label")
        var i = $(btn).find("i")
        if(type == "edit"){
            $(btn).attr('data-action', 'cancel')
            i.removeClass("fa-edit")
            i.addClass("fa-times")
            hide.show()
            label.hide()
        } else {
            $(btn).attr('data-action', 'edit')
            i.removeClass("fa-times")
            i.addClass("fa-edit")
            hide.hide()
            label.show()
        }
        $(btn).blur()
    }

    function submit_edit_form() {
        Swal.fire({
            title: "Update",
            text: "Are you sure you want to update this data?",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Submit",
            cancelButtonText: "Cancel",
            reverseButtons: true,
        }).then(function (result) {
            if (result.value) {
                $("#btn-submit-edit").click()
            }
        })
    }

    function button_delete(x) {
        Swal.fire({
            title: "Delete",
            text: "Are you sure you want to delete?",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Delete",
            cancelButtonText: "Cancel",
            reverseButtons: true,
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: "{{URL::route('business.investors.list.delete')}}",
                    type: "POST",
                    dataType: "json",
                    data: {
                        '_token': '{{csrf_token()}}',
                        'val': x,
                        'bus': "{{ $business->id }}"
                    },
                    cache: false,
                    success: function (response) {
                        if (response.error == 0) {
                            location.reload()
                        } else {
                            Swal.fire({
                                title: "Error Occured",
                                icon: "error"
                            })
                        }
                    }
                })
            }
        })
    }

    function button_edit(x) {
        $.ajax({
            url: "{{URL::route('treasury.find')}}",
            type: "POST",
            dataType: "json",
            data: {
                '_token': '{{csrf_token()}}',
                'val': x
            },
            cache: false,
            success: function (response) {
                $("#bank_name").val(response.source)
                $("#branch_name").val(response.branch)
                $("#account_name").val(response.account_name)
                $("#account_number").val(response.account_number)
                $("#currency").val(response.currency).trigger('change')
                $("#id_tre").val(response.id)
            }
        })
    }

    function printDiv(divName) {
        $("#print-section").html("")
        $(divName).removeClass('collapse')
        $("#print-section").removeClass('collapse')
        var printContents = document.getElementById(divName).innerHTML;
        $("#print-section").append(printContents)
        print()

        // window.print();
        $("#print-section").addClass('collapse')
        $(divName).addClass('collapse')
    }

    function saveField(x) {
        console.log(x)
        $("#table-print-" + x).DataTable().destroy()
        var t = $("#table-print-" + x).DataTable({
            paging: false,
            sorting: false,
            bInfo: false,
        })
        var fields = $(".field-" + x).toArray()
        console.log(t.column())
        for (const fieldsKey in fields) {
            var column = t.column(fields[fieldsKey].value)
            if (fields[fieldsKey].checked) {
                column.visible(true)
            } else {
                column.visible(false)
            }
        }
        $("#btn-close-modal-" + x).click()

    }

    function this_investor(x, y) {
        var opt = $("#sel-to option").toArray()
        for (const optKey in opt) {
            console.log()
            $(opt[optKey]).show()
            if ($(opt[optKey]).val() == x) {
                $(opt[optKey]).hide()
            }
        }

        $("#key-investor").val(x)
        $("#key-detail").val(y)

    }

    function show_toast(msg, type) {

        console.log(type)

        if(type == "success"){
            toastr.options = {
                "closeButton": false,
                "debug": false,
                "newestOnTop": false,
                "progressBar": false,
                "positionClass": "toast-bottom-center",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
            toastr.success(msg);
        } else if(type == "error"){
            toastr.options = {
                "closeButton": false,
                "debug": false,
                "newestOnTop": false,
                "progressBar": false,
                "positionClass": "toast-bottom-center",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
            toastr.error(msg)
        }
    }

    $(document).ready(function () {
        $(".print-div").hide()

        @if(\Session::get('msg'))
        show_toast("{{ \Session::get('msg') }}", "success")
        @endif

        @if(\Session::get('error'))
            Swal.fire("Error", "{{ \Session::get('error') }}", "error")
        @endif

        $(".tr-hide").hide()

        $("#new-investor").hide()

        $("#sel-to").change(function () {
            // var am = $("#sel-to option:selected").attr('data-amount')
            if ($(this).val() == "new") {
                // show for description
                $("#new-investor").show()
                $("#new-investor input").attr('required', true)
            } else {
                $("#new-investor").hide()
                $("#new-investor input").attr('required', false)
            }
        })

        $(".number").number(true, 2)
        $("#modalField").on('hidden.bs.modal', function () {
            $("#target-table").val("")
        })
        $("#btn-set-field").click(function () {
            $("#table-print").DataTable().destroy()
            var t = $("#table-print").DataTable({
                paging: false,
                sorting: false,
                bInfo: false,
            })
            $("#btn-close-modal").click()
            var fields = $(".field").toArray()
            var field = []
            for (const fieldsKey in fields) {
                var column = t.column(fields[fieldsKey].value)
                if (fields[fieldsKey].checked) {
                    column.visible(true)
                } else {
                    column.visible(false)
                }
            }
            // var c = btoa(JSON.stringify(field))
            {
                {{--$("#frame_print").attr('src', "{{route('business.print', $business->id)}}?c="+c)--}}
            }
        })
        $("#btn-submit-edit").hide()
        $("#btn-submit").hide()
        $("#btn-deposit").click(function () {
            Swal.fire({
                title: "Add Deposit",
                text: "Are you sure you want to submit this data?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Submit",
                cancelButtonText: "Cancel",
                reverseButtons: true,
            }).then(function (result) {
                if (result.value) {
                    $("#btn-submit").click()
                }
            })
        })

        $("table.display").DataTable({
            responsive: true,
            fixedHeader: true,
            fixedHeader: {
                headerOffset: 90
            },
            bInfo: false,
            paging: false
        })
        $("select.select2").select2({
            width: "100%"
        })
    })

</script>
@endsection
