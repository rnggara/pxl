@extends('layouts.template')

@section('css')
    <style>
        @media print {
            footer {page-break-after: always;}
        }
        .print-show {
            display: none;
        }
    </style>
@endsection

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">Cash Flow </h3>
            <div class="card-toolbar">
                <div class="btn-group">

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mx-auto">
            <div class="card card-custom gutter-b">
                <div class="card-body">
                    <form action="{{ route('finance.cf.data') }}" method="post">
                        <div class="row">
                            <div class="col-6 mx-auto">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <select name="_year" class="form-control select2" id="year">
                                            @for ($i = 2010; $i <= date("Y") ; $i++)
                                                <option value="{{ $i }}" {{ ($ynow == $i) ? "SELECTED" : "" }}>{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            @csrf
                                            <button type="submit" name="search" value="1" class="btn btn-primary" ><i class="fa fa-search"></i>Search</button>
                                            <a href="{{ route('finance.cf.settings') }}" class="btn btn-secondary" ><i class="fa fa-cog"></i></a>
                                            {{-- <button type="button" id="btn-pdf" onclick="_pdf()" name="pdf" value="1" class="btn btn-info"><i class="fa fa-file-pdf"></i></button> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        @if (!empty($data))
        <div class="col-md-12 mx-auto">
            @php
                $bl = [];
                $sum_bank = [];
                foreach ($treasury as $key => $value) {
                    $bl[$value->id] = 0;
                    $sum_bank[$value->id] = [];
                }
                $sum_bank[0] = [];
            @endphp
            @for ($i = 1; $i <= 12; $i++)
            @php
                $surplusIDR = 0;
                $surplusUSD = 0;
            @endphp
            <div class="accordion accordion-solid accordion-panel mb-5 accordion-svg-toggle" id="accordionExample{{ $i }}">
                <div class="card">
                    <div class="card-header" id="headingOne{{ $i }}">
                        <div class="card-title collapsed" data-toggle="collapse" data-target="#collapseOne{{ $i }}">
                            <div class="card-label">
                                {{ date("F", strtotime(date("Y")."-".$i)) }}
                                @if (!empty($data))
                                    @php
                                        $link = "";
                                        if (isset($data[$i])) {
                                            $link = route('finance.cf.detail')."?t=".$data[$i]['year']."-".sprintf("%02d", $data[$i]['period']);
                                        }
                                    @endphp

                                @endif
                            </div>
                            <span class="svg-icon">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px"
                                    height="24px"
                                    viewBox="0 0 24 24"
                                    version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                        <path
                                            d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z"
                                            fill="#000000"
                                            fill-rule="nonzero"></path>
                                        <path
                                            d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z"
                                            fill="#000000"
                                            fill-rule="nonzero"
                                            opacity="0.3"
                                            transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) "></path>
                                    </g>
                                </svg>
                            </span>
                        </div>
                    </div>
                    <div id="collapseOne{{ $i }}" class="collapse" data-parent="#accordionExample{{ $i }}">
                        <div class="card-body m-5" id="print-div{{ $i }}">
                            <div class="row">
                                <div class="col-md-12 border p-5">
                                    <div class="print-hide">
                                        <a href="{{ $link }}" class="btn btn-success"><i class="fa fa-eye"></i> View</a>
                                        <button type="button" onclick="printDiv('print-div{{ $i }}')" href="#" class="btn btn-success"><i class="fa fa-print"></i> Print</button>
                                    </div>
                                    <h3 class="print-show">{{ date("F", strtotime(date("Y")."-".$i)) }} {{ $data[$i]['year'] }}</h3>
                                </div>
                                <div class="col-md-12 mx-auto border p-5 mt-5 footer">
                                    <h3 class="card-title">STARTING BALANCE</h3>
                                    <div class="row">
                                        <div class="col-12">
                                            <table class="table table-borderless" style="width: 100%">
                                                <tr>
                                                    <td>Description</td>
                                                    <td align="right">Amount (IDR)</td>
                                                    <td align="right">Amount (USD)</td>
                                                </tr>
                                                @php
                                                    $sum_IDR = 0;
                                                    $sum_USD = 0;
                                                @endphp
                                                @foreach ($treasury as $item)
                                                    <tr>
                                                        <td style="width: 60%">{{ $item->source }}</td>
                                                        <td align="right" style="width: 20%">
                                                            @php
                                                                $idr = 0;
                                                                $usd = 0;
                                                                if($i == 1){
                                                                    if (!empty($data)) {
                                                                        if(isset($data[$i])){
                                                                            $_data = $data[$i];
                                                                            if(isset($_data['op'])){
                                                                                $begin = $_data['op'];
                                                                                $IDR = $begin['IDR'];
                                                                                $USD = $begin['USD'];
                                                                                if(isset($IDR[$item->id])){
                                                                                    $idr = $IDR[$item->id];
                                                                                }

                                                                                if(isset($USD[$item->id])){
                                                                                    $usd = $USD[$item->id];
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                    if($item->currency == "IDR"){
                                                                        $bl[$item->id] = $idr;
                                                                    } else {
                                                                        $bl[$item->id] = $usd;
                                                                    }
                                                                } else {
                                                                    $idr = ($item->currency == "IDR") ? $bl[$item->id] : 0;
                                                                    $usd = ($item->currency != "IDR") ? $bl[$item->id] : 0;
                                                                }
                                                                $sum_IDR += $idr;
                                                                $sum_USD += $usd;
                                                            @endphp
                                                            <span class="number begin-balance-IDR-{{ $item->id }} ">{{ number_format($idr, 2) }}</span>
                                                        </td>
                                                        <td align="right" style="width: 20%">
                                                            <span class="number begin-balance-USD-{{ $item->id }} ">{{ number_format($usd, 2) }}</span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                @php
                                                    $surplusIDR += $sum_IDR;
                                                    $surplusUSD += $sum_USD;
                                                @endphp
                                                <tr>
                                                    <td class="font-weight-bold font-size-h3" style="width: 60%">TOTAL</td>
                                                    <td align="right" class="font-weight-bold font-size-h3" style="width: 20%">
                                                        <span class="number begin-balance-IDR-total">{{ number_format($sum_IDR, 2) }}</span>
                                                    </td>
                                                    <td align="right" class="font-weight-bold font-size-h3" style="width: 20%">
                                                        <span class="number begin-balance-USD-total">{{ number_format($sum_USD, 2) }}</span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @foreach ($cash as $item)
                                <div class="col-md-12 mx-auto border p-5">
                                    <h3 class="card-title">{{ strtoupper(str_replace("_", " ", $item)) }}</h3>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-borderless" style="width: 100%">
                                                <tr>
                                                    <td>Description</td>
                                                    <td align="right">Amount (IDR)</td>
                                                    <td align="right">Amount (USD)</td>
                                                </tr>
                                                @php
                                                    $sum_IDR = 0;
                                                    $sum_USD = 0;
                                                @endphp
                                                @if (isset($setting[$item]))
                                                    @foreach ($setting[$item] as $st)
                                                        @empty($st->parent)
                                                        <tr class="bg-light-success">
                                                            <td class="font-weight-bold" colspan="2">
                                                                {{ ucwords($st->label) }}
                                                            </td>
                                                            <td align="right">
                                                                {{-- <button type="button" data-toggle="modal" onclick="_modalChild('{{ $item.'-'.$st->label }}', {{ $st->id }})" data-target="#modalSettingChild" class="btn btn-outline-primary btn-icon btn-xs"><i class="fa fa-plus"></i></button>
                                                                <button type="button" onclick="_edit({{ $st->id }})" class="btn btn-outline-info btn-icon btn-xs"><i class="fa fa-edit"></i></button>
                                                                <button type="button" onclick="_delete({{ $st->id }})" class="btn btn-outline-danger btn-icon btn-xs"><i class="fa fa-trash"></i></button> --}}
                                                            </td>
                                                        </tr>
                                                        @php
                                                            $sum_item_IDR = 0;
                                                            $sum_item_USD = 0;
                                                        @endphp
                                                        @if (!empty($st->child))
                                                            @foreach ($st->child as $child)
                                                            @php
                                                                $idr = 0;
                                                                $usd = 0;
                                                                $link = "#";
                                                                if (!empty($data)) {
                                                                    if(isset($data[$i])){
                                                                        $link = route('finance.cf.view')."/$child->id?period=".$data[$i]['year']."-".sprintf("%02d", $data[$i]['period']);
                                                                        $_data = $data[$i]['data'];
                                                                        if(isset($_data[$item])){
                                                                            $begin = $_data[$item];
                                                                            $IDR = $begin['IDR'];
                                                                            $USD = $begin['USD'];
                                                                            if(isset($IDR[$child->id])){
                                                                                foreach ($IDR[$child->id] as $_ival) {
                                                                                    if($item == "cash_in"){
                                                                                        $idr += (!empty($_ival['debit'])) ? abs($_ival['debit']) * -1 : abs($_ival['credit']);
                                                                                    } else {
                                                                                        $idr += (!empty($_ival['debit'])) ? abs($_ival['debit']) : abs($_ival['credit']) * -1;
                                                                                    }

                                                                                    // if(isset($sum_bank[$_ival['id_bank']])){
                                                                                    //     $sum_bank[$_ival['id_bank']][$i][] = $_ival;
                                                                                    // } else {
                                                                                    //     $sum_bank[0][$i][] = $_ival;
                                                                                    // }

                                                                                    if(isset($bl[$_ival['id_bank']])){
                                                                                        $bl[$_ival['id_bank']] += (!empty($_ival['debit'])) ? abs($_ival['debit']) * -1 : abs($_ival['credit']);
                                                                                    }
                                                                                }
                                                                            }

                                                                            if(isset($USD[$child->id])){
                                                                                foreach ($USD[$child->id] as $_ival) {
                                                                                    if($item == "cash_in"){
                                                                                        $usd += (!empty($_ival['debit'])) ? abs($_ival['debit']) * -1 : abs($_ival['credit']);
                                                                                    } else {
                                                                                        $usd += (!empty($_ival['debit'])) ? abs($_ival['debit']) : abs($_ival['credit']) * -1;
                                                                                    }

                                                                                    // if(isset($sum_bank[$_ival['id_bank']])){
                                                                                    //     $sum_bank[$_ival['id_bank']][$i][] = $_ival;
                                                                                    // } else {
                                                                                    //     $sum_bank[0][$i][] = $_ival;
                                                                                    // }

                                                                                    if(isset($bl[$_ival['id_bank']])){
                                                                                        $bl[$_ival['id_bank']] += (!empty($_ival['debit'])) ? abs($_ival['debit']) * -1 : abs($_ival['credit']);
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                                $sum_IDR += $idr;
                                                                $sum_USD += $usd;

                                                                $sum_item_IDR += $idr;
                                                                $sum_item_USD += $usd;
                                                            @endphp
                                                            <tr class="border">
                                                                <td style="width: 60%">
                                                                    <i class="fa fa-arrow-right"></i>
                                                                    <a href="{{ $link }}" id="view-{{ $child->id }}">
                                                                        {{ $child->label }}
                                                                    </a>
                                                                    {{-- <button type="button" onclick="_edit({{ $child->id }})" class="btn btn-outline-info btn-circle btn-icon btn-xs"><i class="fa fa-edit"></i></button>
                                                                    <button type="button" onclick="_delete({{ $child->id }})" class="btn btn-outline-danger btn-circle btn-icon btn-xs"><i class="fa fa-times-circle"></i></button> --}}
                                                                </td>
                                                                <td align="right" style="width: 20%">
                                                                    <span data-parent="{{ $child->parent }}" class="number sub-IDR-{{ $child->parent }} child {{ $st->parent_type }}-balance-IDR-{{ $child->id }}">{{ number_format($idr, 2) }}</span>
                                                                </td>
                                                                <td align="right" style="width: 20%">
                                                                    <span data-parent="{{ $child->parent }}" class="number sub-USD-{{ $child->parent }} child {{ $st->parent_type }}-balance-USD-{{ $child->id }}">{{ number_format($usd, 2) }}</span>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        @endif
                                                        <tr class="border-bottom bg-secondary">
                                                            <td class="font-weight-bold">Total {{ $st->label }}</td>
                                                            <td align="right">
                                                                <span class="number sub-IDR parent" data-id="{{ $st->id }}">{{ number_format($sum_item_IDR, 2) }}</span>
                                                            </td>
                                                            <td align="right">
                                                                <span class="number sub-USD parent" data-id="{{ $st->id }}">{{ number_format($sum_item_USD, 2) }}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3"></td>
                                                        </tr>
                                                        @endempty
                                                    @endforeach
                                                @endif
                                                @php
                                                    if($item == "cash_in"){
                                                        $surplusIDR += $sum_IDR;
                                                        $surplusUSD += $sum_USD;
                                                    } else {
                                                        $surplusIDR -= $sum_IDR;
                                                        $surplusUSD -= $sum_USD;
                                                    }
                                                @endphp
                                                <tr>
                                                    <td class="font-weight-bold font-size-h3" style="width: 60%">TOTAL {{ strtoupper(str_replace("_", " ", $item)) }}</td>
                                                    <td align="right" class="font-weight-bold font-size-h3" style="width: 20%">
                                                        <span class="number {{ $item }}-balance-IDR-total">{{ number_format($sum_IDR, 2) }}</span>
                                                    </td>
                                                    <td align="right" class="font-weight-bold font-size-h3" style="width: 20%">
                                                        <span class="number {{ $item }}-balance-USD-total">{{ number_format($sum_USD, 2) }}</span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                <div class="col-md-12 mx-auto border p-5 mt-5 footer">
                                    <table class="table table-borderless" style="width: 100%">
                                        <tr>
                                            <td style="width: 60%">
                                                <h3 class="card-title">SURPLUS/DEFISIT</h3>
                                            </td>
                                            <td align="right" class="font-weight-bold font-size-h3" style="width: 20%">
                                                <span id="surplus-defisit-IDR" class="number">{{ number_format($surplusIDR, 2) }}</span>
                                            </td>
                                            <td align="right" class="font-weight-bold font-size-h3" style="width: 20%">
                                                <span id="surplus-defisit-USD" class="number">{{ number_format($surplusUSD, 2) }}</span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-12 mx-auto border p-5 mt-5">
                                    <h3 class="card-title">ENDING BALANCE</h3>
                                    <div class="row">
                                        <div class="col-12">
                                            <table class="table table-borderless" style="width: 100%">
                                                <tr>
                                                    <td>Description</td>
                                                    <td align="right">Amount (IDR)</td>
                                                    <td align="right">Amount (USD)</td>
                                                </tr>
                                                @php
                                                    $sum_IDR = 0;
                                                    $sum_USD = 0;
                                                @endphp
                                                @foreach ($treasury as $item)
                                                    @php

                                                        if(isset($data[$i]) && isset($data[$i]['pinbuk'])){
                                                            $pinbuk = $data[$i]['pinbuk'];
                                                            if(isset($pinbuk[$item->id])){
                                                                $dkey = $data[$i]['year']."-".sprintf("%02d", $data[$i]['period']);
                                                                if(isset($pinbuk[$item->id][$dkey])){
                                                                    foreach($pinbuk[$item->id][$dkey] as $pb){
                                                                        if(!empty($pb['credit'])){
                                                                            $bl[$item->id] += $pb['credit'];
                                                                        } else {
                                                                            $bl[$item->id] -= $pb['debit'];
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }

                                                        $idr = (isset($bl[$item->id]) && $item->currency == "IDR") ? $bl[$item->id] : 0;
                                                        $usd = (isset($bl[$item->id]) && $item->currency != "IDR") ? $bl[$item->id] : 0;
                                                        $sum_IDR += $idr;
                                                        $sum_USD += $usd;
                                                    @endphp
                                                    <tr>
                                                        <td style="width: 60%">{{ $item->source }}</td>
                                                        <td align="right" style="width: 20%">
                                                            <span class="number end-balance-IDR-{{ $item->id }} ">
                                                                {{ number_format($idr, 2) }}
                                                            </span>
                                                        </td>
                                                        <td align="right" style="width: 20%">
                                                            <span class="number end-balance-USD-{{ $item->id }} ">
                                                                {{ number_format($usd, 2) }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td class="font-weight-bold font-size-h3" style="width: 60%">TOTAL</td>
                                                    <td align="right" class="font-weight-bold font-size-h3" style="width: 20%">
                                                        <span class="number end-balance-IDR-total">{{ number_format($sum_IDR, 2) }}</span>
                                                    </td>
                                                    <td align="right" class="font-weight-bold font-size-h3" style="width: 20%">
                                                        <span class="number end-balance-USD-total">{{ number_format($sum_USD, 2) }}</span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12">
                            @php
                                $lock_status = $data[$i]['locked'];
                                $btn_bg = ($lock_status == 0) ? "primary" : "danger";
                                $btn_icon = ($lock_status == 0) ? "lock-open" : "lock";
                                $label = ($lock_status == 0) ? "Lock" : "Unlock";
                            @endphp
                            <button type="button" onclick="_lock(this)" data-month-label="{{ date("F", strtotime(date("Y")."-".$i)) }}" data-lock="{{ $lock_status }}" data-month="{{ $i }}" data-year="{{ $data[$i]['year'] }}" class="btn btn-{{ $btn_bg }}">
                                <i class="fa fa-{{ $btn_icon }}"></i>
                                <span>
                                    {{ $label }}
                                </span> Cashflow {{ date("F", strtotime(date("Y")."-".$i)) }} {{ $data[$i]['year'] }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endfor
        </div>
        @endif
    </div>
@endsection

@section('custom_script')
    <script src="{{ asset("assets/jquery-number/jquery.number.js") }}"></script>
    <script>

        function printDiv(div_name)
        {

            var divToPrint=document.getElementById(div_name);
            $(divToPrint).find('table').attr('border', 1)

            var newWin=window.open('','Print-Window');

            newWin.document.open();

            newWin.document.write('<html><style>@media print {.footer {page-break-after: always;} .print-hide {display: none;} .print-show {display: block;}</style><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');

            newWin.document.close();

            setTimeout(function(){newWin.close();},10);

        }

        function _lock(e){
            var month = $(e).data('month')
            var year = $(e).data('year')
            var lock = $(e).data('lock')
            var month_label = $(e).data('month-label')

            var _label = "Lock"
            if(lock == 1){
                _label = "Unlock"
            }

            var btn = $(e)
            Swal.fire({
                title: 'Are you sure?',
                text: _label+" Cashflow "+month_label+" "+year,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{ route('finance.cf.lock') }}",
                        type : "post",
                        dataType : "json",
                        data : {
                            _token : "{{ csrf_token() }}",
                            month : month,
                            year : year,
                            lock : lock
                        },
                        beforeSend : function(){
                            $(e).prop('disabled', true).addClass('spinner spinner-right')
                            Swal.fire({
                                title: "Processing",
                                onOpen: function() {
                                    Swal.showLoading()
                                },
                                allowOutsideClick: false
                            })
                        },
                        success : function(response){
                            if(response.success){
                                var _i = btn.find("i")
                                var label = btn.find('span')
                                btn.data('lock', response.lock)
                                console.log(label)
                                if(response.lock == 1){
                                    btn.removeClass("btn-primary")
                                    btn.addClass("btn-danger")

                                    _i.removeClass("fa-unlock")
                                    _i.addClass("fa-lock")

                                    label.text('Unlock')
                                } else {
                                    btn.addClass("btn-primary")
                                    btn.removeClass("btn-danger")

                                    _i.addClass("fa-unlock")
                                    _i.removeClass("fa-lock")

                                    label.text('Lock')
                                }
                            }
                            swal.close()
                            btn.prop('disabled', false).removeClass('spinner spinner-right')
                        }
                    })
                }
            })
        }

        function _modal(type){
            $("#title-add").text(type.toUpperCase().replaceAll("_", " "))
            $("#type-hide").val(type)
        }

        function _modalChild(type, id){
            $("#title-add-child").text(type.toUpperCase().replaceAll("_", " "))
            $("#type-hide-child").val(id)
        }

        function _edit(id){
            $("#modalEditChild").modal('show')
            $.ajax({
                url : "{{ route('finance.cf.edit') }}/"+id,
                type : "get",
                success : function(response){
                    $("#modalEditChild .modal-content").html(response)
                    $("#modalEditChild .select2").select2({
                        width : "100%"
                    })
                }
            })
        }

        function _pdf(){
            Swal.fire({
                title: "Generating File",
                text: "proccess",
                onOpen: function() {
                    Swal.showLoading()
                },
                // allowOutsideClick: false
            })
            $.ajax({
                url : "{{ route('finance.cf.data') }}",
                type : "post",
                data : {
                    _token : "{{ csrf_token() }}",
                    _month : $("#mnth").val(),
                    _year : $("#year").val(),
                    _pdf : 1,
                },
                success : function(response){
                    swal.close()
                    if (response == 1) {
                        Swal.fire('Pdf', 'File has been created', 'success')
                    } else {
                        Swal.fire('Pdf', 'Failed to create file. Please contact your system administrator', 'error')
                    }
                }
            })
        }

        function _delete(id){
            $.ajax({
                url : "{{ route('finance.cf.delete') }}/"+id,
                type : "get",
                dataType : "json",
                beforeSend : function(){
                        Swal.fire({
                            title: "Deleting Data",
                            text: "Please wait",
                            // allowOutsideClick : false,
                            onOpen: function() {
                                Swal.showLoading()
                            }
                        })
                    },
                success : function(response){
                    swal.close()
                    if(response == 1){
                        location.reload()
                    } else {
                        Swal.fire('Error', "Can't delete data, this data has child! Please delete the child first!", 'error')
                    }
                }
            })
        }

        function _calculate_sub(){
            var sub_idr = $(".sub-IDR")

            sub_idr.each(function(){
                var id = $(this).data('id')
                console.log(id)
                var _child = $(".sub-IDR-"+id)
                console.log(_child)
                var total = 0
                _child.each(function(){
                    total += parseFloat($(this).text().replaceAll(",", ""))
                })

                $(this).number(total, 2)
            })
        }

        function _add_row(btn){
            var _body = $(btn).parents(".modal-body")

            _body.find("select.select2").select2('destroy')

            var fl = _body.find('fieldset').toArray()
            console.log(fl)

            var _clone = $(fl[0]).clone()

            var id_clone = _clone.attr('id')

            var _sel = _clone.find("select.select2")

            var _prj = _clone.find("select.prj")
            var _tc = _clone.find("select.tc")
            var _legend = _clone.find("legend")
            var fl_num = $(_body).find(".fl").length

            _clone.attr('id', id_clone + fl_num)

            var num = (parseInt(fl_num) + 1)
            _legend.text("Source " + num)
            _prj.attr('name', 'prj['+fl_num+']')
            _tc.attr('name', 'tc['+fl_num+'][]')

            var div_rm = _clone.find('.div-rm')
            var btn_remove = '<button type="button" onclick="_remove_row(this)" class="btn btn-icon btn-danger btn-sm"><i class="fa fa-times"></i></button>';
            div_rm.html(btn_remove)

            _body.append(_clone)

            _body.find("select.select2").select2({
                width : "100%",
                allowClear : true
            })
        }

        function _remove_row(btn){
            var fl = $(btn).parents('fieldset')
            fl.remove()
        }

        $(document).ready(function(){
            $("#btn-view").hide()

            $("#form-source").hide()
            // $(".number").number(true, 2)

            $("select.select2").select2({
                width : "100%"
            })

            $("#source").change(function(){
                $("#paper").select2({
                    ajax : {
                        url : "{{ route('finance.cf.find_source') }}?source=" + $("#source").val(),
                        dataType : "json",
                    }
                })
            })

            $("#sel-prj").select2({
                width: "100%",
                placeholder: "All Project",
                allowClear: true
            })
            $("#btn-search").click(function(){
                $(".number").number(0, 2)
                $.ajax({
                    url : "{{ route('finance.cf.data') }}",
                    type : "post",
                    dataType : "json",
                    data : {
                        _token : "{{ csrf_token() }}",
                        _month : $("#mnth").val(),
                        _year : $("#year").val()
                    },
                    beforeSend : function(){
                        Swal.fire({
                            title: "Processing Data!",
                            text: "Please wait to receive data!",
                            // allowOutsideClick : false,
                            onOpen: function() {
                                Swal.showLoading()
                            }
                        })
                    },
                    success : function(response){
                        var data = response.data
                        $("#btn-view").show()
                        $("#btn-view").attr('href', '{{ route('finance.cf.detail') }}?t='+$("#year").val()+"-"+$("#mnth").val())
                        for (const key in data) {
                            var _total = 0;
                            var dataCurr = data[key]
                            for (const curr in dataCurr) {
                                var _total_curr = 0
                                var data_bank = dataCurr[curr]
                                for (const i in data_bank) {
                                    $("."+key+"-balance-"+curr+"-"+i).number(data_bank[i], 2)

                                    $("#view-" + i).attr('href', '{{ route('finance.cf.view') }}/'+i+'?period='+response.period)
                                    $("#view-" + i).attr('target', '_blank')

                                    // console.log( '{{ route('finance.cf.view') }}/'+i+'?period='+response.period)
                                    // console.log(".view-" + i)
                                    _total_curr += parseFloat(data_bank[i])
                                }
                                $("."+key+"-balance-"+curr+"-total").number(_total_curr, 2)

                            }
                        }

                        _calculate_sub()

                        var beginInIDR = parseFloat($(".begin-balance-IDR-total").text().replaceAll(",", ""))
                        var _cash_inIDR = parseFloat($(".cash_in-balance-IDR-total").text().replaceAll(",", ""))
                        var _cash_outIDR = parseFloat($(".cash_out-balance-IDR-total").text().replaceAll(",", ""))

                        surplus_defIDR = beginInIDR + _cash_inIDR - _cash_outIDR
                        var tr = $("#surplus-defisit-IDR").parent()
                        if(surplus_defIDR < 0){
                            tr.addClass('text-danger')
                        } else if(surplus_defIDR > 0) {
                            tr.addClass('text-success')
                        } else {
                            tr.removeClass("text-danger text-success")
                        }
                        $("#surplus-defisit-IDR").number(surplus_defIDR, 2)

                        var beginInUSD = parseFloat($(".begin-balance-USD-total").text().replaceAll(",", ""))
                        var _cash_inUSD = parseFloat($(".cash_in-balance-USD-total").text().replaceAll(",", ""))
                        var _cash_outUSD = parseFloat($(".cash_out-balance-USD-total").text().replaceAll(",", ""))

                        surplus_defUSD = beginInUSD + _cash_inUSD - _cash_outUSD
                        var tr = $("#surplus-defisit-USD").parent()
                        if(surplus_defUSD < 0){
                            tr.addClass('text-danger')
                        } else if(surplus_defUSD > 0) {
                            tr.addClass('text-success')
                        } else {
                            tr.removeClass("text-danger text-success")
                        }
                        $("#surplus-defisit-USD").number(surplus_defUSD, 2)
                        swal.close()
                    }
                })
            })
        })
    </script>
@endsection
