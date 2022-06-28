@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">Cash Flow</h3>
            <div class="card-toolbar">
                <div class="btn-group">

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-8 mx-auto">
            <div class="card card-custom gutter-b">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mx-auto">
                            <form class="form" action="{{route('finance.cf.data')}}" method="post">
                                @csrf
                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <select name="project[]" multiple class="form-control" id="sel-prj">
                                            @foreach ($projects as $id => $name)
                                                <option value="{{ $name->id }}" {{ (!empty($prj_selected) && in_array($name->id, $prj_selected)) ? "SELECTED" : "" }}>{{ $name->prj_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="date" name="from_date" id="start-date" class="form-control mr-3" value="{{(isset($from)) ? $from : date('Y')."-".date('m')."-01"}}">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="date" name="to_date" id="end-date" class="form-control" value="{{(isset($to)) ? $to : date('Y')."-".date('m')."-".date('t')}}">
                                    </div>
                                    <div class="col-md-3">
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <button type="submit" id="btn-search" class="btn btn-primary" ><i class="fa fa-search"></i>Search</button>
                                            @if (!empty($from))
                                                <button type="button" id="btn-pdf" onclick="_pdf()" name="pdf" value="1" class="btn btn-info"><i class="fa fa-file-pdf"></i></button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-8 mx-auto">
            <div class="card card-custom gutter-b">
                <div class="card-header">
                    <h3 class="card-title">Saldo Awal</h3>
                    <div class="card-toolbar">
                        <div class="btn-group">
                            @php
                                $tc = 'null';
                                if (isset($setting['saldo_awal'])) {
                                    if (!empty($setting['saldo_awal'])) {
                                        $tc = "'".$setting['saldo_awal']."'";
                                    }
                                }
                            @endphp
                            <button type="button" class="btn btn-icon btn-sm btn-light-dark" onclick="_modal('saldo_awal', {{ $tc }})"><i class="fa fa-cog"></i></button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @php
                        $sum_saldo_awal = 0;
                        $sum_saldo_awal_usd = 0;
                    @endphp
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-hover table-responsive-sm">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 70%">Description</th>
                                        <th class="text-center">Amount (IDR)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($data['saldo_awal']))
                                        @foreach ($data['saldo_awal'] as $i => $item)
                                            <tr>
                                                <td>
                                                   {!! $item['description'] !!}
                                                </td>
                                                <td align="right">
                                                    {{ number_format($item['amount'], 2) }}
                                                </td>
                                            </tr>
                                            @php
                                                $sum_saldo_awal += $item['amount'];
                                            @endphp
                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td>
                                            <span class="font-weight-bold font-size-h3">Total</span>
                                        </td>
                                        <td align="right" nowrap="nowrap">
                                            <span class="font-weight-bold font-size-h3">
                                                IDR {{ number_format($sum_saldo_awal, 2) }}
                                            </span>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-8 mx-auto">
            <div class="card card-custom gutter-b">
                <div class="card-header">
                    <h3 class="card-title">Income</h3>
                    <div class="card-toolbar">
                        <div class="btn-group">
                            @php
                                $tc = 'null';
                                if (isset($setting['income'])) {
                                    if (!empty($setting['income'])) {
                                        $tc = "'".$setting['income']."'";
                                    }
                                }
                            @endphp
                            <button type="button" class="btn btn-icon btn-sm btn-light-dark" onclick="_modal('income', {{ $tc }})"><i class="fa fa-cog"></i></button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-hover table-responsive-sm">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 70%">Description</th>
                                        <th class="text-center">Amount (IDR)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $sum_income = 0;
                                        $sum_income_usd = 0;
                                    @endphp
                                    @if (isset($data['income']))
                                        @foreach ($data['income'] as $i => $item)
                                            <tr>
                                                <td>
                                                    {!! $item['description'] !!}
                                                </td>
                                                <td align="right">
                                                    {{ number_format($item['amount'], 2) }}
                                                </td>
                                            </tr>
                                            @php
                                                $sum_income += $item['amount'];
                                            @endphp
                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td>
                                            <span class="font-weight-bold font-size-h3">Total</span>
                                        </td>
                                        <td align="right" nowrap="nowrap">
                                            <span class="font-weight-bold font-size-h3">
                                                IDR {{ number_format($sum_income, 2) }}
                                            </span>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-8 mx-auto">
            <div class="card card-custom gutter-b">
                <div class="card-header">
                    <h3 class="card-title">Expense</h3>
                    <div class="card-toolbar">
                        <div class="btn-group">
                            @php
                                $tc = 'null';
                                if (isset($setting['expense'])) {
                                    if (!empty($setting['expense'])) {
                                        $tc = "'".$setting['expense']."'";
                                    }
                                }
                            @endphp
                            <button type="button" class="btn btn-icon btn-sm btn-light-dark" onclick="_modal('expense', {{ $tc }})"><i class="fa fa-cog"></i></button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-hover table-responsive-sm">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 70%">Description</th>
                                        <th class="text-center">Amount (IDR)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $sum_expense = 0;
                                        $sum_expense_usd = 0;
                                    @endphp
                                    @if (isset($data['expense']))
                                        @foreach ($data['expense'] as $i => $item)
                                            <tr>
                                                <td>
                                                    {!! $item['description'] !!}
                                                </td>
                                                <td align="right">
                                                    {{ number_format($item['amount'], 2) }}
                                                </td>
                                            </tr>
                                            @php
                                                $sum_expense += $item['amount'];
                                            @endphp
                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td>
                                            <span class="font-weight-bold font-size-h3">Total</span>
                                        </td>
                                        <td align="right" nowrap="nowrap">
                                            <span class="font-weight-bold font-size-h3">
                                                IDR {{ number_format($sum_expense, 2) }}
                                            </span>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-8 mx-auto">
            <div class="card card-custom gutter-b">
                <div class="card-header">
                    <h3 class="card-title">Saldo Akhir</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-hover table-responsive-sm">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 70%">Description</th>
                                        <th class="text-center">Amount (IDR)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $sum_saldo_akhir = 0;
                                        $sum_saldo_akhir_usd = 0;
                                    @endphp
                                    @if (isset($data['saldo_akhir']))
                                        @foreach ($data['saldo_akhir'] as $i => $item)
                                            <tr>
                                                <td>
                                                    {!! $item['description'] !!}
                                                </td>
                                                <td align="right">
                                                    {{ number_format($item['amount'], 2) }}
                                                </td>
                                            </tr>
                                            @php
                                                $sum_saldo_akhir += $item['amount'];
                                            @endphp
                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td>
                                            <span class="font-weight-bold font-size-h3">Total</span>
                                        </td>
                                        <td align="right" nowrap="nowrap">
                                            <span class="font-weight-bold font-size-h3">
                                                IDR {{ number_format($sum_saldo_akhir, 2) }}
                                            </span>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalSetting" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><span id="title-add"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{URL::route('finance.cf.settings')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">{{ !empty(\Session::get('company_tc_name')) ? \Session::get('company_tc_name') : "Transaction Code" }}</label>
                            <div class="col-md-9">
                                <select name="tc[]" class="form-control select2" multiple id="tc-sel" required>
                                    @foreach($coa as $value)
                                        <option value="{{$value->id}}">{{"[".$value->code."] ".$value->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="type" id="type-hide">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        function _modal(type, selected){
            $("#modalSetting").modal('show')
            $("#title-add").text(type.replaceAll('_', " ").toUpperCase())
            $("#type-hide").val(type)
            $("#tc-sel").val(null).trigger('change')
            if (selected !== null) {
                jsSelected = JSON.parse(selected)
                console.log(jsSelected)
                $("#tc-sel").val(jsSelected).trigger('change')
            }
        }

        function _pdf(){
            Swal.fire({
                title: "Generating File",
                text: "proccess",
                onOpen: function() {
                    Swal.showLoading()
                },
                allowOutsideClick: false
            })
            $.ajax({
                url : "{{ route('finance.cf.pdf') }}",
                type : "post",
                data : {
                    _token : "{{ csrf_token() }}",
                    from_date : $("#start-date").val(),
                    to_date : $("#end-date").val(),
                    projects : $("#sel-prj").val()
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

        $(document).ready(function(){
            $("#sel-prj").select2({
                width: "100%",
                placeholder: "All Project",
                allowClear: true
            })
            $("#tc-sel").select2({
                width: "100%",
                placeholder: "Select {{ !empty(\Session::get('company_tc_name')) ? \Session::get('company_tc_name') : "Transaction Code" }}",
                allowClear: true
            })
        })
    </script>
@endsection
