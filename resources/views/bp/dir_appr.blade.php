@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Finance Division Approval</h3>
            </div>
            <div class="card-toolbar">

            </div>
        </div>
        <div class="card-body">
            <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                    <thead>
                    <tr>
                        <th>Project Code</th>
                        <th>Keperluan Pekerjaan</th>
                        <th class="text-center">Currency</th>
                        <th class="text-center">Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($price as $key => $val)
                        <tr>
                            <td>{{$val->prj_code }}</td>
                            <td>{{$val->nama_prj }}</td>
                            <td class="text-center">{{$val->currency}}</td>
                            <td class="text-center">{{number_format($val->nilai_jaminan,2)}}</td>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <br>
            <br>
            <div class="row">
                <div class="col-md-6">
                    <form action='{{route('bp.submitappr')}}' method='POST'>
                        @csrf
                        <input type="hidden" name="code" value="{{$code}}">
                        <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                            <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                                <thead>
                                <tr>
                                    <th class="text-left">Item Name</th>
                                    <th class="text-center">Currency</th>
                                    <th class="text-center">Amount</th>
                                    <th class="text-center">Bank Source</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($detail as $key => $value)
                                    <tr>
                                        <td>{{$value->item_name}}</td>
                                        <td class="text-center">
                                            {{$value->currency}}
                                            <input type='hidden' name='detail_id_{{$value->item_name}}' value='{{$value->id_main}}'>
                                        </td>
                                        <td class="text-center">
                                            <input type='number' name='price_{{$value->item_name}}' id='price_{{$value->item_name}}' value="{{$value->request_amount}}" class='form-control' />
                                            <input type="hidden" name="jobdesc_{{$value->item_name}}" id="jobdesc_{{$value->item_name}}" @if($value->item_name == 'AMOUNT') @if($code == 'actual') value='Return Bond ID : {{$value->prj_code}}/{{strtoupper(\Session::get('company_tag'))}}/BPB/{{date("m/Y")}} Guarantee Fund' @else value='Pembayaran Bid/Performance Bond ID : {{$value->prj_code}}/{{strtoupper(\Session::get('company_tag'))}}/BPB/{{date("m/Y")}} Guarantee Fund' @endif
                                            @else
                                            value='Administrasi Bid/Performance Bond ID : {{$value->prj_code}}/{{strtoupper(\Session::get('company_tag'))}}/BPB/{{date("m/Y")}} Administration Fund'
                                            @endif >
                                            <input type="hidden" name="currency_{{$value->item_name}}" id="currency_{{$value->item_name}}" value="{{$value->currency}}">
                                        </td>
                                        <td class="text-center">
                                            <div class="form-group row" id="opt">
                                                <div class="col-md-5">
                                                    <select name='source_{{$value->item_name}}' id='source_{{$value->item_name}}' class='form-control'>
                                                        <option value='SP' selected='selected'>From
                                                            {{strtoupper(\Session::get('company_tag'))}}</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <select name='{{$value->item_name}}' id='{{$value->item_name}}' class='form-control' style='width: 200px'>
                                                        @foreach($sources as $key2 =>$valS)
                                                            <option value="{{$valS->id}}">{{$valS->source}}</option>
{{--                                                            @if($valS->currency == $value->currency)--}}
{{--                                                                --}}
{{--                                                            @endif--}}
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <input type='hidden' name='main_id' value='{{$price[0]->id}}'>
                        <input type='submit' name='submit' value='Approve' class="btn btn-success">
                        <input type='submit' name='reject' value='Reject' class="btn btn-danger">
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('custom_script')
@endsection
