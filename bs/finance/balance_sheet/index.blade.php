@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                Balance Sheet <br>
                Period: {{date('d F Y', strtotime(date('Y')."-".date('m')."-01"))}} - {{date('d F Y', strtotime(date('Y')."-".date('m')."-".date('t')))}}
            </div>
            <div class="card-toolbar">

                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <form class="form" action="{{route('bs.find')}}" method="post">
                @csrf
                <div class="form-group row">
                    <div class="col-md-2"></div>
                    <div class="col-md-4">
                        <input type="date" name="from_date" id="start-date" class="form-control mr-3" value="{{date('Y')."-".date('m')."-01"}}">
                    </div>
                    <div class="col-md-4">
                        <input type="date" name="to_date" id="end-date" class="form-control" value="{{date('Y')."-".date('m')."-".date('t')}}">
                    </div>
                    <div class="col-md-2">
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <button type="submit" id="btn-search" class="btn btn-primary" ><i class="fa fa-search"></i>Search</button>
                            {{--                        <button type="button" id="btn-search" class="btn btn-light-dark ml-2" data-toggle="modal" data-target="#modalSetting"><i class="fa fa-cog"></i></button>--}}
                        </div>
                    </div>

                </div>
            </form>
            <div class="row">

            </div>
        </div>
    </div>
    <div class="row mt-10">
        <div class="col-md-8 mx-auto">
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card card-custom card-stretch">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label">Assets</h3>
                    </div>
                    <div class="card-toolbar">
                        <button type="button" id="btn-search" class="btn btn-light-dark ml-2" data-toggle="modal" data-target="#modalSettingAsset">
                            <i class="fa fa-cog"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if(!isset($asset))
                        <table id="table_asset">
                            <tr>
                                <th></th>
                                <th></th>
                            </tr>
                        </table>
                    @else
                        <table id="table_asset" class="table table-sm" border="0">
                            <tbody>
                            @php
                                $totalkiri = 0.00;
                                $sumasset = 0.00;
                            @endphp
                            @for($i = 0; $i<count($asset); $i++)
                                <tr>
                                    <td>{{$asset[$i][0]}}</td>
                                    @php
                                        /** @var TYPE_NAME $sumasset */
                                        /** @var TYPE_NAME $asset */
                                        /** @var TYPE_NAME $i */
                                        $sumasset += intval($asset[$i][1]);
                                    @endphp
                                    <td align="right">{{number_format($asset[$i][1],2)}}</td>
                                </tr>
                            @endfor
                            @php
                                /** @var TYPE_NAME $totalkiri */
                                /** @var TYPE_NAME $sumasset */
                                $totalkiri += $sumasset;
                            @endphp
                            </tbody>
                        </table>
                    @endif

                </div>

            </div>
        </div>

        <div class="col-lg-6">
            <div class="card card-custom card-stretch">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label">Liabilitiy</h3>
                    </div>
                    <div class="card-toolbar">
                        <button type="button" id="btn-search" class="btn btn-light-dark ml-2" data-toggle="modal" data-target="#modalSettingLia"><i class="fa fa-cog"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    @if(!isset($liability))
                        <table id="table_liability">
                            <tr>
                                <th></th>
                                <th></th>
                            </tr>
                        </table>
                    @else
                        <table id="table_liability" class="table table-sm" border="0">
                            <tbody>
                            @php
                                $totalkanan = 0.00;
                                $sumlia = 0.00;
                            @endphp
                            @for($i = 0; $i<count($liability); $i++)
                                <tr>
                                    <td>{{$liability[$i][0]}}</td>
                                    @php
                                        /** @var TYPE_NAME $sumlia */
                                        /** @var TYPE_NAME $liability */
                                        /** @var TYPE_NAME $i */
                                        $sumlia += intval($liability[$i][1]);
                                    @endphp
                                    <td align="right">{{number_format($liability[$i][1],2)}}</td>
                                </tr>
                            @endfor
                            @php
                                /** @var TYPE_NAME $sumlia */
                                /** @var TYPE_NAME $totalkanan */
                                $totalkanan += $sumlia;
                            @endphp
                            </tbody>
                        </table>
                    @endif
                </div>

                <div class="card-footer d-flex justify-content-between">
                    <h3>Sub Total</h3>
                    @if(!isset($liability))
                        <h3>-</h3>
                    @else
                        <h3>{{number_format($sumlia,2)}}</h3>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-1"></div>
    <div class="row">
        <div class="col-lg-6">
            <div class="card card-custom card-stretch">
                <div class="card-footer d-flex justify-content-between">
                    <h3>Sub Total</h3>
                    @if(!isset($asset))
                        <h3>-</h3>
                    @else
                        <h3>{{number_format($sumasset,2)}}</h3>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card card-custom card-stretch">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label">Equity</h3>
                    </div>
                    <div class="card-toolbar">
                        <button type="button" id="btn-search" class="btn btn-light-dark ml-2" data-toggle="modal" data-target="#modalSettingEq"><i class="fa fa-cog"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    @if(!isset($equity))
                        <table id="table_liability">
                            <tr>
                                <th></th>
                                <th></th>
                            </tr>
                        </table>
                    @else
                        <table id="table_equity" class="table table-sm" border="0">
                            <tbody>
                            @php
                                $sumeq = 0.00;
                            @endphp
                            @for($i = 0; $i<count($equity); $i++)
                                <tr>
                                    <td>{{$equity[$i][0]}}</td>
                                    @php
                                        /** @var TYPE_NAME $sumeq */
                                        /** @var TYPE_NAME $equity */
                                        /** @var TYPE_NAME $i */
                                        $sumeq += intval($equity[$i][1]);
                                    @endphp
                                    <td align="right">{{number_format($equity[$i][1],2)}}</td>
                                </tr>
                            @endfor
                            @php
                                /** @var TYPE_NAME $sumeq */
                                /** @var TYPE_NAME $totalkanan */
                                $totalkanan += $sumeq;
                            @endphp
                            </tbody>
                        </table>
                    @endif
                </div>

                <div class="card-footer d-flex justify-content-between">
                    <h3>Sub Total</h3>
                    @if(!isset($equity))
                        <h3>-</h3>
                    @else
                        <h3>{{number_format($sumeq,2)}}</h3>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-1"></div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card card-custom card-stretch">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label">Total</h3>
                    </div>
                    <div class="card-toolbar">
                        @if(!isset($totalkiri))
                            <h3>-</h3>
                        @else
                            <h3>{{number_format($totalkiri,2)}}</h3>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card card-custom card-stretch">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label">Total</h3>
                    </div>
                    <div class="card-toolbar">
                        @if(!isset($totalkanan))
                            <h3>-</h3>
                        @else
                            <h3>{{number_format($totalkanan,2)}}</h3>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalSettingAsset" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Balance Sheet Setting (Assets)</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{URL::route('bs.setting')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <hr>
                        <input type="hidden" name="asset" value="1">
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-right">Assets</label>
                            <div class="col-md-10">
                                <select name="assets[]" class="form-control select2" multiple id="" required>
                                    <option value="">&nbsp;</option>
                                    @foreach($coa as $value)
                                        <option value="{{$value->id}}"
                                        @if($setting != null)
                                            @foreach(json_decode($setting->assets) as $item)
                                                {{($item == $value->id) ? "SELECTED" : ""}}
                                                @endforeach
                                            @endif
                                        >{{"[".$value->code."] ".$value->name}}</option>
                                    @endforeach
                                </select>
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
    <div class="modal fade" id="modalSettingLia" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Balance Sheet Setting (Liability)</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{URL::route('bs.setting')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <hr>
                        <input type="hidden" name="lia" value="1">

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-right">Liability</label>
                            <div class="col-md-10">
                                <select name="liablity[]" class="form-control select2" multiple id="" required>
                                    <option value="">&nbsp;</option>
                                    @foreach($coa as $value)
                                        <option value="{{$value->id}}"
                                        @if($setting != null)
                                            @foreach(json_decode($setting->liability) as $item)
                                                {{($item == $value->id) ? "SELECTED" : ""}}
                                                @endforeach
                                            @endif
                                        >{{"[".$value->code."] ".$value->name}}</option>
                                    @endforeach
                                </select>
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
    <div class="modal fade" id="modalSettingEq" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Balance Sheet Setting (Equity)</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{URL::route('bs.setting')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <hr>
                        <input type="hidden" name="eq" value="1">
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-right">Equity</label>
                            <div class="col-md-10">
                                <select name="equity[]" class="form-control select2" multiple id="" required>
                                    <option value="">&nbsp;</option>
                                    @foreach($coa as $value)
                                        <option value="{{$value->id}}"
                                        @if($setting != null)
                                            @foreach(json_decode($setting->equity) as $item)
                                                {{($item == $value->id) ? "SELECTED" : ""}}
                                                @endforeach
                                            @endif
                                        >{{"[".$value->code."] ".$value->name}}</option>
                                    @endforeach
                                </select>
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
        $(document).ready(function () {
            {{--$('#table-data').DataTable({--}}
            {{--    "responsive": true,--}}
            {{--    "columnDefs": [--}}
            {{--        { "visible": false, "targets": 2 }--}}
            {{--    ],--}}
            {{--})--}}
            {{--$("#btn-search").click(function(){--}}
            {{--    $.ajax({--}}
            {{--        url: "{{route('bs.find')}}",--}}
            {{--        type: "post",--}}
            {{--        dataType: "json",--}}
            {{--        cache: false,--}}
            {{--        data: {--}}
            {{--            "_token" : "{{csrf_token()}}",--}}
            {{--            'start' : $("#start-date").val(),--}}
            {{--            'end' : $("#end-date").val(),--}}
            {{--        },--}}
            {{--        success: function(response){--}}
            {{--            console.log(response)--}}

            {{--        }--}}
            {{--    })--}}
            {{--})--}}

            $("#modalSettingAsset select.select2").select2({
                width: "100%"
            })
            $("#modalSettingLia select.select2").select2({
                width: "100%"
            })
            $("#modalSettingEq select.select2").select2({
                width: "100%"
            })

            var val = []
            val['data'] = src

            console.log(val)

            console.log(hisdata)

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
