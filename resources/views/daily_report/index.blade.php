@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                Daily Report
            </div>

            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{route('general.dr.view')}}" class="btn btn-primary" ><i class="fa fa-plus"></i>New Record</a>
                </div>
                <!--end::Button-->
            </div>

        </div>

        <div class="card-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#all">
                        <span class="nav-icon">
                            <i class="flaticon-folder-1"></i>
                        </span>
                        <span class="nav-text">All</span>
                    </a>
                </li>
                @foreach($divisions as $key => $value)
                    <li class="nav-item">
                        <a class="nav-link" id="home-tab{{$key}}" data-toggle="tab" href="#report{{$value->id}}">
                        <span class="nav-icon">
                            <i class="flaticon-folder-1"></i>
                        </span>
                            <span class="nav-text">{{$value->name}}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
            <div class="tab-content mt-5" id="myTabContent">
                <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="home-tab">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <table class="table table-bordered table-hover display font-size-sm " style="margin-top: 13px !important; width: 100%;">
                            <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th nowrap="nowrap" class="text-center">Report By</th>
                                <th nowrap="nowrap" class="text-center">Report Date</th>
                                <th nowrap="nowrap" class="text-center">Division</th>
                                <th nowrap="nowrap" class="text-center" style="width: 25%">Subject</th>
                                <th nowrap="nowrap" class="text-center">Approval</th>
                                <th nowrap="nowrap" class="text-center">#</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($report as $keyItem => $valueItem)
                                <tr>
                                    <td class="text-center">{{($keyItem+1)}}</td>
                                    <td class="text-center">{{$valueItem->create_by}}</td>
                                    <td class="text-center">{{date('d M Y', strtotime($valueItem->rpt_time))}}</td>
                                    <td class="text-center">{{(isset($div[$valueItem->rpt_wh]))?strtoupper($div[$valueItem->rpt_wh]):''}}</td>
                                    <td class="text-center"><a href="{{route('general.dr.view',['id' => $valueItem->id])}}" class="btn btn-link">{{strtoupper($valueItem->rpt_subject)}}</a></td>
                                    <td class="text-center">
                                        @if($valueItem->approve_time == null)
                                            <a href="{{route('general.dr.view',['id' => $valueItem->id,'appr' => base64_encode('appr')])}}" class="btn btn-link"><i class="fa fa-clock"></i>&nbsp;waiting</a>
                                        @else
                                            @if($valueItem->approve_time)
                                                {{$valueItem->approve_by}}
                                                <br />
                                                <em style='font-size:10px'>
                                                    {{date('d M Y',strtotime($valueItem->approve_time))}}
                                                </em>
                                            @else
                                                waiting
                                            @endif
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if(\Illuminate\Support\Facades\Auth::user()->id_rms_roles_divisions == 1)
                                            <a href="{{route('general.dr.delete',['id'=>$valueItem->id])}}" class="btn btn-danger btn-sm btn-icon" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-trash"></i></a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @foreach($divisions as $key => $value)
                    <div class="tab-pane fade" id="report{{$value->id}}" role="tabpanel" aria-labelledby="home-tab{{$key}}">
                        <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                            <table class="table table-bordered table-hover display font-size-sm " style="margin-top: 13px !important; width: 100%;">
                                <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th nowrap="nowrap" class="text-center">Report By</th>
                                    <th nowrap="nowrap" class="text-center">Report Date</th>
                                    <th nowrap="nowrap" class="text-center">Division</th>
                                    <th nowrap="nowrap" class="text-center" style="width: 25%">Subject</th>
                                    <th nowrap="nowrap" class="text-center">Approval</th>
                                    <th nowrap="nowrap" class="text-center">#</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($report as $keyItem => $valueItem)
                                    @if($valueItem->rpt_wh == $value->id)
                                        <tr>
                                            <td class="text-center">{{($keyItem+1)}}</td>
                                            <td class="text-center">{{$valueItem->create_by}}</td>
                                            <td class="text-center">{{date('d M Y', strtotime($valueItem->rpt_time))}}</td>
                                            <td class="text-center">{{(isset($div[$valueItem->rpt_wh]))?strtoupper($div[$valueItem->rpt_wh]):''}}</td>
                                            <td class="text-center"><a href="{{route('general.dr.view',['id' => $valueItem->id])}}" class="btn btn-link">{{strtoupper($valueItem->rpt_subject)}}</a></td>
                                            <td class="text-center">
                                                @if($valueItem->approve_time == null)
                                                    <a href="#" class="btn btn-link"><i class="fa fa-clock"></i>&nbsp;waiting</a>
                                                @else
                                                    @if($valueItem->approve_time)
                                                        {{$valueItem->approve_by}}
                                                        <br />
                                                        <em style='font-size:10px'>
                                                            {{date('d M Y',strtotime($valueItem->approve_time))}}
                                                        </em>
                                                    @else
                                                        waiting
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if(\Illuminate\Support\Facades\Auth::user()->id_rms_roles_divisions == 1)
                                                    <a href="#" class="btn btn-danger btn-sm btn-icon" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-trash"></i></a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach

            </div>

        </div>
    </div>
@endsection
@section('custom_script')
<script type="text/javascript">
    $("table.display").DataTable({
        fixedHeader: true,
        fixedHeader: {
            headerOffset: 90
        }
    })
</script>
@endsection
