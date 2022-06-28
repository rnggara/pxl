@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <a href="{{route('marketing.project')}}" class="text-black-50">Project List</a>
            </div>
            @actionStart('project','create')
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addProject"><i class="fa fa-plus"></i>Add Project</button>
                </div>
                <!--end::Button-->
            </div>
            @actionEnd
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 col-sm-6">
                    <div class="alert alert-info" role="alert">
                        <i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;&nbsp;This page contains all on-going projects. To see completed or cancelled projects, click the circle on the right.
                    </div>
                </div>
                <div class="col-md-5 col-sm-5">
                </div>
                <div class="col-md-1 col-sm-1">
                    <form class="form">
                        <div class="form-group row">
                            <div class="col-form-label">
                                <div class="radio-inline">

                                    <a href="{{route('marketing.project',['view' => base64_encode('done')])}}" class="btn btn-icon btn-circle btn-xs @if($view == base64_encode('done')) btn-success @else btn-default @endif" >
                                        <i class="fa fa-dot-circle"></i>
                                    </a>&nbsp;&nbsp;Done
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <a href="{{route('marketing.project',['view' => base64_encode('failed')])}}" class="btn btn-icon btn-circle btn-xs @if($view == base64_encode('failed')) btn-danger @else btn-default @endif">
                                        <i class="fa fa-dot-circle"></i>
                                    </a>&nbsp;&nbsp;Failed

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#all">
                        <span class="nav-icon">
                            <i class="flaticon-folder-1"></i>
                        </span>
                        <span class="nav-text">All</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#sales" aria-controls="profile">
                        <span class="nav-icon">
                            <i class="flaticon-folder-2"></i>
                        </span>
                        <span class="nav-text">Sales</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#cost" aria-controls="profile">
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
                        <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th></th>
                                <th nowrap="nowrap" class="text-center">Project Code</th>
                                <th nowrap="nowrap" class="text-center">Project Name</th>
                                <th nowrap="nowrap" class="text-center">Project Prefix</th>
                                <th class="text-center">Project Company</th>
                                <th nowrap="nowrap" class="text-center">Project Expiry</th>
                                <th nowrap="nowrap" class="text-center">FT</th>
                                <th nowrap="nowrap" class="text-left">SKPI <br> SKPP</th>
                                <th nowrap="nowrap" class="text-center">Status</th>
                                <th nowrap="nowrap" class="text-center">Prognosis</th>
                                <th nowrap="nowrap" class="text-center">Files</th>
                                <th nowrap="nowrap" class="text-center" data-priority=1>#</th>
                            </tr>
                            </thead>
                            <tbody>
                            @actionStart('project', 'read')
                            @foreach($projectsall as $key => $prj_all)
                                <tr>
                                    <td>{{($key+1)}}</td>
                                    <td class="text-center">
                                        @if($prj_all->category == 'cost')
                                            <label class='btn btn-sm btn-primary'>COST</label>
                                        @else
                                            <label class='btn btn-sm btn-success'>SALES</label>
                                        @endif
                                    </td>
                                    <td class="text-center">{{$prj_all->prj_code}}</td>
                                    <td class="text-center"><a href="#editProject-{{$prj_all->id}}" data-toggle="modal" class="btn btn-link btn-xs"><i class='fa fa-search'></i>&nbsp;&nbsp;{{$prj_all->prj_name}}</a></td>
                                    <div class="modal fade" id="editProject-{{$prj_all->id}}" tabindex="-1" role="dialog" aria-labelledby="editProject-{{$prj_all->id}}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Edit Project</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <i aria-hidden="true" class="ki ki-close"></i>
                                                    </button>
                                                </div>
                                                <form class="form" method="post" action="{{route('marketing.project.update')}}" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <br>
                                                                <h4>Basic Info</h4><hr>
                                                                <div class="form-group">
                                                                    <label>Project Code</label>
                                                                    <input type="text" class="form-control" name="prj_code" value="{{$prj_all->prj_code}}" readonly/>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Project Name</label>
                                                                    <input type="text" class="form-control" name="prj_name" placeholder="Project Name" value="{{$prj_all->prj_name}}" required/>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Project prefix</label>
                                                                    <input type="text" class="form-control" name="prefix" value="{{$prj_all->prefix}}" placeholder="Project Name" required/>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Project Category</label>
                                                                    <select class="form-control" id="category" name="category" value="{{$prj_all->category}}" required>
                                                                        <option value="cost">COST</option>
                                                                        <option value="sales">SALES</option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Project Value</label>
                                                                    <input type="number" class="form-control" name="prj_value" value="{{$prj_all->value}}" placeholder="" required/>
                                                                </div>
                                                                <div class="alert alert-warning" role="alert">
                                                                    <i class="fa fa-exclamation-circle text-white" aria-hidden="true"></i>
                                                                    Please note that Project Value will be related to the amount that will be generated on invoice out
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Project Client</label>
                                                                    <select class="form-control" id="category" name="client" required>
                                                                        @foreach($clients as $key => $client)
                                                                            <option value="{{$client->id}}" @if($client->id == $prj_all->id_client) selected @endif>{{$client->company_name}}</option>
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
                                                                            <input type="date" class="form-control" name="prj_start" id="prj_start" value="{{$prj_all->start_time}}" placeholder="" required>
                                                                            <small><i>start</i></small>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <input type="date" class="form-control" name="prj_end" id="prj_end" value="{{$prj_all->end_time}}" placeholder="" required>
                                                                            <small><i>end</i></small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Project Currency</label>
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12">
                                                                            <select class="form-control" id="currency" name="currency" required>
                                                                                @foreach($arrCurrency as $key2 => $value)
                                                                                    <option value="{{$key2}}" @if($key2 == $prj_all->currency) selected @endif>{{$key2}} - {{$value}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Project Address</label>
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12">
                                                                            <textarea class="form-control" name="address" required>{{$prj_all->address}}</textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>File Quotation List</label>
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12">
                                                                            <select class="form-control" id="quotation" name="quotation" required>
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
                                                                            <input type="text" class="form-control" name="agreement" value="{{$prj_all->agreement_number}}" placeholder="" required/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Agreement Title</label>
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12">
                                                                            <textarea class="form-control" name="agreement_title" required>{{$prj_all->agreement_title}}</textarea>
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
                                                                        <input type="number" class="form-control" name="transport" value="{{$prj_all->transport}}" required id="dom_transport_airport" placeholder="">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="inputEmail3" class="col-sm-2 control-label">Taxi</label>
                                                                    <div class="col-sm-12">
                                                                        <input type="number" class="form-control" name="taxi" id="dom_transport_train" value="{{$prj_all->taxi}}" required placeholder="">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="inputEmail3" class="col-sm-2 control-label">Car Rent</label>
                                                                    <div class="col-sm-12">
                                                                        <input type="number" class="form-control" name="rent" id="dom_transport_bus" value="{{$prj_all->rent}}" placeholder="" required>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="inputEmail3" class="col-sm-2 control-label">Airport Tax</label>
                                                                    <div class="col-sm-12">
                                                                        <input type="hidden" name="id" id="" value="{{$prj_all->id}}">
                                                                        <input type="number" class="form-control" name="airtax" id="airport_tax" value="{{$prj_all->airtax}}" placeholder="" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                                                        @actionStart('project', 'update')
                                                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                                                            <i class="fa fa-check"></i>
                                                            Update</button>
                                                        @actionEnd
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <td class="text-center">{{$prj_all->prefix}}</td>
                                    <td align="center">
                                        {{$view_company[$prj_all->company_id]->tag}}
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
                                    <td class="text-left">0.00 <br> 0.00</td>
                                    <td class="text-center">
                                        @if(isset($prognosis[$prj_all->id]))
                                            <a href="{{route('marketing.prognosis.view', base64_encode($prj_all->id))}}" class="label label-md label-inline label-primary"><i class="fa fa-eye text-white"></i>&nbsp;PL</a>
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
                                        <button type="button" class="btn btn-default btn-xs dttb" data-toggle="modal" data-target="#filesAll{{$prj_all->id}}"><i class="fa fa-clipboard-list"></i></button>
                                        <div class="modal fade" id="filesAll{{$prj_all->id}}" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Upload Files</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <i aria-hidden="true" class="ki ki-close"></i>
                                                        </button>
                                                    </div>
                                                    <form method="post" action="#" enctype="multipart/form-data">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{$prj_all->id}}">
                                                        <div class="modal-body">
                                                            <div class="form-group row mx-auto">
                                                                <div class="col-md-10 custom-file">
                                                                    <input type="file" class="form-control custom-file-input" name="equipment" required/>
                                                                    <label class=" custom-file-label" for="customFile">Upload File Equipment</label>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    @if($prj_all->equipment != null)
                                                                        <a href="{{route('download',$prj_all->equipment)}}" class="btn btn-sm btn-info btn-icon btn-icon-md" title="Download Equipment File"><i class="fa fa-download"></i></a>&nbsp;&nbsp;
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="form-group row mx-auto">
                                                                <div class="col-md-10 custom-file">
                                                                    <input type="file" class="form-control custom-file-input" name="description" required/>
                                                                    <label class=" custom-file-label" for="customFile">Upload File Description</label>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    @if($prj_all->description != null)
                                                                        <a href="{{route('download',$prj_all->description)}}" class="btn btn-sm btn-info btn-icon btn-icon-md" title="Download Description File"><i class="fa fa-download"></i></a>&nbsp;&nbsp;
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="form-group row mx-auto">
                                                                <div class="col-md-10 custom-file">
                                                                    <input type="file" class="form-control custom-file-input" name="contract_file" required/>
                                                                    <label class=" custom-file-label" for="customFile">Upload File Contract</label>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    @if($prj_all->contract_file != null)
                                                                        <a href="{{route('download',$prj_all->contract_file)}}" class="btn btn-sm btn-info btn-icon btn-icon-md" title="Download Contract File"><i class="fa fa-download"></i></a>&nbsp;&nbsp;
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="form-group row mx-auto">
                                                                <div class="col-md-10 custom-file">
                                                                    <input type="file" class="form-control custom-file-input" name="photo" required/>
                                                                    <label class=" custom-file-label" for="customFile">Upload File Photo</label>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    @if($prj_all->photo != null)
                                                                        <a href="{{route('download',$prj_all->photo)}}" class="btn btn-sm btn-info btn-icon btn-icon-md" title="Download Photo"><i class="fa fa-download"></i></a>&nbsp;&nbsp;
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="form-group row mx-auto">
                                                                <div class="col-md-10"></div>
                                                                <div class="col-md-2 btn-group">
                                                                    <button type="submit" class="btn btn-xs btn-light-primary"><i class="fa fa-upload"></i>Upload</button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        <a href='#' class='btn btn-success btn-xs btn-icon dttb' alt='Project Done' title='Project Done' onclick='return confirm("This project is done? Done = Project yang sudah selesai sampai dengan selesai Demobilisasi.")' ><i class='fa fa-check'></i></a>
                                        &nbsp;&nbsp;
                                        <a href='#' class='btn btn-danger btn-xs btn-icon dttb' alt='Project Failed' title='Project Failed' onclick='return confirm("This project is failed? Fail = Project yang terhenti saat project sudah berjalan.")' ><i class='fa fa-times'></i></a>
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
                                <th>#</th>
                                <th></th>
                                <th nowrap="nowrap" class="text-center">Project Code</th>
                                <th nowrap="nowrap" class="text-center">Project Name</th>
                                <th nowrap="nowrap" class="text-center">Project Prefix</th>
                                <th class="text-center">Project Company</th>
                                <th nowrap="nowrap" class="text-center">Project Expiry</th>
                                <th nowrap="nowrap" class="text-center">FT</th>
                                <th nowrap="nowrap" class="text-left">SKPI <br> SKPP</th>
                                <th nowrap="nowrap" class="text-center">Status</th>
                                <th nowrap="nowrap" class="text-center">Prognosis</th>
                                <th nowrap="nowrap" class="text-center">Files</th>
                                <th nowrap="nowrap" class="text-center" data-priority=1>#</th>
                            </tr>
                            </thead>
                            <tbody>
                            @actionStart('project', 'read')
                            @foreach($projectssales as $key => $prj_all)
                                <tr>
                                    <td>{{($key+1)}}</td>
                                    <td class="text-center">
                                        @if($prj_all->category == 'cost')
                                            <label class='btn btn-sm btn-primary'>COST</label>
                                        @else
                                            <label class='btn btn-sm btn-success'>SALES</label>
                                        @endif
                                    </td>
                                    <td class="text-center">{{$prj_all->prj_code}}</td>
                                    <td class="text-center"><a href="#editProject1-{{$prj_all->id}}" data-toggle="modal" class="btn btn-link btn-xs"><i class='fa fa-search'></i>&nbsp;&nbsp;{{$prj_all->prj_name}}</a></td>
                                    <div class="modal fade" id="editProject1-{{$prj_all->id}}" tabindex="-1" role="dialog" aria-labelledby="editProject1-{{$prj_all->id}}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Edit Project</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <i aria-hidden="true" class="ki ki-close"></i>
                                                    </button>
                                                </div>
                                                <form class="form" method="post" action="{{route('marketing.project.update')}}" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <br>
                                                                <h4>Basic Info</h4><hr>
                                                                <div class="form-group">
                                                                    <label>Project Code</label>
                                                                    <input type="text" class="form-control" name="prj_code" value="{{$prj_all->prj_code}}" readonly/>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Project Name</label>
                                                                    <input type="text" class="form-control" name="prj_name" placeholder="Project Name" value="{{$prj_all->prj_name}}" required/>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Project prefix</label>
                                                                    <input type="text" class="form-control" name="prefix" value="{{$prj_all->prefix}}" placeholder="Project Name" required/>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Project Category</label>
                                                                    <select class="form-control" id="category" name="category" value="{{$prj_all->category}}" required>
                                                                        <option value="cost">COST</option>
                                                                        <option value="sales">SALES</option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Project Value</label>
                                                                    <input type="number" class="form-control" name="prj_value" value="{{$prj_all->value}}" placeholder="" required/>
                                                                </div>
                                                                <div class="alert alert-warning" role="alert">
                                                                    <i class="fa fa-exclamation-circle text-white" aria-hidden="true"></i>
                                                                    Please note that Project Value will be related to the amount that will be generated on invoice out
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Project Client</label>
                                                                    <select class="form-control" id="category" name="client" required>
                                                                        @foreach($clients as $key => $client)
                                                                            <option value="{{$client->id}}" @if($client->id == $prj_all->id_client) selected @endif>{{$client->company_name}}</option>
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
                                                                            <input type="date" class="form-control" name="prj_start" id="prj_start" value="{{$prj_all->start_time}}" placeholder="" required>
                                                                            <small><i>start</i></small>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <input type="date" class="form-control" name="prj_end" id="prj_end" value="{{$prj_all->end_time}}" placeholder="" required>
                                                                            <small><i>end</i></small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Project Currency</label>
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12">
                                                                            <select class="form-control" id="currency" name="currency" required>
                                                                                @foreach($arrCurrency as $key2 => $value)
                                                                                    <option value="{{$key2}}" @if($key2 == $prj_all->currency) selected @endif>{{$key2}} - {{$value}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Project Address</label>
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12">
                                                                            <textarea class="form-control" name="address" required>{{$prj_all->address}}</textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>File Quotation List</label>
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12">
                                                                            <select class="form-control" id="quotation" name="quotation" required>
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
                                                                            <input type="text" class="form-control" name="agreement" value="{{$prj_all->agreement_number}}" placeholder="" required/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Agreement Title</label>
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12">
                                                                            <textarea class="form-control" name="agreement_title" required>{{$prj_all->agreement_title}}</textarea>
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
                                                                        <input type="number" class="form-control" name="transport" value="{{$prj_all->transport}}" required id="dom_transport_airport" placeholder="">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="inputEmail3" class="col-sm-2 control-label">Taxi</label>
                                                                    <div class="col-sm-12">
                                                                        <input type="number" class="form-control" name="taxi" id="dom_transport_train" value="{{$prj_all->taxi}}" required placeholder="">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="inputEmail3" class="col-sm-2 control-label">Car Rent</label>
                                                                    <div class="col-sm-12">
                                                                        <input type="number" class="form-control" name="rent" id="dom_transport_bus" value="{{$prj_all->rent}}" placeholder="" required>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="inputEmail3" class="col-sm-2 control-label">Airport Tax</label>
                                                                    <div class="col-sm-12">
                                                                        <input type="hidden" name="id" id="" value="{{$prj_all->id}}">
                                                                        <input type="number" class="form-control" name="airtax" id="airport_tax" value="{{$prj_all->airtax}}" placeholder="" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                                                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                                                            <i class="fa fa-check"></i>
                                                            Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <td class="text-center">{{$prj_all->prefix}}</td>
                                    <td align="center">
                                        {{$view_company[$prj_all->company_id]->tag}}
                                    </td>
                                    <td class="text-center">
                                        @if($prj_all->category != 'cost' && $prj_all->end_time == '0000-00-00')
                                            {{'-'}}
                                        @else
                                            {{date('d F Y', strtotime($prj_all->end_time))}}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{number_format((intval($prj_all->transport)+intval($prj_all->rent)+intval($prj_all->taxi)+intval($prj_all->airtax)),2)}}
                                    </td>
                                    <td class="text-left">0.00 <br> 0.00</td>
                                    <td class="text-center">
                                        @if(!empty($prj_all->status))
                                            {{$prj_all->status}}
                                        @else
                                            {{'No PL'}}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if(isset($prognosis[$prj_all->id]))
                                        @else
                                            <a href=""><i class="fa fa-pencil-alt"></i>Create prognosis</a>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-default btn-xs dttb" data-toggle="modal" data-target="#filesSales{{$prj_all->id}}"><i class="fa fa-clipboard-list"></i></button>
                                        <div class="modal fade" id="filesSales{{$prj_all->id}}" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Upload Files</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <i aria-hidden="true" class="ki ki-close"></i>
                                                        </button>
                                                    </div>
                                                    <form method="post" action="#" enctype="multipart/form-data">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{$prj_all->id}}">
                                                        <div class="modal-body">
                                                            <div class="form-group row mx-auto">
                                                                <div class="col-md-10 custom-file">
                                                                    <input type="file" class="form-control custom-file-input" name="equipment" required/>
                                                                    <label class=" custom-file-label" for="customFile">Upload File Equipment</label>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    @if($prj_all->equipment != null)
                                                                        <a href="{{route('download',$prj_all->equipment)}}" class="btn btn-sm btn-info btn-icon btn-icon-md" title="Download Equipment File"><i class="fa fa-download"></i></a>&nbsp;&nbsp;
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="form-group row mx-auto">
                                                                <div class="col-md-10 custom-file">
                                                                    <input type="file" class="form-control custom-file-input" name="description" required/>
                                                                    <label class=" custom-file-label" for="customFile">Upload File Description</label>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    @if($prj_all->description != null)
                                                                        <a href="{{route('download',$prj_all->description)}}" class="btn btn-sm btn-info btn-icon btn-icon-md" title="Download Description File"><i class="fa fa-download"></i></a>&nbsp;&nbsp;
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="form-group row mx-auto">
                                                                <div class="col-md-10 custom-file">
                                                                    <input type="file" class="form-control custom-file-input" name="contract_file" required/>
                                                                    <label class=" custom-file-label" for="customFile">Upload File Contract</label>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    @if($prj_all->contract_file != null)
                                                                        <a href="{{route('download',$prj_all->contract_file)}}" class="btn btn-sm btn-info btn-icon btn-icon-md" title="Download Contract File"><i class="fa fa-download"></i></a>&nbsp;&nbsp;
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="form-group row mx-auto">
                                                                <div class="col-md-10 custom-file">
                                                                    <input type="file" class="form-control custom-file-input" name="photo" required/>
                                                                    <label class=" custom-file-label" for="customFile">Upload File Photo</label>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    @if($prj_all->photo != null)
                                                                        <a href="{{route('download',$prj_all->photo)}}" class="btn btn-sm btn-info btn-icon btn-icon-md" title="Download Photo"><i class="fa fa-download"></i></a>&nbsp;&nbsp;
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="form-group row mx-auto">
                                                                <div class="col-md-10"></div>
                                                                <div class="col-md-2 btn-group">
                                                                    <button type="submit" class="btn btn-xs btn-light-primary"><i class="fa fa-upload"></i>Upload</button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    </td>

                                    <td class="text-center">
                                        <a href='#' class='btn btn-success btn-icon btn-xs dttb' alt='Project Done' title='Project Done' onclick='return confirm("This project is done? Done = Project yang sudah selesai sampai dengan selesai Demobilisasi.")' ><i class='fa fa-check'></i></a>
                                        &nbsp;&nbsp;
                                        <a href='#' class='btn btn-danger btn-icon btn-xs dttb' alt='Project Failed' title='Project Failed' onclick='return confirm("This project is failed? Fail = Project yang terhenti saat project sudah berjalan.")' ><i class='fa fa-times'></i></a>
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
                            <thead>
                            <tr>
                                <th>#</th>
                                <th></th>
                                <th nowrap="nowrap" class="text-center">Project Code</th>
                                <th nowrap="nowrap" class="text-center">Project Name</th>
                                <th nowrap="nowrap" class="text-center">Project Prefix</th>
                                <th class="text-center">Project Company</th>
                                <th nowrap="nowrap" class="text-center">Project Expiry</th>
                                <th nowrap="nowrap" class="text-center">FT</th>
                                <th nowrap="nowrap" class="text-left">SKPI <br> SKPP</th>
                                <th nowrap="nowrap" class="text-center">Status</th>
                                <th nowrap="nowrap" class="text-center">Prognosis</th>
                                <th nowrap="nowrap" class="text-center">Files</th>
                                <th nowrap="nowrap" class="text-center" data-priority=1>#</th>
                            </tr>
                            </thead>
                            <tbody>
                            @actionStart('project', 'read')
                            @foreach($projectscost as $key => $prj_all)
                                <tr>
                                    <td>{{($key+1)}}</td>
                                    <td class="text-center">
                                        @if($prj_all->category == 'cost')
                                            <label class='btn btn-sm btn-primary'>COST</label>
                                        @else
                                            <label class='btn btn-sm btn-success'>SALES</label>
                                        @endif
                                    </td>
                                    <td class="text-center">{{$prj_all->prj_code}}</td>
                                    <td class="text-center"><a href="#editProject2-{{$prj_all->id}}" data-toggle="modal" class="btn btn-link btn-xs"><i class='fa fa-search'></i>&nbsp;&nbsp;{{$prj_all->prj_name}}</a></td>
                                    <div class="modal fade" id="editProject2-{{$prj_all->id}}" tabindex="-1" role="dialog" aria-labelledby="editProject2-{{$prj_all->id}}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Edit Project</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <i aria-hidden="true" class="ki ki-close"></i>
                                                    </button>
                                                </div>
                                                <form class="form" method="post" action="{{route('marketing.project.update')}}" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <br>
                                                                <h4>Basic Info</h4><hr>
                                                                <div class="form-group">
                                                                    <label>Project Code</label>
                                                                    <input type="text" class="form-control" name="prj_code" value="{{$prj_all->prj_code}}" readonly/>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Project Name</label>
                                                                    <input type="text" class="form-control" name="prj_name" placeholder="Project Name" value="{{$prj_all->prj_name}}" required/>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Project prefix</label>
                                                                    <input type="text" class="form-control" name="prefix" value="{{$prj_all->prefix}}" placeholder="Project Name" required/>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Project Category</label>
                                                                    <select class="form-control" id="category" name="category" value="{{$prj_all->category}}" required>
                                                                        <option value="cost">COST</option>
                                                                        <option value="sales">SALES</option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Project Value</label>
                                                                    <input type="number" class="form-control" name="prj_value" value="{{$prj_all->value}}" placeholder="" required/>
                                                                </div>
                                                                <div class="alert alert-warning" role="alert">
                                                                    <i class="fa fa-exclamation-circle text-white" aria-hidden="true"></i>
                                                                    Please note that Project Value will be related to the amount that will be generated on invoice out
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Project Client</label>
                                                                    <select class="form-control" id="category" name="client" required>
                                                                        @foreach($clients as $key => $client)
                                                                            <option value="{{$client->id}}" @if($client->id == $prj_all->id_client) selected @endif>{{$client->company_name}}</option>
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
                                                                            <input type="date" class="form-control" name="prj_start" id="prj_start" value="{{$prj_all->start_time}}" placeholder="" required>
                                                                            <small><i>start</i></small>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <input type="date" class="form-control" name="prj_end" id="prj_end" value="{{$prj_all->end_time}}" placeholder="" required>
                                                                            <small><i>end</i></small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Project Currency</label>
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12">
                                                                            <select class="form-control" id="currency" name="currency" required>
                                                                                @foreach($arrCurrency as $key2 => $value)
                                                                                    <option value="{{$key2}}" @if($key2 == $prj_all->currency) selected @endif>{{$key2}} - {{$value}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Project Address</label>
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12">
                                                                            <textarea class="form-control" name="address" required>{{$prj_all->address}}</textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>File Quotation List</label>
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12">
                                                                            <select class="form-control" id="quotation" name="quotation" required>
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
                                                                            <input type="text" class="form-control" name="agreement" value="{{$prj_all->agreement_number}}" placeholder="" required/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Agreement Title</label>
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12">
                                                                            <textarea class="form-control" name="agreement_title" required>{{$prj_all->agreement_title}}</textarea>
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
                                                                        <input type="number" class="form-control" name="transport" value="{{$prj_all->transport}}" required id="dom_transport_airport" placeholder="">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="inputEmail3" class="col-sm-2 control-label">Taxi</label>
                                                                    <div class="col-sm-12">
                                                                        <input type="number" class="form-control" name="taxi" id="dom_transport_train" value="{{$prj_all->taxi}}" required placeholder="">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="inputEmail3" class="col-sm-2 control-label">Car Rent</label>
                                                                    <div class="col-sm-12">
                                                                        <input type="number" class="form-control" name="rent" id="dom_transport_bus" value="{{$prj_all->rent}}" placeholder="" required>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="inputEmail3" class="col-sm-2 control-label">Airport Tax</label>
                                                                    <div class="col-sm-12">
                                                                        <input type="hidden" name="id" id="" value="{{$prj_all->id}}">
                                                                        <input type="number" class="form-control" name="airtax" id="airport_tax" value="{{$prj_all->airtax}}" placeholder="" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                                                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                                                            <i class="fa fa-check"></i>
                                                            Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <td class="text-center">{{$prj_all->prefix}}</td>
                                    <td align="center">
                                        {{$view_company[$prj_all->company_id]->tag}}
                                    </td>
                                    <td class="text-center">
                                        @if($prj_all->category != 'cost' && $prj_all->end_time == '0000-00-00')
                                            {{'-'}}
                                        @else
                                            {{date('d F Y', strtotime($prj_all->end_time))}}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{number_format((intval($prj_all->transport)+intval($prj_all->rent)+intval($prj_all->taxi)+intval($prj_all->airtax)),2)}}
                                    </td>
                                    <td class="text-left">0.00 <br> 0.00</td>
                                    <td class="text-center">
                                        @if(!empty($prj_all->status))
                                            {{$prj_all->status}}
                                        @else
                                            {{'No PL'}}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if(isset($prognosis[$prj_all->id]))
                                        @else
                                            <a href="">Create prognosis</a>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-default btn-xs dttb" data-toggle="modal" data-target="#filesCost{{$prj_all->id}}"><i class="fa fa-clipboard-list"></i></button>
                                        <div class="modal fade" id="filesCost{{$prj_all->id}}" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Upload Files</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <i aria-hidden="true" class="ki ki-close"></i>
                                                        </button>
                                                    </div>
                                                    <form method="post" action="#" enctype="multipart/form-data">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{$prj_all->id}}">
                                                        <div class="modal-body">
                                                            <div class="form-group row mx-auto">
                                                                <div class="col-md-10 custom-file">
                                                                    <input type="file" class="form-control custom-file-input" name="equipment" required/>
                                                                    <label class=" custom-file-label" for="customFile">Upload File Equipment</label>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    @if($prj_all->equipment != null)
                                                                        <a href="{{route('download',$prj_all->equipment)}}" class="btn btn-sm btn-info btn-icon btn-icon-md" title="Download Equipment File"><i class="fa fa-download"></i></a>&nbsp;&nbsp;
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="form-group row mx-auto">
                                                                <div class="col-md-10 custom-file">
                                                                    <input type="file" class="form-control custom-file-input" name="description" required/>
                                                                    <label class=" custom-file-label" for="customFile">Upload File Description</label>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    @if($prj_all->description != null)
                                                                        <a href="{{route('download',$prj_all->description)}}" class="btn btn-sm btn-info btn-icon btn-icon-md" title="Download Description File"><i class="fa fa-download"></i></a>&nbsp;&nbsp;
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="form-group row mx-auto">
                                                                <div class="col-md-10 custom-file">
                                                                    <input type="file" class="form-control custom-file-input" name="contract_file" required/>
                                                                    <label class=" custom-file-label" for="customFile">Upload File Contract</label>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    @if($prj_all->contract_file != null)
                                                                        <a href="{{route('download',$prj_all->contract_file)}}" class="btn btn-sm btn-info btn-icon btn-icon-md" title="Download Contract File"><i class="fa fa-download"></i></a>&nbsp;&nbsp;
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="form-group row mx-auto">
                                                                <div class="col-md-10 custom-file">
                                                                    <input type="file" class="form-control custom-file-input" name="photo" required/>
                                                                    <label class=" custom-file-label" for="customFile">Upload File Photo</label>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    @if($prj_all->photo != null)
                                                                        <a href="{{route('download',$prj_all->photo)}}" class="btn btn-sm btn-info btn-icon btn-icon-md" title="Download Photo"><i class="fa fa-download"></i></a>&nbsp;&nbsp;
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="form-group row mx-auto">
                                                                <div class="col-md-10"></div>
                                                                <div class="col-md-2 btn-group">
                                                                    <button type="submit" class="btn btn-xs btn-light-primary"><i class="fa fa-upload"></i>Upload</button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        <a href='#' class='btn btn-success btn-icon btn-xs dttb' alt='Project Done' title='Project Done' onclick='return confirm("This project is done? Done = Project yang sudah selesai sampai dengan selesai Demobilisasi.")' ><i class='fa fa-check'></i></a>
                                        &nbsp;&nbsp;
                                        <a href='#' class='btn btn-danger btn-icon btn-xs dttb' alt='Project Failed' title='Project Failed' onclick='return confirm("This project is failed? Fail = Project yang terhenti saat project sudah berjalan.")' ><i class='fa fa-times'></i></a>
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
                                    <select class="form-control" id="category" name="category" required>
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
                                    <select class="form-control" id="category" name="client">
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
                                            <input type="date" class="form-control" name="prj_start" id="prj_start" placeholder="" required>
                                            <small><i>start</i></small>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="date" class="form-control" name="prj_end" id="prj_end" placeholder="" required>
                                            <small><i>end</i></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Project Currency</label>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <select class="form-control" id="currency" name="currency" required>
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
                                    <label>File Quotation List</label>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <select class="form-control" id="quotation" name="quotation" required>
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
                                        <input type="number" value="0" class="form-control" name="transport" required id="dom_transport_airport" placeholder="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-2 control-label">Taxi</label>
                                    <div class="col-sm-12">
                                        <input type="number" value="0" class="form-control" name="taxi" id="dom_transport_train" required placeholder="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-2 control-label">Car Rent</label>
                                    <div class="col-sm-12">
                                        <input type="number" value="0" class="form-control" name="rent" id="dom_transport_bus" placeholder="" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-2 control-label">Airport Tax</label>
                                    <div class="col-sm-12">
                                        <input type="number" value="0" class="form-control" name="airtax" id="airport_tax" placeholder="" required>
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
@endsection
@section('custom_script')
    <script>
        $(document).ready(function () {
            $('.display').DataTable({
                responsive: true,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            });
        });
    </script>
@endsection
