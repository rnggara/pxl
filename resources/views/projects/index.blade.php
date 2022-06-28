@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            @php
                $project_title = "<span class='text-primary font-weight-bold'>On Going</span>";
                $table_bg = "default";
                if(!empty($view)) {
                    $param = base64_decode($view);
                    if($param == "done"){
                        $project_title = "<span class='text-success font-weight-bold'>Done</span>";
                        $table_bg = "light-success";
                    } else {
                        $project_title = "<span class='text-danger font-weight-bold'>Failed</span>";
                        $table_bg = "light-danger";
                    }
                }
            @endphp
            <div class="card-title">
                <a href="{{route('marketing.project')}}" class="text-black-50">Project List - {!! $project_title !!}</a>
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addProject"><i class="fa fa-plus"></i>Add Project</button>
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 col-sm-6">
                    <div class="alert alert-info" role="alert">
                        <i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;&nbsp;This page contains all on-going projects. To see completed or cancelled projects, click the circle on the right.
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 text-right">
                    <div class="form-group btn-group">
                        <a href="{{ route('marketing.project') }}" class="btn btn-xs btn-light font-weight-bold btn-hover-primary {{ (empty($view)) ? "active" : "" }}">On Going</a>
                        <a href="{{ route('marketing.project',['view' => base64_encode('done')]) }}" class="btn btn-xs btn-light font-weight-bold btn-hover-success {{ (!empty($view) && $param == "done") ? "active" : "" }}">Done</a>
                        <a href="{{ route('marketing.project',['view' => base64_encode('failed')]) }}" class="btn btn-xs btn-light font-weight-bold btn-hover-danger {{ (!empty($view) && $param == "failed") ? "active" : "" }}">Failed</a>
                    </div>
                </div>
            </div>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link filter-project active" id="home-tab" data-toggle="tab" href="">
                        <span class="nav-icon">
                            <i class="flaticon-folder-1"></i>
                        </span>
                        <span class="nav-text">All</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link filter-project" id="profile-tab" data-toggle="tab" href="sales" aria-controls="profile">
                        <span class="nav-icon">
                            <i class="flaticon-folder-2"></i>
                        </span>
                        <span class="nav-text">Sales</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link filter-project" id="profile-tab" data-toggle="tab" href="cost" aria-controls="profile">
                        <span class="nav-icon">
                            <i class="flaticon-folder-3"></i>
                        </span>
                        <span class="nav-text">Cost</span>
                    </a>
                </li>
            </ul>
            <div class="tab-content mt-5" id="myTabContent">
                <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="home-tab">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <table class="table table-bordered table-hover display table-{{ $table_bg }} font-size-sm" id="table-project" style="margin-top: 13px !important; width: 100%;">
                            <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th nowrap="nowrap" class="text-center">Project Name</th>
                                <th nowrap="nowrap" class="text-center">Project Prefix</th>
                                <th class="text-center">Project Client</th>
                                <th nowrap="nowrap" class="text-center">Project Expiry</th>
                                <th nowrap="nowrap" class="text-center">Total FT</th>
                                <th nowrap="nowrap" class="text-center">Files</th>
                                <th nowrap="nowrap" class="text-center">Status</th>
                                <th nowrap="nowrap" class="text-center">Prognosis</th>
                                <th nowrap="nowrap" class="text-center">Equipment</th>
{{--                                <th nowrap="nowrap" class="text-center">Description</th>--}}
{{--                                <th nowrap="nowrap" class="text-center">Contract File</th>--}}
{{--                                <th nowrap="nowrap" class="text-center">Photo</th>--}}
                                <th nowrap="nowrap" class="text-center" data-priority=1>#</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($projectsall as $key => $prj_all)
                                <tr>
                                    <td align="center">{{ $key+1 }}</td>
                                    <td class="text-center"><button type="button" onclick="show_modal('{{$prj_all->id}}')" class="btn btn-link btn-xs"><i class='fa fa-search'></i>&nbsp;&nbsp;{{$prj_all->prj_name}}</button></td>
                                    <td class="text-center">
                                        {{$prj_all->prefix}} <br>
                                        @if($prj_all->category == 'cost')
                                            <label class='btn btn-sm btn-primary'>COST</label>
                                        @else
                                            <label class='btn btn-sm btn-success'>SALES</label>
                                        @endif
                                    </td>
                                    <td align="center">
                                        {{(isset($data_client[$prj_all->id_client])) ? $data_client[$prj_all->id_client]->company_name : ""}}
                                    </td>
                                    <td class="text-center">
                                        @if($prj_all->end_time == '0000-00-00' || $prj_all->end_time == null)
                                            {{'-'}}
                                        @else
                                            {{date('d F Y', strtotime($prj_all->end_time))}}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{number_format((intval($prj_all->transport)+intval($prj_all->rent)+intval($prj_all->taxi)+intval($prj_all->airtax)),2)}}
                                    </td>
                                    <td class="text-center">
                                        <a href="{{route('marketing.project.attachment', $prj_all->id)}}" class="btn btn-sm btn-primary btn-icon"><i class="fa fa-folder"></i></a>
                                    </td>
                                    <td class="text-center">
                                        @if(isset($prognosis[$prj_all->id]))
                                            <a href="{{route('marketing.prognosis.view', base64_encode($prj_all->id))}}" class="label label-warning label-inline"><i class="fa fa-eye text-white font-size-sm"></i>&nbsp;PL</a>
                                        @else
                                            {{'No PL'}}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if(isset($prognosis[$prj_all->id]))
                                            <a href="{{route('marketing.prognosis.index', $prj_all->id)}}" class="label label-md label-inline label-primary"><i class="fa fa-eye text-white font-size-sm"></i>&nbsp;View</a>
                                        @else
                                            <a href="{{route('marketing.prognosis.index', $prj_all->id)}}" class="label label-md label-inline label-primary"><i class="fa fa-pencil-alt text-white font-size-sm"></i>&nbsp;Create</a>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('marketing.project.equipments', $prj_all->id) }}" class="btn btn-default btn-sm btn-icon dttb"><i class="fa fa-cog"></i></a>
                                    </td>
{{--                                    <td class="text-center">--}}
{{--                                        <a href="#" class="btn btn-default btn-xs dttb"><i class="fa fa-list"></i></a>--}}
{{--                                    </td>--}}
{{--                                    <td class="text-center">--}}
{{--                                        <a href="#" class="btn btn-default btn-xs dttb" title="Attachment"><i class="fa fa-file"></i></a>--}}
{{--                                    </td>--}}
{{--                                    <td class="text-center">--}}
{{--                                        <a href="#" class="btn btn-default btn-xs dttb" title="Attachment"><i class="fa fa-file-image"></i></a>--}}
{{--                                    </td>--}}
                                    <td class="text-center text-nowrap">
                                        @if (empty($view))
                                            <a href="{{route('marketing.project.change_status',['type' => 'done','id'=>$prj_all->id])}}" class='btn btn-success btn-xs btn-icon dttb' alt='Project Done' title='Project Done' onclick='return confirm("This project is done? Done = Project yang sudah selesai sampai dengan selesai Demobilisasi.")' ><i class='fa fa-check'></i></a>
                                            <a href="{{route('marketing.project.change_status',['type' => 'delete','id'=>$prj_all->id])}}" class='btn btn-danger btn-xs btn-icon dttb' alt='Project Failed' title='Project Failed' onclick='return confirm("This project is failed? Fail = Project yang terhenti saat project sudah berjalan.")' ><i class='fa fa-times'></i></a>
                                        @else
                                            <a href="{{route('marketing.project.change_status',['type' => 'return','id'=>$prj_all->id])}}" class='btn btn-primary btn-xs btn-icon dttb' alt='Project Return' title='Project Return' onclick='return confirm("Return this Project? Return = Project dikembalikan ke status on going.")' ><i class='flaticon-refresh'></i></a>
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="addProject" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Project</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form class="form" method="post" action="{{route('marketing.project.store')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <br>
                                <h4>Basic Info</h4><hr>
                                <div class="form-group">
                                    <label>Project Code</label>
                                    <input type="text" class="form-control" name="prj_code" value="{{intval($cd_max)+1}}" readonly/>
                                </div>
                                <div class="form-group">
                                    <label>Project Name</label>
                                    <input type="text" class="form-control" name="prj_name" placeholder="Project Name" required/>
                                </div>
                                <div class="form-group">
                                    <label>Project prefix</label>
                                    <input type="text" class="form-control" name="prefix" placeholder="Project Name" required/>
                                </div>
                                <div class="form-group">
                                    <label>Project Category</label>
                                    <select class="form-control" name="category" required>
                                        <option value="cost">COST</option>
                                        <option value="sales">SALES</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Project Value</label>
                                    <input type="number" class="form-control" name="prj_value" placeholder="" required/>
                                </div>
                                <div class="alert alert-warning" role="alert">
                                    <i class="fa fa-exclamation-circle text-white" aria-hidden="true"></i>
                                    Please note that Project Value will be related to the amount that will be generated on invoice out
                                </div>
                                <div class="form-group">
                                    <label>Project Client</label>
                                    <select class="form-control" name="client">
                                        <option value="">Select Client</option>
                                        @foreach($clients as $key => $client)
                                            <option value="{{$client->id}}">{{$client->company_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <br>
                                <h4>Project Detail</h4><hr>
                                <div class="form-group">
                                    <label>Project</label>
                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            <input type="date" class="form-control" name="prj_start" placeholder="" required>
                                            <small><i>start</i></small>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="date" class="form-control" name="prj_end" placeholder="" required>
                                            <small><i>end</i></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Project Currency</label>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <select class="form-control" name="currency" required>
                                                @foreach($arrCurrency as $key2 => $value)
                                                    <option value="{{$key2}}">{{$key2}} - {{$value}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Project Address</label>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <textarea class="form-control" name="address" required></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Longitude</label>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control number-geo" name="longitude">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Latitude</label>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control number-geo" name="latitude">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>File Quotation List</label>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <select class="form-control" name="quotation" required>
                                                <option value="1">Q1</option>
                                                <option value="2">Q2</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Attach WO</label>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <input type='file' name='wo_attach'>
                                            <span class="form-text text-muted">Max file size is 500KB </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Agreement #</label>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" name="agreement" placeholder="" required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Agreement Title</label>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <textarea class="form-control" name="agreement_title" required></textarea>
                                        </div>
                                    </div>
                                </div>

                                <br>
                                <br>
                                <br>
                                <h4>Financial Transport</h4><hr>

                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-2 control-label">Travel</label>
                                    <div class="col-sm-12">
                                        <input type="number" value="0" class="form-control" name="transport" required placeholder="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-2 control-label">Taxi</label>
                                    <div class="col-sm-12">
                                        <input type="number" value="0" class="form-control" name="taxi" required placeholder="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-2 control-label">Car Rent</label>
                                    <div class="col-sm-12">
                                        <input type="number" value="0" class="form-control" name="rent" placeholder="" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-2 control-label">Airport Tax</label>
                                    <div class="col-sm-12">
                                        <input type="number" value="0" class="form-control" name="airtax" placeholder="" required>
                                    </div>

                                </div>
                            </div>
                        </div>
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
    <div class="modal fade" id="editProject" tabindex="-1" role="dialog" aria-labelledby="editProject" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content" id="modal-edit">
            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script src="{{ asset('assets/jquery-number/jquery.number.js') }}"></script>
    <script>
        function show_modal(x){
            $.ajax({
                url: "{{route('marketing.project.view')}}/"+x,
                type: "GET",
                cache: false,
                success: function(response){
                    $("#editProject").modal('show')
                    $("#modal-edit").html(" ")
                    $("#modal-edit").append(response)
                }
            })
        }
        $(document).ready(function () {
            var table = $('#table-project').DataTable({
                responsive: true,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            });

            $(".number-geo").number(true, 10, '.', '')

            $(".filter-project").each(function(){
                $(this).click(function () {
                    console.log($(this).attr('href'))
                    table.column(2).search($(this).attr('href')).draw()
                })
            })


            $("select.select2").select2({
                width: "100%"
            })

        });

    </script>
@endsection
