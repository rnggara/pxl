@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Purchase Evaluation Waiting</h3><br>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-5 mt-5">
                <div class="col-md-12">
                    <img src="{{asset('media/pe.png')}}" style="width: 65%">
                </div>
            </div>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#all">
                        <span class="nav-icon">
                            <i class="flaticon-folder-1"></i>
                        </span>
                        <span class="nav-text">[PE] Purchase Evaluation Waiting</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#sales" aria-controls="profile">
                        <span class="nav-icon">
                            <i class="flaticon-folder-2"></i>
                        </span>
                        <span class="nav-text">[PE] Purchase Evaluation Bank</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#cost" aria-controls="profile">
                        <span class="nav-icon">
                            <i class="flaticon-folder-3"></i>
                        </span>
                        <span class="nav-text">[PE] Purchase Evaluation Rejected</span>
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
                                <th class="text-center">PRE#</th>
                                <th class="text-center">PRE Date</th>
                                <th class="text-center">PEV#</th>
                                <th class="text-center">Request by</th>
                                <th class="text-center">Division</th>
                                <th class="text-center">Project</th>
                                <th class="text-center">Company</th>
                                <th class="text-center">Item(s)</th>
                                <th class="text-center">Input Date</th>
                                <th class="text-center">Director Approval</th>
                                <th class="text-center"></th>
                            </tr>
                            </thead>
                            <tbody>
                                @actionStart('pe', 'read')
                            @foreach($pev as $key => $value)
                                @if(empty($value->pev_approved_by) && empty($value->rejected_time))
                                    <tr>
                                        <td align="center">{{$key + 1}}</td>
                                        <td align="center">
                                            {{$value->pre_num}}
                                        </td>
                                        <td align="center">
                                            {{date('d M Y', strtotime($value->pre_date))}}
                                        </td>
                                        <td align="center">
                                            <a href="{{URL::route('pe.view', $value->id)}}" class="text-hover-danger">{{$value->pev_num}}</a>
                                        </td>
                                        <td align="center">
                                            {{$value->request_by}}
                                        </td>
                                        <td align="center">
                                            {{$value->division}}
                                        </td>
                                        <td align="center">
                                            {{(isset($pro[$value->project]))?:''}}
                                        </td>
                                        <td align="center">{{$view_company[$value->company_id]->tag}}</td>
                                        <td align="center">
                                            {{(isset($items[$value->id]))?array_sum($items[$value->id]):0}}
                                        </td>
                                        <td align="center">
                                            @if(empty($value->pev_date))
                                                <a href="{{URL::route('pe.input', $value->id)}}" class="text-hover-danger">Input <i class="fa fa-clock"></i></a>
                                            @else
                                                inputed at {{date('Y-m-d', strtotime($value->pev_date))}}
                                            @endif
                                        </td>
                                        <td align="center">
                                            @if(empty($value->pev_approved_by))
                                                <a href="{{URL::route('pe.dir_appr', $value->id)}}" class="text-hover-danger">Waiting <i class="fa fa-clock"></i></a>
                                            @else
                                                approved at {{date('Y-m-d', strtotime($value->pev_approved_at))}}
                                            @endif
                                        </td>
                                        <td align="center">
                                            @actionStart('pe', 'delete')
                                            <a href="{{route('fr.pr.delete', ['code' => 'pe', 'id' => $value->id])}}" class="btn btn-xs btn-icon btn-danger"><i class="fa fa-trash"></i></a>
                                            @actionEnd
                                        </td>
                                    </tr>
                                @endif
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
                                <th class="text-center">PRE#</th>
                                <th class="text-center">PRE Date</th>
                                <th class="text-center">PEV#</th>
                                <th class="text-center">Request by</th>
                                <th class="text-center">Division</th>
                                <th class="text-center">Project</th>
                                <th class="text-center">Company</th>
                                <th class="text-center">Item(s)</th>
                                <th class="text-center">Input Date</th>
                                <th class="text-center">Director Approval</th>
                                <th class="text-center"></th>
                            </tr>
                            </thead>
                            <tbody>
                                @actionStart('pe', 'read')
                            @foreach($pev as $key => $value)
                                @if(!empty($value->pev_approved_by))
                                    <tr>
                                        <td align="center">{{$key + 1}}</td>
                                        <td align="center">
                                            {{$value->pre_num}}
                                        </td>
                                        <td align="center">
                                            {{date('d M Y', strtotime($value->pre_date))}}
                                        </td>
                                        <td align="center">
                                            <a href="{{URL::route('pe.view', $value->id)}}" class="text-hover-danger">{{$value->pev_num}}</a>
                                        </td>
                                        <td align="center">
                                            {{$value->request_by}}
                                        </td>
                                        <td align="center">
                                            {{$value->division}}
                                        </td>
                                        <td align="center">
                                            {{(isset($pro[$value->project]))?$pro[$value->project]:''}}
                                        </td>
                                        <td align="center">{{$view_company[$value->company_id]->tag}}</td>
                                        <td align="center">
                                            {{(isset($items[$value->id]))?array_sum($items[$value->id]):'-'}}
                                        </td>
                                        <td align="center">
                                            @if(empty($value->pev_date))
                                                <a href="{{URL::route('pe.input', $value->id)}}" class="text-hover-danger">Input <i class="fa fa-clock"></i></a>
                                            @else
                                                {{date('Y-m-d', strtotime($value->pev_date))}}
                                            @endif
                                        </td>
                                        <td align="center">
                                            @if(empty($value->pev_approved_by))
                                                <a href="{{URL::route('pe.dir_appr', $value->id)}}" class="text-hover-danger">Waiting <i class="fa fa-clock"></i></a>
                                            @else
                                                approved at {{date('Y-m-d', strtotime($value->pev_approved_at))}}
                                            @endif
                                        </td>
                                        <td align="center">
                                            @actionStart('pe', 'delete')
                                            <button class="btn btn-xs btn-icon btn-danger"><i class="fa fa-trash"></i></button>
                                            @actionEnd
                                        </td>
                                    </tr>
                                @endif
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
                                <th class="text-center">PRE#</th>
                                <th class="text-center">PRE Date</th>
                                <th class="text-center">PEV#</th>
                                <th class="text-center">Request by</th>
                                <th class="text-center">Division</th>
                                <th class="text-center">Project</th>
                                <th class="text-center">Company</th>
                                <th class="text-center">Item(s)</th>
                                <th class="text-center">Input Date</th>
                                <th class="text-center">Director Approval</th>
                                <th class="text-center"></th>
                            </tr>
                            </thead>
                            <tbody>
                                @actionStart('pe', 'read')
                            @foreach($pev as $key => $value)
                                @if(!empty($value->pev_rejected_time))
                                    <tr>
                                        <td align="center">{{$key + 1}}</td>
                                        <td align="center">
                                            {{$value->pre_num}}
                                        </td>
                                        <td align="center">
                                            {{date('d M Y', strtotime($value->pre_date))}}
                                        </td>
                                        <td align="center">
                                            <a href="{{URL::route('pe.view', $value->id)}}" class="text-hover-danger">{{$value->pev_num}}</a>
                                        </td>
                                        <td align="center">
                                            {{$value->request_by}}
                                        </td>
                                        <td align="center">
                                            {{$value->division}}
                                        </td>
                                        <td align="center">
                                            {{(isset($pro[$value->project]))?$pro[$value->project]:''}}
                                        </td>
                                        <td align="center">{{$view_company[$value->company_id]->tag}}</td>
                                        <td align="center">
                                            {{(isset($items[$value->id]))?array_sum($items[$value->id]):'-'}}
                                        </td>
                                        <td align="center">
                                            @if(empty($value->pev_date))
                                                <a href="{{URL::route('pe.input', $value->id)}}" class="text-hover-danger">Input <i class="fa fa-clock"></i></a>
                                            @else
                                                {{date('Y-m-d', strtotime($value->pev_date))}}
                                            @endif
                                        </td>
                                        <td align="center">
                                            <label for="" class="text-danger">rejected</label>
                                        </td>
                                        <td align="center">
                                            @actionStart('pe', 'delete')
                                            <button class="btn btn-xs btn-icon btn-danger"><i class="fa fa-trash"></i></button>
                                            @actionEnd
                                        </td>
                                    </tr>
                                @endif
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
            })
        })
    </script>
@endsection
