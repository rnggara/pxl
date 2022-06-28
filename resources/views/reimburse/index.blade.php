@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Reimburse</h3>
            </div>
            @actionStart('reimburse', 'create')
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addItem"><i class="fa fa-plus"></i>Add Reimburse</button>
                </div>

            </div>
            @actionEnd
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#all">
                        <span class="nav-icon">
                            <i class="flaticon-folder-1"></i>
                        </span>
                        <span class="nav-text">Reimburse List</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="recv-tab" data-toggle="tab" href="#recv">
                        <span class="nav-icon">
                            <i class="flaticon-folder-1"></i>
                        </span>
                        <span class="nav-text">Reimburse Received</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#sales" aria-controls="profile">
                        <span class="nav-icon">
                            <i class="flaticon-folder-2"></i>
                        </span>
                        <span class="nav-text">Reimburse Bank</span>
                    </a>
                </li>
            </ul>
            <div class="tab-content mt-5" id="myTabContent">
                <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="home-tab">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                            <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-left">Reimburse#</th>
                                <th class="text-left">Subject</th>
                                <th class="text-left">Project</th>
                                <th class="text-center">Division</th>
                                <th class="text-right">Cash Out</th>
                                <th class="text-center">Receive Date</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Dirut Appr.</th>
                                <th class="text-center">Finance Appr.</th>
                                <th class="text-center"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @actionStart('reimburse', 'read')
                            @foreach($reimburselists as $key => $value)

                                    <tr>
                                        <td class="text-center">{{($key+1)}}</td>
                                        <td>{{$value->id}}</td>
                                        <td class="text-left">
                                            <a href="{{route('reimburse.detail',['id' => $value->id])}}" class="btn btn-link dttb" title="{{$value->subject}}">
                                                <i class="fa fa-search"></i>&nbsp;&nbsp; {{$value->subject}}</a>
                                        </td>
                                        <td class="text-left">{{ (isset($prjname[$value->project])) ? $prjname[$value->project] : "N/A"  }}</td>
                                        <td class="text-center">
                                            {{($value->division!=null)?$value->division:'N/A'}}
                                        </td>
                                        <td class="text-right">{{$value->currency}}. {{number_format($cashin[$value->id][0],2)}}</td>


                                        <td class="text-center">{{date('d F Y', strtotime($value->input_date))}}</td>
                                        <td class="text-center">
                                            @if($value->done != null)
                                                Reimburse Done
                                            @else
                                                <a href='{{route('reimburse.getDetRA',['id' => $value->id,'who'=>base64_encode('user')])}}' class='btn btn-link'><i class='fa fa-clock'></i> Done !!</a>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($value->m_approve != null)
                                                <i class='fa fa-check'></i>
                                                {{date('d-M-Y',strtotime($value->m_approve_time))}}
                                            @else
                                            @actionStart('reimburse', 'approvedir')
                                                <a href='{{route('reimburse.getDetRA',['id' => $value->id,'who'=>base64_encode('manager')])}}'  class='btn btn-link'><i class='fa fa-clock'></i> waiting...</a>
                                                @actionEnd
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($value->approved_by != null)
                                                <i class='fa fa-clock'></i>
                                                {{date('d-M-Y',strtotime($value->approved_time))}}
                                            @else
                                                @if($value->m_approve != null)
                                                @actionStart('reimburse', 'approvedir')
                                                    <a href='{{route('reimburse.getDetRA',['id' => $value->id,'who'=>base64_encode('finance')])}}' class='btn btn-link dttb'>waiting..</a>
                                                    @actionEnd
                                                @else
                                                    waiting
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            <div class="text-block" style='margin-top:5px'>
                                                <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#editItem{{$value->id}}" title="Edit"><i class="fa fa-edit"></i></button>
                                                @actionStart('reimburse', 'delete')
                                                    <a href="{{route('reimburse.delete',['id' => $value->id])}}" class="btn btn-danger btn-xs"  title="Delete" onclick="return confirm('Are you sure you want to delete?'); "><i class="fa fa-trash"></i></a>
                                                @actionEnd
                                            </div>
                                            <div class="modal fade" id="editItem{{$value->id}}" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Edit Reimburse</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <i aria-hidden="true" class="ki ki-close"></i>
                                                            </button>
                                                        </div>
                                                        <form method="post" action="{{route('reimburse.add')}}" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <input type="hidden" name="edit" id="" value="1">
                                                                <input type="hidden" name="id" id="" value="{{$value->id}}">
                                                                <hr>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group row">
                                                                            <label class="col-md-2 col-form-label text-right">Subject</label>
                                                                            <div class="col-md-6">
                                                                                <input type="text" class="form-control" placeholder="Reimburse Subject" name="subject" value="{{$value->subject}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row" id="opt">
                                                                            <label class="col-md-2 col-form-label text-right">Project</label>
                                                                            <div class="col-md-6">
                                                                                <select name="project" id="project" class="form-control">
                                                                                    @foreach($projects as $key => $val)
                                                                                        <option value="{{$val->id}}" @if($val->id == $value->project) SELECTED @endif>{{$val->prj_name}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row">
                                                                            <label class="col-md-2 col-form-label text-right">For Division</label>
                                                                            <div class="col-md-6">
                                                                                <select name="division" id="division" class="form-control">
                                                                                    <option value="">-Choose-</option>
                                                                                    <option value="Asset" @if($value->project == 'Asset') SELECTED @endif>Asset</option>
                                                                                    <option value="Consultant" @if($value->project == 'Consultant') SELECTED @endif>Consultant</option>
                                                                                    <option value="Finance" @if($value->project == 'Finance') SELECTED @endif>Finance</option>
                                                                                    <option value="GA" @if($value->project == 'GA') SELECTED @endif>GA</option>
                                                                                    <option value="HRD" @if($value->project == 'HRD') SELECTED @endif>HRD</option>
                                                                                    <option value="IT" @if($value->project == 'IT') SELECTED @endif>IT</option>
                                                                                    <option value="Laboratory" @if($value->project == 'Laboratory') SELECTED @endif>Laboratory</option>
                                                                                    <option value="Maintenance" @if($value->project == 'Maintenance') SELECTED @endif>Maintenance</option>
                                                                                    <option value="Marketing" @if($value->project == 'Marketing') SELECTED @endif>Marketing</option>
                                                                                    <option value="Operation" @if($value->project == 'Operation') SELECTED @endif>Operation</option>
                                                                                    <option value="Procurement" @if($value->project == 'Procurement') SELECTED @endif>Procurement</option>
                                                                                    <option value="Production" @if($value->project == 'Production') SELECTED @endif>Production</option>
                                                                                    <option value="QC" @if($value->project == 'QC') SELECTED @endif>QC</option
                                                                                    ><option value="QHSSE" @if($value->project == 'QHSSE') SELECTED @endif>QHSSE</option>
                                                                                    <option value="Receiptionist" @if($value->project == 'Receiptionist') SELECTED @endif>Receiptionist</option>
                                                                                    <option value="Secretary" @if($value->project == 'Secretary') SELECTED @endif>Secretary</option>
                                                                                    <option value="Technical" @if($value->project == 'Technical') SELECTED @endif>Technical</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row" id="opt">
                                                                            <label class="col-md-2 col-form-label text-right">For Personel</label>
                                                                            <div class="col-md-6">
                                                                                <select name="for_personel" id="for_personel" class="form-control">
                                                                                    <option value="open">Open Reimburse</option>
                                                                                    @foreach($listpersons as $key => $val)
                                                                                        <option value="{{$val->username}}" @if($value->user == $val->username) SELECTED @endif>{{$val->username}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row">
                                                                            <label class="col-md-2 col-form-label text-right">Currency</label>
                                                                            <div class="col-md-6">
                                                                                <select name="currency" id="currency" class="form-control">
                                                                                    <option value="IDR" @if($value->currency == 'IDR') SELECTED @endif>IDR (Rp)</option>
                                                                                    <option value="USD" @if($value->currency == 'USD') SELECTED @endif>USD ($)</option>
                                                                                    <option value="EURO" @if($value->currency == 'EURO') SELECTED @endif>EURO (€)</option>
                                                                                </select>
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
                                        </td>
                                    </tr>

                            @endforeach
                            </tbody>
                            @actionEnd

                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="recv" role="tabpanel" aria-labelledby="home-tab">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                            <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-left">Reimburse#</th>
                                <th class="text-left">Subject</th>
                                <th class="text-left">Project</th>
                                <th class="text-center">Division</th>
                                <th class="text-right">Cash Out</th>
                                <th class="text-center">Receive Date</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Dirut Appr.</th>
                                <th class="text-center">Finance Appr.</th>
                                <th class="text-center"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @actionStart('reimburse', 'read')
                            @foreach($reimburserecv as $key => $value)

                                    <tr>
                                        <td class="text-center">{{($key+1)}}</td>
                                        <td>{{$value->id}}</td>
                                        <td class="text-left">
                                            <a href="{{route('reimburse.detail',['id' => $value->id])}}" class="btn btn-link dttb" title="{{$value->subject}}">
                                                <i class="fa fa-search"></i>&nbsp;&nbsp; {{$value->subject}}</a>
                                        </td>
                                        <td class="text-left">{{ (isset($prjname[$value->project])) ? $prjname[$value->project] : "N/A"  }}</td>
                                        <td class="text-center">
                                            {{($value->division!=null)?$value->division:'N/A'}}
                                        </td>
                                        <td class="text-right">{{$value->currency}}. {{number_format($cashin[$value->id][0],2)}}</td>


                                        <td class="text-center">{{date('d F Y', strtotime($value->input_date))}}</td>
                                        <td class="text-center">
                                            @if($value->done != null)
                                                Reimburse Done
                                            @else
                                                <a href='{{route('reimburse.getDetRA',['id' => $value->id,'who'=>base64_encode('user')])}}' class='btn btn-link'><i class='fa fa-clock'></i> Done !!</a>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($value->m_approve != null)
                                                <i class='fa fa-check'></i>
                                                {{date('d-M-Y',strtotime($value->m_approve_time))}}
                                            @else
                                                <a href='{{route('reimburse.getDetRA',['id' => $value->id,'who'=>base64_encode('manager')])}}'  class='btn btn-link'><i class='fa fa-clock'></i> waiting...</a>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($value->approved_by != null)
                                                <i class='fa fa-clock'></i>
                                                {{date('d-M-Y',strtotime($value->approved_time))}}
                                            @else
                                                @if($value->m_approve != null)
                                                    <a href='{{route('reimburse.getDetRA',['id' => $value->id,'who'=>base64_encode('finance')])}}' class='btn btn-link dttb'>waiting..</a>
                                                @else
                                                    waiting
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            <div class="text-block" style='margin-top:5px'>
                                                <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#editItem{{$value->id}}" title="Edit"><i class="fa fa-edit"></i></button>
                                                <a href="{{route('reimburse.delete',['id' => $value->id])}}" class="btn btn-danger btn-xs"  title="Delete" onclick="return confirm('Are you sure you want to delete?'); "><i class="fa fa-trash"></i></a>
                                            </div>
                                            <div class="modal fade" id="editItem{{$value->id}}" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Edit Reimburse</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <i aria-hidden="true" class="ki ki-close"></i>
                                                            </button>
                                                        </div>
                                                        <form method="post" action="{{route('reimburse.add')}}" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <input type="hidden" name="edit" id="" value="1">
                                                                <input type="hidden" name="id" id="" value="{{$value->id}}">
                                                                <hr>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group row">
                                                                            <label class="col-md-2 col-form-label text-right">Subject</label>
                                                                            <div class="col-md-6">
                                                                                <input type="text" class="form-control" placeholder="Reimburse Subject" name="subject" value="{{$value->subject}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row" id="opt">
                                                                            <label class="col-md-2 col-form-label text-right">Project</label>
                                                                            <div class="col-md-6">
                                                                                <select name="project" id="project" class="form-control">
                                                                                    @foreach($projects as $key => $val)
                                                                                        <option value="{{$val->id}}" @if($val->id == $value->project) SELECTED @endif>{{$val->prj_name}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row">
                                                                            <label class="col-md-2 col-form-label text-right">For Division</label>
                                                                            <div class="col-md-6">
                                                                                <select name="division" id="division" class="form-control">
                                                                                    <option value="">-Choose-</option>
                                                                                    <option value="Asset" @if($value->project == 'Asset') SELECTED @endif>Asset</option>
                                                                                    <option value="Consultant" @if($value->project == 'Consultant') SELECTED @endif>Consultant</option>
                                                                                    <option value="Finance" @if($value->project == 'Finance') SELECTED @endif>Finance</option>
                                                                                    <option value="GA" @if($value->project == 'GA') SELECTED @endif>GA</option>
                                                                                    <option value="HRD" @if($value->project == 'HRD') SELECTED @endif>HRD</option>
                                                                                    <option value="IT" @if($value->project == 'IT') SELECTED @endif>IT</option>
                                                                                    <option value="Laboratory" @if($value->project == 'Laboratory') SELECTED @endif>Laboratory</option>
                                                                                    <option value="Maintenance" @if($value->project == 'Maintenance') SELECTED @endif>Maintenance</option>
                                                                                    <option value="Marketing" @if($value->project == 'Marketing') SELECTED @endif>Marketing</option>
                                                                                    <option value="Operation" @if($value->project == 'Operation') SELECTED @endif>Operation</option>
                                                                                    <option value="Procurement" @if($value->project == 'Procurement') SELECTED @endif>Procurement</option>
                                                                                    <option value="Production" @if($value->project == 'Production') SELECTED @endif>Production</option>
                                                                                    <option value="QC" @if($value->project == 'QC') SELECTED @endif>QC</option
                                                                                    ><option value="QHSSE" @if($value->project == 'QHSSE') SELECTED @endif>QHSSE</option>
                                                                                    <option value="Receiptionist" @if($value->project == 'Receiptionist') SELECTED @endif>Receiptionist</option>
                                                                                    <option value="Secretary" @if($value->project == 'Secretary') SELECTED @endif>Secretary</option>
                                                                                    <option value="Technical" @if($value->project == 'Technical') SELECTED @endif>Technical</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row" id="opt">
                                                                            <label class="col-md-2 col-form-label text-right">For Personel</label>
                                                                            <div class="col-md-6">
                                                                                <select name="for_personel" id="for_personel" class="form-control">
                                                                                    <option value="open">Open Reimburse</option>
                                                                                    @foreach($listpersons as $key => $val)
                                                                                        <option value="{{$val->username}}" @if($value->user == $val->username) SELECTED @endif>{{$val->username}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row">
                                                                            <label class="col-md-2 col-form-label text-right">Currency</label>
                                                                            <div class="col-md-6">
                                                                                <select name="currency" id="currency" class="form-control">
                                                                                    <option value="IDR" @if($value->currency == 'IDR') SELECTED @endif>IDR (Rp)</option>
                                                                                    <option value="USD" @if($value->currency == 'USD') SELECTED @endif>USD ($)</option>
                                                                                    <option value="EURO" @if($value->currency == 'EURO') SELECTED @endif>EURO (€)</option>
                                                                                </select>
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
                                        </td>
                                    </tr>

                            @endforeach
                            </tbody>
                            @actionEnd

                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="sales" role="tabpanel" aria-labelledby="profile-tab">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                            <thead class="table-success">
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-left">Reimburse#</th>
                                <th class="text-left">Subject</th>
                                <th class="text-left">Project</th>
                                <th class="text-right">Division</th>
                                <th class="text-center">Cash Out</th>
                                <th class="text-center">Receive Date</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Dirut Appr.</th>
                                <th class="text-center">Finance Appr.</th>
                                <th class="text-center"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @actionStart('reimburse', 'read')
                            @foreach($reimbursebanks as $key => $value)

                                    <tr>
                                        <td class="text-center">{{($key+1)}}</td>
                                        <td>{{$value->id}}</td>
                                        <td class="text-left">
                                            <a href="{{route('reimburse.detail',['id' => $value->id])}}" class="btn btn-link dttb" title="{{$value->subject}}">
                                                <i class="fa fa-search"></i>&nbsp;&nbsp; {{$value->subject}}</a>
                                        </td>
                                        <td class="text-left">{{ (isset($prjname[$value->project])) ? $prjname[$value->project] : "N/A"  }}</td>
                                        <td class="text-center">
                                            {{($value->division!=null)?$value->division:'N/A'}}
                                        </td>
                                        <td class="text-right">{{$value->currency}}. {{number_format($cashin[$value->id][0],2)}}</td>


                                        <td class="text-center">{{date('d F Y', strtotime($value->input_date))}}</td>
                                        <td class="text-center">
                                            @if($value->done != null)
                                                Reimburse Done
                                            @else
                                                <a href='{{route('reimburse.getDetRA',['id' => $value->id,'who'=>base64_encode('user')])}}' class='btn btn-link'><i class='fa fa-clock'></i> Done !!</a>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($value->m_approve != null)
                                                <i class='fa fa-check'></i>
                                                {{date('d-M-Y',strtotime($value->m_approve_time))}}
                                            @else
                                                <a href='{{route('reimburse.getDetRA',['id' => $value->id,'who'=>base64_encode('manager')])}}'  class='btn btn-link'><i class='fa fa-clock'></i> waiting...</a>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($value->approved_by != null)
                                                <i class='fa fa-clock'></i>
                                                {{date('d-M-Y',strtotime($value->approved_time))}}
                                            @else
                                                @if($value->m_approve != null)
                                                    <a href='{{route('reimburse.getDetRA',['id' => $value->id,'who'=>base64_encode('finance')])}}' class='btn btn-link dttb'>waiting..</a>
                                                @else
                                                    waiting
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            <div class="text-block" style='margin-top:5px'>
                                                <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#editItem{{$value->id}}" title="Edit"><i class="fa fa-edit"></i></button>
                                                <a href="{{route('reimburse.delete',['id' => $value->id])}}" class="btn btn-danger btn-xs"  title="Delete" onclick="return confirm('Are you sure you want to delete?'); "><i class="fa fa-trash"></i></a>
                                            </div>
                                            <div class="modal fade" id="editItem{{$value->id}}" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Edit Reimburse</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <i aria-hidden="true" class="ki ki-close"></i>
                                                            </button>
                                                        </div>
                                                        <form method="post" action="{{route('reimburse.add')}}" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <input type="hidden" name="edit" id="" value="1">
                                                                <input type="hidden" name="id" id="" value="{{$value->id}}">
                                                                <hr>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group row">
                                                                            <label class="col-md-2 col-form-label text-right">Subject</label>
                                                                            <div class="col-md-6">
                                                                                <input type="text" class="form-control" placeholder="Reimburse Subject" name="subject" value="{{$value->subject}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row" id="opt">
                                                                            <label class="col-md-2 col-form-label text-right">Project</label>
                                                                            <div class="col-md-6">
                                                                                <select name="project" id="project" class="form-control">
                                                                                    @foreach($projects as $key => $val)
                                                                                        <option value="{{$val->id}}" @if($val->id == $value->project) SELECTED @endif>{{$val->prj_name}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row">
                                                                            <label class="col-md-2 col-form-label text-right">For Division</label>
                                                                            <div class="col-md-6">
                                                                                <select name="division" id="division" class="form-control">
                                                                                    <option value="">-Choose-</option>
                                                                                    <option value="Asset" @if($value->project == 'Asset') SELECTED @endif>Asset</option>
                                                                                    <option value="Consultant" @if($value->project == 'Consultant') SELECTED @endif>Consultant</option>
                                                                                    <option value="Finance" @if($value->project == 'Finance') SELECTED @endif>Finance</option>
                                                                                    <option value="GA" @if($value->project == 'GA') SELECTED @endif>GA</option>
                                                                                    <option value="HRD" @if($value->project == 'HRD') SELECTED @endif>HRD</option>
                                                                                    <option value="IT" @if($value->project == 'IT') SELECTED @endif>IT</option>
                                                                                    <option value="Laboratory" @if($value->project == 'Laboratory') SELECTED @endif>Laboratory</option>
                                                                                    <option value="Maintenance" @if($value->project == 'Maintenance') SELECTED @endif>Maintenance</option>
                                                                                    <option value="Marketing" @if($value->project == 'Marketing') SELECTED @endif>Marketing</option>
                                                                                    <option value="Operation" @if($value->project == 'Operation') SELECTED @endif>Operation</option>
                                                                                    <option value="Procurement" @if($value->project == 'Procurement') SELECTED @endif>Procurement</option>
                                                                                    <option value="Production" @if($value->project == 'Production') SELECTED @endif>Production</option>
                                                                                    <option value="QC" @if($value->project == 'QC') SELECTED @endif>QC</option
                                                                                    ><option value="QHSSE" @if($value->project == 'QHSSE') SELECTED @endif>QHSSE</option>
                                                                                    <option value="Receiptionist" @if($value->project == 'Receiptionist') SELECTED @endif>Receiptionist</option>
                                                                                    <option value="Secretary" @if($value->project == 'Secretary') SELECTED @endif>Secretary</option>
                                                                                    <option value="Technical" @if($value->project == 'Technical') SELECTED @endif>Technical</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row" id="opt">
                                                                            <label class="col-md-2 col-form-label text-right">For Personel</label>
                                                                            <div class="col-md-6">
                                                                                <select name="for_personel" id="for_personel" class="form-control">
                                                                                    <option value="open">Open Reimburse</option>
                                                                                    @foreach($listpersons as $key => $val)
                                                                                        <option value="{{$val->username}}" @if($value->user == $val->username) SELECTED @endif>{{$val->username}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row">
                                                                            <label class="col-md-2 col-form-label text-right">Currency</label>
                                                                            <div class="col-md-6">
                                                                                <select name="currency" id="currency" class="form-control">
                                                                                    <option value="IDR" @if($value->currency == 'IDR') SELECTED @endif>IDR (Rp)</option>
                                                                                    <option value="USD" @if($value->currency == 'USD') SELECTED @endif>USD ($)</option>
                                                                                    <option value="EURO" @if($value->currency == 'EURO') SELECTED @endif>EURO (€)</option>
                                                                                </select>
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
                                        </td>
                                    </tr>

                            @endforeach
                            </tbody>
                            @actionEnd
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addItem" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Reimburse</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{route('reimburse.add')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="add" id="" value="1">
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Subject</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="Reimburse Subject" name="subject">
                                    </div>
                                </div>
                                <div class="form-group row" id="opt">
                                    <label class="col-md-2 col-form-label text-right">Project</label>
                                    <div class="col-md-6">
                                        <select name="project" id="project" class="form-control" required="">
                                            <option value="">Select Project</option>
                                            @foreach($projects as $key => $value)
                                                <option value="{{$value->id}}">{{$value->prj_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">For Division</label>
                                    <div class="col-md-6">
                                        <select name="division" id="division" class="form-control">
                                            <option value="">-Choose-</option>
                                            <option value="Asset">Asset</option>
                                            <option value="Consultant">Consultant</option>
                                            <option value="Finance">Finance</option>
                                            <option value="GA">GA</option>
                                            <option value="HRD">HRD</option>
                                            <option value="IT">IT</option>
                                            <option value="Laboratory">Laboratory</option>
                                            <option value="Maintenance">Maintenance</option>
                                            <option value="Marketing">Marketing</option>
                                            <option value="Operation">Operation</option>
                                            <option value="Procurement">Procurement</option>
                                            <option value="Production">Production</option>
                                            <option value="QC">QC</option
                                            ><option value="QHSSE">QHSSE</option>
                                            <option value="Receiptionist">Receiptionist</option>
                                            <option value="Secretary">Secretary</option>
                                            <option value="Technical">Technical</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" id="opt">
                                    <label class="col-md-2 col-form-label text-right">For Personel</label>
                                    <div class="col-md-6">
                                        <select name="for_personel" id="for_personel" class="form-control">
                                            <option value="open">Open Reimburse</option>
                                            @foreach($listpersons as $key => $value)
                                                <option value="{{$value->username}}">{{$value->username}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Currency</label>
                                    <div class="col-md-6">
                                        <select name="currency" id="currency" class="form-control">
                                            <option value="IDR" selected="selected">IDR (Rp)</option>
                                            <option value="USD">USD ($)</option>
                                            <option value="EURO">EURO (€)</option>
                                        </select>
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
