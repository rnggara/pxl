@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Purchase Request List</h3><br>

            </div>
        </div>
        <div class="card-body">
            <div class="row mb-5 mt-5">
                <div class="col-md-12">
                    <img src="{{asset('media/pr.png')}}" style="width: 35%">
                </div>
            </div>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#all">
                        <span class="nav-icon">
                            <i class="flaticon-folder-1"></i>
                        </span>
                        <span class="nav-text">PR Waiting</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#sales" aria-controls="profile">
                        <span class="nav-icon">
                            <i class="flaticon-folder-2"></i>
                        </span>
                        <span class="nav-text">PR Bank</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#cost" aria-controls="profile">
                        <span class="nav-icon">
                            <i class="flaticon-folder-3"></i>
                        </span>
                        <span class="nav-text">PR Rejected</span>
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
                                <th class="text-center">FR#</th>
                                <th class="text-center">PRE#</th>
                                <th class="text-center">PRE Date</th>
                                <th class="text-center">Request by</th>
                                <th class="text-center">Division</th>
                                <th class="text-center">Project</th>
                                <th class="text-center">Company</th>
                                <th class="text-center">Item(s)</th>
                                <th class="text-center">Director Approval</th>
                                <th class="text-center"></th>
                            </tr>
                            </thead>
                            <tbody>
                                @actionStart('pr', 'read')
                            @foreach($waitings as $key => $value)
                                <tr>
                                    <td class="text-center">{{($key+1)}}</td>
                                    <td class="text-center">{{$value->fr_num}}</td>
                                    <td class="text-center"><a href="{{route('pr.view',['id'=>$value->id])}}" class="btn btn-xs btn-link"><i class="fa fa-search"></i>{{ $value->pre_num}}</a></td>
                                    <td class="text-center">{{date('d F Y', strtotime($value->pre_date))}}</td>
                                    <td class="text-center">{{ $value->request_by}}</td>
                                    <td class="text-center">{{ $value->division}}</td>
                                    <td class="text-center">{{ $value->prj_name}}</td>
                                    <td align="center">{{$view_company[$value->company_id]->tag}}</td>
                                    <td class="text-center">{{( $value->qty != null)?$value->qty:'-'}}</td>
                                    <td class="text-center">
                                        @if($value->pre_approved_by != null && ($value->pre_approved_at != null))
                                            {{date('d F Y', strtotime( $value->pre_approved_at))}}
                                        @else
                                            @actionStart('pr', 'approvedir')
                                                <a href="{{route('pr.view',['id'=>$value->id,'code'=>base64_encode('dir_appr')])}}" class="btn btn-link"><i class="fa fa-clock"></i>waiting</a>
                                            @actionEnd
                                            <!-- <a href="{{route('pr.view',['id'=>$value->id,'code'=>base64_encode('dir_appr')])}}" class="btn btn-link"><i class="fa fa-clock"></i>waiting</a> -->
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @actionStart('pr', 'delete')
                                        <a href="{{route('fr.pr.delete',['id'=>$value->id,'code' =>'pr'])}}" class="btn btn-danger btn-xs"  title="Delete" onclick="return confirm('Are you sure you want to delete?')"><i class="fa fa-trash"></i></a>
                                        @actionEnd
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
                            <thead class="table-success">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">FR#</th>
                                <th class="text-center">PRE#</th>
                                <th class="text-center">PRE Date</th>
                                <th class="text-center">Request by</th>
                                <th class="text-center">Division</th>
                                <th class="text-center">Project</th>
                                <th class="text-center">Company</th>
                                <th class="text-center">Item(s)</th>
                                <th class="text-center">Director Approval</th>
                                <th class="text-center"></th>
                            </tr>
                            </thead>
                            <tbody>
                                @actionStart('pr', 'read')
                            @foreach($banks as $key => $value)
                                <tr>
                                    <td class="text-center">{{($key+1)}}</td>
                                    <td class="text-center">{{$value->fr_num}}</td>
                                    <td class="text-center"><a href="{{route('pr.view',['id'=>$value->id])}}" class="btn btn-xs btn-link"><i class="fa fa-search"></i>{{ $value->pre_num}}</a></td>
                                    <td class="text-center">{{date('d F Y', strtotime($value->pre_date))}}</td>
                                    <td class="text-center">{{ $value->request_by}}</td>
                                    <td class="text-center">{{ $value->division}}</td>
                                    <td class="text-center">{{ $value->prj_name}}</td>
                                    <td align="center">{{$view_company[$value->company_id]->tag}}</td>
                                    <td class="text-center">{{( $value->qty != null)?$value->qty:'-'}}</td>
                                    <td class="text-center">
                                        @if($value->pre_approved_by != null && ($value->pre_approved_at != null))
                                            {{date('d F Y', strtotime( $value->pre_approved_at))}}
                                        @else
                                            <a href="{{route('pr.view',['id'=>$value->id,'code'=>base64_encode('dir_appr')])}}" class="btn btn-link"><i class="fa fa-clock"></i>waiting</a>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @actionStart('pr', 'delete')
                                        <a href="{{route('fr.pr.delete',['id'=>$value->id,'code' =>'pr'])}}" class="btn btn-danger btn-xs"  title="Delete" onclick="return confirm('Are you sure you want to delete?')"><i class="fa fa-trash"></i></a>
                                        @actionEnd
                                    </td>
                                </tr>
                            @endforeach
                            @actionEnd
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="cost" role="tabpanel" aria-labelledby="contact-tab">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                            <thead class="table-danger">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">FR#</th>
                                <th class="text-center">PRE#</th>
                                <th class="text-center">PRE Date</th>
                                <th class="text-center">Request by</th>
                                <th class="text-center">Division</th>
                                <th class="text-center">Project</th>
                                <th class="text-center">Company</th>
                                <th class="text-center">Item(s)</th>
                                <th class="text-center">Director Reject</th>
                                <th class="text-center"></th>
                            </tr>
                            </thead>
                            <tbody>
                                @actionStart('pr', 'read')
                            @foreach($rejects as $key => $value)
                                <tr>
                                    <td class="text-center">{{($key+1)}}</td>
                                    <td class="text-center">{{$value->fr_num}}</td>
                                    <td class="text-center"><a href="{{route('pr.view',['id'=>$value->id])}}" class="btn btn-xs btn-link"><i class="fa fa-search"></i>{{ $value->pre_num}}</a></td>
                                    <td class="text-center">{{date('d F Y', strtotime($value->pre_date))}}</td>
                                    <td class="text-center">{{ $value->request_by}}</td>
                                    <td class="text-center">{{ $value->division}}</td>
                                    <td class="text-center">{{ $value->prj_name}}</td>
                                    <td align="center">{{$view_company[$value->company_id]->tag}}</td>
                                    <td class="text-center">{{( $value->qty != null)?$value->qty:'-'}}</td>
                                    <td class="text-center">
                                        @if($value->pre_rejected_by != null && ($value->pre_rejected_at != null))
                                            {{date('d F Y', strtotime( $value->pre_rejected_at))}}
                                        @else
                                            <a href="{{route('pr.view',['id'=>$value->id,'code'=>base64_encode('dir_appr')])}}" class="btn btn-link"><i class="fa fa-clock"></i>waiting</a>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @actionStart('pr', 'delete')
                                        <a href="{{route('fr.pr.delete',['id'=>$value->id,'code' =>'pr'])}}" class="btn btn-danger btn-xs"  title="Delete" onclick="return confirm('Are you sure you want to delete?')"><i class="fa fa-trash"></i></a>
                                        @actionEnd
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
            fixedHeader: true,
            fixedHeader: {
                headerOffset: 90
            }
        });
    });
</script>
@endsection
