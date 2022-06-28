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
                        <th>Project Name</th>
                        <th class="text-center">Currency</th>
                        <th class="text-center">Nilai Jaminan</th>
                        <th class="text-center">Nomor Tender</th>
                        <th class="text-center">Nomor Bond</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($price as $key => $val)
                        <tr>
                            <td>{{ $val->prj_code }}</td>
                            <td>{{$val->nama_prj }}</td>
                            <td class="text-center">{{$val->currency}}</td>
                            <td class="text-center">{{number_format($val->nilai_jaminan,2)}}</td>
                            <td class="text-center">{{$val->no_tender}}</td>
                            <td class="text-center">{{$val->no_bond }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <br>
            <br>
            <div class="row">
                <div class="col-md-6">
                    <form action='{{route('bp.finDivAppr')}}' method='POST'>
                        @csrf
                        <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                            <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                                <thead>
                                <tr>
                                    <th class="text-left">Item Name</th>
                                    <th class="text-center">Currency</th>
                                    <th class="text-center">Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($detail as $key => $value)
                                    <tr>
                                        <td>{{$value->item_name}}</td>
                                        <td class="text-center">
                                            @if($value->currency != null || $value->currency != '')
                                                {{$value->currency}}
                                            @else
                                                <input type='hidden' name='detail_id_{{$value->item_name}}' value='{{$value->id_main}}'>
                                                <select name='adm_currency_{{$value->item_name}}' id='adm_currency_{{$value->item_name}}' class='form-control'>
                                                    <option value='IDR'>IDR. </option>
                                                    <option value='USD'>USD. </option>
                                                </select>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($value->request_amount == null || $value->request_amount == 0)
                                                <input type='text' name='{{$value->item_name}}' id='{{$value->item_name}}' class='form-control number' />
                                            @else
                                                {{number_format($value->request_amount,2)}}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($status != "view")
                        <input type='hidden' name='main_id' value='{{$price[0]->id}}'>
                        <input type='submit' name='submit' value='Approve' class="btn btn-success">
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom_script')
<script src="{{asset('assets/jquery-number/jquery.number.js')}}"></script>

<script>
    $(document).ready(function () {
        $("select.form-control").select2({
            width: "100%"
        })

    });

    $(".number").number(true, 2)
</script>
@endsection
