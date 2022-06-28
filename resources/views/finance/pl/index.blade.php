@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                Profit & Loss
            </div>
            <div class="card-toolbar">
                <a href="{{ route('pl.list') }}" class="btn btn-icon btn-sm btn-success"><i class="fa fa-arrow-left"></i></a>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <form action="{{route('pl.find')}}" method="post">
            @csrf
            <div class="row">
                <div class="col-md-8 mx-auto row">
                    <div class="col-md-3">
                        <select name="projects[]" multiple class="form-control" id="sel-prj">
                            @foreach ($project as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" id="start-date" name="start" class="form-control mr-3" value="{{date('Y')."-01-01"}}">
                    </div>
                    <div class="col-md-2">
                        <input type="date" id="end-date" name="end" class="form-control" value="{{date('Y')."-".date('m')."-".date('t')}}">
                    </div>
                    <div class="col-md-5">
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <button type="submit" name="submit" value="search" class="btn btn-primary"><i class="fa fa-search"></i>Search</button>
                            <button type="submit" name="submit" value="pdf" class="btn btn-info"><i class="fa fa-file-pdf"></i></button>
                            <button type="button" id="btn-search" class="btn btn-light-dark ml-2" data-toggle="modal" data-target="#modalSetting"><i class="fa fa-cog"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            </form>
            <div class="row mt-10">
                <div class="col-md-8 mx-auto">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <table class="table table-bordered table-hover display font-size-sm" id="table-data" style="margin-top: 13px !important; width: 100%;">
                            <thead>
                            <tr>
                                <th nowrap="nowrap" class="text-left">Code</th>
                                <th nowrap="nowrap" class="text-center">Value</th>
                                <th nowrap="nowrap" class="text-center">Type</th>
                                <th nowrap="nowrap" class="text-center">Total</th>
                            </tr>
                            </thead>
                            <tbody>
                                @php
                                    $rate = (!empty($setting->tax)) ? $setting->tax : 0;
                                @endphp
                                @foreach ($data as $key => $item)
                                    @php
                                        $total[$key] = 0;
                                    @endphp
                                    @if (is_array($item))
                                        <tr class="bg-secondary">
                                            <td colspan="4" class="font-weight-bold">{{ str_replace("_", " ", ucwords($key)) }}</td>
                                        </tr>
                                        @foreach ($item as $i => $val)
                                            @if (!in_array($i, $oe))
                                                @php
                                                    $sum = abs(array_sum($val['amount']));
                                                    $total[$key] += $sum
                                                @endphp
                                                <tr>
                                                    <td colspan="3">
                                                        @php
                                                            $c = ltrim(explode("]", $val['code'])[0], "[");
                                                        @endphp
                                                        <a href="{{ route('report.coa.view',  $c) }}">{{ $val['code'] }}</a>
                                                        <a href="{{ route('export.tc', ["type" => 'profit-loss', 'code' => $c]) }}" target="_blank" class="btn btn-xs btn-success btn-icon"><i class="fa fa-file-csv"></i></a>
                                                    </td>
                                                    <td align="right">
                                                        {{ number_format($sum, 2) }}
                                                    </td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td colspan="4" class="font-weight-bold"><i class="flaticon2-next"></i> {{ str_replace("_", " ", ucwords($i)) }}</td>
                                                </tr>
                                                @if (count($val) > 0)
                                                    @foreach ($val as $ival)
                                                        @php
                                                            $sum = array_sum($ival['amount']);
                                                            $total[$key] += $sum;
                                                        @endphp
                                                        <tr>
                                                            <td colspan="3">
                                                                @php
                                                                    $c = ltrim(explode("]", $ival['code'])[0], "[");
                                                                @endphp
                                                                <a href="{{ route('report.coa.view',  $c) }}">{{ $ival['code'] }}</a>
                                                                <a href="{{ route('export.tc', ["type" => 'profit-loss', 'code' => $c]) }}" target="_blank" class="btn btn-xs btn-success btn-icon"><i class="fa fa-file-csv"></i></a>
                                                            </td>
                                                            <td align="right">{{number_format($sum, 2)}}</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="3">No Data</td>
                                                        <td align="right">{{ number_format(0, 2) }}</td>
                                                    </tr>
                                                @endif
                                            @endif
                                        @endforeach
                                        <tr>
                                            <td colspan="3" class="font-weight-bold">Total {{ str_replace("_", " ", ucwords($key)) }}</td>
                                            <td align="right" class="font-weight-bold">{{ number_format($total[$key], 2) }}</td>
                                        </tr>
                                    @else
                                    <tr class="bg-secondary">
                                        @php
                                            $total[$key] = eval("return $item;");
                                        @endphp
                                        <td colspan="3" class="font-weight-bold">{{ str_replace("_", " ", ucwords($key)) }}</td>
                                        <td align="right" class="font-weight-bold">
                                            {{ number_format($total[$key], 2) }}
                                            @if ($key == "laba_setelah_pajak")
                                                <input type="hidden" id="laba_setelah_pajak" value="{{ $total[$key] }}">
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalSetting" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Profit & Loss Setting</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{URL::route('pl.setting')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <hr>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Sales</label>
                            <div class="col-md-9">
                                <select name="oi[]" class="form-control select2" multiple id="" required>
                                    <option value="">&nbsp;</option>
                                    @foreach($coa as $value)
                                        <option value="{{$value->id}}"
                                                @if($setting != null && !empty($setting->operating_income))
                                                    @foreach(json_decode($setting->operating_income) as $item)
                                                        {{($item == $value->id) ? "SELECTED" : ""}}
                                                    @endforeach
                                                @endif
                                        >{{"[".$value->code."] ".$value->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Cost of Sales</label>
                            <div class="col-md-9">
                                <select name="cs[]" class="form-control select2" multiple id="" required>
                                    <option value="">&nbsp;</option>
                                    @foreach($coa as $value)
                                        <option value="{{$value->id}}"
                                                @if($setting != null && !empty($setting->cost_sales))
                                                    @foreach(json_decode($setting->cost_sales) as $item)
                                                        {{($item == $value->id) ? "SELECTED" : ""}}
                                                    @endforeach
                                                @endif
                                        >{{"[".$value->code."] ".$value->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Operating Expense</label>
                            <div class="col-md-9">
                                @php
                                    $loe = [];
                                    $seoe = "";
                                    if(!empty($setting)){
                                        $seoe = $setting->operating_expense;
                                        if(!empty($seoe)){
                                            $loe = json_decode($setting->operating_expense, true);
                                        }
                                    }
                                @endphp
                                @foreach ($oe as $item)
                                    @php
                                        $litem = (isset($loe[$item])) ? $loe[$item] : [];
                                    @endphp
                                    <div class="row mb-2">
                                        <label class="col-form-label col-md-3">{{ str_replace("_", " ", strtoupper($item)) }}</label>
                                        <div class="col-md-9">
                                            <select name="oe[{{ $item }}][]" class="form-control select2" multiple id="" required>
                                                <option value="">&nbsp;</option>
                                                @foreach($coa as $value)
                                                    <option value="{{$value->id}}" {{ (in_array($value->id, $litem)) ? "selected" : "" }} >{{"[".$value->code."] ".$value->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <hr>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Other Incomes</label>
                            <div class="col-md-9">
                                <select name="oti[]" class="form-control select2" multiple id="" required>
                                    <option value="">&nbsp;</option>
                                    @foreach($coa as $value)
                                        <option value="{{$value->id}}"
                                            @if($setting != null && !empty($setting->other_income))
                                                @foreach(json_decode($setting->other_income) as $item)
                                                    {{($item == $value->id) ? "SELECTED" : ""}}
                                                @endforeach
                                            @endif
                                        >{{"[".$value->code."] ".$value->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Other Expenses</label>
                            <div class="col-md-9">
                                <select name="ote[]" class="form-control select2" multiple id="" required>
                                    <option value="">&nbsp;</option>
                                    @foreach($coa as $value)
                                        <option value="{{$value->id}}"
                                            @if($setting != null && !empty($setting->other_expense))
                                                @foreach(json_decode($setting->other_expense) as $item)
                                                    {{($item == $value->id) ? "SELECTED" : ""}}
                                                @endforeach
                                            @endif
                                        >{{"[".$value->code."] ".$value->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Tax</label>
                            <div class="col-md-9 col-form-label">
                                <div class="radio-inline">
                                    <label class="radio radio-rounded">
                                        <input type="radio" value="25" {{($setting != null && $setting->tax == 25) ? "checked" : ""}} name="tax"/>
                                        <span></span>
                                        25 %
                                    </label>
                                    <label class="radio radio-rounded">
                                        <input type="radio" value="0.5" {{($setting != null && $setting->tax == 0.5) ? "checked" : ""}} name="tax"/>
                                        <span></span>
                                        0.5 %
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div id="coa-target"></div>
                    </div>
                    <div class="modal-footer">
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
    <link href="{{asset('theme/jquery-ui/jquery-ui.css')}}" rel="Stylesheet">
    <script src="{{asset('theme/jquery-ui/jquery-ui.js')}}"></script>
    <script>
        // function _search(pdf){
        //     Swal.fire({
        //         title: "Searching Data",
        //         text: "proccess",
        //         onOpen: function() {
        //             Swal.showLoading()
        //         },
        //         // allowOutsideClick: false
        //     })
        //     $.ajax({
        //         url: "{{route('pl.find')}}",
        //         type: "post",
        //         dataType: "json",
        //         cache: false,
        //         data: {
        //             "_token" : "{{csrf_token()}}",
        //             'start' : $("#start-date").val(),
        //             'end' : $("#end-date").val(),
        //             'projects' : $("#sel-prj").val(),
        //             'pdf' : pdf
        //         },
        //         success: function(response){
        //             swal.close()
        //             $('#table-data').DataTable().clear();
        //             $('#table-data').DataTable().destroy();
        //             var t = $('#table-data').DataTable({
        //                 'searching' : false,
        //                 'paging': false,
        //                 'ordering': false,
        //                 'data': response.data,
        //                 "bInfo" : false,
        //                 "columnDefs": [
        //                     { "visible": false, "targets": 2 },
        //                     {
        //                         'targets': [1, 3],
        //                         'className': "text-right"
        //                     }
        //                 ],
        //                 "drawCallback": function ( settings ) {
        //                     var api = this.api();
        //                     var rows = api.rows( {page:'current'} ).nodes();
        //                     var last=null;

        //                     api.column(2, {page:'current'} ).data().each( function ( group, i ) {
        //                         if ( last !== group ) {
        //                             $(rows).eq( i ).before(
        //                                 '<tr class="group"><td colspan="5">'+group+'</td></tr>'
        //                             )

        //                             last = group
        //                         }
        //                     } )
        //                 },
        //             })
        //         }
        //     })
        // }
        $(document).ready(function () {
            $("#sel-prj").select2({
                width: "100%",
                placeholder : "All Project",
                allowClear : true
            })
            // $('#table-data').DataTable({
            //     'searching' : false,
            //     'paging': false,
            //     'ordering': false,
            //     "responsive": true,
            //     "bInfo" : false,
            //     "columnDefs": [
            //         // { "visible": false, "targets": 2 }
            //     ],
            //     fixedHeader: true,
            //     fixedHeader: {
            //         headerOffset: 90
            //     }
            // })

            $("#modalSetting select.select2").select2({
                width: "100%"
            })


            @if($save == 1)
                $.ajax({
                    url : "{{ route('pl.update') }}",
                    type : "POST",
                    dataType : "JSON",
                    data : {
                        _token : "{{ csrf_token() }}",
                        from : $("#start-date").val(),
                        to : $("#end-date").val(),
                        total_val : $("#laba_setelah_pajak").val()
                    },
                    success : function(response){

                    }
                })
            @endif

            var val = []
            // val['data'] = src

        });

        function loop_data(t, arguments){
            for (const argumentsKey in arguments) {
                var sum = 0
                for (let i = 0; i < arguments[argumentsKey].amount.length; i++) {
                    sum += parseInt(arguments[argumentsKey].amount[i])
                }

                t.row.add([
                    arguments[argumentsKey].code,
                    sum.toFixed(2),
                    ''
                ]).draw(false)
            }


        }
    </script>
@endsection
