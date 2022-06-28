@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Sub Cost List</h3><br>

            </div>

        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#all">
                        <span class="nav-icon">
                            <i class="flaticon-folder-1"></i>
                        </span>
                        <span class="nav-text">Ongoing Sub Cost</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#sales" aria-controls="profile">
                        <span class="nav-icon">
                            <i class="flaticon-folder-2"></i>
                        </span>
                        <span class="nav-text">Sub Cost Bank</span>
                    </a>
                </li>
            </ul>
            <div class="tab-content mt-5" id="myTabContent">
                <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="home-tab">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                            <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-left"></th>
                                <th class="text-center">Project Code</th>
                                <th class="text-left" width="50%">Project Name</th>
                                <th class="text-right">Contract Value</th>
                                <th class="text-right">3% Value</th>
                                <th class="text-right">Total Sub Cost</th>
                                <th class="text-center">Sub Cost</th>
                            </tr>
                            </thead>
                            <tbody>
                            @actionStart('subcost','read')
                            @foreach($subcost as $key => $data)
                                <tr>
                                    <td class="text-center">{{($key+1)}}</td>
                                    <td class="text-center @if($data->category == 'cost') text-primary @else text-success @endif" >{{strtoupper($data->category)}}</td>
                                    <td class="text-center">{{$data->prj_code}}</td>
                                    <td class="text-left" width="50%"><a href="{{route('subcost.detail',['id' => $data->id])}}" class="btn btn-link btn-xs">
                                            <i class="fa fa-search"></i>  {{$data->prj_name}}</a>&nbsp;&nbsp;
                                      </td>
                                    <td class="text-right">{{number_format($data->value,2)}}</td>
                                    <td class="text-right">{{number_format(round(0.3* intval($data->value)),2)}}</td>
                                    <td class="text-right">
                                        {{(isset($sumcashidr[$data->id]))?"Rp.".number_format(array_sum($sumcashidr[$data->id]),2):"Rp. 0.00"}}
                                        <br>
                                        {{(isset($sumcashusd[$data->id]))?"$".number_format(array_sum($sumcashusd[$data->id]),2):"$ 0.00"}}
                                    </td>
                                    <td class="text-center">
                                        <a class="btn btn-warning btn-xs font-size-xs" href="{{route('subcost.done',['id' => $data->id])}}"  onclick="return confirm('Close this Subcost? You cannot edit this subcost after it closed.'); "><i class="fa fa-check"></i>&nbsp;&nbsp;Close Sub Cost</a>
                                    </td>
                                </tr>
                            @endforeach
                            @actionEnd
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="sales" role="tabpanel" aria-labelledby="profile-tab">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                            <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-left"></th>
                                <th class="text-center">Project Code</th>
                                <th class="text-left" width="50%">Project Name</th>
                                <th class="text-right">Contract Value</th>
                                <th class="text-right">3% Value</th>
                                <th class="text-right">Total Sub Cost</th>
                                <th class="text-center">Sub Cost</th>
                            </tr>
                            </thead>
                            <tbody>
                            @actionStart('subcost','read')
                            @foreach($subcost_bank as $key => $data)
                                <tr>
                                    <td class="text-center">{{($key+1)}}</td>
                                    <td class="text-center @if($data->category == 'cost') text-primary @else text-success @endif" >{{strtoupper($data->category)}}</td>
                                    <td class="text-center">{{$data->prj_code}}</td>
                                    <td class="text-left" width="50%"><a href="{{route('subcost.detail',['id' => $data->id])}}" class="btn btn-link btn-xs">
                                            <i class="fa fa-search"></i>  {{$data->prj_name}}</a>&nbsp;&nbsp;
                                    </td>
                                    <td class="text-right">{{number_format($data->value,2)}}</td>
                                    <td class="text-right">{{number_format(round(0.3* intval($data->value)),2)}}</td>
                                    <td class="text-right"></td>
                                    <td class="text-center text-success">
                                        Sub Cost Done
                                    </td>
                                </tr>
                            @endforeach
                            @actionEnd
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        $(document).ready(function(){
            $("table.display").DataTable({
                responsive: true,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            })
        })
    </script>
@endsection
