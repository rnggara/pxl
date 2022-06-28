@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h5 class="card-title">{{$prj->prj_name}}</h5>
            <div class="card-toolbar">
                <a href="{{route('marketing.project')}}" class="btn btn-sm btn-icon btn-success"><i class="fa fa-arrow-left"></i></a>
            </div>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" id="editTaball{{$prj->id}}" role="tablist">
                @actionStart('project','access')
                <li class="nav-item">
                    <a class="nav-link active" id="detail-taball{{$prj->id}}" data-toggle="tab" href="#detailall{{$prj->id}}">
                <span class="nav-icon">
                    <i class="flaticon-list-1"></i>
                </span>
                        <span class="nav-text">Detail Information</span>
                    </a>
                </li>
                @actionEnd
                <li class="nav-item">
                    <a class="nav-link" id="detail-taball{{$prj->id}}" data-toggle="tab" href="#activity">
                <span class="nav-icon">
                    <i class="flaticon-list-1"></i>
                </span>
                        <span class="nav-text">Activity</span>
                    </a>
                </li>
                @if ($accounting_mode == 1)
                @actionStart('project','access')
                <li class="nav-item">
                    <a class="nav-link" id="associate-taball{{$prj->id}}" data-toggle="tab" href="#associateall{{$prj->id}}"
                       aria-controls="profile">
                <span class="nav-icon">
                    <i class="flaticon-user-add"></i>
                </span>
                        <span class="nav-text">Manage Participant</span>
                    </a>
                </li>
                @actionEnd
                @actionStart('project','access')
                <li class="nav-item">
                    <a class="nav-link" id="fee-taball{{$prj->id}}" data-toggle="tab" href="#feeall{{$prj->id}}"
                       aria-controls="profile">
                <span class="nav-icon">
                    <i class="fa fa-money-bill-alt"></i>
                </span>
                        <span class="nav-text">Manage Fee</span>
                    </a>
                </li>
                @actionEnd
                @actionStart('project','access')
                <li class="nav-item">
                    <a class="nav-link" id="timesheet-tab" data-toggle="tab" href="#timesheet"
                       aria-controls="profile">
                <span class="nav-icon">
                    <i class="fa fa-calendar" aria-hidden="true"></i>
                </span>
                        <span class="nav-text">Timesheet</span>
                    </a>
                </li>
                @actionEnd
                @endif
            </ul>
            <div class="tab-content mt-5" id="all{{$prj->id}}">
                <div class="tab-pane fade" id="activity" role="tabpanel" aria-labelledby="activity-tab">
                    <div class="separator separator-solid separator-border-2 separator-info mt-5 mb-5"></div>
                    <div class="row">
                        <div class="col-md-12">
                            @if(!empty($prj->type))
                            <div class="wizard wizard-2" id="wizard2" data-wizard-state="step-first" data-wizard-clickable="true">
                                <!--begin: Wizard Nav-->
                                <div class="wizard-nav border-right">
                                    <!--begin::Wizard Step 1 Nav-->
                                    <div class="wizard-steps">
                                        @foreach($stepdetail as $key => $item)
                                            <div class="wizard-step" data-wizard-type="step" {{($key == 0) ? "data-wizard-state='current'" : ""}}>
                                                <div class="wizard-wrapper">
                                                    <div class="wizard-icon">
                                                        <i class="flaticon-add-label-button"></i>
                                                    </div>
                                                    <div class="wizard-label">
                                                        <h3 class="wizard-title">{{$item->progress}}</h3>
                                                        <div class="wizard-desc">Requirements for {{$item->progress}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                    @endforeach
                                    <!--end::Wizard Step 1 Nav-->
                                    </div>
                                </div>
                                <!--end: Wizard Nav-->
                                <!--begin: Wizard Body-->
                                <div class="wizard-body">
                                    <!--begin: Wizard Form-->
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mr-5 ml-5">

                                                <!--begin: Wizard Step 1-->
                                                @foreach($stepdetail as $key => $item)
                                                    <div class="pb-5" data-wizard-type="step-content" {{($key == 0) ? "data-wizard-state='current'" : ""}}>
                                                        <h4 class="mb-10 font-weight-bold text-dark">{{$item->progress}} Requirements</h4>
                                                        @if(isset($stepreq[$item->id]))
                                                            @foreach($stepreq[$item->id] as $list)
                                                                @if($list->name == 'ud')
                                                                    @include('projects.form_upload', ['formType' => 'draft'])
                                                                @elseif($list->name == "ms")
                                                                    @include('projects.form_ms')
                                                                @elseif($list->name == "ol")
                                                                    @include('projects.form_ol')
                                                                @elseif($list->name == "su")
                                                                    @include('projects.form_su')
                                                                @elseif($list->name == "tt")
                                                                    @include('projects.form_tt')
                                                                @elseif($list->name == "ba")
                                                                    @include('projects.form_upload', ['formType' => 'ba'])
                                                                @elseif($list->name == "pe")
                                                                    @include('projects.form_upload', ['formType' => 'pe'])
                                                                @endif
                                                                <div class="separator separator-solid separator-border-2 separator-info mt-5 mb-5"></div>
                                                            @endforeach
                                                        @endif
                                                    </div>

                                            @endforeach
                                                <!--end: Wizard Step 1-->
                                            </div>
                                        </div>
                                        <!--end: Wizard-->
                                    </div>
                                    <!--end: Wizard Form-->
                                </div>
                                <!--end: Wizard Body-->
                            </div>
                            @else
                                <div class="col-md-4">
                                    <form action="{{route('marketing.project.update_category')}}" method="post">
                                        @csrf
                                        <div class="form-group">
                                            <label for="" class="col-form-label">Set Category</label>
                                            <select name="category" id="" class="form-control select2" required>
                                                <option value="">Select Category</option>
                                                @foreach($leads_cat as $cat)
                                                    <option value="{{$cat->id}}">{{$cat->category_name}} [{{$cat->category_type}}]</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <input type="hidden" name="id_project" value="{{$prj->id}}">
                                            <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                        </div>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade active show" id="detailall{{$prj->id}}" role="tabpanel" aria-labelledby="home-tab">
                    @actionStart('project','access')
                    <form class="form" method="post" action="{{route('marketing.project.update')}}"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <br>
                                <h4>Basic Info</h4>
                                <hr>
                                <div class="form-group">
                                    <label>Project Code</label>
                                    <input type="text" class="form-control" name="prj_code" value="{{$prj->prj_code}}"
                                           readonly/>
                                </div>
                                <div class="form-group">
                                    <label>Project Name</label>
                                    <input type="text" class="form-control" name="prj_name" placeholder="Project Name"
                                           value="{{$prj->prj_name}}" required/>
                                </div>
                                <div class="form-group">
                                    <label>Project prefix</label>
                                    <input type="text" class="form-control" name="prefix" value="{{$prj->prefix}}"
                                           placeholder="Project Name" required/>
                                </div>
                                <div class="form-group">
                                    <label>Project Category</label>
                                    <select class="form-control" name="category" value="{{$prj->category}}" required>
                                        <option value="cost">COST</option>
                                        <option value="sales">SALES</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Project Value</label>
                                    <input type="number" class="form-control" name="prj_value" value="{{$prj->value}}"
                                           placeholder="" required/>
                                </div>
                                <div class="alert alert-warning" role="alert">
                                    <i class="fa fa-exclamation-circle text-white" aria-hidden="true"></i>
                                    Please note that Project Value will be related to the amount that will be generated on
                                    invoice out
                                </div>
                                <div class="form-group">
                                    <label>Project Client</label>
                                    <select class="form-control select2" name="client" required>
                                        @foreach($clients as $key => $client)
                                            <option value="{{$client->id}}"
                                                    @if($client->id == $prj->id_client) selected @endif>{{$client->company_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @php
                                    $js = [];
                                    if(!empty($prj->other_client)){
                                        $js = json_decode($prj->other_client, true);
                                    }
                                @endphp
                                <div class="form-group">
                                    <label>Other Client</label>
                                    <select class="form-control select2" name="other_client[]" multiple>
                                        @foreach($clients as $key => $client)
                                            @if ($client->id != $prj->id_client)
                                                <option value="{{$client->id}}" {{ (in_array($client->id, $js)) ? "SELECTED" : "" }}>{{$client->company_name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <br>
                                <h4>Project Detail</h4>
                                <hr>
                                <div class="form-group">
                                    <label>Project</label>
                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            <input type="date" class="form-control" name="prj_start"
                                                   value="{{$prj->start_time}}" placeholder="" required>
                                            <small><i>start</i></small>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="date" class="form-control" name="prj_end" value="{{$prj->end_time}}"
                                                   placeholder="" required>
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
                                                    <option value="{{$key2}}"
                                                            @if($key2 == $prj->currency) selected @endif>{{$key2}}
                                                        - {{$value}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Project Address</label>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <textarea class="form-control tiny-text" name="address" required>{!! $prj->address !!}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Longitude</label>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control number-geo" name="longitude" value="{{ $prj->longitude }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Latitude</label>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control number-geo" name="latitude" value="{{ $prj->latitude }}">
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
                                            <input type="text" class="form-control" name="agreement"
                                                   value="{{$prj->agreement_number}}" placeholder="" required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Agreement Title</label>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                    <textarea class="form-control tiny-text" name="agreement_title"
                                              required>{!! $prj->agreement_title !!}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <br>
                                <br>
                                <br>
                                <h4>Financial Transport</h4>
                                <hr>

                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-2 control-label">Travel</label>
                                    <div class="col-sm-12">
                                        <input type="number" class="form-control" name="transport" value="{{$prj->transport}}"
                                               required placeholder="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-2 control-label">Taxi</label>
                                    <div class="col-sm-12">
                                        <input type="number" class="form-control" name="taxi" value="{{$prj->taxi}}" required
                                               placeholder="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-2 control-label">Car Rent</label>
                                    <div class="col-sm-12">
                                        <input type="number" class="form-control" name="rent" value="{{$prj->rent}}"
                                               placeholder="" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-2 control-label">Airport Tax</label>
                                    <div class="col-sm-12">
                                        <input type="hidden" name="id" value="{{$prj->id}}">
                                        <input type="number" class="form-control" name="airtax" value="{{$prj->airtax}}"
                                               placeholder="" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close
                            </button>
                            <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                                <i class="fa fa-check"></i>
                                Update
                            </button>
                        </div>
                    </form>
                    @actionEnd
                </div>
                @if($accounting_mode == 1)
                <div class="tab-pane fade" id="associateall{{$prj->id}}" role="tabpanel" aria-labelledby="profile-tab">
                    @actionStart('project','access')
                    <div class="separator separator-solid separator-border-2 separator-info mt-5 mb-5"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <form action="{{route('marketing.project.add_associatte')}}" method="post">
                                @csrf
                                <input type="hidden" name="id_project" value="{{$prj->id}}">
                                <div class="form-group">
                                    <label>Participant</label>
                                    <select name="user" class="form-control sel2" required>
                                        @foreach($users as $user)
                                            <option value="{{$user->id}}">{{$user->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Job Desc</label>
                                    <textarea name="job_desc" class="form-control tiny-text" id="" cols="30" rows="10"></textarea>
                                    {{-- <input type="text" class="form-control" name="job_desc"> --}}
                                </div>
                                <div class="row">
                                    <div class="col-md-11"></div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary" name="save" value="save">Save</button>
                                        </div>
                                    </div>
                                </div>

                            </form>
                            <div class="separator separator-solid separator-border-2 separator-info mt-5 mb-5"></div>

                            <div class="row">
                                {{--<div class="col-md-10">--}}
                                <div class="col-md-4">
                                    <span class="text-bold"><b>Participant</b></span>
                                </div>
                                <div class="col-md-8">
                                    <span class="text-bold"><b>Job Description</b></span>
                                </div>
                                {{--</div>--}}
                            </div>
                            <br><br>
                            <div class="row">
                                <div class="col-md-10">
                                    <form action="{{route('marketing.project.add_associatte')}}" method="post">
                                        @csrf
                                        {{--                                                                                {{dd($data_associates[$prj->id]}}--}}

                                        @if(isset($data_associates[$prj->id]))
                                            @foreach($data_associates[$prj->id] as $keyAs => $valAs)
                                                <div class="form-group row">
                                                    <label for=""
                                                           class="col-form-label col-md-3">{{$user_name[$valAs->id_user]}}</label>
                                                    <div class="col-md-1">
                                                        <a href="{{route('marketing.project.deleteAssoc',['id_project' => $prj->id,'id_user'=>$valAs->id_user])}}"
                                                           title="delete"
                                                           onclick='return confirm("Delete this associates from project?")'
                                                           class="btn btn-danger btn-icon btn-xs">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </div>
                                                    <input type="hidden" name="id_user[]" value="{{$valAs->id_user}}">
                                                    <div class="col-md-8">
                                                        <textarea name="job_desc[]" class="form-control tiny-text" id="" cols="20" rows="10">{!! $valAs->job_desc !!}</textarea>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif


                                        <input type="hidden" name="id_project" value="{{$prj->id}}">
                                        <div class="row">
                                            <div class="col-md-11"></div>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-success" name="edit" value="update">
                                                        Save
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @actionEnd
                </div>
                <div class="tab-pane fade" id="feeall{{$prj->id}}" role="tabpanel" aria-labelledby="profile-tab">
                    @actionStart('project','access')
                    <form action="{{route('marketing.project.submit_fee')}}" method="post">
                        @csrf

                        <div class="separator separator-solid separator-border-2 separator-info mt-5 mb-5"></div>

                        @if(isset($data_associates[$prj->id]))
                            @foreach($data_associates[$prj->id] as $keyAs => $valAs)
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-md-2">{{$user_name[$valAs->id_user]}}</label>
                                    <input type="hidden" name="id_user[]" value="{{$valAs->id_user}}">
                                    <div class="col-md-2">
                                        <select name="fee_type[]" id="fee_type{{$prj->id}}{{$valAs->id_user}}"
                                                onchange="select_feetypeassoc({{$prj->id}},{{$valAs->id_user}})"
                                                class="form-control">
                                            <option value="0">--Select Fee Option--</option>
                                            <option value="1" @if($valAs->fee_type == 1) SELECTED @endif>% Value</option>
                                            <option value="2" @if($valAs->fee_type == 2) SELECTED @endif>Fixed Amount</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="hidden" id="contract_value{{$prj->id}}{{$valAs->id_user}}"
                                               value="{{$prj->value}}">
                                        <input type="text" step="0.01" name="fee_amount_detail[]"
                                               id="fee_amount_detail{{$prj->id}}{{$valAs->id_user}}"
                                               onchange="amount_count({{$prj->id}},{{$valAs->id_user}})"
                                               value="{{(isset($valAs->percent))?$valAs->percent:0}}" class="form-control number"/>
                                    </div>
                                    <div class="col-md-1">
                                        <input type="text" readonly name=""
                                               id="fee_amount_detail_sign{{$prj->id}}{{$valAs->id_user}}"
                                               value="{{(isset($valAs->fee_type))?($valAs->fee_type == 1) ? '%' : 'IDR' :''}}"
                                               class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="fee_amount[]" id="fee_amount{{$prj->id}}{{$valAs->id_user}}"
                                               value="{{(isset($valAs->fee_amount))?$valAs->fee_amount:0}}" class="form-control number"
                                               readonly/>
                                    </div>
                                </div>
                                <script type="text/javascript">
                                    var type;

                                    function select_feetypeassoc(x, y) {
                                        type = $('#fee_type' + x + y).val();
                                    }

                                    function amount_count(x, y) {
                                        var t = $('#fee_type' + x + y).val().replaceAll(",", "");
                                        if (t == 1) {

                                            var percent = $('#fee_amount_detail' + x + y).val()
                                            var contract_val = $('#contract_value' + x + y).val()
                                            var amount = (percent * contract_val) / 100
                                            // console.log(amount)
                                            $('#fee_amount' + x + y).val(amount)
                                            $('#fee_amount_detail_sign' + x + y).val("%")
                                        }
                                        if (t == 2) {
                                            $('#fee_amount' + x + y).val($('#fee_amount_detail' + x + y).val())
                                            $('#fee_amount_detail_sign' + x + y).val("IDR")
                                        }

                                    }

                                </script>
                            @endforeach
                        @endif
                        <input type="hidden" name="id_project" value="{{$prj->id}}">
                        <div class="row">
                            <div class="col-md-11"></div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success" name="submit" value="save">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    @actionEnd
                </div>
                <div class="tab-pane fade" id="timesheet" role="tabpanel" aria-labelledby="profile-tab">
                    @actionStart('project','access')
                    <form action="{{route('marketing.project.submit_fee_time')}}" method="post">
                        @csrf

                        <div class="separator separator-solid separator-border-2 separator-info mt-5 mb-5"></div>

                        @if(isset($data_associates[$prj->id]))
                            @foreach($data_associates[$prj->id] as $keyAs => $valAs)
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-md-2">{{$user_name[$valAs->id_user]}}</label>
                                    <input type="hidden" name="id_user[]" value="{{$valAs->id_user}}">
                                    <div class="col-md-2">
                                        <select name="fee_type[]"
                                                class="form-control">
                                            <option value="1" @if($valAs->fee_time_type == 1) SELECTED @endif>Add to Invoice</option>
                                            <option value="2" @if($valAs->fee_time_type == 2) SELECTED @endif>Internal Cost</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="hidden" id="contract_value{{$prj->id}}{{$valAs->id_user}}"
                                               value="{{$prj->value}}">
                                        <input type="text" step="0.01" name="fee_amount_detail[]"
                                               onload="sum_hrs(this)"
                                               onchange="sum_hrs(this)"
                                               value="{{ $valAs->fee_time_amount }}" class="form-control number"/>
                                    </div>
                                    <div class="col-md-1">
                                        <input type="text" name="fee_hrs[]" readonly value="{{ (isset($user_hours[$valAs->id_user])) ? array_sum($user_hours[$valAs->id_user]) : 0 }} hours" class="form-control hrs">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" value="{{ $valAs->fee_time_amount * $valAs->hours }}" class="form-control number hrs-total"
                                               readonly/>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        <input type="hidden" name="id_project" value="{{$prj->id}}">
                        <div class="row">
                            <div class="col-md-11"></div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success" name="submit" value="save">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    @actionEnd
                </div>
                @endif

            </div>
        </div>
    </div>
    <div class="modal fad" id="addDocument" tabindex="-1" role="dialog" aria-labelledby="addDocument" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Document of <span id="document-title"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row custom-file">
                        <label for="" class="custom-file-label">Choose File</label>
                        <input type="file" class="custom-file-input" name="document">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="meetingModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create new meeting</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form action="{{route('marketing.projects.meetings.create')}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="" class="col-md-3 col-form-label">Subject</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="subject" placeholder="Subject">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-md-3 col-form-label">Date</label>
                            <div class="col-md-9">
                                <input type="date" class="form-control" name="date" id="dateMeeting" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-md-3 col-form-label">Start Time</label>
                            <div class="col-md-9">
                                <input type="time" class="form-control" name="time">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-md-3 col-form-label">Duration(s)</label>
                            <div class="col-md-9">
                                <input type="number" class="form-control" name="duration" placeholder="hours">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-md-3 col-form-label">Atendees</label>
                            <div class="col-md-9">
                                <input id="atendees-meeting" class="tag_input form-control tagify" name='attendees' placeholder='type attendees and press enter' />
                                <div class="mt-3">
                                    <a href="javascript:;" id="atendees-meeting-remove" class="tag_remove btn btn-sm btn-light-primary font-weight-bold">Remove Attendees</a>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-md-3 col-form-label">Description</label>
                            <div class="col-md-9">
                                <textarea name="description" class="form-control" id="" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_project" value="{{$prj->id}}">
                        <input type="hidden" name="id_step" id="id_step">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" id="btn-copy" class="btn btn-primary font-weight-bold">
                            Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="shareFileModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Share File</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" name="file" readonly class="form-control" id="shareFile" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                    <button type="button" id="btn-copy" class="btn btn-primary font-weight-bold">
                        <i class="fa fa-clipboard"></i>
                        Copy to clipboard</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="momMeeting" tabindex="-1" role="dialog" aria-labelledby="addNotes" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Upload MOM File</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{route('marketing.projects.meetings.mom')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row mb-5">
                            <div class="col-md-12">
                                <a href="" id="meeting-download">File MOM <i class="fa fa-download text-primary"></i></a>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-12 col-md-12 col-sm-12 custom-file">
                                <input type="file" name="file" class="custom-file-input" placeholder="Subject">
                                <span class="custom-file-label">Choose File</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_meeting" id="id_meeting">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="olModal" tabindex="-1" role="dialog" aria-labelledby="addNotes" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Outbox List</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{route('marketing.projects.ol.create')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        @foreach($field_ol as $key => $form)
                            <div class="form-group row">
                                <label for="" class="col-form-label col-3">{{ucwords(str_replace("_", " ", $key))}}</label>
                                <div class="col-lg-9 col-md-9 col-sm-9">
                                    <input type="{{$form}}" class="form-control" name="{{$key}}">
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_project" value="{{$prj->id}}">
                        <input type="hidden" name="id_step" id="id_step_ol">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="olEditModal" tabindex="-1" role="dialog" aria-labelledby="addNotes" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Outbox List</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{route('marketing.projects.ol.update')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        @foreach($field_ol as $key => $form)
                            <div class="form-group row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <input type="{{$form}}" class="form-control" name="$key">
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="ol-id" name="ol_id">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="readModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="read-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <p id="read-more"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="ttModal" tabindex="-1" role="dialog" aria-labelledby="addNotes" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New Time Table</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{route('marketing.projects.tt.create')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="" class="col-form-label col-md-3 text-right">Title</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" placeholder="Title" name="title">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-form-label col-md-3 text-right">Notes</label>
                            <div class="col-md-9">
                                <textarea name="notes" class="form-control" id="" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-form-label col-md-3 text-right">Due Date</label>
                            <div class="col-md-9">
                                <input type="date" class="form-control" name="due_date">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-form-label col-md-3 text-right"></label>
                            <div class="col-md-9">
                                <input type="time" class="form-control" name="due_time">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_project" value="{{$prj->id}}">
                        <input type="hidden" name="id_step" id="id_step_tt">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="suFieldModal" tabindex="-1" role="dialog" aria-labelledby="addNotes" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Summary Field</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{route('marketing.projects.su.field')}}" id="form-su">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-3">Field Name</label>
                                    <div class="col-9">
                                        <input type="text" class="form-control" id="field_name" placeholder="(name, address, phone_number, etc)">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-3">Field Type</label>
                                    <div class="col-9">
                                        <select name="" id="field_type" class="form-control select2">
                                            <option value="text">Text</option>
                                            <option value="number">Number</option>
                                            <option value="date">Date</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-12 text-right">
                                        <button type="button" class="btn btn-sm btn-icon btn-info" id="btn-add-field"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-3">Table Name</label>
                                    <div class="col-9">
                                        <input type="text" class="form-control" name="table_title" placeholder="">
                                    </div>
                                </div>
                                <table class="table table-bordered table-hover" id="table-field">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Field Name</th>
                                            <th class="text-center">Field Type</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_project" value="{{$prj->id}}">
                        <input type="hidden" name="id_step" id="id_step_su">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="button" id="btn-su-submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="suAddRowModal" tabindex="-1" role="dialog" aria-labelledby="addNotes" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content" id="addRowContent">

            </div>
        </div>
    </div>
    <div class="modal fade" id="suEditFieldModal" tabindex="-1" role="dialog" aria-labelledby="addNotes" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content" id="addRowContent">

            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <!--begin::Page Vendors(used by this page)-->
    <script src="{{asset('theme/tinymce/tinymce.min.js')}}"></script>
    <script src="{{ asset("assets/jquery-number/jquery.number.js") }}"></script>
    <!--end::Page Vendors-->
    <script>

        function sum_hrs(input){
            var val = $(input).val()
            var group = $(input).parents("div.form-group")
            var hours = $(group).find(".hrs")
            var total = $(group).find(".hrs-total")
            var hrs = hours.val().split(" ")
            var hrstotal = val * parseInt(hrs[0])
            total.val(hrstotal)
        }

        function delete_ol(x) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    location.href = "{{route('marketing.projects.ol.delete')}}/"+x
                }
            })
        }

        function edit_ol(x) {
            $("#olEditModal").modal('show')
            $.ajax({
                url: "{{route('marketing.projects.ol.get')}}/"+x,
                type: "GET",
                dataType: "json",
                cache: false,
                success: function (response) {
                    tinymce.get('ol-edit-notes').setContent(response.notes)
                    $("#ol-edit-title").val(response.title)
                    $("#ol-id").val(x)
                }
            })
        }

        function su_add(x) {
            $.ajax({
                url: "{{route('marketing.projects.su.form')}}/"+x,
                type: "get",
                cache: false,
                success: function (response) {
                    $("#addRowContent").html("")
                    $("#addRowContent").append(response)
                }
            })
        }

        function su_delete(x,y){
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{route('marketing.projects.su.delete_row')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            _token: "{{csrf_token()}}",
                            _id: x,
                            _index: y
                        },
                        cache: false,
                        success: function (response) {
                            if (response.delete === 1){
                                location.reload()
                            } else {
                                Swal.fire('Error occured', "Please contact your system administration", 'error')
                            }
                        }
                    })
                }
            })
        }

        function delete_su(x) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    location.href = "{{route('marketing.projects.su.delete')}}/"+x
                }
            })
        }

        function read_more(x) {
            $("#readModal").modal('show')
            $.ajax({
                url: "{{route('marketing.projects.ol.get')}}/"+x,
                type: "GET",
                dataType: "json",
                cache: false,
                success: function (response) {
                    $("#read-more").html(response.notes)
                    $("#read-title").html(response.title)
                }
            })
        }

        function share_button(x){
            $("#shareFile").val(x)
        }

        function set_data(x, y, z) {
            $("#"+x+".")
        }

        function show_modal(x, y) {
            $(x).val(y)
            $("#table-field > tbody").html("")
        }

        function ol_modal(x) {
            $("#id_step_ol").val(x)
        }

        function tt_modal(x) {
            $("#id_step_tt").val(x)
        }

        function tt_delete(x) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    location.href = "{{route('marketing.projects.tt.delete')}}/"+x
                }
            })
        }

        function tt_follow(btn, x) {
            var td = $(btn).parent()
            var tr = td.parent()
            $.ajax({
                url: "{{route('marketing.projects.tt.follow')}}/"+x,
                type: "get",
                dataType: "json",
                success: function(response){
                    var i = $(btn).find('i')
                    console.log(i)
                    if (response === 1){
                        i.removeClass("fa-check")
                        i.addClass("fa-times")
                        $(btn).removeClass("btn-success")
                        $(btn).addClass("btn-light-dark")
                        tr.addClass('bg-success')
                    } else {
                        i.addClass("fa-check")
                        i.removeClass("fa-times")
                        $(btn).addClass("btn-success")
                        $(btn).removeClass("btn-light-dark")
                        tr.removeClass('bg-success')
                    }
                }
            })
        }

        function delete_file(x){
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{URL::route('marketing.projects.files.delete')}}/" + x,
                        type: "get",
                        dataType: "json",
                        cache: "false",
                        success: function(response){
                            if (response.error == 0){
                                location.reload()
                            } else {
                                Swal.fire('Error occured', 'Please contact your administrator!', 'error')
                            }
                        }
                    })
                }
            })
        }

        var wizard2 = function () {
            var _wizardEl;
            var _wizard;

            // Private functions
            var initWizard = function () {
                // Initialize form wizard
                _wizard = new KTWizard(_wizardEl, {
                    startStep: 1, // initial active step number
                    clickableSteps: true // to make steps clickable this set value true and add data-wizard-clickable="true" in HTML for class="wizard" element
                });

                // Change event
            }

            return {
                // public functions
                init: function () {
                    _wizardEl = KTUtil.getById('wizard2');

                    initWizard();
                }
            };
        }()

        function meeting_modal(x, y) {
            $("#meetingModal").modal('show')
            $("#dateMeeting").val(x)
            $("#id_step").val(y)
            console.log(x)
        }

        function modal_mom(x, y, z){
            $("#momMeeting").modal('show')
            $("#id_meeting").val(x)
            if (y === 1){
                $("#meeting-download").show()
                $("#meeting-download").attr('href', "{{route('download')}}/"+z)
            } else {
                $("#meeting-download").hide()
                $("#meeting-download").attr('href', "")
            }
        }

        // Class definition
        var KTTagify = function() {

            // Private functions
            var demo1 = function(x, y) {
                var input = document.getElementById(x),
                    // init Tagify script on the above inputs
                    tagify = new Tagify(input, {
                        whitelist: [],
                        blacklist: [], // <-- passed as an attribute in this demo
                    })


                // "remove all tags" button event listener
                document.getElementById(y).addEventListener('click', tagify.removeAllTags.bind(tagify))

                // Chainable event listeners
                tagify.on('add', onAddTag)
                    .on('remove', onRemoveTag)
                    .on('input', onInput)
                    .on('edit', onTagEdit)
                    .on('invalid', onInvalidTag)
                    .on('click', onTagClick)
                    .on('dropdown:show', onDropdownShow)
                    .on('dropdown:hide', onDropdownHide)

                // tag added callback
                function onAddTag(e) {
                    console.log("onAddTag: ", e.detail);
                    console.log("original input value: ", input.value)
                    tagify.off('add', onAddTag) // exmaple of removing a custom Tagify event
                }

                // tag remvoed callback
                function onRemoveTag(e) {
                    console.log(e.detail);
                    console.log("tagify instance value:", tagify.value)
                }

                // on character(s) added/removed (user is typing/deleting)
                function onInput(e) {
                    console.log(e.detail);
                    console.log("onInput: ", e.detail);
                }

                function onTagEdit(e) {
                    console.log("onTagEdit: ", e.detail);
                }

                // invalid tag added callback
                function onInvalidTag(e) {
                    console.log("onInvalidTag: ", e.detail);
                }

                // invalid tag added callback
                function onTagClick(e) {
                    console.log(e.detail);
                    console.log("onTagClick: ", e.detail);
                }

                function onDropdownShow(e) {
                    console.log("onDropdownShow: ", e.detail)
                }

                function onDropdownHide(e) {
                    console.log("onDropdownHide: ", e.detail)
                }
            }

            return {
                // public functions
                init: function(x,y) {
                    demo1(x,y);
                }
            };
        }();

        var KTCalendarBasic = function() {

            return {
                //main function to initiate the module
                init: function(x) {
                    var todayDate = moment().startOf('day');
                    var YM = todayDate.format('YYYY-MM');
                    var YESTERDAY = todayDate.clone().subtract(1, 'day').format('YYYY-MM-DD');
                    var TODAY = todayDate.format('YYYY-MM-DD');
                    var TOMORROW = todayDate.clone().add(1, 'day').format('YYYY-MM-DD');
                    var data = JSON.parse($.ajax({
                        url: "{{route('marketing.projects.meetings.get', $prj->id)}}",
                        type: "get",
                        dataType: "json",
                        cache: false,
                        async: false
                    }).responseText)
                    console.log(data)
                    var list = []
                    for (const key in data) {
                        if (data[key].id_step == x){
                            // console.log(data[key].file_mom)
                            var row = []
                            var classBg = ""
                            var dw = 0
                            if (data[key].file_mom == null || data[key].file_mom === ""){
                                classBg = "fc-event-solid-info"
                                dw = 0
                            } else {
                                classBg = "fc-event-solid-success"
                                dw = 1
                            }
                            row.title = "Meeting "+data[key].subject
                            row.url = "Javascript:modal_mom("+data[key].id+", "+dw+", '"+data[key].file_mom+"')"
                            row.start = data[key].date
                            row.className = classBg+" fc-event-light"
                            row.description = "Start: "+data[key].time+" | Duration: "+data[key].duration+" hour(s)"
                            list.push(row)
                        }
                    }

                    var calendarEl = document.getElementById('kt_calendar_'+x);
                    var calendar = new FullCalendar.Calendar(calendarEl, {
                        plugins: [ 'bootstrap', 'interaction', 'dayGrid', 'timeGrid', 'list' ],
                        themeSystem: 'bootstrap',
                        displayEventTime : false,
                        isRTL: KTUtil.isRTL(),
                        selectable: true,
                        dateClick : function (info){
                            // window.location.href = "meetingscheduler/"+btoa(info.dateStr)
                            meeting_modal(info.dateStr, x)

                        },

                        header: {
                            left: 'prev,today',
                            center: 'title',
                            right: 'next'
                        },

                        height: 800,
                        contentHeight: 780,
                        aspectRatio: 3,  // see: https://fullcalendar.io/docs/aspectRatio

                        nowIndicator: true,
                        now: TODAY + 'T08:00:00', // just for demo

                        views: {
                            dayGridMonth: {
                                buttonText: 'month'
                            },
                            timeGridWeek: {
                                buttonText: 'week'
                            },
                            timeGridDay: {
                                buttonText: 'day'
                            }
                        },

                        defaultView: 'dayGridMonth',
                        defaultDate: TODAY,

                        editable: true,
                        eventLimit: true, // allow "more" link when too many events
                        navLinks: true,
                        events: list,

                        eventRender: function(info) {
                            var element = $(info.el);

                            if (info.event.extendedProps && info.event.extendedProps.description) {
                                if (element.hasClass('fc-day-grid-event')) {
                                    element.data('content', info.event.extendedProps.description);
                                    element.data('placement', 'top');
                                    KTApp.initPopover(element);
                                } else if (element.hasClass('fc-time-grid-event')) {
                                    element.find('.fc-title').append('<div class="fc-description">' + info.event.extendedProps.description + '</div>');
                                } else if (element.find('.fc-list-item-title').lenght !== 0) {
                                    element.find('.fc-list-item-title').append('<div class="fc-description">' + info.event.extendedProps.description + '</div>');
                                }
                            }
                        },

                    });

                    calendar.render();
                }
            };
        }();

        $(document).ready(function () {
            wizard2.init()
            @foreach($stepdetail as $key => $item)
                @if(isset($stepreq[$item->id]))
                    @foreach($stepreq[$item->id] as $list)
                        @if($list->name == "ms")
                            KTCalendarBasic.init('{{$item->id}}')
                        @endif
                    @endforeach
                @endif
            @endforeach
            KTTagify.init('atendees-meeting', 'atendees-meeting-remove')

            $(".number").number(true, 2)

            tinymce.init({
                selector : ".tiny-text",
                menubar : false
            })

            $("table.display").DataTable({
                sorting: false
            })

            $("select.select2").select2({
                width: "100%"
            })

            $("select.sel2").select2({
                width: "100%"
            })

            var field = 0;

            $("#btn-add-field").click(function(){
                var name = $("#field_name").val()
                var type = $("#field_type option:selected").val()
                var tbody = $("#table-field > tbody:last-child")
                if (name !== ""){
                    var tr = "<tr>" +
                        "<td align='center'>"+name+"<input type='hidden' name='field_name[]' value='"+name+"'></td>" +
                        "<td align='center'>"+type+"<input type='hidden' name='field_type[]' value='"+type+"'></td>" +
                        "<td align='center'><button type='button' class='btn btn-xs btn-icon btn-danger' id='btn_delete_"+name+"'><i class='fa fa-trash'></i></button></td>" +
                        "</tr>"
                    tbody.append(tr)
                    field++
                } else {
                    Swal.fire('Warning', 'Please fill the form!', 'warning')
                }
                $("#field_name").val("")
                $("#field_type").val("text").trigger("change")
                $("#field_name").focus()

                $("#btn_delete_"+name).click(function(){
                    var td = $(this).parent()
                    var tr = $(td).parent()
                    $(tr).remove()
                    field--
                })
            })

            $("#btn-su-submit").click(function(){
                if (field > 0){
                    $("#form-su").submit()
                }
            })

            $("#btn-copy").click(function(){
                var txt = document.getElementById('shareFile')
                txt.select()
                txt.setSelectionRange(0, 99999)
                document.execCommand('copy')

                var content = {}

                content.message = "copied"
                var notify = $.notify(content, {
                    type: "success",
                    allow_dismiss: true,
                    newest_on_top: false,
                    mouse_over:  false,
                    showProgressbar:  false,
                    spacing: 10,
                    timer: 500,
                    placement: {
                        from: "bottom",
                        align: "center"
                    },
                    offset: {
                        x: 30,
                        y: 30
                    },
                    delay: 500,
                    z_index: 10000,
                    animate: {
                        enter: 'animate__animated animate__bounce',
                        exit: 'animate__animated animate__bounce'
                    }

                })
            })
        })
    </script>
@endsection
